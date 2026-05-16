(function () {
  'use strict';

  function dispatch(name, detail) {
    try {
      window.dispatchEvent(new CustomEvent(name, { detail: detail || {} }));
    } catch (error) {
      var event = document.createEvent('CustomEvent');
      event.initCustomEvent(name, false, false, detail || {});
      window.dispatchEvent(event);
    }
  }

  function bootReactHome() {
    var root = document.getElementById('sixtythree-headless-root');
    var shell = root ? root.querySelector('[data-react-enhance="true"]') : null;

    if (!root || !shell) {
      return;
    }

    root.setAttribute('data-react-rendered', 'seo-enhanced');
    root.setAttribute('data-react-mode', 'seo-enhancement');
    root.setAttribute('data-react-hydration', 'disabled-no-dom-replace');
    document.documentElement.classList.add('sixtythree-react-home-ready');

    // Do not hydrate or replace the SEO HTML. The server-rendered markup stays
    // exactly as delivered in view-source, so Google can read it and the legacy
    // Ajax search/menu/gallery scripts can bind to the same DOM nodes.
    if (!window.React || !window.ReactDOM) {
      dispatch('sixtythree:react-home-mounted', { root: root, mode: 'seo-dom-only' });
      return;
    }

    var mount = document.getElementById('sixtythree-react-controller');
    if (!mount) {
      mount = document.createElement('div');
      mount.id = 'sixtythree-react-controller';
      mount.hidden = true;
      mount.setAttribute('aria-hidden', 'true');
      root.appendChild(mount);
    }

    try {
      var React = window.React;
      var ReactDOM = window.ReactDOM;
      function Controller() {
        React.useEffect(function () {
          root.setAttribute('data-react-controller', 'mounted');
          dispatch('sixtythree:react-home-mounted', { root: root, mode: 'seo-enhancement' });
        }, []);
        return null;
      }
      if (ReactDOM.createRoot) {
        ReactDOM.createRoot(mount).render(React.createElement(Controller));
      } else if (ReactDOM.render) {
        ReactDOM.render(React.createElement(Controller), mount);
      }
    } catch (error) {
      root.setAttribute('data-react-controller', 'fallback');
      root.setAttribute('data-react-error', error && error.message ? error.message : 'react-controller-failed');
      dispatch('sixtythree:react-home-fallback', { root: root, error: error });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootReactHome, { once: true });
  } else {
    bootReactHome();
  }
}());

/* v1.8.4: accessible global scroll-to-top for the homepage and inner pages. */
(function () {
  'use strict';

  function initScrollTop() {
    var button = document.getElementById('sixtythreeScrollTop');
    if (!button) { return; }
    var rafPending = false;

    function updateVisibility() {
      rafPending = false;
      var scrollPosition = window.scrollY || document.documentElement.scrollTop || 0;
      var isVisible = scrollPosition > 350;
      button.classList.toggle('is-visible', isVisible);
      button.setAttribute('aria-hidden', isVisible ? 'false' : 'true');
      button.tabIndex = isVisible ? 0 : -1;
    }

    function onScroll() {
      if (rafPending) { return; }
      rafPending = true;
      if (window.requestAnimationFrame) {
        window.requestAnimationFrame(updateVisibility);
      } else {
        updateVisibility();
      }
    }

    button.addEventListener('click', function () {
      var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      try {
        window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
      } catch (error) {
        window.scrollTo(0, 0);
      }
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('pageshow', onScroll);
    updateVisibility();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollTop, { once: true });
  } else {
    initScrollTop();
  }
}());

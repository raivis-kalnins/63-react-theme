(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function cfg() {
    return window.sixtythreeWpbbCompat ||
      (window.sixtythreeTheme && window.sixtythreeTheme.wpbb) ||
      window.wpbbForm ||
      {};
  }

  var hcaptchaLoading = false;
  var hcaptchaCallbacks = [];

  function withHCaptcha(callback) {
    if (window.hcaptcha && typeof window.hcaptcha.render === 'function') {
      callback(window.hcaptcha);
      return;
    }
    hcaptchaCallbacks.push(callback);
    if (!hcaptchaLoading) {
      hcaptchaLoading = true;
      var existing = document.querySelector('script[src*="hcaptcha.com/1/api.js"]');
      if (existing) {
        existing.addEventListener('load', flushHCaptchaCallbacks, { once: true });
      } else {
        var script = document.createElement('script');
        var settings = cfg();
        var lang = settings.hcaptchaLang || 'lv';
        script.src = settings.hcaptchaApiUrl || ('https://js.hcaptcha.com/1/api.js?render=explicit&hl=' + encodeURIComponent(lang));
        script.async = true;
        script.defer = true;
        script.onload = flushHCaptchaCallbacks;
        document.head.appendChild(script);
      }
      var tries = 0;
      var timer = setInterval(function () {
        tries += 1;
        if ((window.hcaptcha && typeof window.hcaptcha.render === 'function') || tries > 60) {
          clearInterval(timer);
          flushHCaptchaCallbacks();
        }
      }, 250);
    }
  }

  function flushHCaptchaCallbacks() {
    if (!(window.hcaptcha && typeof window.hcaptcha.render === 'function')) return;
    var callbacks = hcaptchaCallbacks.splice(0, hcaptchaCallbacks.length);
    callbacks.forEach(function (callback) {
      try { callback(window.hcaptcha); } catch (e) {}
    });
  }

  function renderHCaptchaWidgets(context) {
    context = context || document;
    var widgets = context.querySelectorAll ? context.querySelectorAll('.h-captcha[data-sitekey]') : [];
    if (!widgets.length) return;
    withHCaptcha(function (api) {
      widgets.forEach(function (widget) {
        if (widget.dataset.sixtythreeHcaptchaRendered === '1') return;
        if (widget.querySelector('iframe')) {
          widget.dataset.sixtythreeHcaptchaRendered = '1';
          return;
        }
        try {
          var settings = cfg();
          var lang = widget.getAttribute('data-hl') || settings.hcaptchaLang || 'lv';
          var widgetId = api.render(widget, {
            sitekey: widget.getAttribute('data-sitekey'),
            hl: lang
          });
          widget.dataset.sixtythreeHcaptchaRendered = '1';
          widget.dataset.sixtythreeHcaptchaId = widgetId;
        } catch (e) {
          if (widget.querySelector('iframe')) {
            widget.dataset.sixtythreeHcaptchaRendered = '1';
          }
        }
      });
    });
  }

  function resetHCaptchaInForm(form) {
    if (!(window.hcaptcha && typeof window.hcaptcha.reset === 'function')) return;
    form.querySelectorAll('.h-captcha[data-sixtythree-hcaptcha-id]').forEach(function (widget) {
      try { window.hcaptcha.reset(widget.dataset.sixtythreeHcaptchaId); } catch (e) {}
    });
  }

  function closestLabel(control) {
    var wrap = control.closest('.wpbb-field, .field, .form-field, .col-12, .col-md-6, .col-md-12');
    if (wrap) {
      var label = wrap.querySelector('label');
      if (label) return label.textContent.replace('*', '').trim();
    }
    if (control.id) {
      var safeId = (window.CSS && CSS.escape) ? CSS.escape(control.id) : control.id.replace(/"/g, '\\"');
      var explicit = document.querySelector('label[for="' + safeId + '"]');
      if (explicit) return explicit.textContent.replace('*', '').trim();
    }
    return control.getAttribute('placeholder') || control.name || 'Field';
  }

  function collectFields(form) {
    var fields = [];
    var controls = form.querySelectorAll('input, textarea, select');

    controls.forEach(function (control) {
      if (!control.name || control.disabled) return;
      var type = (control.type || '').toLowerCase();

      if (['submit', 'button', 'reset', 'file'].indexOf(type) !== -1) return;
      if (control.name === 'website' || control.name === 'started_at') return;
      if (control.name.indexOf('captcha') !== -1 || control.name === 'h-captcha-response' || control.name === 'g-recaptcha-response') return;
      if ((type === 'checkbox' || type === 'radio') && !control.checked) return;

      var value = control.value || '';
      var name = control.name.replace(/\[\]$/, '');
      fields.push({
        name: name,
        label: closestLabel(control),
        value: value
      });
    });

    return fields;
  }

  function appendIfExists(formData, form, name) {
    var input = form.querySelector('[name="' + name + '"]') || document.querySelector('[name="' + name + '"]');
    if (input) formData.append(name, input.value || '');
  }

  function messageEl(form) {
    var el = form.querySelector('.wpbb-form-message');
    if (!el) {
      el = document.createElement('div');
      el.className = 'wpbb-form-message mt-3';
      el.setAttribute('aria-live', 'polite');
      form.appendChild(el);
    }
    return el;
  }

  function showMessage(form, text, ok) {
    var el = messageEl(form);
    el.textContent = text || '';
    el.classList.toggle('is-success', !!ok);
    el.classList.toggle('is-error', !ok);
  }

  function bindWpbbForm(form) {
    if (!form || form.dataset.sixtythreeWpbbBound === '1') return;
    renderHCaptchaWidgets(form);
    form.dataset.sixtythreeWpbbBound = '1';

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (event.stopImmediatePropagation) event.stopImmediatePropagation();

      var options = cfg();
      if (!form.checkValidity()) {
        if (form.reportValidity) form.reportValidity();
        showMessage(form, options.validationText || 'Please fill in all required fields correctly.', false);
        return;
      }

      var formData = new FormData();
      formData.append('action', 'wpbb_submit_form');
      formData.append('nonce', options.nonce || (window.wpbbForm && window.wpbbForm.nonce) || '');
      formData.append('fields', JSON.stringify(collectFields(form)));
      formData.append('settings', JSON.stringify({
        recipient: form.getAttribute('data-recipient') || '',
        email_subject: form.getAttribute('data-subject') || '',
        success_message: form.getAttribute('data-success') || ''
      }));

      appendIfExists(formData, form, 'website');
      appendIfExists(formData, form, 'started_at');
      appendIfExists(formData, form, 'wpbb_captcha_enabled');
      appendIfExists(formData, form, 'wpbb_captcha_provider');
      appendIfExists(formData, form, 'h-captcha-response');
      appendIfExists(formData, form, 'g-recaptcha-response');

      form.querySelectorAll('input[type="file"]').forEach(function (input) {
        if (!input.name || !input.files) return;
        Array.prototype.forEach.call(input.files, function (file) {
          formData.append(input.name, file);
        });
      });

      var submit = form.querySelector('[type="submit"]');
      var oldText = submit ? submit.textContent : '';
      if (submit) {
        submit.disabled = true;
        submit.textContent = 'Nosūta...';
      }
      showMessage(form, '', true);

      fetch(options.ajaxUrl || (window.ajaxurl || '/wp-admin/admin-ajax.php'), {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      })
        .then(function (response) {
          return response.json().catch(function () {
            throw new Error(options.error || 'Something went wrong. Please try again.');
          });
        })
        .then(function (json) {
          var msg = (json && json.data && json.data.message) || (json && json.message) || '';
          if (!json || json.success !== true) {
            throw new Error(msg || options.error || 'Something went wrong. Please try again.');
          }
          showMessage(form, msg || form.getAttribute('data-success') || 'Paldies! Sazināsimies ar jums.', true);
          form.reset();
          resetHCaptchaInForm(form);
          renderHCaptchaWidgets(form);
        })
        .catch(function (error) {
          showMessage(form, error && error.message ? error.message : (options.error || 'Something went wrong. Please try again.'), false);
          resetHCaptchaInForm(form);
          renderHCaptchaWidgets(form);
        })
        .finally(function () {
          if (submit) {
            submit.disabled = false;
            submit.textContent = oldText;
          }
        });
    }, true);
  }

  ready(function () {
    renderHCaptchaWidgets(document);
    document.querySelectorAll('form.wpbb-dynamic-form').forEach(bindWpbbForm);

    var observer = new MutationObserver(function () {
      renderHCaptchaWidgets(document);
      document.querySelectorAll('form.wpbb-dynamic-form').forEach(bindWpbbForm);
    });
    observer.observe(document.documentElement, { childList: true, subtree: true });
  });
})();


    // Hero slider
    const slides = [...document.querySelectorAll('.slide')];
    const dots = [...document.querySelectorAll('.dot')];
    let current = 0;
    function showSlide(i) {
      current = i;
      slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
      dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
    }
    dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
    setInterval(() => showSlide((current + 1) % slides.length), 5200);

    // Rent modal
    const rentModal = document.getElementById('rentModal');
    document.getElementById('openRent').addEventListener('click', () => rentModal.classList.add('open'));
    document.getElementById('closeRent').addEventListener('click', () => rentModal.classList.remove('open'));
    rentModal.addEventListener('click', (e) => {
      if (e.target === rentModal) rentModal.classList.remove('open');
        if (pirtsLightbox) closePirtsLightbox();
    });

    // Story big-image slider
    const storySlides = [...document.querySelectorAll('.story-visual-slide')];
    const storyDots = [...document.querySelectorAll('.story-dot')];
    function showStory(index) {
      storySlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
      storyDots.forEach((dot, i) => dot.classList.toggle('active', i === index));
    }
    storyDots.forEach(btn => {
      btn.addEventListener('click', () => showStory(Number(btn.dataset.storyTarget)));
    });


    // Pirts clean native gallery slider
    const pirtsSlides = [...document.querySelectorAll('.clean-gallery-slide')];
    const pirtsThumbs = [...document.querySelectorAll('.clean-gallery-thumb')];
    function showPirts(index) {
      pirtsSlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
      pirtsThumbs.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
    }
    pirtsThumbs.forEach(btn => {
      btn.addEventListener('click', () => showPirts(Number(btn.dataset.pirtsTarget)));
    });


    // Pirts gallery lightbox
    const pirtsLightbox = document.getElementById('pirtsLightbox');
    const pirtsLightboxImg = document.getElementById('pirtsLightboxImg');
    const pirtsLightboxClose = document.getElementById('pirtsLightboxClose');
    const pirtsLightboxPrev = document.getElementById('pirtsLightboxPrev');
    const pirtsLightboxNext = document.getElementById('pirtsLightboxNext');
    let pirtsLightboxIndex = 0;

    function getPirtsSlideUrl(index) {
      const style = pirtsSlides[index]?.style.backgroundImage || '';
      return style.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
    }

    function openPirtsLightbox(index) {
      pirtsLightboxIndex = index;
      pirtsLightboxImg.src = getPirtsSlideUrl(index);
      pirtsLightbox.classList.add('open');
      pirtsLightbox.setAttribute('aria-hidden', 'false');
    }

    function closePirtsLightbox() {
      pirtsLightbox.classList.remove('open');
      pirtsLightbox.setAttribute('aria-hidden', 'true');
      pirtsLightboxImg.removeAttribute('src');
    }

    function movePirtsLightbox(direction) {
      pirtsLightboxIndex = (pirtsLightboxIndex + direction + pirtsSlides.length) % pirtsSlides.length;
      showPirts(pirtsLightboxIndex);
      pirtsLightboxImg.src = getPirtsSlideUrl(pirtsLightboxIndex);
    }

    pirtsSlides.forEach((slide, index) => {
      slide.addEventListener('click', () => openPirtsLightbox(index));
    });
    pirtsLightboxClose.addEventListener('click', closePirtsLightbox);
    pirtsLightbox.addEventListener('click', (e) => {
      if (e.target === pirtsLightbox) closePirtsLightbox();
    });
    pirtsLightboxPrev.addEventListener('click', () => movePirtsLightbox(-1));
    pirtsLightboxNext.addEventListener('click', () => movePirtsLightbox(1));

    // Offcanvas
    const overlay = document.getElementById('overlay');
    const offcanvas = document.getElementById('offcanvas');
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    function openDrawer() {
      overlay.classList.add('open');
      offcanvas.classList.add('open');
      if (openMenu) {
        openMenu.classList.add('is-open');
        openMenu.setAttribute('aria-label', 'Aizvērt izvēlni');
        openMenu.setAttribute('aria-expanded', 'true');
      }
    }
    function closeDrawer() {
      overlay.classList.remove('open');
      offcanvas.classList.remove('open');
      if (openMenu) {
        openMenu.classList.remove('is-open');
        openMenu.setAttribute('aria-label', 'Atvērt izvēlni');
        openMenu.setAttribute('aria-expanded', 'false');
      }
    }
    function toggleDrawer() {
      if (offcanvas.classList.contains('open')) closeDrawer();
      else openDrawer();
    }
    if (openMenu) {
      openMenu.setAttribute('aria-expanded', 'false');
      openMenu.addEventListener('click', toggleDrawer);
    }
    if (closeMenu) closeMenu.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    offcanvas.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));

    // Search modal
    const searchModal = document.getElementById('searchModal');
    const openSearchDesktop = document.getElementById('openSearchDesktop');
    const openSearchMobile = document.getElementById('openSearchMobile');
    const closeSearch = document.getElementById('closeSearch');
    function showSearch() {
      searchModal.classList.add('open');
      closeDrawer();
    }
    function hideSearch() {
      searchModal.classList.remove('open');
    }
    if (openSearchDesktop) openSearchDesktop.addEventListener('click', showSearch);
    if (openSearchMobile) openSearchMobile.addEventListener('click', showSearch);
    closeSearch.addEventListener('click', hideSearch);
    searchModal.addEventListener('click', (e) => {
      if (e.target === searchModal) hideSearch();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        hideSearch();
        closeDrawer();
        rentModal.classList.remove('open');
      }
    });
  

/* 63.lv BBuilder compatibility hooks */
window.sixtythreeTheme = window.sixtythreeTheme || {};
window.sixtythreeTheme.version = '1.0.0';
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('[data-wpbb-lightbox], .clean-gallery-slide, .story-visual-slide').forEach(function(el){
    el.setAttribute('data-bbuilder-lightbox', el.getAttribute('data-bbuilder-lightbox') || 'true');
  });
});
(function(wp){
  if (!wp || !wp.element) return;
  var root = document.querySelector('[data-sixtythree-headless]');
  if (root) root.setAttribute('data-react-ready', 'true');
})(window.wp);


/* 63.lv booking calendar + form integration */
document.addEventListener('DOMContentLoaded', function(){
  var selectedDate = '';
  var selectedTime = '';
  document.querySelectorAll('.booking-card .cal-date').forEach(function(day){
    day.setAttribute('role', 'button');
    day.setAttribute('tabindex', '0');

    function choose(){
      if (day.classList.contains('booked')) return;
      document.querySelectorAll('.booking-card .cal-date').forEach(function(d){ d.classList.remove('selected'); });
      day.classList.add('selected');
      selectedDate = (day.querySelector('span') ? day.querySelector('span').textContent : '').trim();
      selectedTime = (day.querySelector('small') ? day.querySelector('small').textContent : '').trim();

      var bookingCard = day.closest('.booking-card');
      if (bookingCard) {
        bookingCard.setAttribute('data-selected-date', selectedDate);
        bookingCard.setAttribute('data-selected-time', selectedTime);
      }

      var serviceInputs = document.querySelectorAll('input[name="service"], select[name="service"]');
      serviceInputs.forEach(function(input){
        var value = 'Pirts zona — Maijs ' + selectedDate + ' / ' + selectedTime;
        if (input.tagName.toLowerCase() === 'select') {
          Array.prototype.forEach.call(input.options, function(opt){
            if (opt.text.toLowerCase().indexOf('pirts') !== -1) input.value = opt.value;
          });
        } else {
          input.value = value;
        }
      });

      var msg = document.querySelector('textarea[name="message"]');
      if (msg && selectedDate) {
        var line = 'Vēlos rezervēt pirts laiku: Maijs ' + selectedDate + ' (' + selectedTime + ').';
        if (msg.value.indexOf('Vēlos rezervēt pirts laiku:') === -1) {
          msg.value = line + (msg.value ? '\n\n' + msg.value : '');
        } else {
          msg.value = msg.value.replace(/Vēlos rezervēt pirts laiku:.*?(\n|$)/, line + '\n');
        }
      }
    }

    day.addEventListener('click', choose);
    day.addEventListener('keydown', function(e){
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        choose();
      }
    });
  });

  document.querySelectorAll('.booking-actions a[href="#cta"], .pirts-side-actions a[href="#cta"]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var active = document.querySelector('.booking-card .cal-date.selected');
      if (active) active.click();
      setTimeout(function(){
        var first = document.querySelector('#cta input, #cta textarea, #cta select');
        if (first) first.focus({preventScroll:true});
      }, 500);
    });
  });
});


/* 63.lv language switch fallback */
document.addEventListener('DOMContentLoaded', function(){
  var pathLang = (location.pathname.match(/^\/(en|ru)(\/|$)/) || [,'lv'])[1] || 'lv';
  document.querySelectorAll('.lang-switch a, .mobile-lang a').forEach(function(a){
    var lang = a.getAttribute('data-lang');
    if (lang === pathLang) a.classList.add('active');
    else a.classList.remove('active');
  });
});

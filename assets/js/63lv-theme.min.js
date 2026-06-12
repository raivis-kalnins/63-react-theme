// Hero slider
(function(){
  const slides = [...document.querySelectorAll('.slide')];
  const dots = [...document.querySelectorAll('.dot')];
  let current = 0;

  function showSlide(i) {
    current = i;
    slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
    dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
  }

  if (slides.length && dots.length) {
    dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
    setInterval(() => showSlide((current + 1) % slides.length), 5200);
  }

  // Rent offer image modal
  const rentModal = document.getElementById('rentModal');
  const openRent = document.getElementById('openRent');
  const closeRent = document.getElementById('closeRent');
  const rentOfferImage = document.getElementById('rentOfferImage');
  const rentOfferUrl = openRent ? openRent.dataset.rentOfferUrl : '';

  function openRentModal(event) {
    if (event) event.preventDefault();
    if (!rentModal) return;
    if (rentOfferImage && rentOfferUrl) rentOfferImage.src = rentOfferUrl;
    rentModal.classList.add('open');
    rentModal.setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('modal-is-open');
  }

  function closeRentModal() {
    if (!rentModal) return;
    rentModal.classList.remove('open');
    rentModal.setAttribute('aria-hidden', 'true');
    document.documentElement.classList.remove('modal-is-open');
  }

  if (openRent && rentModal) openRent.addEventListener('click', openRentModal);
  if (closeRent && rentModal) closeRent.addEventListener('click', closeRentModal);
  if (rentModal) {
    rentModal.addEventListener('click', (event) => {
      if (event.target === rentModal) closeRentModal();
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && rentModal.classList.contains('open')) closeRentModal();
    });
  }

  // Story big-image slider
  const storySlides = [...document.querySelectorAll('.story-visual-slide')];
  const storyDots = [...document.querySelectorAll('.story-dot')];
  function showStory(index) {
    storySlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
    storyDots.forEach((dot, i) => dot.classList.toggle('active', i === index));
  }
  storyDots.forEach((btn) => btn.addEventListener('click', () => showStory(Number(btn.dataset.storyTarget))));

  // Pirts clean native gallery slider
  const pirtsSlides = [...document.querySelectorAll('.clean-gallery-slide')];
  const pirtsThumbs = [...document.querySelectorAll('.clean-gallery-thumb')];
  function showPirts(index) {
    pirtsSlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
    pirtsThumbs.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
  }
  pirtsThumbs.forEach((btn) => btn.addEventListener('click', () => showPirts(Number(btn.dataset.pirtsTarget))));

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
    if (!pirtsLightbox || !pirtsLightboxImg) return;
    pirtsLightboxIndex = index;
    pirtsLightboxImg.src = getPirtsSlideUrl(index);
    pirtsLightbox.classList.add('open');
    pirtsLightbox.setAttribute('aria-hidden', 'false');
  }

  function closePirtsLightbox() {
    if (!pirtsLightbox || !pirtsLightboxImg) return;
    pirtsLightbox.classList.remove('open');
    pirtsLightbox.setAttribute('aria-hidden', 'true');
    pirtsLightboxImg.removeAttribute('src');
  }

  function movePirtsLightbox(direction) {
    if (!pirtsSlides.length || !pirtsLightboxImg) return;
    pirtsLightboxIndex = (pirtsLightboxIndex + direction + pirtsSlides.length) % pirtsSlides.length;
    showPirts(pirtsLightboxIndex);
    pirtsLightboxImg.src = getPirtsSlideUrl(pirtsLightboxIndex);
  }

  pirtsSlides.forEach((slide, index) => slide.addEventListener('click', () => openPirtsLightbox(index)));
  if (pirtsLightboxClose) pirtsLightboxClose.addEventListener('click', closePirtsLightbox);
  if (pirtsLightbox) pirtsLightbox.addEventListener('click', (e) => {
    if (e.target === pirtsLightbox) closePirtsLightbox();
  });
  if (pirtsLightboxPrev) pirtsLightboxPrev.addEventListener('click', () => movePirtsLightbox(-1));
  if (pirtsLightboxNext) pirtsLightboxNext.addEventListener('click', () => movePirtsLightbox(1));

  // Blog lightbox
  const blogLightbox = document.getElementById('blogLightbox');
  const blogLightboxClose = document.getElementById('blogLightboxClose');
  const blogLightboxMedia = document.getElementById('blogLightboxMedia');
  const blogLightboxDate = document.getElementById('blogLightboxDate');
  const blogLightboxTitle = document.getElementById('blogLightboxTitle');
  const blogLightboxSummary = document.getElementById('blogLightboxSummary');
  const blogLightboxText = document.getElementById('blogLightboxText');
  const blogLightboxLink = document.getElementById('blogLightboxLink');
  const blogShareFacebook = document.getElementById('blogShareFacebook');
  const blogShareLinkedin = document.getElementById('blogShareLinkedin');
  const blogShareCopy = document.getElementById('blogShareCopy');
  let currentBlogShareUrl = '';
  const blogCards = [...document.querySelectorAll('.blog-card.is-clickable')];

  function nl2br(str) {
    return String(str || '').replace(/\n/g, '<br>');
  }

  function buildBlogShareUrl(blogId) {
    const match = String(blogId || '').match(/(\d+)/);
    const id = match ? match[1] : '';
    const share = new URL(window.location.href);
    share.hash = '';
    if (id) share.searchParams.set('pirts_blog', id);
    return share.toString();
  }

  function openBlogLightbox(card) {
    if (!blogLightbox) return;
    const title = card.dataset.blogTitle || '';
    const date = card.dataset.blogDate || '';
    const summary = card.dataset.blogSummary || '';
    const content = card.dataset.blogContent || summary;
    const image = card.dataset.blogImage || '';
    const url = card.dataset.blogUrl || '#';
    const blogId = card.dataset.blogId || ('pirts-blog-' + encodeURIComponent(title.toLowerCase().replace(/\s+/g, '-')));
    currentBlogShareUrl = buildBlogShareUrl(blogId);

    if (blogLightboxDate) blogLightboxDate.textContent = date;
    if (blogLightboxTitle) blogLightboxTitle.textContent = title;
    if (blogLightboxSummary) blogLightboxSummary.textContent = summary;
    if (blogLightboxText) blogLightboxText.innerHTML = '<p>' + nl2br(content) + '</p>';
    if (blogLightboxMedia) {
      blogLightboxMedia.innerHTML = image ? '<img src="' + image + '" alt="' + title.replace(/"/g, '&quot;') + '">' : '';
    }
    if (blogLightboxLink) {
      if (url && url !== '#') {
        blogLightboxLink.href = url;
        blogLightboxLink.style.display = '';
      } else {
        blogLightboxLink.removeAttribute('href');
        blogLightboxLink.style.display = 'none';
      }
    }

    if (blogShareCopy) { blogShareCopy.classList.remove('is-copied'); blogShareCopy.setAttribute('aria-label', ((window.sixtythreeTheme && window.sixtythreeTheme.labels && window.sixtythreeTheme.labels.copyLink) || 'Copy link')); }
    blogLightbox.classList.add('open');
    blogLightbox.setAttribute('aria-hidden', 'false');
  }

  function closeBlogLightbox() {
    if (!blogLightbox) return;
    blogLightbox.classList.remove('open');
    blogLightbox.setAttribute('aria-hidden', 'true');
  }

  blogCards.forEach((card) => {
    card.addEventListener('click', function(e) {
      if (e.target.closest('a')) e.preventDefault();
      openBlogLightbox(card);
    });
    card.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openBlogLightbox(card);
      }
    });
  });
  const requestedBlog = new URLSearchParams(window.location.search).get('pirts_blog');
  if (requestedBlog) {
    const autoCard = blogCards.find((card) => String(card.dataset.blogId || '').match(new RegExp('(?:^|-)'+requestedBlog+'$')));
    if (autoCard) setTimeout(() => openBlogLightbox(autoCard), 250);
  }

  if (blogLightboxClose) blogLightboxClose.addEventListener('click', closeBlogLightbox);
  if (blogLightbox) blogLightbox.addEventListener('click', function(e) {
    if (e.target === blogLightbox) closeBlogLightbox();
  });
  if (blogShareFacebook) blogShareFacebook.addEventListener('click', function() {
    const shareUrl = encodeURIComponent(currentBlogShareUrl || window.location.href);
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + shareUrl, 'shareFacebook', 'width=680,height=520');
  });
  if (blogShareLinkedin) blogShareLinkedin.addEventListener('click', function() {
    const shareUrl = encodeURIComponent(currentBlogShareUrl || window.location.href);
    window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + shareUrl, 'shareLinkedin', 'width=680,height=520');
  });
  if (blogShareCopy) blogShareCopy.addEventListener('click', function() {
    const shareUrl = currentBlogShareUrl || window.location.href;
    function done(){ var labels=(window.sixtythreeTheme && window.sixtythreeTheme.labels) || {}; blogShareCopy.classList.add('is-copied'); blogShareCopy.setAttribute('aria-label', labels.copiedLink || 'Link copied'); setTimeout(function(){ blogShareCopy.classList.remove('is-copied'); blogShareCopy.setAttribute('aria-label', labels.copyLink || 'Copy link'); }, 1800); }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(shareUrl).then(done).catch(function(){ window.prompt(((window.sixtythreeTheme && window.sixtythreeTheme.labels && window.sixtythreeTheme.labels.copyPrompt) || 'Copy link:'), shareUrl); });
    } else {
      window.prompt(((window.sixtythreeTheme && window.sixtythreeTheme.labels && window.sixtythreeTheme.labels.copyPrompt) || 'Copy link:'), shareUrl);
    }
  });

  // Offcanvas
  const overlay = document.getElementById('overlay');
  const offcanvas = document.getElementById('offcanvas');
  const openMenu = document.getElementById('openMenu');
  const closeMenu = document.getElementById('closeMenu');

  function openDrawer() {
    if (!overlay || !offcanvas) return;
    overlay.classList.add('open');
    offcanvas.classList.add('open');
    if (openMenu) {
      openMenu.classList.add('is-open');
      openMenu.setAttribute('aria-label', 'Aizvērt izvēlni');
      openMenu.setAttribute('aria-expanded', 'true');
    }
  }

  function closeDrawer() {
    if (!overlay || !offcanvas) return;
    overlay.classList.remove('open');
    offcanvas.classList.remove('open');
    if (openMenu) {
      openMenu.classList.remove('is-open');
      openMenu.setAttribute('aria-label', 'Atvērt izvēlni');
      openMenu.setAttribute('aria-expanded', 'false');
    }
  }

  function toggleDrawer() {
    if (!offcanvas) return;
    if (offcanvas.classList.contains('open')) closeDrawer();
    else openDrawer();
  }

  if (openMenu) {
    openMenu.setAttribute('aria-expanded', 'false');
    openMenu.addEventListener('click', toggleDrawer);
  }
  if (closeMenu) closeMenu.addEventListener('click', closeDrawer);
  if (overlay) overlay.addEventListener('click', closeDrawer);
  if (offcanvas) offcanvas.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeDrawer));

  // Expandable AJAX current-page search
  const openSearchDesktop = document.getElementById('openSearchDesktop');
  const openSearchMobile = document.getElementById('openSearchMobile');
  const desktopSearchPanel = document.getElementById('siteSearchPanel');
  const mobileSearchPanel = document.getElementById('mobileSiteSearchPanel');

  function setSearchPanel(button, panel, open) {
    if (!button || !panel) return;
    panel.classList.toggle('is-open', open);
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open) {
      const input = panel.querySelector('.site-search-input');
      if (input) setTimeout(() => input.focus(), 40);
    }
  }

  function toggleSearch(button, panel) {
    const next = !panel.classList.contains('is-open');
    setSearchPanel(button, panel, next);
  }

  if (openSearchDesktop && desktopSearchPanel) {
    openSearchDesktop.addEventListener('click', (event) => {
      event.stopPropagation();
      toggleSearch(openSearchDesktop, desktopSearchPanel);
    });
  }
  if (openSearchMobile && mobileSearchPanel) {
    openSearchMobile.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      const next = !mobileSearchPanel.classList.contains('is-open');
      setSearchPanel(openSearchMobile, mobileSearchPanel, next);
      if (next) {
        if (overlay) overlay.classList.add('open');
        if (offcanvas) offcanvas.classList.add('open');
      }
    });
  }

  document.addEventListener('click', function(event) {
    if (desktopSearchPanel && !event.target.closest('.site-search-wrap')) {
      setSearchPanel(openSearchDesktop, desktopSearchPanel, false);
    }
  });

  function textMatch(haystack, needle) {
    return String(haystack || '').toLowerCase().indexOf(String(needle || '').toLowerCase()) !== -1;
  }

  function getSectionResults(query) {
    const results = [];
    const seen = new Set();
    const selectors = ['section[id]','.service-card','.blog-card','.bath-item','.bath-package-item','.panel'].join(',');
    document.querySelectorAll(selectors).forEach(function(node) {
      const text = (node.textContent || '').replace(/\s+/g, ' ').trim();
      if (!text || !textMatch(text, query)) return;
      const section = node.closest('section[id]') || node;
      const id = section.getAttribute('id');
      if (!id) return;
      const heading = section.querySelector('h1,h2,h3,h4,h5') || (node.querySelector ? node.querySelector('h3,h4,h5') : null) || node;
      const title = (heading.textContent || text).replace(/\s+/g, ' ').trim().slice(0, 90);
      const key = id + '|' + title;
      if (seen.has(key)) return;
      seen.add(key);
      results.push({title:title,url:'#'+id,type:'Lapa',excerpt:text.slice(0,150)+(text.length>150?'...':'')});
    });
    return results.slice(0, 8);
  }

  function renderSearchResults(panel, query, items, loading) {
    const box = panel ? panel.querySelector('.site-search-results') : null;
    if (!box) return;
    if (loading) {
      box.innerHTML = '<div class="site-search-state">' + ((window.sixtythreeTheme && window.sixtythreeTheme.labels && window.sixtythreeTheme.labels.searching) || 'Meklē...') + '</div>';
      return;
    }
    if (!query || query.length < 2) {
      box.innerHTML = '<div class="site-search-state">Ievadiet vismaz 2 simbolus.</div>';
      return;
    }
    if (!items.length) {
      box.innerHTML = '<div class="site-search-state">' + ((window.sixtythreeTheme && window.sixtythreeTheme.labels && window.sixtythreeTheme.labels.noResults) || 'Nekas netika atrasts.') + '</div>';
      return;
    }
    box.innerHTML = items.map(function(item) {
      return '<a class="site-search-result" href="' + item.url + '">' +
        '<span class="site-search-result-type">' + (item.type || 'Rezultāts') + '</span>' +
        '<strong>' + item.title + '</strong>' +
        (item.excerpt ? '<small>' + item.excerpt + '</small>' : '') +
        '</a>';
    }).join('');
    box.querySelectorAll('a[href^="#"]').forEach(function(link){
      link.addEventListener('click', function(e){
        const target = document.querySelector(link.getAttribute('href'));
        if (!target) return;
        e.preventDefault();
        setSearchPanel(openSearchDesktop, desktopSearchPanel, false);
        setSearchPanel(openSearchMobile, mobileSearchPanel, false);
        closeDrawer();
        target.scrollIntoView({behavior:'smooth', block:'start'});
      });
    });
  }

  function initSearchPanel(panel) {
    if (!panel) return;
    const input = panel.querySelector('.site-search-input');
    if (!input) return;
    let timer = null;
    let lastController = null;

    function doSearch() {
      const query = input.value.trim();
      if (lastController) lastController.abort();
      if (!query || query.length < 2) {
        renderSearchResults(panel, query, [], false);
        return;
      }
      const local = getSectionResults(query);
      renderSearchResults(panel, query, local, true);
      lastController = new AbortController();
      const params = new URLSearchParams({action:'sixtythree_search',nonce:(window.sixtythreeTheme && window.sixtythreeTheme.nonce) || '',s:query});
      fetch(((window.sixtythreeTheme && window.sixtythreeTheme.ajaxUrl) || '/wp-admin/admin-ajax.php') + '?' + params.toString(), {signal:lastController.signal})
        .then(function(res){ return res.json(); })
        .then(function(data){
          const remote = data && data.success ? (data.data.items || data.data || []) : [];
          const seen = new Set();
          const merged = local.concat(remote).filter(function(item){
            const key = (item.url || '') + '|' + (item.title || '');
            if (seen.has(key)) return false;
            seen.add(key);
            return true;
          });
          renderSearchResults(panel, query, merged.slice(0, 10), false);
        })
        .catch(function(err){
          if (err.name === 'AbortError') return;
          renderSearchResults(panel, query, local, false);
        });
    }

    input.addEventListener('input', function(){clearTimeout(timer);timer=setTimeout(doSearch,260);});
    panel.addEventListener('submit', function(event){event.preventDefault();doSearch();});
  }

  initSearchPanel(desktopSearchPanel);
  initSearchPanel(mobileSearchPanel);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      setSearchPanel(openSearchDesktop, desktopSearchPanel, false);
      setSearchPanel(openSearchMobile, mobileSearchPanel, false);
      closeDrawer();
      if (rentModal) rentModal.classList.remove('open');
      closePirtsLightbox();
      closeBlogLightbox();
    }
  });
})();

/* 63.lv BBuilder compatibility hooks */
window.sixtythreeTheme = window.sixtythreeTheme || {};
window.sixtythreeTheme.version = '1.3.0';
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
  document.querySelectorAll('.booking-card .cal-date').forEach(function(day){
    day.setAttribute('role', 'button');
    day.setAttribute('tabindex', '0');

    function choose(){
      if (day.classList.contains('booked')) return;
      var bookingCard = day.closest('.booking-card');
      if (!bookingCard) return;

      bookingCard.querySelectorAll('.cal-date').forEach(function(d){ d.classList.remove('selected'); });
      day.classList.add('selected');

      var selectedDate = (day.querySelector('span') ? day.querySelector('span').textContent : '').trim();
      var selectedTime = (day.querySelector('small') ? day.querySelector('small').textContent : '').trim();
      var bookingMonth = (bookingCard.getAttribute('data-booking-month') || '').trim();
      bookingCard.setAttribute('data-selected-date', selectedDate);
      bookingCard.setAttribute('data-selected-time', selectedTime);

      var serviceInputs = document.querySelectorAll('input[name="service"], select[name="service"]');
      serviceInputs.forEach(function(input){
        var value = 'Pirts zona — ' + (bookingMonth ? bookingMonth + ' ' : '') + selectedDate + ' / ' + selectedTime;
        if (input.tagName.toLowerCase() === 'select') {
          Array.prototype.forEach.call(input.options, function(opt){
            if (opt.text.toLowerCase().indexOf('pirts') !== -1) input.value = opt.value;
          });
        } else {
          input.value = value;
        }
        input.dispatchEvent(new Event('input', {bubbles:true}));
        input.dispatchEvent(new Event('change', {bubbles:true}));
      });

      var msg = document.querySelector('textarea[name="message"]');
      if (msg && selectedDate) {
        var line = 'Vēlos rezervēt pirts laiku: ' + (bookingMonth ? bookingMonth + ' ' : '') + selectedDate + ' (' + selectedTime + ').';
        if (msg.value.indexOf('Vēlos rezervēt pirts laiku:') === -1) {
          msg.value = line + (msg.value ? '\n\n' + msg.value : '');
        } else {
          msg.value = msg.value.replace(/Vēlos rezervēt pirts laiku:.*?(\n|$)/, line + '\n');
        }
        msg.dispatchEvent(new Event('input', {bubbles:true}));
        msg.dispatchEvent(new Event('change', {bubbles:true}));
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
        if (first && first.focus) first.focus({preventScroll:true});
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

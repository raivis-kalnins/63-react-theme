  <header class="site-header">
    <div class="header-inner">
      <a class="brand brand-image" href="<?php echo esc_url(home_url('/')); ?>">
      <picture class="brand-logo-picture">
        <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services-360.avif'); ?>" type="image/avif">
        <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services-360.webp'); ?>" type="image/webp">
        <img class="brand-logo-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services.png'); ?>" width="360" height="128" loading="eager" decoding="async" fetchpriority="high" alt="<?php echo esc_attr(get_bloginfo('name') ?: '63.lv Services'); ?>">
      </picture>
    </a>

      <nav class="desktop-nav">
        <a class="nav-pill" href="#pakalpojumi">Pakalpojumi</a>
        <a class="nav-pill" href="#web">Web izstrāde</a>
        <a class="nav-pill" href="#apps"><?php echo esc_html(sixtythree_i18n('Lietotnes', 'Apps', 'Приложения')); ?></a>
        <a class="nav-pill" href="#cta">Kontakti</a>
        <a class="nav-pill" href="#map">Atrašanās vieta</a>
      </nav>

      <div class="header-tools">
        <?php sixtythree_language_switcher("lang-switch"); ?>
        <div class="site-search-wrap">
          <button class="icon-btn desktop-search" id="openSearchDesktop" type="button" aria-label="Atvērt meklēšanu" aria-expanded="false" aria-controls="siteSearchPanel">
            <span class="search-icon"></span>
          </button>
          <form class="site-search-panel" id="siteSearchPanel" role="search" autocomplete="off">
            <div class="site-search-row">
              <input class="site-search-input" id="siteSearchInput" type="search" placeholder="Meklēt šajā lapā..." aria-label="Meklēt šajā lapā">
              <button class="site-search-submit" type="submit">Meklēt</button>
            </div>
            <div class="site-search-results" id="siteSearchResults" aria-live="polite"></div>
          </form>
        </div>
        <button class="icon-btn mobile-toggle" id="openMenu" type="button" aria-label="Atvērt izvēlni">
          <span class="hamburger"><span></span></span>
        </button>
      </div>
    </div>
  </header>

  <div class="overlay" id="overlay"></div>

  <aside class="offcanvas" id="offcanvas">
    <div class="offcanvas-head">
      <div class="wordmark">
        <span class="top">Bauskas 63 • Rīga</span>
        <span class="main">
          <strong><em>63.lv</em></strong>
          <span>Services</span>
        </span>
        <span class="bottom">Kalns RTL</span>
      </div>
      <button class="icon-btn" id="closeMenu" type="button" aria-label="Aizvērt izvēlni">×</button>
    </div>
    <button class="btn btn-outline" id="openSearchMobile" type="button" aria-expanded="false" aria-controls="mobileSiteSearchPanel">Meklēt</button>
    <form class="site-search-panel mobile-search-panel" id="mobileSiteSearchPanel" role="search" autocomplete="off">
      <div class="site-search-row">
        <input class="site-search-input" id="mobileSiteSearchInput" type="search" placeholder="Meklēt šajā lapā..." aria-label="Meklēt šajā lapā">
        <button class="site-search-submit" type="submit">Meklēt</button>
      </div>
      <div class="site-search-results" id="mobileSiteSearchResults" aria-live="polite"></div>
    </form>
    <?php sixtythree_language_switcher("mobile-lang"); ?>
    <nav>
      <a href="#pakalpojumi">Pakalpojumi</a>
      <a href="#web">Web izstrāde</a>
      <a href="#apps"><?php echo esc_html(sixtythree_i18n('Lietotnes', 'Apps', 'Приложения')); ?></a>
      <a href="#cta">Kontakti</a>
      <a href="#map">Atrašanās vieta</a>
    </nav>
  </aside>

  <main>
    <section class="hero">
      <div class="hero-copy">
        <div class="kicker">Services · Bauskas 63</div>
        <h1>Telpas, zināšanas un pakalpojumi vienuviet</h1>
        <div class="gold-rule"></div>
        <p>SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē — telpas, telpu zonu, apmācības, web risinājumus un ikdienas servisu vienuviet.</p>
        <div class="hero-actions">
          <a class="btn" href="#pakalpojumi">Mūsu pakalpojumi →</a>
          <a class="btn btn-outline" href="#cta">Sazināties</a>
        </div>
      </div>
      <div class="hero-panels">
        <div class="panel"><div class="icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h2 class="panel-title">Lieliska vieta</h2><p>Bauskas iela 63, Rīga — ērta piekļuve no ielas un pagalma puses.</p></div>
        <div class="panel"><div class="icon">⚿</div><h2 class="panel-title">Elastīgi risinājumi</h2><p>Privātpersonām, uzņēmumiem, telpu nomai, kursiem un ikdienas pakalpojumiem.</p></div>
        <div class="panel"><div class="icon">☷</div><h2 class="panel-title">Pieredze kopš 2003</h2><p>Uzticams lokāls centrs ar plašu pakalpojumu klāstu vienā adresē.</p></div>
        <div class="panel"><div class="icon">♡</div><h2 class="panel-title">Personiska pieeja</h2><p>Palīdzam piemeklēt atbilstošāko risinājumu jūsu vajadzībām un idejām.</p></div>
      </div>
    </section>

    <div class="slider-wrap">
      <section class="slider" aria-label="Pakalpojumu slider">
        <article class="slide active">
          <div class="photo photo-sauna" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/pirts-ballitem-sauna.avif'); ?>')"></div>
          <div class="copy">
            <div class="kicker">Telpas ballītēm</div>
            <h2>Atpūta ģimenei un draugiem</h2>
            <p>Darba dienās no 16:00, brīvdienās pēc vienošanās. Rekomendējam līdz 15 personām. Standarta piedāvājums €84 jeb €14/h.</p>
            <a class="btn" href="#pakalpojumi">Skatīt vairāk →</a>
          </div>
        </article>
        <article class="slide">
          <div class="photo" style="background-image:url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80')"></div>
          <div class="copy">
            <div class="kicker">Web izstrāde</div>
            <h2>Digitāli risinājumi biznesam</h2>
            <p>WordPress mājaslapas, headless React projekti, UX dizains, SEO, sociālo tīklu integrācijas, hostings un uzturēšana.</p>
            <a class="btn" href="#web">Web pakalpojumi →</a>
          </div>
        </article>
        <article class="slide">
          <div class="photo photo-learning" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/learning-books-globe.svg'); ?>')"></div>
          <div class="copy">
            <div class="kicker">Mācību centrs</div>
            <h2>Tālmācības un klātienes kursi</h2>
            <p>Individuāli un grupās — uzņēmējdarbība, ekonomika, Zoom & Skype, web rīki, biroja darbi, kopēšana un printēšana.</p>
            <a class="btn" href="#pakalpojumi">Kursi un pakalpojumi →</a>
          </div>
        </article>
        <article class="slide">
          <div class="photo photo-hairdress-chair" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/hairdress-one-chair.svg'); ?>')"></div>
          <div class="copy">
            <div class="kicker">Ikdienas pakalpojumi</div>
            <h2>Skaistumam un ērtībai</h2>
            <p>Frizētava, manikīrs un pedikīrs, šuvēju pakalpojumi, mēbeļu remonts un citi risinājumi vienuviet.</p>
            <a class="btn" href="#pakalpojumi">Pieteikt laiku →</a>
          </div>
        </article>
        <div class="slider-controls">
          <button class="dot active" type="button" aria-label="Rādīt 1. slaidu"></button>
          <button class="dot" type="button" aria-label="Rādīt 2. slaidu"></button>
          <button class="dot" type="button" aria-label="Rādīt 3. slaidu"></button>
          <button class="dot" type="button" aria-label="Rādīt 4. slaidu"></button>
        </div>
      </section>
    </div>

    <section id="pakalpojumi" class="section">
      <div class="section-head">
        <div>
          <div class="kicker">Pakalpojumi</div>
          <h2>Viss svarīgākais vienā vietā</h2>
        </div>
        <p>Pakalpojumu sadaļā atradīsiet telpu rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.</p>
      </div>

      <div class="services">
        <article class="service-card"><h3>Telpas ballītēm</h3><ul><li>Ģimenēm un draugiem</li><li>Līdz ~15 personām</li><li>Darba dienās no 16:00</li><li>€84 vai €14/h</li></ul><a href="#cta">UZZINĀT VAIRĀK →</a></article>
        <article class="service-card"><h3>Web izstrāde un uzturēšana</h3><ul><li><?php echo esc_html(sixtythree_i18n('WordPress Business no €1 190', 'WordPress Business from €1,190', 'WordPress Business от €1 190')); ?></li><li>WordPress & WooCommerce</li><li>SEO, hostings, optimizācija</li><li>Headless ReactJS risinājumi</li></ul><a href="#web">UZZINĀT VAIRĀK →</a></article>
        <article class="service-card"><h3>E-apmācības un kursi</h3><ul><li>Individuāli €15/h</li><li>Grupas 3–5 pers. €50/h</li><li>Zoom & Skype</li><li>Biroja darbi, kopēšana, printēšana</li></ul><a href="#learning">UZZINĀT VAIRĀK →</a></article>
        <article class="service-card"><h3>Citi pakalpojumi</h3><ul><li>Telpu noma pasākumiem</li><li>Frizētava IEVA</li><li>Manikīrs & pedikīrs</li><li>Šūšanas un remonta darbi</li></ul><a href="#cta">UZZINĀT VAIRĀK →</a></article>
        <article class="service-card service-card--forairsoft"><h3>ForAirsoft</h3><ul><li>Airsoft veikals un serviss</li><li>Replikas un taktiskais ekipējums</li><li>Rezerves daļas un uzlabojumi</li><li>Bauskas 63 · k1</li></ul><a href="#forairsoft">UZZINĀT VAIRĀK →</a></article>
      </div>

    </section>

    <section id="forairsoft" class="section forairsoft-section" aria-labelledby="forairsoft-title">
      <div class="forairsoft-shell">
        <div class="forairsoft-media" aria-label="ForAirsoft veikals Bauskas ielā 63">
          <figure class="forairsoft-sign-photo">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/forairsoft-sign.jpg'); ?>" width="832" height="864" loading="lazy" decoding="async" alt="ForAirsoft veikala izkārtne Bauskas ielā 63">
          </figure>
          <figure class="forairsoft-shop-photo">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/forairsoft-shop.jpg'); ?>" width="300" height="225" loading="lazy" decoding="async" alt="ForAirsoft fiziskā veikala interjers">
          </figure>
        </div>

        <div class="forairsoft-copy">
          <div class="forairsoft-eyebrow"><span class="forairsoft-dot" aria-hidden="true"></span><span>Tajā pašā Bauskas 63 ēkā</span></div>
          <div class="kicker">Bauskas 63 · Airsoft</div>
          <h2 id="forairsoft-title">ForAirsoft — airsoft veikals un serviss</h2>
          <p class="forairsoft-lead">Airsoft veikals ar 10+ gadu pieredzi nozarē. Piedāvā replikas, taktisko ekipējumu un rezerves daļas remontam un uzlabojumiem, kā arī repliku apkopes, remonta un individuālas pielāgošanas servisu.</p>

          <div class="forairsoft-info-grid">
            <div class="forairsoft-info-card">
              <span class="forairsoft-info-label">Adrese</span>
              <strong>Bauskas iela 63, k1, Rīga, LV-1004</strong>
            </div>
            <div class="forairsoft-info-card">
              <span class="forairsoft-info-label">Darba laiks</span>
              <strong>Otr.–Piekt. 10:00–19:00</strong>
              <span>Sest. 11:00–15:00 · Sv.–Pirm. slēgts</span>
            </div>
            <div class="forairsoft-info-card">
              <span class="forairsoft-info-label">E-pasts</span>
              <a href="mailto:info@forairsoft.com">info@forairsoft.com</a>
            </div>
            <div class="forairsoft-info-card">
              <span class="forairsoft-info-label">Tālruņi</span>
              <a href="tel:+37122329800">+371 22329800</a>
              <a href="tel:+37129415744">+371 29415744</a>
            </div>
          </div>

          <div class="forairsoft-actions">
            <a class="btn forairsoft-primary" href="https://forairsoft.com/" target="_blank" rel="noopener">Apmeklēt ForAirsoft.com ↗</a>
            <a class="forairsoft-secondary" href="https://forairsoft.com/pages/contact" target="_blank" rel="noopener">ForAirsoft kontakti ↗</a>
          </div>
        </div>
      </div>
    </section>

    
    
    <section class="bath-section" id="pirts">
      <div class="bath-wrap">
        <div class="bath-content pirts-v21">
          <div class="pirts-v21-main">
            <div class="bath-copy-panel">
              <div class="kicker"><?php echo esc_html(sixtythree_homepage_text('pirts_kicker')); ?></div>
              <h3><?php echo esc_html(sixtythree_homepage_text('pirts_heading')); ?></h3>
              <p><?php echo esc_html(sixtythree_homepage_text('pirts_intro')); ?></p>

              <div class="bath-offer-badge"><span class="star">★</span> <?php echo esc_html(sixtythree_homepage_text('pirts_badge')); ?></div>

              <div class="bath-grid">
                <div class="bath-item"><b><?php echo esc_html(sixtythree_homepage_text('pirts_price_title')); ?></b><span><?php echo esc_html(sixtythree_homepage_text('pirts_price_body')); ?></span></div>
                <div class="bath-item"><b><?php echo esc_html(sixtythree_homepage_text('pirts_hours_title')); ?></b><span><?php echo esc_html(sixtythree_homepage_text('pirts_hours_body')); ?></span></div>
                <div class="bath-item"><b><?php echo esc_html(sixtythree_homepage_text('pirts_people_title')); ?></b><span><?php echo esc_html(sixtythree_homepage_text('pirts_people_body')); ?></span></div>
                <div class="bath-item"><b><?php echo esc_html(sixtythree_homepage_text('pirts_notice_title')); ?></b><span><?php echo esc_html(sixtythree_homepage_text('pirts_notice_body')); ?></span></div>
              </div>

              <div class="bath-meta">
                <div class="bath-meta-row">
                  <i class="bath-meta-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5v5l3.3 2"/></svg></i>
                  <div><b><?php echo esc_html(sixtythree_homepage_text('pirts_available_title')); ?></b><span><?php echo esc_html(sixtythree_homepage_text('pirts_available_body')); ?></span></div>
                </div>
                <div class="bath-meta-row">
                  <i class="bath-meta-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg></i>
                  <div><b><?php echo esc_html(sixtythree_homepage_text('pirts_contact_title')); ?></b><span><?php echo esc_html(sixtythree_contact_phone()); ?> · rezervācijām un pieejamībai.</span></div>
                </div>
              </div>

              <div class="bath-package">
                <h4><?php echo esc_html(sixtythree_homepage_text('pirts_package_heading')); ?></h4>
                <div class="bath-package-grid">
                  <div class="bath-package-item"><span class="dot">✓</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_1')); ?></span></div>
                  <div class="bath-package-item"><span class="dot">✓</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_2')); ?></span></div>
                  <div class="bath-package-item"><span class="dot">✓</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_3')); ?></span></div>
                  <div class="bath-package-item"><span class="dot">✓</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_4')); ?></span></div>
                  <div class="bath-package-item"><span class="dot">i</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_5')); ?></span></div>
                  <div class="bath-package-item"><span class="dot">⌂</span><span><?php echo esc_html(sixtythree_homepage_text('pirts_package_6')); ?></span></div>
                </div>
              </div>
            </div>

            <div class="pirts-v21-side">
              <div class="clean-native-gallery" aria-label="63.lv telpu galerija">
                <div class="clean-gallery-stage">
                  <div class="clean-gallery-slide active" data-pirts="0" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_1', 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg')); ?>')"></div>
                  <div class="clean-gallery-slide" data-pirts="1" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_2', 'assets/images/63lv/pirts-zone-clean-02.jpg')); ?>')"></div>
                  <div class="clean-gallery-slide" data-pirts="2" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_3', 'assets/images/63lv/pirts-zone-clean-03.jpg')); ?>')"></div>
                  <div class="clean-gallery-slide" data-pirts="3" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_4', 'assets/images/63lv/pirts-zone-clean-04.jpg')); ?>')"></div>
                  <div class="clean-gallery-slide" data-pirts="4" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_5', 'assets/images/63lv/pirts-zone-clean-05.jpg')); ?>')"></div>
                  <div class="clean-gallery-slide" data-pirts="5" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_6', 'assets/images/63lv/pirts-zone-clean-06.jpg')); ?>')"></div>
                </div>
                <div class="clean-gallery-thumbs">
                  <button class="clean-gallery-thumb active" type="button" data-pirts-target="0" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_1', 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg')); ?>')" aria-label="Telpu galerijas attēls 1"></button>
                  <button class="clean-gallery-thumb" type="button" data-pirts-target="1" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_2', 'assets/images/63lv/pirts-zone-clean-02.jpg')); ?>')" aria-label="Telpu galerijas attēls 2"></button>
                  <button class="clean-gallery-thumb" type="button" data-pirts-target="2" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_3', 'assets/images/63lv/pirts-zone-clean-03.jpg')); ?>')" aria-label="Telpu galerijas attēls 3"></button>
                  <button class="clean-gallery-thumb" type="button" data-pirts-target="3" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_4', 'assets/images/63lv/pirts-zone-clean-04.jpg')); ?>')" aria-label="Telpu galerijas attēls 4"></button>
                  <button class="clean-gallery-thumb" type="button" data-pirts-target="4" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_5', 'assets/images/63lv/pirts-zone-clean-05.jpg')); ?>')" aria-label="Telpu galerijas attēls 5"></button>
                  <button class="clean-gallery-thumb" type="button" data-pirts-target="5" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url('pirts_gallery_image_6', 'assets/images/63lv/pirts-zone-clean-06.jpg')); ?>')" aria-label="Telpu galerijas attēls 6"></button>
                </div>
              </div>

              <div class="booking-card" data-booking-month="<?php echo esc_attr(sixtythree_booking_value('booking_heading')); ?>">
                <div class="booking-head">
                  <div>
                    <div class="kicker"><?php echo esc_html(sixtythree_booking_value('booking_kicker')); ?></div>
                    <h4><?php echo esc_html(sixtythree_booking_value('booking_heading')); ?></h4>
                  </div>
                  <div class="booking-legend">
                    <span class="legend-dot"><?php echo esc_html(sixtythree_booking_value('booking_legend_available')); ?></span>
                    <span class="legend-dot booked"><?php echo esc_html(sixtythree_booking_value('booking_legend_booked')); ?></span>
                    <span class="legend-dot selected"><?php echo esc_html(sixtythree_booking_value('booking_legend_selected')); ?></span>
                  </div>
                </div>
                <div class="calendar-grid">
                  <div class="calendar-days">
                    <span>P</span><span>O</span><span>T</span><span>C</span><span>P</span><span>S</span><span>Sv</span>
                  </div>
                  <div class="calendar-dates">
                    <?php foreach (sixtythree_booking_days() as $day) : ?>
                      <div class="cal-date <?php echo esc_attr($day[2]); ?>"><span><?php echo esc_html($day[0]); ?></span><small><?php echo esc_html($day[1]); ?></small></div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="booking-actions">
                  <a class="btn" href="#cta"><?php echo esc_html(sixtythree_booking_value('booking_button')); ?></a>
                  <a class="btn btn-outline" href="tel:+37129837694"><?php echo esc_html(sixtythree_booking_value('booking_call_button')); ?></a>
                </div>
              </div>
            
              <div class="pirts-side-actions">
                <div class="bath-contact-strip">
                <div class="bath-contact-pill"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg></span> <?php echo esc_html(sixtythree_i18n('Bauskas iela 63, Rīga', 'Bauskas Street 63, Riga', 'Улица Баускас 63, Рига')); ?></div>
                <div class="bath-contact-pill"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg></span> <?php echo esc_html(sixtythree_contact_phone()); ?></div>
              </div>
                <div class="actions">
                <a class="btn" href="#cta"><?php echo esc_html(sixtythree_homepage_text('pirts_cta_primary')); ?></a>
                <a class="btn btn-outline" href="#pakalpojumi"><?php echo esc_html(sixtythree_homepage_text('pirts_cta_secondary')); ?></a>
              </div>
              </div>
</div>
          </div>

          <div class="pirts-news">
            <div class="news-head">
              <div>
                <div class="kicker"><?php echo esc_html(sixtythree_homepage_text('pirts_blog_kicker')); ?></div>
                <h4><?php echo esc_html(sixtythree_homepage_text('pirts_blog_heading')); ?></h4>
              </div>
              <a class="btn btn-outline" href="#cta"><?php echo esc_html(sixtythree_homepage_text('pirts_blog_button')); ?></a>
            </div>
            <div class="blog-grid">
              <?php for ($i = 1; $i <= 3; $i++) :
                $img_key = 'pirts_blog_image_' . $i;
                $title_key = 'pirts_blog_' . $i . '_title';
                $date_key = 'pirts_blog_' . $i . '_date';
                $text_key = 'pirts_blog_' . $i . '_text';
                $link_key = 'pirts_blog_' . $i . '_link';
                $url_key = 'pirts_blog_' . $i . '_url';
                $full_key = 'pirts_blog_' . $i . '_full';
                $fallbacks = array(
                  1 => 'assets/images/63lv/pirts-zone-clean-04.avif',
                  2 => 'assets/images/63lv/pirts-zone-clean-05.avif',
                  3 => 'assets/images/63lv/pirts-zone-clean-06.avif',
                );
                $full_content = sixtythree_homepage_text($full_key, sixtythree_homepage_text($text_key));
              ?>
              <article class="blog-card is-clickable" tabindex="0" role="button"
                       data-blog-id="pirts-blog-<?php echo esc_attr($i); ?>"
                       data-blog-title="<?php echo esc_attr(sixtythree_homepage_text($title_key)); ?>"
                       data-blog-date="<?php echo esc_attr(sixtythree_homepage_text($date_key)); ?>"
                       data-blog-summary="<?php echo esc_attr(sixtythree_homepage_text($text_key)); ?>"
                       data-blog-content="<?php echo esc_attr($full_content); ?>"
                       data-blog-image="<?php echo esc_url(sixtythree_homepage_media_url($img_key, $fallbacks[$i])); ?>"
                       data-blog-url="<?php echo esc_url(sixtythree_homepage_text($url_key)); ?>">
                <div class="blog-img" style="background-image:url('<?php echo esc_url(sixtythree_homepage_media_url($img_key, $fallbacks[$i])); ?>')"></div>
                <div class="blog-body">
                  <span class="blog-date"><?php echo esc_html(sixtythree_homepage_text($date_key)); ?></span>
                  <h5><?php echo esc_html(sixtythree_homepage_text($title_key)); ?></h5>
                  <p><?php echo esc_html(sixtythree_homepage_text($text_key)); ?></p>
                  <a href="#pirts-blog" class="blog-open-modal"><?php echo esc_html(sixtythree_homepage_text($link_key)); ?></a>
                </div>
              </article>
              <?php endfor; ?>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section id="web" class="section" style="padding-top:0;">
      <div class="web-wrap">
        <div class="web-intro">
          <div class="kicker">Web Development</div>
          <h2>WordPress un full stack izstrāde</h2>
          <p><?php echo esc_html(sixtythree_i18n(
            'Izstrādājam WordPress, WooCommerce, LPCMS, React un PHP risinājumus — no uzņēmumu mājaslapām līdz pielāgotām lietotnēm, automatizācijai un AI integrācijām.',
            'We build WordPress, WooCommerce, LPCMS, React and PHP solutions — from business websites to custom apps, automation and AI integrations.',
            'Мы разрабатываем решения на WordPress, WooCommerce, LPCMS, React и PHP — от сайтов компаний до приложений, автоматизации и интеграций ИИ.'
          )); ?></p>
          <p><?php echo esc_html(sixtythree_i18n(
            'No Figma vai Adobe XD dizaina līdz WordPress, PHP un React izstrādei — veidojam responsīvu priekšpusi, REST API, datubāzu un integrāciju loģiku. Pārņemam un labojam arī esošas WordPress vietnes, migrējam novecojušus risinājumus, ieviešam AI funkcijas un nodrošinām hostingu, rezerves kopijas, drošību un veiktspējas uzraudzību.',
            'From Figma or Adobe XD designs to WordPress, PHP and React development — we build responsive front ends, REST APIs, database logic and integrations. We also take over and fix existing WordPress sites, migrate older systems, add practical AI features, and provide hosting, backups, security and performance monitoring.',
            'От дизайна в Figma или Adobe XD до разработки на WordPress, PHP и React — создаём адаптивный интерфейс, REST API, логику баз данных и интеграции. Также берём на поддержку и исправляем существующие сайты WordPress, переносим устаревшие решения, внедряем практические AI-функции и обеспечиваем хостинг, резервные копии, безопасность и контроль производительности.'
          )); ?></p>
          <div class="web-delivery-points" aria-label="<?php echo esc_attr(sixtythree_i18n('Web izstrādes iespējas', 'Web development capabilities', 'Возможности веб-разработки')); ?>">
            <span><?php echo esc_html(sixtythree_i18n('Dizains → izstrāde', 'Design → development', 'Дизайн → разработка')); ?></span>
            <span><?php echo esc_html(sixtythree_i18n('Web un mobilās lietotnes', 'Web & mobile apps', 'Веб- и мобильные приложения')); ?></span>
            <span><?php echo esc_html(sixtythree_i18n('Migrācijas un remonts', 'Migrations & fixes', 'Миграции и исправления')); ?></span>
            <span><?php echo esc_html(sixtythree_i18n('AI un automatizācija', 'AI & automation', 'AI и автоматизация')); ?></span>
          </div>
          <div class="web-badges">
            <span>WordPress Themes</span>
            <span>WooCommerce</span>
            <span>Custom Plugins</span>
            <span>Full Stack</span>
            <span>SEO</span>
            <span>Headless ReactJS</span>
            <span>LPCMS</span>
            <span>Web Apps</span>
            <span>AI Themes</span>
            <span>Hosting Support</span>
          </div>
          <div class="web-stats">
            <div class="web-stat"><b>WP</b><span>Tēmas, ACF bloki, administrēšana</span></div>
            <div class="web-stat"><b>API</b><span>Savienojumi, integrācijas, automatizācija</span></div>
            <div class="web-stat"><b>UX</b><span>Struktūra, satura ceļi, konversija</span></div>
          </div>
        </div>

        <div class="web-grid">
          <article class="web-card">
            <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80')"></div>
            <div class="body">
              <h3>WordPress Themes</h3>
              <p>Pilnībā pielāgotas premium tēmas, dizaina sistēmas, Gutenberg bloki un ātrdarbības optimizācija.</p>
            </div>
          </article>
          <article class="web-card">
            <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1400&q=80')"></div>
            <div class="body">
              <h3>WooCommerce</h3>
              <p>E-veikali, maksājumu integrācijas, produktu katalogi, piegādes loģika un klientu pieredzes uzlabojumi.</p>
            </div>
          </article>
          <article class="web-card">
            <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1400&q=80')"></div>
            <div class="body">
              <h3>Plugins & Custom Code</h3>
              <p>Individuāli spraudņi, automatizācijas, rezervāciju formas, nomas pieteikumi un biznesa procesu digitalizācija.</p>
            </div>
          </article>
          <article class="web-card">
            <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1400&q=80')"></div>
            <div class="body">
              <h3>Headless ReactJS</h3>
              <p>WP kā CMS + React/Vite/Next frontend, API savienojumi un gatavība pārejai uz headless arhitektūru.</p>
            </div>
          </article>
        </div>
      </div>

      <div class="web-extra">
        <article class="web-extra-card">
          <div class="mini-icon">AI</div>
          <h3>AI theme development</h3>
          <p>AI asistētas dizaina sistēmas, satura ģenerēšanas plūsmas, gudri WordPress bloki un automatizēti UI komponenti.</p>
        </article>
        <article class="web-extra-card">
          <div class="mini-icon">WP</div>
          <h3>AI plugin development</h3>
          <p>Pielāgoti WordPress spraudņi ar AI funkcijām, API integrācijām, meklēšanu, rezervācijām un biznesa automatizāciju.</p>
        </article>
        <article class="web-extra-card">
          <div class="mini-icon">24</div>
          <h3>Hosting, support & maintenance</h3>
          <p>Hostings, atjauninājumi, drošība, rezerves kopijas, veiktspējas uzlabojumi un regulārs tehniskais atbalsts.</p>
        </article>
        <article class="web-extra-card">
          <div class="mini-icon">CMS</div>
          <h3>LPCMS</h3>
          <p><?php echo esc_html(sixtythree_i18n(
            'Ātra satura pārvaldība, pielāgota izstrāde un pieejami Premium un Premium Pro mēneša plāni.',
            'Fast content management, custom development and affordable Premium or Premium Pro monthly plans.',
            'Быстрое управление контентом, индивидуальная разработка и доступные планы Premium и Premium Pro.'
          )); ?></p>
        </article>
      </div>

      <?php
      $sixtythree_portfolio_projects = array(
        array(
          'group' => 'websites',
          'type' => array('Mājaslapa', 'Website', 'Сайт'),
          'title' => 'Labas Lietas',
          'description' => array(
            'E-komercijas vietne instrumentiem, dārza tehnikai, darbnīcu precēm un rezerves daļām ar meklēšanu un katalogu.',
            'E-commerce website for tools, garden equipment, workshop products and spare parts with search and catalogue flows.',
            'Интернет-магазин инструментов, садовой техники, товаров для мастерской и запчастей с поиском и каталогом.'
          ),
          'url' => 'https://labaslietas.63.lv',
          'image' => 'https://image.thum.io/get/width/1200/crop/760/maxAge/168/noanimate/https://labaslietas.63.lv',
        ),
        array(
          'group' => 'websites',
          'type' => array('Mājaslapa', 'Website', 'Сайт'),
          'title' => 'Maride.lv',
          'description' => array(
            'E-komercijas vietne profesionāliem instrumentiem, dārza tehnikai un darbnīcu aprīkojumam.',
            'E-commerce website for professional tools, garden machinery and workshop equipment.',
            'Интернет-магазин профессиональных инструментов, садовой техники и оборудования для мастерской.'
          ),
          'url' => 'https://maride.lv',
          'image' => get_template_directory_uri() . '/assets/images/63lv/portfolio-maride.jpg',
        ),
        array(
          'group' => 'websites',
          'type' => array('Mājaslapa', 'Website', 'Сайт'),
          'title' => 'GG Garage',
          'description' => array(
            'Uzņēmuma vietne dārza un celtniecības tehnikas remontam, diagnostikai un DAEWOO garantijas servisam Ogrē.',
            'Business website for garden and construction machinery repair, diagnostics and DAEWOO warranty service in Ogre.',
            'Сайт компании по ремонту и диагностике садовой и строительной техники и гарантийному сервису DAEWOO в Огре.'
          ),
          'url' => 'https://gggarage.lv',
          'image' => 'https://image.thum.io/get/width/1200/crop/760/maxAge/168/noanimate/https://gggarage.lv',
        ),
      );
      ?>
      <div class="web-portfolio">
        <div class="web-portfolio-head">
          <div>
            <div class="kicker"><?php echo esc_html(sixtythree_i18n('Portfolio', 'Portfolio', 'Портфолио')); ?></div>
            <h2><?php echo esc_html(sixtythree_i18n('Izstrādātās mājaslapas', 'Developed websites', 'Разработанные сайты')); ?></h2>
          </div>
          <p><?php echo esc_html(sixtythree_i18n(
            'Atlasīti 63.lv un DigitalPulse klientu projekti — e-komercijas veikali un uzņēmumu mājaslapas. Visas pārlūka lietotnes atradīsiet tālāk lietotņu galerijā.',
            'Selected 63.lv and DigitalPulse client projects — online stores and business websites. Explore the full web-app gallery below.',
            'Избранные клиентские проекты 63.lv и DigitalPulse — интернет-магазины и сайты компаний. Полная галерея приложений представлена ниже.'
          )); ?></p>
        </div>

        <?php foreach (array('websites') as $portfolio_group) : ?>
          <div class="web-portfolio-group">
            <div class="web-portfolio-group-title">
              <span><?php echo esc_html($portfolio_group === 'apps'
                ? sixtythree_i18n('Lietotnes', 'Apps', 'Приложения')
                : sixtythree_i18n('Mājaslapas', 'Websites', 'Сайты')); ?></span>
            </div>
            <div class="web-portfolio-grid">
              <?php foreach ($sixtythree_portfolio_projects as $portfolio_project) : ?>
                <?php if ($portfolio_project['group'] !== $portfolio_group) { continue; } ?>
                <a class="web-project-card" href="<?php echo esc_url($portfolio_project['url']); ?>" target="_blank" rel="noopener">
                  <span class="web-project-media">
                    <img src="<?php echo esc_url($portfolio_project['image']); ?>" alt="<?php echo esc_attr($portfolio_project['title']); ?>" loading="lazy" decoding="async">
                  </span>
                  <span class="web-project-body">
                    <span class="web-project-meta"><?php echo esc_html(sixtythree_i18n($portfolio_project['type'][0], $portfolio_project['type'][1], $portfolio_project['type'][2])); ?></span>
                    <strong><?php echo esc_html($portfolio_project['title']); ?></strong>
                    <span class="web-project-description"><?php echo esc_html(sixtythree_i18n($portfolio_project['description'][0], $portfolio_project['description'][1], $portfolio_project['description'][2])); ?></span>
                    <span class="web-project-open"><?php echo esc_html(sixtythree_i18n('Atvērt projektu', 'Open project', 'Открыть проект')); ?> ↗</span>
                  </span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="portfolio-row">
        <a class="portfolio-btn" href="https://digitalpulse.click/lv/#gallery" target="_blank" rel="noopener noreferrer"><?php echo esc_html(sixtythree_i18n('Skatīt pilnu DigitalPulse portfolio', 'View full DigitalPulse portfolio', 'Смотреть полное портфолио DigitalPulse')); ?> <span>↗</span></a>
        <a class="btn btn-outline" href="#apps"><?php echo esc_html(sixtythree_i18n('Skatīt lietotnes', 'View apps', 'Смотреть приложения')); ?> &#8595;</a>
        <a class="btn btn-outline" href="#cta"><?php echo esc_html(sixtythree_i18n('Pieteikt web projektu', 'Start a web project', 'Обсудить веб-проект')); ?></a>
      </div>
    </section>


    <?php require get_template_directory() . '/template-parts/digitalpulse-app-gallery.php'; ?>

    <?php
    // Publicly visible EUR starting prices use the DigitalPulse 2026 offer.
    $sixtythree_development_prices = array(
      array('category' => array('Mājaslapas', 'Websites', 'Сайты'), 'title' => 'Landing Page', 'price' => '€490', 'summary' => array('Kompakta, pārliecinoša vienas lapas vietne.', 'A focused, effective single-page website.', 'Компактный и убедительный одностраничный сайт.'), 'detail' => array('Piemērots kampaņām, pakalpojumiem un ātrai pieteikumu saņemšanai.', 'Ideal for campaigns, services and direct enquiries.', 'Для кампаний, услуг и получения заявок.'), 'featured' => false),
      array('category' => array('WordPress', 'WordPress', 'WordPress'), 'title' => 'WordPress Business', 'price' => '€1 190', 'summary' => array('Profesionāla mājaslapa mazam uzņēmumam.', 'A professional site for a small business.', 'Профессиональный сайт для малого бизнеса.'), 'detail' => array('Pielāgots saturs, pārskatāma administrēšana, responsīvs dizains un SEO pamati.', 'Custom content, clear editing tools, responsive design and an SEO foundation.', 'Индивидуальный контент, удобное редактирование, адаптивный дизайн и основы SEO.'), 'featured' => true),
      array('category' => array('WordPress', 'WordPress', 'WordPress'), 'title' => 'WordPress Growth', 'price' => '€2 490', 'summary' => array('Plašāks risinājums augošam biznesam.', 'A larger solution for a growing business.', 'Расширенное решение для растущего бизнеса.'), 'detail' => array('Sarežģītāka struktūra, pielāgoti bloki un biznesa integrāciju iespējas.', 'More advanced content structures, custom blocks and business integrations.', 'Более сложная структура, специальные блоки и бизнес-интеграции.'), 'featured' => false),
      array('category' => array('E-komercija', 'E-commerce', 'Электронная коммерция'), 'title' => 'WooCommerce', 'price' => '€2 490', 'summary' => array('E-veikals ar pielāgotu pirkšanas pieredzi.', 'An online store with a tailored buying experience.', 'Интернет-магазин с удобными покупками.'), 'detail' => array('Preču katalogi, variācijas, maksājumi un piegādes loģika.', 'Product catalogues, variants, payments and delivery logic.', 'Каталоги, вариации товаров, оплата и доставка.'), 'featured' => false),
      array('category' => array('B2B', 'B2B', 'B2B'), 'title' => 'Advanced / B2B', 'price' => '€7 500+', 'summary' => array('Kompleksa tirdzniecība un uzņēmumu procesi.', 'Complex commerce and business workflows.', 'Сложная коммерция и корпоративные процессы.'), 'detail' => array('B2B cenas, klientu lomas, sarežģīti katalogi un integrācijas.', 'B2B prices, customer roles, complex catalogues and integrations.', 'B2B-цены, роли клиентов, сложные каталоги и интеграции.'), 'featured' => false),
      array('category' => array('Spraudņi', 'Plugins', 'Плагины'), 'title' => 'Custom WordPress Plugin', 'price' => '€990+', 'summary' => array('Individuāla WordPress funkcionalitāte.', 'Custom WordPress functionality.', 'Индивидуальные функции WordPress.'), 'detail' => array('ACF, Gutenberg bloki, integrācijas, automatizācija un administrācijas rīki.', 'ACF, Gutenberg blocks, integrations, automation and admin tools.', 'ACF, блоки Gutenberg, интеграции, автоматизация и инструменты управления.'), 'featured' => false),
      array('category' => array('Lietotnes un MI', 'Apps & AI', 'Приложения и ИИ'), 'title' => 'Web Apps & AI', 'price' => '€8 000+', 'summary' => array('Pielāgotas lietotnes un mākslīgā intelekta risinājumi.', 'Custom web apps and artificial-intelligence solutions.', 'Веб-приложения и решения с искусственным интеллектом.'), 'detail' => array('React/PHP paneļi, portāli, API, AI integrācijas un automatizācija.', 'React/PHP dashboards, portals, APIs, AI integrations and automation.', 'Панели React/PHP, порталы, API, интеграции ИИ и автоматизация.'), 'featured' => false),
    );
    $sixtythree_care_prices = array(
      array('title' => 'LPCMS Premium', 'price' => '€4', 'detail' => array('Vienkārša vietnes pārvaldība LPCMS vidē.', 'Straightforward website management with LPCMS.', 'Простое управление сайтом через LPCMS.')),
      array('title' => 'LPCMS Premium Pro', 'price' => '€7', 'detail' => array('Plašākas iespējas LPCMS platformā.', 'Additional features on the LPCMS platform.', 'Дополнительные возможности платформы LPCMS.')),
      array('title' => 'WordPress Hosting + Care', 'price' => '€3,50', 'detail' => array('+ 0,5 h uzturēšana mēnesī', '+ 0.5 h of monthly maintenance', '+ 0,5 ч обслуживания в месяц')),
      array('title' => 'WooCommerce Hosting + Care', 'price' => '€5', 'detail' => array('+ 0,5 h uzturēšana mēnesī', '+ 0.5 h of monthly maintenance', '+ 0,5 ч обслуживания в месяц')),
      array('title' => 'Website Maintenance', 'price' => '€69', 'detail' => array('Vietnes uzturēšana un tehniskais atbalsts.', 'Website maintenance and technical support.', 'Поддержка сайта и техническое обслуживание.')),
    );
    ?>
    <section class="price-section dp-pricing-section" id="web-cenas" aria-labelledby="dp-pricing-title">
      <div class="price-wrap">
        <div class="price-head">
          <div>
            <div class="kicker"><?php echo esc_html(sixtythree_i18n('DigitalPulse · cenas', 'DigitalPulse · pricing', 'DigitalPulse · цены')); ?></div>
            <h2 id="dp-pricing-title"><?php echo esc_html(sixtythree_i18n('Web izstrāde un lietotnes — cenas', 'Web development and apps — pricing', 'Разработка сайтов и приложений — цены')); ?></h2>
          </div>
          <p><?php echo esc_html(sixtythree_i18n(
            'Digitālie risinājumi pēc DigitalPulse piedāvājuma. Norādītas sākuma cenas EUR; gala tāme atkarīga no projekta apjoma.',
            'Digital solutions from the DigitalPulse offer. Starting prices are shown in EUR; your final quote depends on project scope.',
            'Цифровые решения DigitalPulse. Указаны стартовые цены в евро; итоговая смета зависит от объёма проекта.'
          )); ?></p>
        </div>
        <div class="pricing-grid dp-pricing-grid">
          <?php foreach ($sixtythree_development_prices as $dp_plan) : ?>
            <article class="price-card dp-price-card<?php echo $dp_plan['featured'] ? ' featured' : ''; ?>">
              <small><?php echo esc_html(sixtythree_i18n($dp_plan['category'][0], $dp_plan['category'][1], $dp_plan['category'][2])); ?></small>
              <h3><?php echo esc_html($dp_plan['title']); ?></h3>
              <p class="dp-price-teaser"><?php echo esc_html(sixtythree_i18n($dp_plan['summary'][0], $dp_plan['summary'][1], $dp_plan['summary'][2])); ?></p>
              <div class="price-value"><?php echo esc_html($dp_plan['price']); ?> <span><?php echo esc_html(sixtythree_i18n('no', 'from', 'от')); ?></span></div>
              <details class="dp-price-details">
                <summary><?php echo esc_html(sixtythree_i18n('Uzzināt vairāk', 'Learn more', 'Подробнее')); ?></summary>
                <p><?php echo esc_html(sixtythree_i18n($dp_plan['detail'][0], $dp_plan['detail'][1], $dp_plan['detail'][2])); ?></p>
              </details>
            </article>
          <?php endforeach; ?>
        </div>
        <div class="dp-care-section">
          <div class="dp-care-heading">
            <h3><?php echo esc_html(sixtythree_i18n('Platformas, hostings un uzturēšana', 'Platforms, hosting and maintenance', 'Платформы, хостинг и поддержка')); ?></h3>
            <p><?php echo esc_html(sixtythree_i18n('Mēneša sākuma cenas', 'Monthly starting prices', 'Стартовые цены в месяц')); ?></p>
          </div>
          <div class="dp-care-grid">
            <?php foreach ($sixtythree_care_prices as $dp_care) : ?>
              <article class="dp-care-card">
                <strong><?php echo esc_html($dp_care['title']); ?></strong>
                <span class="dp-care-price"><?php echo esc_html($dp_care['price']); ?><small><?php echo esc_html(sixtythree_i18n('/mēn', '/mo', '/мес')); ?></small></span>
                <span class="dp-care-detail"><?php echo esc_html(sixtythree_i18n($dp_care['detail'][0], $dp_care['detail'][1], $dp_care['detail'][2])); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="dp-pricing-foot">
          <p><?php echo esc_html(sixtythree_i18n(
            'Precīzu cenu un iekļautos darbus saskaņosim pirms izstrādes sākuma.',
            'We will confirm the exact price and included work before development begins.',
            'Точную цену и состав работ согласуем до начала разработки.'
          )); ?></p>
          <a class="btn" href="#cta"><?php echo esc_html(sixtythree_i18n('Saņemt piedāvājumu', 'Request a quote', 'Запросить предложение')); ?> →</a>
        </div>
      </div>
    </section>

<section id="cta" class="cta-section">
      <div class="cta-wrap">
        <div class="cta-copy">
          <div class="kicker">Pieteikums un kontakti</div>
          <h2>Pastāstiet par savu ideju vai vajadzību</h2>
          <p>Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Sazinieties ar mums, lai pārrunātu pieejamību vai projekta vajadzības.</p>
          <div class="contact-list">
            <div class="contact-item"><div class="mini"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4.5 7 7.5 6 7.5-6"/></svg></div><div><b>Epasts</b><span><?php echo esc_html(sixtythree_contact_email()); ?><br>Atbildēsim 1 darba dienas laikā.</span></div></div>
            <div class="contact-item"><div class="mini"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg></div><div><b>Tālrunis</b><span><?php echo esc_html(sixtythree_contact_phone()); ?><br>Zvaniet vai rakstiet par pieejamību.</span></div></div>
            <div class="contact-item"><div class="mini"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg></div><div><b>Adrese</b><span>Bauskas iela 63, Rīga<br>Ērta piekļuve no ielas un pagalma puses.</span></div></div>
          </div>
        </div>

        <div class="cta-form sixtythree-bbuilder-form">
          <div class="kicker">Saziņas forma</div>
          <h3>Pieteikt konsultāciju</h3>
          <?php echo do_shortcode('[sixtythree_contact_form]'); ?>
        </div>
      </div>
    </section>

    <section id="map" class="map-section">
      <div class="map-wrap">
        <iframe 
          src="https://www.google.com/maps?q=Bauskas%20iela%2063%2C%20R%C4%ABga&z=15&output=embed"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Google Map Bauskas iela 63, Rīga">
        </iframe>

        <div class="map-box">
          <div class="kicker"><?php echo esc_html(sixtythree_i18n('Atrašanās vieta', 'Location', 'Местоположение')); ?></div>
          <h3><?php echo esc_html(sixtythree_i18n('Bauskas iela 63, Rīga', 'Bauskas Street 63, Riga', 'Улица Баускас 63, Рига')); ?></h3>
          <p><?php echo esc_html(sixtythree_i18n('Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.', 'You can find us at Bauskas Street 63, Riga — with convenient access from both the street and courtyard side.', 'Вы найдёте нас на улице Баускас 63, в Риге — с удобным доступом как со стороны улицы, так и со двора.')); ?></p>
          <div class="address-pills">
            <div class="address-pill"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg><span class="address-pill-text"><?php echo esc_html(sixtythree_i18n('Bauskas iela 63, Rīga', 'Bauskas Street 63, Riga', 'Улица Баускас 63, Рига')); ?></span></div>
            <div class="address-pill"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg><span class="address-pill-text"><?php echo esc_html(sixtythree_contact_phone()); ?></span></div>
            <div class="address-pill"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4.5 7 7.5 6 7.5-6"/></svg><span class="address-pill-text"><?php echo esc_html(sixtythree_contact_email()); ?></span></div>
          </div>
        </div>
      </div>
    </section>
  </main>

  
  <footer>
    <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4.5 7 7.5 6 7.5-6"/></svg></span><div><b><?php echo esc_html(sixtythree_contact_email()); ?></b></div></div>
    <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg></span><div><b><?php echo esc_html(sixtythree_contact_phone()); ?></b></div></div>
    <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg></span><div><b><?php echo esc_html(sixtythree_i18n('Bauskas 63, Rīga', 'Bauskas 63, Riga', 'Баускас 63, Рига')); ?></b></div></div>
    <?php echo sixtythree_facebook_footer_item(); ?>
    <a class="btn" href="mailto:<?php echo esc_attr(sixtythree_contact_email()); ?>"><?php echo esc_html(sixtythree_i18n('Sazināties →', 'Contact →', 'Связаться →')); ?></a>
  </footer>



  <div class="pirts-lightbox" id="pirtsLightbox" aria-hidden="true">
    <div class="pirts-lightbox-inner">
      <button class="pirts-lightbox-close" id="pirtsLightboxClose" type="button" aria-label="Aizvērt galeriju">×</button>
      <img class="pirts-lightbox-img" id="pirtsLightboxImg" alt="Telpu galerijas attēls">
      <div class="pirts-lightbox-nav">
        <button id="pirtsLightboxPrev" type="button" aria-label="Iepriekšējais attēls">‹</button>
        <button id="pirtsLightboxNext" type="button" aria-label="Nākamais attēls">›</button>
      </div>
    </div>
  </div>

  <div class="blog-lightbox" id="blogLightbox" aria-hidden="true">
    <div class="blog-lightbox-inner">
      <button class="blog-lightbox-close" id="blogLightboxClose" type="button" aria-label="<?php echo esc_attr(sixtythree_homepage_text('pirts_blog_modal_close')); ?>">×</button>
      <div class="blog-lightbox-media" id="blogLightboxMedia"></div>
      <div class="blog-lightbox-content">
        <span class="blog-lightbox-date" id="blogLightboxDate"></span>
        <h3 id="blogLightboxTitle"></h3>
        <p class="blog-lightbox-summary" id="blogLightboxSummary"></p>
        <div class="blog-lightbox-text" id="blogLightboxText"></div>
        <div class="blog-lightbox-share" aria-label="<?php echo esc_attr(sixtythree_i18n('Dalīties ar rakstu', 'Share this article', 'Поделиться статьёй')); ?>">
          <button class="blog-share-btn blog-share-facebook" id="blogShareFacebook" type="button" aria-label="<?php echo esc_attr(sixtythree_i18n('Dalīties Facebook', 'Share on Facebook', 'Поделиться в Facebook')); ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.2 8.7h2.6V5h-3.1c-3.7 0-5.5 2.2-5.5 5.3v2.1H5.4v4h2.8V23h4.1v-6.6h3.3l.6-4h-3.9v-1.8c0-1.1.3-1.9 1.9-1.9Z"/></svg>
          </button>
          <button class="blog-share-btn blog-share-linkedin" id="blogShareLinkedin" type="button" aria-label="<?php echo esc_attr(sixtythree_i18n('Dalīties LinkedIn', 'Share on LinkedIn', 'Поделиться в LinkedIn')); ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.4 20.5H2.6V8.9h3.8v11.6ZM4.5 7.3a2.2 2.2 0 1 1 0-4.4 2.2 2.2 0 0 1 0 4.4Zm17 13.2h-3.8v-5.7c0-1.4 0-3.1-1.9-3.1s-2.2 1.5-2.2 3v5.8H9.8V8.9h3.6v1.6h.1c.5-.9 1.7-1.9 3.6-1.9 3.8 0 4.5 2.5 4.5 5.8v6.1Z"/></svg>
          </button>
          <button class="blog-share-btn blog-share-x" id="blogShareX" type="button" aria-label="<?php echo esc_attr(sixtythree_i18n('Dalīties X', 'Share on X', 'Поделиться в X')); ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.4 10.2 22.7 1h-2l-7.2 8.1L7.8 1H1.2l8.7 12.4L1.2 23h2l7.6-8.6 6.1 8.6h6.6l-9.1-12.8Zm-2.7 3-1-.1L3.7 2.5h3.1l14.3 19h-3.2l-6.2-8.3Z"/></svg>
          </button>
          <button class="blog-share-btn blog-share-copy" id="blogShareCopy" type="button" aria-label="<?php echo esc_attr(sixtythree_i18n('Kopēt saiti', 'Copy link', 'Скопировать ссылку')); ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M16 1H5a2 2 0 0 0-2 2v13h2V3h11V1Zm3 4H9a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 16H9V7h10v14Z"/></svg>
            <span class="sr-only"><?php echo esc_html(sixtythree_i18n('Kopēt saiti', 'Copy link', 'Скопировать ссылку')); ?></span>
          </button>
        </div>
        <a class="btn btn-outline blog-lightbox-link" id="blogLightboxLink" href="#cta" target="_self" rel="noopener"><?php echo esc_html(sixtythree_homepage_text('pirts_blog_button')); ?></a>
      </div>
    </div>
  </div>

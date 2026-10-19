<?php
/**
 * DigitalPulse web-app gallery.
 * 17 verified app links + one custom-project enquiry card.
 * Text remains accessible in the server-rendered LV / EN / RU homepage.
 */
if (!defined('ABSPATH')) { exit; }

$sixtythree_digitalpulse_apps = array(
    array(
        'title' => 'GKI Maintenance Reports',
        'url' => 'https://maintenance.gkiengineering.co.uk',
        'description' => array('Santehnikas apkopes darbi, pārbaudes, fotoattēli un darbu atskaites.', 'Plumbing maintenance tasks, inspections, photos and job reports.', 'Обслуживание сантехники, проверки, фотографии и отчёты о работах.'),
    ),
    array(
        'title' => '63 Games',
        'url' => 'https://games.63.lv',
        'description' => array('Aizraujošas tiešsaistes spēles telefonam, planšetei un datoram.', 'Online games for phones, tablets and computers.', 'Увлекательные онлайн-игры для телефона, планшета и компьютера.'),
    ),
    array(
        'title' => 'Music Writer',
        'url' => 'https://music-writer.63.lv',
        'description' => array('Rakstiet, rediģējiet un atskaņojiet notis tieši pārlūkprogrammā.', 'Write, edit and play sheet music directly in your browser.', 'Создавайте, редактируйте и воспроизводите ноты прямо в браузере.'),
    ),
    array(
        'title' => 'Music Studio 63',
        'url' => 'https://music.63.lv',
        'description' => array('Ierakstu studijas vide mūzikas grupām, dziesmām un projektiem.', 'A music studio workspace for bands, songs and recording projects.', 'Музыкальная студия для групп, песен и творческих проектов.'),
    ),
    array(
        'title' => 'GKI Builder Estimate',
        'url' => 'https://builder-estimate.gkiengineering.co.uk',
        'description' => array('Būvdarbu tāmes, mērījumi un noderīgi būvniecības kalkulatori.', 'Building estimates, measurements and construction calculators.', 'Строительные сметы, замеры и полезные калькуляторы.'),
    ),
    array(
        'title' => 'Go Navigator',
        'url' => 'https://go.63.lv',
        'description' => array('Navigācijas un maršrutu plānošanas rīki ceļojumiem un vietām.', 'Navigation and route-planning tools for trips and places.', 'Навигация и планирование маршрутов для путешествий.'),
    ),
    array(
        'title' => 'Radio 63',
        'url' => 'https://radio.63.lv',
        'description' => array('Ātrs, mobilajām ierīcēm piemērots tiešsaistes radio atskaņotājs.', 'A fast, mobile-friendly online radio player.', 'Быстрый интернет-радиоплеер для мобильных устройств.'),
    ),
    array(
        'title' => 'ClientWatch Support',
        'url' => 'https://csupport.63.lv',
        'description' => array('Klientu vietņu uzraudzība, pieejamības pārbaudes un atbalsta pārskats.', 'Website monitoring, uptime checks and a support overview.', 'Мониторинг сайтов клиентов, проверки доступности и поддержка.'),
    ),
    array(
        'title' => 'SEO & Image Studio',
        'url' => 'https://seo.63.lv',
        'description' => array('SEO pārbaudes, vietņu karšu skenēšana, responsivitāte un attēlu optimizācija.', 'SEO audits, sitemap scans, responsive checks and image optimisation.', 'SEO-аудит, сканирование карт сайтов, адаптивность и оптимизация изображений.'),
    ),
    array(
        'title' => 'Language Bridge',
        'url' => 'https://language.63.lv',
        'description' => array('Interaktīvas valodu nodarbības, vārdu krājums un mācību rīki.', 'Interactive language lessons, vocabulary and learning tools.', 'Интерактивные уроки языков, словарь и учебные инструменты.'),
    ),
    array(
        'title' => 'Live Translator',
        'url' => 'https://translate.63.lv',
        'description' => array('Teksta, mikrofona runas, audio un video tulkošana.', 'Translate text, microphone speech, audio and video.', 'Перевод текста, речи с микрофона, аудио и видео.'),
    ),
    array(
        'title' => 'Interactive Whiteboard',
        'url' => 'https://whiteboard.63.lv',
        'description' => array('Vizuāla darba tāfele mācībām, studentiem un sanāksmēm.', 'A visual whiteboard for lessons, students and meetings.', 'Интерактивная доска для обучения, студентов и встреч.'),
    ),
    array(
        'title' => 'Price & Property Compare',
        'url' => 'https://price.63.lv',
        'description' => array('Salīdziniet preču, automašīnu un nekustamā īpašuma cenas kartē.', 'Compare product, car and property prices on a map.', 'Сравнение цен на товары, автомобили и недвижимость на карте.'),
    ),
    array(
        'title' => 'Free AI Hub',
        'url' => 'https://ai.63.lv',
        'description' => array('Atklājiet bezmaksas MI čatbotus, radošus un programmēšanas rīkus.', 'Discover free AI chatbots, creative tools and coding resources.', 'Бесплатные ИИ-чат-боты и инструменты творчества и программирования.'),
    ),
    array(
        'title' => 'Social Media Manager',
        'url' => 'https://social.63.lv',
        'description' => array('Sociālo tīklu aktivitāšu pārvaldība un pārskatīšana vienuviet.', 'Manage and review social-media activity in one place.', 'Управление и обзор активности в соцсетях в одном месте.'),
    ),
    array(
        'title' => 'Remote Desktop',
        'url' => 'https://remote.63.lv',
        'description' => array('Attālināta datora piekļuve un atbalsta rīki pārlūkā.', 'Remote desktop access and support tools in a browser.', 'Удалённый доступ к компьютеру и техподдержка через браузер.'),
    ),
    array(
        'title' => 'WPBB Commerce Platform',
        'url' => 'https://shop.63.lv',
        'description' => array('WooCommerce platforma ar Laravel Blade, Bedrock, Acorn, ACF un Docker.', 'WooCommerce platform with Laravel Blade, Bedrock, Acorn, ACF and Docker.', 'Платформа WooCommerce с Laravel Blade, Bedrock, Acorn, ACF и Docker.'),
    ),
 );

$sixtythree_digitalpulse_app_icon_keys = array(
    'report', 'gamepad', 'music', 'disc', 'calculator', 'route',
    'radio', 'pulse', 'gauge', 'languages', 'mic', 'whiteboard',
    'tag', 'sparkles', 'chat', 'desktop', 'shopping',
);
$sixtythree_digitalpulse_app_icon_colors = array(
    'coral', 'violet', 'teal', 'peach', 'blue', 'coral',
    'violet', 'teal', 'peach', 'blue', 'coral', 'violet',
    'teal', 'peach', 'blue', 'coral', 'violet',
);
$sixtythree_digitalpulse_svg_icons = array(
    'report' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4.5h6M9 9h6M9 12h3M10 16l2 2 3-4"/>',
    'gamepad' => '<path d="M7 8h10c2 0 3 2 4 7 .6 3-2 4-4 2l-2-2H9l-2 2c-2 2-4.6 1-4-2 1-5 2-7 4-7Z"/><path d="M7 12h5M9.5 9.5v5M16.7 11.2h.1M18.7 13.2h.1"/>',
    'music' => '<path d="M9 19V6l11-2v13M9 9l11-2"/><ellipse cx="6.5" cy="19" rx="2.5" ry="1.8"/><ellipse cx="17.5" cy="17" rx="2.5" ry="1.8"/>',
    'disc' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/><path d="M12 3a9 9 0 0 1 9 9"/>',
    'calculator' => '<rect x="5" y="3" width="14" height="18" rx="2"/><rect x="8" y="6" width="8" height="4" rx=".7"/><path d="M8 13h1m3 0h1m3 0h.1M8 16h1m3 0h1m3 0h.1M8 19h1m3 0h1m3 0h.1"/>',
    'route' => '<path d="m4 12 16-8-8 16-2-7-6-1Z"/><path d="m10 13 10-9"/>',
    'radio' => '<rect x="4" y="8" width="16" height="12" rx="2"/><path d="m5 8 13-5M7 13h6M7 16h4"/><circle cx="16.5" cy="15" r="2"/>',
    'pulse' => '<path d="M2 12h5l3-7 4 14 3-7h5"/>',
    'gauge' => '<path d="M4 17a9 9 0 1 1 16 0H4Z"/><path d="m12 14 4-4M7 15h.1M12 7v.1M17 15h.1"/>',
    'languages' => '<rect x="3" y="5" width="12" height="12" rx="2"/><path d="M6 9h6m-3-3v9m-3-6 6 6"/><rect x="10" y="9" width="11" height="11" rx="2"/><path d="m13 17 3-6 3 6m-5-.8h4"/>',
    'mic' => '<rect x="9" y="3" width="6" height="12" rx="3"/><path d="M6 11a6 6 0 0 0 12 0M12 17v4m-4 0h8"/>',
    'whiteboard' => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21l2-4m6 4-2-4M9 12l3-3 3 2 2-3"/>',
    'tag' => '<path d="M4 4h9l8 8-9 9-8-8V4Z"/><circle cx="9" cy="9" r="1.5"/>',
    'sparkles' => '<path d="m12 2 2.3 7.7L22 12l-7.7 2.3L12 22l-2.3-7.7L2 12l7.7-2.3L12 2Z"/>',
    'chat' => '<path d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7l-5 3v-3H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="M8 10h8M8 13h5"/>',
    'desktop' => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8m-4-4v4M6 7h12"/>',
    'shopping' => '<path d="M5 8h14l1 13H4L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/>',
    'plus' => '<rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 7v10m-5-5h10"/>',
);
?>
<section class="section dp-apps-section" id="apps" aria-labelledby="dp-apps-heading">
  <div class="dp-apps-shell">
    <div class="dp-apps-head">
      <div class="dp-apps-intro">
        <div class="kicker"><?php echo esc_html(sixtythree_i18n('DigitalPulse · lietotņu galerija', 'DigitalPulse · app gallery', 'DigitalPulse · галерея приложений')); ?></div>
        <h2 id="dp-apps-heading"><?php echo esc_html(sixtythree_i18n('Lietotnes darbam, radošumam un ikdienai', 'Apps for work, creativity and everyday life', 'Приложения для работы, творчества и жизни')); ?></h2>
        <p><?php echo esc_html(sixtythree_i18n(
          'No praktiskiem biznesa rīkiem līdz mūzikai, mācībām un AI. Iepazīstiet izstrādātās lietotnes un atveriet tās jaunā cilnē.',
          'From practical business tools to music, learning and AI. Explore our web apps and open them in a new tab.',
          'От полезных бизнес-инструментов до музыки, обучения и ИИ. Изучайте наши приложения и открывайте их в новой вкладке.'
        )); ?></p>
      </div>
      <span class="dp-apps-count">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="4" height="4" rx=".7"/><rect x="10" y="3" width="4" height="4" rx=".7"/><rect x="17" y="3" width="4" height="4" rx=".7"/><rect x="3" y="10" width="4" height="4" rx=".7"/><rect x="10" y="10" width="4" height="4" rx=".7"/><rect x="17" y="10" width="4" height="4" rx=".7"/><rect x="3" y="17" width="4" height="4" rx=".7"/><rect x="10" y="17" width="4" height="4" rx=".7"/><rect x="17" y="17" width="4" height="4" rx=".7"/></svg>
        <?php echo esc_html((string) (count($sixtythree_digitalpulse_apps) + 1)); ?> <?php echo esc_html(sixtythree_i18n('lietotnes', 'apps', 'приложений')); ?>
      </span>
    </div>
    <div class="dp-apps-grid">
      <?php foreach ($sixtythree_digitalpulse_apps as $dp_index => $dp_app) :
        $dp_icon_key = $sixtythree_digitalpulse_app_icon_keys[$dp_index] ?? 'report';
        $dp_icon_color = $sixtythree_digitalpulse_app_icon_colors[$dp_index] ?? 'blue';
      ?>
        <a class="dp-app-card" href="<?php echo esc_url($dp_app['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($dp_app['title'] . ' — ' . sixtythree_i18n('Atvērt lietotni jaunā cilnē', 'Open app in a new tab', 'Открыть приложение в новой вкладке')); ?>">
          <span class="dp-app-icon dp-app-icon--<?php echo esc_attr($dp_icon_color); ?>" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $sixtythree_digitalpulse_svg_icons[$dp_icon_key]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg>
          </span>
          <span class="dp-app-external" aria-hidden="true">&#8599;</span>
          <span class="dp-app-body">
            <strong><?php echo esc_html($dp_app['title']); ?></strong>
            <span class="dp-app-description"><?php echo esc_html(sixtythree_i18n($dp_app['description'][0], $dp_app['description'][1], $dp_app['description'][2])); ?></span>
          </span>
        </a>
      <?php endforeach; ?>
      <a class="dp-app-card dp-app-card-custom" href="#cta">
        <span class="dp-app-icon dp-app-icon--teal" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $sixtythree_digitalpulse_svg_icons['plus']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg>
        </span>
        <span class="dp-app-external" aria-hidden="true">&#8599;</span>
        <span class="dp-app-body">
          <strong><?php echo esc_html(sixtythree_i18n('Tava lietotne', 'Your App', 'Ваше приложение')); ?></strong>
          <span class="dp-app-description"><?php echo esc_html(sixtythree_i18n(
          'Pielāgota lietotne, WordPress spraudnis vai AI risinājums — pastāstiet par savu ideju.',
          'A custom web app, WordPress plugin or AI solution — tell us about your idea.',
          'Веб-приложение, плагин WordPress или ИИ-решение — расскажите о своей идее.'
        )); ?></span>
        </span>
      </a>
    </div>
    <div class="dp-apps-footer">
      <span><?php echo esc_html(sixtythree_i18n('Projekti no DigitalPulse portfeļa', 'Projects from the DigitalPulse portfolio', 'Проекты из портфолио DigitalPulse')); ?></span>
      <a href="https://digitalpulse.click/lv/#gallery" target="_blank" rel="noopener noreferrer"><?php echo esc_html(sixtythree_i18n('Apskatīt oriģinālo galeriju', 'View the original gallery', 'Смотреть исходную галерею')); ?> ↗</a>
    </div>
  </div>
</section>

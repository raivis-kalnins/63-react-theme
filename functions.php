<?php
/**
 * 63.lv React Theme functions.
 */
if (!defined('ABSPATH')) { exit; }

define('SIXTYTHREE_THEME_VERSION', '1.0.0');

function sixtythree_setup() {
    load_theme_textdomain('sixty-three-lv', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height'=>96,'width'=>220,'flex-height'=>true,'flex-width'=>true));
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption','style','script'));
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'sixty-three-lv'),
        'footer'  => __('Footer Menu', 'sixty-three-lv'),
    ));
}
add_action('after_setup_theme', 'sixtythree_setup');

function sixtythree_current_lang() {
    if (function_exists('pll_current_language')) {
        $lang = pll_current_language('slug');
        if ($lang) { return $lang; }
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? trim((string) $_SERVER['REQUEST_URI'], '/') : '';
    if (preg_match('#^(en|ru)(/|$)#', $uri, $m)) { return $m[1]; }
    if (isset($_GET['lang']) && in_array($_GET['lang'], array('lv','en','ru'), true)) { return sanitize_key($_GET['lang']); }
    return 'lv';
}

function sixtythree_i18n($lv, $en = '', $ru = '') {
    $lang = sixtythree_current_lang();
    if ($lang === 'en') { return $en !== '' ? $en : $lv; }
    if ($lang === 'ru') { return $ru !== '' ? $ru : $lv; }
    return $lv;
}

function sixtythree_contact_email() {
    return apply_filters('sixtythree_contact_email', 'hello@63.lv');
}

function sixtythree_contact_phone() {
    return apply_filters('sixtythree_contact_phone', '+371 29837694');
}

function sixtythree_scripts() {
    $ver = SIXTYTHREE_THEME_VERSION;
    $css_file = get_template_directory() . '/assets/css/63lv-theme.css';
    $js_file  = get_template_directory() . '/assets/js/63lv-theme.js';
    $css_ver = file_exists($css_file) ? filemtime($css_file) : $ver;
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : $ver;

    wp_enqueue_style('sixtythree-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap', array(), null);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css', array(), '5.3.3');
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.1.14');
    wp_enqueue_style('sixtythree-theme', get_template_directory_uri() . '/assets/css/63lv-theme.css', array('sixtythree-fonts','bootstrap','swiper'), $css_ver);

    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.1.14', true);
    wp_enqueue_script('sixtythree-theme', get_template_directory_uri() . '/assets/js/63lv-theme.js', array('wp-element','swiper'), $js_ver, true);
    wp_localize_script('sixtythree-theme', 'sixtythreeTheme', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sixtythree_search'),
        'lang' => sixtythree_current_lang(),
        'labels' => array(
            'searching' => sixtythree_i18n('Meklē...', 'Searching...', 'Поиск...'),
            'noResults' => sixtythree_i18n('Nekas netika atrasts.', 'No results found.', 'Ничего не найдено.'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'sixtythree_scripts');

function sixtythree_language_switcher($class = 'lang-switch') {
    $langs = array('lv' => 'LV', 'en' => 'EN', 'ru' => 'RU');
    $current = sixtythree_current_lang();
    echo '<div class="' . esc_attr($class) . '">';

    foreach ($langs as $slug => $label) {
        if (function_exists('pll_home_url')) {
            $url = pll_home_url($slug);
        } else {
            $url = home_url($slug === 'lv' ? '/' : '/' . $slug . '/');
        }

        // When Polylang is active on translated pages, use the exact current translation URL if available.
        if (function_exists('pll_the_languages') && !is_front_page()) {
            $translations = pll_the_languages(array('raw'=>1, 'hide_if_empty'=>0));
            if (isset($translations[$slug]['url']) && $translations[$slug]['url']) {
                $url = $translations[$slug]['url'];
            }
        }

        $active = $slug === $current ? ' active' : '';
        echo '<a class="' . esc_attr(trim($active)) . '" data-lang="' . esc_attr($slug) . '" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
    }
    echo '</div>';
}

function sixtythree_fallback_contact_form() {
    ?>
    <form class="wpbb-form sixtythree-fallback-form" action="mailto:<?php echo esc_attr(sixtythree_contact_email()); ?>" method="post" enctype="text/plain">
      <div class="field-row">
        <input class="input" name="name" placeholder="<?php echo esc_attr(sixtythree_i18n('Vārds', 'Name', 'Имя')); ?>" required>
        <input class="input" name="company" placeholder="<?php echo esc_attr(sixtythree_i18n('Uzņēmums / projekts', 'Company / project', 'Компания / проект')); ?>">
      </div>
      <div class="field-row">
        <input class="input" type="email" name="email" placeholder="<?php echo esc_attr(sixtythree_i18n('Epasts', 'Email', 'Эл. почта')); ?>" required>
        <input class="input" name="phone" placeholder="<?php echo esc_attr(sixtythree_i18n('Tālrunis', 'Phone', 'Телефон')); ?>">
      </div>
      <input class="input" name="service" placeholder="<?php echo esc_attr(sixtythree_i18n('Interesējošais pakalpojums', 'Service of interest', 'Интересующая услуга')); ?>">
      <textarea class="textarea" name="message" placeholder="<?php echo esc_attr(sixtythree_i18n('Pastāstiet īsi par savu vajadzību...', 'Tell us briefly what you need...', 'Кратко опишите вашу потребность...')); ?>"></textarea>
      <button type="submit" class="btn"><?php echo esc_html(sixtythree_i18n('Nosūtīt pieteikumu →', 'Send request →', 'Отправить заявку →')); ?></button>
    </form>
    <?php
}

function sixtythree_ajax_search() {
    check_ajax_referer('sixtythree_search', 'nonce');
    $term = sanitize_text_field($_GET['s'] ?? '');
    $q = new WP_Query(array(
        's' => $term,
        'post_type' => array('post','page'),
        'post_status' => 'publish',
        'posts_per_page' => 6,
    ));
    $items = array();
    while ($q->have_posts()) {
        $q->the_post();
        $items[] = array('title'=>get_the_title(), 'url'=>get_permalink(), 'type'=>get_post_type());
    }
    wp_reset_postdata();
    wp_send_json_success($items);
}
add_action('wp_ajax_sixtythree_search', 'sixtythree_ajax_search');
add_action('wp_ajax_nopriv_sixtythree_search', 'sixtythree_ajax_search');

function sixtythree_register_polylang_strings() {
    if (!function_exists('pll_register_string')) { return; }
    $strings = array(
        'Telpas, zināšanas un pakalpojumi vienuviet',
        'Bauskas 63 · pirts zona',
        'Vieta ģimenei, draugiem un svinībām',
        'Vertikālais solārijs — cenas',
        'WordPress un full stack izstrāde',
        'Pastāstiet par savu ideju vai vajadzību',
        'Bauskas iela 63, Rīga',
        'Pieteikt konsultāciju',
        'Meklēt',
    );
    foreach ($strings as $string) {
        pll_register_string('63.lv', $string, '63.lv Theme');
    }
}
add_action('init', 'sixtythree_register_polylang_strings');

function sixtythree_bbuilder_notice() {
    if (is_admin() && current_user_can('activate_plugins') && !class_exists('WP_BBuilder')) {
        echo '<div class="notice notice-info"><p><strong>63.lv React Theme:</strong> Install/activate WP BBuilder for editable Bootstrap sections, Dynamic Form, gallery/lightbox and form handling.</p></div>';
    }
}
add_action('admin_notices', 'sixtythree_bbuilder_notice');

require_once get_template_directory() . '/inc/demo-importer.php';


/**
 * WP BBuilder fallback block compatibility.
 * Prevents "Your site doesn’t include support for wpbb/*" in admin when BBuilder is not active yet.
 * When WP BBuilder is active, its real block registrations take priority and these fallbacks are skipped.
 */
function sixtythree_register_wpbb_fallback_blocks() {
    $blocks = array(
        'accordion' => 'sixtythree_render_wpbb_placeholder_block',
        'accordion-item' => 'sixtythree_render_wpbb_placeholder_block',
        'alert' => 'sixtythree_render_wpbb_placeholder_block',
        'badge' => 'sixtythree_render_wpbb_placeholder_block',
        'breadcrumb' => 'sixtythree_render_wpbb_placeholder_block',
        'button' => 'sixtythree_render_wpbb_placeholder_block',
        'card' => 'sixtythree_render_wpbb_placeholder_block',
        'cards' => 'sixtythree_render_wpbb_placeholder_block',
        'column' => 'sixtythree_render_wpbb_content_block',
        'cta-card' => 'sixtythree_render_wpbb_placeholder_block',
        'cta-section' => 'sixtythree_render_wpbb_placeholder_block',
        'dynamic-form' => 'sixtythree_render_wpbb_dynamic_form_fallback',
        'google-map' => 'sixtythree_render_wpbb_placeholder_block',
        'list-group' => 'sixtythree_render_wpbb_placeholder_block',
        'menu-option' => 'sixtythree_render_wpbb_placeholder_block',
        'navbar' => 'sixtythree_render_wpbb_placeholder_block',
        'progress' => 'sixtythree_render_wpbb_placeholder_block',
        'row' => 'sixtythree_render_wpbb_content_block',
        'section' => 'sixtythree_render_wpbb_content_block',
        'sitemap' => 'sixtythree_render_wpbb_placeholder_block',
        'soc-follow-block' => 'sixtythree_render_wpbb_placeholder_block',
        'soc-share' => 'sixtythree_render_wpbb_placeholder_block',
        'social-feeds' => 'sixtythree_render_wpbb_placeholder_block',
        'spinner' => 'sixtythree_render_wpbb_placeholder_block',
        'tab-item' => 'sixtythree_render_wpbb_placeholder_block',
        'table' => 'sixtythree_render_wpbb_placeholder_block',
        'tabs' => 'sixtythree_render_wpbb_placeholder_block',
        'video' => 'sixtythree_render_wpbb_placeholder_block',
        'file' => 'sixtythree_render_wpbb_placeholder_block',
        'inline-svg' => 'sixtythree_render_wpbb_placeholder_block',
        'swiper' => 'sixtythree_render_wpbb_placeholder_block',
        'weather' => 'sixtythree_render_wpbb_placeholder_block',
        'varda-dienas' => 'sixtythree_render_wpbb_placeholder_block',
        'ajax-search' => 'sixtythree_render_wpbb_placeholder_block',
        'pricecards' => 'sixtythree_render_wpbb_placeholder_block',
        'catalogue' => 'sixtythree_render_wpbb_placeholder_block',
        'code-display' => 'sixtythree_render_wpbb_placeholder_block',
        'countdown-timer' => 'sixtythree_render_wpbb_placeholder_block',
        'chart' => 'sixtythree_render_wpbb_placeholder_block',
        'fun-fact' => 'sixtythree_render_wpbb_placeholder_block',
        'mailchimp' => 'sixtythree_render_wpbb_placeholder_block',
        'bootstrap-div' => 'sixtythree_render_wpbb_content_block',
        'feature-list' => 'sixtythree_render_wpbb_placeholder_block',
        'timeline' => 'sixtythree_render_wpbb_placeholder_block',
        'custom-embed' => 'sixtythree_render_wpbb_placeholder_block',
        'ai-content' => 'sixtythree_render_wpbb_placeholder_block',
        'login-register' => 'sixtythree_render_wpbb_placeholder_block',
        'load-more' => 'sixtythree_render_wpbb_placeholder_block',
        'contact-links' => 'sixtythree_render_wpbb_placeholder_block',
        'events' => 'sixtythree_render_wpbb_placeholder_block',
        'testimonials' => 'sixtythree_render_wpbb_placeholder_block',
        'blog-filter' => 'sixtythree_render_wpbb_placeholder_block',
        'booking-calendar' => 'sixtythree_render_wpbb_placeholder_block',
    );

    if (!class_exists('WP_Block_Type_Registry')) { return; }

    foreach ($blocks as $slug => $callback) {
        if (WP_Block_Type_Registry::get_instance()->is_registered('wpbb/' . $slug)) { continue; }
        register_block_type('wpbb/' . $slug, array(
            'api_version' => 3,
            'render_callback' => $callback,
            'attributes' => array(
                'formTitle' => array('type'=>'string'),
                'recipient' => array('type'=>'string'),
                'emailSubject' => array('type'=>'string'),
                'successMessage' => array('type'=>'string'),
                'submitText' => array('type'=>'string'),
                'buttonClass' => array('type'=>'string'),
                'formClass' => array('type'=>'string'),
                'fieldsJson' => array('type'=>'string'),
                'showTitle' => array('type'=>'boolean'),
                'html' => array('type'=>'string'),
                'title' => array('type'=>'string'),
                'lead' => array('type'=>'string'),
                'content' => array('type'=>'string'),
                'className' => array('type'=>'string'),
                'anchor' => array('type'=>'string'),
                'address' => array('type'=>'string'),
                'height' => array('type'=>'string'),
                'zoom' => array('type'=>'number'),
            ),
            'supports' => array('anchor' => true, 'html' => false),
        ));
    }
}
add_action('init', 'sixtythree_register_wpbb_fallback_blocks', 999);

function sixtythree_render_wpbb_content_block($attributes = array(), $content = '') {
    return $content;
}

function sixtythree_render_wpbb_placeholder_block($attributes = array(), $content = '') {
    if ($content !== '') { return $content; }
    $title = isset($attributes['title']) ? $attributes['title'] : 'WP BBuilder block';
    return '<div class="sixtythree-builder-note"><strong>' . esc_html($title) . '</strong><br>' . esc_html__('Install/activate WP BBuilder for the full editable admin block.', 'sixty-three-lv') . '</div>';
}

function sixtythree_render_wpbb_dynamic_form_fallback($attributes = array(), $content = '') {
    ob_start();
    echo '<div class="wpbb-dynamic-form-wrap sixtythree-bbuilder-fallback-block">';
    if (!empty($attributes['showTitle'])) {
        echo '<h3 class="wpbb-form-title">' . esc_html($attributes['formTitle'] ?? __('Contact form', 'sixty-three-lv')) . '</h3>';
    }
    sixtythree_fallback_contact_form();
    echo '</div>';
    return ob_get_clean();
}


/**
 * Translate the static headless front-page HTML for Polylang EN/RU.
 * The visual preview is authored once in Latvian; this replacement layer keeps it multilingual
 * without making every design fragment depend on separate templates.
 */
function sixtythree_front_translation_map() {
    return array(
        'en' => array(
            'Pakalpojumi' => 'Services',
            'Par mums' => 'About us',
            'Web izstrāde' => 'Web development',
            'Kontakti' => 'Contacts',
            'Atrašanās vieta' => 'Location',
            'Atvērt meklēšanu' => 'Open search',
            'Atvērt izvēlni' => 'Open menu',
            'Aizvērt izvēlni' => 'Close menu',
            'Meklēt' => 'Search',
            'Ierakstiet meklējamo...' => 'Type to search...',
            'Ātrie rezultāti tiks parādīti šeit, kad būs WordPress Ajax savienojums.' => 'Quick results will appear here when the WordPress Ajax connection is active.',
            'Bauskas 63 • Rīga' => 'Bauskas 63 • Riga',
            'Bauskas 63 · Rīga' => 'Bauskas 63 · Riga',
            'Telpas, zināšanas un pakalpojumi vienuviet' => 'Spaces, knowledge and services in one place',
            'ŠIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attīstām visu Latviju un pasauli. Praktiskas zināšanas tagad, zelta un soli klasiskā-modernā vizuālā valodā.' => 'SIA Kalns RTL was founded in 2003. We offer services on site at Bauskas Street 63 in Riga and develop practical solutions for Latvia and beyond.',
            'Mūsu pakalpojumi →' => 'Our services →',
            '63.lv pirts' => '63.lv sauna',
            'Lieliska vieta' => 'Great location',
            'Bauskas iela 63, Rīga — ērta piekļuve no ielas un pagalma puses.' => 'Bauskas Street 63, Riga — easy access from the street and courtyard.',
            'Elastīgi risinājumi' => 'Flexible solutions',
            'Telpas nomai, web izstrāde, pasākumi, pirts, skaistums, kursi un zināšanas praktiskam darbam.' => 'Rental spaces, web development, events, sauna, beauty and practical knowledge.',
            'Pieredze kopš 2003' => 'Experience since 2003',
            'Uzņēmums ar stabilu pamatu un spēju pielāgoties dažādām vajadzībām.' => 'A stable company with the ability to adapt to different needs.',
            'Personiska pieeja' => 'Personal approach',
            'Attieksme, komunikācija un praktisks risinājums — kādam vajadzīgs tieši šodien.' => 'Attitude, communication and practical solutions for today’s needs.',
            'Pirts ballīte' => 'Sauna party',
            'Atpūta ģimenei un draugiem' => 'Relaxation for family and friends',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās. Rekomendējam līdz 15 personām. Standarta piedāvājums €90 jeb €84.' => 'Weekdays from 16:00, weekends by agreement. Recommended for up to 15 people. Standard offer €90 or €84.',
            'Skatīt pirts zonu →' => 'View sauna zone →',
            'Viss svarīgākais vienā vietā' => 'Everything important in one place',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un Bauskas servisi — bet vizuāli pārvērsts uz premium, modern-classic priekšskatījumu.' => 'The content is based on 63.lv service directions — sauna, web development, training and Bauskas services — transformed into a premium modern-classic preview.',
            'Pirts ballītēm' => 'For sauna parties',
            'Web izstrāde un uzturēšana' => 'Web development and maintenance',
            'E-apmācības un kursi' => 'E-training and courses',
            'Citi pakalpojumi' => 'Other services',
            'Skatīt vairāk →' => 'View more →',
            'Bauskas 63 · pirts zona' => 'Bauskas 63 · sauna zone',
            'Vieta ģimenei, draugiem un svinībām' => 'A place for family, friends and celebrations',
            'Skaidrs pirts ballītes piedāvājums ar cenu, ilgumu, iekļautajiem pakalpojumiem, pieejamības kalendāru un ātru pieteikšanos.' => 'A clear sauna party offer with price, duration, included services, availability calendar and quick booking.',
            'Standarta piedāvājums' => 'Standard offer',
            'Cena par standarta pirts ballītes piedāvājumu.' => 'Price for the standard sauna party offer.',
            'Optimāls ilgums atpūtai un svinībām.' => 'Optimal duration for relaxation and celebrations.',
            'Ieteicamais viesu skaits ģimenēm un draugiem.' => 'Recommended guest count for families and friends.',
            'Pieteikšanās savlaicīgi, drošības nauda 50%.' => 'Book in advance, 50% security deposit.',
            'Pieejams' => 'Available',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās.' => 'Weekdays from 16:00, weekends by agreement.',
            'Saziņai' => 'Contact',
            'rezervācijām un pieejamībai.' => 'for reservations and availability.',
            'Kas iekļauts pirts ballītes paketē' => 'What is included in the sauna party package',
            'Sauna un slotiņas.' => 'Sauna and whisks.',
            'Zāle ar galdiem kopīgai atpūtai.' => 'Hall with tables for shared relaxation.',
            'Atpūtas telpas mierīgai pauzei.' => 'Relaxation rooms for a quiet break.',
            'Virtuve uzkodu sagatavošanai.' => 'Kitchen for preparing snacks.',
            'Baseins šobrīd restaurācijas procesā.' => 'Pool is currently under restoration.',
            'Bauskas iela 63, Rīga.' => 'Bauskas Street 63, Riga.',
            'Pieteikt pirts laiku →' => 'Book sauna time →',
            'Visi pakalpojumi' => 'All services',
            'Pieejamība' => 'Availability',
            'Maija rezervācijas' => 'May reservations',
            'Aizņemts' => 'Booked',
            'Izvēlēts' => 'Selected',
            'aizņemts' => 'booked',
            'brīvs' => 'free',
            'diena' => 'day',
            'Pieteikt izvēlēto laiku →' => 'Book selected time →',
            'Zvanīt' => 'Call',
            'Pirts blogs' => 'Sauna blog',
            'Jaunākie raksti' => 'Latest articles',
            'Blogs →' => 'Blog →',
            'Kā sagatavoties pirts vakaram?' => 'How to prepare for a sauna evening',
            'Īss ceļvedis, ko paņemt līdzi un kā plānot sešu stundu atpūtu.' => 'A short guide on what to bring and how to plan a six-hour relaxation evening.',
            'Pirts ballīte līdz 15 personām' => 'Sauna party for up to 15 people',
            'Ieteikumi nelielām svinībām, galdu izvietojumam un atpūtas ritmam.' => 'Tips for small celebrations, table layout and a comfortable event rhythm.',
            'Ko iekļaut pasākuma plānā?' => 'What to include in the event plan?',
            'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas vienā vakarā.' => 'Sauna, whisks, table hall, kitchen and relaxation rooms in one evening.',
            'Lasīt vairāk →' => 'Read more →',
            'Skaistumam' => 'For beauty',
            'Vertikālais solārijs — cenas' => 'Vertical solarium — prices',
            'Kompakta un skaidra cenu sadaļa ar 63.lv aktuālajām solārija cenām. Pieejams pēc iepriekšējas pieteikšanās, saziņai' => 'A compact and clear pricing section with current 63.lv solarium prices. Available by prior appointment, contact',
            'Sesija' => 'Session',
            'minūtes' => 'minutes',
            'Papildus' => 'Extra',
            'Krēms' => 'Cream',
            'Vertikālais solārijs' => 'Vertical solarium',
            'Pieraksts pēc vienošanās' => 'Appointment by agreement',
            'Iedegumam un labsajūtai' => 'For tanning and wellbeing',
            'Mūsdienīgs, kompakts un vizuāli izcelts piedāvājums ar skaidri nolasāmām cenām.' => 'A modern, compact and visually highlighted offer with clear pricing.',
            'Iznomā' => 'For rent',
            'Telpas biznesam' => 'Business premises',
            'Vieta, kur darbi notiek un idejas aug' => 'A place where work happens and ideas grow',
            'WordPress un full stack izstrāde' => 'WordPress and full stack development',
            'Praktiski pielāgoti biznesa risinājumi: mājaslapas, interneta veikali, integrācijas, uzturēšana un AI tēmu/plugin izstrāde.' => 'Practical business solutions: websites, online stores, integrations, maintenance and AI theme/plugin development.',
            'Apskatīt portfolio →' => 'View portfolio →',
            'Pieteikt web projektu' => 'Start a web project',
            'Web development cenas' => 'Web development prices',
            'Digitālie risinājumi ar skaidru sākuma cenu' => 'Digital solutions with clear starting prices',
            'Pieteikums un kontakti' => 'Request and contacts',
            'Pastāstiet par savu ideju vai vajadzību' => 'Tell us about your idea or need',
            'Saziņas forma' => 'Contact form',
            'Pieteikt konsultāciju' => 'Request a consultation',
            'Forma izmanto WP BBuilder Dynamic Form bloku, ja spraudnis ir aktīvs. Bez BBuilder redzams tēmas fallback.' => 'The form uses the WP BBuilder Dynamic Form block when the plugin is active. Without BBuilder, the theme fallback is shown.',
            'Atrašanās vieta' => 'Location',
            'Bauskas iela 63, Rīga' => 'Bauskas Street 63, Riga',
            'Sazināties →' => 'Contact →',
        ),
        'ru' => array(
            'Pakalpojumi' => 'Услуги',
            'Par mums' => 'О нас',
            'Web izstrāde' => 'Веб-разработка',
            'Kontakti' => 'Контакты',
            'Atrašanās vieta' => 'Местоположение',
            'Atvērt meklēšanu' => 'Открыть поиск',
            'Atvērt izvēlni' => 'Открыть меню',
            'Aizvērt izvēlni' => 'Закрыть меню',
            'Meklēt' => 'Поиск',
            'Ierakstiet meklējamo...' => 'Введите запрос...',
            'Ātrie rezultāti tiks parādīti šeit, kad būs WordPress Ajax savienojums.' => 'Быстрые результаты появятся здесь при активном WordPress Ajax.',
            'Bauskas 63 • Rīga' => 'Bauskas 63 • Рига',
            'Bauskas 63 · Rīga' => 'Bauskas 63 · Рига',
            'Telpas, zināšanas un pakalpojumi vienuviet' => 'Помещения, знания и услуги в одном месте',
            'Mūsu pakalpojumi →' => 'Наши услуги →',
            '63.lv pirts' => '63.lv сауна',
            'Lieliska vieta' => 'Отличное место',
            'Elastīgi risinājumi' => 'Гибкие решения',
            'Pieredze kopš 2003' => 'Опыт с 2003 года',
            'Personiska pieeja' => 'Индивидуальный подход',
            'Pirts ballīte' => 'Сауна-вечеринка',
            'Atpūta ģimenei un draugiem' => 'Отдых для семьи и друзей',
            'Skatīt pirts zonu →' => 'Смотреть сауну →',
            'Viss svarīgākais vienā vietā' => 'Всё важное в одном месте',
            'Pirts ballītēm' => 'Для сауна-вечеринок',
            'Web izstrāde un uzturēšana' => 'Веб-разработка и поддержка',
            'E-apmācības un kursi' => 'Онлайн-обучение и курсы',
            'Citi pakalpojumi' => 'Другие услуги',
            'Skatīt vairāk →' => 'Подробнее →',
            'Bauskas 63 · pirts zona' => 'Bauskas 63 · зона сауны',
            'Vieta ģimenei, draugiem un svinībām' => 'Место для семьи, друзей и праздников',
            'Skaidrs pirts ballītes piedāvājums ar cenu, ilgumu, iekļautajiem pakalpojumiem, pieejamības kalendāru un ātru pieteikšanos.' => 'Понятное предложение сауны с ценой, длительностью, включёнными услугами, календарём доступности и быстрой заявкой.',
            'Standarta piedāvājums' => 'Стандартное предложение',
            'Cena par standarta pirts ballītes piedāvājumu.' => 'Цена стандартного предложения сауны.',
            'Optimāls ilgums atpūtai un svinībām.' => 'Оптимальная длительность для отдыха и праздника.',
            'Ieteicamais viesu skaits ģimenēm un draugiem.' => 'Рекомендуемое количество гостей для семьи и друзей.',
            'Pieteikšanās savlaicīgi, drošības nauda 50%.' => 'Бронирование заранее, депозит 50%.',
            'Pieejams' => 'Доступно',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās.' => 'В рабочие дни с 16:00, в выходные по договорённости.',
            'Saziņai' => 'Связь',
            'rezervācijām un pieejamībai.' => 'для бронирования и доступности.',
            'Kas iekļauts pirts ballītes paketē' => 'Что входит в пакет сауны',
            'Sauna un slotiņas.' => 'Сауна и веники.',
            'Zāle ar galdiem kopīgai atpūtai.' => 'Зал со столами для отдыха.',
            'Atpūtas telpas mierīgai pauzei.' => 'Комнаты отдыха для спокойной паузы.',
            'Virtuve uzkodu sagatavošanai.' => 'Кухня для подготовки закусок.',
            'Baseins šobrīd restaurācijas procesā.' => 'Бассейн сейчас на реставрации.',
            'Bauskas iela 63, Rīga.' => 'Улица Баускас 63, Рига.',
            'Pieteikt pirts laiku →' => 'Забронировать сауну →',
            'Visi pakalpojumi' => 'Все услуги',
            'Pieejamība' => 'Доступность',
            'Maija rezervācijas' => 'Бронирования на май',
            'Aizņemts' => 'Занято',
            'Izvēlēts' => 'Выбрано',
            'aizņemts' => 'занято',
            'brīvs' => 'свободно',
            'diena' => 'день',
            'Pieteikt izvēlēto laiku →' => 'Забронировать выбранное →',
            'Zvanīt' => 'Позвонить',
            'Pirts blogs' => 'Блог сауны',
            'Jaunākie raksti' => 'Последние статьи',
            'Blogs →' => 'Блог →',
            'Kā sagatavoties pirts vakaram?' => 'Как подготовиться к вечеру в сауне',
            'Īss ceļvedis, ko paņemt līdzi un kā plānot sešu stundu atpūtu.' => 'Кратко: что взять с собой и как спланировать шесть часов отдыха.',
            'Pirts ballīte līdz 15 personām' => 'Сауна-вечеринка до 15 человек',
            'Ieteikumi nelielām svinībām, galdu izvietojumam un atpūtas ritmam.' => 'Советы для небольшого праздника, расстановки столов и ритма отдыха.',
            'Ko iekļaut pasākuma plānā?' => 'Что включить в план мероприятия?',
            'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas vienā vakarā.' => 'Сауна, веники, зал со столами, кухня и комнаты отдыха за один вечер.',
            'Lasīt vairāk →' => 'Читать дальше →',
            'Skaistumam' => 'Красота',
            'Vertikālais solārijs — cenas' => 'Вертикальный солярий — цены',
            'Sesija' => 'Сеанс',
            'minūtes' => 'минут',
            'Papildus' => 'Дополнительно',
            'Krēms' => 'Крем',
            'Vertikālais solārijs' => 'Вертикальный солярий',
            'Pieraksts pēc vienošanās' => 'Запись по договорённости',
            'Iedegumam un labsajūtai' => 'Для загара и самочувствия',
            'Iznomā' => 'Аренда',
            'Telpas biznesam' => 'Помещения для бизнеса',
            'Vieta, kur darbi notiek un idejas aug' => 'Место, где работа идёт и идеи растут',
            'WordPress un full stack izstrāde' => 'WordPress и full stack разработка',
            'Apskatīt portfolio →' => 'Смотреть портфолио →',
            'Pieteikt web projektu' => 'Заказать веб-проект',
            'Web development cenas' => 'Цены веб-разработки',
            'Digitālie risinājumi ar skaidru sākuma cenu' => 'Цифровые решения с понятной стартовой ценой',
            'Pieteikums un kontakti' => 'Заявка и контакты',
            'Pastāstiet par savu ideju vai vajadzību' => 'Расскажите об идее или потребности',
            'Saziņas forma' => 'Форма связи',
            'Pieteikt konsultāciju' => 'Запросить консультацию',
            'Atrašanās vieta' => 'Местоположение',
            'Bauskas iela 63, Rīga' => 'Улица Баускас 63, Рига',
            'Sazināties →' => 'Связаться →',
        ),
    );
}

function sixtythree_translate_front_html($html) {
    $lang = sixtythree_current_lang();
    if ($lang === 'lv') { return $html; }

    $map = sixtythree_front_translation_map();

    $extra_map = function_exists('sixtythree_front_translation_extra_map') ? sixtythree_front_translation_extra_map() : array();
    if (!empty($extra_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $extra_map[$lang]);
    }

    $complete_map = function_exists('sixtythree_front_translation_complete_map') ? sixtythree_front_translation_complete_map() : array();
    if (!empty($complete_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $complete_map[$lang]);
    }

    $live_map = function_exists('sixtythree_front_translation_live_override_map') ? sixtythree_front_translation_live_override_map() : array();
    if (!empty($live_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $live_map[$lang]);
    }

    $final_map = function_exists('sixtythree_front_translation_final_override_map') ? sixtythree_front_translation_final_override_map() : array();
    if (!empty($final_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $final_map[$lang]);
    }

    $modal_map = function_exists('sixtythree_front_translation_modal_override_map') ? sixtythree_front_translation_modal_override_map() : array();
    if (!empty($modal_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $modal_map[$lang]);
    }

    if (empty($map[$lang])) { return $html; }

    // Avoid partial-word corruption like ikdienas -> ikденьs.
    unset($map[$lang]['diena']);

    // Longest first so longer sentences are not partially replaced by shorter fragments.
    uksort($map[$lang], function($a, $b) { return mb_strlen($b) <=> mb_strlen($a); });
    foreach ($map[$lang] as $from => $to) {
        $html = str_replace($from, $to, $html);
    }
    return $html;
}


/**
 * Extra exact front-page translations added from live review.
 */
function sixtythree_front_translation_extra_map() {
    return array(
        'en' => array(
            'Ko vēlaties atrast?' => 'What would you like to find?',
            'Pirts ballītēm' => 'For sauna parties',
            'Manikīrs' => 'Manicure',
            'Kursi' => 'Courses',
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē. Priekšskatā izmantota gaiša, zeltaina un klasiskā-modernā vizuālā valoda.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide. The preview uses a light, golden and classic-modern visual language.',
            'Sazināties' => 'Contact us',
            'Privātpersonām, uzņēmumiem, telpu nomai, kursiem un ikdienas pakalpojumiem.' => 'For individuals, companies, space rental, courses and everyday services.',
            'Uzticams lokāls centrs ar plašu pakalpojumu klāstu vienā adresē.' => 'A trusted local centre with a wide range of services at one address.',
            'Palīdzam piemeklēt atbilstošāko risinājumu jūsu vajadzībām un idejām.' => 'We help find the most suitable solution for your needs and ideas.',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās. Rekomendējam līdz 15 personām. Standarta piedāvājums €84 jeb €14/h.' => 'Weekdays from 16:00, weekends by agreement. Recommended for up to 15 people. Standard offer €84 or €14/h.',
            'Digitāli risinājumi biznesam' => 'Digital solutions for business',
            'WordPress mājaslapas, headless React projekti, UX dizains, SEO, sociālo tīklu integrācijas, hostings un uzturēšana.' => 'WordPress websites, headless React projects, UX design, SEO, social media integrations, hosting and maintenance.',
            'Mācību centrs' => 'Training centre',
            'Tālmācības un klātienes kursi' => 'Remote and in-person courses',
            'Individuāli un grupās — uzņēmējdarbība, ekonomika, Zoom & Skype, web rīki, biroja darbi, kopēšana un printēšana.' => 'Individual and group training — business, economics, Zoom & Skype, web tools, office work, copying and printing.',
            'Kursi un pakalpojumi →' => 'Courses and services →',
            'Skaistumam un ērtībai' => 'For beauty and convenience',
            'Stāvošais solārijs, frizētava, manikīrs un pedikīrs, šuvēju pakalpojumi, mēbeļu remonts un citi risinājumi vienuviet.' => 'Vertical solarium, hairdresser, manicure and pedicure, tailoring, furniture repair and other solutions in one place.',
            'Pieteikt laiku →' => 'Book a time →',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikdienas servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'The content is based on 63.lv service directions — sauna, web development, training and everyday services — visually transformed into a premium artistic preview.',
            'Ģimenēm un draugiem' => 'For families and friends',
            'Līdz ~15 personām' => 'Up to ~15 people',
            'Darba dienās no 16:00' => 'Weekdays from 16:00',
            'UZZINĀT VAIRĀK →' => 'LEARN MORE →',
            'SEO, hostings, optimizācija' => 'SEO, hosting, optimisation',
            'Headless ReactJS risinājumi' => 'Headless ReactJS solutions',
            'Individuāli €15/h' => 'Individual €15/h',
            'Biroja darbi, kopēšana, printēšana' => 'Office work, copying, printing',
            'Vertikālais solārijs' => 'Vertical solarium',
            'Frizētava IEVA' => 'Hairdresser IEVA',
            'Manikīrs & pedikīrs' => 'Manicure & pedicure',
            'Šūšanas un remonta darbi' => 'Tailoring and repair work',
            'Pirts' => 'Sauna',
            'Apmācības' => 'Training',
            '€90 / akcijā €84' => '€90 / promo €84',
            'Līdz 15 personām' => 'Up to 15 people',
            '2 dienas iepriekš' => '2 days in advance',
            '10 minūtes' => '10 minutes',
            '12 minūtes' => '12 minutes',
            '14 minūtes' => '14 minutes',
            'Kompakta un skaidra cenu sadaļa ar 63.lv aktuālajām solārija cenām. Pieejams pēc iepriekšējas pieteikšanās, saziņai' => 'A compact and clear pricing section with current 63.lv solarium prices. Available by prior appointment, contact',
            'Telpas biznesam' => 'Business premises',
            'Atvērt piedāvājumu' => 'Open offer',
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā. Kreisais lielais vizuālis darbojas kā slideris — sākumā art-stila ēkas skats, pēc tam reālie iekšskati no telpām.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003. The large left visual works as a slider: first an art-style building view, then real interior views.',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Display windows and entrance from Bauskas Street.',
            'Atsevišķa ieeja no pagalma puses.' => 'Separate entrance from the courtyard side.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for an office, shop, salon and more.',
            'Šī sadaļa ir strukturēta ar digitālās aģentūras noskaņu — skaidrs pakalpojumu piedāvājums, tehnoloģiju fokuss un spēcīgs CTA virziens projekta pieteikšanai.' => 'This section is structured with a digital agency feel — clear services, technology focus and a strong CTA to start a project.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress sites to headless ReactJS projects — we build solutions that look good, are easy to manage and focus on business results. Also: AI theme/plugin development, hosting, support and maintenance.',
            'Tēmas, ACF bloki, administrēšana' => 'Themes, ACF blocks, administration',
            'Savienojumi, integrācijas, automatizācija' => 'Connections, integrations, automation',
            'Struktūra, satura ceļi, konversija' => 'Structure, content paths, conversion',
            'Pilnībā pielāgotas premium tēmas, dizaina sistēmas, Gutenberg bloki un ātrdarbības optimizācija.' => 'Fully customised premium themes, design systems, Gutenberg blocks and performance optimisation.',
            'E-veikali, maksājumu integrācijas, produktu katalogi, piegādes loģika un klientu pieredzes uzlabojumi.' => 'Online stores, payment integrations, product catalogues, delivery logic and customer experience improvements.',
            'Individuāli spraudņi, automatizācijas, rezervāciju formas, nomas pieteikumi un biznesa procesu digitalizācija.' => 'Custom plugins, automation, booking forms, rental requests and business process digitisation.',
            'WP kā CMS + React/Vite/Next frontend, API savienojumi un gatavība pārejai uz headless arhitektūru.' => 'WordPress as CMS + React/Vite/Next frontend, API connections and readiness for headless architecture.',
            'AI asistētas dizaina sistēmas, satura ģenerēšanas plūsmas, gudri WordPress bloki un automatizēti UI komponenti.' => 'AI-assisted design systems, content generation flows, smart WordPress blocks and automated UI components.',
            'Pielāgoti WordPress spraudņi ar AI funkcijām, API integrācijām, meklēšanu, rezervācijām un biznesa automatizāciju.' => 'Custom WordPress plugins with AI features, API integrations, search, bookings and business automation.',
            'Hostings, atjauninājumi, drošība, rezerves kopijas, veiktspējas uzlabojumi un regulārs tehniskais atbalsts.' => 'Hosting, updates, security, backups, performance improvements and regular technical support.',
            'Apskatīt portfolio' => 'View portfolio',
            'Cenas strukturētas pēc pašreizējās 63.lv pakalpojumu informācijas — sākot no biznesa mājaslapas līdz e-komercijai, uzturēšanai un hostingam.' => 'Prices are structured based on current 63.lv service information — from business websites to e-commerce, maintenance and hosting.',
            'Labs sākums uzņēmuma klātbūtnei internetā.' => 'A good start for a company presence online.',
            'Plašāka struktūra, vairāk sadaļu un pielāgots saturs.' => 'Wider structure, more sections and customised content.',
            'Individuālāka uzņēmuma/nozares mājaslapas izstrāde.' => 'More individual company/industry website development.',
            'WooCommerce / shop tipa risinājums pārdošanai tiešsaistē.' => 'WooCommerce / shop-type solution for online sales.',
            'Hosting 63.lv klientiem — no €3/mēn' => 'Hosting for 63.lv clients — from €3/month',
            'SEO, optimizācija, sociālo tīklu pieslēgšana' => 'SEO, optimisation, social network connection',
            'Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Šī sadaļa ir veidota kā spēcīgs CTA bloks ar kontaktiem un formu.' => 'We can help with space rental, web development, training and other services at Bauskas Street 63. This section is a strong CTA block with contacts and a form.',
            'Epasts' => 'Email',
            'Atbildēsim 1 darba dienas laikā.' => 'We reply within one working day.',
            'Tālrunis' => 'Phone',
            'Zvaniet vai rakstiet par pieejamību.' => 'Call or write about availability.',
            'Adrese' => 'Address',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Easy access from the street and courtyard side.',
            'Pilna platuma karte ar pelēku, elegantu vizuālo stilu un pārklātu adreses bloku. Vēlāk to var aizstāt ar pielāgotu Google Maps vai Leaflet risinājumu WordPress / headless vidē.' => 'A full-width map with an elegant grey visual style and an overlaid address block. It can later be replaced with a custom Google Maps or Leaflet solution in WordPress/headless.',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Space for rent at Bauskas Street 63, Riga',
            '⌖ Bauskas iela 63, Rīga · pieejamas uzreiz' => 'Bauskas Street 63, Riga · available now',
            'Lietošanas veids' => 'Usage type',
            'Koplietošana' => 'Shared costs',
            'Drošības nauda' => 'Security deposit',
            'Apkure pēc gāzes patēriņa' => 'Heating by gas consumption',
            'Elektrība pēc skaitītāja' => 'Electricity by meter',
            'Vitrīnas logi un ieeja no Bauskas ielas' => 'Display windows and entrance from Bauskas Street',
            'Atsevišķa ieeja no pagalma puses' => 'Separate entrance from courtyard side',
            'Atrašanās vieta ar aktīvu plūsmu, ērta piekļuve' => 'Active flow location, easy access',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for office, shop, salon and more',
            'Pieteikt apskati' => 'Book a viewing',
        ),
        'ru' => array(
            'Ko vēlaties atrast?' => 'Что вы хотите найти?',
            'Pirts ballītēm' => 'Для сауна-вечеринок',
            'Manikīrs' => 'Маникюр',
            'Kursi' => 'Курсы',
            'Sazināties' => 'Связаться',
            'Privātpersonām, uzņēmumiem, telpu nomai, kursiem un ikdienas pakalpojumiem.' => 'Для частных лиц, компаний, аренды помещений, курсов и ежедневных услуг.',
            'Uzticams lokāls centrs ar plašu pakalpojumu klāstu vienā adresē.' => 'Надёжный локальный центр с широким спектром услуг по одному адресу.',
            'Palīdzam piemeklēt atbilstošāko risinājumu jūsu vajadzībām un idejām.' => 'Помогаем подобрать лучшее решение для ваших потребностей и идей.',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās. Rekomendējam līdz 15 personām. Standarta piedāvājums €84 jeb €14/h.' => 'В рабочие дни с 16:00, в выходные по договорённости. Рекомендуем до 15 человек. Стандартное предложение €84 или €14/ч.',
            'Digitāli risinājumi biznesam' => 'Цифровые решения для бизнеса',
            'WordPress mājaslapas, headless React projekti, UX dizains, SEO, sociālo tīklu integrācijas, hostings un uzturēšana.' => 'Сайты WordPress, headless React проекты, UX дизайн, SEO, интеграции соцсетей, хостинг и поддержка.',
            'Mācību centrs' => 'Учебный центр',
            'Tālmācības un klātienes kursi' => 'Дистанционные и очные курсы',
            'Kursi un pakalpojumi →' => 'Курсы и услуги →',
            'Skaistumam un ērtībai' => 'Для красоты и удобства',
            'Pieteikt laiku →' => 'Записаться →',
            'Ģimenēm un draugiem' => 'Для семьи и друзей',
            'Līdz ~15 personām' => 'До ~15 человек',
            'Darba dienās no 16:00' => 'В рабочие дни с 16:00',
            'UZZINĀT VAIRĀK →' => 'УЗНАТЬ БОЛЬШЕ →',
            'SEO, hostings, optimizācija' => 'SEO, хостинг, оптимизация',
            'Headless ReactJS risinājumi' => 'Headless ReactJS решения',
            'Individuāli €15/h' => 'Индивидуально €15/ч',
            'Biroja darbi, kopēšana, printēšana' => 'Офисные работы, копирование, печать',
            'Frizētava IEVA' => 'Парикмахерская IEVA',
            'Manikīrs & pedikīrs' => 'Маникюр и педикюр',
            'Šūšanas un remonta darbi' => 'Швейные и ремонтные работы',
            'Pirts' => 'Сауна',
            'Apmācības' => 'Обучение',
            '€90 / akcijā €84' => '€90 / акция €84',
            'Līdz 15 personām' => 'До 15 человек',
            '2 dienas iepriekš' => 'За 2 дня',
            '10 minūtes' => '10 минут',
            '12 minūtes' => '12 минут',
            '14 minūtes' => '14 минут',
            'Telpas biznesam' => 'Помещения для бизнеса',
            'Atvērt piedāvājumu' => 'Открыть предложение',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Витринные окна и вход с улицы Баускас.',
            'Atsevišķa ieeja no pagalma puses.' => 'Отдельный вход со стороны двора.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Подходит для офиса, магазина, салона и др.',
            'Tēmas, ACF bloki, administrēšana' => 'Темы, ACF блоки, администрирование',
            'Savienojumi, integrācijas, automatizācija' => 'Подключения, интеграции, автоматизация',
            'Struktūra, satura ceļi, konversija' => 'Структура, пути контента, конверсия',
            'Apskatīt portfolio' => 'Смотреть портфолио',
            'Labs sākums uzņēmuma klātbūtnei internetā.' => 'Хорошее начало присутствия компании в интернете.',
            'Plašāka struktūra, vairāk sadaļu un pielāgots saturs.' => 'Более широкая структура, больше разделов и адаптированный контент.',
            'WooCommerce / shop tipa risinājums pārdošanai tiešsaistē.' => 'WooCommerce / магазин для онлайн-продаж.',
            'Hosting 63.lv klientiem — no €3/mēn' => 'Хостинг для клиентов 63.lv — от €3/мес',
            'Epasts' => 'Эл. почта',
            'Atbildēsim 1 darba dienas laikā.' => 'Ответим в течение 1 рабочего дня.',
            'Tālrunis' => 'Телефон',
            'Zvaniet vai rakstiet par pieejamību.' => 'Звоните или пишите о доступности.',
            'Adrese' => 'Адрес',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Удобный доступ с улицы и со двора.',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Помещение в аренду на улице Баускас 63, Рига',
            'Lietošanas veids' => 'Тип использования',
            'Koplietošana' => 'Общие расходы',
            'Drošības nauda' => 'Депозит',
            'Apkure pēc gāzes patēriņa' => 'Отопление по расходу газа',
            'Elektrība pēc skaitītāja' => 'Электричество по счётчику',
            'Pieteikt apskati' => 'Записаться на просмотр',
        ),
    );
}


function sixtythree_favicons() {
    $base = get_template_directory_uri() . '/assets/images/63lv/';
    $ver = SIXTYTHREE_THEME_VERSION . '-' . (file_exists(get_template_directory() . '/assets/images/63lv/touch.png') ? filemtime(get_template_directory() . '/assets/images/63lv/touch.png') : time());
    echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url($base . 'favicon-32.png?v=' . $ver) . '">' . "
";
    echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url($base . 'favicon-16.png?v=' . $ver) . '">' . "
";
    echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url($base . 'icon-192.png?v=' . $ver) . '">' . "
";
    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($base . 'apple-touch-icon-180.png?v=' . $ver) . '">' . "
";
    echo '<link rel="apple-touch-icon" sizes="512x512" href="' . esc_url($base . 'touch.png?v=' . $ver) . '">' . "
";
    echo '<link rel="shortcut icon" href="' . esc_url($base . 'favicon.ico?v=' . $ver) . '">' . "
";
}
add_action('wp_head', 'sixtythree_favicons', 100);
add_action('admin_head', 'sixtythree_favicons', 100);

/**
 * Complete front-page translation map for search, form placeholders, rent listing and remaining UI labels.
 */
function sixtythree_front_translation_complete_map() {
    return array(
        'en' => array(
            'Meklēt pakalpojumus, telpas, kursus vai kontaktus...' => 'Search services, spaces, courses or contacts...',
            'Ko vēlaties atrast?' => 'What would you like to find?',
            'Aizvērt meklēšanu' => 'Close search',
            'Pirts galerijas attēls 1' => 'Sauna gallery image 1',
            'Pirts galerijas attēls 2' => 'Sauna gallery image 2',
            'Pirts galerijas attēls 3' => 'Sauna gallery image 3',
            'Pirts galerijas attēls 4' => 'Sauna gallery image 4',
            'Pirts galerijas attēls 5' => 'Sauna gallery image 5',
            'Pirts galerijas attēls 6' => 'Sauna gallery image 6',
            'Vertikālais solārijs Bauskas ielā 63' => 'Vertical solarium at Bauskas Street 63',
            'Telpu attēli' => 'Space images',
            'Ēkas art vizuālis' => 'Art-style building visual',
            'Google Map Bauskas iela 63, Rīga' => 'Google Map Bauskas Street 63, Riga',
            'Aizvērt galeriju' => 'Close gallery',
            'Iepriekšējais attēls' => 'Previous image',
            'Nākamais attēls' => 'Next image',
            'Telpa nomai Bauskas ielā 63' => 'Space for rent at Bauskas Street 63',
            'Aizvērt' => 'Close',
            'Vārds' => 'Name',
            'Uzņēmums / projekts' => 'Company / project',
            'Epasts' => 'Email',
            'Tālrunis' => 'Phone',
            'Interesējošais pakalpojums' => 'Service of interest',
            'Ziņa' => 'Message',
            'Pastāstiet īsi par savu vajadzību...' => 'Tell us briefly what you need...',
            'Nosūtīt pieteikumu' => 'Send request',
            'Pieteikt konsultāciju' => 'Request a consultation',
            'Telpu noma' => 'Space rental',
            'Pirts zona' => 'Sauna zone',
            'Solārijs' => 'Solarium',
            'Citi pakalpojumi' => 'Other services',
            'Manikīrs' => 'Manicure',
            'Kursi' => 'Courses',
            'Services · Bauskas 63' => 'Services · Bauskas 63',
            'Pirts ballītēm' => 'For sauna parties',
            'Mūsu pakalpojumi →' => 'Our services →',
            'Sazināties' => 'Contact',
            'Bauskas iela 63, Rīga — ērta piekļuve no ielas un pagalma puses.' => 'Bauskas Street 63, Riga — easy access from the street and courtyard.',
            'Privātpersonām, uzņēmumiem, telpu nomai, kursiem un ikdienas pakalpojumiem.' => 'For individuals, companies, space rental, courses and everyday services.',
            'Uzticams lokāls centrs ar plašu pakalpojumu klāstu vienā adresē.' => 'A trusted local centre with a wide range of services at one address.',
            'Palīdzam piemeklēt atbilstošāko risinājumu jūsu vajadzībām un idejām.' => 'We help find the most suitable solution for your needs and ideas.',
            'Darba dienās no 16:00, brīvdienās pēc vienošanās. Rekomendējam līdz 15 personām. Standarta piedāvājums €84 jeb €14/h.' => 'Weekdays from 16:00, weekends by agreement. Recommended for up to 15 people. Standard offer €84 or €14/h.',
            'Digitāli risinājumi biznesam' => 'Digital solutions for business',
            'WordPress mājaslapas, headless React projekti, UX dizains, SEO, sociālo tīklu integrācijas, hostings un uzturēšana.' => 'WordPress websites, headless React projects, UX design, SEO, social media integrations, hosting and maintenance.',
            'Mācību centrs' => 'Training centre',
            'Tālmācības un klātienes kursi' => 'Remote and in-person courses',
            'Individuāli un grupās — uzņēmējdarbība, ekonomika, Zoom & Skype, web rīki, biroja darbi, kopēšana un printēšana.' => 'Individual and group training — business, economics, Zoom & Skype, web tools, office work, copying and printing.',
            'Kursi un pakalpojumi →' => 'Courses and services →',
            'Skaistumam un ērtībai' => 'For beauty and convenience',
            'Stāvošais solārijs, frizētava, manikīrs un pedikīrs, šuvēju pakalpojumi, mēbeļu remonts un citi risinājumi vienuviet.' => 'Vertical solarium, hairdresser, manicure and pedicure, tailoring, furniture repair and other solutions in one place.',
            'Pieteikt laiku →' => 'Book a time →',
            'Pakalpojumi' => 'Services',
            'Viss svarīgākais vienā vietā' => 'Everything important in one place',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikdienas servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'The content is based on 63.lv service directions — sauna, web development, training and everyday services — visually transformed into a premium artistic preview.',
            'Ģimenēm un draugiem' => 'For families and friends',
            'Līdz ~15 personām' => 'Up to ~15 people',
            'Darba dienās no 16:00' => 'Weekdays from 16:00',
            'UZZINĀT VAIRĀK →' => 'LEARN MORE →',
            'SEO, hostings, optimizācija' => 'SEO, hosting, optimisation',
            'Headless ReactJS risinājumi' => 'Headless ReactJS solutions',
            'Individuāli €15/h' => 'Individual €15/h',
            'Biroja darbi, kopēšana, printēšana' => 'Office work, copying, printing',
            'Vertikālais solārijs' => 'Vertical solarium',
            'Frizētava IEVA' => 'Hairdresser IEVA',
            'Manikīrs & pedikīrs' => 'Manicure & pedicure',
            'Šūšanas un remonta darbi' => 'Tailoring and repair work',
            'Pirts' => 'Sauna',
            'Apmācības' => 'Training',
            '€90 / akcijā €84' => '€90 / promo €84',
            'Līdz 15 personām' => 'Up to 15 people',
            '2 dienas iepriekš' => '2 days in advance',
            '10 minūtes' => '10 minutes',
            '12 minūtes' => '12 minutes',
            '14 minūtes' => '14 minutes',
            'Old to New Website — no €1050' => 'Old to New Website — from €1050',
            'Maintenance / Coding — no €25/h' => 'Maintenance / Coding — from €25/h',
            'Hosting 63.lv klientiem — no €3/mēn' => 'Hosting for 63.lv clients — from €3/month',
            'SEO, optimizācija, sociālo tīklu pieslēgšana' => 'SEO, optimisation, social media connection',
            'Telpas biznesam' => 'Business premises',
            'Atvērt piedāvājumu' => 'Open offer',
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā. Kreisais lielais vizuālis darbojas kā slideris — sākumā art-stila ēkas skats, pēc tam reālie iekšskati no telpām.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003. The large left visual works as a slider: first an art-style building view, then real interior views.',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Display windows and entrance from Bauskas Street.',
            'Atsevišķa ieeja no pagalma puses.' => 'Separate entrance from the courtyard side.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for an office, shop, salon and more.',
            'Šī sadaļa ir strukturēta ar digitālās aģentūras noskaņu — skaidrs pakalpojumu piedāvājums, tehnoloģiju fokuss un spēcīgs CTA virziens projekta pieteikšanai.' => 'This section is structured with a digital agency feel — clear services, technology focus and a strong CTA to start a project.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress sites to headless ReactJS projects — we build solutions that look good, are easy to manage and focus on business results. Also: AI theme/plugin development, hosting, support and maintenance.',
            'Tēmas, ACF bloki, administrēšana' => 'Themes, ACF blocks, administration',
            'Savienojumi, integrācijas, automatizācija' => 'Connections, integrations, automation',
            'Struktūra, satura ceļi, konversija' => 'Structure, content paths, conversion',
            'Pilnībā pielāgotas premium tēmas, dizaina sistēmas, Gutenberg bloki un ātrdarbības optimizācija.' => 'Fully customised premium themes, design systems, Gutenberg blocks and performance optimisation.',
            'E-veikali, maksājumu integrācijas, produktu katalogi, piegādes loģika un klientu pieredzes uzlabojumi.' => 'Online stores, payment integrations, product catalogues, delivery logic and customer experience improvements.',
            'Individuāli spraudņi, automatizācijas, rezervāciju formas, nomas pieteikumi un biznesa procesu digitalizācija.' => 'Custom plugins, automation, booking forms, rental requests and business process digitisation.',
            'WP kā CMS + React/Vite/Next frontend, API savienojumi un gatavība pārejai uz headless arhitektūru.' => 'WordPress as CMS + React/Vite/Next frontend, API connections and readiness for headless architecture.',
            'AI asistētas dizaina sistēmas, satura ģenerēšanas plūsmas, gudri WordPress bloki un automatizēti UI komponenti.' => 'AI-assisted design systems, content generation flows, smart WordPress blocks and automated UI components.',
            'Pielāgoti WordPress spraudņi ar AI funkcijām, API integrācijām, meklēšanu, rezervācijām un biznesa automatizāciju.' => 'Custom WordPress plugins with AI features, API integrations, search, bookings and business automation.',
            'Hostings, atjauninājumi, drošība, rezerves kopijas, veiktspējas uzlabojumi un regulārs tehniskais atbalsts.' => 'Hosting, updates, security, backups, performance improvements and regular technical support.',
            'Apskatīt portfolio' => 'View portfolio',
            'Pieteikt web projektu' => 'Start a web project',
            'Cenas strukturētas pēc pašreizējās 63.lv pakalpojumu informācijas — sākot no biznesa mājaslapas līdz e-komercijai, uzturēšanai un hostingam.' => 'Prices are structured based on current 63.lv service information — from a business website to e-commerce, maintenance and hosting.',
            'Labs sākums uzņēmuma klātbūtnei internetā.' => 'A good start for a company presence online.',
            'Plašāka struktūra, vairāk sadaļu un pielāgots saturs.' => 'Wider structure, more sections and tailored content.',
            'Individuālāka uzņēmuma/nozares mājaslapas izstrāde.' => 'More individual company/industry website development.',
            'WooCommerce / shop tipa risinājums pārdošanai tiešsaistē.' => 'WooCommerce / shop-type solution for online sales.',
            'Pastāstiet par savu ideju vai vajadzību' => 'Tell us about your idea or need',
            'Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Šī sadaļa ir veidota kā spēcīgs CTA bloks ar kontaktiem un formu.' => 'We can help with space rental, web development, training and other services at Bauskas Street 63. This section is a strong CTA block with contacts and a form.',
            'Atbildēsim 1 darba dienas laikā.' => 'We reply within one working day.',
            'Zvaniet vai rakstiet par pieejamību.' => 'Call or write about availability.',
            'Adrese' => 'Address',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Easy access from the street and courtyard side.',
            'Pilna platuma karte ar pelēku, elegantu vizuālo stilu un pārklātu adreses bloku. Vēlāk to var aizstāt ar pielāgotu Google Maps vai Leaflet risinājumu WordPress / headless vidē.' => 'A full-width map with an elegant grey visual style and an overlaid address block. It can later be replaced with a custom Google Maps or Leaflet solution in WordPress/headless.',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Space for rent at Bauskas Street 63, Riga',
            '⌖ Bauskas iela 63, Rīga · pieejamas uzreiz' => 'Bauskas Street 63, Riga · available now',
            'Lietošanas veids' => 'Usage type',
            '370 €/mēn' => '€370/month',
            'Koplietošana' => 'Shared costs',
            '40 €/mēn' => '€40/month',
            'Drošības nauda' => 'Security deposit',
            'Apkure pēc gāzes patēriņa' => 'Heating by gas consumption',
            'Elektrība pēc skaitītāja' => 'Electricity by meter',
            'Vitrīnas logi un ieeja no Bauskas ielas' => 'Display windows and entrance from Bauskas Street',
            'Atsevišķa ieeja no pagalma puses' => 'Separate entrance from the courtyard side',
            'Atrašanās vieta ar aktīvu plūsmu, ērta piekļuve' => 'Active flow location, easy access',
            'Pieteikt apskati' => 'Book a viewing',
        ),
        'ru' => array(
            'Meklēt pakalpojumus, telpas, kursus vai kontaktus...' => 'Искать услуги, помещения, курсы или контакты...',
            'Ko vēlaties atrast?' => 'Что вы хотите найти?',
            'Aizvērt meklēšanu' => 'Закрыть поиск',
            'Pirts galerijas attēls 1' => 'Изображение сауны 1',
            'Pirts galerijas attēls 2' => 'Изображение сауны 2',
            'Pirts galerijas attēls 3' => 'Изображение сауны 3',
            'Pirts galerijas attēls 4' => 'Изображение сауны 4',
            'Pirts galerijas attēls 5' => 'Изображение сауны 5',
            'Pirts galerijas attēls 6' => 'Изображение сауны 6',
            'Vertikālais solārijs Bauskas ielā 63' => 'Вертикальный солярий на улице Баускас 63',
            'Telpu attēli' => 'Изображения помещений',
            'Ēkas art vizuālis' => 'Художественный вид здания',
            'Google Map Bauskas iela 63, Rīga' => 'Google Map улица Баускас 63, Рига',
            'Aizvērt galeriju' => 'Закрыть галерею',
            'Iepriekšējais attēls' => 'Предыдущее изображение',
            'Nākamais attēls' => 'Следующее изображение',
            'Telpa nomai Bauskas ielā 63' => 'Помещение в аренду на улице Баускас 63',
            'Aizvērt' => 'Закрыть',
            'Vārds' => 'Имя',
            'Uzņēmums / projekts' => 'Компания / проект',
            'Epasts' => 'Эл. почта',
            'Tālrunis' => 'Телефон',
            'Interesējošais pakalpojums' => 'Интересующая услуга',
            'Ziņa' => 'Сообщение',
            'Pastāstiet īsi par savu vajadzību...' => 'Кратко опишите вашу потребность...',
            'Nosūtīt pieteikumu' => 'Отправить заявку',
            'Pieteikt konsultāciju' => 'Запросить консультацию',
            'Telpu noma' => 'Аренда помещений',
            'Pirts zona' => 'Зона сауны',
            'Web izstrāde' => 'Веб-разработка',
            'Solārijs' => 'Солярий',
            'Citi pakalpojumi' => 'Другие услуги',
            'Manikīrs' => 'Маникюр',
            'Kursi' => 'Курсы',
            'Pirts ballītēm' => 'Для сауна-вечеринок',
            'Mūsu pakalpojumi →' => 'Наши услуги →',
            'Sazināties' => 'Связаться',
            'Pakalpojumi' => 'Услуги',
            'Ģimenēm un draugiem' => 'Для семьи и друзей',
            'Līdz ~15 personām' => 'До ~15 человек',
            'Darba dienās no 16:00' => 'В рабочие дни с 16:00',
            'UZZINĀT VAIRĀK →' => 'УЗНАТЬ БОЛЬШЕ →',
            'SEO, hostings, optimizācija' => 'SEO, хостинг, оптимизация',
            'Headless ReactJS risinājumi' => 'Headless ReactJS решения',
            'Individuāli €15/h' => 'Индивидуально €15/ч',
            'Biroja darbi, kopēšana, printēšana' => 'Офисные работы, копирование, печать',
            'Vertikālais solārijs' => 'Вертикальный солярий',
            'Frizētava IEVA' => 'Парикмахерская IEVA',
            'Manikīrs & pedikīrs' => 'Маникюр и педикюр',
            'Šūšanas un remonta darbi' => 'Швейные и ремонтные работы',
            'Pirts' => 'Сауна',
            'Apmācības' => 'Обучение',
            '€90 / akcijā €84' => '€90 / акция €84',
            'Līdz 15 personām' => 'До 15 человек',
            '2 dienas iepriekš' => 'За 2 дня',
            '10 minūtes' => '10 минут',
            '12 minūtes' => '12 минут',
            '14 minūtes' => '14 минут',
            'Telpas biznesam' => 'Помещения для бизнеса',
            'Atvērt piedāvājumu' => 'Открыть предложение',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Витринные окна и вход с улицы Баускас.',
            'Atsevišķa ieeja no pagalma puses.' => 'Отдельный вход со стороны двора.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Подходит для офиса, магазина, салона и др.',
            'Tēmas, ACF bloki, administrēšana' => 'Темы, ACF блоки, администрирование',
            'Savienojumi, integrācijas, automatizācija' => 'Подключения, интеграции, автоматизация',
            'Struktūra, satura ceļi, konversija' => 'Структура, пути контента, конверсия',
            'Apskatīt portfolio' => 'Смотреть портфолио',
            'Pieteikt web projektu' => 'Заказать веб-проект',
            'Labs sākums uzņēmuma klātbūtnei internetā.' => 'Хорошее начало присутствия компании в интернете.',
            'Plašāka struktūra, vairāk sadaļu un pielāgots saturs.' => 'Более широкая структура, больше разделов и адаптированный контент.',
            'Individuālāka uzņēmuma/nozares mājaslapas izstrāde.' => 'Более индивидуальная разработка сайта компании/отрасли.',
            'WooCommerce / shop tipa risinājums pārdošanai tiešsaistē.' => 'WooCommerce / магазин для онлайн-продаж.',
            'Pastāstiet par savu ideju vai vajadzību' => 'Расскажите об идее или потребности',
            'Atbildēsim 1 darba dienas laikā.' => 'Ответим в течение 1 рабочего дня.',
            'Zvaniet vai rakstiet par pieejamību.' => 'Звоните или пишите о доступности.',
            'Adrese' => 'Адрес',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Удобный доступ с улицы и со двора.',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Помещение в аренду на улице Баускас 63, Рига',
            '⌖ Bauskas iela 63, Rīga · pieejamas uzreiz' => 'Улица Баускас 63, Рига · доступно сразу',
            'Lietošanas veids' => 'Тип использования',
            '370 €/mēn' => '€370/мес',
            'Koplietošana' => 'Общие расходы',
            '40 €/mēn' => '€40/мес',
            'Drošības nauda' => 'Депозит',
            'Apkure pēc gāzes patēriņa' => 'Отопление по расходу газа',
            'Elektrība pēc skaitītāja' => 'Электричество по счётчику',
            'Vitrīnas logi un ieeja no Bauskas ielas' => 'Витринные окна и вход с улицы Баускас',
            'Atsevišķa ieeja no pagalma puses' => 'Отдельный вход со стороны двора',
            'Atrašanās vieta ar aktīvu plūsmu, ērta piekļuve' => 'Локация с активным потоком, удобный доступ',
            'Pieteikt apskati' => 'Записаться на просмотр',
        ),
    );
}


/**
 * Live-reviewed translation overrides for strings that remained untranslated.
 * Also avoids partial word replacements like ikdienas -> ikденьs.
 */
function sixtythree_front_translation_live_override_map() {
    return array(
        'en' => array(
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē. Priekšskatā izmantota gaiša, zeltaina un klasiskā-modernā vizuālā valoda.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide. The preview uses a light, golden and classic-modern visual language.',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikdienas servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'The content is based on 63.lv service directions — sauna, web development, training and everyday services — visually transformed into a premium artistic preview.',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikденьs servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'The content is based on 63.lv service directions — sauna, web development, training and everyday services — visually transformed into a premium artistic preview.',
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide.',
            'Priekšskatā izmantota gaiša, zeltaina un klasiskā-modernā vizuālā valoda.' => 'The preview uses a light, golden and classic-modern visual language.',
            'ikdienas servisi' => 'everyday services',
            '>diena<' => '>day<',
            ' diena<' => ' day<',
            'diena</small>' => 'day</small>',
            'Ko vēlaties atrast?' => 'What would you like to find?',
            'Meklēt pakalpojumus, telpas, kursus vai kontaktus...' => 'Search services, spaces, courses or contacts...',
            'Vārds' => 'Name',
            'Uzņēmums / projekts' => 'Company / project',
            'Epasts' => 'Email',
            'Tālrunis' => 'Phone',
            'Interesējošais pakalpojums' => 'Service of interest',
            'Ziņa' => 'Message',
            'Pastāstiet īsi par savu vajadzību...' => 'Tell us briefly what you need...',
            'Nosūtīt pieteikumu' => 'Send request',
            'Telpu noma' => 'Space rental',
            'Pirts zona' => 'Sauna zone',
            'Citi pakalpojumi' => 'Other services',
        ),
        'ru' => array(
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē. Priekšskatā izmantota gaiša, zeltaina un klasiskā-modernā vizuālā valoda.' => 'SIA Kalns RTL основана в 2003 году. Мы предоставляем услуги на месте по адресу улица Баускас 63 в Риге, а также удалённо по всей Латвии и миру. В превью использован светлый, золотистый и классически-современный визуальный стиль.',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikdienas servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'Контент основан на направлениях услуг 63.lv — сауна, веб-разработка, обучение и ежедневные сервисы — и визуально перенесён в премиальное художественное превью.',
            'Saturs ir balstīts uz 63.lv pakalpojumu virzieniem — pirts, web izstrāde, apmācības un ikденьs servisi — bet vizuāli pārnests uz premium, māksliniecisku priekšskatījumu.' => 'Контент основан на направлениях услуг 63.lv — сауна, веб-разработка, обучение и ежедневные сервисы — и визуально перенесён в премиальное художественное превью.',
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē.' => 'SIA Kalns RTL основана в 2003 году. Мы предоставляем услуги на месте по адресу улица Баускас 63 в Риге, а также удалённо по всей Латвии и миру.',
            'Priekšskatā izmantota gaiša, zeltaina un klasiskā-modernā vizuālā valoda.' => 'В превью использован светлый, золотистый и классически-современный визуальный стиль.',
            'ikdienas servisi' => 'ежедневные сервисы',
            'ikденьs servisi' => 'ежедневные сервисы',
            '>diena<' => '>день<',
            ' diena<' => ' день<',
            'diena</small>' => 'день</small>',
            'Ko vēlaties atrast?' => 'Что вы хотите найти?',
            'Meklēt pakalpojumus, telpas, kursus vai kontaktus...' => 'Искать услуги, помещения, курсы или контакты...',
            'Vārds' => 'Имя',
            'Uzņēmums / projekts' => 'Компания / проект',
            'Epasts' => 'Эл. почта',
            'Tālrunis' => 'Телефон',
            'Interesējošais pakalpojums' => 'Интересующая услуга',
            'Ziņa' => 'Сообщение',
            'Pastāstiet īsi par savu vajadzību...' => 'Кратко опишите вашу потребность...',
            'Nosūtīt pieteikumu' => 'Отправить заявку',
            'Telpu noma' => 'Аренда помещений',
            'Pirts zona' => 'Зона сауны',
            'Citi pakalpojumi' => 'Другие услуги',
        ),
    );
}


/**
 * Final live translation overrides for remaining un-translated Latvian/Russian-mixed text.
 */
function sixtythree_front_translation_final_override_map() {
    return array(
        'en' => array(
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā. Kreisais lielais vizuālis darbojas kā slideris — sākumā art-stila ēkas skats, pēc tam reālie iekšskati no telpām.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003. The large left visual works as a slider: first an art-style building view, then real interior views.',
            'Šī sadaļa ir strukturēta ar digitālās aģentūras noskaņu — skaidrs pakalpojumu piedāvājums, tehnoloģiju fokuss un spēcīgs CTA virziens projekta pieteikšanai.' => 'This section is structured with a digital agency feel — clear services, technology focus and a strong CTA direction for project requests.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress pages to headless ReactJS projects — we build solutions that are visually refined, easy to manage and focused on business results. Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress pages to headless ReactJS projects — we build solutions that are visually refined, easy to manage and focused on business results. Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Bauskas 63, Rīga' => 'Bauskas 63, Riga',
            'Bauskas iela 63, Rīga' => 'Bauskas Street 63, Riga',
            'Web Themes' => 'Web Themes',
            'WooCommerce' => 'WooCommerce',
            'Plugins & Custom Code' => 'Plugins & Custom Code',
            'Headless ReactJS' => 'Headless ReactJS',
            'AI theme development' => 'AI theme development',
            'AI plugin development' => 'AI plugin development',
            'Hosting, support & maintenance' => 'Hosting, support & maintenance',
        ),
        'ru' => array(
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā. Kreisais lielais vizuālis darbojas kā slideris — sākumā art-stila ēkas skats, pēc tam reālie iekšskati no telpām.' => 'Современные помещения, индивидуальный подход и качественные услуги — с 2003 года мы поддерживаем ваши повседневные и бизнес-задачи. Большой визуальный блок слева работает как слайдер: сначала художественный вид здания, затем реальные интерьеры помещений.',
            'Šī sadaļa ir strukturēta ar digitālās aģentūras noskaņu — skaidrs pakalpojumu piedāvājums, tehnoloģiju fokuss un spēcīgs CTA virziens projekta pieteikšanai.' => 'Этот раздел оформлен в стиле цифрового агентства — понятное предложение услуг, фокус на технологиях и сильный CTA для заявки на проект.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'От классических сайтов WordPress до headless ReactJS проектов — создаём решения, которые выглядят качественно, легко управляются и ориентированы на бизнес-результат. Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'От классических сайтов WordPress до headless ReactJS проектов — создаём решения, которые выглядят качественно, легко управляются и ориентированы на бизнес-результат. Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Bauskas 63, Rīga' => 'Баускас 63, Рига',
            'Bauskas iela 63, Rīga' => 'Улица Баускас 63, Рига',
            'Web Themes' => 'Веб-темы',
            'WooCommerce' => 'WooCommerce',
            'Plugins & Custom Code' => 'Плагины и custom-код',
            'Headless ReactJS' => 'Headless ReactJS',
            'AI theme development' => 'Разработка AI тем',
            'AI plugin development' => 'Разработка AI плагинов',
            'Hosting, support & maintenance' => 'Хостинг, поддержка и обслуживание',
        ),
    );
}


function sixtythree_front_translation_modal_override_map() {
    return array(
        'en' => array(
            'Telpa nomai Bauskas ielā 63' => 'Space for rent at Bauskas Street 63',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Space for rent at Bauskas Street 63, Riga',
            '⌖ Bauskas iela 63, Rīga · pieejamas uzreiz' => 'Bauskas Street 63, Riga · available now',
            'Telpas biznesam' => 'Business premises',
            'Lietošanas veids' => 'Usage type',
            'Uzreiz' => 'Now',
            'Pieejamas' => 'Available',
            'Noma' => 'Rent',
            '370 €/mēn' => '€370/month',
            'Koplietošana' => 'Shared costs',
            '40 €/mēn' => '€40/month',
            'Drošības nauda' => 'Security deposit',
            'Apkure pēc gāzes patēriņa' => 'Heating by gas consumption',
            'Elektrība pēc skaitītāja' => 'Electricity by meter',
            '105 m² telpu plānojums Bauskas ielā 63' => '105 m² floor plan at Bauskas Street 63',
            'Bauskas 63 ēkas foto' => 'Bauskas 63 building photo',
            'Vitrīnas logi un ieeja no Bauskas ielas' => 'Display windows and entrance from Bauskas Street',
            'Atsevišķa ieeja no pagalma puses' => 'Separate entrance from the courtyard side',
            'Svaigs remonts' => 'Fresh renovation',
            'Atrašanās vieta ar aktīvu plūsmu, ērta piekļuve' => 'Active-flow location with easy access',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for an office, shop, salon and more',
            'Pieteikt apskati' => 'Book a viewing',
            'Sazināties →' => 'Contact →',
            'Aizvērt' => 'Close',
            'Pirts galerijas attēls' => 'Sauna gallery image',
        ),
        'ru' => array(
            'Telpa nomai Bauskas ielā 63' => 'Помещение в аренду на улице Баускас 63',
            'Telpa nomai Bauskas ielā 63, Rīgā' => 'Помещение в аренду на улице Баускас 63, Рига',
            '⌖ Bauskas iela 63, Rīga · pieejamas uzreiz' => 'Улица Баускас 63, Рига · доступно сразу',
            'Telpas biznesam' => 'Помещения для бизнеса',
            'Lietošanas veids' => 'Тип использования',
            'Uzreiz' => 'Сразу',
            'Pieejamas' => 'Доступно',
            'Noma' => 'Аренда',
            '370 €/mēn' => '€370/мес',
            'Koplietošana' => 'Общие расходы',
            '40 €/mēn' => '€40/мес',
            'Drošības nauda' => 'Депозит',
            'Apkure pēc gāzes patēriņa' => 'Отопление по расходу газа',
            'Elektrība pēc skaitītāja' => 'Электричество по счётчику',
            '105 m² telpu plānojums Bauskas ielā 63' => 'Планировка 105 м² на улице Баускас 63',
            'Bauskas 63 ēkas foto' => 'Фото здания Баускас 63',
            'Vitrīnas logi un ieeja no Bauskas ielas' => 'Витринные окна и вход с улицы Баускас',
            'Atsevišķa ieeja no pagalma puses' => 'Отдельный вход со стороны двора',
            'Svaigs remonts' => 'Свежий ремонт',
            'Atrašanās vieta ar aktīvu plūsmu, ērta piekļuve' => 'Локация с активным потоком и удобным доступом',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Подходит для офиса, магазина, салона и др.',
            'Pieteikt apskati' => 'Записаться на просмотр',
            'Sazināties →' => 'Связаться →',
            'Aizvērt' => 'Закрыть',
            'Pirts galerijas attēls' => 'Изображение сауны',
        ),
    );
}
/**
 * Editor-side fallback registrations for WP BBuilder blocks that may be disabled or not
 * registered by the plugin. This prevents "Your site doesn’t include support for the
 * wpbb/google-map block" while still allowing the real BBuilder block to take over
 * when it is available.
 */
function sixtythree_editor_fallback_blocks() {
    $file = get_template_directory() . '/assets/js/editor-fallback-blocks.js';
    wp_enqueue_script(
        'sixtythree-editor-fallback-blocks',
        get_template_directory_uri() . '/assets/js/editor-fallback-blocks.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-dom-ready'),
        file_exists($file) ? filemtime($file) : SIXTYTHREE_THEME_VERSION,
        true
    );
}
add_action('enqueue_block_editor_assets', 'sixtythree_editor_fallback_blocks', 99);

/**
 * Core-block friendly shortcodes used by the admin-editable demo homepage.
 * These avoid unsupported custom block warnings while preserving front-end functionality.
 */
function sixtythree_shortcode_contact_form($atts = array()) {
    ob_start();
    echo '<div class="wpbb-dynamic-form-wrap sixtythree-shortcode-form">';
    sixtythree_fallback_contact_form();
    echo '</div>';
    return ob_get_clean();
}
add_shortcode('sixtythree_contact_form', 'sixtythree_shortcode_contact_form');

function sixtythree_shortcode_google_map($atts = array()) {
    $atts = shortcode_atts(array(
        'address' => 'Bauskas iela 63, Rīga',
        'height' => '440px',
        'zoom' => '15',
    ), $atts, 'sixtythree_google_map');

    $src = 'https://maps.google.com/maps?q=' . rawurlencode($atts['address']) . '&t=&z=' . intval($atts['zoom']) . '&ie=UTF8&iwloc=&output=embed';
    return '<div class="sixtythree-shortcode-map"><iframe title="Google Map" src="' . esc_url($src) . '" style="width:100%;height:' . esc_attr($atts['height']) . ';border:0;border-radius:22px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
}
add_shortcode('sixtythree_google_map', 'sixtythree_shortcode_google_map');

function sixtythree_shortcode_booking_calendar($atts = array()) {
    $days = array(
        array('12','aizņemts','booked'),
        array('13','16:00+','available'),
        array('14','18:00+','available'),
        array('15','aizņemts','booked'),
        array('16','brīvs','selected'),
        array('17','aizņemts','booked'),
        array('18','diena','available'),
        array('19','16:00+','available'),
        array('20','aizņemts','booked'),
        array('21','18:00+','available'),
        array('22','16:00+','available'),
        array('23','aizņemts','booked'),
        array('24','diena','available'),
        array('25','diena','available'),
    );

    ob_start();
    ?>
    <div class="booking-card sixtythree-shortcode-booking">
      <div class="calendar-head">
        <div>
          <div class="kicker"><?php echo esc_html(sixtythree_i18n('Pieejamība','Availability','Доступность')); ?></div>
          <h3><?php echo esc_html(sixtythree_i18n('Maija rezervācijas','May reservations','Бронирования на май')); ?></h3>
        </div>
      </div>
      <div class="calendar-grid">
        <?php foreach ($days as $day) : ?>
          <button type="button" class="cal-date <?php echo esc_attr($day[2]); ?>">
            <span><?php echo esc_html($day[0]); ?></span>
            <small><?php echo esc_html(sixtythree_i18n($day[1], $day[1] === 'aizņemts' ? 'booked' : ($day[1] === 'brīvs' ? 'free' : ($day[1] === 'diena' ? 'day' : $day[1])), $day[1] === 'aizņemts' ? 'занято' : ($day[1] === 'brīvs' ? 'свободно' : ($day[1] === 'diena' ? 'день' : $day[1])))); ?></small>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="booking-actions">
        <a class="btn" href="#cta"><?php echo esc_html(sixtythree_i18n('Pieteikt izvēlēto laiku →','Book selected time →','Забронировать выбранное →')); ?></a>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sixtythree_booking_calendar', 'sixtythree_shortcode_booking_calendar');

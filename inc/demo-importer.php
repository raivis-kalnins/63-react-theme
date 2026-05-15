<?php
/**
 * 63.lv demo importer.
 */
if (!defined('ABSPATH')) { exit; }

function sixtythree_demo_admin_menu() {
    add_theme_page('63.lv Demo Import', '63.lv Demo Import', 'manage_options', 'sixtythree-demo-import', 'sixtythree_demo_page');
}
add_action('admin_menu', 'sixtythree_demo_admin_menu');

function sixtythree_demo_page() {
    if (!current_user_can('manage_options')) { return; }
    if (isset($_POST['sixtythree_demo_import']) && check_admin_referer('sixtythree_demo_import')) {
        $result = sixtythree_run_demo_import();
        echo '<div class="notice notice-success"><p>' . esc_html($result) . '</p></div>';
    }
    ?>
    <div class="wrap">
      <h1>63.lv Demo Import</h1>
      <p>This imports the 63.lv pages, menus, demo posts, Polylang LV/EN/RU structure and BBuilder form block content.</p>
      <form method="post">
        <?php wp_nonce_field('sixtythree_demo_import'); ?>
        <p><button class="button button-primary" name="sixtythree_demo_import" value="1">Import / Update 63.lv Demo</button></p>
      </form>
      <p><strong>Recommended:</strong> Activate Polylang and WP BBuilder before importing.</p>
    </div>
    <?php
}

function sixtythree_demo_languages() {
    if (!function_exists('pll_languages_list')) { return; }
    $existing = pll_languages_list(array('fields'=>'slug'));
    $langs = array(
        'lv' => array('name'=>'Latviešu','locale'=>'lv_LV','flag'=>'lv'),
        'en' => array('name'=>'English','locale'=>'en_US','flag'=>'gb'),
        'ru' => array('name'=>'Русский','locale'=>'ru_RU','flag'=>'ru'),
    );
    foreach ($langs as $slug => $args) {
        if (!in_array($slug, $existing, true) && function_exists('pll_add_language')) {
            $args['slug'] = $slug;
            pll_add_language($args);
        }
    }
    $opts = get_option('polylang');
    if (is_array($opts)) {
        $opts['default_lang'] = 'lv';
        update_option('polylang', $opts);
    }
}

function sixtythree_upsert_page($title, $slug, $content = '', $lang = '') {
    $page = get_page_by_path($slug);
    $args = array(
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'page',
    );
    if ($page) {
        $args['ID'] = $page->ID;
        $id = wp_update_post($args, true);
    } else {
        $id = wp_insert_post($args, true);
    }
    if (!is_wp_error($id) && $lang && function_exists('pll_set_post_language')) {
        pll_set_post_language($id, $lang);
    }
    return is_wp_error($id) ? 0 : (int)$id;
}

function sixtythree_demo_form_fields($lang = 'lv') {
    $labels = array(
        'lv' => array(
            'name'=>'Vārds','company'=>'Uzņēmums / projekts','email'=>'Epasts','phone'=>'Tālrunis',
            'service'=>'Interesējošais pakalpojums','message'=>'Ziņa',
            'placeholder_message'=>'Pastāstiet īsi par savu vajadzību...',
            'options'=>"Telpu noma
Pirts zona
Web izstrāde
Solārijs
Citi pakalpojumi",
        ),
        'en' => array(
            'name'=>'Name','company'=>'Company / project','email'=>'Email','phone'=>'Phone',
            'service'=>'Service of interest','message'=>'Message',
            'placeholder_message'=>'Tell us briefly what you need...',
            'options'=>"Space rental
Sauna zone
Web development
Solarium
Other services",
        ),
        'ru' => array(
            'name'=>'Имя','company'=>'Компания / проект','email'=>'Эл. почта','phone'=>'Телефон',
            'service'=>'Интересующая услуга','message'=>'Сообщение',
            'placeholder_message'=>'Кратко опишите вашу потребность...',
            'options'=>"Аренда помещений
Зона сауны
Веб-разработка
Солярий
Другие услуги",
        ),
    );
    $t = isset($labels[$lang]) ? $labels[$lang] : $labels['lv'];

    return array(
        array('type'=>'text','name'=>'name','label'=>$t['name'],'placeholder'=>$t['name'],'required'=>true,'width'=>6),
        array('type'=>'text','name'=>'company','label'=>$t['company'],'placeholder'=>$t['company'],'required'=>false,'width'=>6),
        array('type'=>'email','name'=>'email','label'=>$t['email'],'placeholder'=>$t['email'],'required'=>true,'width'=>6),
        array('type'=>'phone','name'=>'phone','label'=>$t['phone'],'placeholder'=>$t['phone'],'required'=>false,'width'=>6),
        array('type'=>'select','name'=>'service','label'=>$t['service'],'options'=>$t['options'],'required'=>false,'width'=>12),
        array('type'=>'textarea','name'=>'message','label'=>$t['message'],'placeholder'=>$t['placeholder_message'],'required'=>true,'width'=>12),
    );
}

function sixtythree_demo_form_block($lang = 'lv') {
    $title = array('lv'=>'Pieteikt konsultāciju','en'=>'Request a consultation','ru'=>'Запросить консультацию');
    $subject = array('lv'=>'63.lv pieteikums','en'=>'63.lv request','ru'=>'Заявка 63.lv');
    $success = array('lv'=>'Paldies! Sazināsimies ar jums.','en'=>'Thank you! We will contact you.','ru'=>'Спасибо! Мы свяжемся с вами.');
    $submit = array('lv'=>'Nosūtīt pieteikumu','en'=>'Send request','ru'=>'Отправить заявку');

    $attrs = array(
        'showTitle' => true,
        'formTitle' => $title[$lang] ?? $title['lv'],
        'recipient' => 'hello@63.lv',
        'emailSubject' => $subject[$lang] ?? $subject['lv'],
        'successMessage' => $success[$lang] ?? $success['lv'],
        'submitText' => $submit[$lang] ?? $submit['lv'],
        'buttonClass' => 'btn btn-primary',
        'formClass' => 'wpbb-form',
        'fieldsJson' => wp_json_encode(sixtythree_demo_form_fields($lang), JSON_UNESCAPED_UNICODE),
    );

    return '<!-- wp:wpbb/dynamic-form ' . wp_json_encode($attrs, JSON_UNESCAPED_UNICODE) . ' /-->';
}

function sixtythree_home_builder_content($lang = 'lv') {
    $content = <<<'WPBLOCKS'
<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section sixtythree-admin-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section sixtythree-admin-hero">
<!-- wp:columns {"verticalAlignment":"center","className":"sixtythree-admin-columns"} -->
<div class="wp-block-columns are-vertically-aligned-center sixtythree-admin-columns">
<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Telpas, zināšanas un pakalpojumi vienuviet</h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#pakalpojumi">Mūsu pakalpojumi →</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
<!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group sixtythree-admin-card">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Pakalpojumi</h3>
<!-- /wp:heading -->
<!-- wp:list {"className":"sixtythree-feature-list"} -->
<ul class="wp-block-list sixtythree-feature-list">
<!-- wp:list-item --><li>Pirts ballītēm</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Web izstrāde un uzturēšana</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Vertikālais solārijs</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Telpu noma Bauskas 63</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section" id="pakalpojumi">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Pakalpojumi</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Viss svarīgākais vienā vietā</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"className":"sixtythree-admin-service-grid"} -->
<div class="wp-block-columns sixtythree-admin-service-grid">
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Pirts ballītēm</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Ģimenēm un draugiem, līdz 15 personām, darba dienās no 16:00.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Web izstrāde</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WordPress, headless ReactJS, SEO, hostings un uzturēšana.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Solārijs</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Vertikālais solārijs ar skaidrām cenām un pierakstu.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Telpu noma</h3><!-- /wp:heading --><!-- wp:paragraph --><p>105 m² biznesa telpas Bauskas ielā 63, Rīgā.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section" id="pirts">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Bauskas 63 · pirts zona</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Vieta ģimenei, draugiem un svinībām</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"top"} -->
<div class="wp-block-columns are-vertically-aligned-top">
<!-- wp:column {"verticalAlignment":"top","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:55%">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Vieta ģimenei, draugiem un svinībām</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Skaidrs pirts ballītes piedāvājums ar cenu, ilgumu, iekļautajiem pakalpojumiem, pieejamības kalendāru un ātru pieteikšanos.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"sixtythree-feature-list"} -->
<ul class="wp-block-list sixtythree-feature-list">
<!-- wp:list-item --><li>€90 / akcijā €84</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>6 stundas</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Līdz 15 personām</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Pieteikšanās 2 dienas iepriekš</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:45%">
<!-- wp:shortcode -->
[sixtythree_booking_calendar]
<!-- /wp:shortcode -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section" id="skaistums">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Skaistumam</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Vertikālais solārijs — cenas</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"top"} -->
<div class="wp-block-columns are-vertically-aligned-top">
<!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Vertikālais solārijs — cenas</h2>
<!-- /wp:heading -->
<!-- wp:columns {"className":"solarium-price-row"} -->
<div class="wp-block-columns solarium-price-row">
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:paragraph {"className":"solarium-price-value"} --><p class="solarium-price-value"><strong>€3</strong></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"solarium-price-label"} --><p class="solarium-price-label">10 minūtes</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:paragraph {"className":"solarium-price-value"} --><p class="solarium-price-value"><strong>€3,30</strong></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"solarium-price-label"} --><p class="solarium-price-label">12 minūtes</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:columns {"className":"solarium-price-row"} -->
<div class="wp-block-columns solarium-price-row">
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:paragraph {"className":"solarium-price-value"} --><p class="solarium-price-value"><strong>€4</strong></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"solarium-price-label"} --><p class="solarium-price-label">14 minūtes</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:paragraph {"className":"solarium-price-value"} --><p class="solarium-price-value"><strong>€2</strong></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"solarium-price-label"} --><p class="solarium-price-label">Krēms</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%">
<!-- wp:paragraph -->
<p>Kompakta un skaidra cenu sadaļa ar 63.lv aktuālajām solārija cenām. Pieejams pēc iepriekšējas pieteikšanās.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section" id="web">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Web Development</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>WordPress un full stack izstrāde</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"top"} -->
<div class="wp-block-columns are-vertically-aligned-top">
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">WordPress un full stack izstrāde</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://digitalpulse.click">Apskatīt portfolio</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:columns {"className":"web-services-row"} -->
<div class="wp-block-columns web-services-row">
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Web Themes</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Pilnībā pielāgotas premium tēmas, dizaina sistēmas, Gutenberg bloki un ātrdarbības optimizācija.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">WooCommerce</h3><!-- /wp:heading --><!-- wp:paragraph --><p>E-veikali, maksājumu integrācijas, produktu katalogi un klientu pieredzes uzlabojumi.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:columns {"className":"web-services-row"} -->
<div class="wp-block-columns web-services-row">
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Plugins & Custom Code</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Individuāli spraudņi, automatizācijas un biznesa procesu digitalizācija.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Headless ReactJS</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WP kā CMS + React/Vite/Next frontend, API savienojumi un headless arhitektūra.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Web development cenas</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Digitālie risinājumi ar skaidru sākuma cenu</p>
<!-- /wp:paragraph -->
<!-- wp:columns {"className":"web-price-row"} -->
<div class="wp-block-columns web-price-row">
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Business Startup</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"web-price-value"} --><p class="web-price-value">no €550</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Labs sākums uzņēmuma klātbūtnei internetā.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Business Pro</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"web-price-value"} --><p class="web-price-value">no €850</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Plašāka struktūra, vairāk sadaļu un pielāgots saturs.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:group {"className":"sixtythree-admin-card","layout":{"type":"constrained"}} --><div class="wp-block-group sixtythree-admin-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">E-commerce</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"web-price-value"} --><p class="web-price-value">no €1500</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>WooCommerce / shop tipa risinājums pārdošanai tiešsaistē.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sixtythree-admin-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group sixtythree-admin-section" id="cta">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Pieteikums un kontakti</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Pastāstiet par savu ideju vai vajadzību</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"top"} -->
<div class="wp-block-columns are-vertically-aligned-top">
<!-- wp:column {"width":"42%"} -->
<div class="wp-block-column" style="flex-basis:42%">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Pastāstiet par savu ideju vai vajadzību</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Epasts: hello@63.lv</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Tālrunis: +371 29837694</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Adrese: Bauskas iela 63, Rīga</p><!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%">
<!-- wp:shortcode -->
[sixtythree_contact_form]
<!-- /wp:shortcode -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:shortcode -->
[sixtythree_google_map]
<!-- /wp:shortcode -->
</section>
<!-- /wp:group -->
WPBLOCKS;
    return sixtythree_demo_translate_content($content, $lang);
}

function sixtythree_run_demo_import() {
    sixtythree_demo_languages();
    sixtythree_demo_enable_wpbb_blocks();

    $pages = array(
        'lv' => array('title'=>'Sākumlapa','slug'=>'sakumlapa'),
        'en' => array('title'=>'Home','slug'=>'home'),
        'ru' => array('title'=>'Главная','slug'=>'glavnaya'),
    );
    $ids = array();
    foreach ($pages as $lang => $p) {
        $ids[$lang] = sixtythree_upsert_page($p['title'], $p['slug'], sixtythree_home_builder_content($lang), $lang);
    }
    if (function_exists('pll_save_post_translations') && !empty($ids['lv']) && !empty($ids['en']) && !empty($ids['ru'])) {
        pll_save_post_translations($ids);
    }
    if (!empty($ids['lv'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $ids['lv']);
    }

    $simple_pages = array(
        'pakalpojumi' => array('lv'=>'Pakalpojumi','en'=>'Services','ru'=>'Услуги'),
        'pirts-zona' => array('lv'=>'Pirts zona','en'=>'Sauna zone','ru'=>'Сауна'),
        'web-izstrade' => array('lv'=>'Web izstrāde','en'=>'Web development','ru'=>'Веб разработка'),
        'kontakti' => array('lv'=>'Kontakti','en'=>'Contact','ru'=>'Контакты'),
    );
    foreach ($simple_pages as $slug => $titles) {
        foreach (array('lv','en','ru') as $lang) {
            $l_slug = $lang === 'lv' ? $slug : $slug . '-' . $lang;
            sixtythree_upsert_page($titles[$lang], $l_slug, '<!-- wp:paragraph -->
<p>' . esc_html($titles[$lang]) . ' — 63.lv</p>
<!-- /wp:paragraph -->' . ($slug === 'kontakti' ? sixtythree_demo_form_block($lang) : ''), $lang);
        }
    }


    // Optional separate editable BBuilder reference page. The front page content is also editable in admin,
    // while front-page.php keeps the approved premium static/headless design on the public site.
    $builder_page_id = sixtythree_upsert_page('63.lv Builder Sections', '63lv-builder-sections', sixtythree_builder_demo_content(), 'lv');
    if ($builder_page_id && function_exists('pll_set_post_language')) {
        pll_set_post_language($builder_page_id, 'lv');
    }

    $posts = array(
        array(
            'lv' => array('title'=>'Kā sagatavoties pirts vakaram?', 'content'=>'Īss ceļvedis, ko paņemt līdzi un kā plānot sešu stundu atpūtu.'),
            'en' => array('title'=>'How to prepare for a sauna evening', 'content'=>'A short guide on what to bring and how to plan a six-hour relaxation evening.'),
            'ru' => array('title'=>'Как подготовиться к вечеру в сауне', 'content'=>'Краткое руководство: что взять с собой и как спланировать шесть часов отдыха.'),
        ),
        array(
            'lv' => array('title'=>'Pirts ballīte līdz 15 personām', 'content'=>'Ieteikumi nelielām svinībām, galdu izvietojumam un atpūtas ritmam.'),
            'en' => array('title'=>'Sauna party for up to 15 people', 'content'=>'Tips for small celebrations, table layout and a comfortable event rhythm.'),
            'ru' => array('title'=>'Сауна-вечеринка до 15 человек', 'content'=>'Советы для небольшого праздника, расстановки столов и удобного ритма отдыха.'),
        ),
        array(
            'lv' => array('title'=>'Ko iekļaut pasākuma plānā?', 'content'=>'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas vienā vakarā.'),
            'en' => array('title'=>'What to include in the event plan?', 'content'=>'Sauna, whisks, table hall, kitchen and relaxation rooms in one evening.'),
            'ru' => array('title'=>'Что включить в план мероприятия?', 'content'=>'Сауна, веники, зал со столами, кухня и комнаты отдыха за один вечер.'),
        ),
    );

    foreach ($posts as $translations) {
        $translation_ids = array();
        foreach (array('lv','en','ru') as $lang) {
            $title = $translations[$lang]['title'];
            $existing = get_page_by_title($title, OBJECT, 'post');
            if ($existing) {
                $id = $existing->ID;
                wp_update_post(array(
                    'ID' => $id,
                    'post_content' => '<!-- wp:paragraph -->
<p>' . esc_html($translations[$lang]['content']) . '</p>
<!-- /wp:paragraph -->',
                    'post_status' => 'publish',
                ));
            } else {
                $id = wp_insert_post(array(
                    'post_type'=>'post',
                    'post_status'=>'publish',
                    'post_title'=>$title,
                    'post_content'=>'<!-- wp:paragraph -->
<p>' . esc_html($translations[$lang]['content']) . '</p>
<!-- /wp:paragraph -->',
                ));
            }
            if ($id && !is_wp_error($id)) {
                if (function_exists('pll_set_post_language')) { pll_set_post_language($id, $lang); }
                $translation_ids[$lang] = $id;
            }
        }
        if (function_exists('pll_save_post_translations') && count($translation_ids) > 1) {
            pll_save_post_translations($translation_ids);
        }
    }

    sixtythree_demo_menus();
    sixtythree_demo_set_site_icon();
    return '63.lv demo content imported / updated.';
}

function sixtythree_demo_menus() {
    $locations = get_theme_mod('nav_menu_locations', array());
    $labels = array(
        'lv' => array('menu'=>'63.lv Galvenā izvēlne LV','items'=>array('Pakalpojumi'=>'#pakalpojumi','Pirts zona'=>'#pirts','Web izstrāde'=>'#web','Kontakti'=>'#cta')),
        'en' => array('menu'=>'63.lv Main Menu EN','items'=>array('Services'=>'#pakalpojumi','Sauna'=>'#pirts','Web development'=>'#web','Contact'=>'#cta')),
        'ru' => array('menu'=>'63.lv Главное меню RU','items'=>array('Услуги'=>'#pakalpojumi','Сауна'=>'#pirts','Веб'=>'#web','Контакты'=>'#cta')),
    );
    foreach ($labels as $lang => $data) {
        $menu = wp_get_nav_menu_object($data['menu']);
        if (!$menu) { $menu_id = wp_create_nav_menu($data['menu']); } else { $menu_id = $menu->term_id; }
        foreach ($data['items'] as $title => $url) {
            wp_update_nav_menu_item($menu_id, 0, array('menu-item-title'=>$title,'menu-item-url'=>home_url('/' . $url),'menu-item-status'=>'publish','menu-item-type'=>'custom'));
        }
        if ($lang === 'lv') { $locations['primary'] = $menu_id; $locations['footer'] = $menu_id; }
    }
    set_theme_mod('nav_menu_locations', $locations);
}


function sixtythree_demo_set_site_icon() {
    $src = get_template_directory() . '/assets/images/63lv/touch.png';
    if (!file_exists($src)) { return; }

    $existing = get_option('site_icon');
    // Force update demo icon so the latest theme icon is applied after re-import.

    $upload = wp_upload_bits('63lv-touch.png', null, file_get_contents($src));
    if (!empty($upload['error']) || empty($upload['file'])) { return; }

    $filetype = wp_check_filetype($upload['file'], null);
    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'],
        'post_title' => '63.lv touch icon',
        'post_content' => '',
        'post_status' => 'inherit',
    ), $upload['file']);

    if ($attachment_id && !is_wp_error($attachment_id)) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $metadata);
        update_option('site_icon', $attachment_id);
    }
}


function sixtythree_demo_enable_wpbb_blocks() {
    $blocks = array('accordion','accordion-item','alert','badge','breadcrumb','button','card','cards','column','cta-card','cta-section','dynamic-form','google-map','list-group','menu-option','navbar','progress','row','section','sitemap','soc-follow-block','soc-share','social-feeds','spinner','tab-item','table','tabs','video','file','inline-svg','swiper','weather','varda-dienas','ajax-search','pricecards','catalogue','code-display','countdown-timer','chart','fun-fact','mailchimp','bootstrap-div','feature-list','timeline','custom-embed','ai-content','login-register','load-more','contact-links','events','testimonials','blog-filter','booking-calendar');
    $enabled = array();
    foreach ($blocks as $block) {
        $enabled[$block] = 1;
    }

    $settings = get_option('wpbb_settings', array());
    if (!is_array($settings)) { $settings = array(); }
    $settings['enabled_blocks'] = $enabled;
    $settings['load_bootstrap_css'] = 1;
    $settings['load_bootstrap_js'] = 1;
    $settings['load_shared_css'] = 1;
    $settings['force_bootstrap_enqueue'] = 1;
    $settings['default_recipient_email'] = 'hello@63.lv';
    update_option('wpbb_settings', $settings);
}

function sixtythree_home_static_note_content($lang = 'lv') {
    return '';
}

function sixtythree_builder_demo_content() {
    return sixtythree_home_builder_content('lv');
}


function sixtythree_demo_translate_content($content, $lang = 'lv') {
    if ($lang === 'lv') { return $content; }

    $map = array();

    foreach (array(
        'sixtythree_front_translation_map',
        'sixtythree_front_translation_extra_map',
        'sixtythree_front_translation_complete_map',
        'sixtythree_front_translation_live_override_map',
        'sixtythree_front_translation_final_override_map',
    ) as $fn) {
        if (function_exists($fn)) {
            $part = call_user_func($fn);
            if (!empty($part[$lang]) && is_array($part[$lang])) {
                $map = array_merge($map, $part[$lang]);
            }
        }
    }

    $local = array(
        'en' => array(
            'Telpu noma Bauskas 63' => 'Space rental Bauskas 63',
            '105 m² biznesa telpas Bauskas ielā 63, Rīgā.' => '105 m² business premises at Bauskas Street 63, Riga.',
            '6 stundas' => '6 hours',
            'Pieteikšanās 2 dienas iepriekš' => 'Booking 2 days in advance',
            'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas' => 'Sauna, whisks, hall with tables, kitchen and relaxation rooms',
            'Izvēlieties pieejamu datumu un nosūtiet pieteikumu.' => 'Choose an available date and send your request.',
            'Paldies, pieteikums saņemts.' => 'Thank you, your request has been received.',
            'Pieteikt' => 'Request',
            '63.lv pieteikums' => '63.lv request',
            'Paldies! Sazināsimies ar jums.' => 'Thank you! We will contact you.',
            'Telpu noma' => 'Space rental',
            'Pirts zona' => 'Sauna zone',
            'Web izstrāde' => 'Web development',
            'Solārijs' => 'Solarium',
            'Citi pakalpojumi' => 'Other services',
        ),
        'ru' => array(
            'Telpu noma Bauskas 63' => 'Аренда помещений Баускас 63',
            '105 m² biznesa telpas Bauskas ielā 63, Rīgā.' => '105 м² бизнес-помещения на улице Баускас 63, Рига.',
            '6 stundas' => '6 часов',
            'Pieteikšanās 2 dienas iepriekš' => 'Бронирование за 2 дня',
            'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas' => 'Сауна, веники, зал со столами, кухня и комнаты отдыха',
            'Izvēlieties pieejamu datumu un nosūtiet pieteikumu.' => 'Выберите доступную дату и отправьте заявку.',
            'Paldies, pieteikums saņemts.' => 'Спасибо, заявка получена.',
            'Pieteikt' => 'Заказать',
            '63.lv pieteikums' => 'Заявка 63.lv',
            'Paldies! Sazināsimies ar jums.' => 'Спасибо! Мы свяжемся с вами.',
            'Telpu noma' => 'Аренда помещений',
            'Pirts zona' => 'Зона сауны',
            'Web izstrāde' => 'Веб-разработка',
            'Solārijs' => 'Солярий',
            'Citi pakalpojumi' => 'Другие услуги',
        ),
    );

    if (!empty($local[$lang])) {
        $map = array_merge($map, $local[$lang]);
    }

    if (!$map) { return $content; }

    uksort($map, function($a, $b) { return mb_strlen($b) <=> mb_strlen($a); });
    foreach ($map as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    return $content;
}

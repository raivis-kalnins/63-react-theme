<?php
/**
 * Editable homepage copy for the existing headless/SSR template.
 *
 * Each Polylang home-page translation owns its own ACF fields. The historic
 * Gutenberg post_content is intentionally NOT injected into the public page:
 * it is a demo layout and does not match the approved frontend structure.
 */
if (!defined('ABSPATH')) { exit; }

function sixtythree_live_home_page_ids() {
    static $cached = null;
    if (is_array($cached)) { return $cached; }
    $front_id = (int) get_option('page_on_front', 0);
    $ids = array();
    $slugs = array('lv' => 'sakumlapa', 'en' => 'home', 'ru' => 'glavnaya');
    foreach ($slugs as $lang => $slug) {
        $id = 0;
        if ($front_id && function_exists('pll_get_post')) {
            $id = (int) pll_get_post($front_id, $lang);
        }
        if (!$id && $lang === 'lv') { $id = $front_id; }
        if (!$id) {
            $page = get_page_by_path($slug, OBJECT, 'page');
            if ($page instanceof WP_Post) { $id = (int) $page->ID; }
        }
        if ($id) { $ids[$lang] = $id; }
    }
    $cached = $ids;
    return $cached;
}

function sixtythree_live_home_language($post_id) {
    // ACF normally provides an integer ID, but may prefix it with "post_".
    if (is_string($post_id) && preg_match('/^post_(\d+)$/', $post_id, $match)) {
        $post_id = $match[1];
    }
    $post_id = (int) $post_id;
    if ($post_id && function_exists('pll_get_post_language')) {
        $lang = pll_get_post_language($post_id, 'slug');
        if (in_array($lang, array('lv', 'en', 'ru'), true)) { return $lang; }
    }
    foreach (sixtythree_live_home_page_ids() as $lang => $id) {
        if ($post_id === $id) { return $lang; }
    }
    return sixtythree_current_lang();
}

function sixtythree_live_home_defaults($lang = 'lv') {
    static $defaults = null;
    if ($defaults === null) {
    $defaults = array(
        'lv' => array(
            'hero_title' => 'Telpas, zināšanas un pakalpojumi vienuviet',
            'hero_description' => 'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē — telpas, telpu zonu, apmācības, web risinājumus un ikdienas servisu vienuviet.',
            'services_title' => 'Viss svarīgākais vienā vietā',
            'services_description' => 'Pakalpojumu sadaļā atradīsiet telpu rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.',
            'card_event_title' => 'Telpas ballītēm',
            'card_event_items' => "Ģimenēm un draugiem\nLīdz ~15 personām\nDarba dienās no 16:00\n€84 vai €14/h",
            'card_web_title' => 'Web izstrāde un uzturēšana',
            'card_web_items' => "WordPress Business no €1 190\nWordPress & WooCommerce\nSEO, hostings, optimizācija\nHeadless ReactJS risinājumi",
            'card_courses_title' => 'E-apmācības un kursi',
            'card_courses_items' => "Individuāli €15/h\nGrupas 3–5 pers. €50/h\nZoom & Skype\nBiroja darbi, kopēšana, printēšana",
            'card_other_title' => 'Citi pakalpojumi',
            'card_other_items' => "Telpu noma pasākumiem\nFrizētava IEVA\nManikīrs & pedikīrs\nŠūšanas un remonta darbi\nVertikālais solārijs ar pierakstu",
            'card_airsoft_title' => 'ForAirsoft',
            'card_airsoft_items' => "Airsoft veikals un serviss\nReplikas un taktiskais ekipējums\nRezerves daļas un uzlabojumi\nBauskas 63 · k1",
            'card_button_text' => 'UZZINĀT VAIRĀK →',
            'contact_title' => 'Pastāstiet par savu ideju vai vajadzību',
            'contact_description' => 'Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Sazinieties ar mums, lai pārrunātu pieejamību vai projekta vajadzības.',
        ),
        'en' => array(
            'hero_title' => 'Spaces, knowledge and services in one place',
            'hero_description' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide.',
            'services_title' => 'Everything important in one place',
            'services_description' => 'In the services section you can find venue bookings, space rental, web development, training and everyday services. Choose the service you need and contact us about availability.',
            'card_event_title' => 'For private events',
            'card_event_items' => "For family and friends\nUp to ~15 people\nWeekdays from 16:00\n€84 or €14/h",
            'card_web_title' => 'Web development and maintenance',
            'card_web_items' => "WordPress Business from €1,190\nWordPress & WooCommerce\nSEO, hosting, optimisation\nHeadless ReactJS solutions",
            'card_courses_title' => 'E-learning and courses',
            'card_courses_items' => "Individual sessions €15/h\nGroups of 3–5 people €50/h\nZoom & Skype\nOffice services, copying, printing",
            'card_other_title' => 'Other services',
            'card_other_items' => "Venue hire for events\nIEVA hair salon\nManicure & pedicure\nSewing and repair services\nVertical tanning booth by appointment",
            'card_airsoft_title' => 'ForAirsoft',
            'card_airsoft_items' => "Airsoft shop and service\nReplicas and tactical equipment\nSpare parts and upgrades\nBauskas 63 · building 1",
            'card_button_text' => 'LEARN MORE →',
            'contact_title' => 'Tell us about your idea or needs',
            'contact_description' => 'We can help with venue rental, web development, training and other services at Bauskas Street 63. Contact us about availability or the needs of your project.',
        ),
        'ru' => array(
            'hero_title' => 'Помещения, знания и услуги в одном месте',
            'hero_description' => 'SIA Kalns RTL основана в 2003 году. Мы предоставляем услуги на месте по адресу улица Баускас 63 в Риге, а также удалённо по всей Латвии и миру.',
            'services_title' => 'Всё важное в одном месте',
            'services_description' => 'В разделе услуг доступны бронирование помещений, аренда помещений, веб-разработка, обучение и повседневные услуги. Выберите нужное направление и свяжитесь с нами по поводу доступности.',
            'card_event_title' => 'Помещения для праздников',
            'card_event_items' => "Для семьи и друзей\nДо ~15 человек\nВ рабочие дни с 16:00\n€84 или €14/ч",
            'card_web_title' => 'Веб-разработка и поддержка',
            'card_web_items' => "WordPress Business от €1 190\nWordPress & WooCommerce\nSEO, хостинг, оптимизация\nHeadless ReactJS решения",
            'card_courses_title' => 'Онлайн-обучение и курсы',
            'card_courses_items' => "Индивидуально €15/ч\nГруппы 3–5 человек €50/ч\nZoom & Skype\nОфисные услуги, копирование, печать",
            'card_other_title' => 'Другие услуги',
            'card_other_items' => "Аренда помещений для мероприятий\nПарикмахерская IEVA\nМаникюр и педикюр\nШвейные и ремонтные работы\nВертикальный солярий по предварительной записи",
            'card_airsoft_title' => 'ForAirsoft',
            'card_airsoft_items' => "Магазин и сервис airsoft\nРеплики и тактическое снаряжение\nЗапчасти и улучшения\nБаускас 63 · корпус 1",
            'card_button_text' => 'УЗНАТЬ БОЛЬШЕ →',
            'contact_title' => 'Расскажите об идее или потребности',
            'contact_description' => 'Поможем с арендой помещений, веб-разработкой, обучением и другими услугами на улице Баускас 63. Свяжитесь с нами, чтобы обсудить доступность и детали проекта.',
        ),
    );
    }
    return $defaults[isset($defaults[$lang]) ? $lang : 'lv'];
}

function sixtythree_live_field($field, $lang = '') {
    $lang = $lang ?: sixtythree_current_lang();
    $defaults = sixtythree_live_home_defaults($lang);
    $default = isset($defaults[$field]) ? $defaults[$field] : '';
    $ids = sixtythree_live_home_page_ids();
    $id = isset($ids[$lang]) ? (int) $ids[$lang] : 0;
    $meta_key = 'sixtythree_edit_' . $field;
    if ($id && metadata_exists('post', $id, $meta_key)) {
        return (string) get_post_meta($id, $meta_key, true);
    }
    return $default;
}

function sixtythree_live_card_list_html($text) {
    $items = preg_split('/\r\n|\r|\n/', (string) $text);
    $html = '<ul>';
    foreach (array_slice($items, 0, 30) as $item) {
        $item = trim($item);
        if ($item !== '') { $html .= '<li>' . esc_html($item) . '</li>'; }
    }
    return $html . '</ul>';
}

function sixtythree_live_services_html() {
    $cards = array(
        'event' => array('href' => '#cta', 'class' => ''),
        'web' => array('href' => '#web', 'class' => ''),
        'courses' => array('href' => '#learning', 'class' => ''),
        'other' => array('href' => '#cta', 'class' => ''),
        'airsoft' => array('href' => '#forairsoft', 'class' => ' service-card--forairsoft'),
    );
    $button = sixtythree_live_field('card_button_text');
    $html = '';
    foreach ($cards as $name => $card) {
        $html .= '<article class="service-card' . esc_attr($card['class']) . '"><h3>';
        $html .= esc_html(sixtythree_live_field('card_' . $name . '_title')) . '</h3>';
        $html .= sixtythree_live_card_list_html(sixtythree_live_field('card_' . $name . '_items'));
        $html .= '<a href="' . esc_url($card['href']) . '">' . esc_html($button) . '</a></article>';
    }
    return $html;
}

/**
 * Safe exact-text replacements for content not covered by a named ACF field.
 * These operate on rendered text nodes only (not attributes/HTML or scripts).
 */
function sixtythree_live_acf_extra_text($html) {
    if (!function_exists('get_field')) { return $html; }
    $ids = sixtythree_live_home_page_ids();
    $lang = sixtythree_current_lang();
    if (empty($ids[$lang])) { return $html; }
    $rows = get_field('sixtythree_edit_extra_replacements', $ids[$lang]);
    if (!is_array($rows) || !$rows) { return $html; }
    $map = array();
    foreach (array_slice($rows, 0, 100) as $row) {
        $original = trim((string) ($row['original'] ?? ''));
        if ($original !== '') { $map[$original] = (string) ($row['replacement'] ?? ''); }
    }
    if (!$map) { return $html; }
    $protected = array();
    $html = preg_replace_callback('#<(script|style|textarea|pre)\b[^>]*>.*?</\1>#is', function($match) use (&$protected) {
        $key = '%%63LV_PROTECTED_' . count($protected) . '%%';
        $protected[$key] = $match[0];
        return $key;
    }, $html);
    $html = preg_replace_callback('/(?<=>)[^<>]+(?=<)/u', function($match) use ($map) {
        $text = trim($match[0]);
        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (!array_key_exists($decoded, $map)) { return $match[0]; }
        $prefix = preg_match('/^\s*/u', $match[0], $m) ? $m[0] : '';
        $suffix = preg_match('/\s*$/u', $match[0], $m) ? $m[0] : '';
        return $prefix . esc_html($map[$decoded]) . $suffix;
    }, $html);
    return strtr($html, $protected);
}

function sixtythree_live_apply_frontpage($html) {
    $replacements = array(
        '%%SIXTYTHREE_LIVE_HERO_TITLE%%' => esc_html(sixtythree_live_field('hero_title')),
        '%%SIXTYTHREE_LIVE_HERO_DESCRIPTION%%' => esc_html(sixtythree_live_field('hero_description')),
        '%%SIXTYTHREE_LIVE_SERVICES_TITLE%%' => esc_html(sixtythree_live_field('services_title')),
        '%%SIXTYTHREE_LIVE_SERVICES_DESCRIPTION%%' => esc_html(sixtythree_live_field('services_description')),
        '%%SIXTYTHREE_LIVE_SERVICE_CARDS%%' => sixtythree_live_services_html(),
        '%%SIXTYTHREE_LIVE_CONTACT_TITLE%%' => esc_html(sixtythree_live_field('contact_title')),
        '%%SIXTYTHREE_LIVE_CONTACT_DESCRIPTION%%' => esc_html(sixtythree_live_field('contact_description')),
    );
    return sixtythree_live_acf_extra_text(strtr($html, $replacements));
}

/** Editable ACF fields, displayed on the actual LV/EN/RU home pages only. */
function sixtythree_live_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) { return; }
    $ids = array_values(array_unique(array_values(sixtythree_live_home_page_ids())));
    if (!$ids) { return; }
    $locations = array();
    foreach ($ids as $id) {
        $locations[] = array(array('param' => 'post', 'operator' => '==', 'value' => (string) $id));
    }
    $fields = array();
    $tab = function($key, $label) use (&$fields) {
        $fields[] = array('key'=>'field_63lv_tab_' . $key, 'label'=>$label, 'name'=>'', 'type'=>'tab', 'placement'=>'top');
    };
    $input = function($name, $label, $type = 'text', $instructions = '') use (&$fields) {
        $fields[] = array(
            'key' => 'field_63lv_edit_' . $name,
            'label' => $label,
            'name' => 'sixtythree_edit_' . $name,
            'type' => $type,
            'instructions' => $instructions,
            'rows' => $type === 'textarea' ? 5 : 0,
            'new_lines' => '',
            'required' => 0,
            'wrapper' => array('width' => ($type === 'text' ? '100' : '100')),
        );
        add_filter('acf/load_value/name=sixtythree_edit_' . $name, function($value, $post_id) use ($name) {
            if ($value !== null && $value !== false) { return $value; }
            $lang = sixtythree_live_home_language($post_id);
            $defaults = sixtythree_live_home_defaults($lang);
            return $defaults[$name] ?? '';
        }, 10, 2);
    };
    $tab('hero', '01 · Hero');
    $input('hero_title', 'Main heading');
    $input('hero_description', 'Introduction', 'textarea');
    $tab('services', '02 · Services');
    $input('services_title', 'Services heading');
    $input('services_description', 'Services introduction', 'textarea');
    $card_labels = array(
        'event' => 'Private events',
        'web' => 'Web development',
        'courses' => 'Courses',
        'other' => 'Other services (solarium is the last item)',
        'airsoft' => 'ForAirsoft',
    );
    foreach ($card_labels as $slug => $label) {
        $input('card_' . $slug . '_title', $label . ' — heading');
        $input('card_' . $slug . '_items', $label . ' — list', 'textarea', 'One item per line. Order and number of items can be changed.');
    }
    $input('card_button_text', 'Service card button label');
    $tab('contacts', '03 · Contacts');
    $input('contact_title', 'Contact heading');
    $input('contact_description', 'Contact introduction', 'textarea');
    $tab('extras', '04 · Additional text');
    $fields[] = array(
        'key' => 'field_63lv_edit_extra_replacements',
        'label' => 'Additional homepage text changes',
        'name' => 'sixtythree_edit_extra_replacements',
        'type' => 'repeater',
        'instructions' => 'Optional: to edit other plain text on the public homepage. Enter the exact currently visible text and its replacement. Does not edit HTML, links, images or scripts. These changes apply only to this page language.',
        'layout' => 'row',
        'button_label' => 'Add text change',
        'sub_fields' => array(
            array('key'=>'field_63lv_edit_original','label'=>'Current visible text','name'=>'original','type'=>'textarea','rows'=>2,'required'=>1),
            array('key'=>'field_63lv_edit_replacement','label'=>'New text','name'=>'replacement','type'=>'textarea','rows'=>2),
        ),
    );
    acf_add_local_field_group(array(
        'key' => 'group_63lv_live_homepage',
        'title' => '63.lv · Live homepage content (this language)',
        'fields' => $fields,
        'location' => $locations,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'menu_order' => 0,
    ));
}
add_action('acf/init', 'sixtythree_live_register_acf_fields');

/** Polylang must NOT sync text fields between LV/EN/RU. */
function sixtythree_live_polylang_independent_metas($metas) {
    if (!is_array($metas)) { return $metas; }
    return array_values(array_filter($metas, function($key) {
        return strpos(ltrim((string) $key, '_'), 'sixtythree_edit_') !== 0;
    }));
}
add_filter('pll_copy_post_metas', 'sixtythree_live_polylang_independent_metas', 99, 1);

/** ACF writes meta after WordPress save_post; invalidate cached frontend afterwards. */
function sixtythree_live_after_acf_save($post_id) {
    if (is_string($post_id) && preg_match('/^post_(\d+)$/', $post_id, $match)) {
        $post_id = $match[1];
    }
    if (!is_numeric($post_id)) { return; }
    if (in_array((int) $post_id, sixtythree_live_home_page_ids(), true) && function_exists('sixtythree_cache_purge')) {
        sixtythree_cache_purge();
    }
}
add_action('acf/save_post', 'sixtythree_live_after_acf_save', 30);

/**
 * Hide the stale Gutenberg demo/source on the three homepage editors only.
 * WordPress keeps Gutenberg enabled for all other pages and blog posts.
 */
function sixtythree_live_admin_editor_setup() {
    global $pagenow;
    if ($pagenow !== 'post.php' || empty($_GET['post']) || !current_user_can('edit_pages')) { return; }
    if (!function_exists('acf_add_local_field_group')) { return; }
    $post_id = absint(wp_unslash($_GET['post']));
    if (!in_array($post_id, sixtythree_live_home_page_ids(), true)) { return; }
    remove_post_type_support('page', 'editor');
}
add_action('admin_init', 'sixtythree_live_admin_editor_setup', 20);

function sixtythree_live_home_editor_notice() {
    global $pagenow;
    if ($pagenow !== 'post.php' || empty($_GET['post'])) { return; }
    $post_id = absint(wp_unslash($_GET['post']));
    if (!in_array($post_id, sixtythree_live_home_page_ids(), true)) { return; }
    $lang = strtoupper(sixtythree_live_home_language($post_id));
    echo '<div class="notice notice-info"><p><strong>' . esc_html($lang) . ' · 63.lv:</strong> '
        . esc_html__('Edit this language’s public homepage in the "63.lv · Live homepage content" fields below. The old Gutenberg source is not used by the homepage.', 'sixty-three-lv')
        . '</p></div>';
}
add_action('admin_notices', 'sixtythree_live_home_editor_notice');

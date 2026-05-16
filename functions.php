<?php
/**
 * 63.lv React Theme functions.
 */
if (!defined('ABSPATH')) { exit; }

define('SIXTYTHREE_THEME_VERSION', '1.6.2');

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



function sixtythree_social_share_defaults() {
    return array(
        'title' => sixtythree_i18n(
            'Vieta ģimenei, draugiem un svinībām',
            'A place for family, friends and celebrations',
            'Место для семьи, друзей и праздников'
        ),
        'description' => sixtythree_i18n(
            'Pirts piedāvājums ģimenei, draugiem un nelielām svinībām ar cenu, ilgumu, iekļautajiem pakalpojumiem, kalendāru un ātru pieteikšanos.',
            'Sauna offer for family, friends and small celebrations with price, duration, included services, calendar and quick booking.',
            'Предложение сауны для семьи, друзей и небольших праздников с ценой, длительностью, включёнными услугами, календарём и быстрой заявкой.'
        ),
        'image' => 'https://63.lv/wp-content/uploads/2026/05/pirts-zone-clean-01-replacement.jpg',
        'url' => sixtythree_language_home_url(),
        'type' => 'website',
    );
}

function sixtythree_language_home_url() {
    $lang = sixtythree_current_lang();
    if (function_exists('pll_home_url')) {
        $url = pll_home_url($lang);
        if ($url) { return $url; }
    }
    if (in_array($lang, array('en', 'ru'), true)) {
        return home_url('/' . $lang . '/');
    }
    return home_url('/');
}

function sixtythree_current_pirts_blog_id() {
    $raw = '';
    if (isset($_GET['pirts_blog'])) {
        $raw = (string) wp_unslash($_GET['pirts_blog']);
    } elseif (isset($_GET['pirts-blog'])) {
        $raw = (string) wp_unslash($_GET['pirts-blog']);
    }
    if ($raw === '') { return 0; }
    if (preg_match('/(1|2|3)/', $raw, $m)) {
        return (int) $m[1];
    }
    return 0;
}

function sixtythree_pirts_blog_share_meta($blog_id = 0) {
    $blog_id = $blog_id ? (int) $blog_id : sixtythree_current_pirts_blog_id();
    if ($blog_id < 1 || $blog_id > 3) { return null; }

    $fallback_images = array(
        1 => 'assets/images/63lv/pirts-zone-clean-04.jpg',
        2 => 'assets/images/63lv/pirts-zone-clean-05.jpg',
        3 => 'assets/images/63lv/pirts-zone-clean-06.jpg',
    );

    $title = function_exists('sixtythree_homepage_text')
        ? sixtythree_homepage_text('pirts_blog_' . $blog_id . '_title')
        : '';
    $description = function_exists('sixtythree_homepage_text')
        ? sixtythree_homepage_text('pirts_blog_' . $blog_id . '_text')
        : '';
    $image = function_exists('sixtythree_homepage_media_url')
        ? sixtythree_homepage_media_url('pirts_blog_image_' . $blog_id, $fallback_images[$blog_id])
        : get_template_directory_uri() . '/' . $fallback_images[$blog_id];

    $url = add_query_arg('pirts_blog', $blog_id, sixtythree_language_home_url());

    return array(
        'title' => $title ?: sixtythree_social_share_defaults()['title'],
        'description' => $description ?: sixtythree_social_share_defaults()['description'],
        'image' => $image,
        'url' => $url,
        'type' => 'article',
        'blog_id' => $blog_id,
    );
}

function sixtythree_social_share_current_meta() {
    $blog_meta = sixtythree_pirts_blog_share_meta();
    if (is_array($blog_meta)) { return $blog_meta; }
    return sixtythree_social_share_defaults();
}

function sixtythree_social_share_meta() {
    if (is_admin()) { return; }
    $defaults = sixtythree_social_share_current_meta();
    $title = $defaults['title'];
    $description = $defaults['description'];
    $url = $defaults['url'] ?? (is_singular() ? get_permalink() : sixtythree_language_home_url());
    $image = $defaults['image'];
    $type = $defaults['type'] ?? 'website';
    $locale_map = array('lv' => 'lv_LV', 'en' => 'en_US', 'ru' => 'ru_RU');
    $locale = $locale_map[sixtythree_current_lang()] ?? 'lv_LV';

    echo "
<!-- 63.lv social sharing -->
";
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "
";
    echo '<meta property="og:locale" content="' . esc_attr($locale) . '">' . "
";
    echo '<meta property="og:type" content="' . esc_attr($type) . '">' . "
";
    echo '<meta property="og:site_name" content="63.lv">' . "
";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "
";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "
";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "
";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "
";
    echo '<meta property="og:image:secure_url" content="' . esc_url($image) . '">' . "
";
    echo '<meta property="og:image:type" content="image/jpeg">' . "
";
    echo '<meta property="og:image:alt" content="' . esc_attr($title . ' - ' . $description) . '">' . "
";
    if (($defaults['type'] ?? '') === 'article') {
        echo '<meta property="article:section" content="Pirts blogs">' . "
";
        echo '<meta property="article:tag" content="Pirts">' . "
";
        echo '<meta property="article:tag" content="Bauskas 63">' . "
";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "
";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "
";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "
";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "
";
    echo '<meta name="twitter:image:alt" content="' . esc_attr($title . ' - ' . $description) . '">' . "
";
}
add_action('wp_head', 'sixtythree_social_share_meta', 5);

function sixtythree_filter_document_title($title) {
    if (is_admin()) { return $title; }
    if (is_front_page() || is_home()) {
        return sixtythree_social_share_current_meta()['title'];
    }
    return $title;
}
add_filter('pre_get_document_title', 'sixtythree_filter_document_title', 20);

function sixtythree_filter_document_title_parts($parts) {
    if (is_admin()) { return $parts; }
    if (is_front_page() || is_home()) {
        $parts['title'] = sixtythree_social_share_current_meta()['title'];
        if (isset($parts['tagline'])) {
            $parts['tagline'] = '';
        }
        if (isset($parts['site'])) {
            $parts['site'] = '63.lv';
        }
    }
    return $parts;
}
add_filter('document_title_parts', 'sixtythree_filter_document_title_parts', 20);

function sixtythree_localbusiness_schema() {
    if (is_admin()) { return; }
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => sixtythree_social_share_defaults()['title'],
        'url' => home_url('/'),
        'logo' => get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services.png',
        'image' => sixtythree_social_share_defaults()['image'],
        'description' => sixtythree_social_share_defaults()['description'],
        'telephone' => sixtythree_contact_phone(),
        'email' => sixtythree_contact_email(),
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => 'Bauskas iela 63',
            'addressLocality' => 'Rīga',
            'addressCountry' => 'LV'
        ),
        'sameAs' => array('https://www.facebook.com/profile.php?id=100068508997510'),
        'makesOffer' => array(
            array('@type' => 'Offer', 'name' => 'Pirts zona'),
            array('@type' => 'Offer', 'name' => 'Telpu noma'),
            array('@type' => 'Offer', 'name' => 'Web izstrāde'),
            array('@type' => 'Offer', 'name' => 'Apmācības')
        )
    );
    echo "\n<script type=\"application/ld+json\">" . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
}
add_action('wp_head', 'sixtythree_localbusiness_schema', 6);

/** Keep homepage indexable even when older metadata/plugin output tries to add noindex. */
function sixtythree_force_index_robots($robots) {
    if (is_front_page() || is_home()) {
        unset($robots['noindex'], $robots['nofollow']);
        $robots['index'] = true;
        $robots['follow'] = true;
    }
    return $robots;
}
add_filter('wp_robots', 'sixtythree_force_index_robots', 99);


function sixtythree_minify_front_html($html) {
    if (is_admin() || is_feed() || is_customize_preview()) { return $html; }
    $placeholders = array();
    $html = preg_replace_callback('#<(script|style|textarea|pre)\b[^>]*>.*?</\\1>#is', function($m) use (&$placeholders) {
        $key = '%%SIXTYTHREE_KEEP_' . count($placeholders) . '%%';
        $placeholders[$key] = $m[0];
        return $key;
    }, $html);
    $html = preg_replace('/<!--(?!\s*\[if).*?-->/s', '', $html);
    $html = preg_replace('/>\s+</', '><', $html);
    $html = preg_replace('/\s{2,}/', ' ', $html);
    $html = trim($html);
    foreach ($placeholders as $key => $value) {
        $html = str_replace($key, $value, $html);
    }
    return $html;
}

function sixtythree_frontpage_output($html) {
    return sixtythree_translate_front_html($html);
}




function sixtythree_facebook_footer_item() {
    $url = 'https://www.facebook.com/profile.php?id=100068508997510';
    ob_start();
    ?>
    <a class="footer-item footer-social-facebook footer-social-icon-only" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="Facebook 63.lv">
      <span class="footer-icon footer-icon-facebook" aria-hidden="true"><svg class="gold-svg-icon" viewBox="0 0 24 24"><path d="M14.25 8.25V6.7c0-.74.5-.91.85-.91h2.17V2.08L14.28 2C10.97 2 10.21 4.48 10.21 6.06v2.19H7.5V12h2.71v10h4.04V12h2.98l.39-3.75h-3.37Z"/></svg></span>
    </a>
    <?php
    return ob_get_clean();
}

function sixtythree_scripts() {
    $ver = SIXTYTHREE_THEME_VERSION;
    $css_file = get_template_directory() . '/assets/css/63lv-theme.css';
    $js_file  = get_template_directory() . '/assets/js/63lv-theme.js';
    $css_ver = file_exists($css_file) ? filemtime($css_file) : $ver;
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : $ver;
    $css_asset = '/assets/css/63lv-theme.css';
    $js_asset = '/assets/js/63lv-theme.js';

    wp_enqueue_style('sixtythree-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css', array(), '5.3.3');
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.1.14');
    wp_enqueue_style('sixtythree-theme', get_template_directory_uri() . $css_asset, array('sixtythree-fonts','bootstrap','swiper'), $css_ver);

    $wpbb_compat_css = get_template_directory() . '/assets/css/wpbb-compat.css';
    if (file_exists($wpbb_compat_css)) {
        wp_enqueue_style('sixtythree-wpbb-compat-style', get_template_directory_uri() . '/assets/css/wpbb-compat.css', array('sixtythree-theme'), filemtime($wpbb_compat_css));
    }

    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.1.14', true);
    wp_enqueue_script('sixtythree-theme', get_template_directory_uri() . $js_asset, array('swiper'), $js_ver, true);

    $wpbb_compat_file = get_template_directory() . '/assets/js/wpbb-compat.js';
    $wpbb_compat_ver = file_exists($wpbb_compat_file) ? filemtime($wpbb_compat_file) : $ver;
    wp_enqueue_script('sixtythree-wpbb-compat', get_template_directory_uri() . '/assets/js/wpbb-compat.js', array('sixtythree-theme'), $wpbb_compat_ver, true);
    wp_localize_script('sixtythree-wpbb-compat', 'sixtythreeWpbbCompat', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wpbb_form_nonce'),
        'error' => sixtythree_i18n('Neizdevās nosūtīt formu. Lūdzu, mēģiniet vēlreiz.', 'Unable to send the form. Please try again.', 'Не удалось отправить форму. Попробуйте еще раз.'),
        'validationText' => sixtythree_i18n('Lūdzu, aizpildiet obligātos laukus.', 'Please fill in the required fields.', 'Пожалуйста, заполните обязательные поля.'),
        'hcaptchaLang' => sixtythree_hcaptcha_lang(),
        'hcaptchaApiUrl' => sixtythree_hcaptcha_api_url(),
    ));
    wp_localize_script('sixtythree-theme', 'sixtythreeTheme', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sixtythree_search'),
        'wpbb' => array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpbb_form_nonce'),
            'error' => sixtythree_i18n('Neizdevās nosūtīt formu. Lūdzu, mēģiniet vēlreiz.', 'Unable to send the form. Please try again.', 'Не удалось отправить форму. Попробуйте еще раз.'),
            'validationText' => sixtythree_i18n('Lūdzu, aizpildiet obligātos laukus.', 'Please fill in the required fields.', 'Пожалуйста, заполните обязательные поля.'),
            'hcaptchaLang' => sixtythree_hcaptcha_lang(),
            'hcaptchaApiUrl' => sixtythree_hcaptcha_api_url(),
        ),
        'lang' => sixtythree_current_lang(),
        'labels' => array(
            'searching' => sixtythree_i18n('Meklē...', 'Searching...', 'Поиск...'),
            'noResults' => sixtythree_i18n('Nekas netika atrasts.', 'No results found.', 'Ничего не найдено.'),
            'copyLink' => sixtythree_i18n('Kopēt saiti', 'Copy link', 'Скопировать ссылку'),
            'copiedLink' => sixtythree_i18n('Saite nokopēta', 'Link copied', 'Ссылка скопирована'),
            'copyPrompt' => sixtythree_i18n('Kopēt saiti:', 'Copy link:', 'Скопировать ссылку:'),
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

function sixtythree_form_config($attributes = array()) {
    $settings = get_option('wpbb_settings', array());
    if (!is_array($settings)) { $settings = array(); }
    $lang = sixtythree_current_lang();
    $defaults = array(
        'recipient' => $settings['default_recipient_email'] ?? sixtythree_contact_email(),
        'emailSubject' => sixtythree_i18n('63.lv pieteikums', '63.lv request', 'Заявка 63.lv'),
        'successMessage' => sixtythree_i18n('Paldies! Sazināsimies ar jums.', 'Thank you! We will contact you.', 'Спасибо! Мы свяжемся с вами.'),
        'submitText' => sixtythree_i18n('Nosūtīt pieteikumu →', 'Send request →', 'Отправить заявку →'),
        'formClass' => 'wpbb-form',
        'fieldsJson' => '',
    );
    $config = array_merge($defaults, is_array($attributes) ? $attributes : array());
    $config['recipient'] = sanitize_email($config['recipient']);
    if (!$config['recipient']) { $config['recipient'] = sixtythree_contact_email(); }
    return $config;
}

function sixtythree_default_contact_fields() {
    return array(
        array('type'=>'text','name'=>'name','placeholder'=>sixtythree_i18n('Vārds', 'Name', 'Имя'),'required'=>true,'width'=>6),
        array('type'=>'text','name'=>'company','placeholder'=>sixtythree_i18n('Uzņēmums / projekts', 'Company / project', 'Компания / проект'),'required'=>false,'width'=>6),
        array('type'=>'email','name'=>'email','placeholder'=>sixtythree_i18n('Epasts', 'Email', 'Эл. почта'),'required'=>true,'width'=>6),
        array('type'=>'phone','name'=>'phone','placeholder'=>sixtythree_i18n('Tālrunis', 'Phone', 'Телефон'),'required'=>false,'width'=>6),
        array('type'=>'select','name'=>'service','placeholder'=>sixtythree_i18n('Interesējošais pakalpojums', 'Service of interest', 'Интересующая услуга'),'options'=>sixtythree_i18n("Telpu noma
Pirts zona
Web izstrāde
Solārijs
Citi pakalpojumi", "Space rental
Sauna zone
Web development
Solarium
Other services", "Аренда помещений
Зона сауны
Веб-разработка
Солярий
Другие услуги"),'required'=>false,'width'=>12),
        array('type'=>'textarea','name'=>'message','placeholder'=>sixtythree_i18n('Pastāstiet īsi par savu vajadzību...', 'Tell us briefly what you need...', 'Кратко опишите вашу потребность...'),'required'=>true,'width'=>12),
    );
}

function sixtythree_fallback_contact_form($attributes = array()) {
    $config = sixtythree_form_config($attributes);
    $fields = array();
    if (!empty($config['fieldsJson'])) {
        $decoded = json_decode(wp_unslash($config['fieldsJson']), true);
        if (is_array($decoded)) { $fields = $decoded; }
    }
    if (!$fields) { $fields = sixtythree_default_contact_fields(); }
    $status = isset($_GET['sixtythree_form']) ? sanitize_key($_GET['sixtythree_form']) : '';
    if ($status === 'sent') {
        echo '<div class="sixtythree-form-message success">' . esc_html($config['successMessage']) . '</div>';
    } elseif ($status === 'error') {
        echo '<div class="sixtythree-form-message error">' . esc_html(sixtythree_i18n('Lūdzu aizpildiet obligātos laukus.', 'Please fill in the required fields.', 'Пожалуйста, заполните обязательные поля.')) . '</div>';
    }
    ?>
    <form class="<?php echo esc_attr($config['formClass']); ?> sixtythree-fallback-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
      <input type="hidden" name="action" value="sixtythree_contact_submit">
      <?php wp_nonce_field('sixtythree_contact_submit', 'sixtythree_contact_nonce'); ?>
      <div class="field-row">
      <?php foreach ($fields as $field) :
          $type = isset($field['type']) ? sanitize_key($field['type']) : 'text';
          $name = isset($field['name']) ? sanitize_key($field['name']) : '';
          if (!$name) { continue; }
          $placeholder = $field['placeholder'] ?? ($field['label'] ?? $name);
          $required = !empty($field['required']);
          $input_type = $type === 'phone' ? 'tel' : ($type === 'email' ? 'email' : 'text');
          if ($type === 'textarea') : ?>
            </div><textarea class="textarea" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" <?php required($required); ?>></textarea><div class="field-row">
          <?php elseif ($type === 'select') :
              $options = preg_split('/\r\n|\r|\n/', (string)($field['options'] ?? '')); ?>
            </div><select class="input" name="<?php echo esc_attr($name); ?>" <?php required($required); ?>>
              <option value=""><?php echo esc_html($placeholder); ?></option>
              <?php foreach ($options as $option) : $option = trim($option); if ($option === '') { continue; } ?>
                <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
              <?php endforeach; ?>
            </select><div class="field-row">
          <?php else : ?>
            <input class="input" type="<?php echo esc_attr($input_type); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" <?php required($required); ?>>
          <?php endif; ?>
      <?php endforeach; ?>
      </div>
      <button type="submit" class="btn"><?php echo esc_html($config['submitText']); ?></button>
    </form>
    <?php
}

function sixtythree_handle_contact_submit() {
    if (!isset($_POST['sixtythree_contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sixtythree_contact_nonce'])), 'sixtythree_contact_submit')) {
        wp_safe_redirect(add_query_arg('sixtythree_form', 'error', wp_get_referer() ?: home_url('/'))); exit;
    }
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    if (!$name || !$email || !$message) {
        wp_safe_redirect(add_query_arg('sixtythree_form', 'error', wp_get_referer() ?: home_url('/'))); exit;
    }
    $settings = get_option('wpbb_settings', array());
    if (!is_array($settings)) { $settings = array(); }
    $to = sanitize_email($settings['default_recipient_email'] ?? sixtythree_contact_email());
    if (!$to) { $to = sixtythree_contact_email(); }
    $subject = sixtythree_i18n('63.lv pieteikums', '63.lv request', 'Заявка 63.lv');
    $lines = array();
    foreach (array('name','company','email','phone','service','message') as $key) {
        if (isset($_POST[$key]) && $_POST[$key] !== '') {
            $value = $key === 'message' ? sanitize_textarea_field(wp_unslash($_POST[$key])) : sanitize_text_field(wp_unslash($_POST[$key]));
            $lines[] = ucfirst($key) . ': ' . $value;
        }
    }
    $headers = array('Reply-To: ' . $name . ' <' . $email . '>');
    wp_mail($to, $subject, implode("
", $lines), $headers);
    wp_safe_redirect(add_query_arg('sixtythree_form', 'sent', wp_get_referer() ?: home_url('/'))); exit;
}
add_action('admin_post_sixtythree_contact_submit', 'sixtythree_handle_contact_submit');
add_action('admin_post_nopriv_sixtythree_contact_submit', 'sixtythree_handle_contact_submit');

function sixtythree_ajax_search() {
    check_ajax_referer('sixtythree_search', 'nonce');
    $term = sanitize_text_field(wp_unslash($_GET['s'] ?? ($_GET['term'] ?? '')));
    $term_lc = function_exists('mb_strtolower') ? mb_strtolower($term) : strtolower($term);
    $items = array();

    $section_items = array(
        array(
            'title' => 'Pakalpojumi',
            'url' => home_url('/#pakalpojumi'),
            'type' => 'Lapa',
            'excerpt' => 'Pirts ballītēm, web izstrāde, e-apmācības, skaistumkopšana un citi 63.lv pakalpojumi.',
            'keywords' => 'pakalpojumi pirts ballītes web izstrāde wordpress kursi solārijs frizētava manikīrs šūšana',
        ),
        array(
            'title' => function_exists('sixtythree_homepage_text') ? sixtythree_homepage_text('pirts_kicker', 'Bauskas 63 · pirts zona') : 'Bauskas 63 · pirts zona',
            'url' => home_url('/#pirts'),
            'type' => 'Lapa',
            'excerpt' => function_exists('sixtythree_homepage_text') ? sixtythree_homepage_text('pirts_intro', 'Pirts zona ar rezervācijas kalendāru un pirts ballītes piedāvājumu.') : 'Pirts zona ar rezervācijas kalendāru un pirts ballītes piedāvājumu.',
            'keywords' => 'pirts sauna ballīte rezervācija kalendārs bauskas 63 atpūta svinības',
        ),
        array(
            'title' => 'Vertikālais solārijs — cenas',
            'url' => home_url('/#skaistums-cenas'),
            'type' => 'Lapa',
            'excerpt' => 'Solārija cenas un skaistuma pakalpojumu informācija.',
            'keywords' => 'solārijs cenas skaistums frizētava manikīrs pedikīrs',
        ),
        array(
            'title' => 'Web izstrāde',
            'url' => home_url('/#web'),
            'type' => 'Lapa',
            'excerpt' => 'WordPress, WooCommerce, React, SEO, hostings un uzturēšana.',
            'keywords' => 'web izstrāde mājaslapas wordpress woocommerce react seo hostings uzturēšana',
        ),
        array(
            'title' => 'Kontakti',
            'url' => home_url('/#cta'),
            'type' => 'Lapa',
            'excerpt' => 'Saziņas forma, tālrunis un e-pasts.',
            'keywords' => 'kontakti forma pieteikt konsultāciju telefons epasts e-pasts',
        ),
        array(
            'title' => 'Atrašanās vieta',
            'url' => home_url('/#map'),
            'type' => 'Lapa',
            'excerpt' => 'Bauskas iela 63, Rīga — karte un adrese.',
            'keywords' => 'karte adrese atrašanās vieta bauskas iela rīga',
        ),
    );

    if ($term_lc !== '') {
        foreach ($section_items as $item) {
            $haystack = implode(' ', array($item['title'], $item['excerpt'], $item['keywords']));
            $haystack_lc = function_exists('mb_strtolower') ? mb_strtolower($haystack) : strtolower($haystack);
            if (strpos($haystack_lc, $term_lc) !== false) {
                unset($item['keywords']);
                $items[] = $item;
            }
        }
    } else {
        foreach (array_slice($section_items, 0, 6) as $item) {
            unset($item['keywords']);
            $items[] = $item;
        }
    }

    if ($term_lc !== '') {
        $q = new WP_Query(array(
            's' => $term,
            'post_type' => array('post','page'),
            'post_status' => 'publish',
            'posts_per_page' => 6,
        ));
        while ($q->have_posts()) {
            $q->the_post();
            $items[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type(),
                'excerpt' => wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 22),
            );
        }
        wp_reset_postdata();
    }

    $deduped = array();
    $seen = array();
    foreach ($items as $item) {
        $key = md5(($item['url'] ?? '') . '|' . ($item['title'] ?? ''));
        if (isset($seen[$key])) { continue; }
        $seen[$key] = true;
        $deduped[] = $item;
    }

    wp_send_json_success(array('items' => $deduped));
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
    // Form title intentionally hidden to avoid duplicate output.
    sixtythree_fallback_contact_form($attributes);
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
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'In the services section you can find sauna zone bookings, room rental, web development, training and everyday services. Choose the direction you need and contact us about availability.',
            'Pirts ballītēm' => 'For sauna parties',
            'Web izstrāde un uzturēšana' => 'Web development and maintenance',
            'E-apmācības un kursi' => 'E-training and courses',
            'Citi pakalpojumi' => 'Other services',
            'Skatīt vairāk →' => 'View more →',
            'Bauskas 63 · pirts zona' => 'Bauskas 63 · sauna zone',
            'Vieta ģimenei, draugiem un svinībām' => 'A place for family, friends and celebrations',
            'Pirts zonas piedāvājums ģimenei, draugiem un nelielām svinībām ar cenu, ilgumu, iekļautajiem pakalpojumiem, kalendāru un ātru pieteikšanos.' => 'A sauna-zone offer for family, friends and small celebrations with price, duration, included services, calendar and quick booking.',
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
            'Pilnais raksta saturs par sagatavošanos pirts vakaram. Aprakstiet, ko paņemt līdzi, kā plānot vakaru un kādi ir saimnieku ieteikumi ērtai atpūtai.' => 'Full article content about preparing for a sauna evening. Learn what to bring, how to plan the evening and what the hosts recommend for comfortable relaxation.',
            'Pilnais raksta saturs par pirts ballīti līdz 15 personām. Šeit var ievadīt detalizētākus padomus par sēdvietām, uzkodām, plānu un pasākuma gaitu.' => 'Full article content about a sauna party for up to 15 people. Here you can add detailed tips about seating, snacks, planning and the flow of the event.',
            'Pilnais raksta saturs ar idejām pasākuma plānam. Aprakstiet vakara programmu, saunas apmeklējumu, atpūtas pauzes un citus noderīgus ieteikumus.' => 'Full article content with ideas for the event plan. Describe the evening programme, sauna visit, rest breaks and other useful recommendations.',
            'Kopēt saiti' => 'Copy link',
            'Saite nokopēta' => 'Link copied',
            'Skaistumam' => 'For beauty',
            'Vertikālais solārijs — cenas' => 'Vertical solarium — prices',
            'Vertikālais solārijs ar skaidrām cenām un ērtu pieteikšanos. Pieejams pēc iepriekšējas vienošanās, saziņai' => 'Vertical solarium with clear pricing and convenient booking. Available by appointment, contact',
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
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'You can find us at Bauskas Street 63, Riga — with convenient access from both the street and courtyard side.',
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
            'Pirts zonas piedāvājums ģimenei, draugiem un nelielām svinībām ar cenu, ilgumu, iekļautajiem pakalpojumiem, kalendāru un ātru pieteikšanos.' => 'Предложение сауны для семьи, друзей и небольших праздников с ценой, длительностью, включёнными услугами, календарём и быстрой заявкой.',
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
            'Pilnais raksta saturs par sagatavošanos pirts vakaram. Aprakstiet, ko paņemt līdzi, kā plānot vakaru un kādi ir saimnieku ieteikumi ērtai atpūtai.' => 'Полный текст статьи о подготовке к вечеру в сауне. Что взять с собой, как спланировать вечер и что рекомендуют хозяева для комфортного отдыха.',
            'Pilnais raksta saturs par pirts ballīti līdz 15 personām. Šeit var ievadīt detalizētākus padomus par sēdvietām, uzkodām, plānu un pasākuma gaitu.' => 'Полный текст статьи о сауне-вечеринке до 15 человек. Здесь можно добавить подробные советы о местах, закусках, плане и ходе мероприятия.',
            'Pilnais raksta saturs ar idejām pasākuma plānam. Aprakstiet vakara programmu, saunas apmeklējumu, atpūtas pauzes un citus noderīgus ieteikumus.' => 'Полный текст статьи с идеями для плана мероприятия. Опишите программу вечера, посещение сауны, паузы для отдыха и другие полезные рекомендации.',
            'Kopēt saiti' => 'Скопировать ссылку',
            'Saite nokopēta' => 'Ссылка скопирована',
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
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'Вы найдёте нас на улице Баускас 63, в Риге — с удобным доступом как со стороны улицы, так и со двора.',
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

    $hard_map = function_exists('sixtythree_front_translation_hardfix_map') ? sixtythree_front_translation_hardfix_map() : array();
    if (!empty($hard_map[$lang])) {
        $map[$lang] = array_merge($map[$lang] ?? array(), $hard_map[$lang]);
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
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē — telpas, pirts zonu, apmācības, web risinājumus un ikdienas servisu vienuviet.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide. ',
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
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'In the services section you can find sauna zone bookings, room rental, web development, training and everyday services. Choose the direction you need and contact us about availability.',
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
            'Vertikālais solārijs ar skaidrām cenām un ērtu pieteikšanos. Pieejams pēc iepriekšējas vienošanās, saziņai' => 'Vertical solarium with clear pricing and convenient booking. Available by appointment, contact',
            'Telpas biznesam' => 'Business premises',
            'Atvērt piedāvājumu' => 'Open offer',
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003.',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Display windows and entrance from Bauskas Street.',
            'Atsevišķa ieeja no pagalma puses.' => 'Separate entrance from the courtyard side.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for an office, shop, salon and more.',
            'Izstrādājam WordPress, WooCommerce un headless ReactJS risinājumus uzņēmumiem, kuriem svarīgs ātrums, pārskatāma administrēšana un labs rezultāts meklētājos.' => 'We build WordPress, WooCommerce and headless ReactJS solutions for businesses that need speed, clear administration and strong search visibility.',
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
            'Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Sazinieties ar mums, lai pārrunātu pieejamību vai projekta vajadzības.' => 'We can help with space rental, web development, training and other services at Bauskas Street 63. Contact us to discuss availability or project needs.',
            'Epasts' => 'Email',
            'Atbildēsim 1 darba dienas laikā.' => 'We reply within one working day.',
            'Tālrunis' => 'Phone',
            'Zvaniet vai rakstiet par pieejamību.' => 'Call or write about availability.',
            'Adrese' => 'Address',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Easy access from the street and courtyard side.',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'Find us at Bauskas Street 63, Riga — with convenient access from the street and courtyard side.',
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
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'In the services section you can find sauna zone bookings, room rental, web development, training and everyday services. Choose the direction you need and contact us about availability.',
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
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003.',
            'Vitrīnas logi un ieeja no Bauskas ielas.' => 'Display windows and entrance from Bauskas Street.',
            'Atsevišķa ieeja no pagalma puses.' => 'Separate entrance from the courtyard side.',
            'Piemērots birojam, veikalam, salonam u.c.' => 'Suitable for an office, shop, salon and more.',
            'Izstrādājam WordPress, WooCommerce un headless ReactJS risinājumus uzņēmumiem, kuriem svarīgs ātrums, pārskatāma administrēšana un labs rezultāts meklētājos.' => 'We build WordPress, WooCommerce and headless ReactJS solutions for businesses that need speed, clear administration and strong search visibility.',
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
            'Varam palīdzēt ar telpu nomu, web izstrādi, apmācībām un citiem pakalpojumiem Bauskas ielā 63. Sazinieties ar mums, lai pārrunātu pieejamību vai projekta vajadzības.' => 'We can help with space rental, web development, training and other services at Bauskas Street 63. Contact us to discuss availability or project needs.',
            'Atbildēsim 1 darba dienas laikā.' => 'We reply within one working day.',
            'Zvaniet vai rakstiet par pieejamību.' => 'Call or write about availability.',
            'Adrese' => 'Address',
            'Ērta piekļuve no ielas un pagalma puses.' => 'Easy access from the street and courtyard side.',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'Find us at Bauskas Street 63, Riga — with convenient access from the street and courtyard side.',
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
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē — telpas, pirts zonu, apmācības, web risinājumus un ikdienas servisu vienuviet.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide. ',
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'In the services section you can find sauna zone bookings, room rental, web development, training and everyday services. Choose the direction you need and contact us about availability.',
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'In the services section you can find sauna zone bookings, room rental, web development, training and everyday services. Choose the direction you need and contact us about availability.',
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē.' => 'SIA Kalns RTL was founded in 2003. We provide services on site at Bauskas Street 63 in Riga, as well as remotely across Latvia and worldwide.',
            '' => '',
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
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē — telpas, pirts zonu, apmācības, web risinājumus un ikdienas servisu vienuviet.' => 'SIA Kalns RTL основана в 2003 году. Мы предоставляем услуги на месте по адресу улица Баускас 63 в Риге, а также удалённо по всей Латвии и миру. ',
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'В разделе услуг доступны бронирование сауны, аренда помещений, веб-разработка, обучение и повседневные услуги. Выберите нужное направление и свяжитесь с нами по поводу доступности.',
            'Pakalpojumu sadaļā atradīsiet pirts zonas rezervāciju, telpu nomu, web izstrādi, apmācības un ikdienas pakalpojumus. Izvēlieties vajadzīgo virzienu un sazinieties ar mums par pieejamību.' => 'В разделе услуг доступны бронирование сауны, аренда помещений, веб-разработка, обучение и повседневные услуги. Выберите нужное направление и свяжитесь с нами по поводу доступности.',
            'SIA Kalns RTL dibināta 2003. gadā. Piedāvājam pakalpojumus uz vietas Bauskas ielā 63, Rīgā, kā arī attālināti visā Latvijā un pasaulē.' => 'SIA Kalns RTL основана в 2003 году. Мы предоставляем услуги на месте по адресу улица Баускас 63 в Риге, а также удалённо по всей Латвии и миру.',
            '' => '',
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
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā.' => 'Modern spaces, a personal approach and quality services — supporting your everyday and business needs since 2003.',
            'Izstrādājam WordPress, WooCommerce un headless ReactJS risinājumus uzņēmumiem, kuriem svarīgs ātrums, pārskatāma administrēšana un labs rezultāts meklētājos.' => 'We build WordPress, WooCommerce and headless ReactJS solutions for businesses that need speed, clear administration and strong search visibility.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress pages to headless ReactJS projects — we build solutions that are visually refined, easy to manage and focused on business results. Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'From classic WordPress pages to headless ReactJS projects — we build solutions that are visually refined, easy to manage and focused on business results. Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Additionally: AI theme/plugin development, hosting, support and maintenance.',
            'Bauskas 63, Rīga' => 'Bauskas 63, Riga',
            'Bauskas iela 63, Rīga' => 'Bauskas Street 63, Riga',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'You can find us at Bauskas Street 63, Riga — with convenient access from both the street and courtyard side.',
            'Web Themes' => 'Web Themes',
            'WooCommerce' => 'WooCommerce',
            'Plugins & Custom Code' => 'Plugins & Custom Code',
            'Headless ReactJS' => 'Headless ReactJS',
            'AI theme development' => 'AI theme development',
            'AI plugin development' => 'AI plugin development',
            'Hosting, support & maintenance' => 'Hosting, support & maintenance',
        ),
        'ru' => array(
            'Mūsdienīgas telpas, personīga pieeja un kvalitatīvi pakalpojumi — kopš 2003. gada jūsu atbalstam ikdienā un biznesā.' => 'Современные помещения, индивидуальный подход и качественные услуги — с 2003 года мы поддерживаем ваши повседневные и бизнес-задачи.',
            'Izstrādājam WordPress, WooCommerce un headless ReactJS risinājumus uzņēmumiem, kuriem svarīgs ātrums, pārskatāma administrēšana un labs rezultāts meklētājos.' => 'Создаём WordPress, WooCommerce и headless ReactJS решения для бизнеса: быстрые сайты, удобное управление и хорошая видимость в поиске.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'От классических сайтов WordPress до headless ReactJS проектов — создаём решения, которые выглядят качественно, легко управляются и ориентированы на бизнес-результат. Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'No klasiskām WordPress lapām līdz headless ReactJS projektiem — veidojam risinājumus, kas ir vizuāli kvalitatīvi, ērti pārvaldāmi un orientēti uz biznesa rezultātu. Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'От классических сайтов WordPress до headless ReactJS проектов — создаём решения, которые выглядят качественно, легко управляются и ориентированы на бизнес-результат. Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Papildus: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Дополнительно: AI theme/plugin development, hostings, atbalsts un uzturēšana.' => 'Дополнительно: разработка AI тем/плагинов, хостинг, поддержка и обслуживание.',
            'Bauskas 63, Rīga' => 'Баускас 63, Рига',
            'Bauskas iela 63, Rīga' => 'Улица Баускас 63, Рига',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'Вы найдёте нас на улице Баускас 63, в Риге — с удобным доступом как со стороны улицы, так и со двора.',
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
 * Hard fallback translations for live strings that must never stay in Latvian
 * on translated pages.
 */
function sixtythree_front_translation_hardfix_map() {
    return array(
        'en' => array(
            'Atrašanās vieta' => 'Location',
            'Bauskas iela 63, Rīga' => 'Bauskas Street 63, Riga',
            'Bauskas 63, Rīga' => 'Bauskas 63, Riga',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'You can find us at Bauskas Street 63, Riga — with convenient access from both the street and courtyard side.',
            'Meklēt' => 'Search',
            'Meklēt šajā lapā...' => 'Search this page...',
            'Meklēt šajā lapā' => 'Search this page',
            'Kontakti' => 'Contacts',
            'Sazināties' => 'Contact us',
            'Sazināties →' => 'Contact →',
            'Visi pakalpojumi' => 'All services',
        ),
        'ru' => array(
            'Atrašanās vieta' => 'Местоположение',
            'Bauskas iela 63, Rīga' => 'Улица Баускас 63, Рига',
            'Bauskas 63, Rīga' => 'Баускас 63, Рига',
            'Mūs atradīsiet Bauskas ielā 63, Rīgā — ar ērtu piekļuvi no ielas un pagalma puses.' => 'Вы найдёте нас на улице Баускас 63, в Риге — с удобным доступом как со стороны улицы, так и со двора.',
            'Meklēt' => 'Поиск',
            'Meklēt šajā lapā...' => 'Поиск на этой странице...',
            'Meklēt šajā lapā' => 'Поиск на этой странице',
            'Kontakti' => 'Контакты',
            'Sazināties' => 'Связаться',
            'Sazināties →' => 'Связаться →',
            'Visi pakalpojumi' => 'Все услуги',
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
 * WP BBuilder hCaptcha compatibility.
 * The BBuilder block renders hCaptcha only when settings are present. The theme
 * also renders a safe fallback widget for the contact shortcode and makes sure
 * the hCaptcha API is loaded on the frontend.
 */
function sixtythree_wpbb_setting($key, $default = '') {
    if (function_exists('wpbb_get_option')) {
        return wpbb_get_option($key, $default);
    }
    $settings = get_option('wpbb_settings', array());
    if (!is_array($settings)) { $settings = array(); }
    return array_key_exists($key, $settings) ? $settings[$key] : $default;
}

function sixtythree_hcaptcha_site_key() {
    return trim((string) sixtythree_wpbb_setting('hcaptcha_site_key', ''));
}

function sixtythree_hcaptcha_enabled() {
    return (bool) sixtythree_wpbb_setting('hcaptcha_enabled', 0) && sixtythree_hcaptcha_site_key() !== '';
}

function sixtythree_hcaptcha_lang() {
    $lang = sixtythree_current_lang();
    $map = array(
        'lv' => 'lv',
        'en' => 'en',
        'ru' => 'ru',
    );
    return $map[$lang] ?? 'lv';
}

function sixtythree_hcaptcha_api_url() {
    return add_query_arg(
        array(
            'render' => 'explicit',
            'hl' => sixtythree_hcaptcha_lang(),
        ),
        'https://js.hcaptcha.com/1/api.js'
    );
}

function sixtythree_filter_hcaptcha_plugin_api_src($src) {
    if (!is_string($src) || $src === '') { return $src; }
    return add_query_arg('hl', sixtythree_hcaptcha_lang(), $src);
}
add_filter('hcap_api_src', 'sixtythree_filter_hcaptcha_plugin_api_src', 20);

function sixtythree_enqueue_hcaptcha_api() {
    if (!sixtythree_hcaptcha_enabled()) { return; }
    if (wp_script_is('hcaptcha-api', 'enqueued')) { return; }
    wp_enqueue_script('hcaptcha-api', sixtythree_hcaptcha_api_url(), array(), null, true);
}
add_action('wp_enqueue_scripts', 'sixtythree_enqueue_hcaptcha_api', 30);

function sixtythree_hcaptcha_markup() {
    if (!sixtythree_hcaptcha_enabled()) { return ''; }
    $site_key = sixtythree_hcaptcha_site_key();
    $lang = sixtythree_hcaptcha_lang();
    return '<div class="sixtythree-hcaptcha-field col-12"><div class="wpbb-field wpbb-field--captcha"><div class="h-captcha" data-sitekey="' . esc_attr($site_key) . '" data-hl="' . esc_attr($lang) . '"></div><input type="hidden" name="wpbb_captcha_enabled" value="1"><input type="hidden" name="wpbb_captcha_provider" value="hcaptcha"></div></div>';
}

function sixtythree_add_hcaptcha_to_form_html($html) {
    if (!is_string($html) || $html === '' || !sixtythree_hcaptcha_enabled()) { return $html; }
    if (stripos($html, 'wpbb-dynamic-form') === false) { return $html; }

    // The real BBuilder block already rendered hCaptcha; only make sure hidden provider fields exist.
    if (stripos($html, 'h-captcha') !== false) {
        if (stripos($html, 'name="wpbb_captcha_provider"') === false) {
            $html = str_ireplace('</form>', '<input type="hidden" name="wpbb_captcha_enabled" value="1"><input type="hidden" name="wpbb_captcha_provider" value="hcaptcha"></form>', $html);
        }
        return $html;
    }

    $markup = sixtythree_hcaptcha_markup();
    if ($markup === '') { return $html; }

    $targets = array(
        '<div class="wpbb-form-message',
        '<div class="wpbb-form-actions',
        '<button type="submit"',
        '</form>',
    );
    foreach ($targets as $target) {
        $pos = stripos($html, $target);
        if ($pos !== false) {
            return substr($html, 0, $pos) . $markup . substr($html, $pos);
        }
    }
    return $html;
}

/**
 * Core-block friendly shortcodes used by the admin-editable demo homepage.
 * These avoid unsupported custom block warnings while preserving front-end functionality.
 */
function sixtythree_shortcode_contact_form($atts = array()) {
    sixtythree_enqueue_hcaptcha_api();
    $custom_shortcode = trim((string) get_theme_mod('sixtythree_contact_form_shortcode', ''));
    if ($custom_shortcode !== '' && stripos($custom_shortcode, 'sixtythree_contact_form') === false) {
        return sixtythree_add_hcaptcha_to_form_html(do_shortcode($custom_shortcode));
    }
    $block = function_exists('sixtythree_demo_form_block') ? sixtythree_demo_form_block(sixtythree_current_lang()) : '';
    if ($block && function_exists('do_blocks')) {
        return sixtythree_add_hcaptcha_to_form_html(do_blocks($block));
    }
    ob_start();
    echo '<div class="wpbb-dynamic-form-wrap sixtythree-shortcode-form">';
    sixtythree_fallback_contact_form();
    echo '</div>';
    return sixtythree_add_hcaptcha_to_form_html(ob_get_clean());
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

function sixtythree_booking_defaults() {
    return array(
        'booking_kicker' => 'Pieejamība',
        'booking_heading' => 'Maija rezervācijas',
        'booking_button' => 'Pieteikt izvēlēto laiku →',
        'booking_call_button' => 'Zvanīt',
        'booking_legend_available' => 'Pieejams',
        'booking_legend_booked' => 'Aizņemts',
        'booking_legend_selected' => 'Izvēlēts',
        'booking_days_data' => "12|aizņemts|booked
13|16:00+|available
14|18:00+|available
15|aizņemts|booked
16|brīvs|selected
17|aizņemts|booked
18|diena|available
19|16:00+|available
20|aizņemts|booked
21|18:00+|available
22|16:00+|available
23|aizņemts|booked
24|diena|available
25|diena|available",
    );
}

function sixtythree_get_booking_settings() {
    $saved = get_option('sixtythree_booking_settings', array());
    if (!is_array($saved)) { $saved = array(); }
    return wp_parse_args($saved, sixtythree_booking_defaults());
}

function sixtythree_booking_value($key, $fallback = '') {
    $settings = sixtythree_get_booking_settings();
    if (array_key_exists($key, $settings) && trim((string) $settings[$key]) !== '') {
        return $settings[$key];
    }
    $theme_mod = get_theme_mod('sixtythree_' . $key, '');
    if ($theme_mod !== '') { return $theme_mod; }
    $defaults = sixtythree_booking_defaults();
    return array_key_exists($key, $defaults) ? $defaults[$key] : $fallback;
}

function sixtythree_booking_days() {
    $raw = (string) sixtythree_booking_value('booking_days_data');
    $lines = preg_split('/\r\n|\r|\n/', $raw);
    $days = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') { continue; }
        $parts = array_map('trim', explode('|', $line));
        $day = $parts[0] ?? '';
        $label = $parts[1] ?? '';
        $status = sanitize_html_class($parts[2] ?? 'available');
        if ($day === '') { continue; }
        if (!in_array($status, array('available', 'booked', 'selected'), true)) {
            $status = 'available';
        }
        $days[] = array($day, $label, $status);
    }

    if (empty($days)) {
        foreach (preg_split('/\r\n|\r|\n/', sixtythree_booking_defaults()['booking_days_data']) as $line) {
            $parts = array_map('trim', explode('|', $line));
            if (!empty($parts[0])) {
                $days[] = array($parts[0], $parts[1] ?? '', $parts[2] ?? 'available');
            }
        }
    }
    return $days;
}

function sixtythree_shortcode_booking_calendar($atts = array()) {
    $days = sixtythree_booking_days();

    ob_start();
    ?>
    <div class="booking-card sixtythree-shortcode-booking" data-booking-month="<?php echo esc_attr(sixtythree_booking_value('booking_heading')); ?>">
      <div class="calendar-head">
        <div>
          <div class="kicker"><?php echo esc_html(sixtythree_booking_value('booking_kicker')); ?></div>
          <h3><?php echo esc_html(sixtythree_booking_value('booking_heading')); ?></h3>
        </div>
      </div>
      <div class="calendar-grid">
        <?php foreach ($days as $day) : ?>
          <button type="button" class="cal-date <?php echo esc_attr($day[2]); ?>">
            <span><?php echo esc_html($day[0]); ?></span>
            <small><?php echo esc_html($day[1]); ?></small>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="booking-actions">
        <a class="btn" href="#cta"><?php echo esc_html(sixtythree_booking_value('booking_button')); ?></a>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sixtythree_booking_calendar', 'sixtythree_shortcode_booking_calendar');

/**
 * Admin-side booking calendar settings.
 * Manage these under Appearance > 63.lv Booking.
 */
function sixtythree_booking_admin_menu() {
    add_theme_page(
        __('63.lv Booking', 'sixty-three-lv'),
        __('63.lv Booking', 'sixty-three-lv'),
        'manage_options',
        'sixtythree-booking',
        'sixtythree_booking_settings_page'
    );
}
add_action('admin_menu', 'sixtythree_booking_admin_menu');

function sixtythree_register_booking_settings() {
    register_setting('sixtythree_booking_settings_group', 'sixtythree_booking_settings', 'sixtythree_sanitize_booking_settings');
}
add_action('admin_init', 'sixtythree_register_booking_settings');

function sixtythree_sanitize_booking_settings($input) {
    $defaults = sixtythree_booking_defaults();
    $input = is_array($input) ? $input : array();
    $out = array();
    foreach ($defaults as $key => $default) {
        if ($key === 'booking_days_data') {
            $out[$key] = sanitize_textarea_field(wp_unslash($input[$key] ?? $default));
        } else {
            $out[$key] = sanitize_text_field(wp_unslash($input[$key] ?? $default));
        }
    }
    return $out;
}

function sixtythree_booking_settings_page() {
    if (!current_user_can('manage_options')) { return; }
    $settings = sixtythree_get_booking_settings();
    $days = sixtythree_booking_days();
    ?>
    <div class="wrap sixtythree-booking-admin">
      <h1><?php esc_html_e('63.lv Booking Calendar', 'sixty-three-lv'); ?></h1>
      <p><?php esc_html_e('Control the public pirts booking calendar shown on the homepage and through the [sixtythree_booking_calendar] shortcode.', 'sixty-three-lv'); ?></p>
      <form method="post" action="options.php" id="sixtythree-booking-settings-form">
        <?php settings_fields('sixtythree_booking_settings_group'); ?>
        <div class="sixtythree-admin-card">
          <h2><?php esc_html_e('Calendar labels', 'sixty-three-lv'); ?></h2>
          <div class="sixtythree-settings-grid">
            <?php
            $fields = array(
                'booking_kicker' => __('Kicker', 'sixty-three-lv'),
                'booking_heading' => __('Calendar title / month', 'sixty-three-lv'),
                'booking_button' => __('Booking button text', 'sixty-three-lv'),
                'booking_call_button' => __('Call button text', 'sixty-three-lv'),
                'booking_legend_available' => __('Available legend', 'sixty-three-lv'),
                'booking_legend_booked' => __('Booked legend', 'sixty-three-lv'),
                'booking_legend_selected' => __('Selected legend', 'sixty-three-lv'),
            );
            foreach ($fields as $key => $label) : ?>
              <label class="sixtythree-setting-field" for="<?php echo esc_attr($key); ?>">
                <span><?php echo esc_html($label); ?></span>
                <input name="sixtythree_booking_settings[<?php echo esc_attr($key); ?>]" id="<?php echo esc_attr($key); ?>" type="text" value="<?php echo esc_attr($settings[$key] ?? ''); ?>">
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="sixtythree-admin-card sixtythree-booking-builder">
          <div class="sixtythree-booking-builder-head">
            <div>
              <h2><?php esc_html_e('Calendar days', 'sixty-three-lv'); ?></h2>
              <p><?php esc_html_e('Use the block-style cards below. Add, edit, duplicate, remove or reorder days. Booked days are disabled on the public calendar.', 'sixty-three-lv'); ?></p>
            </div>
            <div class="sixtythree-booking-actions-top">
              <button type="button" class="button" id="sixtythree-fill-weekdays"><?php esc_html_e('Quick weekdays', 'sixty-three-lv'); ?></button>
              <button type="button" class="button button-primary" id="sixtythree-add-booking-row"><?php esc_html_e('+ Add day', 'sixty-three-lv'); ?></button>
            </div>
          </div>

          <input type="hidden" name="sixtythree_booking_settings[booking_days_data]" id="booking_days_data" value="<?php echo esc_attr($settings['booking_days_data'] ?? ''); ?>">
          <div class="sixtythree-booking-card-list" id="sixtythree-booking-rows">
            <?php foreach ($days as $index => $day) : ?>
              <div class="sixtythree-booking-row sixtythree-booking-card" data-status="<?php echo esc_attr($day[2]); ?>">
                <div class="sixtythree-booking-card-handle" aria-hidden="true">☰</div>
                <label><span><?php esc_html_e('Day/date', 'sixty-three-lv'); ?></span><input type="text" class="sixtythree-booking-day" value="<?php echo esc_attr($day[0]); ?>" placeholder="12"></label>
                <label><span><?php esc_html_e('Label / time', 'sixty-three-lv'); ?></span><input type="text" class="sixtythree-booking-label" value="<?php echo esc_attr($day[1]); ?>" placeholder="16:00+"></label>
                <label><span><?php esc_html_e('Status', 'sixty-three-lv'); ?></span><select class="sixtythree-booking-status">
                  <option value="available" <?php selected($day[2], 'available'); ?>><?php esc_html_e('Available', 'sixty-three-lv'); ?></option>
                  <option value="booked" <?php selected($day[2], 'booked'); ?>><?php esc_html_e('Booked', 'sixty-three-lv'); ?></option>
                  <option value="selected" <?php selected($day[2], 'selected'); ?>><?php esc_html_e('Selected', 'sixty-three-lv'); ?></option>
                </select></label>
                <div class="sixtythree-booking-card-buttons">
                  <button type="button" class="button sixtythree-move-up" title="<?php esc_attr_e('Move up', 'sixty-three-lv'); ?>">↑</button>
                  <button type="button" class="button sixtythree-move-down" title="<?php esc_attr_e('Move down', 'sixty-three-lv'); ?>">↓</button>
                  <button type="button" class="button sixtythree-duplicate-booking-row"><?php esc_html_e('Duplicate', 'sixty-three-lv'); ?></button>
                  <button type="button" class="button sixtythree-remove-booking-row"><?php esc_html_e('Remove', 'sixty-three-lv'); ?></button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <p class="description" id="sixtythree-booking-help"><?php esc_html_e('Stored format remains compatible with the theme: day|label|status.', 'sixty-three-lv'); ?></p>

          <template id="sixtythree-booking-row-template">
            <div class="sixtythree-booking-row sixtythree-booking-card" data-status="available">
              <div class="sixtythree-booking-card-handle" aria-hidden="true">☰</div>
              <label><span><?php esc_html_e('Day/date', 'sixty-three-lv'); ?></span><input type="text" class="sixtythree-booking-day" value="" placeholder="12"></label>
              <label><span><?php esc_html_e('Label / time', 'sixty-three-lv'); ?></span><input type="text" class="sixtythree-booking-label" value="" placeholder="16:00+"></label>
              <label><span><?php esc_html_e('Status', 'sixty-three-lv'); ?></span><select class="sixtythree-booking-status">
                <option value="available"><?php esc_html_e('Available', 'sixty-three-lv'); ?></option>
                <option value="booked"><?php esc_html_e('Booked', 'sixty-three-lv'); ?></option>
                <option value="selected"><?php esc_html_e('Selected', 'sixty-three-lv'); ?></option>
              </select></label>
              <div class="sixtythree-booking-card-buttons">
                <button type="button" class="button sixtythree-move-up">↑</button>
                <button type="button" class="button sixtythree-move-down">↓</button>
                <button type="button" class="button sixtythree-duplicate-booking-row"><?php esc_html_e('Duplicate', 'sixty-three-lv'); ?></button>
                <button type="button" class="button sixtythree-remove-booking-row"><?php esc_html_e('Remove', 'sixty-three-lv'); ?></button>
              </div>
            </div>
          </template>
        </div>

        <?php submit_button(__('Save booking calendar', 'sixty-three-lv')); ?>
      </form>
    </div>

    <style>
      .sixtythree-booking-admin .sixtythree-admin-card{background:#fff;border:1px solid #dcdcde;border-radius:16px;padding:24px;margin:18px 0;box-shadow:0 10px 30px rgba(0,0,0,.045)}
      .sixtythree-booking-admin .sixtythree-admin-card h2{margin-top:0}
      .sixtythree-settings-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px}
      .sixtythree-setting-field span,.sixtythree-booking-card label span{display:block;font-weight:600;margin:0 0 6px;color:#1d2327}
      .sixtythree-setting-field input,.sixtythree-booking-card input,.sixtythree-booking-card select{width:100%;max-width:100%;min-height:38px}
      .sixtythree-booking-builder-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:18px}
      .sixtythree-booking-builder-head p{margin:.25rem 0 0;max-width:760px;color:#646970}
      .sixtythree-booking-actions-top{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}
      .sixtythree-booking-card-list{display:grid;gap:12px}
      .sixtythree-booking-card{display:grid;grid-template-columns:34px minmax(90px,.7fr) minmax(140px,1fr) minmax(150px,.8fr) auto;align-items:end;gap:12px;padding:14px;border:1px solid #dcdcde;border-left:5px solid #2f8f46;border-radius:14px;background:#fbfbfc}
      .sixtythree-booking-card[data-status="booked"]{border-left-color:#b32d2e;background:#fff8f8}
      .sixtythree-booking-card[data-status="selected"]{border-left-color:#c99627;background:#fffaf0}
      .sixtythree-booking-card-handle{height:38px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:#f0f0f1;color:#646970;cursor:grab}
      .sixtythree-booking-card-buttons{display:flex;gap:6px;align-items:center;justify-content:flex-end;flex-wrap:wrap}.sixtythree-remove-booking-row{color:#b32d2e!important;border-color:#d63638!important}
      @media(max-width:1000px){.sixtythree-booking-card{grid-template-columns:1fr 1fr}.sixtythree-booking-card-handle{display:none}.sixtythree-booking-card-buttons{grid-column:1/-1;justify-content:flex-start}}
      @media(max-width:782px){.sixtythree-booking-builder-head{display:block}.sixtythree-booking-actions-top{justify-content:flex-start;margin-top:12px}.sixtythree-booking-card{grid-template-columns:1fr}}
    </style>
    <script>
      (function(){
        const hidden = document.getElementById('booking_days_data');
        const rows = document.getElementById('sixtythree-booking-rows');
        const addBtn = document.getElementById('sixtythree-add-booking-row');
        const fillWeekdays = document.getElementById('sixtythree-fill-weekdays');
        const tpl = document.getElementById('sixtythree-booking-row-template');
        const form = document.getElementById('sixtythree-booking-settings-form');
        if (!hidden || !rows) return;
        function syncRows(){
          const lines = [];
          rows.querySelectorAll('.sixtythree-booking-row').forEach(function(row){
            const day = (row.querySelector('.sixtythree-booking-day')?.value || '').trim();
            const label = (row.querySelector('.sixtythree-booking-label')?.value || '').trim();
            const status = (row.querySelector('.sixtythree-booking-status')?.value || 'available').trim();
            row.dataset.status = status;
            if (day || label) lines.push([day,label,status].join('|'));
          });
          hidden.value = lines.join('
');
        }
        function addRow(values){
          const fragment = tpl.content.cloneNode(true);
          const row = fragment.querySelector('.sixtythree-booking-row');
          if (values) {
            row.querySelector('.sixtythree-booking-day').value = values[0] || '';
            row.querySelector('.sixtythree-booking-label').value = values[1] || '';
            row.querySelector('.sixtythree-booking-status').value = values[2] || 'available';
            row.dataset.status = values[2] || 'available';
          }
          rows.appendChild(fragment);
          syncRows();
          return rows.lastElementChild;
        }
        function clearRows(){ rows.innerHTML = ''; }
        rows.addEventListener('input', syncRows);
        rows.addEventListener('change', syncRows);
        rows.addEventListener('click', function(e){
          const row = e.target.closest('.sixtythree-booking-row');
          if (!row) return;
          if (e.target.closest('.sixtythree-remove-booking-row')) { row.remove(); if (!rows.children.length) addRow(); syncRows(); return; }
          if (e.target.closest('.sixtythree-duplicate-booking-row')) { addRow([row.querySelector('.sixtythree-booking-day').value,row.querySelector('.sixtythree-booking-label').value,row.querySelector('.sixtythree-booking-status').value]); return; }
          if (e.target.closest('.sixtythree-move-up') && row.previousElementSibling) { rows.insertBefore(row,row.previousElementSibling); syncRows(); return; }
          if (e.target.closest('.sixtythree-move-down') && row.nextElementSibling) { rows.insertBefore(row.nextElementSibling,row); syncRows(); return; }
        });
        if (addBtn) addBtn.addEventListener('click', function(){ const row = addRow(); row.querySelector('.sixtythree-booking-day').focus(); });
        if (fillWeekdays) fillWeekdays.addEventListener('click', function(){ clearRows(); ['13|16:00+|available','14|18:00+|available','15|aizņemts|booked','16|brīvs|selected','17|aizņemts|booked','18|diena|available','19|16:00+|available'].forEach(function(line){ addRow(line.split('|')); }); syncRows(); });
        if (form) form.addEventListener('submit', syncRows);
        syncRows();
      })();
    </script>
    <?php
}



/**
 * Editable homepage defaults and media helpers.
 * These defaults keep the designed homepage intact while allowing the owner
 * to edit text and replace images from Appearance > Customize > 63.lv Homepage.
 */
function sixtythree_homepage_defaults() {
    return array(
        'pirts_kicker' => 'Bauskas 63 · pirts zona',
        'pirts_heading' => 'Vieta ģimenei, draugiem un svinībām',
        'pirts_intro' => 'Pirts zonas piedāvājums ģimenei, draugiem un nelielām svinībām ar cenu, ilgumu, iekļautajiem pakalpojumiem, kalendāru un ātru pieteikšanos.',
        'pirts_badge' => 'Standarta piedāvājums',
        'pirts_price_title' => '€90 / akcijā €84',
        'pirts_price_body' => 'Cena par standarta pirts ballītes piedāvājumu.',
        'pirts_hours_title' => '6 stundas',
        'pirts_hours_body' => 'Optimāls ilgums atpūtai un svinībām.',
        'pirts_people_title' => 'Līdz 15 personām',
        'pirts_people_body' => 'Ieteicamais viesu skaits ģimenēm un draugiem.',
        'pirts_notice_title' => '2 dienas iepriekš',
        'pirts_notice_body' => 'Pieteikšanās savlaicīgi, drošības nauda 50%.',
        'pirts_available_title' => 'Pieejams',
        'pirts_available_body' => 'Darba dienās no 16:00, brīvdienās pēc vienošanās.',
        'pirts_contact_title' => 'Saziņai',
        'pirts_package_heading' => 'Kas iekļauts pirts ballītes paketē',
        'pirts_package_1' => 'Sauna un slotiņas.',
        'pirts_package_2' => 'Zāle ar galdiem kopīgai atpūtai.',
        'pirts_package_3' => 'Atpūtas telpas mierīgai pauzei.',
        'pirts_package_4' => 'Virtuve uzkodu sagatavošanai.',
        'pirts_package_5' => 'Baseins šobrīd restaurācijas procesā.',
        'pirts_package_6' => 'Bauskas iela 63, Rīga.',
        'pirts_cta_primary' => 'Pieteikt pirts laiku →',
        'pirts_cta_secondary' => 'Visi pakalpojumi',
        'pirts_blog_kicker' => 'Pirts blogs',
        'pirts_blog_heading' => 'Jaunākie raksti',
        'pirts_blog_button' => 'Blogs →',
        'pirts_blog_modal_close' => 'Aizvērt',
        'pirts_blog_1_date' => 'Jaunumi',
        'pirts_blog_1_title' => 'Kā sagatavoties pirts vakaram?',
        'pirts_blog_1_text' => 'Īss ceļvedis, ko paņemt līdzi un kā plānot sešu stundu atpūtu.',
        'pirts_blog_1_link' => 'Lasīt vairāk →',
        'pirts_blog_1_url' => '#',
        'pirts_blog_1_full' => 'Pilnais raksta saturs par sagatavošanos pirts vakaram. Aprakstiet, ko paņemt līdzi, kā plānot vakaru un kādi ir saimnieku ieteikumi ērtai atpūtai.',
        'pirts_blog_2_date' => 'Padomi',
        'pirts_blog_2_title' => 'Pirts ballīte līdz 15 personām',
        'pirts_blog_2_text' => 'Ieteikumi nelielām svinībām, galdu izvietojumam un atpūtas ritmam.',
        'pirts_blog_2_link' => 'Lasīt vairāk →',
        'pirts_blog_2_url' => '#',
        'pirts_blog_2_full' => 'Pilnais raksta saturs par pirts ballīti līdz 15 personām. Šeit var ievadīt detalizētākus padomus par sēdvietām, uzkodām, plānu un pasākuma gaitu.',
        'pirts_blog_3_date' => 'Idejas',
        'pirts_blog_3_title' => 'Ko iekļaut pasākuma plānā?',
        'pirts_blog_3_text' => 'Sauna, slotiņas, zāle ar galdiem, virtuve un atpūtas telpas vienā vakarā.',
        'pirts_blog_3_link' => 'Lasīt vairāk →',
        'pirts_blog_3_url' => '#',
        'pirts_blog_3_full' => 'Pilnais raksta saturs ar idejām pasākuma plānam. Aprakstiet vakara programmu, saunas apmeklējumu, atpūtas pauzes un citus noderīgus ieteikumus.',
        'contact_form_shortcode' => '',
        'show_page_builder_content' => '0',
    );
}

function sixtythree_homepage_image_defaults() {
    return array(
        'pirts_gallery_image_1' => 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg',
        'pirts_gallery_image_2' => 'assets/images/63lv/pirts-zone-clean-02.jpg',
        'pirts_gallery_image_3' => 'assets/images/63lv/pirts-zone-clean-03.jpg',
        'pirts_gallery_image_4' => 'assets/images/63lv/pirts-zone-clean-04.jpg',
        'pirts_gallery_image_5' => 'assets/images/63lv/pirts-zone-clean-05.jpg',
        'pirts_gallery_image_6' => 'assets/images/63lv/pirts-zone-clean-06.jpg',
        'pirts_blog_image_1' => 'assets/images/63lv/pirts-zone-clean-04.jpg',
        'pirts_blog_image_2' => 'assets/images/63lv/pirts-zone-clean-05.jpg',
        'pirts_blog_image_3' => 'assets/images/63lv/pirts-zone-clean-06.jpg',
    );
}

function sixtythree_homepage_text($key, $fallback = '') {
    $defaults = sixtythree_homepage_defaults();
    $default = array_key_exists($key, $defaults) ? $defaults[$key] : $fallback;
    return get_theme_mod('sixtythree_' . $key, $default);
}

function sixtythree_homepage_media_url($key, $fallback_rel = '') {
    $defaults = sixtythree_homepage_image_defaults();
    $fallback_rel = $fallback_rel !== '' ? $fallback_rel : ($defaults[$key] ?? '');
    $mod = get_theme_mod('sixtythree_' . $key, 0);
    if (is_numeric($mod) && (int) $mod > 0) {
        $url = wp_get_attachment_image_url((int) $mod, 'full');
        if ($url) { return $url; }
    }
    if (is_string($mod) && preg_match('#^https?://#i', $mod)) { return $mod; }
    return get_template_directory_uri() . '/' . ltrim($fallback_rel, '/');
}

function sixtythree_import_theme_image_to_media($relative_path) {
    $relative_path = ltrim($relative_path, '/');
    $source = get_template_directory() . '/' . $relative_path;
    if (!file_exists($source)) { return 0; }

    $existing = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'meta_key' => '_sixtythree_theme_asset',
        'meta_value' => $relative_path,
        'fields' => 'ids',
    ));
    if (!empty($existing)) { return (int) $existing[0]; }

    $upload_dir = wp_upload_dir();
    if (!empty($upload_dir['error'])) { return 0; }
    wp_mkdir_p($upload_dir['path']);

    $filename = wp_unique_filename($upload_dir['path'], basename($source));
    $dest = trailingslashit($upload_dir['path']) . $filename;
    if (!copy($source, $dest)) { return 0; }

    $filetype = wp_check_filetype($filename, null);
    $attachment_id = wp_insert_attachment(array(
        'guid' => trailingslashit($upload_dir['url']) . $filename,
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
        'post_content' => '',
        'post_status' => 'inherit',
    ), $dest);

    if (is_wp_error($attachment_id) || !$attachment_id) { return 0; }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $metadata = wp_generate_attachment_metadata($attachment_id, $dest);
    wp_update_attachment_metadata($attachment_id, $metadata);
    update_post_meta($attachment_id, '_sixtythree_theme_asset', $relative_path);
    return (int) $attachment_id;
}

function sixtythree_import_editable_media_defaults() {
    $image_defaults = sixtythree_homepage_image_defaults();
    foreach ($image_defaults as $key => $relative_path) {
        $theme_mod_key = 'sixtythree_' . $key;
        if ((int) get_theme_mod($theme_mod_key, 0) > 0) { continue; }
        $attachment_id = sixtythree_import_theme_image_to_media($relative_path);
        if ($attachment_id) { set_theme_mod($theme_mod_key, $attachment_id); }
    }
}
add_action('after_switch_theme', 'sixtythree_import_editable_media_defaults');

function sixtythree_register_homepage_customizer($wp_customize) {
    $wp_customize->add_panel('sixtythree_homepage_panel', array(
        'title' => __('63.lv Homepage', 'sixty-three-lv'),
        'priority' => 30,
    ));

    $sections = array(
        'sixtythree_pirts_content' => __('Pirts zone content', 'sixty-three-lv'),
        'sixtythree_pirts_gallery' => __('Pirts zone gallery', 'sixty-three-lv'),
        'sixtythree_pirts_blog' => __('Pirts blog cards', 'sixty-three-lv'),
        'sixtythree_pirts_booking' => __('Pirts booking calendar', 'sixty-three-lv'),
    );
    foreach ($sections as $section_id => $title) {
        $wp_customize->add_section($section_id, array(
            'title' => $title,
            'panel' => 'sixtythree_homepage_panel',
        ));
    }

    $content_keys = array(
        'pirts_kicker','pirts_heading','pirts_intro','pirts_badge','pirts_price_title','pirts_price_body',
        'pirts_hours_title','pirts_hours_body','pirts_people_title','pirts_people_body','pirts_notice_title','pirts_notice_body',
        'pirts_available_title','pirts_available_body','pirts_contact_title','pirts_package_heading','pirts_package_1',
        'pirts_package_2','pirts_package_3','pirts_package_4','pirts_package_5','pirts_package_6','pirts_cta_primary','pirts_cta_secondary',
    );
    $defaults = sixtythree_homepage_defaults();
    foreach ($content_keys as $index => $key) {
        $wp_customize->add_setting('sixtythree_' . $key, array(
            'default' => $defaults[$key] ?? '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('sixtythree_' . $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'sixtythree_pirts_content',
            'type' => (strpos($key, '_body') !== false || strpos($key, '_intro') !== false || strpos($key, '_text') !== false) ? 'textarea' : 'text',
            'priority' => 10 + $index,
        ));
    }

    $image_defaults = sixtythree_homepage_image_defaults();
    foreach ($image_defaults as $key => $relative_path) {
        $section = strpos($key, 'blog') !== false ? 'sixtythree_pirts_blog' : 'sixtythree_pirts_gallery';
        $wp_customize->add_setting('sixtythree_' . $key, array(
            'default' => 0,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'sixtythree_' . $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => $section,
            'mime_type' => 'image',
        )));
    }

    $blog_text_keys = array(
        'pirts_blog_kicker','pirts_blog_heading','pirts_blog_button',
        'pirts_blog_modal_close',
        'pirts_blog_1_date','pirts_blog_1_title','pirts_blog_1_text','pirts_blog_1_link','pirts_blog_1_url','pirts_blog_1_full',
        'pirts_blog_2_date','pirts_blog_2_title','pirts_blog_2_text','pirts_blog_2_link','pirts_blog_2_url','pirts_blog_2_full',
        'pirts_blog_3_date','pirts_blog_3_title','pirts_blog_3_text','pirts_blog_3_link','pirts_blog_3_url','pirts_blog_3_full',
    );
    foreach ($blog_text_keys as $index => $key) {
        $wp_customize->add_setting('sixtythree_' . $key, array(
            'default' => $defaults[$key] ?? '',
            'sanitize_callback' => strpos($key, '_url') !== false ? 'esc_url_raw' : 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('sixtythree_' . $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'sixtythree_pirts_blog',
            'type' => strpos($key, '_url') !== false ? 'url' : ((strpos($key, '_text') !== false || strpos($key, '_full') !== false) ? 'textarea' : 'text'),
            'priority' => 50 + $index,
        ));
    }



    $booking_keys = array(
        'booking_kicker','booking_heading','booking_button','booking_call_button',
        'booking_legend_available','booking_legend_booked','booking_legend_selected','booking_days_data',
    );
    $booking_defaults = sixtythree_booking_defaults();
    foreach ($booking_keys as $index => $key) {
        $wp_customize->add_setting('sixtythree_' . $key, array(
            'default' => $booking_defaults[$key] ?? '',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('sixtythree_' . $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'description' => $key === 'booking_days_data' ? __('One booking day per line: day|label|status. Example: 12|aizņemts|booked', 'sixty-three-lv') : '',
            'section' => 'sixtythree_pirts_booking',
            'type' => $key === 'booking_days_data' ? 'textarea' : 'text',
            'priority' => 80 + $index,
        ));
    }

    $wp_customize->add_section('sixtythree_admin_editing', array(
        'title' => __('Admin editing / form builder', 'sixty-three-lv'),
        'panel' => 'sixtythree_homepage_panel',
    ));
    $wp_customize->add_setting('sixtythree_show_page_builder_content', array(
        'default' => '0',
        'sanitize_callback' => function($value) { return $value ? '1' : '0'; },
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('sixtythree_show_page_builder_content', array(
        'label' => __('Show optional WordPress page/builder content section', 'sixty-three-lv'),
        'description' => __('Keeps the designed homepage intact and inserts the assigned page content as an extra styled section. Leave disabled to avoid plain HTML/builder content breaking the design.', 'sixty-three-lv'),
        'section' => 'sixtythree_admin_editing',
        'type' => 'checkbox',
    ));
    $wp_customize->add_setting('sixtythree_contact_form_shortcode', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('sixtythree_contact_form_shortcode', array(
        'label' => __('Builder/contact form shortcode', 'sixty-three-lv'),
        'description' => __('Paste Contact Form 7, Fluent Forms, WPForms, or builder shortcode here. If empty, the theme uses the WP BBuilder Dynamic Form block settings/fallback.', 'sixty-three-lv'),
        'section' => 'sixtythree_admin_editing',
        'type' => 'textarea',
    ));

}
add_action('customize_register', 'sixtythree_register_homepage_customizer');

function sixtythree_migrate_pirts_gallery_without_shower() {
    $replacement_paths = array(
        'sixtythree_pirts_gallery_image_1' => 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg',
        'sixtythree_pirts_gallery_image_2' => 'assets/images/63lv/pirts-zone-clean-02.jpg',
        'sixtythree_pirts_gallery_image_3' => 'assets/images/63lv/pirts-zone-clean-03.jpg',
        'sixtythree_pirts_gallery_image_4' => 'assets/images/63lv/pirts-zone-clean-04.jpg',
        'sixtythree_pirts_gallery_image_5' => 'assets/images/63lv/pirts-zone-clean-05.jpg',
        'sixtythree_pirts_gallery_image_6' => 'assets/images/63lv/pirts-zone-clean-06.jpg',
    );
    foreach ($replacement_paths as $mod_key => $path) {
        $current = (int) get_theme_mod($mod_key, 0);
        $asset = $current ? get_post_meta($current, '_sixtythree_theme_asset', true) : '';
        if (!$current || $asset === 'assets/images/63lv/pirts-zone-clean-01.jpg' || $asset === 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg' || $mod_key === 'sixtythree_pirts_gallery_image_1') {
            $new_id = sixtythree_import_theme_image_to_media($path);
            if ($new_id) { set_theme_mod($mod_key, $new_id); }
        }
    }
    remove_theme_mod('sixtythree_pirts_gallery_image_7');
}
add_action('after_switch_theme', 'sixtythree_migrate_pirts_gallery_without_shower', 20);



/**
 * v7 safety migration: keep the designed homepage visible by default.
 * v6 could replace the whole homepage with plain page content, which broke the live style.
 */
function sixtythree_disable_plain_homepage_takeover() {
    remove_theme_mod('sixtythree_use_page_content');
    if (get_theme_mod('sixtythree_show_page_builder_content', '') === '') {
        set_theme_mod('sixtythree_show_page_builder_content', '0');
    }
}
add_action('after_switch_theme', 'sixtythree_disable_plain_homepage_takeover', 30);


/**
 * v12 migration: replace first pirts gallery image with the provided PAGRABS logo
 * and make sure the booking calendar remains editable from theme settings.
 */
function sixtythree_upgrade_v12_assets() {
    $stored = get_option('sixtythree_theme_migration_version', '0');
    if (version_compare((string) $stored, '1.2.0', '>=')) {
        return;
    }

    $new_id = sixtythree_import_theme_image_to_media('assets/images/63lv/pirts-zone-clean-01-replacement.jpg');
    if ($new_id) {
        $current = (int) get_theme_mod('sixtythree_pirts_gallery_image_1', 0);
        $asset = $current ? (string) get_post_meta($current, '_sixtythree_theme_asset', true) : '';
        if (!$current || $asset === 'assets/images/63lv/pirts-zone-clean-01.jpg' || $asset === 'assets/images/63lv/pirts-zone-clean-01-replacement.jpg') {
            set_theme_mod('sixtythree_pirts_gallery_image_1', $new_id);
        }
    }

    update_option('sixtythree_theme_migration_version', '1.2.0');
}
add_action('after_switch_theme', 'sixtythree_upgrade_v12_assets', 40);
add_action('admin_init', 'sixtythree_upgrade_v12_assets');



/**
 * 63.lv Google Analytics admin connector.
 *
 * Notes:
 * - The existing BBuilder tracking code may use an old Universal Analytics ID (UA-...).
 *   That can still stay in the header for legacy tracking, but Google's Data API only
 *   reports GA4 properties, so the admin stats use a GA4 property ID such as 364777561.
 * - OAuth is optional and only starts after a real Google OAuth client ID/secret are saved.
 */
function sixtythree_analytics_defaults() {
    return array(
        'measurement_id' => '',
        'ua_tracking_id' => '',
        'property_id' => '364777561',
        'client_id' => '',
        'client_secret' => '',
        'access_token' => '',
        'refresh_token' => '',
        'token_expires' => 0,
        'last_error' => '',
    );
}

function sixtythree_detect_ga_ids() {
    $settings = get_option('wpbb_settings', array());
    $head = is_array($settings) ? (string)($settings['google_analytics_head'] ?? '') : '';
    $ids = array('measurement_id' => '', 'ua_tracking_id' => '');
    if (preg_match('/G-[A-Z0-9]+/i', $head, $m)) {
        $ids['measurement_id'] = strtoupper($m[0]);
    }
    if (preg_match('/UA-[0-9]+-[0-9]+/i', $head, $m)) {
        $ids['ua_tracking_id'] = strtoupper($m[0]);
    }
    return $ids;
}

function sixtythree_detect_ga_measurement_id() {
    $ids = sixtythree_detect_ga_ids();
    return $ids['measurement_id'];
}

function sixtythree_get_analytics_settings() {
    $settings = get_option('sixtythree_analytics_settings', array());
    $settings = is_array($settings) ? array_merge(sixtythree_analytics_defaults(), $settings) : sixtythree_analytics_defaults();
    $detected = sixtythree_detect_ga_ids();
    if ($settings['measurement_id'] === '' && $detected['measurement_id'] !== '') {
        $settings['measurement_id'] = $detected['measurement_id'];
    }
    if ($settings['ua_tracking_id'] === '' && $detected['ua_tracking_id'] !== '') {
        $settings['ua_tracking_id'] = $detected['ua_tracking_id'];
    }
    return $settings;
}

function sixtythree_analytics_valid_client_id($client_id) {
    $client_id = trim((string)$client_id);
    return $client_id !== '' && preg_match('/^[0-9]+-[a-z0-9_\-]+\.apps\.googleusercontent\.com$/i', $client_id);
}

function sixtythree_sanitize_analytics_settings($input) {
    $input = is_array($input) ? $input : array();
    $old = sixtythree_get_analytics_settings();
    return array(
        'measurement_id' => sanitize_text_field(wp_unslash($input['measurement_id'] ?? $old['measurement_id'])),
        'ua_tracking_id' => sanitize_text_field(wp_unslash($input['ua_tracking_id'] ?? $old['ua_tracking_id'])),
        'property_id' => preg_replace('/[^0-9]/', '', (string)($input['property_id'] ?? $old['property_id'])),
        'client_id' => sanitize_text_field(wp_unslash($input['client_id'] ?? $old['client_id'])),
        'client_secret' => sanitize_text_field(wp_unslash($input['client_secret'] ?? $old['client_secret'])),
        'access_token' => (string)($old['access_token'] ?? ''),
        'refresh_token' => (string)($old['refresh_token'] ?? ''),
        'token_expires' => (int)($old['token_expires'] ?? 0),
        'last_error' => '',
    );
}

function sixtythree_analytics_admin_menu() {
    add_theme_page(__('63.lv Analytics', 'sixty-three-lv'), __('63.lv Analytics', 'sixty-three-lv'), 'manage_options', 'sixtythree-analytics', 'sixtythree_analytics_settings_page');
}
add_action('admin_menu', 'sixtythree_analytics_admin_menu');

function sixtythree_register_analytics_settings() {
    register_setting('sixtythree_analytics_settings_group', 'sixtythree_analytics_settings', 'sixtythree_sanitize_analytics_settings');
}
add_action('admin_init', 'sixtythree_register_analytics_settings');

function sixtythree_analytics_redirect_uri() {
    return admin_url('admin.php?page=sixtythree-analytics&sixtythree_ga_callback=1');
}

function sixtythree_analytics_refresh_access_token($settings) {
    if (empty($settings['refresh_token']) || empty($settings['client_id']) || empty($settings['client_secret'])) { return $settings; }
    if (!sixtythree_analytics_valid_client_id($settings['client_id'])) { return $settings; }
    if (!empty($settings['access_token']) && (int)$settings['token_expires'] > time() + 90) { return $settings; }
    $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
        'timeout' => 20,
        'body' => array(
            'client_id' => $settings['client_id'],
            'client_secret' => $settings['client_secret'],
            'refresh_token' => $settings['refresh_token'],
            'grant_type' => 'refresh_token',
        ),
    ));
    if (is_wp_error($response)) { return $settings; }
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($body['access_token'])) {
        $settings['access_token'] = sanitize_text_field($body['access_token']);
        $settings['token_expires'] = time() + max(300, (int)($body['expires_in'] ?? 3600));
        $settings['last_error'] = '';
        update_option('sixtythree_analytics_settings', $settings);
    } elseif (!empty($body['error'])) {
        $settings['last_error'] = sanitize_text_field(($body['error'] ?? '') . ': ' . ($body['error_description'] ?? ''));
        update_option('sixtythree_analytics_settings', $settings);
    }
    return $settings;
}

function sixtythree_analytics_admin_actions() {
    if (!current_user_can('manage_options')) { return; }
    if (isset($_GET['page']) && $_GET['page'] === 'sixtythree-analytics' && isset($_GET['sixtythree_ga_disconnect'])) {
        check_admin_referer('sixtythree_ga_disconnect');
        $settings = sixtythree_get_analytics_settings();
        $settings['access_token'] = '';
        $settings['refresh_token'] = '';
        $settings['token_expires'] = 0;
        $settings['last_error'] = '';
        update_option('sixtythree_analytics_settings', $settings);
        wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'disconnected', admin_url('admin.php?page=sixtythree-analytics'))); exit;
    }
    if (isset($_GET['page']) && $_GET['page'] === 'sixtythree-analytics' && isset($_GET['sixtythree_ga_connect'])) {
        check_admin_referer('sixtythree_ga_connect');
        $settings = sixtythree_get_analytics_settings();
        if (empty($settings['client_id']) || empty($settings['client_secret'])) {
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'missing_credentials', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        if (!sixtythree_analytics_valid_client_id($settings['client_id'])) {
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'invalid_client_id', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        $state = wp_create_nonce('sixtythree_ga_oauth');
        set_transient('sixtythree_ga_oauth_state_' . get_current_user_id(), $state, 10 * MINUTE_IN_SECONDS);
        $args = array(
            'client_id' => $settings['client_id'],
            'redirect_uri' => sixtythree_analytics_redirect_uri(),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        );
        wp_redirect('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($args)); exit;
    }
    if (isset($_GET['page']) && $_GET['page'] === 'sixtythree-analytics' && isset($_GET['sixtythree_ga_callback'])) {
        $state = sanitize_text_field(wp_unslash($_GET['state'] ?? ''));
        $expected = get_transient('sixtythree_ga_oauth_state_' . get_current_user_id());
        if (!$state || !$expected || !hash_equals($expected, $state)) {
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'bad_state', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        delete_transient('sixtythree_ga_oauth_state_' . get_current_user_id());
        $code = sanitize_text_field(wp_unslash($_GET['code'] ?? ''));
        $settings = sixtythree_get_analytics_settings();
        if (!$code || empty($settings['client_id']) || empty($settings['client_secret']) || !sixtythree_analytics_valid_client_id($settings['client_id'])) {
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'missing_code', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'timeout' => 20,
            'body' => array(
                'code' => $code,
                'client_id' => $settings['client_id'],
                'client_secret' => $settings['client_secret'],
                'redirect_uri' => sixtythree_analytics_redirect_uri(),
                'grant_type' => 'authorization_code',
            ),
        ));
        if (is_wp_error($response)) {
            $settings['last_error'] = $response->get_error_message();
            update_option('sixtythree_analytics_settings', $settings);
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'token_error', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($body['access_token'])) {
            $settings['access_token'] = sanitize_text_field($body['access_token']);
            if (!empty($body['refresh_token'])) { $settings['refresh_token'] = sanitize_text_field($body['refresh_token']); }
            $settings['token_expires'] = time() + max(300, (int)($body['expires_in'] ?? 3600));
            $settings['last_error'] = '';
            update_option('sixtythree_analytics_settings', $settings);
            wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'connected', admin_url('admin.php?page=sixtythree-analytics'))); exit;
        }
        $settings['last_error'] = sanitize_text_field(($body['error'] ?? 'token_error') . ': ' . ($body['error_description'] ?? ''));
        update_option('sixtythree_analytics_settings', $settings);
        wp_safe_redirect(add_query_arg('sixtythree_ga_status', 'token_error', admin_url('admin.php?page=sixtythree-analytics'))); exit;
    }
}
add_action('admin_init', 'sixtythree_analytics_admin_actions');

function sixtythree_analytics_get_account_summaries($settings) {
    $settings = sixtythree_analytics_refresh_access_token($settings);
    if (empty($settings['access_token'])) { return array(); }
    $response = wp_remote_get('https://analyticsadmin.googleapis.com/v1beta/accountSummaries', array(
        'timeout' => 20,
        'headers' => array('Authorization' => 'Bearer ' . $settings['access_token']),
    ));
    if (is_wp_error($response)) { return array(); }
    $body = json_decode(wp_remote_retrieve_body($response), true);
    $items = array();
    foreach (($body['accountSummaries'] ?? array()) as $account) {
        foreach (($account['propertySummaries'] ?? array()) as $property) {
            $property_id = preg_replace('/[^0-9]/', '', (string)($property['property'] ?? ''));
            if ($property_id) {
                $items[$property_id] = trim(($account['displayName'] ?? 'Account') . ' — ' . ($property['displayName'] ?? $property_id));
            }
        }
    }
    return $items;
}

function sixtythree_analytics_run_report($settings) {
    $settings = sixtythree_analytics_refresh_access_token($settings);
    if (empty($settings['access_token']) || empty($settings['property_id'])) { return array(); }
    $endpoint = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode($settings['property_id']) . ':runReport';
    $payload = array(
        'dateRanges' => array(array('startDate' => '7daysAgo', 'endDate' => 'today')),
        'metrics' => array(
            array('name' => 'activeUsers'), array('name' => 'sessions'), array('name' => 'screenPageViews'), array('name' => 'eventCount')
        ),
    );
    $response = wp_remote_post($endpoint, array(
        'timeout' => 25,
        'headers' => array('Authorization' => 'Bearer ' . $settings['access_token'], 'Content-Type' => 'application/json'),
        'body' => wp_json_encode($payload),
    ));
    if (is_wp_error($response)) { return array('error' => $response->get_error_message()); }
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($body['error']['message'])) { return array('error' => sanitize_text_field($body['error']['message'])); }
    $values = array();
    $metric_headers = $body['metricHeaders'] ?? array();
    $metric_values = $body['rows'][0]['metricValues'] ?? array();
    foreach ($metric_headers as $i => $header) {
        $values[$header['name']] = $metric_values[$i]['value'] ?? '0';
    }
    return $values;
}

function sixtythree_analytics_status_message($status) {
    $messages = array(
        'missing_credentials' => __('Add a real Google OAuth Client ID and Client Secret before connecting.', 'sixty-three-lv'),
        'invalid_client_id' => __('The saved OAuth Client ID is not a valid Google Web application client ID. This prevents the Google “invalid_client” screen.', 'sixty-three-lv'),
        'bad_state' => __('Google connection security check failed. Please try again.', 'sixty-three-lv'),
        'missing_code' => __('Google did not return an authorization code. Please try again.', 'sixty-three-lv'),
        'token_error' => __('Google returned a token error. Check the OAuth client credentials and redirect URI.', 'sixty-three-lv'),
        'connected' => __('Google Analytics connected successfully.', 'sixty-three-lv'),
        'disconnected' => __('Google Analytics connection removed.', 'sixty-three-lv'),
    );
    return $messages[$status] ?? sanitize_text_field($status);
}

function sixtythree_analytics_settings_page() {
    if (!current_user_can('manage_options')) { return; }
    $settings = sixtythree_get_analytics_settings();
    $properties = sixtythree_analytics_get_account_summaries($settings);
    $stats = sixtythree_analytics_run_report($settings);
    $connect_url = wp_nonce_url(admin_url('admin.php?page=sixtythree-analytics&sixtythree_ga_connect=1'), 'sixtythree_ga_connect');
    $disconnect_url = wp_nonce_url(admin_url('admin.php?page=sixtythree-analytics&sixtythree_ga_disconnect=1'), 'sixtythree_ga_disconnect');
    $has_real_oauth = sixtythree_analytics_valid_client_id($settings['client_id']) && !empty($settings['client_secret']);
    $tracking_id = $settings['measurement_id'] ?: $settings['ua_tracking_id'];
    ?>
    <div class="wrap sixtythree-analytics-admin">
      <h1><?php esc_html_e('63.lv Google Analytics', 'sixty-three-lv'); ?></h1>
      <p><?php esc_html_e('The site tracking code can stay in WP BBuilder. This page only needs Google OAuth if you want to show GA4 statistics inside WordPress.', 'sixty-three-lv'); ?></p>
      <?php if (!empty($_GET['sixtythree_ga_status'])) : ?><div class="notice notice-info"><p><?php echo esc_html(sixtythree_analytics_status_message(sanitize_text_field(wp_unslash($_GET['sixtythree_ga_status'])))); ?></p></div><?php endif; ?>
      <?php if (!empty($settings['last_error'])) : ?><div class="notice notice-warning"><p><strong><?php esc_html_e('Last Google error:', 'sixty-three-lv'); ?></strong> <?php echo esc_html($settings['last_error']); ?></p></div><?php endif; ?>
      <form method="post" action="options.php">
        <?php settings_fields('sixtythree_analytics_settings_group'); ?>
        <div class="sixtythree-admin-card">
          <h2><?php esc_html_e('Tracking and GA4 property', 'sixty-three-lv'); ?></h2>
          <div class="sixtythree-settings-grid">
            <label><span><?php esc_html_e('GA4 Measurement ID', 'sixty-three-lv'); ?></span><input type="text" name="sixtythree_analytics_settings[measurement_id]" value="<?php echo esc_attr($settings['measurement_id']); ?>" placeholder="G-XXXXXXXXXX"><small><?php esc_html_e('Optional. Auto-detected from WP BBuilder when the code uses a GA4 G- ID.', 'sixty-three-lv'); ?></small></label>
            <label><span><?php esc_html_e('Universal Analytics ID', 'sixty-three-lv'); ?></span><input type="text" name="sixtythree_analytics_settings[ua_tracking_id]" value="<?php echo esc_attr($settings['ua_tracking_id']); ?>" placeholder="UA-53400803-1"><small><?php esc_html_e('Your current BBuilder code uses UA-53400803-1. It is tracking code only; GA4 reports use the Property ID below.', 'sixty-three-lv'); ?></small></label>
            <label><span><?php esc_html_e('GA4 Property ID', 'sixty-three-lv'); ?></span><input type="text" name="sixtythree_analytics_settings[property_id]" value="<?php echo esc_attr($settings['property_id']); ?>" placeholder="364777561"><small><?php esc_html_e('For this site use 364777561 if that is the GA4 property in Google Analytics.', 'sixty-three-lv'); ?></small></label>
            <label><span><?php esc_html_e('Detected tracking ID', 'sixty-three-lv'); ?></span><input type="text" value="<?php echo esc_attr($tracking_id); ?>" readonly><small><?php esc_html_e('Read from WP BBuilder GA header code or saved manually above.', 'sixty-three-lv'); ?></small></label>
          </div>
        </div>
        <div class="sixtythree-admin-card">
          <h2><?php esc_html_e('Optional WordPress dashboard statistics', 'sixty-three-lv'); ?></h2>
          <p><?php esc_html_e('Do not paste the UA tracking ID here. Google login requires an OAuth Client created in Google Cloud Console as a “Web application”. Add the Redirect URI below to that OAuth client.', 'sixty-three-lv'); ?></p>
          <div class="sixtythree-settings-grid">
            <label><span><?php esc_html_e('Google OAuth Client ID', 'sixty-three-lv'); ?></span><input type="text" name="sixtythree_analytics_settings[client_id]" value="<?php echo esc_attr($settings['client_id']); ?>" placeholder="000000000000-xxxxxxxxxxxxxxxx.apps.googleusercontent.com"></label>
            <label><span><?php esc_html_e('Google OAuth Client Secret', 'sixty-three-lv'); ?></span><input type="password" name="sixtythree_analytics_settings[client_secret]" value="<?php echo esc_attr($settings['client_secret']); ?>"></label>
          </div>
          <p><strong><?php esc_html_e('Authorized redirect URI:', 'sixty-three-lv'); ?></strong> <code><?php echo esc_html(sixtythree_analytics_redirect_uri()); ?></code></p>
          <?php if ($properties) : ?>
            <label class="sixtythree-property-select"><span><?php esc_html_e('Select property found in your Google account', 'sixty-three-lv'); ?></span><select onchange="document.querySelector('[name=&quot;sixtythree_analytics_settings[property_id]&quot;]').value=this.value;">
              <option value=""><?php esc_html_e('Choose property…', 'sixty-three-lv'); ?></option>
              <?php foreach ($properties as $property_id => $label) : ?><option value="<?php echo esc_attr($property_id); ?>" <?php selected($settings['property_id'], $property_id); ?>><?php echo esc_html($label . ' (' . $property_id . ')'); ?></option><?php endforeach; ?>
            </select></label>
          <?php endif; ?>
          <?php submit_button(__('Save Analytics settings', 'sixty-three-lv'), 'primary', 'submit', false); ?>
          <?php if ($has_real_oauth) : ?>
            <a class="button button-secondary" href="<?php echo esc_url($connect_url); ?>"><?php esc_html_e('Connect with Google Analytics', 'sixty-three-lv'); ?></a>
          <?php else : ?>
            <button class="button button-secondary" type="button" disabled><?php esc_html_e('Add valid OAuth credentials to connect', 'sixty-three-lv'); ?></button>
          <?php endif; ?>
          <?php if (!empty($settings['refresh_token']) || !empty($settings['access_token'])) : ?>
            <a class="button" href="<?php echo esc_url($disconnect_url); ?>"><?php esc_html_e('Disconnect', 'sixty-three-lv'); ?></a>
          <?php endif; ?>
        </div>
      </form>
      <div class="sixtythree-admin-card">
        <h2><?php esc_html_e('Last 7 days', 'sixty-three-lv'); ?></h2>
        <?php if ($settings['ua_tracking_id'] && !$settings['measurement_id']) : ?>
          <p class="sixtythree-note"><?php esc_html_e('Current header code is Universal Analytics (UA). UA can remain for legacy tracking, but dashboard stats require a connected GA4 property ID.', 'sixty-three-lv'); ?></p>
        <?php endif; ?>
        <?php if (!empty($stats['error'])) : ?><p class="notice notice-error inline"><strong><?php echo esc_html($stats['error']); ?></strong></p><?php elseif (!empty($stats)) : ?>
          <div class="sixtythree-analytics-stats">
            <div><span><?php esc_html_e('Active users', 'sixty-three-lv'); ?></span><b><?php echo esc_html($stats['activeUsers'] ?? '0'); ?></b></div>
            <div><span><?php esc_html_e('Sessions', 'sixty-three-lv'); ?></span><b><?php echo esc_html($stats['sessions'] ?? '0'); ?></b></div>
            <div><span><?php esc_html_e('Views', 'sixty-three-lv'); ?></span><b><?php echo esc_html($stats['screenPageViews'] ?? '0'); ?></b></div>
            <div><span><?php esc_html_e('Events', 'sixty-three-lv'); ?></span><b><?php echo esc_html($stats['eventCount'] ?? '0'); ?></b></div>
          </div>
        <?php else : ?><p><?php esc_html_e('Tracking code and property ID are saved. Add valid Google OAuth credentials only if you want live GA4 statistics inside WordPress.', 'sixty-three-lv'); ?></p><?php endif; ?>
      </div>
    </div>
    <style>.sixtythree-analytics-admin .sixtythree-admin-card{background:#fff;border:1px solid #dcdcde;border-radius:16px;padding:24px;margin:18px 0;box-shadow:0 10px 30px rgba(0,0,0,.045)}.sixtythree-analytics-admin .sixtythree-settings-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}.sixtythree-analytics-admin label span{display:block;font-weight:600;margin-bottom:6px}.sixtythree-analytics-admin input,.sixtythree-analytics-admin select{width:100%;max-width:100%;min-height:38px}.sixtythree-analytics-admin small{display:block;color:#646970;margin-top:4px}.sixtythree-property-select{display:block;margin:18px 0}.sixtythree-analytics-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px}.sixtythree-analytics-stats div{background:#f7f7f8;border-radius:14px;padding:18px}.sixtythree-analytics-stats span{display:block;color:#646970}.sixtythree-analytics-stats b{font-size:30px}.sixtythree-note{background:#fff8e5;border-left:4px solid #c9a45d;padding:12px 14px}</style>
    <?php
}

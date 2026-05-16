<?php
if (!defined('ABSPATH')) { exit; }
get_header();

$title = sixtythree_i18n('Lapa nav atrasta', 'Page not found', 'Страница не найдена');
$lead = sixtythree_i18n(
    'Šī adrese neeksistē vai lapa ir pārvietota. Atgriezieties sākumlapā un izvēlieties vajadzīgo sadaļu.',
    'This address does not exist or the page has moved. Go back to the homepage and choose the section you need.',
    'Такой страницы нет или она была перемещена. Вернитесь на главную страницу и выберите нужный раздел.'
);
$home_label = sixtythree_i18n('Uz sākumlapu', 'Back to homepage', 'На главную');
$small = sixtythree_i18n('Kļūda 404', 'Error 404', 'Ошибка 404');
$home_url = sixtythree_language_home_url();
?>
<main class="section sixtythree-page-content sixtythree-error-page" aria-labelledby="sixtythree-error-title">
  <section class="error-hero-card">
    <div class="kicker"><?php echo esc_html($small); ?></div>
    <h1 id="sixtythree-error-title"><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($lead); ?></p>
    <a class="btn-primary error-home-link" href="<?php echo esc_url($home_url); ?>"><?php echo esc_html($home_label); ?> →</a>
  </section>
</main>
<?php get_footer(); ?>

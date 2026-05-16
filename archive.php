<?php
if (!defined('ABSPATH')) { exit; }
get_header();
$home_url = sixtythree_language_home_url();
?>
<main class="section sixtythree-page-content sixtythree-archive-page">
  <?php if (have_posts()) : ?>
    <div class="section-head">
      <div>
        <div class="kicker">63.lv</div>
        <h1><?php echo esc_html(get_the_archive_title()); ?></h1>
      </div>
    </div>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class('service-card'); ?>>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; the_posts_pagination(); ?>
  <?php else : ?>
    <section class="error-hero-card">
      <div class="kicker">63.lv</div>
      <h1><?php echo esc_html(sixtythree_i18n('Šeit vēl nav satura', 'No content here yet', 'Здесь пока нет материалов')); ?></h1>
      <p><?php echo esc_html(sixtythree_i18n('Šī arhīva vai kategorijas lapa netiek rādīta meklētājiem. Dodieties uz sākumlapu.', 'This archive or category page is hidden from search engines. Please go to the homepage.', 'Эта страница архива или категории скрыта от поисковых систем. Перейдите на главную страницу.')); ?></p>
      <a class="btn-primary error-home-link" href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(sixtythree_i18n('Uz sākumlapu', 'Back to homepage', 'На главную')); ?> →</a>
    </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>

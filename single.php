<?php
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main class="section sixtythree-page-content">
  <div class="section-head">
    <div>
      <div class="kicker">63.lv</div>
      <h2><?php echo esc_html(get_the_archive_title() ?: get_the_title()); ?></h2>
    </div>
  </div>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class('service-card'); ?>>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <div><?php is_singular() || is_page() ? the_content() : the_excerpt(); ?></div>
    </article>
  <?php endwhile; the_posts_pagination(); else : ?>
    <p><?php esc_html_e('Nekas netika atrasts.', 'sixty-three-lv'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>

<?php if (!defined('ABSPATH')) { exit; } ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('sixtythree-theme'); ?>>
<?php wp_body_open(); ?>
<?php if (!is_front_page()) : ?>
<header class="site-header">
  <div class="header-inner">
    <a class="brand brand-image" href="<?php echo esc_url(home_url('/')); ?>">
      <picture class="brand-logo-picture">
        <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services.webp'); ?>" type="image/webp">
        <img class="brand-logo-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/63lv/63lv-logo-services.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name') ?: '63.lv Services'); ?>">
      </picture>
    </a>
    <nav class="desktop-nav" aria-label="<?php esc_attr_e('Galvenā izvēlne', 'sixty-three-lv'); ?>">
      <?php
      if (has_nav_menu('primary')) {
          wp_nav_menu(array('theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s','fallback_cb'=>false));
      } else {
          echo '<a class="nav-pill" href="' . esc_url(home_url('/#pakalpojumi')) . '">Pakalpojumi</a>';
          echo '<a class="nav-pill" href="' . esc_url(home_url('/#pirts')) . '">Pirts zona</a>';
          echo '<a class="nav-pill" href="' . esc_url(home_url('/#web')) . '">Web izstrāde</a>';
          echo '<a class="nav-pill" href="' . esc_url(home_url('/#cta')) . '">Kontakti</a>';
      }
      ?>
    </nav>
    <div class="header-tools"><?php sixtythree_language_switcher("lang-switch"); ?></div>
  </div>
</header>
<?php endif; ?>

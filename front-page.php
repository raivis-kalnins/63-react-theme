<?php
/**
 * 63.lv headless React homepage template.
 *
 * WordPress now acts as the CMS/shell and React renders the public homepage
 * into #sixtythree-headless-root. The same markup remains as an immediate
 * server fallback so the live site never goes blank if a CDN/script is blocked.
 */
if (!defined('ABSPATH')) { exit; }

get_header();

ob_start();
require get_template_directory() . '/template-parts/headless-home-markup.php';
$sixtythree_home_markup = sixtythree_frontpage_output(ob_get_clean());
?>
<div
  id="sixtythree-headless-root"
  data-sixtythree-headless="react"
  data-react-home-root="true"
  data-seo-source="server-rendered"
  class="sixtythree-react-root"
>
  <div class="sixtythree-react-home-app" data-react-enhance="true">
    <?php echo $sixtythree_home_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
  </div>
</div>
<script type="application/json" id="sixtythree-react-home-state">{"mode":"seo-enhancement","serverRendered":true}</script>
<noscript>
  <div class="sixtythree-react-home-app">
    <?php echo $sixtythree_home_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
  </div>
</noscript>
<?php get_footer(); ?>

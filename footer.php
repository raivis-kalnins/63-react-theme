<?php if (!defined('ABSPATH')) { exit; } ?>
<?php if (!is_front_page()) : ?>
<footer>
  <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4.5 7 7.5 6 7.5-6"/></svg></span><div><b><?php echo esc_html(sixtythree_contact_email()); ?></b></div></div>
  <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 4.5 6.2 6.8c-.7.7-.8 1.8-.3 2.7 2 3.7 4.9 6.6 8.6 8.6.9.5 2 .4 2.7-.3l2.3-2.3-3.7-3.7-1.6 1.6c-1.8-.9-3.1-2.2-4-4l1.6-1.6-3.3-3.9Z"/></svg></span><div><b><?php echo esc_html(sixtythree_contact_phone()); ?></b></div></div>
  <div class="footer-item"><span class="footer-icon"><svg class="gold-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.15 7-12a7 7 0 0 0-14 0c0 5.85 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg></span><div><b>Bauskas 63, Rīga</b></div></div>
  <?php echo sixtythree_facebook_footer_item(); ?>
  <button class="sixtythree-scroll-top footer-scroll-top" type="button" aria-label="Uz augšu"><span aria-hidden="true">↑</span></button>
  <a class="btn" href="mailto:<?php echo esc_attr(sixtythree_contact_email()); ?>">Sazināties →</a>
</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body></html>

<?php
/**
 * Closing call-to-action band. Text comes from the Customizer options.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="cta-band" id="contact">
  <div class="container cta-band__inner">
    <h2><?php echo esc_html(rashid_option('cta_title')); ?></h2>
    <p><?php echo esc_html(rashid_option('cta_text')); ?></p>
    <p>
      <a class="btn" href="<?php echo esc_url(rashid_page_url('contact')); ?>">
        <?php echo esc_html(rashid_option('cta_label')); ?>
      </a>
    </p>
  </div>
</section>

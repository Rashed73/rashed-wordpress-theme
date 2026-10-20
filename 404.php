<?php
/**
 * "Not found" page.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <section class="section section--center">
    <div class="container prose">
      <p class="eyebrow">404</p>
      <h1><?php esc_html_e('This page does not exist', 'rashid'); ?></h1>
      <p><?php esc_html_e('The link may be old or mistyped.', 'rashid'); ?></p>
      <p>
        <a class="btn" href="<?php echo esc_url(rashid_home_url()); ?>">
          <?php esc_html_e('Back to the home page', 'rashid'); ?>
        </a>
      </p>
    </div>
  </section>
</main>
<?php
get_footer();

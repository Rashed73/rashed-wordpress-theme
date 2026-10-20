<?php
/**
 * Brand logo (or text mark and name when no logo is set), linked to the home page.
 *
 * Args: class (string) CSS classes for the link; defaults to "brand".
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$class = $args['class'] ?? 'brand';
$logo  = rashid_logo();
?>
<a class="<?php echo esc_attr($class . ($logo !== '' ? ' brand--logo' : '')); ?>" href="<?php echo esc_url(rashid_home_url()); ?>">
  <?php if ($logo !== '') : ?>
    <?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput -- built by wp_get_attachment_image(). ?>
  <?php else : ?>
    <span class="brand__mark" aria-hidden="true"><?php echo esc_html(rashid_option('brand_mark')); ?></span>
    <span class="brand__name"><?php echo esc_html(rashid_option('brand_name')); ?></span>
  <?php endif; ?>
</a>

<?php
/**
 * Dark title band at the top of inner pages.
 *
 * Args: title (string), lead (string, optional), eyebrow (string, optional).
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$title   = $args['title'] ?? '';
$lead    = $args['lead'] ?? '';
$eyebrow = $args['eyebrow'] ?? '';
?>
<section class="page-hero">
  <div class="container">
    <?php if ($eyebrow !== '') : ?>
      <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
    <?php endif; ?>
    <h1><?php echo esc_html($title); ?></h1>
    <?php if ($lead !== '') : ?>
      <p class="page-hero__lead"><?php echo esc_html($lead); ?></p>
    <?php endif; ?>
  </div>
</section>

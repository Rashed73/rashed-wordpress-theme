<?php
/**
 * Breadcrumb trail. Args: items (list of [label, url]); the last one has no url and is the current page.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$items = $args['items'] ?? [];
if (count($items) < 2) {
    return;
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'rashid'); ?>">
  <ol>
    <?php foreach ($items as [$label, $url]) : ?>
      <li>
        <?php if ($url !== '') : ?>
          <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
        <?php else : ?>
          <span aria-current="page"><?php echo esc_html($label); ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>

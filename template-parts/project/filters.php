<?php
/**
 * Category filter bar.
 *
 * Every filter is a real link to a category page, so it works without
 * JavaScript; on the all-projects page the script upgrades the links to an
 * instant client-side filter.
 *
 * Args: current (WP_Term|null) the category being viewed.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$terms = rashid_project_categories();
if (!$terms) {
    return;
}

$current = $args['current'] ?? null;
?>
<nav class="filters" aria-label="<?php esc_attr_e('Project categories', 'rashid'); ?>">
  <ul role="list" data-filters>
    <li>
      <a class="filter" href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" data-filter="*" <?php echo $current ? '' : 'aria-current="true"'; ?>>
        <?php esc_html_e('All projects', 'rashid'); ?>
      </a>
    </li>
    <?php foreach ($terms as $term) : ?>
      <li>
        <a class="filter" href="<?php echo esc_url(get_term_link($term)); ?>" data-filter="<?php echo esc_attr($term->slug); ?>" <?php echo ($current && $current->term_id === $term->term_id) ? 'aria-current="true"' : ''; ?>>
          <?php echo esc_html($term->name); ?>
          <span class="filter__count"><?php echo (int) $term->count; ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>

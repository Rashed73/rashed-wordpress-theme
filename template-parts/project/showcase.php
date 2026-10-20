<?php
/**
 * Projects showcase: title band, category filter and the grid of cards.
 * Used by the all-projects page and by single-category pages.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$current = rashid_current_project_category();

rashid_part('common/page-hero', [
    'title'   => $current ? $current->name : __('Selected work', 'rashid'),
    'lead'    => $current && $current->description ? $current->description : __('Websites, e-commerce platforms and business systems built for clients.', 'rashid'),
    'eyebrow' => __('Portfolio', 'rashid'),
]);
?>
<section class="section showcase">
  <div class="container">
    <?php rashid_part('project/filters', ['current' => $current]); ?>
    <?php rashid_part('project/grid', ['filterable' => $current === null]); ?>
  </div>
</section>

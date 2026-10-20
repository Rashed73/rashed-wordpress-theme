<?php
/**
 * Grid of project cards from the main query.
 *
 * The first card of the unfiltered first page is shown wide ("feature"); pin a
 * project there with the Order box on its edit screen.
 *
 * Args: filterable (bool) whether the script may filter this grid in place.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!have_posts()) {
    echo '<p class="muted">' . esc_html__('No projects yet.', 'rashid') . '</p>';
    return;
}

$filterable = !empty($args['filterable']);
$feature    = $filterable && !is_paged();
?>
<div class="project-grid" data-grid <?php echo $filterable ? 'data-filterable' : ''; ?>>
  <?php while (have_posts()) : the_post(); ?>
    <?php rashid_part('project/card', ['feature' => $feature, 'heading' => 'h2']); ?>
    <?php $feature = false; ?>
  <?php endwhile; ?>
</div>
<?php the_posts_pagination(); ?>

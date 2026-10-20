<?php
/**
 * One project card. Call inside the loop.
 *
 * Args: feature (bool) render the wide variant; heading ('h2' or 'h3', default h3) so
 * the title never skips a heading level on the page it appears in.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$project = rashid_project();
$tag     = ($args['heading'] ?? 'h3') === 'h2' ? 'h2' : 'h3';
$class   = !empty($args['feature']) ? 'project-card project-card--feature' : 'project-card';
$slugs   = implode(' ', array_keys($project->categories()));
?>
<article class="<?php echo esc_attr($class); ?>" data-cats="<?php echo esc_attr($slugs); ?>">
  <a class="project-card__link" href="<?php the_permalink(); ?>">

    <div class="project-card__media">
      <?php if ($project->hasImage()) : ?>
        <?php the_post_thumbnail(!empty($args['feature']) ? 'project-wide' : 'project-card', ['loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title()]); ?>
      <?php else : ?>
        <span class="project-card__placeholder" aria-hidden="true"><?php echo esc_html($project->websiteHost() ?: get_the_title()); ?></span>
      <?php endif; ?>
    </div>

    <div class="project-card__body">
      <?php rashid_part('project/categories', ['project' => $project]); ?>
      <<?php echo $tag; ?> class="project-card__title"><?php the_title(); ?></<?php echo $tag; ?>>
      <?php if ($project->industry() !== '') : ?>
        <p class="project-card__meta"><?php echo esc_html($project->industry()); ?></p>
      <?php endif; ?>
      <?php if (has_excerpt()) : ?>
        <p class="project-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <span class="more"><?php esc_html_e('View project', 'rashid'); ?> <?php echo rashid_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG from Icons. ?></span>
    </div>

  </a>
</article>

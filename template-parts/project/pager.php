<?php
/**
 * Previous / next project links at the bottom of a project page.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$previous = get_adjacent_post(false, '', true);
$next     = get_adjacent_post(false, '', false);
if (!$previous && !$next) {
    return;
}
?>
<nav class="project-nav" aria-label="<?php esc_attr_e('Projects', 'rashid'); ?>">
  <div class="container project-nav__inner">
    <?php if ($previous) : ?>
      <a class="project-nav__link" href="<?php echo esc_url(get_permalink($previous)); ?>">
        <span class="project-nav__label"><?php esc_html_e('Previous project', 'rashid'); ?></span>
        <span class="project-nav__title"><?php echo esc_html(get_the_title($previous)); ?></span>
      </a>
    <?php endif; ?>

    <a class="project-nav__all" href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>">
      <?php esc_html_e('All projects', 'rashid'); ?>
    </a>

    <?php if ($next) : ?>
      <a class="project-nav__link project-nav__link--next" href="<?php echo esc_url(get_permalink($next)); ?>">
        <span class="project-nav__label"><?php esc_html_e('Next project', 'rashid'); ?></span>
        <span class="project-nav__title"><?php echo esc_html(get_the_title($next)); ?></span>
      </a>
    <?php endif; ?>
  </div>
</nav>

<?php
/**
 * "Project at a glance" side box: facts, technologies and the website button.
 *
 * Args: project (Rashid\Content\Project).
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$project = $args['project'];
$stack   = $project->stack();
?>
<aside class="case__rail" aria-label="<?php esc_attr_e('Project at a glance', 'rashid'); ?>">
  <h2 class="case__rail-title"><?php esc_html_e('Project at a glance', 'rashid'); ?></h2>

  <dl class="facts">
    <?php foreach ($project->facts() as $fact) : ?>
      <div class="facts__row">
        <dt><?php echo esc_html($fact['label']); ?></dt>
        <dd><?php echo esc_html($fact['value']); ?></dd>
      </div>
    <?php endforeach; ?>

    <?php if ($stack) : ?>
      <div class="facts__row">
        <dt><?php esc_html_e('Technologies', 'rashid'); ?></dt>
        <dd>
          <ul class="chips" role="list">
            <?php foreach ($stack as $item) : ?>
              <li class="chip"><?php echo esc_html($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </dd>
      </div>
    <?php endif; ?>
  </dl>

  <?php if ($project->website() !== '') : ?>
    <a class="btn" href="<?php echo esc_url($project->website()); ?>" target="_blank" rel="noopener noreferrer">
      <?php esc_html_e('Visit website', 'rashid'); ?> <?php echo rashid_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG from Icons. ?>
    </a>
  <?php endif; ?>
</aside>

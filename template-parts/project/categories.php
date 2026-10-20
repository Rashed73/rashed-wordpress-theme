<?php
/**
 * A project's category names as small chips.
 *
 * Args: project (Rashid\Content\Project).
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$categories = $args['project']->categories();
if (!$categories) {
    return;
}
?>
<ul class="chips" role="list">
  <?php foreach ($categories as $name) : ?>
    <li class="chip"><?php echo esc_html($name); ?></li>
  <?php endforeach; ?>
</ul>

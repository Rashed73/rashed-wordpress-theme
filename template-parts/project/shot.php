<?php
/**
 * The project's main screenshot inside a browser-style frame.
 * Tall captures are cropped from the top; the full image opens in a new tab.
 *
 * @package Rashid
 */

if (!defined('ABSPATH') || !rashid_project()->hasImage()) {
    return;
}
?>
<figure class="shot">
  <div class="shot__bar" aria-hidden="true"><span></span><span></span><span></span></div>
  <a class="shot__frame" href="<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>" target="_blank" rel="noopener">
    <?php the_post_thumbnail('project-wide', ['alt' => get_the_title(), 'decoding' => 'async']); ?>
  </a>
</figure>

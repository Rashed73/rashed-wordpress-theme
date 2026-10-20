<?php
/**
 * Language switcher ("EN | ع"). Prints nothing when there is only one language.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$languages = rashid_languages();
if (count($languages) < 2) {
    return;
}
?>
<ul class="lang" aria-label="<?php esc_attr_e('Language', 'rashid'); ?>">
  <?php foreach ($languages as $language) : ?>
    <li>
      <a href="<?php echo esc_url($language['url']); ?>"
         hreflang="<?php echo esc_attr($language['code']); ?>"
         lang="<?php echo esc_attr($language['code']); ?>"
         <?php echo $language['current'] ? 'aria-current="true"' : ''; ?>>
        <?php echo esc_html($language['label']); ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?php
/**
 * Primary navigation: the menu, the language switcher and the header button.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e('Main', 'rashid'); ?>">
  <?php
  wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'menu',
      'fallback_cb'    => [\Rashid\View\FallbackMenu::class, 'render'],
      'depth'          => 2,
  ]);
  ?>

  <div class="site-nav__tools">
    <?php rashid_part('header/language-switcher'); ?>
    <a class="btn btn--sm" href="<?php echo esc_url(rashid_page_url('contact')); ?>">
      <?php echo esc_html(rashid_option('apply_label')); ?>
    </a>
  </div>
</nav>

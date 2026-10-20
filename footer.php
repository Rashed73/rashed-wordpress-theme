<?php
/**
 * Site footer and document end.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<footer class="site-footer">
  <div class="container site-footer__inner">
    <?php rashid_part('common/brand', ['class' => 'brand brand--footer']); ?>

    <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer', 'rashid'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => 'menu',
          'fallback_cb'    => [\Rashid\View\FallbackMenu::class, 'render'],
          'depth'          => 1,
      ]);
      ?>
    </nav>

    <?php $profiles = \Rashid\Settings\Options::profileLinks(); ?>
    <?php if ($profiles !== []) : ?>
      <ul class="footer-social" aria-label="<?php esc_attr_e('Profiles', 'rashid'); ?>">
        <?php foreach ($profiles as $profile) : ?>
          <li><a href="<?php echo esc_url($profile['url']); ?>" rel="me noopener" target="_blank"><?php echo \Rashid\View\Icons::social($profile['label']); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG from Icons. ?><?php echo esc_html($profile['label']); ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'rashid'); ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <p class="site-footer__credit"><?php echo esc_html(rashid_footer_credit()); ?></p>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

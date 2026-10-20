<?php
/**
 * Document head and site header.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'rashid'); ?></a>

<header class="site-header" data-header>
  <div class="container site-header__inner">
    <?php rashid_part('common/brand'); ?>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
      <span class="nav-toggle__bars" aria-hidden="true"></span>
      <span class="screen-reader-text"><?php esc_html_e('Menu', 'rashid'); ?></span>
    </button>

    <?php rashid_part('header/primary-nav'); ?>
  </div>
</header>

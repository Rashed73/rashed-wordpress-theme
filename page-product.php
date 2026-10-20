<?php
/**
 * Template Name: Landing page (own hero)
 *
 * A page that opens with its own dark hero (with the page's h1) instead of the standard
 * title band. Meant for a product or landing page; any page built the same way can pick it.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main site-main--product">
  <?php while (have_posts()) : the_post(); ?>
    <div class="entry-content"><?php the_content(); ?></div>
  <?php endwhile; ?>
</main>
<?php
get_footer();

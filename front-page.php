<?php
/**
 * Front page.
 *
 * The page is made of block content (see the block patterns), so the owner edits
 * it in the block editor, once per language. The template adds nothing else.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main site-main--home">
  <?php while (have_posts()) : the_post(); ?>
    <div class="entry-content"><?php the_content(); ?></div>
  <?php endwhile; ?>
</main>
<?php
get_footer();

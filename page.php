<?php
/**
 * Standard page: a dark title band (title and optional excerpt as the lead line) followed by the page's block content.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <?php while (have_posts()) : the_post(); ?>
    <?php rashid_part('common/page-hero', ['title' => get_the_title(), 'lead' => has_excerpt() ? get_the_excerpt() : '']); ?>
    <div class="entry-content"><?php the_content(); ?></div>
  <?php endwhile; ?>
</main>
<?php
get_footer();

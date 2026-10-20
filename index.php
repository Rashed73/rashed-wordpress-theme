<?php
/**
 * Fallback template: blog index, search results, anything without its own template.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <?php rashid_part('common/page-hero', ['title' => get_the_archive_title() ?: get_bloginfo('name')]); ?>

  <section class="section">
    <div class="container prose">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
          </article>
        <?php endwhile; ?>
        <?php the_posts_pagination(); ?>
      <?php else : ?>
        <p><?php esc_html_e('Nothing found.', 'rashid'); ?></p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();

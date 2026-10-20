<?php
/**
 * The category chips and the grid of article cards, with pagination. Used by the blog page and the archives.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$chips = rashid_blog_categories();
$ours  = is_category() ? get_queried_object_id() : 0;
?>
<section class="section blog">
  <div class="container">
    <?php if ($chips) : ?>
      <nav class="filters" aria-label="<?php esc_attr_e('Article categories', 'rashid'); ?>">
        <ul role="list">
          <li>
            <a class="filter" href="<?php echo esc_url(rashid_blog_url()); ?>" <?php echo $ours ? '' : 'aria-current="true"'; ?>>
              <?php esc_html_e('All articles', 'rashid'); ?>
            </a>
          </li>
          <?php foreach ($chips as $term) : ?>
            <li>
              <a class="filter" href="<?php echo esc_url(get_term_link($term)); ?>" <?php echo $ours === $term->term_id ? 'aria-current="true"' : ''; ?>>
                <?php echo esc_html($term->name); ?>
                <span class="filter__count"><?php echo (int) $term->count; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <?php if (have_posts()) : ?>
      <div class="post-grid">
        <?php while (have_posts()) : the_post(); ?>
          <?php rashid_part('blog/card'); ?>
        <?php endwhile; ?>
      </div>
      <?php
      the_posts_pagination([
          'mid_size'  => 1,
          'prev_text' => '<span class="screen-reader-text">' . esc_html__('Newer articles', 'rashid') . '</span>' . rashid_arrow(),
          'next_text' => '<span class="screen-reader-text">' . esc_html__('Older articles', 'rashid') . '</span>' . rashid_arrow(),
      ]);
      ?>
    <?php else : ?>
      <p class="blog__empty"><?php esc_html_e('Articles are on the way.', 'rashid'); ?></p>
    <?php endif; ?>
  </div>
</section>

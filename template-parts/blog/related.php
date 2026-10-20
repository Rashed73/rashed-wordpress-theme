<?php
/**
 * Up to three more articles from the same category (else the newest ones), under an article.
 *
 * Args: article (Rashid\Content\Article).
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$article  = $args['article'];
$category = $article->primaryCategory();

$related = new WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post__not_in'        => [$article->id],
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
] + ($category ? ['cat' => $category->term_id] : []));

if (!$related->have_posts()) {
    return;
}
?>
<section class="section related">
  <div class="container">
    <h2><?php esc_html_e('Keep reading', 'rashid'); ?></h2>
    <div class="post-grid">
      <?php while ($related->have_posts()) : $related->the_post(); ?>
        <?php rashid_part('blog/card', ['heading' => 'h3']); ?>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>

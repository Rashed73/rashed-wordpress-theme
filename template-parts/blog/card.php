<?php
/**
 * One article card. Call inside the loop.
 *
 * Args: heading ('h2' or 'h3', default h2) so the title never skips a heading level.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$article  = rashid_article();
$category = $article->primaryCategory();
$tag      = ($args['heading'] ?? 'h2') === 'h3' ? 'h3' : 'h2';
?>
<article class="post-card">
  <a class="post-card__link" href="<?php the_permalink(); ?>">
    <div class="post-card__media<?php echo $article->hasImage() ? '' : ' post-card__media--empty'; ?>">
      <?php if ($article->hasImage()) : ?>
        <?php the_post_thumbnail('project-card', ['loading' => 'lazy', 'decoding' => 'async', 'alt' => '']); ?>
      <?php else : ?>
        <?php echo rashid_icon('doc'); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG from Icons. ?>
      <?php endif; ?>
    </div>

    <div class="post-card__body">
      <?php if ($category) : ?>
        <p class="post-card__cat"><?php echo esc_html($category->name); ?></p>
      <?php endif; ?>
      <<?php echo $tag; ?> class="post-card__title"><?php the_title(); ?></<?php echo $tag; ?>>
      <?php if (has_excerpt()) : ?>
        <p class="post-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <p class="post-card__meta">
        <time datetime="<?php echo esc_attr($article->dateIso()); ?>"><?php echo esc_html($article->dateLabel()); ?></time>
        <span><?php echo esc_html($article->readingLabel()); ?></span>
      </p>
    </div>
  </a>
</article>

<?php
/**
 * One article.
 *
 * Layout only: the pieces (breadcrumbs, author box, related articles) are template parts and the
 * facts come from the Article object.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
the_post();

$article  = rashid_article();
$category = $article->primaryCategory();
?>
<main id="main" class="site-main">
  <section class="page-hero article-hero">
    <div class="container">
      <?php
      rashid_part('common/breadcrumbs', ['items' => [
          [__('Home', 'rashid'), rashid_home_url()],
          [rashid_blog_title(), rashid_blog_url()],
          [get_the_title(), ''],
      ]]);
      ?>
      <?php if ($category) : ?>
        <p class="eyebrow"><a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a></p>
      <?php endif; ?>
      <h1><?php the_title(); ?></h1>
      <?php if (has_excerpt()) : ?>
        <p class="page-hero__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <p class="article-meta">
        <span><?php echo esc_html($article->authorName()); ?></span>
        <time datetime="<?php echo esc_attr($article->dateIso()); ?>"><?php echo esc_html($article->dateLabel()); ?></time>
        <span><?php echo esc_html($article->readingLabel()); ?></span>
      </p>
    </div>
  </section>

  <section class="section article">
    <div class="container article__inner">
      <article <?php post_class('article__main'); ?>>
        <?php if ($article->hasImage()) : ?>
          <figure class="article__image"><?php the_post_thumbnail('project-wide', ['decoding' => 'async', 'fetchpriority' => 'high']); ?></figure>
        <?php endif; ?>

        <div class="entry-content prose article__body"><?php the_content(); ?></div>

        <?php rashid_part('blog/author', ['article' => $article]); ?>
      </article>
    </div>
  </section>

  <?php rashid_part('blog/related', ['article' => $article]); ?>
  <?php rashid_part('common/cta-band'); ?>
</main>
<?php
get_footer();

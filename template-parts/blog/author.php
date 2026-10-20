<?php
/**
 * "Written by" box at the end of an article: the owner's name and role, with a link to the About page.
 *
 * Args: article (Rashid\Content\Article).
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

$article = $args['article'];
$role    = rashid_option('author_role');
$name    = $article->authorName();
if ($name === '') {
    return;
}
?>
<aside class="author-box" aria-label="<?php esc_attr_e('About the author', 'rashid'); ?>">
  <p class="author-box__label"><?php esc_html_e('Written by', 'rashid'); ?></p>
  <p class="author-box__name"><?php echo esc_html($name); ?></p>
  <?php if ($role !== '') : ?>
    <p class="author-box__role"><?php echo esc_html($role); ?></p>
  <?php endif; ?>
  <a class="more" href="<?php echo esc_url(rashid_page_url('about')); ?>"><?php esc_html_e('About me', 'rashid'); ?> <?php echo rashid_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG from Icons. ?></a>
</aside>

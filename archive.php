<?php
/**
 * Article archives: a category, a tag, an author or a date.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <?php
  rashid_part('common/page-hero', [
      'eyebrow' => rashid_blog_title(),
      'title'   => wp_strip_all_tags(get_the_archive_title()),
      'lead'    => wp_strip_all_tags(get_the_archive_description()),
  ]);
  ?>
  <?php rashid_part('blog/listing'); ?>
  <?php rashid_part('common/cta-band'); ?>
</main>
<?php
get_footer();

<?php
/**
 * The blog page (the page chosen as "Posts page" under Settings → Reading): every article, newest first.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <?php rashid_part('common/page-hero', ['title' => rashid_blog_title(), 'lead' => rashid_blog_lead()]); ?>
  <?php rashid_part('blog/listing'); ?>
  <?php rashid_part('common/cta-band'); ?>
</main>
<?php
get_footer();

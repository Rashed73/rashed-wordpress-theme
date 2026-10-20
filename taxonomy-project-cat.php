<?php
/**
 * The projects of one category. Shares its layout with the all-projects page.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="main" class="site-main">
  <?php rashid_part('project/showcase'); ?>
  <?php rashid_part('common/cta-band'); ?>
</main>
<?php
get_footer();

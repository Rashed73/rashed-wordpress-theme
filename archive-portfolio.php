<?php
/**
 * Projects showcase page (/portfolio/): every project, filterable by category.
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

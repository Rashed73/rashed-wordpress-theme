<?php
/**
 * One project / case study.
 *
 * Layout only: the pieces (screenshot, facts, neighbours) are template parts and
 * the data comes from the Project object.
 *
 * @package Rashid
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
the_post();

$project   = rashid_project();
$gridClass = $project->hasDetails() ? 'case__grid' : 'case__grid case__grid--single';
?>
<main id="main" class="site-main">
  <?php
  rashid_part('common/page-hero', [
      'title'   => get_the_title(),
      'lead'    => has_excerpt() ? get_the_excerpt() : '',
      'eyebrow' => $project->primaryCategory(),
  ]);
  ?>

  <section class="section case">
    <div class="container <?php echo esc_attr($gridClass); ?>">
      <article class="case__main">
        <?php rashid_part('project/shot'); ?>
        <div class="entry-content prose"><?php the_content(); ?></div>
      </article>

      <?php if ($project->hasDetails()) : ?>
        <?php rashid_part('project/facts', ['project' => $project]); ?>
      <?php endif; ?>
    </div>
  </section>

  <?php rashid_part('project/pager'); ?>
  <?php rashid_part('common/cta-band'); ?>
</main>
<?php
get_footer();

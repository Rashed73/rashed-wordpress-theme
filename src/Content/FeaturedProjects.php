<?php
/**
 * Finds the projects the home page (or any "featured" block) should show.
 *
 * Rule (ADR-0006): projects marked "Show on the home page", ordered by their Order
 * number and then newest first. If none are marked, the newest projects are used,
 * so the section is never empty.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use WP_Query;

final class FeaturedProjects
{
    /**
     * @param int    $count    How many projects to return.
     * @param string $category Optional project-category slug to limit to.
     * @param bool   $onlyHome Prefer projects marked for the home page.
     */
    public static function query(int $count, string $category = '', bool $onlyHome = true): WP_Query
    {
        $query = new WP_Query(self::arguments($count, $category, $onlyHome));

        if ($onlyHome && !$query->have_posts()) {
            return new WP_Query(self::arguments($count, $category, false));
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private static function arguments(int $count, string $category, bool $onlyHome): array
    {
        $arguments = [
            'post_type'      => ProjectPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => max(1, $count),
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
            'no_found_rows'  => true,
        ];

        if ($onlyHome) {
            $arguments['meta_query'] = [['key' => ProjectDisplay::META_HOME, 'value' => '1']];
        }

        if ($category !== '') {
            $arguments['tax_query'] = [['taxonomy' => ProjectPostType::TAXONOMY, 'field' => 'slug', 'terms' => $category]];
        }

        return $arguments;
    }
}

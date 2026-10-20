<?php
/**
 * Renders the "Featured projects" block on the front end.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Blocks;

use Rashid\Content\FeaturedProjects;

final class FeaturedProjectsRenderer
{
    private const DEFAULT_COUNT = 6;

    /**
     * @param array<string, mixed> $attributes Block attributes: count, category, onlyHome.
     */
    public static function render(array $attributes): string
    {
        $query = FeaturedProjects::query(
            (int) ($attributes['count'] ?? self::DEFAULT_COUNT),
            (string) ($attributes['category'] ?? ''),
            (bool) ($attributes['onlyHome'] ?? true)
        );

        if (!$query->have_posts()) {
            return '';
        }

        $html = self::cards($query);
        wp_reset_postdata();

        return '<div class="project-grid project-grid--home">' . $html . '</div>';
    }

    /**
     * Markup of one card per project in the query.
     */
    private static function cards(\WP_Query $query): string
    {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            rashid_part('project/card');
        }

        return (string) ob_get_clean();
    }
}

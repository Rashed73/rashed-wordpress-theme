<?php
/**
 * Project categories as the filter bar needs them.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use WP_Term;

final class ProjectCategories
{
    /**
     * Categories that have at least one project, in the order they were created.
     *
     * Creation order (not alphabetical) lets the owner decide the order of the
     * filter bar by creating the categories in that order.
     *
     * @return WP_Term[]
     */
    public static function all(): array
    {
        $terms = get_terms([
            'taxonomy'   => ProjectPostType::TAXONOMY,
            'hide_empty' => true,
            'orderby'    => 'term_id',
            'order'      => 'ASC',
        ]);

        return is_array($terms) ? $terms : [];
    }

    /**
     * The category being viewed, or null on the all-projects page.
     */
    public static function current(): ?WP_Term
    {
        if (!is_tax(ProjectPostType::TAXONOMY)) {
            return null;
        }

        $term = get_queried_object();

        return $term instanceof WP_Term ? $term : null;
    }
}

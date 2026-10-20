<?php
/**
 * Ordering and paging of the project listings.
 *
 * Projects appear by their manual order first (the "Order" box on the edit
 * screen, used to pin featured work), then newest first.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;
use WP_Query;

final class ProjectQuery implements Module
{
    private const PER_PAGE = 24;

    public function register(): void
    {
        add_action('pre_get_posts', [$this, 'configure']);
    }

    public function configure(WP_Query $query): void
    {
        if (!$this->isProjectListing($query)) {
            return;
        }

        $query->set('posts_per_page', self::PER_PAGE);
        $query->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
    }

    private function isProjectListing(WP_Query $query): bool
    {
        return !is_admin()
            && $query->is_main_query()
            && ($query->is_post_type_archive(ProjectPostType::POST_TYPE) || $query->is_tax(ProjectPostType::TAXONOMY));
    }
}

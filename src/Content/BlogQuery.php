<?php
/**
 * Blog listing size: nine articles per page (three rows of three), on the blog page and its archives.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;
use WP_Query;

final class BlogQuery implements Module
{
    private const PER_PAGE = 9;

    public function register(): void
    {
        add_action('pre_get_posts', [$this, 'limit']);
    }

    public function limit(WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($query->is_home() || $query->is_category() || $query->is_tag()) {
            $query->set('posts_per_page', self::PER_PAGE);
        }
    }
}

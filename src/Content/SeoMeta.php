<?php
/**
 * The two per-page search fields: an optional page title and an optional description.
 *
 * Saved as post meta on pages and projects, shown in the block editor sidebar (Admin\SeoPanel),
 * and read by Setup\SeoTitles (the <title>) and Setup\MetaTags (the description).
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

final class SeoMeta
{
    public const TITLE       = 'rashid_seo_title';
    public const DESCRIPTION = 'rashid_seo_description';

    /** Post types that carry the fields. */
    public const POST_TYPES = ['page', 'post', ProjectPostType::POST_TYPE];

    /**
     * The post whose search fields apply to the current request: the page or article being viewed, or the
     * page chosen as "Posts page" on the blog index. Zero on archives and everywhere else.
     */
    public static function currentPostId(): int
    {
        if (is_singular()) {
            return (int) get_queried_object_id();
        }

        $object = is_home() ? get_queried_object() : null;

        return $object instanceof \WP_Post ? (int) $object->ID : 0;
    }

    /** The saved value for a post, or an empty string. */
    public static function get(int $postId, string $key): string
    {
        return trim((string) get_post_meta($postId, $key, true));
    }
}

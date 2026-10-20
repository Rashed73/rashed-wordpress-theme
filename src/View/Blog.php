<?php
/**
 * Where the blog lives: its address, title and intro, and the categories that have articles.
 *
 * The blog is the page chosen as "Posts page" (Settings → Reading), one per language with Polylang.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\View;

use WP_Term;

final class Blog
{
    /** The blog page in the current language, or 0 when none is set. */
    private static function pageId(): int
    {
        $id = (int) get_option('page_for_posts');
        if ($id > 0 && function_exists('pll_get_post')) {
            $translated = pll_get_post($id);
            $id         = $translated ? (int) $translated : $id;
        }

        return $id;
    }

    public static function url(): string
    {
        $id = self::pageId();

        return $id > 0 ? (string) get_permalink($id) : Urls::home();
    }

    public static function title(): string
    {
        $id = self::pageId();

        return $id > 0 ? wp_strip_all_tags(get_the_title($id)) : __('Blog', 'rashid');
    }

    /** The blog page's excerpt, shown under its title. */
    public static function lead(): string
    {
        $id = self::pageId();

        return $id > 0 ? wp_strip_all_tags((string) get_post_field('post_excerpt', $id)) : '';
    }

    /**
     * Categories that have at least one article (for the filter bar), without the default one.
     *
     * @return WP_Term[]
     */
    public static function categories(): array
    {
        $terms = get_categories(['hide_empty' => true, 'exclude' => [(int) get_option('default_category')]]);

        return is_array($terms) ? $terms : [];
    }
}

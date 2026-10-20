<?php
/**
 * URL helpers that respect the current language.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\View;

final class Urls
{
    /**
     * Home URL of the current language.
     */
    public static function home(): string
    {
        return function_exists('pll_home_url') ? (string) pll_home_url() : home_url('/');
    }

    /**
     * URL of a page by its path, in the current language when a translation exists.
     *
     * Falls back to "<home>/<path>/" so menus and buttons still work before the
     * page has been created.
     */
    public static function page(string $path): string
    {
        $page = get_page_by_path($path);
        if (!$page instanceof \WP_Post) {
            return trailingslashit(self::home()) . trailingslashit($path);
        }

        return (string) get_permalink(self::translatedId($page->ID));
    }

    /**
     * Post ID of the current-language translation, or the same ID without Polylang.
     */
    private static function translatedId(int $postId): int
    {
        if (!function_exists('pll_get_post')) {
            return $postId;
        }

        $translated = pll_get_post($postId);

        return $translated ? (int) $translated : $postId;
    }
}

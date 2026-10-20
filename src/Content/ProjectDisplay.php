<?php
/**
 * The two controls that decide where a project appears: its order number and
 * whether it is shown on the home page.
 *
 * The order number is WordPress's own `menu_order` (lower comes first), so every
 * listing sorts by it without extra queries. "Show on the home page" is a meta flag.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

final class ProjectDisplay
{
    /** Post-meta key of the "show on the home page" flag ("1" when set). */
    public const META_HOME = 'rashid_home';

    /** Order given to a new project: after the hand-numbered featured ones. */
    public const DEFAULT_ORDER = 10;

    /**
     * Whether the project is marked to appear on the home page.
     */
    public static function onHome(int $postId): bool
    {
        return get_post_meta($postId, self::META_HOME, true) === '1';
    }

    /**
     * The project's order number (lower comes first).
     */
    public static function order(int $postId): int
    {
        return (int) get_post_field('menu_order', $postId);
    }
}

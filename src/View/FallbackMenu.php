<?php
/**
 * Menu shown until the owner assigns a real one under Appearance → Menus.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\View;

use Rashid\Content\ProjectPostType;

final class FallbackMenu
{
    /**
     * Print a minimal menu.
     */
    public static function render(): void
    {
        echo '<ul class="menu">';
        foreach (self::items() as $label => $url) {
            printf('<li class="menu-item"><a href="%s">%s</a></li>', esc_url($url), esc_html($label));
        }
        echo '</ul>';
    }

    /**
     * @return array<string, string> Label => URL.
     */
    private static function items(): array
    {
        $projects = get_post_type_archive_link(ProjectPostType::POST_TYPE);

        return [
            __('Home', 'rashid')      => Urls::home(),
            __('Projects', 'rashid')  => $projects ? $projects : home_url('/portfolio/'),
            __('About', 'rashid')     => Urls::page('about'),
            __('Contact', 'rashid')   => Urls::page('contact'),
        ];
    }
}

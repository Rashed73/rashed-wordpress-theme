<?php
/**
 * Gives the generic "Learn more" links of the cards a descriptive name for search engines and
 * screen readers: "Learn more" becomes "Learn more about <card title>" (the added words are
 * visually hidden). Works on the saved page content, so editors keep writing plain cards.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;

final class MoreLinks implements Module
{
    /** Labels that say nothing on their own, in each language the site uses. */
    private const GENERIC = ['Learn more', 'اعرف المزيد'];

    public function register(): void
    {
        add_filter('the_content', [$this, 'describe'], 20);
    }

    public function describe(string $html): string
    {
        if (!str_contains($html, 'class="more"')) {
            return $html;
        }

        $title = '';

        // Walks the headings and the links in order: a link takes the nearest heading before it.
        return (string) preg_replace_callback(
            '#<h[1-6][^>]*>(.*?)</h[1-6]>|<a class="more"([^>]*)>(.*?)</a>#s',
            function (array $match) use (&$title): string {
                if (!isset($match[3])) {
                    $title = trim(wp_strip_all_tags($match[1]));
                    return $match[0];
                }

                $label = trim(wp_strip_all_tags($match[3]));
                if ($title === '' || !in_array($label, self::GENERIC, true)) {
                    return $match[0];
                }

                return '<a class="more"' . $match[2] . '>' . $match[3]
                    . '<span class="screen-reader-text"> ' . esc_html($title) . '</span></a>';
            },
            $html
        );
    }
}

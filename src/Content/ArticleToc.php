<?php
/**
 * "On this page" list at the top of a long article: links to its second-level headings.
 *
 * Appears only on a single article with three or more such headings. Headings without an id get one,
 * so every link has somewhere to go (Arabic headings included).
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;

final class ArticleToc implements Module
{
    private const MIN_HEADINGS = 3;

    public function register(): void
    {
        add_filter('the_content', [$this, 'add'], 9);
    }

    public function add(string $html): string
    {
        if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
            return $html;
        }

        $items = [];
        $used  = [];

        $html = (string) preg_replace_callback(
            '#<h2([^>]*)>(.*?)</h2>#s',
            function (array $match) use (&$items, &$used): string {
                $text = trim(wp_strip_all_tags($match[2]));
                if ($text === '') {
                    return $match[0];
                }

                if (preg_match('/\sid="([^"]+)"/', $match[1], $found)) {
                    $id = $found[1];
                } else {
                    $id    = $this->uniqueId($text, $used);
                    $match[1] .= ' id="' . esc_attr($id) . '"';
                }

                $used[]  = $id;
                $items[] = [$id, $text];

                return '<h2' . $match[1] . '>' . $match[2] . '</h2>';
            },
            $html
        );

        if (count($items) < self::MIN_HEADINGS) {
            return $html;
        }

        $list = '';
        foreach ($items as [$id, $text]) {
            $list .= '<li><a href="#' . esc_attr($id) . '">' . esc_html($text) . '</a></li>';
        }

        $toc = '<nav class="toc" aria-label="' . esc_attr__('On this page', 'rashid') . '"><p class="toc__title">'
            . esc_html__('On this page', 'rashid') . '</p><ol>' . $list . '</ol></nav>';

        return $toc . $html;
    }

    /** @param string[] $used */
    private function uniqueId(string $text, array $used): string
    {
        $base = sanitize_title($text);
        $base = $base !== '' ? $base : 'section';
        $id   = $base;

        for ($n = 2; in_array($id, $used, true); $n++) {
            $id = $base . '-' . $n;
        }

        return $id;
    }
}

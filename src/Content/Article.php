<?php
/**
 * Facts about one article (a blog post): reading time, category, date, author.
 *
 * Templates ask this object instead of computing anything themselves.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Settings\Options;
use WP_Term;

final class Article
{
    /** Words read per minute, used for the reading time. */
    private const WORDS_PER_MINUTE = 200;

    private function __construct(public readonly int $id)
    {
    }

    public static function fromPost(?int $postId = null): self
    {
        return new self($postId ?? (int) get_the_ID());
    }

    /** Whole minutes, at least one. Counts words by white space, which works for Arabic and English. */
    public function readingMinutes(): int
    {
        $text  = wp_strip_all_tags(strip_shortcodes((string) get_post_field('post_content', $this->id)));
        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return max(1, (int) ceil(count($words) / self::WORDS_PER_MINUTE));
    }

    public function readingLabel(): string
    {
        /* translators: %d: minutes */
        return sprintf(__('%d min read', 'rashid'), $this->readingMinutes());
    }

    /** The first category that is not WordPress's default "Uncategorized". */
    public function primaryCategory(): ?WP_Term
    {
        $default = (int) get_option('default_category');
        foreach (get_the_category($this->id) as $term) {
            if ((int) $term->term_id !== $default) {
                return $term;
            }
        }

        return null;
    }

    public function hasImage(): bool
    {
        return Project::imageExists($this->id);
    }

    /**
     * "20 October 2026" with the month written by the theme (translated in languages/), so Arabic pages show
     * Arabic month names even when WordPress's own Arabic language pack is not installed on the host.
     */
    public function dateLabel(): string
    {
        $time = (int) get_post_time('U', true, $this->id);

        return sprintf('%d %s %d', (int) wp_date('j', $time), self::monthName((int) wp_date('n', $time)), (int) wp_date('Y', $time));
    }

    private static function monthName(int $month): string
    {
        $names = [
            1  => _x('January', 'month name', 'rashid'),
            2  => _x('February', 'month name', 'rashid'),
            3  => _x('March', 'month name', 'rashid'),
            4  => _x('April', 'month name', 'rashid'),
            5  => _x('May', 'month name', 'rashid'),
            6  => _x('June', 'month name', 'rashid'),
            7  => _x('July', 'month name', 'rashid'),
            8  => _x('August', 'month name', 'rashid'),
            9  => _x('September', 'month name', 'rashid'),
            10 => _x('October', 'month name', 'rashid'),
            11 => _x('November', 'month name', 'rashid'),
            12 => _x('December', 'month name', 'rashid'),
        ];

        return $names[$month] ?? '';
    }

    public function dateIso(): string
    {
        return (string) get_the_date('c', $this->id);
    }

    public function modifiedIso(): string
    {
        return (string) get_the_modified_date('c', $this->id);
    }

    /** The owner's name from the Customizer, else the post author's display name. */
    public function authorName(): string
    {
        $name = Options::get('author_name');
        if ($name !== '') {
            return $name;
        }

        return (string) get_the_author_meta('display_name', (int) get_post_field('post_author', $this->id));
    }
}

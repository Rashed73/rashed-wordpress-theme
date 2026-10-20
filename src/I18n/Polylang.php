<?php
/**
 * Polylang integration (English / Arabic).
 *
 * Every hook here is harmless when Polylang is not installed: the filters never
 * fire and pll_register_string() is guarded, so the theme also runs as a
 * single-language site.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\I18n;

use Rashid\Content\ProjectPostType;
use Rashid\Module;
use Rashid\Settings\Options;

final class Polylang implements Module
{
    private const STRING_GROUP = 'Rashid theme';

    public function register(): void
    {
        add_action('init', [$this, 'registerOptionStrings']);
        add_filter('pll_get_post_types', [$this, 'translatablePostTypes'], 10, 2);
        add_filter('pll_get_taxonomies', [$this, 'translatableTaxonomies'], 10, 2);
    }

    /**
     * Make the owner-editable options translatable under Languages → Strings.
     */
    public function registerOptionStrings(): void
    {
        if (!function_exists('pll_register_string')) {
            return;
        }

        foreach (Options::rawValues() as $key => $value) {
            pll_register_string(Options::PREFIX . $key, $value, self::STRING_GROUP, $key === 'cta_text');
        }
    }

    /**
     * Always translate projects, without offering the choice in Polylang's settings.
     *
     * @param array<string, string> $types
     * @return array<string, string>
     */
    public function translatablePostTypes(array $types, bool $isSettings): array
    {
        // Projects and articles are always translated (articles are the blog, one set per language).
        return $this->alwaysEnabled($this->alwaysEnabled($types, ProjectPostType::POST_TYPE, $isSettings), 'post', $isSettings);
    }

    /**
     * Always translate project categories.
     *
     * @param array<string, string> $taxonomies
     * @return array<string, string>
     */
    public function translatableTaxonomies(array $taxonomies, bool $isSettings): array
    {
        return $this->alwaysEnabled($this->alwaysEnabled($taxonomies, ProjectPostType::TAXONOMY, $isSettings), 'category', $isSettings);
    }

    /**
     * Force an item on, and hide it from the settings screen (Polylang's
     * documented pattern for content the theme always translates).
     *
     * @param array<string, string> $items
     * @return array<string, string>
     */
    private function alwaysEnabled(array $items, string $name, bool $isSettings): array
    {
        if ($isSettings) {
            unset($items[$name]);
            return $items;
        }

        $items[$name] = $name;
        return $items;
    }
}

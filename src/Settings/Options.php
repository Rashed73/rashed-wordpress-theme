<?php
/**
 * Owner-editable theme options (brand and call-to-action text).
 *
 * These are the only pieces of site-specific copy the theme itself prints. Each
 * one is edited under Appearance → Customize and, when Polylang is active, is
 * translated per language under Languages → Strings.
 *
 * Defaults are generic so the theme is usable on any site out of the box.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Settings;

final class Options
{
    public const PREFIX = 'rashid_';

    /**
     * Option definitions, in the order they appear in the Customizer.
     *
     * Call only after translations load (labels and defaults use gettext).
     *
     * @return array<string, array{label: string, default: string, input: string}>
     */
    public static function definitions(): array
    {
        return [
            'brand_name'    => ['label' => __('Brand name', 'rashid'), 'default' => get_bloginfo('name'), 'input' => 'text'],
            'brand_mark'    => ['label' => __('Brand mark (1-3 characters)', 'rashid'), 'default' => '', 'input' => 'text'],
            'author_name'   => ['label' => __('Your name (used in page titles and search results)', 'rashid'), 'default' => '', 'input' => 'text'],
            'author_role'   => ['label' => __('Your role, e.g. Web developer', 'rashid'), 'default' => '', 'input' => 'text'],
            'profile_links' => ['label' => __('Profile links (LinkedIn, GitHub …), separated by spaces or commas', 'rashid'), 'default' => '', 'input' => 'text'],
            'footer_credit' => ['label' => __('Footer text ({brand} = brand name, {year} = current year)', 'rashid'), 'default' => '{brand} · © {year}', 'input' => 'text'],
            'apply_label'   => ['label' => __('Header button text', 'rashid'), 'default' => __('Get in touch', 'rashid'), 'input' => 'text'],
            'cta_title'     => ['label' => __('Closing section: title', 'rashid'), 'default' => __("Let's talk", 'rashid'), 'input' => 'text'],
            'cta_text'      => ['label' => __('Closing section: text', 'rashid'), 'default' => __('Have a question or a request? Send a message and you will get a reply as soon as possible.', 'rashid'), 'input' => 'textarea'],
            'cta_label'     => ['label' => __('Closing section: button text', 'rashid'), 'default' => __('Contact me', 'rashid'), 'input' => 'text'],
        ];
    }

    /**
     * Current value of an option, translated for the current language.
     */
    public static function get(string $key): string
    {
        $definitions = self::definitions();
        if (!isset($definitions[$key])) {
            return '';
        }

        $value = (string) get_theme_mod(self::PREFIX . $key, $definitions[$key]['default']);
        if ($key === 'brand_mark' && $value === '') {
            $value = self::markFromName();
        }

        return function_exists('pll__') ? pll__($value) : $value;
    }

    /**
     * Footer line with its {brand} and {year} placeholders filled in.
     *
     * Placeholders use braces, not printf codes, because WordPress itself
     * rewrites "%s" inside theme-mod defaults.
     */
    public static function footerCredit(): string
    {
        return strtr(self::get('footer_credit'), [
            '{brand}' => self::get('brand_name'),
            '{year}'  => wp_date('Y'),
        ]);
    }

    /**
     * Profile links from the Customizer. Separators may be spaces, commas or new lines.
     *
     * @return array<int, array{label: string, url: string}>
     */
    public static function profileLinks(): array
    {
        $parts = preg_split('/[\s,;]+/', trim(self::get('profile_links')), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $known = ['linkedin.com' => 'LinkedIn', 'github.com' => 'GitHub', 'x.com' => 'X', 'twitter.com' => 'X', 'facebook.com' => 'Facebook', 'instagram.com' => 'Instagram', 'youtube.com' => 'YouTube', 'behance.net' => 'Behance'];
        $links = [];
        foreach ($parts as $url) {
            if (!preg_match('#^https?://#i', $url)) {
                continue;
            }
            $host  = preg_replace('/^www\./', '', (string) wp_parse_url($url, PHP_URL_HOST));
            $label = $host;
            foreach ($known as $domain => $name) {
                if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                    $label = $name;
                    break;
                }
            }
            $links[] = ['label' => $label, 'url' => $url];
        }

        return $links;
    }

    /**
     * Untranslated stored values, used to register them with Polylang.
     *
     * @return string[]
     */
    public static function rawValues(): array
    {
        $raw = [];
        foreach (self::definitions() as $key => $definition) {
            $raw[$key] = (string) get_theme_mod(self::PREFIX . $key, $definition['default']);
        }

        return $raw;
    }

    /**
     * Fallback brand mark: the first two characters of the brand name.
     */
    private static function markFromName(): string
    {
        $name = (string) get_theme_mod(self::PREFIX . 'brand_name', get_bloginfo('name'));

        return strtoupper(mb_substr($name, 0, 2));
    }
}

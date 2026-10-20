<?php
/**
 * The site's languages as the language switcher needs them.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\I18n;

final class Languages
{
    /**
     * Short labels for the switcher. Languages not listed use their upper-cased code.
     */
    private const SHORT_LABELS = ['ar' => 'ع'];

    /**
     * @return array<int, array{code: string, label: string, url: string, current: bool}>
     */
    public static function all(): array
    {
        if (!function_exists('pll_the_languages')) {
            return [];
        }

        $languages = pll_the_languages(['raw' => 1, 'hide_if_empty' => 0]);
        if (!is_array($languages)) {
            return [];
        }

        return array_values(array_map([self::class, 'describe'], $languages));
    }

    /**
     * @param array<string, mixed> $language One entry of pll_the_languages( raw ).
     * @return array{code: string, label: string, url: string, current: bool}
     */
    private static function describe(array $language): array
    {
        $code = (string) $language['slug'];

        return [
            'code'    => $code,
            'label'   => self::SHORT_LABELS[$code] ?? strtoupper($code),
            'url'     => (string) $language['url'],
            'current' => !empty($language['current_lang']),
        ];
    }
}

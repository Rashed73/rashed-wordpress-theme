<?php
/**
 * The structured fields every project carries.
 *
 * One place describes a field (its meta key, label, input type and sanitizer);
 * the registration, the admin form and the value object all read from here, so
 * adding a field is a one-line change.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

final class ProjectFields
{
    public const CLIENT    = 'rashid_client';
    public const DOMAIN    = 'rashid_domain';
    public const ROLE      = 'rashid_role';
    public const STACK     = 'rashid_stack';
    public const DEVELOPER = 'rashid_developer';
    public const DESIGNER  = 'rashid_designer';
    public const WEBSITE   = 'rashid_website';

    /**
     * Field definitions in display order.
     *
     * Labels are translated here, so call this only after translations load.
     *
     * @return array<string, array{label: string, input: string, sanitize: callable}>
     */
    public static function definitions(): array
    {
        return [
            self::CLIENT    => self::text(__('Client', 'rashid')),
            self::DOMAIN    => self::text(__('Industry / country', 'rashid')),
            self::ROLE      => self::text(__('My role', 'rashid')),
            self::STACK     => self::text(__('Technologies (comma separated)', 'rashid')),
            self::DEVELOPER => self::text(__('Developer', 'rashid')),
            self::DESIGNER  => self::text(__('Designer', 'rashid')),
            self::WEBSITE   => ['label' => __('Website address', 'rashid'), 'input' => 'url', 'sanitize' => 'esc_url_raw'],
        ];
    }

    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return array_keys(self::definitions());
    }

    /**
     * A single-line text field.
     *
     * @return array{label: string, input: string, sanitize: callable}
     */
    private static function text(string $label): array
    {
        return ['label' => $label, 'input' => 'text', 'sanitize' => 'sanitize_text_field'];
    }
}

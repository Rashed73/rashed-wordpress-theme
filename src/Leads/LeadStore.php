<?php
/**
 * Saves a validated submission as a lead record.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use WP_Error;

final class LeadStore
{
    /**
     * @param array<string, string> $values Validated form values (name, email, phone, message).
     * @param string                $source Which form it came from.
     * @param string                $page   URL of the page the form was on.
     * @return int|WP_Error New lead id.
     */
    public static function create(array $values, string $source, string $page): int|WP_Error
    {
        return wp_insert_post(wp_slash([
            'post_type'    => LeadFields::POST_TYPE,
            'post_status'  => 'publish',
            'post_title'   => $values['name'],
            'post_content' => $values['message'],
            'meta_input'   => [
                LeadFields::META_EMAIL    => $values['email'],
                LeadFields::META_PHONE    => $values['phone'],
                LeadFields::META_SOURCE   => $source,
                LeadFields::META_PAGE     => $page,
                LeadFields::META_LANGUAGE => self::language(),
                LeadFields::META_STATUS   => LeadFields::STATUS_NEW,
            ],
        ]), true);
    }

    /**
     * Language of the visitor's page (Polylang when active, else the site locale).
     */
    private static function language(): string
    {
        if (function_exists('pll_current_language')) {
            $code = pll_current_language();
            if (is_string($code) && $code !== '') {
                return $code;
            }
        }

        return strtolower(substr(get_locale(), 0, 2));
    }
}

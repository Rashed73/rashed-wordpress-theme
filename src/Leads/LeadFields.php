<?php
/**
 * Names used to store a lead: record type, meta keys and the status list.
 *
 * Kept in one place so the store, the admin screens and the export agree.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class LeadFields
{
    public const POST_TYPE = 'lead';

    public const META_EMAIL    = 'rashid_lead_email';
    public const META_PHONE    = 'rashid_lead_phone';
    public const META_SOURCE   = 'rashid_lead_source';
    public const META_PAGE     = 'rashid_lead_page';
    public const META_LANGUAGE = 'rashid_lead_language';
    public const META_STATUS   = 'rashid_lead_status';
    public const META_NOTES    = 'rashid_lead_notes';

    public const STATUS_NEW       = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_CLOSED    = 'closed';

    /**
     * Status values with their labels, in workflow order.
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW       => __('New', 'rashid'),
            self::STATUS_CONTACTED => __('Contacted', 'rashid'),
            self::STATUS_CLOSED    => __('Closed', 'rashid'),
        ];
    }

    public static function isStatus(string $value): bool
    {
        return array_key_exists($value, self::statuses());
    }
}

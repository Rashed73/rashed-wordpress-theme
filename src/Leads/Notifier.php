<?php
/**
 * Emails a new lead to the owner, with Reply-To set to the visitor.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class Notifier
{
    public static function send(Lead $lead): bool
    {
        $subject = sprintf(
            /* translators: 1: site name, 2: visitor name. */
            __('[%1$s] New request from %2$s', 'rashid'),
            wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES),
            $lead->name
        );

        // The name was reduced to one clean line when validated, so the header cannot be split.
        $headers = ['Reply-To: ' . $lead->name . ' <' . $lead->email . '>'];

        return wp_mail(Recipient::email(), $subject, self::body($lead), $headers);
    }

    private static function body(Lead $lead): string
    {
        $lines = [
            __('Name', 'rashid') . ': ' . $lead->name,
            __('Email', 'rashid') . ': ' . $lead->email,
            __('Phone', 'rashid') . ': ' . ($lead->phone !== '' ? $lead->phone : '-'),
            __('Form', 'rashid') . ': ' . $lead->source,
            __('Page', 'rashid') . ': ' . $lead->page,
            '',
            $lead->message,
            '',
            __('Open in the dashboard', 'rashid') . ': ' . admin_url('post.php?post=' . $lead->id . '&action=edit'),
        ];

        return implode("\n", $lines);
    }
}

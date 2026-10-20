<?php
/**
 * "Export CSV" button on the Leads list, and the download it triggers.
 *
 * The file holds personal data: only administrators can download it, and the
 * download is protected by a nonce.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads\Admin;

use Rashid\Leads\Lead;
use Rashid\Leads\LeadFields;
use Rashid\Module;

final class LeadExport implements Module
{
    private const ACTION = 'rashid_export_leads';

    public function register(): void
    {
        add_action('admin_post_' . self::ACTION, [$this, 'download']);
        add_action('manage_posts_extra_tablenav', [$this, 'button']);
    }

    public function button(string $which): void
    {
        if ($which !== 'top' || get_current_screen()?->post_type !== LeadFields::POST_TYPE) {
            return;
        }

        $url = wp_nonce_url(admin_url('admin-post.php?action=' . self::ACTION), self::ACTION);
        echo '<a class="button" href="' . esc_url($url) . '">' . esc_html__('Export CSV', 'rashid') . '</a>';
    }

    public function download(): void
    {
        if (!current_user_can('manage_options') || !check_admin_referer(self::ACTION)) {
            wp_die(esc_html__('You are not allowed to export leads.', 'rashid'), '', ['response' => 403]);
        }

        nocache_headers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="leads-' . gmdate('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // Byte order mark so Excel reads Arabic correctly.
        fputcsv($out, [__('Received', 'rashid'), __('Name', 'rashid'), __('Email', 'rashid'), __('Phone', 'rashid'), __('Form', 'rashid'), __('Language', 'rashid'), __('Status', 'rashid'), __('Message', 'rashid'), __('Notes', 'rashid')]);

        foreach ($this->leadIds() as $id) {
            $lead = Lead::fromPost($id);
            fputcsv($out, array_map([self::class, 'safeCell'], [
                $lead->date, $lead->name, $lead->email, $lead->phone, $lead->source,
                $lead->language, LeadFields::statuses()[$lead->status], $lead->message, $lead->notes,
            ]));
        }

        fclose($out);
        exit;
    }

    /**
     * Stop spreadsheet programs from running a cell that starts like a formula.
     */
    public static function safeCell(string $value): string
    {
        return preg_match('/^[=+\-@\t\r]/', $value) === 1 ? "'" . $value : $value;
    }

    /**
     * @return int[] Newest first.
     */
    private function leadIds(): array
    {
        return get_posts([
            'post_type'      => LeadFields::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        ]);
    }
}

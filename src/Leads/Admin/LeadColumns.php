<?php
/**
 * The Leads list: contact columns, status badge, and a status filter.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads\Admin;

use Rashid\Leads\Lead;
use Rashid\Leads\LeadFields;
use Rashid\Module;
use WP_Query;

final class LeadColumns implements Module
{
    private const FILTER = 'rashid_status';

    public function register(): void
    {
        $type = LeadFields::POST_TYPE;

        add_filter("manage_{$type}_posts_columns", [$this, 'columns']);
        add_action("manage_{$type}_posts_custom_column", [$this, 'cell'], 10, 2);
        add_action('restrict_manage_posts', [$this, 'filterDropdown']);
        add_action('pre_get_posts', [$this, 'applyFilter']);
        add_filter('post_row_actions', [$this, 'rowActions'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
    }

    /**
     * @param array<string, string> $columns
     * @return array<string, string>
     */
    public function columns(array $columns): array
    {
        return [
            'cb'                  => $columns['cb'] ?? '',
            'title'               => __('Name', 'rashid'),
            'rashid_contact'      => __('Contact', 'rashid'),
            'rashid_source'       => __('Form', 'rashid'),
            'rashid_status'       => __('Status', 'rashid'),
            'date'                => __('Received', 'rashid'),
        ];
    }

    public function cell(string $column, int $postId): void
    {
        $lead = Lead::fromPost($postId);

        switch ($column) {
            case 'rashid_contact':
                echo esc_html($lead->email);
                if ($lead->phone !== '') {
                    echo '<br>' . esc_html($lead->phone);
                }
                break;
            case 'rashid_source':
                echo esc_html($lead->source);
                break;
            case 'rashid_status':
                $label = LeadFields::statuses()[$lead->status];
                echo '<span class="rashid-status rashid-status--' . esc_attr($lead->status) . '">' . esc_html($label) . '</span>';
                break;
        }
    }

    public function filterDropdown(string $postType): void
    {
        if ($postType !== LeadFields::POST_TYPE) {
            return;
        }

        $current = isset($_GET[self::FILTER]) ? sanitize_key(wp_unslash($_GET[self::FILTER])) : '';
        echo '<select name="' . esc_attr(self::FILTER) . '"><option value="">' . esc_html__('All statuses', 'rashid') . '</option>';
        foreach (LeadFields::statuses() as $value => $label) {
            echo '<option value="' . esc_attr($value) . '"' . selected($current, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }

    public function applyFilter(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== LeadFields::POST_TYPE) {
            return;
        }

        $status = isset($_GET[self::FILTER]) ? sanitize_key(wp_unslash($_GET[self::FILTER])) : '';
        if (LeadFields::isStatus($status)) {
            $query->set('meta_query', [['key' => LeadFields::META_STATUS, 'value' => $status]]);
        }
    }

    /**
     * Leads are read and followed up on the edit screen; quick edit would only
     * expose fields that must not change.
     *
     * @param array<string, string> $actions
     * @return array<string, string>
     */
    public function rowActions(array $actions, \WP_Post $post): array
    {
        if ($post->post_type === LeadFields::POST_TYPE) {
            unset($actions['inline hide-if-no-js']);
        }

        return $actions;
    }

    public function enqueueStyles(): void
    {
        $screen = get_current_screen();
        if ($screen === null || $screen->post_type !== LeadFields::POST_TYPE) {
            return;
        }

        wp_enqueue_style('rashid-admin', RASHID_URI . '/assets/css/admin.css', [], RASHID_VERSION);
    }
}

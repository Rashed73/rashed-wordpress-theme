<?php
/**
 * Registers the private "lead" record type.
 *
 * Leads have no public URL and are limited to administrators. They are created
 * only by the form handler, never from the dashboard.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use Rashid\Module;

final class LeadPostType implements Module
{
    public function register(): void
    {
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(): void
    {
        register_post_type(LeadFields::POST_TYPE, [
            'labels'              => $this->labels(),
            'public'              => false,
            'show_ui'             => true,
            'show_in_rest'        => false,
            'exclude_from_search' => true,
            'menu_icon'           => 'dashicons-email-alt',
            'menu_position'       => 26,
            'supports'            => ['title'],
            'capability_type'     => 'post',
            'capabilities'        => $this->capabilities(),
            'map_meta_cap'        => true,
        ]);
    }

    /**
     * Everything needs the administrator capability; creating is disabled.
     *
     * @return array<string, string>
     */
    private function capabilities(): array
    {
        $admin = 'manage_options';

        return [
            'edit_posts'             => $admin,
            'edit_others_posts'      => $admin,
            'edit_private_posts'     => $admin,
            'edit_published_posts'   => $admin,
            'publish_posts'          => $admin,
            'read_private_posts'     => $admin,
            'delete_posts'           => $admin,
            'delete_private_posts'   => $admin,
            'delete_published_posts' => $admin,
            'delete_others_posts'    => $admin,
            'create_posts'           => 'do_not_allow',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function labels(): array
    {
        return [
            'name'               => __('Leads', 'rashid'),
            'singular_name'      => __('Lead', 'rashid'),
            'menu_name'          => __('Leads', 'rashid'),
            'all_items'          => __('All leads', 'rashid'),
            'edit_item'          => __('Lead', 'rashid'),
            'search_items'       => __('Search leads', 'rashid'),
            'not_found'          => __('No leads yet.', 'rashid'),
            'not_found_in_trash' => __('No leads in the trash.', 'rashid'),
        ];
    }
}

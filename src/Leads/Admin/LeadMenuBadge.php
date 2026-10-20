<?php
/**
 * Shows how many leads are still "New" next to the Leads menu item.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads\Admin;

use Rashid\Leads\LeadFields;
use Rashid\Module;
use WP_Query;

final class LeadMenuBadge implements Module
{
    public function register(): void
    {
        // Late, so the post type's menu entry already exists.
        add_action('admin_menu', [$this, 'addBadge'], 99);
    }

    public function addBadge(): void
    {
        global $menu;

        $count = $this->newLeadCount();
        if ($count === 0 || !is_array($menu)) {
            return;
        }

        $slug = 'edit.php?post_type=' . LeadFields::POST_TYPE;
        foreach ($menu as $position => $item) {
            if (($item[2] ?? '') === $slug) {
                $menu[$position][0] .= ' <span class="awaiting-mod">' . number_format_i18n($count) . '</span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride -- the documented way to add a menu bubble.
                return;
            }
        }
    }

    private function newLeadCount(): int
    {
        $query = new WP_Query([
            'post_type'      => LeadFields::POST_TYPE,
            'post_status'    => 'publish',
            'meta_key'       => LeadFields::META_STATUS,
            'meta_value'     => LeadFields::STATUS_NEW,
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => false,
        ]);

        return (int) $query->found_posts;
    }
}

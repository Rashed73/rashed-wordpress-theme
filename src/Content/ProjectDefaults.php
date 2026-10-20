<?php
/**
 * Gives a new project the default order number.
 *
 * A new post saves with order 0, which would put it before the hand-numbered featured
 * projects. The first save of a new project with no number set gets the default instead.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;

final class ProjectDefaults implements Module
{
    public function register(): void
    {
        add_filter('wp_insert_post_data', [$this, 'applyDefaultOrder'], 10, 2);
    }

    /**
     * @param array<string, mixed> $data    Post data about to be saved.
     * @param array<string, mixed> $postarr Raw data the save was started with.
     * @return array<string, mixed>
     */
    public function applyDefaultOrder(array $data, array $postarr): array
    {
        if (($data['post_type'] ?? '') !== ProjectPostType::POST_TYPE || (int) ($data['menu_order'] ?? 0) !== 0) {
            return $data;
        }

        $id = (int) ($postarr['ID'] ?? 0);
        if ($id === 0 || get_post_status($id) === 'auto-draft') {
            $data['menu_order'] = ProjectDisplay::DEFAULT_ORDER;
        }

        return $data;
    }
}

<?php
/**
 * Columns and default sorting of the Projects list in the dashboard.
 *
 * The list shows the screenshot, categories, order and home flag, sorted by Order
 * first, so the owner sees the projects in the order visitors will.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Admin;

use Rashid\Content\ProjectDisplay;
use Rashid\Content\ProjectPostType;
use Rashid\Module;
use WP_Query;

final class ProjectColumns implements Module
{
    private const COLUMN_IMAGE = 'rashid_image';
    private const COLUMN_ORDER = 'rashid_order';
    private const COLUMN_HOME  = 'rashid_home';

    public function register(): void
    {
        $type = ProjectPostType::POST_TYPE;

        add_filter("manage_{$type}_posts_columns", [$this, 'columns']);
        add_action("manage_{$type}_posts_custom_column", [$this, 'cell'], 10, 2);
        add_filter("manage_edit-{$type}_sortable_columns", [$this, 'sortable']);
        add_action('pre_get_posts', [$this, 'defaultSort']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
    }

    /**
     * @param array<string, string> $columns
     * @return array<string, string>
     */
    public function columns(array $columns): array
    {
        $result = [];
        foreach ($columns as $key => $label) {
            $result[$key] = $label;
            if ($key === 'cb') {
                $result[self::COLUMN_IMAGE] = __('Screenshot', 'rashid');
            }
        }

        $result[self::COLUMN_ORDER] = __('Order', 'rashid');
        $result[self::COLUMN_HOME]  = __('On home', 'rashid');

        return $result;
    }

    public function cell(string $column, int $postId): void
    {
        match ($column) {
            self::COLUMN_IMAGE => print(get_the_post_thumbnail($postId, [80, 50]) ?: '—'),
            self::COLUMN_ORDER => print((string) ProjectDisplay::order($postId)),
            self::COLUMN_HOME  => print(ProjectDisplay::onHome($postId) ? '✓' : '—'),
            default            => null,
        };
    }

    /**
     * @param array<string, string> $columns
     * @return array<string, string>
     */
    public function sortable(array $columns): array
    {
        $columns[self::COLUMN_ORDER] = 'menu_order';

        return $columns;
    }

    /**
     * Show projects by Order (then newest) until the owner picks another sort.
     */
    public function defaultSort(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== ProjectPostType::POST_TYPE) {
            return;
        }

        if ($query->get('orderby') === '') {
            $query->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
        }
    }

    public function enqueueStyles(string $hook): void
    {
        if ($hook !== 'edit.php' || get_current_screen()?->post_type !== ProjectPostType::POST_TYPE) {
            return;
        }

        wp_enqueue_style('rashid-admin', RASHID_URI . '/assets/css/admin.css', [], RASHID_VERSION);
    }
}

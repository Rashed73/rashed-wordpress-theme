<?php
/**
 * The project panels in the block editor sidebar ("Display on the site" and
 * "Project details"), and the post meta they save.
 *
 * Classic meta boxes are collapsed in a panel at the bottom of the block editor, where
 * owners do not find them, so these controls live in the sidebar instead. The order
 * number (menu_order) is edited in the first panel; the project type supports page attributes only so the block editor can read and write it.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Admin;

use Rashid\Content\ProjectDisplay;
use Rashid\Content\ProjectFields;
use Rashid\Content\ProjectPostType;
use Rashid\Module;

final class ProjectPanel implements Module
{
    private const HANDLE = 'rashid-project-panel';

    public function register(): void
    {
        add_action('init', [$this, 'registerHomeFlag']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue']);
    }

    public function registerHomeFlag(): void
    {
        register_post_meta(ProjectPostType::POST_TYPE, ProjectDisplay::META_HOME, [
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => static fn ($value): string => $value === '1' ? '1' : '',
            'auth_callback'     => static fn (): bool => current_user_can('edit_posts'),
        ]);
    }

    public function enqueue(): void
    {
        if (get_current_screen()?->post_type !== ProjectPostType::POST_TYPE) {
            return;
        }

        wp_enqueue_script(
            self::HANDLE,
            RASHID_URI . '/assets/js/editor/project-panel.js',
            ['wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data'],
            RASHID_VERSION,
            true
        );
        wp_add_inline_script(self::HANDLE, 'window.rashidProjectPanel = ' . wp_json_encode($this->config()) . ';', 'before');
    }

    /**
     * What the script needs: the post type, the field list and every label.
     *
     * @return array<string, mixed>
     */
    private function config(): array
    {
        $fields = [];
        foreach (ProjectFields::definitions() as $key => $field) {
            $fields[] = ['key' => $key, 'label' => $field['label'], 'input' => $field['input']];
        }

        return [
            'postType' => ProjectPostType::POST_TYPE,
            'homeKey'  => ProjectDisplay::META_HOME,
            'fields'   => $fields,
            'labels'   => [
                'display'   => __('Display on the site', 'rashid'),
                'home'      => __('Show on the home page', 'rashid'),
                'order'     => __('Order', 'rashid'),
                'orderHelp' => __('Lower numbers come first: 1 is the first project. Projects with the same number show newest first.', 'rashid'),
                'details'   => __('Project details', 'rashid'),
            ],
        ];
    }
}

<?php
/**
 * "Search appearance" panel in the block editor sidebar: the page's own title and description
 * for search engines, with length hints. Both are optional; empty means the automatic ones.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Admin;

use Rashid\Content\SeoMeta;
use Rashid\Module;

final class SeoPanel implements Module
{
    private const HANDLE = 'rashid-seo-panel';

    public function register(): void
    {
        add_action('init', [$this, 'registerMeta']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue']);
    }

    public function registerMeta(): void
    {
        foreach (SeoMeta::POST_TYPES as $type) {
            foreach ([SeoMeta::TITLE, SeoMeta::DESCRIPTION] as $key) {
                register_post_meta($type, $key, [
                    'type'              => 'string',
                    'single'            => true,
                    'show_in_rest'      => true,
                    'sanitize_callback' => 'sanitize_text_field',
                    'auth_callback'     => static fn (): bool => current_user_can('edit_posts'),
                ]);
            }
        }
    }

    public function enqueue(): void
    {
        $type = get_current_screen()?->post_type;
        if (!in_array($type, SeoMeta::POST_TYPES, true)) {
            return;
        }

        wp_enqueue_script(
            self::HANDLE,
            RASHID_URI . '/assets/js/editor/seo-panel.js',
            ['wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-core-data'],
            RASHID_VERSION,
            true
        );
        wp_add_inline_script(self::HANDLE, 'window.rashidSeoPanel = ' . wp_json_encode([
            'postType'       => $type,
            'titleKey'       => SeoMeta::TITLE,
            'descriptionKey' => SeoMeta::DESCRIPTION,
            'labels'         => [
                'panel'       => __('Search appearance', 'rashid'),
                'title'       => __('Title for search engines', 'rashid'),
                'titleHelp'   => __('Aim for 50 to 60 characters. Leave empty to use the page name and your name.', 'rashid'),
                'description' => __('Description for search engines', 'rashid'),
                'descHelp'    => __('Aim for 120 to 160 characters. Leave empty to use the page summary (excerpt).', 'rashid'),
                'characters'  => __('characters', 'rashid'),
            ],
        ]) . ';', 'before');
    }
}

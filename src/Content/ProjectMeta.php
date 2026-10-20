<?php
/**
 * Registers the project fields as post meta, so they are sanitized on save and
 * available to the REST API and the block editor.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;

final class ProjectMeta implements Module
{
    public function register(): void
    {
        add_action('init', [$this, 'registerFields']);
    }

    public function registerFields(): void
    {
        foreach (ProjectFields::definitions() as $key => $field) {
            register_post_meta(ProjectPostType::POST_TYPE, $key, [
                'type'              => 'string',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => $field['sanitize'],
                'auth_callback'     => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }
}

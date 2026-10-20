<?php
/**
 * Registers the project post type and its category taxonomy.
 *
 * The slugs are kept identical to the ones the previous theme (Woodmart) used,
 * so every existing public URL under /portfolio/ keeps working.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

use Rashid\Module;

final class ProjectPostType implements Module
{
    public const POST_TYPE = 'portfolio';
    public const TAXONOMY  = 'project-cat';

    public function register(): void
    {
        // Early priority: other code (Polylang, queries) expects the types to exist.
        add_action('init', [$this, 'registerPostType'], 5);
        add_action('init', [$this, 'registerTaxonomy'], 5);
    }

    public function registerPostType(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels'        => $this->postTypeLabels(),
            'public'        => true,
            'has_archive'   => 'portfolio',
            'rewrite'       => ['slug' => 'portfolio', 'with_front' => false],
            'menu_icon'     => 'dashicons-portfolio',
            'menu_position' => 20,
            'show_in_rest'  => true,
            // page-attributes exposes menu_order (the Order number) to the block editor;
            // custom-fields exposes the project fields (post meta) to it.
            'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'custom-fields'],
            'taxonomies'    => [self::TAXONOMY],
            'template'      => $this->newProjectTemplate(),
        ]);
    }

    /**
     * Blocks a new project starts with, so every project has the same shape.
     *
     * @return array<int, array{0: string, 1?: array<string, mixed>}>
     */
    private function newProjectTemplate(): array
    {
        return [
            ['core/paragraph', ['placeholder' => __('Describe the project: the problem, the approach and the result.', 'rashid')]],
            ['core/list', ['placeholder' => __('What was delivered (one item per line)', 'rashid')]],
            ['core/gallery', ['linkTo' => 'media', 'columns' => 3]],
        ];
    }

    public function registerTaxonomy(): void
    {
        register_taxonomy(self::TAXONOMY, [self::POST_TYPE], [
            'labels'            => [
                'name'          => __('Project categories', 'rashid'),
                'singular_name' => __('Project category', 'rashid'),
            ],
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => ['slug' => 'project-cat', 'with_front' => false],
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function postTypeLabels(): array
    {
        return [
            'name'          => __('Projects', 'rashid'),
            'singular_name' => __('Project', 'rashid'),
            'add_new_item'  => __('Add new project', 'rashid'),
            'edit_item'     => __('Edit project', 'rashid'),
            'all_items'     => __('All projects', 'rashid'),
            'menu_name'     => __('Projects', 'rashid'),
        ];
    }
}

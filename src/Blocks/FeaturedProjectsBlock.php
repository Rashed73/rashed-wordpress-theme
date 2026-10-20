<?php
/**
 * Registers the "Featured projects" block.
 *
 * The block's definition lives with its files in blocks/featured-projects
 * (block.json, render.php, editor.js), the standard WordPress layout.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Blocks;

use Rashid\Module;

final class FeaturedProjectsBlock implements Module
{
    public function register(): void
    {
        add_action('init', [$this, 'registerBlock']);
    }

    public function registerBlock(): void
    {
        register_block_type(RASHID_DIR . '/blocks/featured-projects');
    }
}

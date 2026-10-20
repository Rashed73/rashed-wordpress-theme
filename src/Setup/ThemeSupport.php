<?php
/**
 * Core theme features: supports, menus and image sizes.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Setup;

use Rashid\Module;

final class ThemeSupport implements Module
{
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);
    }

    public function setup(): void
    {
        load_theme_textdomain('rashid', RASHID_DIR . '/languages');

        $this->addSupports();
        $this->registerMenus();
        $this->registerImageSizes();
    }

    /**
     * Features the theme opts into.
     */
    private function addSupports(): void
    {
        // Pages use their excerpt as the lead line under the title.
        add_post_type_support('page', 'excerpt');

        add_theme_support('title-tag');
        add_theme_support('custom-logo', ['height' => 64, 'width' => 160, 'flex-width' => true, 'flex-height' => true]);
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
        add_theme_support('responsive-embeds');
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        // The editor shows the same styles as the site, so pages look as they will on the site.
        add_editor_style(['assets/css/main.css', 'assets/css/editor.css']);
    }

    private function registerMenus(): void
    {
        register_nav_menus([
            'primary' => __('Primary menu', 'rashid'),
            'footer'  => __('Footer menu', 'rashid'),
        ]);
    }

    /**
     * Project screenshots are usually tall full-page captures, so cards crop to a
     * fixed 16:10 window anchored at the top, which always shows the page header.
     */
    private function registerImageSizes(): void
    {
        add_image_size('project-card', 960, 600, ['center', 'top']);
        add_image_size('project-wide', 1600, 0, false);
    }
}

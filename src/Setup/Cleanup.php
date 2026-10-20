<?php
/**
 * Removes default WordPress output and doors the theme does not need.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Setup;

use Rashid\Module;

final class Cleanup implements Module
{
    public function register(): void
    {
        add_action('init', [$this, 'removeDefaults']);
    }

    public function removeDefaults(): void
    {
        // Emoji detection script and styles.
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // The generator meta tag advertises the WordPress version.
        remove_action('wp_head', 'wp_generator');

        // Discovery links in <head> that nothing on this site uses (the REST API itself stays on).
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('template_redirect', 'rest_output_link_header', 11);
        remove_action('template_redirect', 'wp_shortlink_header', 11);

        // XML-RPC is an old remote-publishing door; the site is edited in the dashboard.
        add_filter('xmlrpc_enabled', '__return_false');
    }
}

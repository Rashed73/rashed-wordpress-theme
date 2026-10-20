<?php
/**
 * Theme bootstrap.
 *
 * Only wires things together: constants, the class autoloader and the Theme
 * object that registers every module. Behaviour lives in src/.
 *
 * @package Rashid
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('RASHID_VERSION', '0.1.0');
define('RASHID_DIR', get_template_directory());
define('RASHID_URI', get_template_directory_uri());

require_once RASHID_DIR . '/src/Autoloader.php';
Rashid\Autoloader::register(RASHID_DIR . '/src');

// Thin global helpers for templates; each one delegates to a class in src/.
require_once RASHID_DIR . '/inc/template-functions.php';

Rashid\Theme::boot();

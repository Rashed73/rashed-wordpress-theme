<?php
/**
 * Front-end output of the "Featured projects" block.
 *
 * WordPress provides $attributes. The work happens in the renderer class so this
 * file stays a one-line entry point.
 *
 * @package Rashid
 *
 * @var array<string, mixed> $attributes
 */

if (!defined('ABSPATH')) {
    exit;
}

echo Rashid\Blocks\FeaturedProjectsRenderer::render($attributes); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the project card template part.

<?php
/**
 * Template helpers.
 *
 * Templates stay free of logic by calling these short functions, each of which
 * only delegates to a class in src/. Keep them thin: no logic belongs here.
 *
 * @package Rashid
 */

declare(strict_types=1);

use Rashid\Content\Project;
use Rashid\Content\ProjectCategories;
use Rashid\I18n\Languages;
use Rashid\Settings\Options;
use Rashid\View\Icons;
use Rashid\View\Logo;
use Rashid\View\Urls;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Value of an owner-editable option (brand, call-to-action text).
 */
function rashid_option(string $key): string
{
    return Options::get($key);
}

/**
 * Footer credit line, placeholders already filled in.
 */
function rashid_footer_credit(): string
{
    return Options::footerCredit();
}

/**
 * Markup of the uploaded site logo, or an empty string.
 */
function rashid_logo(): string
{
    return Logo::image();
}

/**
 * Structured data of a project.
 */
function rashid_project(?int $postId = null): Project
{
    return Project::fromPost($postId ?? get_the_ID());
}

/**
 * Categories that have projects (for the filter bar).
 *
 * @return WP_Term[]
 */
function rashid_project_categories(): array
{
    return ProjectCategories::all();
}

/**
 * The project category being viewed, if any.
 */
function rashid_current_project_category(): ?WP_Term
{
    return ProjectCategories::current();
}

/**
 * Facts about an article (reading time, category, date).
 */
function rashid_article(?int $postId = null): Rashid\Content\Article
{
    return Rashid\Content\Article::fromPost($postId);
}

/**
 * Address, title and intro of the blog page, and the categories that have articles.
 */
function rashid_blog_url(): string
{
    return Rashid\View\Blog::url();
}

function rashid_blog_title(): string
{
    return Rashid\View\Blog::title();
}

function rashid_blog_lead(): string
{
    return Rashid\View\Blog::lead();
}

/**
 * @return WP_Term[]
 */
function rashid_blog_categories(): array
{
    return Rashid\View\Blog::categories();
}

/**
 * An inline icon by name (see Rashid\View\Icons).
 */
function rashid_icon(string $name): string
{
    return method_exists(Rashid\View\Icons::class, $name) ? Rashid\View\Icons::$name() : '';
}

/**
 * Language switcher entries.
 *
 * @return array<int, array{code: string, label: string, url: string, current: bool}>
 */
function rashid_languages(): array
{
    return Languages::all();
}

/**
 * Arrow icon (mirrors itself in RTL).
 */
function rashid_arrow(): string
{
    return Icons::arrow();
}

/**
 * Home URL of the current language.
 */
function rashid_home_url(): string
{
    return Urls::home();
}

/**
 * URL of a page by path, in the current language.
 */
function rashid_page_url(string $path): string
{
    return Urls::page($path);
}

/**
 * Load a template part with arguments.
 *
 * @param array<string, mixed> $args
 */
function rashid_part(string $slug, array $args = []): void
{
    get_template_part('template-parts/' . $slug, null, $args);
}

/**
 * Print the lead form (same output as the [rashid_lead_form] shortcode).
 *
 * @param array<string, string> $args source (form name) and button (label).
 */
function rashid_lead_form(array $args = []): void
{
    echo Rashid\Leads\FormShortcode::html($args); // phpcs:ignore WordPress.Security.EscapeOutput -- the form template escapes its output.
}

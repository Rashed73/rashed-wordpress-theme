<?php
/**
 * Page description and social-sharing tags in the page head.
 *
 * Uses the excerpt of the page (or the site tagline) as the description, and the featured
 * image or the site logo as the sharing image. Stays out of the way when an SEO plugin is
 * active, because those print their own tags.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Setup;

use Rashid\Content\Article;
use Rashid\Content\Project;
use Rashid\Content\SeoMeta;
use Rashid\Module;
use Rashid\Settings\Options;
use Rashid\View\Blog;
use Rashid\View\Urls;

final class MetaTags implements Module
{
    public function register(): void
    {
        add_action('wp_head', [$this, 'print'], 5);
    }

    public function print(): void
    {
        if (self::seoPluginActive()) {
            return;
        }

        $description = $this->description();
        $tags        = [
            ['name', 'description', $description],
            ['property', 'og:type', is_singular(['portfolio', 'post']) ? 'article' : 'website'],
            ['property', 'og:title', wp_get_document_title()],
            ['property', 'og:description', $description],
            ['property', 'og:url', $this->url()],
            ['property', 'og:site_name', get_bloginfo('name')],
            ['property', 'og:image', $this->image()],
            ['name', 'twitter:card', 'summary_large_image'],
        ];

        foreach ($tags as [$attribute, $name, $content]) {
            if ($content !== '') {
                printf("<meta %s=\"%s\" content=\"%s\">\n", esc_attr($attribute), esc_attr($name), esc_attr($content));
            }
        }

        $this->printStructuredData();
        $this->printArticleData();
    }

    /**
     * Structured data of an article: a BlogPosting with its author, publisher and dates, and the
     * breadcrumb trail (Home, Blog, the article). Printed on single articles only.
     */
    private function printArticleData(): void
    {
        if (!is_singular('post')) {
            return;
        }

        $article = Article::fromPost((int) get_queried_object_id());
        $url     = (string) get_permalink();
        $home    = Urls::home();
        $image   = $this->image();

        $posting = [
            '@type'            => 'BlogPosting',
            '@id'              => $url . '#article',
            'mainEntityOfPage' => $url,
            'headline'         => wp_strip_all_tags(get_the_title()),
            'description'      => $this->description(),
            'datePublished'    => $article->dateIso(),
            'dateModified'     => $article->modifiedIso(),
            'inLanguage'       => get_bloginfo('language'),
            'author'           => ['@type' => 'Person', 'name' => $article->authorName()],
            'publisher'        => ['@type' => 'Organization', 'name' => Options::get('brand_name')],
        ];
        if ($image !== '') {
            $posting['image'] = $image;
        }

        $trail = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Home', 'rashid'), 'item' => $home],
            ['@type' => 'ListItem', 'position' => 2, 'name' => Blog::title(), 'item' => Blog::url()],
            ['@type' => 'ListItem', 'position' => 3, 'name' => wp_strip_all_tags(get_the_title()), 'item' => $url],
        ];

        echo '<script type="application/ld+json">'
            . wp_json_encode(
                ['@context' => 'https://schema.org', '@graph' => [$posting, ['@type' => 'BreadcrumbList', 'itemListElement' => $trail]]],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
            )
            . "</script>\n";
    }

    /**
     * Structured data for search engines, on the home page of each language: who the site is about
     * (a Person with a role and optional profile links) and the website itself. Printed only when the
     * owner's name is set (Customizer → "Your name").
     */
    private function printStructuredData(): void
    {
        $author = Options::get('author_name');
        if (!is_front_page() || $author === '') {
            return;
        }

        $home   = home_url('/');
        $person = ['@type' => 'Person', '@id' => $home . '#person', 'name' => $author, 'url' => $home];

        $role = Options::get('author_role');
        if ($role !== '') {
            $person['jobTitle'] = $role;
        }
        $links = array_column(Options::profileLinks(), 'url');
        if ($links !== []) {
            $person['sameAs'] = $links;
        }

        $graph = [
            $person,
            [
                '@type'      => 'WebSite',
                '@id'        => $home . '#website',
                'url'        => $home,
                'name'       => Options::get('brand_name'),
                'inLanguage' => get_bloginfo('language'),
                'publisher'  => ['@id' => $home . '#person'],
            ],
        ];

        echo '<script type="application/ld+json">'
            . wp_json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
            . "</script>\n";
    }

    /**
     * The page's description: its excerpt, a term description, or the site tagline.
     */
    private function description(): string
    {
        $text = '';
        if (SeoMeta::currentPostId() > 0) {
            $postId = SeoMeta::currentPostId();
            $own    = SeoMeta::get($postId, SeoMeta::DESCRIPTION);
            $text   = $own !== '' ? $own : (string) get_post_field('post_excerpt', $postId);
        } elseif (is_category() || is_tax()) {
            $text = term_description();
        }

        $text = wp_strip_all_tags($text !== '' ? $text : (string) get_bloginfo('description'));

        return trim((string) preg_replace('/\s+/', ' ', $text));
    }

    private function url(): string
    {
        return is_singular() ? (string) get_permalink() : home_url(add_query_arg([]));
    }

    /**
     * Sharing image: the featured image of the page, else the site logo, else the site icon.
     */
    private function image(): string
    {
        if (is_singular() && Project::imageExists((int) get_the_ID())) {
            return (string) get_the_post_thumbnail_url(null, 'large');
        }

        $logo = (int) get_theme_mod('custom_logo');
        if ($logo > 0) {
            return (string) wp_get_attachment_image_url($logo, 'full');
        }

        return (string) get_site_icon_url(512);
    }

    public static function seoPluginActive(): bool
    {
        return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('SEOPRESS_VERSION') || defined('AIOSEO_VERSION');
    }
}

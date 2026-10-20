<?php
/**
 * The page <title>: "<page> | <your name>".
 *
 * A page can set its own title (SeoMeta::TITLE). Without one, the title is built from the page's
 * name and the owner's name (Customizer → "Your name"). The home page reads "<name> | <tagline>".
 * Nothing changes while the name is empty, so the theme stays usable on any site.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Setup;

use Rashid\Content\SeoMeta;
use Rashid\Module;
use Rashid\Settings\Options;

final class SeoTitles implements Module
{
    private const SEPARATOR = ' | ';

    public function register(): void
    {
        // Late, so it wins over the default title WordPress would build.
        add_filter('pre_get_document_title', [$this, 'title'], 20);
    }

    public function title(string $title): string
    {
        // An SEO plugin writes its own titles.
        if (MetaTags::seoPluginActive()) {
            return $title;
        }

        $postId = SeoMeta::currentPostId();
        if ($postId > 0) {
            $own = SeoMeta::get($postId, SeoMeta::TITLE);
            if ($own !== '') {
                return $own;
            }
        }

        $author = Options::get('author_name');
        if ($author === '') {
            return $title;
        }

        if (is_front_page()) {
            $tagline = (string) get_bloginfo('description');

            return $tagline === '' ? $author : $author . self::SEPARATOR . $tagline;
        }

        $name = $this->pageName();

        return $name === '' ? $title : $name . self::SEPARATOR . $author;
    }

    /** The name of what is being shown: a page or project, an archive, a term, or the 404. */
    private function pageName(): string
    {
        if (is_404()) {
            return __('Page not found', 'rashid');
        }
        if (is_singular() || SeoMeta::currentPostId() > 0) {
            return wp_strip_all_tags(get_the_title(SeoMeta::currentPostId()));
        }
        if (is_post_type_archive()) {
            return (string) post_type_archive_title('', false);
        }
        if (is_tax() || is_category() || is_tag()) {
            return (string) single_term_title('', false);
        }

        return '';
    }
}

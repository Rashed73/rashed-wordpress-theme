<?php
/**
 * Front-end styles and scripts.
 *
 * The files enqueued here are built from assets/css/src and assets/js/src by
 * bin/build-assets.sh; never edit the built files by hand.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Setup;

use Rashid\Module;

final class Assets implements Module
{
    private const FONT_LATIN  = 'plex-sans-latin.woff2';
    private const FONT_ARABIC = 'plex-sans-arabic-arabic-400.woff2';

    /** The header's colour (the --ink token in 00-tokens.css), for the phone's browser bar. */
    private const THEME_COLOR = '#0A1020';

    public function register(): void
    {
        // Priority 0 so the "js" class is on <html> before any CSS paints.
        add_action('wp_head', [$this, 'markJsEnabled'], 0);
        add_action('wp_head', [$this, 'preloadFonts'], 1);
        add_action('wp_head', [$this, 'themeColor'], 1);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        $this->enqueueStyles();
        wp_enqueue_script(
            'rashid-main',
            RASHID_URI . '/assets/js/main.js',
            [],
            $this->version('assets/js/main.js'),
            ['in_footer' => true, 'strategy' => 'defer']
        );
    }

    /**
     * The stylesheet is small (about 8 KB compressed), so it is printed in the page head instead of
     * being fetched as a file: the browser can start drawing at once, without waiting for a second
     * request. If the file cannot be read, or a filter turns this off, it is linked as a normal file.
     */
    private function enqueueStyles(): void
    {
        $css = apply_filters('rashid_inline_css', true) ? $this->inlineCss() : '';

        if ($css === '') {
            wp_enqueue_style('rashid-main', RASHID_URI . '/assets/css/main.css', [], $this->version('assets/css/main.css'));
            return;
        }

        wp_register_style('rashid-main', false, [], null);
        wp_enqueue_style('rashid-main');
        wp_add_inline_style('rashid-main', $css);
    }

    /**
     * The built stylesheet with its font addresses made absolute (they are relative to the file).
     */
    private function inlineCss(): string
    {
        $css = @file_get_contents(RASHID_DIR . '/assets/css/main.css');

        return $css === false ? '' : str_replace('../fonts/', RASHID_URI . '/assets/fonts/', $css);
    }

    /**
     * Version string for a built file: the theme version plus the file's modified time, so a
     * rebuilt file is never served from a browser or CDN cache under the old address.
     */
    private function version(string $file): string
    {
        $time = @filemtime(RASHID_DIR . '/' . $file);

        return $time ? RASHID_VERSION . '.' . $time : RASHID_VERSION;
    }

    /**
     * Lets the CSS enhance the mobile menu only when JavaScript can open it.
     */
    public function markJsEnabled(): void
    {
        echo '<script>document.documentElement.classList.add("js");</script>' . "\n";
    }

    /**
     * Tints the browser bar of a phone like the header, so the top of the page looks like one piece.
     */
    public function themeColor(): void
    {
        printf('<meta name="theme-color" content="%s">' . "\n", esc_attr(self::THEME_COLOR));
    }

    /**
     * Preload the fonts used above the fold in the current direction.
     */
    public function preloadFonts(): void
    {
        $files = is_rtl() ? [self::FONT_LATIN, self::FONT_ARABIC] : [self::FONT_LATIN];

        foreach ($files as $file) {
            printf(
                '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
                esc_url(RASHID_URI . '/assets/fonts/' . $file)
            );
        }
    }
}

<?php
/**
 * The site logo uploaded under Appearance → Customize → Site Identity.
 *
 * The header and footer are dark, so the uploaded logo should be the light
 * (white) version. When no logo is set, the brand template falls back to the
 * text mark and name.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\View;

final class Logo
{
    /**
     * Markup of the uploaded logo image, or an empty string when none is set.
     */
    public static function image(): string
    {
        $id = (int) get_theme_mod('custom_logo');
        if ($id === 0) {
            return '';
        }

        return wp_get_attachment_image($id, 'full', false, [
            'class'    => 'brand__logo',
            'alt'      => get_bloginfo('name'),
            'decoding' => 'async',
            // Shown about 144 px wide: without this the browser downloads the 640 px file.
            'sizes'    => '(min-width: 900px) 160px, 144px',
        ]);
    }
}

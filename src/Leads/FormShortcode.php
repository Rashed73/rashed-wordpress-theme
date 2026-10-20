<?php
/**
 * The lead form as a shortcode: [rashid_lead_form source="contact" button="Send"].
 *
 * `source` names the form (it is saved with every lead, so the dashboard shows
 * which form a request came from). Templates call rashid_lead_form() instead.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use Rashid\Module;

final class FormShortcode implements Module
{
    public const TAG = 'rashid_lead_form';

    public function register(): void
    {
        add_shortcode(self::TAG, [$this, 'render']);
    }

    /**
     * @param array<string, string>|string $attributes
     */
    public function render(array|string $attributes = []): string
    {
        return self::html(is_array($attributes) ? $attributes : []);
    }

    /**
     * The form's markup. Used by the shortcode and by rashid_lead_form().
     *
     * @param array<string, string> $attributes source and button label.
     */
    public static function html(array $attributes = []): string
    {
        $args = array_merge([
            'source' => 'contact',
            'button' => __('Send message', 'rashid'),
        ], $attributes);

        $args['source'] = sanitize_key($args['source']) ?: 'contact';

        ob_start();
        get_template_part('template-parts/form/lead-form', null, $args);

        return (string) ob_get_clean();
    }
}

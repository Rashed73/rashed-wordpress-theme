<?php
/**
 * Where lead notifications are sent, and the Customizer setting for it.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use Rashid\Module;
use WP_Customize_Manager;

final class Recipient implements Module
{
    private const SETTING = 'rashid_lead_email';
    private const SECTION = 'rashid_leads';

    public function register(): void
    {
        add_action('customize_register', [$this, 'addSetting']);
    }

    /**
     * Notification address: the Customizer value, else the site admin email.
     */
    public static function email(): string
    {
        $email = sanitize_email((string) get_theme_mod(self::SETTING, ''));

        return is_email($email) ? $email : (string) get_option('admin_email');
    }

    public function addSetting(WP_Customize_Manager $customizer): void
    {
        $customizer->add_section(self::SECTION, [
            'title'    => __('Contact form', 'rashid'),
            'priority' => 31,
        ]);

        $customizer->add_setting(self::SETTING, ['default' => '', 'sanitize_callback' => 'sanitize_email']);
        $customizer->add_control(self::SETTING, [
            'section'     => self::SECTION,
            'label'       => __('Send new requests to this email', 'rashid'),
            'description' => __('Leave empty to use the site administrator email.', 'rashid'),
            'type'        => 'email',
        ]);
    }
}

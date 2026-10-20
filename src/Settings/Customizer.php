<?php
/**
 * Adds the theme's options to Appearance → Customize.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Settings;

use Rashid\Module;
use WP_Customize_Manager;

final class Customizer implements Module
{
    private const SECTION = 'rashid_site';

    public function register(): void
    {
        add_action('customize_register', [$this, 'addSection']);
    }

    public function addSection(WP_Customize_Manager $customizer): void
    {
        $customizer->add_section(self::SECTION, [
            'title'    => __('Brand and call to action', 'rashid'),
            'priority' => 30,
        ]);

        foreach (Options::definitions() as $key => $definition) {
            $this->addControl($customizer, $key, $definition);
        }
    }

    /**
     * @param array{label: string, default: string, input: string} $definition
     */
    private function addControl(WP_Customize_Manager $customizer, string $key, array $definition): void
    {
        $id = Options::PREFIX . $key;

        $customizer->add_setting($id, [
            'default'           => $definition['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $customizer->add_control($id, [
            'section' => self::SECTION,
            'label'   => $definition['label'],
            'type'    => $definition['input'],
        ]);
    }
}

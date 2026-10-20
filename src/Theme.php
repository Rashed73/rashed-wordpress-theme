<?php
/**
 * The theme's composition root: the single list of modules that make it up.
 *
 * To add a feature, write a Module and list it here. Order only matters where a
 * module depends on another having hooked first, which none currently does.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid;

final class Theme
{
    /**
     * Register every module.
     */
    public static function boot(): void
    {
        foreach (self::modules() as $module) {
            $module->register();
        }
    }

    /**
     * @return Module[]
     */
    private static function modules(): array
    {
        return [
            new Setup\ThemeSupport(),
            new Setup\Assets(),
            new Setup\Cleanup(),
            new Setup\MetaTags(),
            new Setup\SeoTitles(),
            new Settings\Customizer(),
            new Content\ProjectPostType(),
            new Content\ProjectMeta(),
            new Content\ProjectDefaults(),
            new Content\MoreLinks(),
            new Content\ArticleToc(),
            new Content\BlogQuery(),
            new Content\ProjectQuery(),
            new Admin\ProjectPanel(),
            new Admin\SeoPanel(),
            new Admin\ProjectColumns(),
            new Blocks\FeaturedProjectsBlock(),
            new Leads\LeadPostType(),
            new Leads\SubmissionHandler(),
            new Leads\FormShortcode(),
            new Leads\Recipient(),
            new Leads\Admin\LeadDetailsBox(),
            new Leads\Admin\LeadColumns(),
            new Leads\Admin\LeadMenuBadge(),
            new Leads\Admin\LeadExport(),
            new I18n\Polylang(),
        ];
    }
}

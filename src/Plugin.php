<?php

/**
 * Plugin.php - Procedural part of Test Mode.
 *
 * @author Viktor Szépe <viktor@szepe.net>
 * @license GNU General Public License v2 or later
 * @link https://github.com/szepeviktor/test-mode
 */

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use function current_user_can;
use function esc_html__;
use function esc_url;
use function is_admin;
use function load_plugin_textdomain;
use function wp_doing_ajax;

/**
 * Plugin functions.
 */
class Plugin
{
    private function __construct()
    {
    }

    /**
     * @return void
     */
    public static function loadTextDomain()
    {
        /** @var string */
        $pluginBasename = Config::get('baseName');
        load_plugin_textdomain('szv-test-mode', false, sprintf('%s/%s', dirname($pluginBasename), 'languages'));
    }

    /**
     * @return void
     */
    public static function activate()
    {
        // Run database migrations, initialize WordPress options etc.
    }

    /**
     * @return void
     */
    public static function deactivate()
    {
        // Do something related to deactivation.
    }

    /**
     * @return void
     */
    public static function uninstall()
    {
        foreach (ModuleLoader::getInstances() as $instance) {
            delete_option(AdminPage::OPTION_PREFIX.$instance->getSlug());
        }
    }

    /**
     * @return void
     */
    public static function printRequirementsNotice()
    {
        // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
        error_log('Test Mode requirements are not met. Please read the Installation instructions.');

        if (! current_user_can('activate_plugins')) {
            return;
        }

        printf(
            '<div class="notice notice-error"><p>%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s</p></div>',
            esc_html__('Test Mode activation failed! Please read', 'szv-test-mode'),
            esc_url('https://github.com/szepeviktor/test-mode#installation'),
            esc_html__('the Installation instructions', 'szv-test-mode'),
            esc_html__('for list of requirements.', 'szv-test-mode')
        );
    }

    /**
     * Start!
     */
    public static function boot(): void
    {
        foreach (ModuleLoader::getInstances() as $instance) {
            $instance->activate();
        }

        (new AdminBar())->boot();

        if (is_admin() && ! wp_doing_ajax()) {
            (new DashboardWidget())->boot();
            (new AdminPage())->boot();
        }
    }
}

<?php

/**
 * Plugin.php - Procedural part of Plugin Name.
 *
 * @author Your Name <username@example.com>
 * @license GPL-2.0-or-later http://www.gnu.org/licenses/gpl-2.0.txt
 * @link https://example.com/plugin-name
 */

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use function current_user_can;
use function esc_html__;
use function esc_url;
use function is_admin;
use function load_plugin_textdomain;

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
        load_plugin_textdomain('plugin-slug', false, sprintf('%s/%s', dirname($pluginBasename), 'languages'));
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
        error_log('Plugin Name requirements are not met. Please read the Installation instructions.');

        if (! current_user_can('activate_plugins')) {
            return;
        }

        printf(
            '<div class="notice notice-error"><p>%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s</p></div>',
            esc_html__('Plugin Name activation failed! Please read', 'plugin-slug'),
            esc_url('https://github.com/szepeviktor/starter-plugin#installation'),
            esc_html__('the Installation instructions', 'plugin-slug'),
            esc_html__('for list of requirements.', 'plugin-slug')
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

        if (is_admin()) {
            (new DashboardWidget())->boot();
            (new AdminPage())->boot();
        }
    }
}

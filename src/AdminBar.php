<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use WP_Admin_Bar;

use function __;
use function add_action;
use function current_user_can;
use function esc_html;
use function get_option;
use function is_admin_bar_showing;
use function plugins_url;
use function sanitize_html_class;
use function wp_enqueue_style;
use function wp_get_environment_type;

class AdminBar
{
    public function boot(): void
    {
        add_action('admin_bar_menu', [$this, 'render'], 7, 1);
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles'], 10, 0);
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 10, 0);
    }

    public function enqueueStyles(): void
    {
        if (! is_admin_bar_showing() || ! current_user_can('edit_posts')) {
            return;
        }

        wp_enqueue_style(
            'test-mode-admin-bar',
            plugins_url('css/admin-bar.css', dirname(__DIR__).'/test-mode.php'),
            ['admin-bar'],
            null
        );
    }

    public function render(WP_Admin_Bar $adminBar): void
    {
        if (! is_admin_bar_showing() || ! current_user_can('edit_posts')) {
            return;
        }

        $modes = [
            ModuleLoader::MODE_NOCHANGE => [__('No change', 'szv-test-mode'), '➖'],
            ModuleLoader::MODE_TESTMODE => [__('Test mode', 'szv-test-mode'), '🚧'],
            ModuleLoader::MODE_DISABLED => [__('Disabled', 'szv-test-mode'), '🚫'],
        ];
        $environment = wp_get_environment_type();
        $environmentNames = [
            'local' => __('Local', 'szv-test-mode'),
            'development' => __('Development', 'szv-test-mode'),
            'staging' => __('Staging', 'szv-test-mode'),
            'production' => __('Production', 'szv-test-mode'),
        ];

        $adminBar->add_node([
            'id' => 'test-mode',
            'parent' => 'top-secondary',
            'title' => sprintf(
                '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">%s</span>',
                esc_html($environmentNames[$environment] ?? $environment)
            ),
            'meta' => [
                'class' => 'test-mode-environment-'.sanitize_html_class($environment),
            ],
        ]);

        foreach (ModuleLoader::getInstances() as $module) {
            $mode = get_option(
                AdminPage::OPTION_PREFIX.$module->getSlug(),
                ModuleLoader::MODE_NOCHANGE
            );
            [$modeName, $emoji] = $modes[$mode] ?? $modes[ModuleLoader::MODE_NOCHANGE];

            $adminBar->add_node([
                'id' => 'test-mode-'.$module->getSlug(),
                'parent' => 'test-mode',
                'title' => esc_html(sprintf('%s %s', $module->getName(), $emoji)),
                'meta' => [
                    'title' => esc_html(sprintf('%s: %s', $module->getName(), $modeName)),
                ],
            ]);
        }
    }
}

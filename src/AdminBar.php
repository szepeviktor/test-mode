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
use function is_string;
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
            '1.0.0'
        );
    }

    public function render(WP_Admin_Bar $adminBar): void
    {
        if (! is_admin_bar_showing() || ! current_user_can('edit_posts')) {
            return;
        }

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
                ModuleMode::NO_CHANGE
            );
            $modeData = ModuleMode::get(is_string($mode) ? $mode : ModuleMode::NO_CHANGE);

            $adminBar->add_node([
                'id' => 'test-mode-'.$module->getSlug(),
                'parent' => 'test-mode',
                'title' => sprintf(
                    '%s %s',
                    esc_html($module->getName()),
                    $modeData['emoji']
                ),
                'meta' => [
                    'title' => esc_html(sprintf(
                        '%s: %s',
                        $module->getName(),
                        $modeData['label']
                    )),
                ],
            ]);
        }
    }
}

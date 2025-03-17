<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use function add_action;
use function wp_add_dashboard_widget;

class DashboardWidget
{

    public function boot(): void
    {
        add_action('wp_dashboard_setup', [$this, 'addDashboardWidget'], 10, 0);
    }

    public function addDashboardWidget()
    {
        wp_add_dashboard_widget(
            'testmode_widget',
            'Status', // widget_name
            [$this, 'renderDashboardWidget'],
            null,
            null,
            'normal', // context
            'high' // priority
        );
    }

    public function renderDashboardWidget($post, $callback_args)
    {
        echo '<div class="main"><ul>';
        printf(
            '<li class="os"><pre><code>%s@%s:%s</code></pre></li>',
            get_current_user(),
            gethostname(),
            ABSPATH
        );
        printf(
            '<li class="core">PHP %s %s, WP %s-%s %s env @%s, debug %s</li>',
            esc_html(php_sapi_name()),
            esc_html(phpversion()),
            esc_html(get_bloginfo('version')),
            esc_html(get_locale()),
            esc_html(wp_get_environment_type()),
            esc_html(date_default_timezone_get()),
            (defined('WP_DEBUG') && WP_DEBUG) ? 'ON' : 'off'
        );
        echo '</ul></div>';
    }
}

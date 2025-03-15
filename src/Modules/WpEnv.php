<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

/**
 * See wp_get_environment_type() in wp-includes/load.php
 */
class WpEnv extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'WP Environment';
    }

    public function getLabel(): string
    {
        return 'Set WordPress environment type to staging or local.';
    }

    public function testmode(): void
    {
        putenv('WP_ENVIRONMENT_TYPE=staging');
        if (!defined('WP_ENVIRONMENT_TYPE')) {
            define('WP_ENVIRONMENT_TYPE', 'staging');
        }
    }

    public function disabled(): void
    {
        putenv('WP_ENVIRONMENT_TYPE=local');
        if (!defined('WP_ENVIRONMENT_TYPE')) {
            define('WP_ENVIRONMENT_TYPE', 'local');
        }
    }
}

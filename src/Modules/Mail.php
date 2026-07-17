<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use function add_filter;
use function constant;
use function defined;
use function get_bloginfo;

class Mail extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'Mail';
    }

    public function getLabel(): string
    {
        return 'Send all mail to admin or disable mail sending.';
    }

    public function testmode(): void
    {
        add_filter(
            'wp_mail',
            static function ($args) {
                $args['to'] = get_bloginfo('admin_email');

                return $args;
            },
            PHP_INT_MAX,
            1
        );
    }

    public function disabled(): void
    {
        if (defined('FLUENTMAIL_PLUGIN_VERSION')) {
            if (! defined('FLUENTMAIL_SIMULATE_EMAILS')) {
                define('FLUENTMAIL_SIMULATE_EMAILS', true);

                return;
            }

            if (constant('FLUENTMAIL_SIMULATE_EMAILS') === true) {
                return;
            }
        }

        add_filter(
            'pre_wp_mail',
            '__return_false',
            10,
            0
        );
    }
}

<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use function add_filter;
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
            10,
            PHP_INT_MAX
        );
    }

    public function disabled(): void
    {
        add_filter(
            'pre_wp_mail',
            '__return_false',
            10,
            0
        );
    }
}

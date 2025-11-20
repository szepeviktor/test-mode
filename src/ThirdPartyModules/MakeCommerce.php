<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\ThirdPartyModules;

use SzepeViktor\TestMode\Modules\BaseModule;
use SzepeViktor\TestMode\Modules\Module;

use function add_filter;

class MakeCommerce extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'MakeCommerce payment gateway';
    }

    public function getLabel(): string
    {
        return 'Use sandbox, or do not start at all.';
    }

    public function testmode(): void
    {
        add_filter(
            'pre_option_'.'mc_api_mode',
            static function () {
                return 'test';
            },
            PHP_INT_MAX,
            0
        );
    }

    public function disabled(): void
    {
        add_filter(
            'pre_option_'.'mc_api_mode',
            '__return_null',
            PHP_INT_MAX,
            0
        );
    }
}

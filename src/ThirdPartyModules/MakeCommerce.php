<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\ThirdPartyModules;

use SzepeViktor\TestMode\Modules\BaseModule;
use SzepeViktor\TestMode\Modules\Module;
use WC_Shipping_Rate;

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
            'woocommerce_available_payment_gateways',
            static function (array $gateways): array {
                unset($gateways['makecommerce']);

                return $gateways;
            },
            PHP_INT_MAX,
            1
        );

        add_filter(
            'woocommerce_package_rates',
            static function (array $rates): array {
                foreach ($rates as $rateId => $rate) {
                    if ($rate instanceof WC_Shipping_Rate && $rate->get_method_id() === 'makecommerce_shipping') {
                        unset($rates[$rateId]);
                    }
                }

                return $rates;
            },
            PHP_INT_MAX,
            1
        );
    }
}

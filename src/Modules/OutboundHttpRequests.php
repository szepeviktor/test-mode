<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use WP_Error;

use function add_filter;
use function apply_filters;
use function home_url;
use function is_string;
use function strcasecmp;
use function wp_parse_url;

class OutboundHttpRequests extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'Outbound HTTP requests';
    }

    public function getLabel(): string
    {
        return 'Allow only approved hosts in test mode or disable all outbound HTTP requests.';
    }

    public function testmode(): void
    {
        add_filter(
            'pre_http_request',
            static function ($response, array $arguments, string $url) {
                // A preempted request will not access the network.
                if ($response !== false) {
                    return $response;
                }

                $requestHost = wp_parse_url($url, PHP_URL_HOST);
                $homeHost = wp_parse_url(home_url(), PHP_URL_HOST);
                $isSameSite = is_string($requestHost)
                    && is_string($homeHost)
                    && strcasecmp($requestHost, $homeHost) === 0;

                /**
                 * Allow selected HTTP requests in test mode.
                 *
                 * @param bool $allowed Whether the request is allowed.
                 * @param string $url Request URL.
                 * @param array $arguments WordPress HTTP API arguments.
                 */
                $allowed = apply_filters(
                    'szepeviktor/test-mode/http-request-allowed',
                    $isSameSite,
                    $url,
                    $arguments
                );

                if ($allowed === true) {
                    return false;
                }

                return new WP_Error(
                    'test_mode_http_request_blocked',
                    'Test Mode blocked an outbound HTTP request.'
                );
            },
            PHP_INT_MAX,
            3
        );
    }

    public function disabled(): void
    {
        add_filter(
            'pre_http_request',
            static function ($response) {
                // A preempted request will not access the network.
                if ($response !== false) {
                    return $response;
                }

                return new WP_Error(
                    'test_mode_http_requests_disabled',
                    'Outbound HTTP requests are disabled.'
                );
            },
            PHP_INT_MAX,
            1
        );
    }
}

<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use function __;

final class ModuleMode
{
    const NO_CHANGE = 'no-change';
    const TEST_MODE = 'testmode';
    const DISABLED = 'disabled';

    /**
     * @return array<string, array{label: string, emoji: string}>
     */
    public static function all(): array
    {
        return [
            self::NO_CHANGE => [
                'label' => __('No change', 'szv-test-mode'),
                'emoji' => '&#x2796;',
            ],
            self::TEST_MODE => [
                'label' => __('Test mode', 'szv-test-mode'),
                'emoji' => '&#x1F6A7;',
            ],
            self::DISABLED => [
                'label' => __('Disabled', 'szv-test-mode'),
                'emoji' => '&#x1F6AB;',
            ],
        ];
    }

    /**
     * @return array{label: string, emoji: string}
     */
    public static function get(string $mode): array
    {
        $modes = self::all();

        return $modes[$mode] ?? $modes[self::NO_CHANGE];
    }
}

<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Support;

class Strings
{
    /**
     * Polyfill for PHP 8 mb_ucfirst()
     */
    public static function mb_ucfirst(string $string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)).mb_substr($string, 1);
    }
}

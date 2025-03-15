<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Support;

class Strings
{
    public static function mb_ucfirst(string $string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)).mb_substr($string, 1);
    }
}

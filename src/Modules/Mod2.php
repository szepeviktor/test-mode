<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use SzepeViktor\TestMode\Support\Autolabel;

class Mod2 implements Module
{
    use Autolabel;

    public function getName(): string
    {
        return 'John Monitor';
    }

    public function run(): void
    {
        echo 'SB';
    }
}

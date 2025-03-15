<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use SzepeViktor\TestMode\Support\Autoname;

class Mod1 implements Module
{
    use Autoname;

    public function getLabel(): string
    {
        return 'Put this in sandbox mode';
    }

    public function run(): void
    {
        echo 'SB';
    }
}

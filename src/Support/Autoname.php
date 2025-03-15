<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Support;

use SzepeViktor\TestMode\ModuleLoader;

trait Autoname
{
    public function getName(): string
    {
        return substr(self::class, strlen(ModuleLoader::MODULE_NAMESPACE));
    }
}

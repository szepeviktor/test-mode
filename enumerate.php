<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use Composer\Autoload\ClassLoader;
use RuntimeException;
use SzepeViktor\TestMode\Modules\Module;

const MODULE_NAMESPACE = 'SzepeViktor\\TestMode\\Modules\\';

function getLoader(): ClassLoader
{
    return require __DIR__ . '/vendor/autoload.php';
}

function getModules(): array
{
    $classes = array_keys(getLoader()->getClassMap());

    if ($classes === []) {
        throw new RuntimeException('Run composer dump-autoload --optimize');
    }

    return array_filter(
        $classes,
        static function (string $fqcn): bool {
            return str_starts_with($fqcn, MODULE_NAMESPACE);
        }
    );
}

function bootModules(): void
{
    foreach (getModules() as $module) {
        if (!in_array(Module::class, class_implements($module, true), true)) {
            continue;
        }

        new $module;
    }
}

bootModules();

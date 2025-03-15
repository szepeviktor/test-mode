<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use RuntimeException;
use SzepeViktor\TestMode\Modules\Module;

use function apply_filters;

class ModuleLoader
{
    const MODULE_NAMESPACE = 'SzepeViktor\\TestMode\\Modules\\';
    const MODE_NOCHANGE = 'no-change';
    const MODE_TESTMODE = 'testmode';
    const MODE_DISABLED = 'disabled';

    /**
     * @return list<class-string>
     */
    public static function getBuiltins(): array
    {
        /** @var list<class-string> $classes */
        $classes = array_keys(getLoader()->getClassMap());

        if ($classes === []) {
            throw new RuntimeException('Run composer dump-autoload --optimize');
        }

        return array_filter(
            $classes,
            static function (string $fqcn): bool {
                return str_starts_with($fqcn, self::MODULE_NAMESPACE)
                    && $fqcn !== Module::class;
            }
        );
    }

    /**
     * @return list<class-string>
     */
    public static function getAll(): array
    {
        $modules = apply_filters('szepeviktor/test-mode/modules', self::getBuiltins());

        return array_filter(
            $modules,
            static function (string $module): bool {
                return in_array(Module::class, class_implements($module), true);
            }
        );
    }

    public static function getInstances(): array
    {
        static $container;

        if (!is_array($container)) {
            foreach (self::getAll() as $module) {
                $container[$module] = new $module();
                $container[$module]->activate();
            }
        }

        return $container;
    }
}

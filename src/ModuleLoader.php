<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use MakeCommerce;
use RuntimeException;
use SzepeViktor\TestMode\Modules\Module;
use SzepeViktor\TestMode\ThirdPartyModules;

use function apply_filters;

class ModuleLoader
{
    const MODULE_NAMESPACE = 'SzepeViktor\\TestMode\\Modules\\';

    /**
     * @return list<class-string>
     */
    public static function getBuiltins(): array
    {
        /** @var list<class-string> $classes */
        $classes = array_keys(getLoader()->getClassMap());

        if ($classes === []) {
            throw new RuntimeException('Run composer dump-autoload --no-dev --optimize');
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
    public static function getThirdParties(): array
    {
        $modules = [];

        if (class_exists(MakeCommerce::class)) {
            $modules[] = ThirdPartyModules\MakeCommerce::class;
        }

        return $modules;
    }

    /**
     * @return list<class-string>
     */
    public static function getAll(): array
    {
        $modules = apply_filters(
            'szepeviktor/test-mode/modules',
            array_merge(self::getBuiltins(), self::getThirdParties())
        );

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
            $container = [];
            foreach (self::getAll() as $module) {
                $container[$module] = new $module();
            }
        }

        return $container;
    }
}

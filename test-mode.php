<?php

/**
 * Test Mode
 *
 * @author            Your Name <username@example.com>
 * @license           GPL-2.0-or-later http://www.gnu.org/licenses/gpl-2.0.txt
 * @link              https://github.com/szepeviktor/starter-plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Test Mode
 * Plugin URI:        https://github.com/szepeviktor/test-mode
 * Description:       I want to be 🤖 a plugin to switch everything to test/sandbox mode
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Viktor Szépe
 * Author URI:        https://github.com/szepeviktor
 * Text Domain:       szv-test-mode
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use Composer\Autoload\ClassLoader;
use RuntimeException;
use SzepeViktor\TestMode\Modules\Module;

const MODULE_NAMESPACE = 'SzepeViktor\\TestMode\\Modules\\';

function getLoader(): ClassLoader
{
    static $loader;

    if (!$loader instanceof ClassLoader) {
        $loader = require __DIR__ . '/vendor/autoload.php';
    }

    return $loader;
}

/**
 * @return list<class-string>
 */
function getModules(): array
{
    /** @var list<class-string> $classes */
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

// Prevent direct execution.
if (! defined('ABSPATH')) {
    exit;
}

getLoader();

add_action('plugins_loaded', [Plugin::class, 'boot'], 10, 0);

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

function getLoader(): ClassLoader
{
    static $loader;

    if (!$loader instanceof ClassLoader) {
        $loader = require __DIR__ . '/vendor/autoload.php';
    }

    return $loader;
}

// Prevent direct execution.
if (! defined('ABSPATH')) {
    exit;
}

getLoader();

// Boot after priority 10
add_action('plugins_loaded', [Plugin::class, 'boot'], 11, 0);

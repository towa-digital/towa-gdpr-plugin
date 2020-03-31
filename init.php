<?php

/**
 * Initialise the plugin and set up necessary variables
 */

declare(strict_types=1);

namespace Towa\GdprPlugin;

use BrightNucleus\Config\ConfigFactory;
use Towa\GdprPlugin\Helper\PluginHelper;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

if (!defined('TOWA_GDPR_PLUGIN_DIR')) {
    define('TOWA_GDPR_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('TOWA_GDPR_PLUGIN_URL')) {
    define('TOWA_GDPR_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('TOWA_GDPR_PLUGIN_FILE')) {
    define('TOWA_GDPR_PLUGIN_FILE', plugin_dir_path(__FILE__) . 'towa-gdpr-plugin.php');
}

if (!defined('TOWA_GDPR_PLUGIN_VERSION')) {
    define('TOWA_GDPR_PLUGIN_VERSION', '1.1.3');
}

// Load Composer autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if (!defined('TOWA_GDPR_DATA')) {
    define('TOWA_GDPR_DATA', PluginHelper::getDataPath());
}

// Initialize the plugin.
add_action(
    'plugins_loaded',
    function () {
        $plugin = new Plugin(ConfigFactory::create(__DIR__ . '/config/defaults.php')->getSubConfig('Towa\GdprPlugin'));
        $plugin->run();
    },
    10,
    0
);

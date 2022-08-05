<?php

/**
 * Initialise the plugin and set up necessary variables
 */

declare(strict_types=1);

namespace Towa\GdprPlugin;

use BrightNucleus\Config\ConfigFactory;
use Towa\GdprPlugin\Helper\PluginHelper;

// If this file is called directly, abort.
// phpcs:disable PSR1.Files.SideEffects
if (!defined('WPINC')) {
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
    define('TOWA_GDPR_PLUGIN_VERSION', '1.2.0');
}

// Load Composer autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if (!defined('TOWA_GDPR_DATA')) {
    define('TOWA_GDPR_DATA', PluginHelper::getDataPath());
}

// Initialize the plugin.
$GLOBALS['towa_gdpr_plugin'] = new Plugin(ConfigFactory::create(__DIR__ . '/config/defaults.php')
    ->getSubConfig('Towa\GdprPlugin'));
$GLOBALS['towa_gdpr_plugin']->run();

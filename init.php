<?php
/**
 * Initialise the plugin
 *
 * This file can use syntax from the required level of PHP or later.
 *
 * @package      Towa\DsgvoPlugin
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin;

use BrightNucleus\Config\ConfigFactory;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'TOWA_DSGVO_PLUGIN_DIR' ) ) {
	// phpcs:ignore NeutronStandard.Constants.DisallowDefine.Define
	define( 'TOWA_DSGVO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'TOWA_DSGVO_PLUGIN_URL' ) ) {
	// phpcs:ignore NeutronStandard.Constants.DisallowDefine.Define
	define( 'TOWA_DSGVO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Load Composer autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin.
$GLOBALS['towa_dsgvo_plugin'] = new Plugin( ConfigFactory::create( __DIR__ . '/config/defaults.php' )->getSubConfig( 'Towa\DsgvoPlugin' ) );
$GLOBALS['towa_dsgvo_plugin']->run();

<?php
/**
 * PHPUnit bootstrap
 *
 * @package      Towa\GdprPlugin\Tests
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\GdprPlugin\Tests;

// Check for a `--testsuite integration` arg when calling phpunit, and use it to conditionally load up WordPress.
$towa_gdpr_plugin_argv = $GLOBALS['argv'];
$towa_gdpr_plugin_key  = (int) array_search( '--testsuite', $towa_gdpr_plugin_argv, true );

if ( $towa_gdpr_plugin_key && 'integration' === $towa_gdpr_plugin_argv[ $towa_gdpr_plugin_key + 1 ] ) {
	$towa_gdpr_plugin_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( ! $towa_gdpr_plugin_tests_dir ) {
		$towa_gdpr_plugin_tests_dir = '/tmp/wordpress-tests-lib';
	}

	// Give access to tests_add_filter() function.
	require_once $towa_gdpr_plugin_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	\tests_add_filter(
		'muplugins_loaded',
		function () {
			require dirname( __DIR__ ) . '/towa-gdpr-plugin.php';
		}
	);

	// Start up the WP testing environment.
	require $towa_gdpr_plugin_tests_dir . '/includes/bootstrap.php';
}

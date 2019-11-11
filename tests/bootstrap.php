<?php
/**
 * PHPUnit bootstrap
 *
 * @package      Towa\DsgvoPlugin\Tests
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin\Tests;

// Check for a `--testsuite integration` arg when calling phpunit, and use it to conditionally load up WordPress.
$towa_dsgvo_plugin_argv = $GLOBALS['argv'];
$towa_dsgvo_plugin_key  = (int) array_search( '--testsuite', $towa_dsgvo_plugin_argv, true );

if ( $towa_dsgvo_plugin_key && 'integration' === $towa_dsgvo_plugin_argv[ $towa_dsgvo_plugin_key + 1 ] ) {
	$towa_dsgvo_plugin_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( ! $towa_dsgvo_plugin_tests_dir ) {
		$towa_dsgvo_plugin_tests_dir = '/tmp/wordpress-tests-lib';
	}

	// Give access to tests_add_filter() function.
	require_once $towa_dsgvo_plugin_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	\tests_add_filter(
		'muplugins_loaded',
		function () {
			require dirname(__DIR__) . '/towa-dsgvo-plugin.php';
		}
	);

	// Start up the WP testing environment.
	require $towa_dsgvo_plugin_tests_dir . '/includes/bootstrap.php';
}

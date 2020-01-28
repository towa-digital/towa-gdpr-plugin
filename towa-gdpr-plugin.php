<?php

/**
 * Plugin Name
 *
 * This file should only use syntax available in PHP 5.2.4 or later.
 *
 * @package      Towa\GdprPlugin
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Towa Gdpr Plugin
 * Plugin URI:        https://bitbucket.org/towa_gmbh/towa-gdpr-plugin/
 * Description:       GDPR conform Plugin to control custom Javscript snippets in Frontend.
 * Version:           1.0.4
 * Author:            Martin Welte
 * Author URI:        https://www.towa-digital.com/
 * Text Domain:       towa-gdpr-plugin
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Bitbucket Plugin URI: https://bitbucket.org/towa_gmbh/towa-gdpr-plugin/
 * Requires PHP:      7.2
 * Requires WP:       >=4.7
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( version_compare( PHP_VERSION, '7.1', '<' ) ) {
	add_action( 'plugins_loaded', 'towa_gdpr_plugin_init_deactivation' );

	/**
	 * Initialise deactivation functions.
	 */
	function towa_gdpr_plugin_init_deactivation() {
		if ( current_user_can( 'activate_plugins' ) ) {
			add_action( 'admin_init', 'towa_gdpr_plugin_deactivate' );
			add_action( 'admin_notices', 'towa_gdpr_plugin_deactivation_notice' );
		}
	}

	/**
	 * Deactivate the plugin.
	 */
	function towa_gdpr_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	/**
	 * Show deactivation admin notice.
	 */
	function towa_gdpr_plugin_deactivation_notice() {
		$notice = sprintf(
			// Translators: 1: Required PHP version, 2: Current PHP version.
			'<strong>Towa Gdpr Plugin</strong> requires PHP %1$s to run. This site uses %2$s, so the plugin has been <strong>deactivated</strong>.',
			'7.1',
			PHP_VERSION
		);
		?>
		<div class="updated"><p><?php echo wp_kses_post( $notice ); ?></p></div>
		<?php
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] );
		}
	}

	return false;
}

/**
 * Load plugin initialisation file.
 */
require plugin_dir_path( __FILE__ ) . '/init.php';

<?php

/**
 * Towa Gdpr Plugin
 *
 * This file should only use syntax available in PHP 5.2.4 or later.
 *
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Towa Gdpr Plugin
 * Plugin URI:        https://github.com/towa-digital/towa-gdpr-plugin/
 * Description:       GDPR conform Plugin to control custom Javscript snippets in Frontend.
 * Version:           1.1.3
 * Author:            Martin Welte
 * Author URI:        https://www.towa-digital.com/
 * Text Domain:       towa-gdpr-plugin
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Github Plugin URI: https://github.com/towa-digital/towa-gdpr-plugin/
 * Requires PHP:      7.2
 * Requires WP:       >=4.7
 */

// phpcs:disable PSR1.Files.SideEffects

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


if (version_compare(PHP_VERSION, '7.2', '<')) {
    add_action('plugins_loaded', 'towa_gdpr_plugin_init_deactivation');

    /**
     * Initialise deactivation functions.
     */
    function towa_gdpr_plugin_init_deactivation()
    {
        if (current_user_can('activate_plugins')) {
            add_action('admin_init', 'towa_gdpr_plugin_deactivate');
            add_action('admin_notices', 'towa_gdpr_plugin_deactivation_notice');
        }
    }

    /**
     * Deactivate the plugin.
     */
    function towa_gdpr_plugin_deactivate()
    {
        deactivate_plugins(plugin_basename(__FILE__));
    }

    /**
     * Show deactivation admin notice.
     */
    function towa_gdpr_plugin_deactivation_notice()
    {
        //phpcs:disable Generic.Files.LineLength
        $notice = sprintf(
            '<strong>Towa Gdpr Plugin</strong> requires PHP %1$s to run. This site uses %2$s, so the plugin has been <strong>deactivated</strong>.',
            '7.2',
            PHP_VERSION
        );
        // phpcs:enable
        ?>
        <div class="updated"><p><?php echo wp_kses_post($notice); ?></p></div>
        <?php
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }

    return false;
}

/**
 * Load plugin initialisation file.
 */
require plugin_dir_path(__FILE__) . '/init.php';

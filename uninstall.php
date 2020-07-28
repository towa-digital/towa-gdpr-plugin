<?php

/**
 * Uninstall the Plugin
 *
 * This file is used to deinstall the towa-gdpr-cookie notice Plugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Load plugin initialisation file.
 */
require plugin_dir_path(__FILE__) . '/init.php';

// uninstall complete Plugin
\Towa\GdprPlugin\Plugin::uninstallPlugin();

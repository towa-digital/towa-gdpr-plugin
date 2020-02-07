<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Load plugin initialisation file.
 */
require plugin_dir_path(__FILE__) . '/init.php';
die('test');
\Towa\GdprPlugin\Plugin::uninstallPlugin();

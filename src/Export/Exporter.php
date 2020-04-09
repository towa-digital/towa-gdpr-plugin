<?php

namespace Towa\GdprPlugin\Export;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Exports all Plugin Settings.
 */
class Exporter
{
    /**
     * Name of the export file.
     */
    private const EXPORT_FILENAME = 'towa-gdpr-settings';

    /**
     * Exports all Settings to a .json File.
     * @throws \Exception
     */
    public function exportToJsonFile(): void
    {
        if (! current_user_can('manage_options')) {
            throw new \Exception('Exports can only be made by user with manage_options permissions');
        }

        $pluginSettings = new PluginSettings();

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . self::EXPORT_FILENAME . '-' . date('m-d-Y') . '.json');
        header("Expires: 0");

        echo json_encode($pluginSettings);

        exit;
    }
}

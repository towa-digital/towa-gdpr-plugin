<?php

namespace Towa\GdprPlugin\Export;

// phpcs:disable PSR1.Files.SideEffects
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        $response = new Response(json_encode($pluginSettings), 200, ['Content-Type: application/json; charset=utf-8']);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            self::EXPORT_FILENAME . '-' . date('m-d-Y') . '.json'
        );
        $response->headers->set('Content-Disposition', $disposition);

        $response->send();
    }
}

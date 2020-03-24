<?php

/**
 * Rest Controller File.
 *
 * @author    Martin Welte
 * @copyright 2020 Towa
 * @license   GPL-2.0+
 */

namespace Towa\GdprPlugin\Rest;

use Towa\GdprPlugin\Consentlogger\Consent;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Class Rest.
 */
class Rest
{
    public const TOWA_GDPR_REST_NAMESPACE = 'towa-gdpr/';
    public const CONSENT_ENDPOINT = 'consent/';

    /**
     * register rest endpoints of towa-gdpr-plugin.
     */
    public function registerRestEndpoints(): void
    {
        register_rest_route(self::TOWA_GDPR_REST_NAMESPACE, self::CONSENT_ENDPOINT, [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'logConsent'],
        ]);
    }

    /**
     * Log consent.
     *
     * @throws \League\Csv\CannotInsertRecord
     */
    public function logConsent(WP_REST_Request $request): WP_REST_Response
    {
        $hash = sanitize_key($request->get_param('hash'));
        $config = json_encode($request->get_param('config'));
        $url = sanitize_url($request->get_param('url'));
        try {
            (new Consent($config, $hash, $url))->save();

            return new WP_REST_Response(null, 200);
        } catch (\Exception $e) {
            return new WP_REST_Response(null, 500);
        }
    }
}

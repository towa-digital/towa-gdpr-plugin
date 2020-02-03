<?php

/**
 * Rest Controller File
 *
 * @author    Martin Welte
 * @copyright 2020 Towa
 * @license   GPL-2.0+
 */

namespace Towa\GdprPlugin\Rest;

use Towa\GdprPlugin\Consentlogger\Consent;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Rest
 *
 * @package Towa\GdprPlugin\Rest
 */
class Rest
{
	const TOWA_GDPR_REST_NAMESPACE = 'towa-gdpr/';
	const CONSENT_ENDPOINT = 'consent/';

	/**
	 * hook rest endpoint registration into rest register_api_init
	 *
	 * @return void
	 */
	public function registerRoutes(): void
	{
		add_action('rest_api_init', [$this, 'registerRestEndpoint']);
	}

	/**
	 * register rest endpoints of towa-gdpr-plugin
	 *
	 * @return void
	 */
	public function registerRestEndpoint(): void
	{
		register_rest_route(self::TOWA_GDPR_REST_NAMESPACE, self::CONSENT_ENDPOINT, [
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => [$this, 'logConsent']
		]);
	}

	/**
	 * Log consent
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
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

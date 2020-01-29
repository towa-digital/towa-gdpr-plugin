<?php
/**
 * Created by PhpStorm.
 * User: marti
 * Date: 27.01.2020
 * Time: 19:12
 */

namespace Towa\GdprPlugin\Rest;


use Towa\GdprPlugin\Consentlogger\Consent;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Rest
{
	const TOWA_GDPR_REST_NAMESPACE = 'towa-gdpr/';

	/**
	 * hook rest endpoint registration into rest register_api_init
	 *
	 * @return void
	 */
	public function register_routes(): void{
		add_action('rest_api_init', array($this, 'register_rest_endpoint'));
	}

	/**
	 * register rest endpoints of towa-gdpr-plugin
	 *
	 * @return void
	 */
	public function register_rest_endpoint():void {
		register_rest_route( self::TOWA_GDPR_REST_NAMESPACE ,'consent/', array(
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => array($this,'log_consent')
		));
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function log_consent(WP_REST_Request $request): WP_REST_Response{
		$hash = sanitize_key($request->get_param('hash'));
		$config = json_encode($request->get_param('config'));
		$url = sanitize_url($request->get_param('url'));
		try{
			(new Consent($config,$hash,$url))->save();
			return new WP_REST_Response(null,200);
		}
		catch(exception $e){
			return new WP_REST_Response($e,500);
		}


	}
}

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

	public function register_routes(){
		add_action('rest_api_init', array($this, 'register_rest_endpoint'));
	}

	public function register_rest_endpoint():void {
		register_rest_route( self::TOWA_GDPR_REST_NAMESPACE ,'consent/', array(
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => array($this,'log_consent')
		));
	}

	/**
	 * @param WP_REST_Request $request
	 */
	public function log_consent(WP_REST_Request $request){
		// TODO: escaping
		$hash = $request->get_param('hash');
		$config = $request->get_param('config');
		try{
			(new Consent($config,$hash))->save();
			return new WP_REST_Response(null,200);
		}
		catch(exception $e){
			return new WP_REST_Response($e,500);
		}


	}
}

<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

if ( class_exists( 'ChillPay_Rest_Webhooks_Controller' ) ) {
	return;
}

class ChillPay_Rest_Webhooks_Controller {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	const ENDPOINT_NAMESPACE = 'chillpay';

	/**
	 * @var string
	 */
	const ENDPOINT = 'webhooks';

	/**
	 * Register the routes for webhooks.
	 */
	public function register_routes() {
		register_rest_route(
			self::ENDPOINT_NAMESPACE,
			'/' . self::ENDPOINT,
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( $this, 'callback' ),
				'permission_callback' => '__return_true'
			)
		);
	}

	/**
	 * @param  WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function callback( $request ) {
		if ( 'application/json' !== $request->get_header( 'Content-Type' ) ) {
			return new WP_Error( 'chillpay_rest_wrong_header', __( 'Wrong header type.', 'chillpay' ), array( 'status' => 400 ) );
		}

		$body = json_decode( $request->get_body(), true );

		if ( 'event' !== $body['object'] ) {
			return new WP_Error( 'chillpay_rest_wrong_object', __( 'Wrong object type.', 'chillpay' ), array( 'status' => 400 ) );
		}

		$event = new ChillPay_Events;
		$event = $event->handle( $body['key'], $body['data'] );

		return rest_ensure_response( $event );
	}
}

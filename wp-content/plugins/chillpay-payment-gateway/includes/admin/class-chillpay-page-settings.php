<?php

require_once dirname(__FILE__,2).'/class-chillpay-hash-helper.php';

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

if ( class_exists( 'ChillPay_Page_Settings') ) {
	return;
}

class ChillPay_Page_Settings {

	/**
	 * @var ChillPay_Setting
	 */                                                    
	protected $settings;

	/**
	 * @since 1.0
	 */
	public function __construct() {
		$this->settings = new ChillPay_Setting;
	}

	/**
	 * @return array
	 *
	 * @since  1.0
	 */
	protected function get_settings() {
		return $this->settings->get_settings();
	}

	/**
	 * @param array $data
	 *
	 * @since  1.0
	 */
	protected function save( $data ) {		
		$this->settings->update_settings( $data );
	}

	/**
	 * @since  1.0
	 */
	public static function render() {
		global $title;

		$page = new self;

		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			$page->save( $_POST );
		}

		$settings = $page->get_settings();

		$language_codes = array('TH', 'EN');
		$columns = array(
			'name'    		=> __( 'Payment Method', 'chillpay' ),
			'channel'    	=> __( 'Channel', 'chillpay' ),
			/*'background' 	=> __( 'URL Background', 'chillpay' ),
			'result'	  	=> __( 'URL Result', 'chillpay' ),*/
			'status'  		=> __( 'Enabled', 'chillpay' ),
			'setting' 		=> __( 'Setting', 'chillpay' ),
		);

		$available_gateways = array();

		array_push($available_gateways, new ChillPay_Payment_Internetbanking);
		array_push($available_gateways, new ChillPay_Payment_Mobilebanking);
		array_push($available_gateways, new ChillPay_Payment_Creditcard);
		array_push($available_gateways, new ChillPay_Payment_eWallet);
		array_push($available_gateways, new ChillPay_Payment_BillPayment);
		array_push($available_gateways, new ChillPay_Payment_QRCode);
		array_push($available_gateways, new ChillPay_Payment_Kiosk_Machine);
		array_push($available_gateways, new ChillPay_Payment_Installment);
		array_push($available_gateways, new ChillPay_Payment_Pay_With_Points);

		include_once __DIR__ . '/views/chillpay-page-settings.php';
	}
}
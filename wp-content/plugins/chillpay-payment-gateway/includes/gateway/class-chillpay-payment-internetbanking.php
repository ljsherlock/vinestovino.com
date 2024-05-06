<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';
require_once dirname( __FILE__,2 ) . '/admin/class-chillpay-page-settings.php';
require_once dirname( __FILE__,2 ) . '/class-chillpay-fee.php';

class ChillPay_Payment_Internetbanking extends ChillPay_Payment
{
	private const CHANNEL_GROUP_ID = 'chillpay_internetbanking';

    public function __construct()
	{
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Internet Banking', 'chillpay' );
        $this->channel			  = __('BAY, BBL, KTB, SCB, TTB');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Internet Banking</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
            array(
                'strong' => array()
            )
        );

        parent::__construct();
	}
	
	/**
	 * @param  array $methods
	 *
	 * @return array
	 */
	public function add_chillpay_internetbanking( $methods ) {
		$methods[] = 'ChillPay_Payment_Internetbanking';
		return $methods;
	}

    /**
     * @see WC_Settings_API::init_form_fields()
     * @see woocommerce/includes/abstracts/abstract-wc-settings-api.php
     */
    public function init_form_fields()
	{
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'chillpay' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable ChillPay Internet Banking Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Internet Banking', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),

            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_internetbanking_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_internetbanking_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),
        ); 
			
		$scb = array(
			'accept_scb' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_scb_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_scb_default_display(),
				'description' => 'Siam Commercial Bank'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $scb);

		$ktb = array(
			'accept_ktb' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_ktb_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_ktb_default_display(),
				'description' => 'Krungthai Bank'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $ktb);

		$bay = array(
			'accept_bay' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_bay_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_bay_default_display(),
				'description' => 'Krungsri Bank'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $bay);

		$bbl = array(
			'accept_bbl' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_bbl_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_bbl_default_display(),
				'description' => 'Bangkok Bank'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $bbl);

		$tbank = array(
			'accept_ttb' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_ttb_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_ttb_default_display(),
				'description' => 'TMB Thanachart Bank'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $tbank);		
    }

    /**
     * @see WC_Payment_Gateway::payment_fields()
     * @see woocommerce/includes/abstracts/abstract-wc-payment-gateway.php
     */
    public function payment_fields()
	{
		parent::payment_fields();

		$currency   = get_woocommerce_currency();
		$cart_total = WC()->cart->total;

		$order = wc_get_order(get_query_var('order-pay'));
		if ( !empty($order) )
		{
			$cart_total = $order->get_total();
			$currency = $order->get_currency();
		}

		$viewData["currency"] = $currency;
				
		$card_icons['accept_scb'] = $this->get_option( 'accept_scb' );
		$card_icons['accept_ktb'] = $this->get_option( 'accept_ktb' );
		$card_icons['accept_bay'] = $this->get_option( 'accept_bay' );
		$card_icons['accept_bbl'] = $this->get_option( 'accept_bbl' );
		$card_icons['accept_ttb'] = $this->get_option( 'accept_ttb' );			

		$viewData["scb"] = false;
		$viewData["ktb"] = false;
		$viewData["bay"] = false;
		$viewData["bbl"] = false;
		$viewData["tbank"] = false;

		$check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_internetebanking = null;
		if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_internetebanking = ChillPay_Fee::fee_internetebanking();
		} else {
            $fee_internetebanking = ChillPay_Fee::fee_internetebanking();
        }
		
		if (isset($fee_internetebanking)) {
			$fee_scb = $fee_internetebanking['fee_scb'];
			$fee_ktb = $fee_internetebanking['fee_ktb'];
			$fee_bay = $fee_internetebanking['fee_bay'];
			$fee_bbl = $fee_internetebanking['fee_bbl'];
			$fee_ttb = $fee_internetebanking['fee_ttb'];
		}

		if (ChillPay_Card_Image::is_scb_enabled($card_icons)) {	
			$viewData["scb"] = true;
			$viewData["fee_scb"] = -1;
			if($fee_scb >= 0) {
				$viewData["fee_scb"] = "Fee : " . number_format((float)$fee_scb, 2) . " " . $this->currency_support;
			}
		}

		if (ChillPay_Card_Image::is_ktb_enabled($card_icons)) {
			$viewData["ktb"] = true;
			$viewData["fee_ktb"] = -1;
			if($fee_ktb >= 0) {
				$viewData["fee_ktb"] = "Fee : " . number_format((float)$fee_ktb, 2) . " " . $this->currency_support;
			}
		}

		if (ChillPay_Card_Image::is_bay_enabled($card_icons)) {	
			$viewData["bay"] = true;
			$viewData["fee_bay"] = -1;
			if($fee_bay >= 0) {
				$viewData["fee_bay"] = "Fee : " . number_format((float)$fee_bay, 2) . " " . $this->currency_support;
			}
		}

		if (ChillPay_Card_Image::is_bbl_enabled($card_icons)) {	
			$viewData["bbl"] = true;
			$viewData["fee_bbl"] = -1;
			if($fee_bbl >= 0) {
				$viewData["fee_bbl"] = "Fee : " . number_format((float)$fee_bbl, 2) . " " . $this->currency_support;
			}
		}

		if (ChillPay_Card_Image::is_tbank_enabled($card_icons)) {	
			$viewData["tbank"] = true;
			$viewData["fee_ttb"] = -1;
			if($fee_ttb >= 0) {
				$viewData["fee_ttb"] = "Fee : " . number_format((float)$fee_ttb, 2) . " " . $this->currency_support;
			}
		}		

		ChillPay_Util::render_view( 'templates/payment/form-internetbanking.php', $viewData );
		
	}	

	/**
	 * @inheritdoc
	 */
	public function charge( $order_id, $order )
	{
		$order_number = $order->get_order_number();

		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$ip_address = $_SERVER["HTTP_CLIENT_IP"];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip_address = $_SERVER["REMOTE_ADDR"];
		}

		$is_order_pay = 'no';
		$order_pay = wc_get_order(get_query_var('order-pay'));
		if ( !empty($order_pay) )
		{
			$is_order_pay = 'yes';
		}		

		ChillPay_Payment::attach_is_order_pay_to_order($order_id,$is_order_pay);

		return $this->sale( array(
			'customer_id' 	 => $order->get_formatted_billing_full_name(),
			'phone'		 	 => $order->get_billing_phone(),//get_user_meta( get_current_user_id(), 'billing_phone', true ),
			'amount'      	 => $this->format_amount_subunit( $order->get_total(), $order->get_currency() ),
			'currency'    	 => $order->get_currency(),
			'description' 	 => 'WooCommerce Order id ' . $order_id,
			'offsite'     	 => sanitize_text_field($_POST['chillpay-offsite']),
			'return_uri'  	 => add_query_arg('order_id', $order_id, site_url() . '?wc-api=' . $this->id . '_callback'),
			'ip_address'  	 => $ip_address,
			'customer_email' => $order->get_billing_email(),
			'metadata'    	 => array('order_id' => $order_id, 'order_number' => $order_number, 'installment_terms' => null, 'absorb_by' => null)
		) );
	}
			
	/**
	 * Register all javascripts
	 */
	public function chillpay_assets()
	{
		if ( ! is_checkout() || ! $this->is_available() ) {
			return;
		}

		wp_enqueue_style( 'chillpay-payment-form-internetbanking-css', plugins_url( '../../assets/css/payment/form-internetbanking.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
		wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
	}	
	
}

if ( ! class_exists( 'add_chillpay_internetbanking' ) ) {
	/**
	 * @param  array $methods
	 *
	 * @return array
	 */
	function add_chillpay_internetbanking( $methods )
	{
		$methods[] = 'ChillPay_Payment_Internetbanking';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_chillpay_internetbanking' );
}

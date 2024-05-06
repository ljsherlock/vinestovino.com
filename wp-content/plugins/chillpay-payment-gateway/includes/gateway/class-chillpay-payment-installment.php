<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';
require_once dirname( __FILE__,2 ) . '/admin/class-chillpay-page-settings.php';
require_once dirname( __FILE__,2 ) . '/class-chillpay-fee.php';

/**
 * @since 2.0
 */
class ChillPay_Payment_Installment extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_installment';

    public function __construct()
    {
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Installment', 'chillpay' );
        $this->channel			  = __( 'KBANK, KTC Flexi, SCB, Krungsri Consumer, Krungsri First Choice', 'chillpay');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Installment</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_installment( $methods ) {
		$methods[] = 'ChillPay_Payment_Installment';
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
                'label'   => __( 'Enable ChillPay Installment Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Installment', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),
            
            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_installment_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_installment_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),
        );

        $kbank = array(
			'accept_installment_kbank' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_kbank_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_kbank_default_display(),
				'description' => 'Kasikorn Bank'
            ),
            
            'absorb_by_installment_kbank' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (KBANK)', 'woocommerce' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
		);
        $this->form_fields = array_merge($this->form_fields, $kbank);	
        
        $ktc = array(
            'accept_installment_ktc_flexi' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_installment_ktc_flexi_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_installment_ktc_flexi_default_display(),
				'description' => 'KTC Flexi'
            ),
            
            'absorb_by_installment_ktc_flexi' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (KTC)', 'chillpay' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $ktc);

        $scb = array(
            'accept_installment_scb' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_scb_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_scb_default_display(),
				'description' => 'Siam Commercial Bank'
            ),
            
            'absorb_by_installment_scb' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (SCB)', 'chillpay' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $scb);

        $krungsri = array(
            'accept_installment_krungsri' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_krungsri_consumer_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_krungsri_consumer_default_display(),
				'description' => 'Krungsri Consumer'
            ),
            
            'absorb_by_installment_krungsri' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (Krungsri Consumer)', 'chillpay' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $krungsri);

        $firstchoice = array(
            'accept_installment_firstchoice' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_krungsri_first_choice_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_krungsri_first_choice_default_display(),
				'description' => 'Krungsri First Choice'
            ),
            
            'absorb_by_installment_firstchoice' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (Krungsri First Choice)', 'chillpay' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $firstchoice);

        /*
        $tbank = array(
			'accept_installment_tbank' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_tbank_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_tbank_default_display(),
				'description' => 'Thanachart Bank'
            ),
            
            'absorb_by_installment_tbank' => array(
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'description' => __( 'Choose whether you want your merchant or customer to absorb interest. (TBANK)', 'chillpay' ),
                'default'     => '01',
                'desc_tip'    => true,
                'options'     => array(
                    '01'      => __( 'Merchant', 'chillpay' ),
                    '02'      => __( 'Customer', 'chillpay' ),
                ),
            ),
		);
        $this->form_fields = array_merge($this->form_fields, $tbank);    
        */ 
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
        $viewData["cart_total"] = $cart_total;
        
        $card_icons['accept_installment_kbank'] = $this->get_option( 'accept_installment_kbank' );
        $card_icons['accept_installment_ktc_flexi'] = $this->get_option( 'accept_installment_ktc_flexi' );
        $card_icons['accept_installment_scb'] = $this->get_option( 'accept_installment_scb' );
        $card_icons['accept_installment_krungsri'] = $this->get_option( 'accept_installment_krungsri' );
        $card_icons['accept_installment_firstchoice'] = $this->get_option( 'accept_installment_firstchoice' );
        //$card_icons['accept_installment_tbank'] = $this->get_option( 'accept_installment_tbank' );
        
        $viewData["installment_kbank"] = false;
        $viewData["installment_ktc_flexi"] = false;
        $viewData["installment_scb"] = false;
        $viewData["installment_krungsri"] = false;
        $viewData["installment_firstchoice"] = false;
        //$viewData["installment_tbank"] = false;

        $absorb_by_installment_kbank = $this->get_option( 'absorb_by_installment_kbank' );
        $absorb_by_installment_ktc_flexi = $this->get_option( 'absorb_by_installment_ktc_flexi' );
        $absorb_by_installment_scb = $this->get_option( 'absorb_by_installment_scb' );
        $absorb_by_installment_krungsri = $this->get_option( 'absorb_by_installment_krungsri' );
        $absorb_by_installment_firstchoice = $this->get_option( 'absorb_by_installment_firstchoice' );
        //$absorb_by_installment_tbank = $this->get_option( 'absorb_by_installment_tbank' );

        $viewData["installment_kbank_data"] = array();
        $viewData["installment_ktc_flexi_data"] = array();
        $viewData["installment_scb_data"] = array();
        $viewData["installment_krungsri_data"] = array();
        $viewData["installment_firstchoice_data"] = array();

        $viewData["has_merchant_route_installment_kbank"] = 0;
        $viewData["has_merchant_route_installment_ktc_flexi"] = 0;
        $viewData["has_merchant_route_installment_scb"] = 0;
        $viewData["has_merchant_route_installment_krungsri"] = 0;
        $viewData["has_merchant_route_installment_firstchoice"] = 0;

        $check_fee = ChillPay_Fee::check_fee();
        $paymeny_fee = null;
		$fee_installment = null;
        if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_installment = ChillPay_Fee::fee_installment();
		} else {
            $fee_installment = ChillPay_Fee::fee_installment();
        }

        if (ChillPay_Card_Image::is_installment_kbank_enabled($card_icons)) {
            $viewData["installment_kbank"] = true;
            $viewData["has_merchant_route_installment_kbank"] = $fee_installment['has_merchant_route_installment_kbank'];
            $viewData["has_merchant_fee_installment_kbank"] = $fee_installment['has_merchant_fee_installment_kbank'];
            $viewData["has_merchant_service_fee_installment_kbank"] = $fee_installment['has_merchant_service_fee_installment_kbank'];
            $viewData["absorb_by_installment_kbank"] = $absorb_by_installment_kbank;
			$viewData["installment_kbank_data"] = $fee_installment['installment_kbank'];
            $viewData["min_amount_installment_kbank"] = $fee_installment['min_amount_installment_kbank'];
            $viewData["max_amount_installment_kbank"] = $fee_installment['max_amount_installment_kbank'];
        }
       
        if (ChillPay_Card_Image::is_installment_ktc_flexi_enabled($card_icons)) {
            $viewData["installment_ktc_flexi"] = true;
            $viewData["has_merchant_route_installment_ktc_flexi"] = $fee_installment['has_merchant_route_installment_ktc_flexi'];
            $viewData["has_merchant_fee_installment_ktc_flexi"] = $fee_installment['has_merchant_fee_installment_ktc_flexi'];
            $viewData["has_merchant_service_fee_installment_ktc_flexi"] = $fee_installment['has_merchant_service_fee_installment_ktc_flexi'];
            $viewData["absorb_by_installment_ktc_flexi"] = $absorb_by_installment_ktc_flexi;
			$viewData["installment_ktc_flexi_data"] = $fee_installment['installment_ktc_flexi'];
            $viewData["min_amount_installment_ktc_flexi"] = $fee_installment['min_amount_installment_ktc_flexi'];
            $viewData["max_amount_installment_ktc_flexi"] = $fee_installment['max_amount_installment_ktc_flexi'];
        }

        if (ChillPay_Card_Image::is_installment_scb_enabled($card_icons)) {
            $viewData["installment_scb"] = true;
            $viewData["has_merchant_route_installment_scb"] = $fee_installment['has_merchant_route_installment_scb'];
            $viewData["has_merchant_fee_installment_scb"] = $fee_installment['has_merchant_fee_installment_scb'];
            $viewData["has_merchant_service_fee_installment_scb"] = $fee_installment['has_merchant_service_fee_installment_scb'];
            $viewData["absorb_by_installment_scb"] = $absorb_by_installment_scb;
			$viewData["installment_scb_data"] = $fee_installment['installment_scb'];
            $viewData["min_amount_installment_scb"] = $fee_installment['min_amount_installment_scb'];
            $viewData["max_amount_installment_scb"] = $fee_installment['max_amount_installment_scb'];
        }

        if (ChillPay_Card_Image::is_installment_krungsri_enabled($card_icons)) {
            $viewData["installment_krungsri"] = true;
            $viewData["has_merchant_route_installment_krungsri"] = $fee_installment['has_merchant_route_installment_krungsri'];
            $viewData["has_merchant_fee_installment_krungsri"] = $fee_installment['has_merchant_fee_installment_krungsri'];
            $viewData["has_merchant_service_fee_installment_krungsri"] = $fee_installment['has_merchant_service_fee_installment_krungsri'];
            $viewData["absorb_by_installment_krungsri"] = $absorb_by_installment_krungsri;
			$viewData["installment_krungsri_data"] = $fee_installment['installment_krungsri'];
            $viewData["min_amount_installment_krungsri"] = $fee_installment['min_amount_installment_krungsri'];
            $viewData["max_amount_installment_krungsri"] = $fee_installment['max_amount_installment_krungsri'];
            $viewData["card_type_installment_krungsri"] = $fee_installment['card_type_installment_krungsri'];
        }

        if (ChillPay_Card_Image::is_installment_firstchoice_enabled($card_icons)) {
            $viewData["installment_firstchoice"] = true;
            $viewData["has_merchant_route_installment_firstchoice"] = $fee_installment['has_merchant_route_installment_firstchoice'];
            $viewData["has_merchant_fee_installment_firstchoice"] = $fee_installment['has_merchant_fee_installment_firstchoice'];
            $viewData["has_merchant_service_fee_installment_firstchoice"] = $fee_installment['has_merchant_service_fee_installment_firstchoice'];
            $viewData["absorb_by_installment_firstchoice"] = $absorb_by_installment_firstchoice;
			$viewData["installment_firstchoice_data"] = $fee_installment['installment_firstchoice'];
            $viewData["min_amount_installment_firstchoice"] = $fee_installment['min_amount_installment_firstchoice'];
            $viewData["max_amount_installment_firstchoice"] = $fee_installment['max_amount_installment_firstchoice'];
            $viewData["card_type_installment_firstchoice"] = $fee_installment['card_type_installment_firstchoice'];
        }

        /*
        if (ChillPay_Card_Image::is_installment_tbank_enabled( $card_icons ))
        {
            $viewData["installment_tbank"] = true;
            $viewData["absorb_by_installment_tbank"] = $absorb_by_installment_tbank;
			$viewData["installment_tbank_data"] = $fee_installment['installment_tbank'];
        }
        */
        
        $get_locale = get_locale();
        $lang_code = 'EN';
        ChillPay_Util::render_view( 'templates/payment/form-installment.php', $viewData );
        /*if (strpos($get_locale, 'th') !== false){
            ChillPay_Util::render_view( 'templates/payment/form-installment-th.php', $viewData );
        } else {
            ChillPay_Util::render_view( 'templates/payment/form-installment.php', $viewData );
        }*/

    }

    /**
     * @inheritdoc
     */
    public function charge( $order_id, $order )
    {
        $order_number = $order->get_order_number();
        $offsite = sanitize_text_field($_POST['chillpay-offsite']);

        $installment_terms = 0;
        $kbank_installment_terms = sanitize_text_field($_POST['kbank_installment_terms']);
        $ktc_installment_terms = sanitize_text_field($_POST['ktc_installment_terms']);
        $scb_installment_terms = sanitize_text_field($_POST['scb_installment_terms']);
        $krungsri_installment_terms = sanitize_text_field($_POST['krungsri_installment_terms']);
        $krungsri_loancard_installment_terms = sanitize_text_field($_POST['krungsri_loancard_installment_terms']);
        $firstchoice_installment_terms = sanitize_text_field($_POST['firstchoice_installment_terms']);
        $firstchoice_loancard_installment_terms = sanitize_text_field($_POST['firstchoice_loancard_installment_terms']);
        //$tbank_installment_terms = sanitize_text_field($_POST['tbank_installment_terms']);

        $absorb_by = '';
        $absorb_by_installment_kbank = sanitize_text_field($_POST['absorb_by_installment_kbank']);
        $absorb_by_installment_ktc_flexi = sanitize_text_field($_POST['absorb_by_installment_ktc_flexi']);
        $absorb_by_installment_scb = sanitize_text_field($_POST['absorb_by_installment_scb']);
        $absorb_by_installment_krungsri = sanitize_text_field($_POST['absorb_by_installment_krungsri']);
        $absorb_by_installment_firstchoice = sanitize_text_field($_POST['absorb_by_installment_firstchoice']);
        //$absorb_by_installment_tbank = sanitize_text_field($_POST['absorb_by_installment_tbank']);

        $card_type = sanitize_text_field($_POST['cardtype']);

        if (strcmp($offsite,'installment_kbank') === 0) {
            $installment_terms = $kbank_installment_terms;
            $absorb_by = $absorb_by_installment_kbank;
        } elseif (strcmp($offsite,'installment_ktc_flexi') === 0) {
            $installment_terms = $ktc_installment_terms;
            $absorb_by = $absorb_by_installment_ktc_flexi;
        } elseif (strcmp($offsite,'installment_scb') === 0) {
            $installment_terms = $scb_installment_terms;
            $absorb_by = $absorb_by_installment_scb;
        } elseif (strcmp($offsite,'installment_krungsri') === 0) {
            if ($krungsri_installment_terms > 0)
            {
                $installment_terms = $krungsri_installment_terms;
            } elseif ($krungsri_loancard_installment_terms > 0) {
                $installment_terms = $krungsri_loancard_installment_terms;
            }
            $absorb_by = $absorb_by_installment_krungsri;
        } elseif (strcmp($offsite,'installment_firstchoice') === 0) {
            if ($firstchoice_installment_terms > 0)
            {
                $installment_terms = $firstchoice_installment_terms;
            } elseif ($firstchoice_loancard_installment_terms > 0) {
                $installment_terms = $firstchoice_loancard_installment_terms;
            }
            $absorb_by = $absorb_by_installment_firstchoice;
        } 
        /*
        elseif (strcmp($offsite,'installment_tbank') === 0) 
        {
            $installment_terms = $tbank_installment_terms;
            $absorb_by = $absorb_by_installment_tbank;
        }*/

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
            'customer_id'    => $order->get_formatted_billing_full_name(),
            'phone'		     => $order->get_billing_phone(),//get_user_meta( get_current_user_id(), 'billing_phone', true ),
            'amount'         => $this->format_amount_subunit( $order->get_total(), $order->get_currency() ),
            'currency'       => $order->get_currency(),
            'description'    => 'WooCommerce Order id ' . $order_id,
            'offsite'        => sanitize_text_field($_POST['chillpay-offsite']),
            'return_uri'     => add_query_arg('order_id', $order_id, site_url() . '?wc-api=' . $this->id . '_callback'),
            'ip_address'     => $ip_address,
            'customer_email' => $order->get_billing_email(),
            'metadata'       => array('order_id' => $order_id, 'order_number' => $order_number, 'installment_terms' => $installment_terms, 'absorb_by' => $absorb_by, 'card_type' => $card_type)
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

		wp_enqueue_style( 'chillpay-payment-form-installment-css', plugins_url( '../../assets/css/payment/form-installment.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_installment' ) ) {
	/**
	 * @param  array $methods
	 *
	 * @return array
	 */
	function add_chillpay_installment( $methods )
    {
		$methods[] = 'ChillPay_Payment_Installment';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_chillpay_installment' );
}
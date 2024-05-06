<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_QRCode extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_qrcode';

    public function __construct() 
    {
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay QR PromptPay', 'chillpay' );
        $this->channel			  = __( 'QR PromptPay', 'chillpay');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>QR PromptPay</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_qrcode( $methods ) {
		$methods[] = 'ChillPay_Payment_QRCode';
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
                'label'   => __( 'Enable ChillPay QR PromptPay Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'QR PromptPay', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),
            
            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_qrcode_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_qrcode_result', 'chillpay' ),
            ),*/
        );
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
        
        $viewData["qrcode"] = true;
        $viewData["fee_qrcode"] = -1;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_qrcode = null;
		if (!isset($check_fee)) {
            $paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
            $fee_qrcode = ChillPay_Fee::fee_qrcode();
        } else {
            $fee_qrcode = ChillPay_Fee::fee_qrcode();
        }     
		
		if (isset($fee_qrcode)) {
			$fee_qrcode = $fee_qrcode['fee_qrcode'];
        }
        
        if($fee_qrcode >= 0) {
            $viewData["fee_qrcode"] = "Fee : " . number_format((float)$fee_qrcode, 2) . " " . $this->currency_support;
        }

        ChillPay_Util::render_view( 'templates/payment/form-qrcode.php', $viewData );
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

		return $this->sale(array(
			'customer_id'    => $order->get_formatted_billing_full_name(),
			'phone'		     => $order->get_billing_phone(),//get_user_meta( get_current_user_id(), 'billing_phone', true ),
			'amount'         => $this->format_amount_subunit( $order->get_total(), $order->get_currency() ),
			'currency'       => $order->get_currency(),
			'description'    => 'WooCommerce Order id ' . $order_id,
			'offsite'        => 'bank_qrcode',
			'return_uri'     => add_query_arg('order_id', $order_id, site_url() . '?wc-api=' . $this->id . '_callback'),
			'ip_address'     => $ip_address,
            'customer_email' => $order->get_billing_email(),
			'metadata'       => array('order_id' => $order_id, 'order_number' => $order_number, 'installment_terms' => null, 'absorb_by' => null)
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

		wp_enqueue_style( 'chillpay-payment-form-qrcode-css', plugins_url( '../../assets/css/payment/form-qrcode.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_qrcode' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_qrcode( $methods )
    {
        $methods[] = 'ChillPay_Payment_QRCode';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_qrcode' );
}
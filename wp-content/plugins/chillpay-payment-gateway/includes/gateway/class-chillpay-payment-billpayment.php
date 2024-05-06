<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_BillPayment extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_billpayment';

    public function __construct()
    {       
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Bill Payment', 'chillpay' );
        $this->channel			  = __('CenPay, Counter Bill Payment');
        $this->currency_support   = 'THB';
        
        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Bill Payment</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_billpayment( $methods ) {
		$methods[] = 'ChillPay_Payment_BillPayment';
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
                'label'   => __( 'Enable ChillPay Bill Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Bill Payment', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),
            
            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_billpayment_callback', 'chillpay' ),		  
            ),
            
            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_billpayment_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),		
                                                          
        );

        // $bigc = array(
        //     'accept_bigc' => array(
        //         'type'        => 'checkbox',
        //         'label'		  => ChillPay_Card_Image::get_bigc_image(),
        //         'css'         => ChillPay_Card_Image::get_css(),
        //         'default'     => ChillPay_Card_Image::get_bigc_default_display(),
        //         'description' => 'Big C'
        //     ),
        // );
        // $this->form_fields = array_merge($this->form_fields, $bigc);

        $cenpay = array(
            'accept_cenpay' => array(
                'type'        => 'checkbox',
                'label' => ChillPay_Card_Image::get_cenpay_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_cenpay_default_display(),
                'description' => 'CenPay'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $cenpay);

        $counter_bill_payment = array(
            'accept_counter_bill_payment' => array(
                'type'        => 'checkbox',
                'label' => ChillPay_Card_Image::get_counter_bill_payment_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_counter_bill_payment_default_display(),
                'description' => 'Counter Bill Payment'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $counter_bill_payment);
             
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
            $currency   = $order->get_currency();
		}

        $viewData["currency"] = $currency;
        
        // $card_icons['accept_bigc'] = $this->get_option( 'accept_bigc' );
        $card_icons['accept_cenpay'] = $this->get_option( 'accept_cenpay' );
        $card_icons['accept_counter_bill_payment'] = $this->get_option( 'accept_counter_bill_payment' );

        // $viewData["bigc"] = false;
        $viewData["cenpay"] = false;
        $viewData["counter_bill_payment"] = false;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_billpayment = null;
		if (!isset($check_fee)) {
            $paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
            $fee_billpayment = ChillPay_Fee::fee_billpayment();
        } else {
            $fee_billpayment = ChillPay_Fee::fee_billpayment();
        }     
		
		if (isset($fee_billpayment)) {
            // $fee_bigc = $fee_billpayment['fee_bigc'];
            $fee_cenpay = $fee_billpayment['fee_cenpay'];
            $fee_counter_bill_payment = $fee_billpayment['fee_counter_bill_payment'];
		}

        // if (ChillPay_Card_Image::is_bigc_enabled($card_icons)) {
        //     $viewData["bigc"] = true;
        //     $viewData["fee_bigc"] = -1;
        //     if($fee_bigc >= 0) {
		// 		$viewData["fee_bigc"] = "Fee : " . number_format((float)$fee_bigc, 2, '.', '') . " " . $this->currency_support;
		// 	}
        // }
        if (ChillPay_Card_Image::is_cenpay_enabled($card_icons)) {
            $viewData["cenpay"] = true;
            $viewData["fee_cenpay"] = -1;
            if($fee_cenpay >= 0) {
				$viewData["fee_cenpay"] = "Fee : " . number_format((float)$fee_cenpay, 2, '.', '') . " " . $this->currency_support;
			}
        }
        if (ChillPay_Card_Image::is_counter_bill_payment_enabled($card_icons)) {
            $viewData["counter_bill_payment"] = true;
            $viewData["fee_counter_bill_payment"] = -1;
            if($fee_counter_bill_payment >= 0) {
				$viewData["fee_counter_bill_payment"] = "Fee : " . number_format((float)$fee_counter_bill_payment, 2, '.', '') . " " . $this->currency_support;
			}
        }

        ChillPay_Util::render_view( 'templates/payment/form-billpayment.php', $viewData );
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
			'customer_id'    => $order->get_formatted_billing_full_name(),
			'phone'		     => $order->get_billing_phone(),//get_user_meta( get_current_user_id(), 'billing_phone', true ),
			'amount'         => $this->format_amount_subunit( $order->get_total(), $order->get_currency() ),
			'currency'       => $order->get_currency(),
			'description'    => 'WooCommerce Order id ' . $order_id,
			'offsite'        => sanitize_text_field($_POST['chillpay-offsite']),
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

		wp_enqueue_style( 'chillpay-payment-form-billpayment-css', plugins_url( '../../assets/css/payment/form-billpayment.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_billpayment' ) ){
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_billpayment( $methods )
    {
        $methods[] = 'ChillPay_Payment_BillPayment';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_billpayment' );
}
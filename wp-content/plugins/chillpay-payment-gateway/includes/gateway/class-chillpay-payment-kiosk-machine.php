<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_Kiosk_Machine extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_kiosk_machine';

    public function __construct()
    {
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Kiosk Machine', 'chillpay' );
        $this->channel			  = __('Boonterm');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Kiosk Machine</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_kiosk_machine( $methods ) {
		$methods[] = 'ChillPay_Payment_Kiosk_Machine';
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
                'label'   => __( 'Enable ChillPay Kiosk Machine', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Kiosk Machine', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),
            
            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_kiosk_machine_callback', 'chillpay' ),		  
            ),
            
            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_kiosk_machine_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),		
                                                          
        );

        $boonterm = array(
            'accept_boonterm' => array(
                'type'        => 'checkbox',
                'label'		  => ChillPay_Card_Image::get_boonterm_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_bigc_default_display(),
                'description' => 'Boonterm'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $boonterm);
                       
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
        
        $card_icons['accept_boonterm'] = $this->get_option( 'accept_boonterm' );

        $viewData["boonterm"] = false;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_kiosk_machine = null;
		if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_kiosk_machine = ChillPay_Fee::fee_kiosk_machine();
		} else {
            $fee_kiosk_machine = ChillPay_Fee::fee_kiosk_machine();
        }
		
		if (isset($fee_kiosk_machine)) {
			$fee_boonterm = $fee_kiosk_machine['fee_boonterm'];
		}

        $get_locale = get_locale();
        $lang_code = 'EN';
        if (strpos($get_locale, 'th') !== false)
        {
            $lang_code = 'TH';
        }

        if (ChillPay_Card_Image::is_boonterm_enabled($card_icons)) {
            $viewData["boonterm"] = true;
            $viewData["fee_boonterm"] = -1;
            if($fee_boonterm >= 0) {
                if (fmod($cart_total, 1) !== 0.00) {                
                    $bill_amount = ceil($cart_total);
                    if (strpos($get_locale, 'th') !== false) {
                        $viewData["fee_boonterm"] = "Fee : " . number_format((float)$fee_boonterm, 2) . " " . $this->currency_support . "<br/> หมายเหตุ ยอดชำระจะปัดเศษทศนิยมขึ้น จาก " .number_format((float)$cart_total, 2). " " . $this->currency_support . " เป็น " .number_format((float)$bill_amount, 2). " " . $this->currency_support . " (โดยยังไม่รวมค่าธรรมเนียม)";
                    } else {
                        $viewData["fee_boonterm"] = "Fee : " . number_format((float)$fee_boonterm, 2) . " " . $this->currency_support . "<br/> Remark : Payment will be round up decimal from " .number_format((float)$cart_total, 2). " " . $this->currency_support . " to " .number_format((float)$bill_amount, 2). " " . $this->currency_support . " (Not including transaction fees.)";
                    }
                } else {
                    $viewData["fee_boonterm"] = "Fee : " . number_format((float)$fee_boonterm, 2) . " " . $this->currency_support;
                }
			}
        }

        ChillPay_Util::render_view( 'templates/payment/form-kiosk-machine.php', $viewData );
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

		wp_enqueue_style( 'chillpay-payment-form-kiosk-machine-css', plugins_url( '../../assets/css/payment/form-kiosk-machine.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_kiosk_machine' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_kiosk_machine( $methods )
    {
        $methods[] = 'ChillPay_Payment_Kiosk_Machine';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_kiosk_machine' );
}
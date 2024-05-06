<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_eWallet extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_ewallet';

    public function __construct() 
    {       
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay e-Wallet', 'chillpay' );
        $this->channel			  = __('Alipay, Rabbit LINE Pay, ShopeePay, TrueMoney Wallet, WeChat Pay');
        $this->currency_support   = 'THB';
        
        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>e-Wallet</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_ewallet( $methods ) {
		$methods[] = 'ChillPay_Payment_eWallet';
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
                'label'   => __( 'Enable ChillPay e-Wallet', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'e-Wallet', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),

            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_ewallet_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_ewallet_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),

        );

        $rabbit = array(
            'accept_rabbit' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_rabbit_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_rabbit_default_display(),
                'description' => 'Rabbit LINE Pay'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $rabbit);

        $true = array(
            'accept_true' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_true_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_true_default_display(),
                'description' => 'TrueMoney Wallet'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $true);

        $alipay = array(
            'accept_alipay' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_alipay_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_alipay_default_display(),
                'description' => 'Alipay'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $alipay);

        $wechatpay = array(
            'accept_wechatpay' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_wechatpay_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_wechatpay_default_display(),
                'description' => 'WeChat Pay'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $wechatpay);

        $shopeepay = array(
            'accept_shopeepay' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_shopeepay_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_shopeepay_default_display(),
                'description' => 'ShopeePay'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $shopeepay);
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
        
        $card_icons['accept_rabbit'] = $this->get_option( 'accept_rabbit' );
        $card_icons['accept_true'] = $this->get_option( 'accept_true' );
        $card_icons['accept_alipay'] = $this->get_option( 'accept_alipay' );
        $card_icons['accept_wechatpay'] = $this->get_option( 'accept_wechatpay' );
        $card_icons['accept_shopeepay'] = $this->get_option( 'accept_shopeepay' );
            
        $viewData["rabbit"] = false;
        $viewData["true"] = false;  
        $viewData["alipay"] = false;
        $viewData["wechatpay"] = false;
        $viewData["shopeepay"] = false;
        
        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_ewallet = null;
		if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_ewallet = ChillPay_Fee::fee_ewallet();
		} else {
            $fee_ewallet = ChillPay_Fee::fee_ewallet();
        }
		
		if (isset($fee_ewallet)) {
			$fee_rabbit = $fee_ewallet['fee_linepay'];
			$fee_true = $fee_ewallet['fee_truemoney'];
            $fee_alipay = $fee_ewallet['fee_alipay'];
            $fee_wechatpay = $fee_ewallet['fee_wechatpay'];
            $fee_shopeepay = $fee_ewallet['fee_shopeepay'];
		}

        if (ChillPay_Card_Image::is_rabbit_enabled($card_icons)) {
            $viewData["rabbit"] = true;
            $viewData["fee_rabbit"] = -1;
			if($fee_rabbit >= 0) {
				$viewData["fee_rabbit"] = "Fee : " . number_format((float)$fee_rabbit, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_true_enabled($card_icons)) {
            $viewData["true"] = true;
            $viewData["fee_true"] = -1;
            if($fee_true >= 0) {
				$viewData["fee_true"] = "Fee : " . number_format((float)$fee_true, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_alipay_enabled($card_icons)) {
            $viewData["alipay"] = true;
            $viewData["fee_alipay"] = -1;
            if($fee_alipay >= 0) {
				$viewData["fee_alipay"] = "Fee : " .number_format((float)$fee_alipay, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_wechatpay_enabled($card_icons)) {
            $viewData["wechatpay"] = true;
            $viewData["fee_wechatpay"] = -1;
            if($fee_wechatpay >= 0) {
				$viewData["fee_wechatpay"] = "Fee : " .number_format((float)$fee_wechatpay, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_shopeepay_enabled($card_icons)) {
            $viewData["shopeepay"] = true;
            $viewData["fee_shopeepay"] = -1;
            if($fee_shopeepay >= 0) {
				$viewData["fee_shopeepay"] = "Fee : " . number_format((float)$fee_shopeepay, 2) . " " . $this->currency_support;
			}
        }

        ChillPay_Util::render_view( 'templates/payment/form-ewallet.php', $viewData );
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
     * Register all required javascripts
     */
    public function chillpay_assets()
    {
        if ( ! is_checkout() || ! $this->is_available() ) {
            return;
        }
        wp_enqueue_style( 'chillpay-payment-form-ewallet-css', plugins_url( '../../assets/css/payment/form-ewallet.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }  
}

if ( ! class_exists( 'add_chillpay_ewallet' ) ){
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_ewallet( $methods )
    {
        $methods[] = 'ChillPay_Payment_eWallet';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_ewallet' );
}
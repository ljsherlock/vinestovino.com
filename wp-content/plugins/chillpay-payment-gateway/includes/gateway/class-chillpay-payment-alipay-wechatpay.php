<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_Alipay_WeChatPay extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_alipay_wechatpay';

    public function __construct()
    {      
        $this->id                 = 'chillpay_alipay_wechatpay';
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Alipay / WeChat Pay', 'chillpay' );
        $this->channel			  = __('Alipay, WeChat Pay');
        $this->currency_support = 'THB';
        
        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Alipay / WeChat Pay</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_alipay_wechatpay($methods)
	{
		$methods[] = 'ChillPay_Payment_Alipay_WeChatPay';
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
                'label'   => __( 'Enable ChillPay Alipay / WeChat Pay', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Alipay / WeChat Pay', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),

            'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_alipay_wechatpay_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_alipay_wechatpay_result', 'chillpay' ),
            ),

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),

        );

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
    }

    /**
     * @see WC_Payment_Gateway::payment_fields()
     * @see woocommerce/includes/abstracts/abstract-wc-payment-gateway.php
     */
    public function payment_fields() {
        parent::payment_fields();

        $currency   = get_woocommerce_currency();
		$viewData["currency"] = $currency;
		$cart_total = WC()->cart->total;

        $card_icons['accept_alipay'] = $this->get_option( 'accept_alipay' );
        $card_icons['accept_wechatpay'] = $this->get_option( 'accept_wechatpay' );
            
        $viewData["alipay"] = false;
        $viewData["wechatpay"] = false;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_alipay_wechatpay = null;
		if (!isset($check_fee)) {
            $paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
            $fee_alipay_wechatpay = ChillPay_Fee::fee_alipay_wechatpay();
        } else {
            $fee_alipay_wechatpay = ChillPay_Fee::fee_alipay_wechatpay();
        }     
		
		if (isset($fee_alipay_wechatpay)) {
            $fee_alipay = $fee_alipay_wechatpay['fee_alipay'];
            $fee_wechatpay = $fee_alipay_wechatpay['fee_wechatpay'];
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

        ChillPay_Util::render_view( 'templates/payment/form-alipay-wechatpay.php', $viewData );
    }

    /**
	 * @inheritdoc
	 */
	public function charge( $order_id, $order ) {
		$order_number = $order->get_order_number();

		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$ip_address = $_SERVER["HTTP_CLIENT_IP"];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip_address = $_SERVER["REMOTE_ADDR"];
		}

		return $this->sale( array(
			'customer_id' => $order->get_formatted_billing_full_name(),
			'phone'		  => $order->get_billing_phone(),//get_user_meta( get_current_user_id(), 'billing_phone', true ),
			'amount'      => $this->format_amount_subunit( $order->get_total(), $order->get_currency() ),
			'currency'    => $order->get_currency(),
			'description' => 'WooCommerce Order id ' . $order_id,
			'offsite'     => sanitize_text_field($_POST['chillpay-offsite']),
			'return_uri'  => add_query_arg( 'order_id', $order_id, site_url() . "?wc-api=chillpay_alipay_wechatpay_callback" ),
			'ip_address'  => $ip_address,
			'metadata'    => array('order_id' => $order_id, 'order_number' => $order_number)
		) );
	}
	
	/**
	 * Register all javascripts
	 */
	public function chillpay_assets() {
		if ( ! is_checkout() || ! $this->is_available() ) {
			return;
		}

		wp_enqueue_style( 'chillpay-payment-form-alipay-wechatpay-css', plugins_url( '../../assets/css/payment/form-alipay-wechatpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
	}
}

if ( ! class_exists( 'add_chillpay_alipay_wechatpay' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_alipay_wechatpay( $methods ) {
        $methods[] = 'ChillPay_Payment_Alipay_WeChatPay';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_alipay_wechatpay' );
}
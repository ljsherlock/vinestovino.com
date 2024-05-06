<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_Pay_With_Points extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_pay_with_points';

    public function __construct()
    {
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Pay With Points', 'chillpay' );
        $this->channel			  = __('KTC Forever');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Pay With Points</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_pay_with_points( $methods ) {
		$methods[] = 'ChillPay_Payment_Pay_With_Points';
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
                'label'   => __( 'Enable ChillPay Pay With Points', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Pay With Points', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),
            
            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_pay_with_points_callback', 'chillpay' ),		  
            ),
            
            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_pay_with_points_result', 'chillpay' ),
            ),*/

            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),		
                                                          
        );

        $ktc_forever = array(
            'accept_point_ktc_forever' => array(
                'type'        => 'checkbox',
                'label'		  => ChillPay_Card_Image::get_point_ktc_forever_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_point_ktc_forever_default_display(),
                'description' => 'KTC Forever'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $ktc_forever);
                       
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
        
        $card_icons['accept_point_ktc_forever'] = $this->get_option( 'accept_point_ktc_forever' );

        $viewData["point_ktc_forever"] = false;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_pay_with_points = null;
		if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_pay_with_points = ChillPay_Fee::fee_pay_with_points();
		} else {
            $fee_pay_with_points = ChillPay_Fee::fee_pay_with_points();
        }
		
		if (isset($fee_pay_with_points)) {
			$fee_point_ktc_forever = $fee_pay_with_points['fee_point_ktc_forever'];
		}

        $get_locale = get_locale();
        $lang_code = 'EN';
        if (strpos($get_locale, 'th') !== false)
        {
            $lang_code = 'TH';
        }

        if (ChillPay_Card_Image::is_point_ktc_forever_enabled($card_icons)) {
            $viewData["point_ktc_forever"] = true;
            $viewData["fee_point_ktc_forever"] = -1;
            if($fee_point_ktc_forever >= 0) {
                if(fmod($cart_total, 1) !== 0.00){      
                    $bill_amount = self::roundout($cart_total,1);
                    if ($bill_amount == $cart_total)
                    {
                        if (strpos($get_locale, 'th') !== false) {
                            $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> หมายเหตุ : 10 พอยท์ = 1 บาท";
                        } else {
                            $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> Remark : 10 Points = 1 Baht";
                        }
                    }
                    else
                    {
                        if (strpos($get_locale, 'th') !== false) {
                            $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> หมายเหตุ : 10 พอยท์ = 1 บาท<br/>ยอดชำระจะปัดเศษทศนิยมขึ้น จาก " .$cart_total. " " . $this->currency_support . " เป็น " .number_format((float)$bill_amount, 2, '.', ''). " " . $this->currency_support. " (โดยยังไม่รวมค่าธรรมเนียม)";           
                        } else {
                            $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> Remark : 10 Points = 1 Baht<br/>Payment will be round up decimal from " .$cart_total. " " . $this->currency_support . " to " .number_format((float)$bill_amount, 2, '.', ''). " " . $this->currency_support ." (Not including transaction fees.)";           
                        }
                    }                           
                }
                else
                {
                    if (strpos($get_locale, 'th') !== false) {
                        $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> หมายเหตุ : 10 พอยท์ = 1 บาท";
                    } else {
                        $viewData["fee_point_ktc_forever"] = "Fee : " . number_format((float)$fee_point_ktc_forever, 2, '.', '') . " " . $this->currency_support . "<br/> Remark : 10 Points = 1 Baht";
                    }
                }
				
			}
        }

        ChillPay_Util::render_view( 'templates/payment/form-pay-with-points.php', $viewData );
    }

    function roundout ($value, $places=0) {
        if ($places < 0) { $places = 0; }
        $x= pow(10, $places);
        return ($value >= 0 ? ceil($value * $x):floor($value * $x)) / $x;
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

		wp_enqueue_style( 'chillpay-payment-form-pay-with-points-css', plugins_url( '../../assets/css/payment/form-pay-with-points.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_pay_with_points' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_pay_with_points( $methods )
    {
        $methods[] = 'ChillPay_Payment_Pay_With_Points';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_pay_with_points' );
}
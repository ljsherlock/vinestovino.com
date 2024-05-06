<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_Mobilebanking extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_mobilebanking';

    public function __construct()
    {
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Mobile Banking', 'chillpay' );
        $this->channel			  = __('K PLUS, SCB Easy App, KMA App, Bualuang mBanking, Krungthai NEXT', 'chillpay');
        $this->currency_support   = 'THB';

        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>mobile Banking</strong> via ChillPay payment gateway (only available in Thailand).', 'chillpay' ),
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
	public function add_chillpay_mobilebanking( $methods ) {
		$methods[] = 'ChillPay_Payment_Mobilebanking';
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
                'label'   => __( 'Enable ChillPay Mobile Banking Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Mobile Banking', 'chillpay' ),
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),

            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_mobilebanking_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_mobilebanking_result', 'chillpay' ),
            ),*/
            
            'channel' => array(
                'title'       => __( 'Payment Channel', 'chillpay' ),
                'type'        => 'hidden',
            ),

        );   
        
        $kplus = array(
            'accept_kplus' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_kplus_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_kplus_default_display(),
                'description' => 'Kasikorn Bank (K PLUS)'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $kplus);

        $scb_easy = array(
            'accept_scb_easy' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_scb_easy_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_scb_easy_default_display(),
                'description' => 'Siam Commercial Bank (SCB Easy App)'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $scb_easy);

        $kma = array(
            'accept_kma' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_kma_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_kma_default_display(),
                'description' => 'Krungsri Bank (KMA App)'
            ),
        );
        $this->form_fields = array_merge($this->form_fields, $kma);

        $bbl_mbanking = array(
            'accept_bbl_mbanking' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_bbl_mbanking_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_bbl_mbanking_default_display(),
                'description' => 'Bangkok Bank (Bualuang mBanking)'
            ),
        ); 
        $this->form_fields = array_merge($this->form_fields, $bbl_mbanking);

        $krungthai_next = array(
            'accept_krungthai_next' => array(
                'type'        => 'checkbox',
                'label'       => ChillPay_Card_Image::get_krungthai_next_image(),
                'css'         => ChillPay_Card_Image::get_css(),
                'default'     => ChillPay_Card_Image::get_krungthai_next_default_display(),
                'description' => 'Krungthai Bank (Krungthai NEXT)'
            ),
        ); 
        $this->form_fields = array_merge($this->form_fields, $krungthai_next);
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
        
        $card_icons['accept_kplus'] = $this->get_option( 'accept_kplus' );
        $card_icons['accept_scb_easy'] = $this->get_option( 'accept_scb_easy' );	
        $card_icons['accept_kma'] = $this->get_option( 'accept_kma' );
        $card_icons['accept_bbl_mbanking'] = $this->get_option( 'accept_bbl_mbanking' );
        $card_icons['accept_krungthai_next'] = $this->get_option( 'accept_krungthai_next' );

        $viewData["kplus"] = false;
        $viewData["scb_easy"] = false;
        $viewData["kma"] = false;
        $viewData["bbl_mbanking"] = false;
        $viewData["krungthai_next"] = false;

        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_mobilebanking = null;
		if (!isset($check_fee)) {
            $paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
            $fee_mobilebanking = ChillPay_Fee::fee_mobilebanking();
        } else {
            $fee_mobilebanking = ChillPay_Fee::fee_mobilebanking();
        }     
		
		if (isset($fee_mobilebanking)) {
			$fee_kplus = $fee_mobilebanking['fee_kplus'];
            $fee_scb_easy = $fee_mobilebanking['fee_scb_easy'];
            $fee_kma = $fee_mobilebanking['fee_kma'];
            $fee_bbl_mbanking = $fee_mobilebanking['fee_bbl_mbanking'];
            $fee_krungthai_next = $fee_mobilebanking['fee_krungthai_next'];
		}

        if (ChillPay_Card_Image::is_kplus_enabled($card_icons)) {
            $viewData["kplus"] = true;
            $viewData["fee_kplus"] = -1;
            if($fee_kplus >= 0) {
				$viewData["fee_kplus"] = "Fee : " . number_format((float)$fee_kplus, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_scb_easy_enabled($card_icons)) {
            $viewData["scb_easy"] = true;
            $viewData["fee_scb_easy"] = -1;
            if($fee_scb_easy >= 0) {
				$viewData["fee_scb_easy"] = "Fee : " . number_format((float)$fee_scb_easy, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_kma_enabled($card_icons)) {
            $viewData["kma"] = true;
            $viewData["fee_kma"] = -1;
            if($fee_kma >= 0) {
				$viewData["fee_kma"] = "Fee : " . number_format((float)$fee_kma, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_bbl_mbanking_enabled($card_icons)) {
            $viewData["bbl_mbanking"] = true;
            $viewData["fee_bbl_mbanking"] = -1;
            if($fee_bbl_mbanking >= 0) {
				$viewData["fee_bbl_mbanking"] = "Fee : " . number_format((float)$fee_bbl_mbanking, 2) . " " . $this->currency_support;
			}
        }

        if (ChillPay_Card_Image::is_krungthai_next_enabled($card_icons)) {
            $viewData["krungthai_next"] = true;
            $viewData["fee_krungthai_next"] = -1;
            if($fee_krungthai_next >= 0) {
				$viewData["fee_krungthai_next"] = "Fee : " . number_format((float)$fee_krungthai_next, 2) . " " . $this->currency_support;
			}
        }

        ChillPay_Util::render_view( 'templates/payment/form-mobilebanking.php', $viewData );
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
            'kplus_mobile'   => sanitize_text_field($_POST['chillpay_kplus_mobile']),
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

		wp_enqueue_style( 'chillpay-payment-form-mobilebanking-css', plugins_url( '../../assets/css/payment/form-mobilebanking.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }
}

if ( ! class_exists( 'add_chillpay_mobilebanking' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_mobilebanking( $methods )
    {
        $methods[] = 'ChillPay_Payment_Mobilebanking';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_mobilebanking' );
}
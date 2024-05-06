<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

require_once dirname( __FILE__ ) . '/class-chillpay-payment.php';

class ChillPay_Payment_Creditcard extends ChillPay_Payment
{
    private const CHANNEL_GROUP_ID = 'chillpay_creditcard';

    public function __construct()
    {        
        $this->id                 = self::CHANNEL_GROUP_ID;
        $this->has_fields         = true;
        $this->method_title       = __( 'ChillPay Credit / Debit Card', 'chillpay' );
        $this->channel 			  = __( 'Credit / Debit Card', 'chillpay');
        
        $this->method_description = wp_kses(
            __( 'Accept payment through <strong>Credit / Debit Card</strong> via ChillPay payment gateway.', 'chillpay' ),
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
	public function add_chillpay_creditcard( $methods ) {
		$methods[] = 'ChillPay_Payment_Creditcard';
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
                'label'   => __( 'Enable ChillPay Credit / Debit Card Payment', 'chillpay' ),
                'default' => 'no'
            ),

            'title' => array(
                'title'       => __( 'Title', 'chillpay' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'chillpay' ),
                'default'     => __( 'Credit / Debit Card', 'chillpay' )
            ),

            'description' => array(
                'title'       => __( 'Description', 'chillpay' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'chillpay' )
            ),

            /*'background' => array(
                'title'       => __( 'URL Background', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_creditcard_callback', 'chillpay' ),		  
            ),

            'result' => array(
                'title'	  => __( 'URL Result', 'chillpay' ),
                'type'        => 'hidden',
                'description'	  => __( get_site_url() . '/?wc-api=chillpay_creditcard_result', 'chillpay' ),
            ),*/
        );

        $creditcard = array(
			'accept_creditcard' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_visa_image().''.ChillPay_Card_Image::get_mastercard_image().''.ChillPay_Card_Image::get_jcb_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_creditcard_default_display(),
				'description' => 'VISA, MASTER CARD, JCB'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $creditcard);

        $unionpay = array(
			'accept_unionpay' => array(
				'type'        => 'checkbox',
				'label'       => ChillPay_Card_Image::get_unionpay_image(),
				'css'         => ChillPay_Card_Image::get_css(),
				'default'     => ChillPay_Card_Image::get_unionpay_default_display(),
				'description' => 'UNIONPAY'
			),
		);
		$this->form_fields = array_merge($this->form_fields, $unionpay);
    }

    /**
     * @see WC_Payment_Gateway::payment_fields()
     * @see woocommerce/includes/abstracts/abstract-wc-payment-gateway.php
     */
    public function payment_fields()
    {
        parent::payment_fields();

        $currency = get_woocommerce_currency();
        $viewData["currency"] = null;
        $cart_total = WC()->cart->total;

        $order = wc_get_order(get_query_var('order-pay'));
		if ( !empty($order) )
		{
			$cart_total = $order->get_total();
            $currency   = $order->get_currency();
		}
        
        $is_currency_support = $this->is_currency_support($currency);
        if ($is_currency_support) {
            $viewData["currency"] = $currency;
        }
     
        $check_fee = ChillPay_Fee::check_fee();
		$paymeny_fee = null;
		$fee_creditcard = null;
		if (!isset($check_fee)) {
			$paymeny_fee = ChillPay_Fee::get_payment_fee($currency, $cart_total);
			$fee_creditcard = ChillPay_Fee::fee_creditcard();
		} else {
            $fee_creditcard = ChillPay_Fee::fee_creditcard();
        }

        $viewData["card_type"] = '';
        if (isset($fee_creditcard)) {
            $fee_credit = $fee_creditcard['fee_creditcard'];
            $card_type = $fee_creditcard['card_type_creditcard'];
        }

        $viewData["card_type"] = $card_type;
        $viewData["fee_credit"] = -1;
        if($fee_credit >= 0) {
            $viewData["fee_credit"] = "Fee : " . number_format((float)$fee_credit, 2) . " " . $currency;
        }

        $card_icons['accept_creditcard'] = $this->get_option( 'accept_creditcard' );
        $card_icons['accept_unionpay'] = $this->get_option( 'accept_unionpay' );

        $viewData["creditcard"] = false;
        $viewData["unionpay"] = false;
        
        if (ChillPay_Card_Image::is_creditcard_enabled($card_icons)) {
            $viewData["creditcard"] = true;
        }

        if (ChillPay_Card_Image::is_unionpay_enabled($card_icons)) {
            $viewData["unionpay"] = true;
        }

        ChillPay_Util::render_view( 'templates/payment/form-creditcard.php', $viewData);
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
        
        $card_type = sanitize_text_field($_POST['cardtype']);
        error_log('charge card_type : '.$card_type);

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
            'metadata'       => array('order_id' => $order_id, 'order_number' => $order_number, 'installment_terms' => null, 'absorb_by' => null, 'card_type' => $card_type)
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
        wp_enqueue_style( 'chillpay-payment-form-creditcard-css', plugins_url( '../../assets/css/payment/form-creditcard.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
        wp_enqueue_style( 'form-chillpay-css', plugins_url( '../../assets/css/payment/form-chillpay.css', __FILE__ ), array(), CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION );
    }     
    
}

if ( ! class_exists( 'add_chillpay_creditcard' ) ) {
    /**
     * @param  array $methods
     *
     * @return array
     */
    function add_chillpay_creditcard( $methods )
    {
        $methods[] = 'ChillPay_Payment_Creditcard';
        return $methods;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_chillpay_creditcard' );
}
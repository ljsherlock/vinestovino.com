<?php

defined( 'ABSPATH' ) || exit;

/**
 * @since 2.0
 */
class ChillPay_Payment_Methods {
    /**
	 * @var null | array
	 */                                               
	public $payment_method = array(
		'ChillPay_Payment_Internetbanking',
		'ChillPay_Payment_Mobilebanking',
		'ChillPay_Payment_Creditcard',
		'ChillPay_Payment_eWallet',
		'ChillPay_Payment_BillPayment',
		'ChillPay_Payment_QRCode',
		'ChillPay_Payment_Kiosk_Machine',
		'ChillPay_Payment_Installment',
		'ChillPay_Payment_Pay_With_Points'
    );

    /**
	 * @param string $id  ChillPay payment method's id.
	 */
	public static function get_payment_method( $id ) {
		$methods = ( WC_Payment_Gateways::instance() )->payment_gateways();
		return isset( $methods[ $id ] ) ? $methods[ $id ] : null;
	}
 
}
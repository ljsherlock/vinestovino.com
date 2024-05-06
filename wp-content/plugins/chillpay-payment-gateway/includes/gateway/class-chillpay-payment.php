<?php

require_once dirname(__FILE__,2).'/class-chillpay-hash-helper.php';
require_once dirname(__FILE__,2).'/chillpay-config.php';

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
	return;
}

if ( class_exists( 'ChillPay_Payment' ) ) {
	return;
}

abstract class ChillPay_Payment extends WC_Payment_Gateway {
	
	const CHARGE_ID = 'chillpay_charge_id';
	const IS_ORDER_PAY = 'is_order_pay';

	/**
	 * @var string PaymentStatus
	 */
	const STATUS_SUCCESS 		= '0';
	const STATUS_FAIL 			= '1';
	const STATUS_CANCEL 		= '2';
	const STATUS_ERROR 			= '3';
	const STATUS_TIMEOUT 		= '4';
	const STATUS_PENDING 		= '9';
	const STATUS_VOID_SUCCESS 	= '20';
	const STATUS_REFUND_SUCCESS = '21';
	const STATUS_REQUEST_REFUND = '22';
	const STATUS_SETTLEMENT 	= '23';
	const STATUS_VOID_FAIL 		= '24';
	const STATUS_REFUND_FAIL 	= '25';
	const STATUS_REQUEST_VOID   = '26';

	/**
	 * @var string OrderStatus
	 */
	const ORDER_STATUS_PENDING = 'pending';
	const ORDER_STATUS_PROCESSING = 'processing';
	const ORDER_STATUS_CANCELLED = 'cancelled';
	const ORDER_STATUS_SHIPPED = 'shipped';
	const ORDER_STATUS_COMPLETED = 'completed';

    /**
	 * @see woocommerce/includes/abstracts/abstract-wc-settings-api.php
	 *
	 * @var string
	 */
    public $id = 'chillpay';
    
    /**
	 * @see chillpay/includes/class-chillpay-setting.php
	 *
	 * @var ChillPay_Setting
	 */
    protected $chillpay_settings;
    
    /**
	 * Payment setting values.
	 *
	 * @var array
	 */
    public $payment_settings = array();

    /**
	 * @var array
	 */
	private $currency_subunits = array(
		'THB' => 100,
		'USD' => 100,
		'EUR' => 100,
		'JPY' => 100,
		'GBP' => 100,
		'AUD' => 100,
		'NZD' => 100,
		'HKD' => 100,
		'SGD' => 100,
		'CHF' => 100,
		'INR' => 100,
		'NOK' => 100,
		'DKK' => 100,
		'SEK' => 100,
		'CAD' => 100,
		'MYR' => 100,
		'CNY' => 100,
		'TWD' => 100,
		'MOP' => 100,
		'BND' => 100,
		'AED' => 100,
		'LKR' => 100,
		'BDT' => 100,
		'SAR' => 100,
		'NPR' => 100,
		'PKR' => 100,
		'ZAR' => 100,
		'PHP' => 100,
		'QAR' => 100,
		'VND' => 100,
		'OMR' => 100,
		'RUB' => 100,
		'KRW' => 100,
		'IDR' => 100,
		'KWD' => 100,
		'BHD' => 100,
	);
    
    /**
	 * @var ChillPay_Order|null
	 */
	protected $order;

	/**
	 * @see /includes/libraries/chillpay-php/lib/chillpay/res/ChillPayApiResource.php
	 * 
	 * @var ChillPayApiResource|null
	 */
	protected $chillpayApiObj;

	public function __construct() {
		$this->chillpay_settings   = new ChillPay_Setting;
		$this->payment_settings = $this->chillpay_settings->get_settings();
		$this->chillpayApiObj = new ChillPayApiResource($this->api_key(), $this->md5_key());

		add_action('wp_enqueue_scripts', array($this, 'chillpay_assets'));

		$this->callback_url = __(get_site_url() . '/?wc-api=' . $this->id . '_callback', 'chillpay');
		$this->result_url = __(get_site_url() . '/?wc-api=' . $this->id . '_result', 'chillpay');

		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');

		$this->init_form_fields();
		$this->init_settings();

		// add_action('woocommerce_api_' . $this->id . '_callback', 'ChillPay_Callback::execute'); 

		add_action('woocommerce_api_' . $this->id . '_callback', array($this, 'callback'));
		add_action('woocommerce_api_' . $this->id . '_result', array($this, 'result'));
		add_action('woocommerce_api_chillpay_callback', array($this, 'callback'));
		add_action('woocommerce_api_chillpay_result', array($this, 'result'));
		
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('wp_enqueue_scripts', array($this, 'chillpay_assets'));
		add_action('woocommerce_order_action_' . $this->id . '_sync_payment_status', array($this, 'sync_payment_status'));
	}

    /**
	 * @param  string|WC_Order $order
	 *
	 * @return ChillPay_Order|null
	 */
	public function load_order( $order ) {
		if ( $order instanceof WC_Order ) {
			$this->order = $order;
		} else {
			$this->order = wc_get_order( $order );
		}

		if ( ! $this->order ) {
			$this->order = null;
		}

		return $this->order;
    }
    
    /**
	 * @return ChillPay_Order|null
	 */
	public function order() {
		return $this->order;
    }
    
    public function setting_order() {
		return $this->chillpay_settings->setting_order();
	}

	/**
	 * Whether Sandbox (test) mode is enabled or not.
	 *
	 * @return bool
	 */
	public function is_test() {
		return $this->chillpay_settings->is_test();
	}

	/**
	 * Return ChillPay merchant code.
	 *
	 * @return string
	 */
	protected function merchant_code() {
		return $this->chillpay_settings->merchant_code();
	}

	/**
	 * Return ChillPay api key.
	 *
	 * @return string
	 */
	protected function api_key() {
		return $this->chillpay_settings->api_key();
	}

	/**
	 * Return ChillPay route no.
	 * 
	 * @return string
	*/
	protected function route_no() {
		return $this->chillpay_settings->route_no();
	}

	/**
	 * Return ChillPay lang code.
	 * 
	 * @return string
	*/
	// protected function lang_code() {
	// 	return $this->chillpay_settings->lang_code();
	// }

	/**
	 * Return ChillPay MD5 Secret Key.
	 *
	 * @return string
	 */
	protected function md5_key() {
		return $this->chillpay_settings->md5_key();
	}

	/**
	 * @param  string $currency
	 *
	 * @return bool
	 */
	protected function is_currency_support( $currency ) {
		if ( isset( $this->currency_subunits[ strtoupper( $currency ) ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param  int    $amount
	 * @param  string $currency
	 *
	 * @return int
	 */
	protected function format_amount_subunit($amount, $currency)
	{
		if (isset($this->currency_subunits[strtoupper($currency)])) {
			return $amount * $this->currency_subunits[$currency];
		}

		return $amount;
	}
	
	/**
	 * @param  int $order_id
	 *
	 * @see    WC_Payment_Gateway::process_payment( $order_id )
	 * @see    woocommerce/includes/abstracts/abstract-wc-payment-gateway.php
	 *
	 * @return array
	 */
	public function process_payment( $order_id )
	{
		if ( ! $order = $this->load_order( $order_id ) ) {
			return $this->invalid_order( $order_id );
		}

		$order_number = $order->get_order_number();
		/** backward compatible with WooCommerce v3.x series **/
		$order_id = version_compare( WC()->version, '3.5.5', '>=' ) ? $order->get_id() : $order->id;

		$order->add_order_note( sprintf( __( 'ChillPay: Processing a payment with %s', 'chillpay' ), $this->method_title ) );
		error_log('order :'. json_encode($this->order));

		try {
			$charge = $this->charge( $order_id, $this->order );
		} catch ( Exception $e ) {
			return $this->payment_failed( $e->getMessage() );
		}

		$result = json_decode($charge,true);
		$this->order->add_order_note( sprintf( __( 'ChillPay: Charge (ID: %s) has been created', 'chillpay' ), $result['TransactionId'] ) );
		//$this->set_order_transaction_id( $result['TransactionId'] );

		switch ( $result['Status'] ) {
			//Success
			case 0:
				$this->attach_charge_id_to_order( $result['TransactionId'] );

				$order->add_order_note( sprintf( __( 'ChillPay: Redirecting buyer out to %s', 'chillpay' ), esc_url( $result['PaymentUrl'] ) ) );

				/** backward compatible with WooCommerce v3.x series **/
				if ( version_compare( WC()->version, '3.5.5', '>=' ) ) {
					$order->set_transaction_id( $result['TransactionId'] );
					$order->save();
				} else {
					update_post_meta( $order->id, '_transaction_id', $result['TransactionId'] );
				}
				
				return array (
					'result'   => 'success',
					'redirect' => $result['PaymentUrl'],
				);
				break;

			// 1:Fail, 2:Error, 3:System Error
			case 1:
			case 2:
			case 3:
				$error_message = $this->get_error_message($result['Code']);
				throw new Exception($this->chillpayApiObj->get_system_error($result['Code'], $error_message));
				break;

			default:
				throw new Exception(
					sprintf(
						__( 'Please feel free to try submit your order again or contact our support team if you have any questions (Your temporary order id is \'%s\')', 'chillpay' ),
						$order_id
					)
				);
				break;
		}
	}

	/**
	 * @since  2.0
	 *
	 * @see    ChillPay_Payment::process_payment( $order_id )
	 *
	 * @param  int $order_id
	 * @param  WC_Order $order
	 *
	 * @return ChillPayCharge|ChillPayException
	 */
	abstract public function charge( $order_id, $order );
	
    /**
	 * Retrieve a charge by a given charge id (that attach to an order).
	 * Find some diff, then merge it back to WooCommerce system.
	 *
	 * @param  WC_Order $order WooCommerce's order object
	 *
	 * @return void
	 *
	 * @see    WC_Meta_Box_Order_Actions::save( $post_id, $post )
	 * @see    woocommerce/includes/admin/meta-boxes/class-wc-meta-box-order-actions.php
	 */
	public function sync_payment_status( $order ) {
		$this->load_order( $order );

		try
		{
			$order_status = $order->get_status();
			$update = 1;
			if ((strpos($order_status, self::ORDER_STATUS_PROCESSING) !== false) || (strpos($order_status, self::ORDER_STATUS_SHIPPED ) !== false) || (strpos($order_status, self::ORDER_STATUS_COMPLETED) !== false)) {
				$update = 0;
			}

			$chillpay_tnx_id = get_post_meta( $this->order->get_id(), 'chillpay_charge_id', true );
			$response_data = $this->chillpayApiObj->inquiry_payment_status($chillpay_tnx_id);
			
			$orderNo = $this->order->get_order_number();
			$is_setting_order = $this->chillpay_settings->setting_order();

			if (!is_null($response_data)) {
				if ($response_data->OrderNo == $orderNo) {
					if ( $response_data->PaymentStatus == self::STATUS_SUCCESS && $update === 1) //Payment : Success (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment successful.<br/>An amount %1$s %2$s has been paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
						$order->update_status( 'processing' );
					}
					elseif($response_data->PaymentStatus == self::STATUS_SUCCESS && $update === 0) //Payment : Success
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_FAIL && $update === 1 && (!$is_setting_order)) //Payment : Fail (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment failed (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'failed' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_FAIL && $update === 1 && ($is_setting_order)) //Payment : Fail (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment cancelled (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_FAIL && $update === 0) //Payment : Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_CANCEL && $update === 1) //Payment : Cancel (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment cancelled (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_CANCEL && $update === 0) //Payment : Cancel
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_ERROR && $update === 1 && (!$is_setting_order)) //Payment : Error (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment error (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'failed' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_ERROR && $update === 1 && ($is_setting_order)) //Payment : Error (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment error (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_ERROR && $update === 0) //Payment : Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_TIMEOUT && $update === 1) //Payment : Transaction Timeout (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Transaction Timeout (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_TIMEOUT && $update === 0) //Payment : Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_PENDING && $update === 1) //Payment : Request (Update)
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Transaction Pending (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_PENDING && $update === 0) //Payment : Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Order has already paid (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_VOID_SUCCESS) //Payment : Void Success
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Voided an amount %1$s %2$s (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_REQUEST_VOID) //Payment : Request to Void
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Request to Void (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_REFUND_SUCCESS) //Payment : Refund Success
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Refunded an amount %1$s %2$s (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
						$order->update_status( 'refunded' );
					}
					elseif ($response_data->PaymentStatus == self::STATUS_REQUEST_REFUND) //Payment : Request to Refund
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Request to Refund (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_SETTLEMENT) //Payment : Settlement Success
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Settlement successful (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_VOID_FAIL) //Payment : Void Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Void failed (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					elseif ($response_data->PaymentStatus == self::STATUS_REFUND_FAIL) //Payment : Refund Fail
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Refund failed (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}
					else
					{
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Sync failed (manual sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
					}	
				}
			}
			else {
				$order->add_order_note(
					sprintf(
						wp_kses(
							__( 'ChillPay: Sync failed (manual sync).', 'chillpay' ),
							array( 'br' => array() )
						)
					)
				);
			}					
		} catch (Exception $e) {
			$order->add_order_note(
				sprintf(
					wp_kses(
						__( 'ChillPay: Sync failed .<br/>%s (manual sync).', 'chillpay' ),
						array( 'br' => array() )
					),
					$e->getMessage()
				)
			);
		}		
	}

	public function auto_sync_payment( $order_id )
	{
		$order = wc_get_order( $order_id );
		$orderNo = $order->get_order_number();

		$order_get_status = $order->get_status();
		$order_status = (int)self::STATUS_PENDING;
		$update = 1;

		if (strpos($order_get_status, 'processing' ) !== false) {
			$order_status = (int)self::STATUS_SUCCESS;
			$update = 0;
		} elseif ((strpos($order_get_status, 'shipped') !== false) || (strpos($order_get_status, 'completed') != false)) {
			$update = 0;
		} else if (strpos($order_get_status, 'cancelled') !== false) {
			$order_status = (int)self::STATUS_CANCEL;
		}

		$chillpay_tnx_id = get_post_meta( $this->order->get_id(), 'chillpay_charge_id', true );
		$response_data = $this->chillpayApiObj->inquiry_payment_status($chillpay_tnx_id);

		$is_setting_order = $this->chillpay_settings->setting_order();

		if (!is_null($response_data)) {
			error_log('(auto sync) orderNo['.$orderNo.'] order_status : ' .$order_get_status. ' | chillpay status : '.$response_data->PaymentStatus);
			if ($response_data->OrderNo == $orderNo) {
				/*if ($response_data->PaymentStatus == $order_status) {
					return "ok";
				} else {*/
					if ( ($response_data->PaymentStatus == (int)self::STATUS_SUCCESS && ( $update === 1 || $order_status == self::STATUS_PENDING ))) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment successful.<br/>An amount %1$s %2$s has been paid (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
						$order->update_status( 'processing' );
	
						return "complete";
					}
					elseif ( $response_data->PaymentStatus == (int)self::STATUS_SUCCESS && $update === 0) {
						return "paid";
					}
					elseif ( $response_data->PaymentStatus == (int)self::STATUS_FAIL && $update === 1 && (!$is_setting_order) ) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment failed (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'failed' );
						
						return "failed";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_FAIL && $update === 1 && ($is_setting_order)) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment cancelled (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
	
						return "cancelled";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_FAIL && $update === 0) {
						return "paid";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_CANCEL && $update === 1) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment cancelled (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
	
						return "cancelled";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_CANCEL && $update === 0) {
						return "paid";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_ERROR && $update === 1 && (!$is_setting_order)) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment error (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'failed' );
	
						return "failed";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_ERROR && $update === 1 && ($is_setting_order)) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Payment error (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
	
						return "cancelled";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_ERROR && $update === 0) {
						return "paid";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_TIMEOUT && $update === 1) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Transaction Timeout (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						$order->update_status( 'cancelled' );
	
						return "cancelled";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_TIMEOUT && $update === 0) {
						return "paid";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_PENDING && $update === 1) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Transaction Pending (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
	
						return "request";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_PENDING && $update === 0) {
						return "paid";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_VOID_SUCCESS) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Voided an amount %1$s %2$s (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
						
						return "other";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_REFUND_SUCCESS) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Refunded an amount %1$s %2$s (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								),
								$order->get_total(),
								$order->get_currency()
							)
						);
						$order->update_status( 'refunded' );
						
						return "other";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_REQUEST_REFUND) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Request to Refund (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						
						return "other";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_SETTLEMENT) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Settlement successful (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						
						return "other";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_VOID_FAIL) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Void failed (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						
						return "other";
					}
					elseif ($response_data->PaymentStatus == (int)self::STATUS_REFUND_FAIL) {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Refund failed (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						
						return "other";
					}
					else {
						$order->add_order_note(
							sprintf(
								wp_kses(
									__( 'ChillPay: Sync failed (auto sync).', 'chillpay' ),
									array( 'br' => array() )
								)
							)
						);
						return "error";
					}
				}
			/*}
			else {
				$order->add_order_note(
					sprintf(
						wp_kses(
							__( 'ChillPay: Sync failed (auto sync).<br/>Cannot read the payment status. Please try sync again or contact ChillPay support team at support@chillpay.co if you have any questions.', 'chillpay' ),
							array( 'br' => array() )
						)
					)
				);
				return "error";
			}*/
		}
		else
		{
			if ((strpos($order_get_status, 'processing' ) !== false) || (strpos($order_get_status, 'shipped') !== false) || (strpos($order_get_status, 'completed') != false)) {
				return "paid";
			} elseif (strpos($order_get_status, 'cancelled') !== false) {
				return "cancelled";
			} elseif (strpos($order_get_status, 'pending') !== false) {
				return "request";
			} elseif (strpos($order_get_status, 'failed') !== false) {
				return "failed";
			}
		}

	}

	/**
	 * @param  WC_Order $order WooCommerce's order object
	 * 
	 * @return void
	 */
	public function result() {	
		$orderNo = isset($_POST['orderNo']) ? sanitize_text_field($_POST['orderNo']) : "";
		$find_order = $this->find_order_by_order_number($orderNo);
		$order_id = $find_order->id;
		$status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : "";
		$respCode = isset($_POST['respCode']) ? sanitize_text_field($_POST['respCode']) : "";

		error_log('(result) orderNo['.$orderNo.'] respCode : '.$respCode.' | status : '.$status);

		if (!isset($orderNo) || !$order = $this->load_order($order_id)) {
			wc_add_notice(
				wp_kses(
					__('We cannot validate your payment result:<br/>Note that your payment might already has been processed. Please contact our support team if you have any questions.', 'chillpay'),
					array('br' => array())
				),
				'error'
			);

			header('Location: ' . wc_get_cart_url());
			die();
		}

		try {

			$auto_sync = $this->auto_sync_payment($order_id);

			if (($auto_sync === 'ok' && $respCode == self::STATUS_SUCCESS) || $auto_sync === 'complete') {
				wp_redirect( $this->order->get_checkout_order_received_url() );
				die();
			}
			elseif (($auto_sync === 'ok' && $respCode == self::STATUS_FAIL) || $auto_sync === 'failed') {
				wc_add_notice( wp_kses('Payment Failed.', 'chillpay'), 'error');

				$is_order_pay_data = get_post_meta( $order_id, self::IS_ORDER_PAY, true );
				if ($is_order_pay_data === 'yes')
				{
					$order_checkout_url = wc_get_endpoint_url( 'order-pay', $this->order->get_id(), wc_get_checkout_url() );
					$order_checkout_url = add_query_arg( 'pay_for_order', 'true', $order_checkout_url );
					$order_checkout_url = add_query_arg( 'key', $this->order->get_order_key(), $order_checkout_url );
					wp_redirect( $order_checkout_url );
					die();
				}		

				wp_redirect( wc_get_checkout_url() );
				die();
			}
			elseif (($auto_sync === 'ok' && $respCode == self::STATUS_CANCEL) || $auto_sync === 'cancelled') {
				wc_add_notice( wp_kses('Payment cancelled.', 'chillpay'), 'notice');

				$is_order_pay_data = get_post_meta( $order_id, self::IS_ORDER_PAY, true );
				if ($is_order_pay_data === 'yes')
				{
					$order_checkout_url = wc_get_endpoint_url( 'order-pay', $this->order->get_id(), wc_get_checkout_url() );
					$order_checkout_url = add_query_arg( 'pay_for_order', 'true', $order_checkout_url );
					$order_checkout_url = add_query_arg( 'key', $this->order->get_order_key(), $order_checkout_url );
					wp_redirect( $order_checkout_url );
					die();
				}

				wp_redirect( wc_get_checkout_url() );
				die();
			}
			elseif (($auto_sync === 'ok' && $respCode == self::STATUS_PENDING) || $auto_sync === 'request') {			
				wc_add_notice( wp_kses('Waiting for payment.', 'chillpay'), 'notice');

				$is_order_pay_data = get_post_meta( $order_id, self::IS_ORDER_PAY, true );
				if ($is_order_pay_data === 'yes')
				{
					$order_checkout_url = wc_get_endpoint_url( 'order-pay', $this->order->get_id(), wc_get_checkout_url() );
					$order_checkout_url = add_query_arg( 'pay_for_order', 'true', $order_checkout_url );
					$order_checkout_url = add_query_arg( 'key', $this->order->get_order_key(), $order_checkout_url );
					wp_redirect( $order_checkout_url );
					die();
				}

				wp_redirect( wc_get_checkout_url() );
				die();
			}
			elseif ($auto_sync === 'other' || $auto_sync === 'paid') {
				wp_redirect( $this->order->get_checkout_order_received_url() );
				die();
			}
			else {
				throw new Exception( 'Status : ' . $status . ' (Code: ' . $respCode . ')' );
			}
		} catch (Exception $e) {
			wc_add_notice(
				sprintf(
					wp_kses(
						__( 'Seems we cannot process your payment properly:<br/>%s', 'chillpay' ),
						array( 'br' => array() )
					),
					$e->getMessage()
				),
				'error'
			);

			$is_order_pay_data = get_post_meta( $order_id, self::IS_ORDER_PAY, true );
			if ($is_order_pay_data === 'yes')
			{
				$order_checkout_url = wc_get_endpoint_url( 'order-pay', $this->order->get_id(), wc_get_checkout_url() );
				$order_checkout_url = add_query_arg( 'pay_for_order', 'true', $order_checkout_url );
				$order_checkout_url = add_query_arg( 'key', $this->order->get_order_key(), $order_checkout_url );
				wp_redirect( $order_checkout_url );
				die();
			}

			wp_redirect( wc_get_checkout_url() );
			die();
		}
	}

	public function callback()
	{
		ChillPay_Callback::execute($_POST);
	}
	
	/**
	 * @param int|mixed $order_id
	 */
	protected function invalid_order( $order_id ) {
		$message = wp_kses( __(
			'We cannot process your payment.<br/>
			Note that nothing wrong by you, this might be from our store issue.<br/><br/>
			Please feel free to try submit your order again or report our support team that you have found this problem (Your temporary order id is \'%s\')', 
			'chillpay'
		), array( 'br' => array() ) );

		wc_add_notice( sprintf( $message, $order_id ), 'error' );
	}

	/**
	 * Set an order transaction id
	 *
	 * @param string $transaction_id  ChillPay charge id.
	 */
	protected function set_order_transaction_id( $transaction_id )
	{
		/** backward compatible with WooCommerce v2.x series **/
		if ( version_compare( WC()->version, '3.5.5', '>=' ) ) {
			$this->order()->set_transaction_id( $transaction_id );
			$this->order()->save();
		} else {
			update_post_meta( $this->order()->id, '_transaction_id', $transaction_id );
		}
	}

	/**
	 * Retrieve an attached charge id.
	 *
	 * @return string
	 */
	public function get_charge_id_from_order()
	{
		if ( $charge_id = $this->order()->get_transaction_id() ) {
			return $charge_id;
		}

		$order_id  = version_compare( WC()->version, '3.5.5', '>=' ) ? $this->order()->get_id() : $this->order()->id;
		$charge_id = get_post_meta( $order_id, self::CHARGE_ID, true );

		if ( empty( $charge_id ) ) {
			$charge_id = $this->deprecated_get_charge_id_from_post();
		}

		return $charge_id;
	}

	/**
	 * Add Custom Fields [is_order_pay] into an order.
	 */

	public function attach_is_order_pay_to_order( $order_id , $is_order_pay )
	{
		$is_order_pay_data = get_post_meta( $order_id, self::IS_ORDER_PAY, true );
		if(isset($is_order_pay_data))
		{
			delete_post_meta( $order_id, self::IS_ORDER_PAY );
		}

		add_post_meta( $order_id, self::IS_ORDER_PAY, $is_order_pay );
	}

	/**
	 * Attach a charge id into an order.
	 *
	 * @param string $charge_id
	 */
	
	public function attach_charge_id_to_order( $charge_id )
	{
		/** backward compatible with WooCommerce v3.x series **/
		$order_id = version_compare( WC()->version, '3.5.5', '>=' ) ? $this->order()->get_id() : $this->order()->id;

		if ( $this->get_charge_id_from_order() ) {
			delete_post_meta( $order_id, self::CHARGE_ID );
		}

		add_post_meta( $order_id, self::CHARGE_ID, $charge_id );
		//$this->set_order_transaction_id( $charge_id );
	}

	/**
	 * @param  array $params
	 *
	 * @return ChillPayCharge
	 */
	public function sale( $params ) {
		$params = array_merge( 
			$params, 
			array('merchant_code' => $this->merchant_code(), 'route_no' => $this->route_no())//, 'lang_code' => $this->lang_code())
		);

		return $this->chillpayApiObj->create_payment($this->chillpay_settings->get_payment_url(), $params);
	}	

	/**
	 * Retrieve a charge id from a post.
	 *
	 * @deprecated 2.0  No longer assign a new charge id with new post.
	 *
	 * @return     string
	 */
	protected function deprecated_get_charge_id_from_post()
	{
		/** backward compatible with WooCommerce v3.x series **/
		$order_id  = version_compare( WC()->version, '3.5.5', '>=' ) ? $this->order()->get_id() : $this->order()->id;

		$posts = get_posts(
			array(
				'post_type'  => 'chillpay_charge_item',
				'meta_query' => array(
					array(
						'key'     => '_wc_order_id',
						'value'   => $order_id,
						'compare' => '='
					)
				)
			)
		);

		if ( empty( $posts ) ) {
			return '';
		}


		$post  = $posts[0];
		$value = get_post_custom_values( '_chillpay_charge_id', $post->ID );

		if ( ! is_null( $value ) && ! empty( $value ) ) {
			return $value[0];
		}
	}

	/**
	 * @param string $order_number
	 */
	protected function find_order_by_order_number( $order_number )
	{
		$order_number = ltrim( $order_number, '#' );

		// search for the order by custom order number
		$query_args = array(
			'numberposts' => 1,
			'meta_key'    => '_order_number_formatted',
			'meta_value'  => $order_number,
			'post_type'   => 'shop_order',
			'post_status' => 'any',
			'fields'      => 'ids',
		);

		$posts            = get_posts( $query_args );
		list( $order_id ) = ! empty( $posts ) ? $posts : null;

		// order was found
		if ( null !== $order_id ) {
			return wc_get_order( $order_id );
		}

		return wc_get_order( $order_number );
	}

	protected function get_error_message ( $result_code )
	{
		$message = '';

		if (strpos($result_code, '1004') !== false) {
			$message = 'Please select your payment method.';
		} elseif (strpos($result_code, '1009') !== false) {
			$message = 'Invalid Mobile Phone Number.<br>Please recheck Mobile Phone number and try again.<br>In case unsuccessful transaction, please contact merchant to inform this problem or contact Customer Support.';
		} elseif (strpos($result_code, '2013') !== false) {
			$message = 'Your mobile phone no. not registered with K PLUS mobile application.<br>Please verify mobile phone no. and try again.<br>Invalid case, please contact merchant to inform this problem or contact Customer Support.';
		} else { 
			$message = 'Merchant sent invalid information..<br> Please contact merchant to inform this problem or contact Customer Support.';
		}

		return $message;
	}
}
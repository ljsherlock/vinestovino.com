<?php

require_once dirname(__FILE__) . '/chillpay-config.php';

defined('ABSPATH') or die('No direct script access allowed.');

if (class_exists('ChillPay_Setting')) {
	return;
}

class ChillPay_Setting
{
	private $test_merchant_route_url	= CHILLPAY_SANDBOX_MERCHANT_ROUTE_URL;
	private $live_merchant_route_url	= CHILLPAY_PROD_MERCHANT_ROUTE_URL;

	private $test_payment_url	= CHILLPAY_SANDBOX_PAYMENT_URL;
	private $live_payment_url	= CHILLPAY_PROD_PAYMENT_URL;

	private $test_payment_status_url	= CHILLPAY_SANDBOX_PAYMENT_STATUS_URL;
	private $live_payment_status_url	= CHILLPAY_PROD_PAYMENT_STATUS_URL;

	private $test_merchant_url = CHILLPAY_SANDBOX_MERCHANT_URL;
	private $live_merchant_url = CHILLPAY_PROD_MERCHANT_URL;

	/**
	 * @var null | array
	 */
	public $settings;

	/**
	 * @var null
	 */
	public $update_date;

	/**
	 * @var null | array
	 */
	public $internetbanking_fees;

	/**
	 * @var null | array
	 */
	public $mobilebanking_fees;

	/**
	 * @var null | array
	 */
	public $creditcard_fees;

	/**
	 * @var null | array
	 */
	public $ewallet_fees;

	/**
	 * @var null | array
	 */
	public $qrcode_fees;

	/**
	 * @var null | array
	 */
	public $billpayment_fees;

	/**
	 * @var null | array
	 */
	public $kiosk_machine_fees;

	/**
	 * @var null | array
	 */
	public $installment_fees;

	/**
	 * @since 1.0
	 */
	public function __construct()
	{
		$this->settings = $this->get_payment_settings('chillpay');
		$this->update_date = $this->get_update_date_data('chillpay');
		$this->internetbanking_fees = $this->get_internetbanking_fee_data('chillpay_internetbanking');
		$this->mobilebanking_fees = $this->get_mobilebanking_fee_data('chillpay_mobilebanking');
		$this->creditcard_fees = $this->get_creditcard_fee_data('chillpay_creditcard');
		$this->ewallet_fees = $this->get_ewallet_fee_data('chillpay_ewallet');
		$this->qrcode_fees = $this->get_qrcode_fee_data('chillpay_qrcode');
		$this->billpayment_fees = $this->get_billpayment_fee_data('chillpay_billpayment');
		$this->kiosk_machine_fees = $this->get_kiosk_machine_fee_data('chillpay_kiosk_machine');
		$this->installment_fees = $this->get_installment_fee_data('chillpay_installment');
		$this->pay_with_points_fees = $this->get_installment_fee_data('chillpay_pay_with_points');
	}

	/**
	 * @return array
	 *
	 * @since  1.0
	 */
	protected function get_default_settings()
	{
		return array(
			'setting_order'			=> 'no',
			'sandbox'          		=> 'yes',
			'test_merchant_code'  => '',
			'test_api_key' 				=> '',
			'test_md5_secret_key'	=> '',
			'test_route_no'   		=> '',
			'live_merchant_code'	=> '',
			'live_api_key' 				=> '',
			'live_md5_secret_key'	=> '',
			'live_route_no'   		=> '',
			//'live_lang_code'   		=> '',
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_installment_absorb_by()
	{
		return array(
			'channel_code'	=> '',
			'absorb_by'		=> '',
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_internetbanking_fees()
	{
		return array(
			'internetbank_scb'		  => array(),
			'internetbank_ktb'  	  => array(),
			'internetbank_bay'  	  => array(),
			'internetbank_bbl' 		  => array(),
			'internetbank_ttb'	  => array(),
		);
	}

	protected function get_default_update_date()
	{
		return '';
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_mobilebanking_fees()
	{
		return array(
			'payplus_kbank'	=> array(),
			'mobilebank_scb' => array(),
			'mobilebank_bay' => array(),
			'mobilebank_ktb' => array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_creditcard_fees()
	{
		return array(
			'creditcard_kbank'	=> array(),
			'creditcard_tbank'	=> array(),
			'creditcard_bbl'	=> array(),
			'creditcard_scb'	=> array(),
			'creditcard'		=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_ewallet_fees()
	{
		return array(
			'epayment_linepay'		=> array(),
			'epayment_truemoney'	=> array(),
			'epayment_alipay'		=> array(),
			'epayment_wechatpay'	=> array(),
			'epayment_shopeepay'	=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_qrcode_fees()
	{
		return array(
			'bank_qrcode'	=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_billpayment_fees()
	{
		return array(
			'billpayment_bigc'		=> array(),
			'billpayment_cenpay'	=> array(),
			'billpayment_counter'	=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_kiosk_machine_fees()
	{
		return array(
			'billpayment_boonterm'	=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	protected function get_default_installment_fees()
	{
		return array(
			'installment_kbank'		  => array(),
			'installment_ktc_flexi'   => array(),
			'installment_scb' 		  => array(),
			'installment_krungsri'	  => array(),
			'installment_firstchoice' => array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  2.1
	 */
	protected function get_default_pay_with_points_fees()
	{
		return array(
			'point_ktc_forever'	=> array(),
		);
	}

	/**
	 * @return array
	 *
	 * @since  1.0
	 */
	public function get_settings()
	{
		return $this->settings;
	}

	public function get_update_date()
	{
		return $this->update_date;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_internetbanking_fees()
	{
		return $this->internetbanking_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_mobilebanking_fees()
	{
		return $this->mobilebanking_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_creditcard_fees()
	{
		return $this->creditcard_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_ewallet_fees()
	{
		return $this->ewallet_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_qrcode_fees()
	{
		return $this->qrcode_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_billpayment_fees()
	{
		return $this->billpayment_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_kiosk_machine_fees()
	{
		return $this->kiosk_machine_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_installment_fees()
	{
		return $this->installment_fees;
	}

	/**
	 * @return array
	 *
	 * @since  2.1
	 */
	public function get_pay_with_points_fees()
	{
		return $this->pay_with_points_fees;
	}

	/**
	 * Returns the payment gateway settings option name
	 *
	 * @param  string $payment_id  Payment ID can be found at each of gateway classes (includes/gateway).
	 *
	 * @return string              The payment gateway settings option name.
	 *
	 * @since  1.0
	 */
	protected function get_payment_method_settings_name($payment_id = 'chillpay')
	{
		return 'woocommerce_' . $payment_id . '_settings';
	}

	protected function get_update_date_name($payment_id = 'chillpay')
	{
		return 'woocommerce_' . $payment_id . '_update_date';
	}

	protected function get_internetbanking_fee_name($payment_id = 'chillpay_internetbanking')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_mobilebanking_fee_name($payment_id = 'chillpay_mobilebanking')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_creditcard_fee_name($payment_id = 'chillpay_creditcard')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_ewallet_fee_name($payment_id = 'chillpay_ewallet')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_qrcode_fee_name($payment_id = 'chillpay_qrcode')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_billpayment_fee_name($payment_id = 'chillpay_billpayment')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_kiosk_machine_fee_name($payment_id = 'chillpay_kiosk_machine')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_installment_fee_name($payment_id = 'chillpay_installment')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	protected function get_pay_with_points_fee_name($payment_id = 'chillpay_pay_with_points')
	{
		return 'woocommerce_' . $payment_id . '_fees';
	}

	/**
	 * Get ChillPay settings from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  1.0
	 */
	public function get_payment_settings($payment_id)
	{
		if ($options = get_option($this->get_payment_method_settings_name($payment_id))) {
			return array_merge(
				$this->get_default_settings(),
				$options
			);
		}

		return $this->get_default_settings();
	}

	public function get_update_date_data($payment_id)
	{
		if ($options = get_option($this->get_update_date_name($payment_id))) {
			return $options;
		}

		return $this->get_default_update_date();
	}

	/**
	 * Get ChillPay internetbanking fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_internetbanking_fee_data($payment_id)
	{
		if ($options = get_option($this->get_internetbanking_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_internetbanking_fees(),
				$options
			);
		}

		return $this->get_default_internetbanking_fees();
	}

	/**
	 * Get ChillPay mobilebanking fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_mobilebanking_fee_data($payment_id)
	{
		if ($options = get_option($this->get_mobilebanking_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_mobilebanking_fees(),
				$options
			);
		}

		return $this->get_default_mobilebanking_fees();
	}

	/**
	 * Get ChillPay creditcard fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_creditcard_fee_data($payment_id)
	{
		if ($options = get_option($this->get_creditcard_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_creditcard_fees(),
				$options
			);
		}

		return $this->get_default_creditcard_fees();
	}

	/**
	 * Get ChillPay ewallet fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_ewallet_fee_data($payment_id)
	{
		if ($options = get_option($this->get_ewallet_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_ewallet_fees(),
				$options
			);
		}

		return $this->get_default_ewallet_fees();
	}

	/**
	 * Get ChillPay ewallet fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_qrcode_fee_data($payment_id)
	{
		if ($options = get_option($this->get_qrcode_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_qrcode_fees(),
				$options
			);
		}

		return $this->get_default_qrcode_fees();
	}

	/**
	 * Get ChillPay billpayment fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_billpayment_fee_data($payment_id)
	{
		if ($options = get_option($this->get_billpayment_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_billpayment_fees(),
				$options
			);
		}

		return $this->get_default_billpayment_fees();
	}

	/**
	 * Get ChillPay kiosk machine fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_kiosk_machine_fee_data($payment_id)
	{
		if ($options = get_option($this->get_kiosk_machine_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_kiosk_machine_fees(),
				$options
			);
		}

		return $this->get_default_kiosk_machine_fees();
	}

	/**
	 * Get ChillPay installment fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.0
	 */
	public function get_installment_fee_data($payment_id)
	{
		if ($options = get_option($this->get_installment_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_installment_fees(),
				$options
			);
		}

		return $this->get_default_installment_fees();
	}

	/**
	 * Get ChillPay Pay With Points fees from 'wp_options' table.
	 *
	 * @param  string $payment_id
	 *
	 * @return array
	 *
	 * @since  2.1
	 */
	public function get_pay_with_points_fee_data($payment_id)
	{
		if ($options = get_option($this->get_pay_with_points_fee_name($payment_id))) {
			return array_merge(
				$this->get_default_pay_with_points_fees(),
				$options
			);
		}

		return $this->get_default_pay_with_points_fees();
	}

	/**
	 * @param  array $data
	 *
	 * @return array
	 *
	 * @since  1.0
	 */
	public function update_settings($data)
	{
		$data            = array_intersect_key($data, $this->get_default_settings());
		$data['sandbox'] = isset($data['sandbox']) && !is_null($data['sandbox']) ? 'yes' : 'no';
		$data['setting_order'] = isset($data['setting_order']) && !is_null($data['setting_order']) ? 'yes' : 'no';

		array_walk($data, function (&$input, $key) {
			$input = sanitize_text_field($input);
		});

		$this->settings = array_merge(
			$this->settings,
			$data
		);

		update_option($this->get_payment_method_settings_name('chillpay'), $this->settings);

		$this->APIFee_V2();
		$this->update_fee();
		error_log('update fee [setting]');
	}

	public function update_fee()
	{
		$update_date = $this->get_update_date();
		$now = date('Y/m/d');
		if (empty($update_date) || strcmp($update_date, $now) !== 0) {
			$this->update_date = $now;
			update_option($this->get_update_date_name('chillpay'), $this->update_date);
		}

		update_option($this->get_internetbanking_fee_name('chillpay_internetbanking'), $this->internetbanking_fees);
		update_option($this->get_mobilebanking_fee_name('chillpay_mobilebanking'), $this->mobilebanking_fees);
		update_option($this->get_creditcard_fee_name('chillpay_creditcard'), $this->creditcard_fees);
		update_option($this->get_ewallet_fee_name('chillpay_ewallet'), $this->ewallet_fees);
		update_option($this->get_qrcode_fee_name('chillpay_qrcode'), $this->qrcode_fees);
		update_option($this->get_billpayment_fee_name('chillpay_billpayment'), $this->billpayment_fees);
		update_option($this->get_kiosk_machine_fee_name('chillpay_kiosk_machine'), $this->kiosk_machine_fees);
		update_option($this->get_installment_fee_name('chillpay_installment'), $this->installment_fees);
		update_option($this->get_pay_with_points_fee_name('chillpay_pay_with_points'), $this->pay_with_points_fees);
	}

	/**
	 *
	 * @return bool
	 *
	 * @since  1.0
	 */
	public function setting_order()
	{
		$setting_order = $this->settings['setting_order'];

		return isset($setting_order) && $setting_order == 'yes';
	}

	/**
	 * Whether Sandbox (test) mode is enabled or not.
	 *
	 * @return bool
	 *
	 * @since  1.0
	 */
	public function is_test()
	{
		$sandbox = $this->settings['sandbox'];

		return isset($sandbox) && $sandbox == 'yes';
	}

	/**
	 * Return ChillPay merchant code.
	 *
	 * @return string
	 *
	 * @since  1.0
	 */
	public function merchant_code()
	{
		if ($this->is_test()) {
			return $this->settings['test_merchant_code'];
		}

		return $this->settings['live_merchant_code'];
	}

	/**
	 * Return ChillPay api key.
	 *
	 * @return string
	 *
	 * @since  1.0
	 */
	public function api_key()
	{
		if ($this->is_test()) {
			return $this->settings['test_api_key'];
		}

		return $this->settings['live_api_key'];
	}

	/**
	 * Return ChillPay md5 secret key.
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	public function md5_key()
	{
		if ($this->is_test()) {
			return $this->settings['test_md5_secret_key'];
		}

		return $this->settings['live_md5_secret_key'];
	}

	/**
	 * Return ChillPay route no.
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	public function route_no()
	{
		if ($this->is_test()) {
			return $this->settings['test_route_no'];
		}

		return $this->settings['live_route_no'];
	}

	// public function lang_code()
	// {
	// 	return $this->settings['live_lang_code'];
	// }

	public function get_merchant_route_url()
	{
		if ($this->is_test()) {
			return $this->test_merchant_route_url;
		}

		return $this->live_merchant_route_url;
	}

	public function get_payment_url()
	{
		if ($this->is_test()) {
			return $this->test_payment_url;
		}

		return $this->live_payment_url;
	}

	public function get_payment_status_url()
	{
		if ($this->is_test()) {
			return $this->test_payment_status_url;
		}

		return $this->live_payment_status_url;
	}

	public function get_merchant_url()
	{
		if ($this->is_test()) {
			return $this->test_merchant_url;
		}

		return $this->live_merchant_url;
	}

	public function get_currency_items()
	{
		$currency_items = array(
			'THB' => '764',
			'USD' => '840',
			'EUR' => '978',
			'JPY' => '392',
			'GBP' => '826',
			'AUD' => '036',
			'NZD' => '554',
			'HKD' => '344',
			'SGD' => '702',
			'CHF' => '756',
			'CNY' => '156',
			'MYR' => '458',
			/*
			'INR' => '356',
			'NOK' => '578',
			'DKK' => '208',
			'SEK' => '752',
			'CAD' => '124',
			'MYR' => '458',
			'CNY' => '156',
			'TWD' => '901',
			'MOP' => '446',
			'BND' => '096',
			'AED' => '784',
			'LKR' => '144',
			'BDT' => '050',
			'SAR' => '682',
			'NPR' => '524',
			'PKR' => '586',
			'ZAR' => '710',
			'PHP' => '608',
			'QAR' => '634',
			'VND' => '704',
			'OMR' => '512',
			'RUB' => '643',
			'KRW' => '410',
			'IDR' => '360',
			'KWD' => '414',
			'BHD' => '048',*/
		);

		return $currency_items;
	}

	public function APIFee_V2()
	{
		$is_sandbox = $this->settings['sandbox'] === 'yes';
		$post_data = '';

		//---Check Mode---//
		if ($is_sandbox) {
			$service_url = CHILLPAY_SANDBOX_MERCHANT_FEE_URL;

			$merchant_code = trim($this->settings['test_merchant_code']);
			$api_key = trim($this->settings["test_api_key"]);
			$route_no = intval($this->settings["test_route_no"]);
			$md5_secret_key = trim($this->settings["test_md5_secret_key"]);
		} else {
			$service_url = CHILLPAY_PROD_MERCHANT_FEE_URL;

			$merchant_code = trim($this->settings['live_merchant_code']);
			$api_key = trim($this->settings["live_api_key"]);
			$route_no = intval($this->settings["live_route_no"]);
			$md5_secret_key = trim($this->settings["live_md5_secret_key"]);
		}

		$installment_settings = get_option('woocommerce_chillpay_installment_settings');

		/*
		$array_absorb_by = array(
			array(
				"ChannelCode" => "installment_kbank",
				"AbsorbBy" => $installment_settings['absorb_by_installment_kbank'],
			),

			array(
				"ChannelCode" => "installment_ktc_flexi",
				"AbsorbBy" => $installment_settings['absorb_by_installment_ktc_flexi'],
			),

			array(
				"ChannelCode" => "installment_scb",
				"AbsorbBy" => $installment_settings['absorb_by_installment_scb'],
			),

			array(
				"ChannelCode" => "installment_krungsri",
				"AbsorbBy" => $installment_settings['absorb_by_installment_krungsri'],
			),

			array(
				"ChannelCode" => "installment_firstchoice",
				"AbsorbBy" => $installment_settings['absorb_by_installment_firstchoice'],
			),
		);

		$absorb_by = json_encode($array_absorb_by);*/

		$str_data = $merchant_code . $route_no . $api_key . $md5_secret_key;
		$checksum = md5($str_data);
		$post_data = "MerchantCode={$merchant_code}&RouteNo={$route_no}&ApiKey={$api_key}&CheckSum={$checksum}";
		
		error_log('Merchant Fee service_url:'.$service_url);
		error_log('Merchant Fee post_data:'.$post_data);

		$headers = array(
			'Cache-Control' => 'no-cache',
			'Content-Type' => 'application/x-www-form-urlencoded',
			'User-Agent' => $md5_secret_key,
		);
		$args = array(
			'body'        => $post_data,
			'timeout'     => CHILLPAY_TIMEOUT,
			'redirection' => CHILLPAY_TIMEOUT,
			'httpversion' => CURL_HTTP_VERSION_1_1,
			'headers'     => $headers,
		);

		$json_response = wp_remote_post($service_url, $args);
        $http_body = wp_remote_retrieve_body($json_response);

		if (is_wp_error($json_response)) {
			error_log('wp_remote_post: ' . json_encode($json_response) . ' //' . get_class($json_response));
		}

		if ($json_response['response']['code'] !== 200) {
			error_log('wp_remote_post: ' . json_encode($json_response));
		}

		$response_data = json_decode($http_body);
		$data_merchant_fee = $response_data->MerchantFees;
		$en_data = json_encode($data_merchant_fee);
		$de_data = json_decode($en_data, true);
		$count = $response_data->MerchantFeeCount;

		global $array_internetbank_scb;
		global $array_internetbank_ktb;
		global $array_internetbank_bay;
		global $array_internetbank_bbl;
		global $array_internetbank_ttb;
		unset($this->internetbanking_fees['internetbank_scb']);
		$this->internetbanking_fees['internetbank_scb'] = array();
		unset($this->internetbanking_fees['internetbank_ktb']);
		$this->internetbanking_fees['internetbank_ktb'] = array();
		unset($this->internetbanking_fees['internetbank_bbl']);
		$this->internetbanking_fees['internetbank_bbl'] = array();
		unset($this->internetbanking_fees['internetbank_bay']);
		$this->internetbanking_fees['internetbank_bay'] = array();
		unset($this->internetbanking_fees['internetbank_ttb']);
		$this->internetbanking_fees['internetbank_ttb'] = array();

		global $array_mobilebank_payplus_kbank;
		global $array_mobilebank_scb;
		global $array_mobilebank_bay;
		global $array_mobilebank_bbl;
		global $array_mobilebank_ktb;
		unset($this->mobilebanking_fees['payplus_kbank']);
		$this->mobilebanking_fees['payplus_kbank'] = array();
		unset($this->mobilebanking_fees['mobilebank_scb']);
		$this->mobilebanking_fees['mobilebank_scb'] = array();
		unset($this->mobilebanking_fees['mobilebank_bay']);
		$this->mobilebanking_fees['mobilebank_bay'] = array();
		unset($this->mobilebanking_fees['mobilebank_bbl']);
		$this->mobilebanking_fees['mobilebank_bbl'] = array();
		unset($this->mobilebanking_fees['mobilebank_ktb']);
		$this->mobilebanking_fees['mobilebank_ktb'] = array();

		global $array_creditcard;
		unset($this->creditcard_fees['creditcard']);
		$this->creditcard_fees['creditcard'] = array();

		global $array_ewallet_linepay;
		global $array_ewallet_truemoney;
		global $array_alipay;
		global $array_wechatpay;
		global $array_ewallet_shopeepay;
		unset($this->ewallet_fees['epayment_linepay']);
		$this->ewallet_fees['epayment_linepay'] = array();
		unset($this->ewallet_fees['epayment_truemoney']);
		$this->ewallet_fees['epayment_truemoney'] = array();
		unset($this->ewallet_fees['epayment_alipay']);
		$this->ewallet_fees['epayment_alipay'] = array();
		unset($this->ewallet_fees['epayment_wechatpay']);
		$this->ewallet_fees['epayment_wechatpay'] = array();
		unset($this->ewallet_fees['epayment_shopeepay']);
		$this->ewallet_fees['epayment_shopeepay'] = array();

		global $array_billpayment_bigc;
		global $array_billpayment_cenpay;
		global $array_billpayment_counter_bill_payment;
		unset($this->billpayment_fees['billpayment_bigc']);
		$this->billpayment_fees['billpayment_bigc'] = array();
		unset($this->billpayment_fees['billpayment_cenpay']);
		$this->billpayment_fees['billpayment_cenpay'] = array();
		unset($this->billpayment_fees['billpayment_counter']);
		$this->billpayment_fees['billpayment_counter'] = array();

		global $array_qrcode;
		unset($this->qrcode_fees['bank_qrcode']);
		$this->qrcode_fees['bank_qrcode'] = array();	

		global $array_kiosk_machine_boonterm;
		unset($this->kiosk_machine_fees['billpayment_boonterm']);
		$this->kiosk_machine_fees['billpayment_boonterm'] = array();

		global $array_installment_kbank;
		global $array_installment_tbank;
		global $array_installment_ktc_flexi;
		global $array_installment_scb;
		global $array_installment_krungsri;
		global $array_installment_firstchoice;
		unset($this->installment_fees['installment_kbank']);
		$this->installment_fees['installment_kbank'] = array();
		unset($this->installment_fees['installment_tbank']);
		$this->installment_fees['installment_tbank'] = array();
		unset($this->installment_fees['installment_ktc_flexi']);
		$this->installment_fees['installment_ktc_flexi'] = array();
		unset($this->installment_fees['installment_scb']);
		$this->installment_fees['installment_scb'] = array();
		unset($this->installment_fees['installment_krungsri']);
		$this->installment_fees['installment_krungsri'] = array();
		unset($this->installment_fees['installment_firstchoice']);
		$this->installment_fees['installment_firstchoice'] = array();


		global $array_point_ktc_forever;
		unset($this->pay_with_points_fees['point_ktc_forever']);
		$this->pay_with_points_fees['point_ktc_forever'] = array();

		for ($i = 0; $i < $count; $i++) {
			$code = $de_data[$i]['ChannelServiceCode'];
			$fee_amount = $de_data[$i]['FeeAmount'];
			$fee_min_amount = $de_data[$i]['FeeMinAmount'];
			$fee_type = $de_data[$i]['FeeType'];
			$payment_min_price = $de_data[$i]['PaymentMinPrice'];
			$payment_max_price = $de_data[$i]['PaymentMaxPrice'];
			$currency_code = $de_data[$i]['CurrencyCode'];
			$currncy_name = $de_data[$i]['CurrencyName'];
			$card_type = '';
			$installments = $de_data[$i]['Installments'];

			if(isset($de_data[$i]['CardType']))
			{
				$card_type = $de_data[$i]['CardType'];
			}

			if (strcmp($code, 'internetbank_scb') === 0) {
				$array_internetbank_scb['fee_amount'] = $fee_amount;
				$array_internetbank_scb['fee_min_amount'] = $fee_min_amount;
				$array_internetbank_scb['fee_type'] = $fee_type;
				$array_internetbank_scb['payment_min_price'] = $payment_min_price;
				$array_internetbank_scb['payment_max_price'] = $payment_max_price;
				$array_internetbank_scb['currency_code'] = $currency_code;
				$array_internetbank_scb['currncy_name'] = $currncy_name;

				if ($this->internetbanking_fees['internetbank_scb'] == array()) {
					$this->internetbanking_fees['internetbank_scb'] = array($array_internetbank_scb);
				} else {
					array_push($this->internetbanking_fees['internetbank_scb'], $array_internetbank_scb);
				}
			} else if (strcmp($code, 'internetbank_ktb') === 0) {
				$array_internetbank_ktb['fee_amount'] = $fee_amount;
				$array_internetbank_ktb['fee_min_amount'] = $fee_min_amount;
				$array_internetbank_ktb['fee_type'] = $fee_type;
				$array_internetbank_ktb['payment_min_price'] = $payment_min_price;
				$array_internetbank_ktb['payment_max_price'] = $payment_max_price;
				$array_internetbank_ktb['currency_code'] = $currency_code;
				$array_internetbank_ktb['currncy_name'] = $currncy_name;

				if ($this->internetbanking_fees['internetbank_ktb'] == array()) {
					$this->internetbanking_fees['internetbank_ktb'] = array($array_internetbank_ktb);
				} else {
					array_push($this->internetbanking_fees['internetbank_ktb'], $array_internetbank_ktb);
				}
			} else if (strcmp($code, 'internetbank_bay') === 0) {
				$array_internetbank_bay['fee_amount'] = $fee_amount;
				$array_internetbank_bay['fee_min_amount'] = $fee_min_amount;
				$array_internetbank_bay['fee_type'] = $fee_type;
				$array_internetbank_bay['payment_min_price'] = $payment_min_price;
				$array_internetbank_bay['payment_max_price'] = $payment_max_price;
				$array_internetbank_bay['currency_code'] = $currency_code;
				$array_internetbank_bay['currncy_name'] = $currncy_name;

				if ($this->internetbanking_fees['internetbank_bay'] == array()) {
					$this->internetbanking_fees['internetbank_bay'] = array($array_internetbank_bay);
				} else {
					array_push($this->internetbanking_fees['internetbank_bay'], $array_internetbank_bay);
				}
			} else if (strcmp($code, 'internetbank_bbl') === 0) {
				$array_internetbank_bbl['fee_amount'] = $fee_amount;
				$array_internetbank_bbl['fee_min_amount'] = $fee_min_amount;
				$array_internetbank_bbl['fee_type'] = $fee_type;
				$array_internetbank_bbl['payment_min_price'] = $payment_min_price;
				$array_internetbank_bbl['payment_max_price'] = $payment_max_price;
				$array_internetbank_bbl['currency_code'] = $currency_code;
				$array_internetbank_bbl['currncy_name'] = $currncy_name;

				if ($this->internetbanking_fees['internetbank_bbl'] == array()) {
					$this->internetbanking_fees['internetbank_bbl'] = array($array_internetbank_bbl);
				} else {
					array_push($this->internetbanking_fees['internetbank_bbl'], $array_internetbank_bbl);
				}
			} else if (strcmp($code, 'internetbank_ttb') === 0) {
				$array_internetbank_ttb['fee_amount'] = $fee_amount;
				$array_internetbank_ttb['fee_min_amount'] = $fee_min_amount;
				$array_internetbank_ttb['fee_type'] = $fee_type;
				$array_internetbank_ttb['payment_min_price'] = $payment_min_price;
				$array_internetbank_ttb['payment_max_price'] = $payment_max_price;
				$array_internetbank_ttb['currency_code'] = $currency_code;
				$array_internetbank_ttb['currncy_name'] = $currncy_name;

				if ($this->internetbanking_fees['internetbank_ttb'] == array()) {
					$this->internetbanking_fees['internetbank_ttb'] = array($array_internetbank_ttb);
				} else {
					array_push($this->internetbanking_fees['internetbank_ttb'], $array_internetbank_ttb);
				}
			} else if (strcmp($code, 'payplus_kbank') === 0) {
				$array_mobilebank_payplus_kbank['fee_amount'] = $fee_amount;
				$array_mobilebank_payplus_kbank['fee_min_amount'] = $fee_min_amount;
				$array_mobilebank_payplus_kbank['fee_type'] = $fee_type;
				$array_mobilebank_payplus_kbank['payment_min_price'] = $payment_min_price;
				$array_mobilebank_payplus_kbank['payment_max_price'] = $payment_max_price;
				$array_mobilebank_payplus_kbank['currency_code'] = $currency_code;
				$array_mobilebank_payplus_kbank['currncy_name'] = $currncy_name;

				if ($this->mobilebanking_fees['payplus_kbank'] == array()) {
					$this->mobilebanking_fees['payplus_kbank'] = array($array_mobilebank_payplus_kbank);
				} else {
					array_push($this->mobilebanking_fees['payplus_kbank'], $array_mobilebank_payplus_kbank);
				}
			} else if (strcmp($code, 'mobilebank_scb') === 0) {
				$array_mobilebank_scb['fee_amount'] = $fee_amount;
				$array_mobilebank_scb['fee_min_amount'] = $fee_min_amount;
				$array_mobilebank_scb['fee_type'] = $fee_type;
				$array_mobilebank_scb['payment_min_price'] = $payment_min_price;
				$array_mobilebank_scb['payment_max_price'] = $payment_max_price;
				$array_mobilebank_scb['currency_code'] = $currency_code;
				$array_mobilebank_scb['currncy_name'] = $currncy_name;

				if ($this->mobilebanking_fees['mobilebank_scb'] == array()) {
					$this->mobilebanking_fees['mobilebank_scb'] = array($array_mobilebank_scb);
				} else {
					array_push($this->mobilebanking_fees['mobilebank_scb'], $array_mobilebank_scb);
				}
			} else if (strcmp($code, 'mobilebank_bay') === 0) {
				$array_mobilebank_bay['fee_amount'] = $fee_amount;
				$array_mobilebank_bay['fee_min_amount'] = $fee_min_amount;
				$array_mobilebank_bay['fee_type'] = $fee_type;
				$array_mobilebank_bay['payment_min_price'] = $payment_min_price;
				$array_mobilebank_bay['payment_max_price'] = $payment_max_price;
				$array_mobilebank_bay['currency_code'] = $currency_code;
				$array_mobilebank_bay['currncy_name'] = $currncy_name;

				if ($this->mobilebanking_fees['mobilebank_bay'] == array()) {
					$this->mobilebanking_fees['mobilebank_bay'] = array($array_mobilebank_bay);
				} else {
					array_push($this->mobilebanking_fees['mobilebank_bay'], $array_mobilebank_bay);
				}
			} else if (strcmp($code, 'mobilebank_bbl') === 0) {
				$array_mobilebank_bbl['fee_amount'] = $fee_amount;
				$array_mobilebank_bbl['fee_min_amount'] = $fee_min_amount;
				$array_mobilebank_bbl['fee_type'] = $fee_type;
				$array_mobilebank_bbl['payment_min_price'] = $payment_min_price;
				$array_mobilebank_bbl['payment_max_price'] = $payment_max_price;
				$array_mobilebank_bbl['currency_code'] = $currency_code;
				$array_mobilebank_bbl['currncy_name'] = $currncy_name;

				if ($this->mobilebanking_fees['mobilebank_bbl'] == array()) {
					$this->mobilebanking_fees['mobilebank_bbl'] = array($array_mobilebank_bbl);
				} else {
					array_push($this->mobilebanking_fees['mobilebank_bbl'], $array_mobilebank_bbl);
				}
			} else if (strcmp($code, 'mobilebank_ktb') === 0) {
				$array_mobilebank_ktb['fee_amount'] = $fee_amount;
				$array_mobilebank_ktb['fee_min_amount'] = $fee_min_amount;
				$array_mobilebank_ktb['fee_type'] = $fee_type;
				$array_mobilebank_ktb['payment_min_price'] = $payment_min_price;
				$array_mobilebank_ktb['payment_max_price'] = $payment_max_price;
				$array_mobilebank_ktb['currency_code'] = $currency_code;
				$array_mobilebank_ktb['currncy_name'] = $currncy_name;

				if ($this->mobilebanking_fees['mobilebank_ktb'] == array()) {
					$this->mobilebanking_fees['mobilebank_ktb'] = array($array_mobilebank_ktb);
				} else {
					array_push($this->mobilebanking_fees['mobilebank_ktb'], $array_mobilebank_ktb);
				}
			} else if (strpos($code, 'creditcard') === 0) {
				$array_creditcard['card_type'] = $card_type;
				$array_creditcard['fee_amount'] = $fee_amount;
				$array_creditcard['fee_min_amount'] = $fee_min_amount;
				$array_creditcard['fee_type'] = $fee_type;
				$array_creditcard['payment_min_price'] = $payment_min_price;
				$array_creditcard['payment_max_price'] = $payment_max_price;
				$array_creditcard['currency_code'] = $currency_code;
				$array_creditcard['currncy_name'] = $currncy_name;

				if ($this->creditcard_fees['creditcard'] == array()) {
					$this->creditcard_fees['creditcard'] = array($array_creditcard);
				} else {
					array_push($this->creditcard_fees['creditcard'], $array_creditcard);
				}
			} else if (strcmp($code, 'epayment_linepay') === 0) {
				$array_ewallet_linepay['fee_amount'] = $fee_amount;
				$array_ewallet_linepay['fee_min_amount'] = $fee_min_amount;
				$array_ewallet_linepay['fee_type'] = $fee_type;
				$array_ewallet_linepay['payment_min_price'] = $payment_min_price;
				$array_ewallet_linepay['payment_max_price'] = $payment_max_price;
				$array_ewallet_linepay['currency_code'] = $currency_code;
				$array_ewallet_linepay['currncy_name'] = $currncy_name;

				if ($this->ewallet_fees['epayment_linepay'] == array()) {
					$this->ewallet_fees['epayment_linepay'] = array($array_ewallet_linepay);
				} else {
					array_push($this->ewallet_fees['epayment_linepay'], $array_ewallet_linepay);
				}
			} else if (strcmp($code, 'epayment_truemoney') === 0) {
				$array_ewallet_truemoney['fee_amount'] = $fee_amount;
				$array_ewallet_truemoney['fee_min_amount'] = $fee_min_amount;
				$array_ewallet_truemoney['fee_type'] = $fee_type;
				$array_ewallet_truemoney['payment_min_price'] = $payment_min_price;
				$array_ewallet_truemoney['payment_max_price'] = $payment_max_price;
				$array_ewallet_truemoney['currency_code'] = $currency_code;
				$array_ewallet_truemoney['currncy_name'] = $currncy_name;

				if ($this->ewallet_fees['epayment_truemoney'] == array()) {
					$this->ewallet_fees['epayment_truemoney'] = array($array_ewallet_truemoney);
				} else {
					array_push($this->ewallet_fees['epayment_truemoney'], $array_ewallet_truemoney);
				}
			} else if (strcmp($code, 'epayment_shopeepay') === 0) {
				$array_ewallet_shopeepay['fee_amount'] = $fee_amount;
				$array_ewallet_shopeepay['fee_min_amount'] = $fee_min_amount;
				$array_ewallet_shopeepay['fee_type'] = $fee_type;
				$array_ewallet_shopeepay['payment_min_price'] = $payment_min_price;
				$array_ewallet_shopeepay['payment_max_price'] = $payment_max_price;
				$array_ewallet_shopeepay['currency_code'] = $currency_code;
				$array_ewallet_shopeepay['currncy_name'] = $currncy_name;

				if ($this->ewallet_fees['epayment_shopeepay'] == array()) {
					$this->ewallet_fees['epayment_shopeepay'] = array($array_ewallet_shopeepay);
				} else {
					array_push($this->ewallet_fees['epayment_shopeepay'], $array_ewallet_shopeepay);
				}
			} else if (strcmp($code, 'billpayment_bigc') === 0) {
				$array_billpayment_bigc['fee_amount'] = $fee_amount;
				$array_billpayment_bigc['fee_min_amount'] = $fee_min_amount;
				$array_billpayment_bigc['fee_type'] = $fee_type;
				$array_billpayment_bigc['payment_min_price'] = $payment_min_price;
				$array_billpayment_bigc['payment_max_price'] = $payment_max_price;
				$array_billpayment_bigc['currency_code'] = $currency_code;
				$array_billpayment_bigc['currncy_name'] = $currncy_name;

				if ($this->billpayment_fees['billpayment_bigc'] == array()) {
					$this->billpayment_fees['billpayment_bigc'] = array($array_billpayment_bigc);
				} else {
					array_push($this->billpayment_fees['billpayment_bigc'], $array_billpayment_bigc);
				}
			} else if (strcmp($code, 'billpayment_cenpay') === 0) {
				$array_billpayment_cenpay['fee_amount'] = $fee_amount;
				$array_billpayment_cenpay['fee_min_amount'] = $fee_min_amount;
				$array_billpayment_cenpay['fee_type'] = $fee_type;
				$array_billpayment_cenpay['payment_min_price'] = $payment_min_price;
				$array_billpayment_cenpay['payment_max_price'] = $payment_max_price;
				$array_billpayment_cenpay['currency_code'] = $currency_code;
				$array_billpayment_cenpay['currncy_name'] = $currncy_name;

				if ($this->billpayment_fees['billpayment_cenpay'] == array()) {
					$this->billpayment_fees['billpayment_cenpay'] = array($array_billpayment_cenpay);
				} else {
					array_push($this->billpayment_fees['billpayment_cenpay'], $array_billpayment_cenpay);
				}
			} else if (strcmp($code, 'billpayment_counter') === 0) {
				$array_billpayment_counter_bill_payment['fee_amount'] = $fee_amount;
				$array_billpayment_counter_bill_payment['fee_min_amount'] = $fee_min_amount;
				$array_billpayment_counter_bill_payment['fee_type'] = $fee_type;
				$array_billpayment_counter_bill_payment['payment_min_price'] = $payment_min_price;
				$array_billpayment_counter_bill_payment['payment_max_price'] = $payment_max_price;
				$array_billpayment_counter_bill_payment['currency_code'] = $currency_code;
				$array_billpayment_counter_bill_payment['currncy_name'] = $currncy_name;

				if ($this->billpayment_fees['billpayment_counter'] == array()) {
					$this->billpayment_fees['billpayment_counter'] = array($array_billpayment_counter_bill_payment);
				} else {
					array_push($this->billpayment_fees['billpayment_counter'], $array_billpayment_counter_bill_payment);
				}
			} else if (strcmp($code, 'bank_qrcode') === 0) {
				$array_qrcode['fee_amount'] = $fee_amount;
				$array_qrcode['fee_min_amount'] = $fee_min_amount;
				$array_qrcode['fee_type'] = $fee_type;
				$array_qrcode['payment_min_price'] = $payment_min_price;
				$array_qrcode['payment_max_price'] = $payment_max_price;
				$array_qrcode['currency_code'] = $currency_code;
				$array_qrcode['currncy_name'] = $currncy_name;

				if ($this->qrcode_fees['bank_qrcode'] == array()) {
					$this->qrcode_fees['bank_qrcode'] = array($array_qrcode);
				} else {
					array_push($this->qrcode_fees['bank_qrcode'], $array_qrcode);
				}
			} else if (strcmp($code, 'epayment_alipay') === 0) {
				$array_alipay['fee_amount'] = $fee_amount;
				$array_alipay['fee_min_amount'] = $fee_min_amount;
				$array_alipay['fee_type'] = $fee_type;
				$array_alipay['payment_min_price'] = $payment_min_price;
				$array_alipay['payment_max_price'] = $payment_max_price;
				$array_alipay['currency_code'] = $currency_code;
				$array_alipay['currncy_name'] = $currncy_name;

				if ($this->ewallet_fees['epayment_alipay'] == array()) {
					$this->ewallet_fees['epayment_alipay'] = array($array_alipay);
				} else {
					array_push($this->ewallet_fees['epayment_alipay'], $array_alipay);
				}
			} else if (strcmp($code, 'epayment_wechatpay') === 0) {
				$array_wechatpay['fee_amount'] = $fee_amount;
				$array_wechatpay['fee_min_amount'] = $fee_min_amount;
				$array_wechatpay['fee_type'] = $fee_type;
				$array_wechatpay['payment_min_price'] = $payment_min_price;
				$array_wechatpay['payment_max_price'] = $payment_max_price;
				$array_wechatpay['currency_code'] = $currency_code;
				$array_wechatpay['currncy_name'] = $currncy_name;

				if ($this->ewallet_fees['epayment_wechatpay'] == array()) {
					$this->ewallet_fees['epayment_wechatpay'] = array($array_wechatpay);
				} else {
					array_push($this->ewallet_fees['epayment_wechatpay'], $array_wechatpay);
				}
			} else if (strcmp($code, 'billpayment_boonterm') === 0) {
				$array_kiosk_machine_boonterm['fee_amount'] = $fee_amount;
				$array_kiosk_machine_boonterm['fee_min_amount'] = $fee_min_amount;
				$array_kiosk_machine_boonterm['fee_type'] = $fee_type;
				$array_kiosk_machine_boonterm['payment_min_price'] = $payment_min_price;
				$array_kiosk_machine_boonterm['payment_max_price'] = $payment_max_price;
				$array_kiosk_machine_boonterm['currency_code'] = $currency_code;
				$array_kiosk_machine_boonterm['currncy_name'] = $currncy_name;

				if ($this->kiosk_machine_fees['billpayment_boonterm'] == array()) {
					$this->kiosk_machine_fees['billpayment_boonterm'] = array($array_kiosk_machine_boonterm);
				} else {
					array_push($this->kiosk_machine_fees['billpayment_boonterm'], $array_kiosk_machine_boonterm);
				}
			} else if (strcmp($code, 'installment_kbank') === 0) {
				$array_installment_kbank['fee_amount'] = $fee_amount;
				$array_installment_kbank['fee_min_amount'] = $fee_min_amount;
				$array_installment_kbank['fee_type'] = $fee_type;
				$array_installment_kbank['payment_min_price'] = $payment_min_price;
				$array_installment_kbank['payment_max_price'] = $payment_max_price;
				$array_installment_kbank['currency_code'] = $currency_code;
				$array_installment_kbank['currncy_name'] = $currncy_name;
				$array_installment_kbank['installments'] = $installments;

				if ($this->installment_fees['installment_kbank'] == array()) {
					$this->installment_fees['installment_kbank'] = array($array_installment_kbank);
				} else {
					array_push($this->installment_fees['installment_kbank'], $array_installment_kbank);
				}
			} else if (strcmp($code, 'installment_tbank') === 0) {
				$array_installment_tbank['fee_amount'] = $fee_amount;
				$array_installment_tbank['fee_min_amount'] = $fee_min_amount;
				$array_installment_tbank['fee_type'] = $fee_type;
				$array_installment_tbank['payment_min_price'] = $payment_min_price;
				$array_installment_tbank['payment_max_price'] = $payment_max_price;
				$array_installment_tbank['currency_code'] = $currency_code;
				$array_installment_tbank['currncy_name'] = $currncy_name;
				$array_installment_tbank['installments'] = $installments;

				if ($this->installment_fees['installment_tbank'] == array()) {
					$this->installment_fees['installment_tbank'] = array($array_installment_tbank);
				} else {
					array_push($this->installment_fees['installment_tbank'], $array_installment_tbank);
				}
			} else if (strcmp($code, 'installment_ktc_flexi') === 0) {
				$array_installment_ktc_flexi['fee_amount'] = $fee_amount;
				$array_installment_ktc_flexi['fee_min_amount'] = $fee_min_amount;
				$array_installment_ktc_flexi['fee_type'] = $fee_type;
				$array_installment_ktc_flexi['payment_min_price'] = $payment_min_price;
				$array_installment_ktc_flexi['payment_max_price'] = $payment_max_price;
				$array_installment_ktc_flexi['currency_code'] = $currency_code;
				$array_installment_ktc_flexi['currncy_name'] = $currncy_name;
				$array_installment_ktc_flexi['installments'] = $installments;

				if ($this->installment_fees['installment_ktc_flexi'] == array()) {
					$this->installment_fees['installment_ktc_flexi'] = array($array_installment_ktc_flexi);
				} else {
					array_push($this->installment_fees['installment_ktc_flexi'], $array_installment_ktc_flexi);
				}
			} else if (strcmp($code, 'installment_scb') === 0) {
				$array_installment_scb['fee_amount'] = $fee_amount;
				$array_installment_scb['fee_min_amount'] = $fee_min_amount;
				$array_installment_scb['fee_type'] = $fee_type;
				$array_installment_scb['payment_min_price'] = $payment_min_price;
				$array_installment_scb['payment_max_price'] = $payment_max_price;
				$array_installment_scb['currency_code'] = $currency_code;
				$array_installment_scb['currncy_name'] = $currncy_name;
				$array_installment_scb['installments'] = $installments;

				if ($this->installment_fees['installment_scb'] == array()) {
					$this->installment_fees['installment_scb'] = array($array_installment_scb);
				} else {
					array_push($this->installment_fees['installment_scb'], $array_installment_scb);
				}
			} else if (strcmp($code, 'installment_krungsri') === 0) {
				$array_installment_krungsri['fee_amount'] = $fee_amount;
				$array_installment_krungsri['fee_min_amount'] = $fee_min_amount;
				$array_installment_krungsri['fee_type'] = $fee_type;
				$array_installment_krungsri['payment_min_price'] = $payment_min_price;
				$array_installment_krungsri['payment_max_price'] = $payment_max_price;
				$array_installment_krungsri['currency_code'] = $currency_code;
				$array_installment_krungsri['currncy_name'] = $currncy_name;
				$array_installment_krungsri['card_type'] = $card_type;
				$array_installment_krungsri['installments'] = $installments;

				if ($this->installment_fees['installment_krungsri'] == array()) {
					$this->installment_fees['installment_krungsri'] = array($array_installment_krungsri);
				} else {
					array_push($this->installment_fees['installment_krungsri'], $array_installment_krungsri);
				}
			} else if (strcmp($code, 'installment_firstchoice') === 0) {
				$array_installment_firstchoice['fee_amount'] = $fee_amount;
				$array_installment_firstchoice['fee_min_amount'] = $fee_min_amount;
				$array_installment_firstchoice['fee_type'] = $fee_type;
				$array_installment_firstchoice['payment_min_price'] = $payment_min_price;
				$array_installment_firstchoice['payment_max_price'] = $payment_max_price;
				$array_installment_firstchoice['currency_code'] = $currency_code;
				$array_installment_firstchoice['currncy_name'] = $currncy_name;
				$array_installment_firstchoice['card_type'] = $card_type;
				$array_installment_firstchoice['installments'] = $installments;

				if ($this->installment_fees['installment_firstchoice'] == array()) {
					$this->installment_fees['installment_firstchoice'] = array($array_installment_firstchoice);
				} else {
					array_push($this->installment_fees['installment_firstchoice'], $array_installment_firstchoice);
				}
			} else if (strcmp($code, 'point_ktc_forever') === 0) {
				$array_point_ktc_forever['fee_amount'] = $fee_amount;
				$array_point_ktc_forever['fee_min_amount'] = $fee_min_amount;
				$array_point_ktc_forever['fee_type'] = $fee_type;
				$array_point_ktc_forever['payment_min_price'] = $payment_min_price;
				$array_point_ktc_forever['payment_max_price'] = $payment_max_price;
				$array_point_ktc_forever['currency_code'] = $currency_code;
				$array_point_ktc_forever['currncy_name'] = $currncy_name;

				if ($this->pay_with_points_fees['point_ktc_forever'] == array()) {
					$this->pay_with_points_fees['point_ktc_forever'] = array($array_point_ktc_forever);
				} else {
					array_push($this->pay_with_points_fees['point_ktc_forever'], $array_point_ktc_forever);
				}
			}
		}
	}
}

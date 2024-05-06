<?php
/*
	Plugin Name: ChillPay Payment Gateway
	Plugin URI: https://www.chillpay.co/Plugin
	Description: The ChillPay WooCommerce plugin lets you accept credit cards and more with real-time reports. Get paid fast under your own branding. www.chillpay.co
	Version: 2.5.1
	Author: PraIn FinTech Co., Ltd.
	Author URI: https://www.chillpay.co/
	Text Domain: chillpay
	License: MIT
	License URI: https://opensource.org/licenses/MIT
*/

defined('ABSPATH') or die('No direct script access allowed.');

class ChillPay
{
	/**
	 * ChillPay plugin version number.
	 *
	 * @var string
	 */
	public $version = '2.5.1';

	/**
	 * The ChillPay Instance.
	 *
	 * @since 1.0
	 *
	 * @var   \ChillPay
	 */
	protected static $the_instance = null;

	/**
	 * @since 2.0
	 *
	 * @var   boolean
	 */
	protected static $can_initiate = false;

	/**
	 * @since  1.0
	 */
	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'check_dependencies'));
		add_action('init', array($this, 'init'));

		do_action('chillpay_initiated');
	}

	/** 
	 * Check if all dependencies are loaded
	 * properly before ChillPay-WooCommerce.
	 * 
	 * @since  2.0
	 */
	public function check_dependencies()
	{
		if (!function_exists('WC')) {
			return;
		}

		static::$can_initiate = true;
	}

	/**
	 * @since  2.0
	 */
	public function init()
	{
		if (!static::$can_initiate) {
			add_action('admin_notices', array($this, 'woocommerce_plugin_notice'));
			return;
		}

		$this->include_classes();
		$this->define_constants();
		$this->load_plugin_textdomain();
		$this->init_admin();
		$this->init_route();
		//$this->register_payment_methods();
	}

	/** 
	 * Callback to display message about activation error
	 * 
	 * @since  1.0
	 */
	public function woocommerce_plugin_notice()
	{
?>
		<div class="error">
			<p><?php echo __('Plugin <strong>deactivated</strong>. The ChillPay WooCommerce plugin requires <strong>WooCommerce</strong> to be installed and active.', 'chillpay'); ?></p>
		</div>
<?php
	}

	/**
	 * Define ChillPay necessary constants.
	 *
	 * @since 2.0
	 */
	private function define_constants()
	{
		global $wp_version;

		defined('CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION') || define('CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION', $this->version);
		defined('CHILLPAY_API_VERSION') || define('CHILLPAY_API_VERSION', '2018-10-25');
	}

	/**
	 * @since  2.0
	 */
	private function include_classes()
	{
		defined('CHILLPAY_WOOCOMMERCE_PLUGIN_PATH') || define('CHILLPAY_WOOCOMMERCE_PLUGIN_PATH', __DIR__);

		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/classes/class-chillpay-card-image.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-internetbanking.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-mobilebanking.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-creditcard.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-qrcode.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-ewallet.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-billpayment.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-kiosk-machine.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-installment.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment-pay-with-points.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/gateway/class-chillpay-payment.php';

		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-hash-helper.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/libraries/chillpay-php/lib/ChillPay.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/libraries/chillpay-plugin/ChillPay.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-callback.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-events.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-payment-methods.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-rest-webhooks-controller.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-setting.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-wc-myaccount.php';
		require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/chillpay-util.php';
	}

	/**
	 * @since  1.0
	 */
	protected function init_admin()
	{
		if (is_admin()) {
			require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/class-chillpay-admin.php';
			ChillPay_Admin::get_instance()->init();
		}
	}

	/**
	 * @since  1.0
	 */
	protected function init_route()
	{
		add_action('rest_api_init', function () {
			$controllers = new ChillPay_Rest_Webhooks_Controller;
			$controllers->register_routes();
		});
	}

	/**
	 * @since  1.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain('chillpay', false, plugin_basename(dirname(__FILE__)) . '/languages/');
	}

	/**
	 * @since  1.0
	 */
	public function register_user_agent()
	{
		global $wp_version;

		$user_agent = sprintf('ChillPayWooCommerce/%s WordPress/%s WooCommerce/%s', CHILLPAY_WOOCOMMERCE_PLUGIN_VERSION, $wp_version, function_exists('WC') ? WC()->version : '');
		defined('CHILLPAY_USER_AGENT_SUFFIX') || define('CHILLPAY_USER_AGENT_SUFFIX', $user_agent);
	}

	/**
	 * The ChillPay Instance.
	 *
	 * @see    ChillPay()
	 *
	 * @since  1.0
	 *
	 * @static
	 *
	 * @return \ChillPay - The instance.
	 */
	public static function instance()
	{
		if (is_null(self::$the_instance)) {
			self::$the_instance = new self();
		}

		return self::$the_instance;
	}

	/**
	 * Get setting class.
	 *
	 * @since  1.0
	 *
	 * @return ChillPay_Setting
	 */
	public function settings()
	{
		return ChillPay_Setting::instance();
	}

}

function ChillPay()
{
	return ChillPay::instance();
}

ChillPay();

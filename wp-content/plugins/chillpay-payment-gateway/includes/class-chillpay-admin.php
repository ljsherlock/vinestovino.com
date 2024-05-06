<?php
defined( 'ABSPATH' ) or die( "No direct script access allowed." );

if ( ! class_exists( 'ChillPay_Admin' ) ) {
    class ChillPay_Admin {
        /**
		 * The ChillPay Instance.
		 *
		 * @var   \ChillPay_Admin
		 */
		protected static $the_instance;

        /**
		 * @return \ChillPay_Admin  The instance.
		 */
		public static function get_instance() {
			if ( ! self::$the_instance ) {
				self::$the_instance = new self();
			}

			return self::$the_instance;
        }
        
        /**
		 * @since 2.0
		 */
		public function init() {
			require_once CHILLPAY_WOOCOMMERCE_PLUGIN_PATH . '/includes/admin/class-chillpay-page-settings.php';

			$this->register_admin_menu();
			$this->register_woocommerce_filters();
        }
        
        /**
		 * Register ChillPay to WordPress, WooCommerce
		 * @return void
		 */
		public function register_admin_menu() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        }
        
		public function add_admin_menu() {
			add_menu_page( 'ChillPay', 'ChillPay', 'manage_options', 'chillpay', array( $this, 'page_settings'), 'https://chillpay-uploads.s3.ap-southeast-1.amazonaws.com/images/chillpay_icon_white_16x16.png');
		}
		
      
        /**
		 * Render ChillPay Setting page.
		 */
		public function page_settings() {
			ChillPay_Page_Settings::render();
		}

		/**
		 * @since 2.0
		 */
		public function register_woocommerce_filters() {
			add_filter( 'woocommerce_order_actions', array( $this,'add_order_meta_box_actions') );
		}

		/**
		 * Callback to $this::add_order_meta_box_actions() method.
		 *
		 * @since  2.0
		 *
		 * @param  array $order_actions
		 *
		 * @return array
		 */
		public function add_order_meta_box_actions( $order_actions ) {
			global $theorder;

			/** backward compatible with WooCommerce v2.x series **/
			$payment_method = version_compare( WC()->version, '3.0.0', '>=' ) ? $theorder->get_payment_method() : $theorder->payment_method;

			$order_actions[ $payment_method . '_sync_payment_status'] = __( 'ChillPay : Manual sync payment status', 'chillpay' );
			return $order_actions;
		}
    }
}
?>
<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

if ( ! class_exists( 'ChillPay_MyAccount' ) ) {
    class ChillPay_MyAccount {
        private static $instance;

        public static function get_instance() {
			if ( ! self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
        }
        
        private function __construct() {
            // prevent running directly without wooCommerce
            if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
                return;
            }

            $settings = get_option( 'woocommerce_chillpay_settings', null );

			if ( is_null($settings) || ! is_array( $settings ) ) {
				return;
			}

			if ( empty( $settings['sandbox'] )
				|| empty( $settings['setting_order'] )
				|| empty( $settings['test_merchant_code'] )
				|| empty( $settings['live_merchant_code'] )
				|| empty( $settings['test_api_key'] )
				|| empty( $settings['live_api_key'] )
				|| empty( $settings['test_md5_secret_key'] )
				|| empty( $settings['live_md5_secret_key'] )
				|| empty( $settings['test_route_no'] )
				|| empty( $settings['live_route_no'] ) ) {
				return;
			}

			$test_mode = isset( $settings['sandbox'] ) && $settings['sandbox'] == 'yes';

			$this->merchant_code  = $test_mode ? $settings['test_merchant_code'] : $settings['live_merchant_code'];
			$this->api_key = $test_mode ? $settings['test_api_key'] : $settings['live_api_key'];
			$this->md5_secret_key = $test_mode ? $settings['test_md5_secret_key'] : $settings['live_md5_secret_key'];

			if ( empty( $this->api_key ) || empty( $this->merchant_code ) || empty( $this->md5_secret_key )) {
				return;
			}

			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$this->chillpay_customer_id = $test_mode ? $current_user->test_chillpay_customer_id : $current_user->live_chillpay_customer_id;
			}

			add_action( 'woocommerce_after_my_account', array( $this, 'init_panel' ) );
			add_action( 'wp_ajax_chillpay_delete_card', array( $this, 'chillpay_delete_card' ) );
			add_action( 'wp_ajax_chillpay_create_card', array( $this, 'chillpay_create_card' ) );
			add_action( 'wp_ajax_nopriv_chillpay_delete_card', array( $this, 'no_op' ) );
			add_action( 'wp_ajax_nopriv_chillpay_create_card', array( $this, 'no_op' ) );
        }

        /**
		 * Append ChillPay Settings panel to My Account page
		 */
		public function init_panel() {
			if ( ! empty( $this->chillpay_customer_id ) ) {
				try {
					$customer                  = ChillPayCustomer::retrieve( $this->chillpay_customer_id, '', $this->api_key );
					$viewData['existingCards'] = $customer->cards();

					ChillPay_Util::render_view( 'templates/myaccount/my-card.php', $viewData );
					$this->register_chillpay_my_account_scripts();
				} catch (Exception $e) {
					// nothing.
				}
			}
        }
        
        /**
		 * Register all javascripts
		 */
		public function register_chillpay_my_account_scripts() {
			wp_enqueue_script(
				'chillpay-util',
				plugins_url( '/assets/javascripts/chillpay-util.js', dirname( __FILE__ ) ),
				array( 'chillpay-js' ),
				WC_VERSION,
				true
			);

			wp_enqueue_script(
				'chillpay-myaccount-card-handler',
				plugins_url( '/assets/javascripts/chillpay-myaccount-card-handler.js', dirname( __FILE__ ) ),
				array( 'chillpay-js' , 'chillpay-util' ),
				WC_VERSION,
				true
			);
        }
        
        /**
		 * Public chillpay_delete_card ajax hook
		 */
		public function chillpay_delete_card() {
			$card_id = isset( $_POST['card_id'] ) ? wc_clean( $_POST['card_id'] ) : '';
			if ( empty( $card_id ) ) {
				ChillPay_Util::render_json_error( 'card_id is required' );
				die();
			}

			$nonce = 'chillpay_delete_card_' . $_POST['card_id'];
			if ( ! wp_verify_nonce( $_POST['chillpay_nonce'], $nonce ) ) {
				ChillPay_Util::render_json_error( 'Nonce verification failure' );
				die();
			}

			$customer = ChillPayCustomer::retrieve( $this->chillpay_customer_id, '', $this->api_key );
			$card     = $customer->cards()->retrieve( $card_id );
			$card->destroy();

			echo json_encode( array(
				'deleted' => $card->isDestroyed()
			) );
			die();
        }
        
        /**
		 * Public chillpay_create_card ajax hook
		 */
		public function chillpay_create_card() {
			$token = isset ( $_POST['chillpay_token'] ) ? wc_clean ( $_POST['chillpay_token'] ) : '';
			if ( empty( $token ) ) {
				ChillPay_Util::render_json_error( 'chillpay_token is required' );
				die();
			}

			if ( ! wp_verify_nonce($_POST['chillpay_nonce'], 'chillpay_add_card' ) ) {
				ChillPay_Util::render_json_error( 'Nonce verification failure' );
				die();
			}

			try {
				$customer = ChillPayCustomer::retrieve( $this->chillpay_customer_id, '', $this->api_key );
				$customer->update( array(
					'card' => $token
				) );

				$cards = $customer->cards( array(
					'limit' => 1,
					'order' => 'reverse_chronological'
				) );

				echo json_encode( $cards['data'][0] );
			} catch( Exception $e ) {
				echo json_encode( array(
					'object'  => 'error',
					'message' => $e->getMessage()
				) );
			}

			die();
        }
        
        /**
		 * No operation on no-priv ajax requests
		 */
		public function no_op() {
			exit( 'Not permitted' );
		}
    }
}

function prepare_chillpay_myaccount_panel() {
	$chillpay_myaccount = ChillPay_MyAccount::get_instance();
}
?>
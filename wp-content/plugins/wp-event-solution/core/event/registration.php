<?php

namespace Etn\Core\Event;

use Etn\Utils\Helper;

defined( "ABSPATH" ) or die();

class Registration {

	use \Etn\Traits\Singleton;

	/**
	 * Call all necessary hook
	 */
	public function init() {
		add_action( 'template_redirect', [$this, 'registration_step_two'] );
	}

	/**
	 * Store attendee report
	 */
	public function registration_step_two() {

		if ( isset( $_POST['ticket_purchase_next_step'] ) && $_POST['ticket_purchase_next_step'] === "two" ) {
			// Seat plan max purchase validation.
			$event_id = isset( $_POST['event_id'] ) ? intval( $_POST['event_id'] ) : 0;
			$selected_seats = isset( $_POST['selected_seats'] ) ? $_POST['selected_seats'] : [];
			$permalink = get_permalink( $event_id );
			$ticket_variations = get_post_meta( $event_id, 'etn_ticket_variations', true );
			
			$errors = 0;
			

			if ( $ticket_variations ) {
				foreach( $ticket_variations as $variation ) {
					$var_name = $variation['etn_ticket_name'];
					$max_ticket = $variation['etn_max_ticket'];
					$total_variation = ! empty( $selected_seats[ $var_name ] ) ? count( explode(',', $selected_seats[$var_name]) ) : 0;

					if ( $total_variation > $max_ticket ) {
						$errors += 1;
					}
				}
			}

			if ( $errors > 0 ) {
				$permalink = add_query_arg( ['etn_errors' => [
					'seat_limit_error'	=> __( 'You can not select more than the ticket purchase limit', 'eventin' ),
				]], $permalink );

				wp_redirect( $permalink );
				exit;
			}
			
			
				$post_arr          = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
				$check             = wp_verify_nonce( $post_arr['ticket_purchase_next_step_two'], 'ticket_purchase_next_step_two' );
				$settings          = Helper::get_settings();
				$include_phone     = !empty( $settings["reg_require_phone"] ) ? true : false;
				$include_email     = !empty( $settings["reg_require_email"] ) ? true : false;
				$reg_form_template = \Wpeventin::core_dir() . "attendee/views/registration/attendee-details-form.php";

				// check if WPML is activated
				if( class_exists('SitePress') && function_exists('icl_object_id') ){
						global $sitepress;
						$event_id = $post_arr["event_id"];
						$trid = $sitepress->get_element_trid($event_id);
						$post_arr["event_id"] = $sitepress->get_original_element_id($event_id, 'post_etn');
						$post_arr["lang_event_id"] = $event_id;
				}

				if ( file_exists( $reg_form_template ) ) {
						// for compatibility with deposit plugin: check two variables are exist in request. if exist, so deposit is running
						$deposit_enabled      = ( isset( $post_arr['wc_deposit_option'] ) && $post_arr['wc_deposit_option'] === 'yes' ) ? 1 : 0;
						$deposit_payment_plan = isset( $post_arr['wc_deposit_payment_plan'] ) ? absint( $post_arr['wc_deposit_payment_plan'] )  : 0;
						include_once $reg_form_template;
				}
		}

		return false;
	}

}

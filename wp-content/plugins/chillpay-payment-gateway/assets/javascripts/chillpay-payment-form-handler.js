(function ( $, undefined ) {
	var $form = $( 'form.checkout, form#order_review' );
	
	function chillpayFormHandler(){
		function showError(message){
			if(!message){
				return;
			}
			$(".woocommerce-error, input.chillpay_token").remove();
			
			$ulError = $("<ul>").addClass("woocommerce-error");
			
			if($.isArray(message)){
				$.each(message, function(i,v){
					$ulError.append($("<li>" + v + "</li>"));
				})
			}else{
				$ulError.html("<li>" + message + "</li>");
			}
			
			$form.prepend( $ulError );
			$("html, body").animate({
				 scrollTop:0
				 },"slow");
		}
		
		function hideError(){
			$(".woocommerce-error").remove();
		}
		
		function validSelection(){
			$card_list = $("input[name='card_id']");
			$selected_card_id = $("input[name='card_id']:checked");
			// there is some existing cards but nothing selected then warning
			if($card_list.size() > 0 && $selected_card_id.size() === 0){
				return false;
			}
			
			return true;
		}
		
		function getSelectedCardId(){
			$selected_card_id = $("input[name='card_id']:checked");
			if($selected_card_id.size() > 0){
				return $selected_card_id.val();
			}
			
			return "";
		}
		
		if ( $( '#payment_method_chillpay' ).is( ':checked' ) ) {
			if( !validSelection() ){
				showError("Please select a card or enter new payment information");
				return false;
			}
			
			if( getSelectedCardId() !== "" )
			{
				//submit the form right away if the card_id is not blank
				return true;
			}
			
			if ( 0 === $( 'input.chillpay_token' ).size() ) {
				$form.block({
					message: null,
					overlayCSS: {
						background: '#fff url(' + wc_checkout_params.ajax_loader_url + ') no-repeat center',
						backgroundSize: '16px 16px',
						opacity: 0.6
					}
				});

				var chillpay_card_name   = $( '#chillpay_card_name' ).val(),
					chillpay_card_number   = $( '#chillpay_card_number' ).val(),
					chillpay_card_expiration_month   = $( '#chillpay_card_expiration_month' ).val(),
					chillpay_card_expiration_year = $( '#chillpay_card_expiration_year' ).val(),
					chillpay_card_security_code    = $( '#chillpay_card_security_code' ).val();
				
				// Serialize the card into a valid card object.
				var card = {
				    "name": chillpay_card_name,
				    "number": chillpay_card_number,
				    "expiration_month": chillpay_card_expiration_month,
				    "expiration_year": chillpay_card_expiration_year,
				    "security_code": chillpay_card_security_code
				};
				
				var errors = ChillPayUtil.validate_card(card);
				if(errors.length > 0){
					showError(errors);
					$form.unblock();
					return false;
				}else{
					hideError();
					if(ChillPay){
						ChillPay.setPublicKey(chillpay_params.key);
						ChillPay.createToken("card", card, function (statusCode, response) {
						    if (statusCode == 200) {
						    	$form.append( '<input type="hidden" class="chillpay_token" name="chillpay_token" value="' + response.id + '"/>' );
						    	$( '#chillpay_card_name' ).val("");
						    	$( '#chillpay_card_number' ).val("");
						    	$( '#chillpay_card_expiration_month' ).val("");
						    	$( '#chillpay_card_expiration_year' ).val("");
						    	$( '#chillpay_card_security_code' ).val("");
								$form.submit();
						    } else {
						    	if(response.message){
						    		showError( "Unable to process payment with ChillPay. " + response.message );
						    	}else if(response.responseJSON && response.responseJSON.message){
						    		showError( "Unable to process payment with ChillPay. " + response.responseJSON.message );
						    	}else if(response.status==0){
						    		showError( "Unable to process payment with ChillPay. No response from ChillPay Api." );
						    	}else {
						    		showError( "Unable to process payment with ChillPay [ status=" + response.status + " ]" );
						    	}
						    	$form.unblock();
						    };
						  });
					}else{
						showError( 'Something wrong with connection to ChillPay.js. Please check your network connection' );
						$form.unblock();
					}
					
					return false;
				}
			}
			
		}
	}
	
	$(function(){
		$( 'body' ).on( 'checkout_error', function () {
			$( '.chillpay_token' ).remove();
		});
		
		$( 'form.checkout' ).unbind('checkout_place_order_chillpay');
		$( 'form.checkout' ).on( 'checkout_place_order_chillpay', function () {
			return chillpayFormHandler();
		});
		
		/* Pay Page Form */
		$( 'form#order_review' ).on( 'submit', function () {
			return chillpayFormHandler();
		});
		
		/* Both Forms */
		$( 'form.checkout, form#order_review' ).on( 'change', '#chillpay_cc_form input', function() {
			$( '.chillpay_token' ).remove();
		});
	})
})(jQuery)

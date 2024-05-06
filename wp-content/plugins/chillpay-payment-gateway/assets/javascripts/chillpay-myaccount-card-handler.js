(function ( $, undefined ) {
	$chillpay_card_panel = $("#chillpay_card_panel");
	$form = $("#chillpay_cc_form");
	
	function showError(message, target){
		if(target===undefined){
			target = $chillpay_card_panel;
		}
		
		target.unblock();
		
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
		
		target.prepend( $ulError );
	}
	
	function hideError(){
		$(".woocommerce-error").remove();
	}
	
	function delete_card(card_id, nonce){
		data = {
				action: "chillpay_delete_card", 
				card_id: card_id, 
				chillpay_nonce: nonce
				};
		
		$.post(chillpay_params.ajax_url, data, 
			function(response){
				if(response.deleted){
					window.location.reload();
				}else{
					showError(response.message);
				}
			}, "json"
		);
		
	}
	
	function create_card(){
		$form.block({
			message: null,
			overlayCSS: {
				background: '#fff url(' + chillpay_params.ajax_loader_url + ') no-repeat center',
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
			showError(errors, $form);
			return false;
		}else{
			hideError();
			if(ChillPay){
				ChillPay.setPublicKey(chillpay_params.key);
				ChillPay.createToken("card", card, function (statusCode, response) {
				    if (statusCode == 200) {
				    	$( '#chillpay_card_name' ).val("");
				    	$( '#chillpay_card_number' ).val("");
				    	$( '#chillpay_card_expiration_month' ).val("");
				    	$( '#chillpay_card_expiration_year' ).val("");
				    	$( '#chillpay_card_security_code' ).val("");
				    	data = {
								action: "chillpay_create_card", 
								chillpay_token: response.id, 
								chillpay_nonce: $("#chillpay_add_card_nonce").val() 
							    };
						
						$.post(chillpay_params.ajax_url, data, 
							function(wp_response){
								if(wp_response.id){
									window.location.reload();
								}else{
									showError(wp_response.message, $form);
								}
							}, "json"
						);
				    } else {
				    	if(response.message){
				    		showError( "Unable to create a card. " + response.message, $form );
				    	}else if(response.responseJSON && response.responseJSON.message){
				    		showError( "Unable to create a card. " + response.responseJSON.message, $form );
				    	}else if(response.status==0){
				    		showError( "Unable to create a card. No response from ChillPay Api.", $form );
				    	}else {
				    		showError( "Unable to create a card [ status=" + response.status + " ].", $form );
				    	}
				    };
				  });
			}else{
				showError( 'Something wrong with connection to ChillPay.js. Please check your network connection', $form );
			}
		}
	}
	
	$(".delete_card").click(function(event){
		if(confirm('Confirm delete card?')){
			var $button = $(this);
			$button.block({
				message: null,
				overlayCSS: {
					background: '#fff url(' + chillpay_params.ajax_loader_url + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});
			delete_card($button.data("card-id"), $button.data("delete-card-nonce"));
		}
	});
	
	$("#chillpay_add_new_card").click(function(event){
		create_card();
	});
}
)(jQuery);

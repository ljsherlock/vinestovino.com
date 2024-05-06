<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

	$bigcCards = $viewData['bigc']; 
    $cenpayCards = $viewData['cenpay']; 
    $currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-billpayment">
    <ul style="list-style:inherit">
        <?php if ( $currency == 'THB') { ?>	
            <!-- Big C -->
            <?php if ( $bigcCards ) : true ?>
                <li class="item">
                    <input id="billpayment_bigc" type="radio" name="chillpay-offsite" value="billpayment_bigc" <?php if($viewData['fee_bigc'] == -1) { echo 'disabled'; } ?> />
                    <label for="billpayment_bigc">
                        <div class="chillpay-form-billpayment-logo-box bigc">
                            <img class="<?php if($viewData['fee_bigc'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bill-payment/IconCounterBillPayment_BigC.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-billpayment-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_bigc'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Big C', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_bigc'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_bigc" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_bigc">
                                    <?php echo $viewData['fee_bigc'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </label>
                </li>
            <?php endif; ?>

            <!-- CenPay -->
            <?php if ( $cenpayCards ) : true ?>
                <li class="item">
                    <input id="billpayment_cenpay" type="radio" name="chillpay-offsite" value="billpayment_cenpay" <?php if($viewData['fee_cenpay'] == -1) { echo 'disabled'; } ?> />
                    <label for="billpayment_cenpay">
                        <div class="chillpay-form-billpayment-logo-box cenpay">
                            <img class="<?php if($viewData['fee_cenpay'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bill-payment/IconCounterBillPayment_CenPay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-billpayment-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_cenpay'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'CenPay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_cenpay'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_cenpay" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_cenpay">
                                    <?php echo $viewData['fee_cenpay'] ?>
                                </span>
                            <?php endif; ?>
                    </div>
                    </label>
                </li>    
            <?php endif; ?>

            <!-- Counter Bill Payment -->
            <?php if ( $cenpayCards ) : true ?>
                <li class="item">
                    <input id="billpayment_counter" type="radio" name="chillpay-offsite" value="billpayment_counter" <?php if($viewData['fee_counter_bill_payment'] == -1) { echo 'disabled'; } ?> />
                    <label for="billpayment_counter">
                        <div class="chillpay-form-billpayment-logo-box counter_bill_payment">
                            <img class="<?php if($viewData['fee_counter_bill_payment'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bill-payment/IconCounterBillPayment.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-billpayment-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_counter_bill_payment'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Counter Bill Payment', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_counter_bill_payment'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_counter_bill_payment" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_counter_bill_payment">
                                    <?php echo $viewData['fee_counter_bill_payment'] ?>
                                </span>
                            <?php endif; ?>
                    </div>
                    </label>
                </li>    
            <?php endif; ?>
        <?php } else { ?>
            <p style="color:red;font-size:16px;" >
                <?php echo __( 'Currency Not Supported.', 'chillpay' ); ?>
            </p>
        <?php } ?>       
    </ul>
</fieldset>

<script>
	(function ($) {
		
	}(jQuery));
</script>
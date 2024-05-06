<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

    $boontermCards = $viewData['boonterm']; 
    $currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-kiosk-machine">
    <ul style="list-style:inherit">
        <?php if ( $currency == 'THB') { ?>	
            <!-- Boonterm -->
            <?php if ( $boontermCards ) : true ?>
                <li class="item">
                    <input id="billpayment_boonterm" type="radio" name="chillpay-offsite" value="billpayment_boonterm" <?php if($viewData['fee_boonterm'] == -1) { echo 'disabled'; } ?>/>
                    <label for="billpayment_boonterm">
                        <div class="chillpay-form-kiosk-machine-logo-box bigc">
                            <img class="<?php if($viewData['fee_boonterm'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-kiosk-machine/IconKiosk_Boonterm.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-kiosk-machine-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_boonterm'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Boonterm', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_boonterm'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_boonterm" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_boonterm">
                                    <?php echo $viewData['fee_boonterm'] ?>
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
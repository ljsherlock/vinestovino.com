<style>
.float-left {
    float: left !important;
}
.chillpay-form-creditcard-logo-box img {
    margin: 0 !important;
}
</style>

<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

    $creditCards = $viewData['creditcard']; 
	$unionpayCards = $viewData['unionpay'];
    $currency = $viewData['currency'];
    $card_type = $viewData['card_type'];
?>
<fieldset id="chillpay-form-creditcard">
    <!-- <ul style="list-style:inherit; <--?php if ( isset($currency) ) { ?> margin-left: 0; <--?php } ?>"> -->
    <ul style="list-style:inherit;">
        <?php if ( isset($currency) ) { ?>	
                <!-- VISA, MASTER CARD, JCB -->
                <?php if ( $creditCards ) : true ?>
                    <li class="item">
                        <input id="creditcard" type="radio" name="chillpay-offsite" value="creditcard" data-cardtype="" <?php if($viewData['fee_credit'] == -1) { echo 'disabled'; } ?> } />
                        <label for="creditcard">
                            <div class="chillpay-form-creditcard-logo-box <?php if($viewData['fee_credit'] == -1) { echo 'opacity-1'; } ?>">
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_VISA.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_MasterCard.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_JCB.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                        </label>
                        <div class="chillpay-form-creditcard-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_credit'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'VISA, MASTER CARD, JCB', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_credit'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_creditcard">
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_creditcard">
                                    <?php echo $viewData['fee_credit'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endif; ?>
                <!-- UnionPay -->
                <?php if ( $unionpayCards ) : true ?>
                    <li class="item">
                        <input id="creditcard_unionpay" type="radio" name="chillpay-offsite" value="creditcard" data-cardtype="unionpay" <?php if($viewData['fee_credit'] == -1 || strcmp($card_type, 'unionpay') !== 0) { echo 'disabled'; } ?> } />
                        <label for="creditcard_unionpay">
                            <div class="chillpay-form-creditcard-logo-box <?php if($viewData['fee_credit'] == -1) { echo 'opacity-1'; } ?>">
                                <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_UnionPay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                        </label>
                        <div class="chillpay-form-creditcard-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_credit'] == -1 || strcmp($card_type, 'unionpay') !== 0) { echo 'opacity-1'; } ?>"><?php _e( 'UnionPay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_credit'] == -1 || strcmp($card_type, 'unionpay') !== 0 ) : ?>
                                <span class="rate secondary-text" id="rate_creditcard">
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_creditcard">
                                    <?php echo $viewData['fee_credit'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endif; ?>

                <!-- <li class="item">
                    <label for="creditcard" style="padding-left: 0;">
                        <div class="chillpay-form-creditcard-logo-box <--?php if($viewData['fee_credit'] == -1) { echo 'opacity-1'; } ?>">
                            <img class="float-left" src="<--?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_VISA.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            <img class="float-left" src="<--?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_MasterCard.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            <img class="float-left" src="<--?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_JCB.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            <img class="float-left" src="<--?php echo plugins_url( '../../assets/images/icon-credit-card/IconCreditCard_UnionPay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-creditcard-label-box" style="width:100%;">
                        <--?php if ( $viewData['fee_credit'] == -1 ) : ?>
                            <span class="rate secondary-text" id="rate_creditcard" >
                                Channel not available
                            </span>
                        <--?php else : ?>
                            <span class="rate secondary-text" id="rate_creditcard">
                                <--?php echo $viewData['fee_credit'] ?>
                            </span>
                        <--?php endif; ?>
                        </div>
                    </label>
                </li> -->
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

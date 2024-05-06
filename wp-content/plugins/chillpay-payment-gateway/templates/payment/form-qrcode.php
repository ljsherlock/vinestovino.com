<?php 
    $qrCards = $viewData['qrcode']; 
    $currency = $viewData['currency'];
?>
<style>
.float-left {
    float: left !important;
}
.chillpay-form-qrcode-logo-box img {
    margin: 0 !important;
}
.no-style {
    list-style: none !important;
}
</style>

<fieldset id="chillpay-form-qrcode">
<ul style="list-style:inherit; <?php if ( $currency == 'THB') { ?> margin-left: 0; <?php } ?>">
        <?php if ( $currency == 'THB') { ?>	
                <li class="item">
                    <label for="qrcode" style="padding-left: 0;">
                        <div class="chillpay-form-qrcode-logo-box <?php if($viewData['fee_qrcode'] == -1) { echo 'opacity-1'; } ?>">
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTB2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BAY2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BBL2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_TTB2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KPLUS2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_GSB2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                            <img class="float-left" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_UOB2.png', __FILE__ ); ?>" style="width:25px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-qrcode-label-box" style="width:100%;">
                        <?php if ( $viewData['fee_qrcode'] == -1 ) : ?>
                            <span class="rate secondary-text" id="rate_qrcode" >
                                Channel not available
                            </span>
                        <?php else : ?>
                            <span class="rate secondary-text" id="rate_qrcode">
                                <?php echo $viewData['fee_qrcode'] ?>
                            </span>
                        <?php endif; ?>
                        </div>
                    </label>
                </li>
        <?php } else { ?>
            <p style="color:red;font-size:16px;" >
                <?php echo __( 'Currency Not Supported.', 'chillpay' ); ?>
            </p>
        <?php } ?>
    </ul>
</fieldset>

<script>
    (function ($) {
        //console.log($('.wc_payment_method').addClass('no-style'));
	}(jQuery));
</script>

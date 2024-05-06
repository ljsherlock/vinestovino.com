<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';
    
    $ktcForeverCards = $viewData['point_ktc_forever']; 
    $currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-pay-with-points">
    <ul style="list-style:inherit">
        <?php if ( $currency == 'THB') { ?>	
            <!-- KTC Forever -->
            <?php if ( $ktcForeverCards ) : true ?>
                <li class="item">
                    <input id="point_ktc_forever" type="radio" name="chillpay-offsite" value="point_ktc_forever" <?php if($viewData['fee_point_ktc_forever'] == -1) { echo 'disabled'; } ?> />
                    <label for="point_ktc_forever">
                        <div class="chillpay-form-pay-with-points-logo-box bigc">
                            <img class="<?php if($viewData['fee_point_ktc_forever'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-pay-with-points/IconPayWithPoints_KTC-Forever.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-pay-with-points-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_point_ktc_forever'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'KTC Forever', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_point_ktc_forever'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_point_ktc_forever" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_point_ktc_forever">
                                    <?php echo $viewData['fee_point_ktc_forever'] ?><br/>
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
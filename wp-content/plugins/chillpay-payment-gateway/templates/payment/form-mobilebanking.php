<?php
	require_once dirname( __FILE__ ) . '/main-script.php';

    $kplusCards = $viewData['kplus'];
    $scbeasyCards = $viewData['scb_easy'];
    $kmaCards = $viewData['kma'];
    $bblmbankingCards = $viewData['bbl_mbanking'];
    $krungthainextCards = $viewData['krungthai_next'];
    $currency = $viewData['currency'];

    $get_locale = get_locale();
    $lang_code = 'EN';
    if (strpos($get_locale, 'th') !== false)
    {
        $lang_code = 'TH';
    }
?>
<fieldset id="chillpay-form-mobilebanking">
    <ul style="list-style:inherit">
        <?php if ( $currency == 'THB') { ?>	
            <!-- K PLUS --> 
            <?php if ( $kplusCards ) : true ?>   
                <li class="item">
                    <input id="payplus_kbank" type="radio" name="chillpay-offsite" value="payplus_kbank" <?php if($viewData['fee_kplus'] == -1) { echo 'disabled'; } ?> />
                    <label for="payplus_kbank">
                        <div class="chillpay-form-mobilebanking-logo-box">
                            <img class="<?php if($viewData['fee_kplus'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KPLUS2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-mobilebanking-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_kplus'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Kasikorn Bank (K PLUS)', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_kplus'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_kplus" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_kplus">
                                    <?php echo $viewData['fee_kplus'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if ( $viewData['fee_kplus'] != -1 ) : ?>
                            <p>
                                <input id="chillpay_kplus_mobile"
                                    class="input-text" type="tel" autocomplete="off" maxlength="10"
                                    placeholder="08xxxxxxxx" name="chillpay_kplus_mobile">
                                <br>
                                <?php if ( strpos($get_locale, 'th') !== false ) { ?>
                                    <label class="note-text" id="chillpay_kplus_mobile_text" for="chillpay_kplus_mobile" style="color: red; font-size: 85%;"><?php _e( '*กรุณาระบุเบอร์โทรศัพท์ที่เชื่อมกับ K PLUS', 'chillpay' ); ?></label>
                                <?php } else { ?>
                                    <label class="note-text" id="chillpay_kplus_mobile_text" for="chillpay_kplus_mobile" style="color: red; font-size: 85%;"><?php _e( '*Please fill in KPLUS mobile phone number.', 'chillpay' ); ?></label>
                                <?php } ?>  
                            </p>
                        <?php endif; ?>
                    </label>
                </li>  
            <?php endif; ?>

            <!-- SCB Easy App -->
            <?php if ( $scbeasyCards ) : true ?>   
                <li class="item">
                    <input id="mobilebank_scb" type="radio" name="chillpay-offsite" value="mobilebank_scb" <?php if($viewData['fee_scb_easy'] == -1) { echo 'disabled'; } ?> />
                    <label for="mobilebank_scb">
                        <div class="chillpay-form-mobilebanking-logo-box">
                            <img class="<?php if($viewData['fee_scb_easy'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-mobile-banking/IconMobile_SCB_EASY2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-mobilebanking-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_scb_easy'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Siam Commercial Bank (SCB Easy App)', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_scb_easy'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_scb_easy" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_scb_easy">
                                    <?php echo $viewData['fee_scb_easy'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </label>
                </li>  
            <?php endif; ?>

            <!-- KMA App -->
            <?php if ( $kmaCards ) : true ?>
                <li class="item">
                    <input id="mobilebank_bay" type="radio" name="chillpay-offsite" value="mobilebank_bay" <?php if($viewData['fee_kma'] == -1) { echo 'disabled'; } ?> />
                    <label for="mobilebank_bay">
                        <div class="chillpay-form-mobilebanking-logo-box">
                            <img class="<?php if($viewData['fee_kma'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BAY2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-mobilebanking-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_kma'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Krungsri Bank (KMA App)', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_kma'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_kma" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_kma">
                                    <?php echo $viewData['fee_kma'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </label>
                </li>
            <?php endif; ?>

            <!-- Bualuang mBanking -->
            <?php if ( $bblmbankingCards ) : true ?>
                <li class="item">
                    <input id="mobilebank_bbl" type="radio" name="chillpay-offsite" value="mobilebank_bbl" <?php if($viewData['fee_bbl_mbanking'] == -1) { echo 'disabled'; } ?> />
                    <label for="mobilebank_bbl">
                        <div class="chillpay-form-mobilebanking-logo-box">
                            <img class="<?php if($viewData['fee_bbl_mbanking'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BBL2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-mobilebanking-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_bbl_mbanking'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Bangkok Bank (Bualuang mBanking)', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_bbl_mbanking'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_bbl_mbanking" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_bbl_mbanking">
                                    <?php echo $viewData['fee_bbl_mbanking'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </label>
                </li>
            <?php endif; ?>


            <!-- Krungthai NEXT -->
            <?php if ( $krungthainextCards ) : true ?>
                <li class="item">
                    <input id="mobilebank_ktb" type="radio" name="chillpay-offsite" value="mobilebank_ktb" <?php if($viewData['fee_krungthai_next'] == -1) { echo 'disabled'; } ?> />
                    <label for="mobilebank_ktb">
                        <div class="chillpay-form-mobilebanking-logo-box">
                            <img class="<?php if($viewData['fee_krungthai_next'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-mobilebanking-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_krungthai_next'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Krungthai Bank (Krungthai NEXT)', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_krungthai_next'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_krungthai_next" >
                                    Channel not available
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_krungthai_next">
                                    <?php echo $viewData['fee_krungthai_next'] ?>
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
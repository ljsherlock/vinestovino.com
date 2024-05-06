<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

    $kbankCards = $viewData['installment_kbank']; 
    $ktcCards = $viewData['installment_ktc_flexi']; 
    $scbCards = $viewData['installment_scb'];
    $krungsriCards = $viewData['installment_krungsri'];
    $firstchoiceCards = $viewData['installment_firstchoice'];
    //$tbankCards = $viewData['installment_tbank']; 
    $currency = $viewData['currency'];
    $cart_total = $viewData["cart_total"];

    $min_amount_installment_kbank = null;
    $max_amount_installment_kbank = null;

    $has_merchant_route_installment_kbank = $viewData['has_merchant_route_installment_kbank'];
    if($has_merchant_route_installment_kbank > 0){
        $min_amount_installment_kbank = number_format((float)$viewData["min_amount_installment_kbank"], 2, '.', '');
        $max_amount_installment_kbank = number_format((float)$viewData["max_amount_installment_kbank"], 2, '.', '');
    }

    $min_amount_installment_ktc_flexi = null;
    $max_amount_installment_ktc_flexi = null;

    $has_merchant_route_installment_ktc_flexi = $viewData['has_merchant_route_installment_ktc_flexi'];
    if($has_merchant_route_installment_ktc_flexi > 0){
        $min_amount_installment_ktc_flexi = number_format((float)$viewData["min_amount_installment_ktc_flexi"], 2, '.', '');
        $max_amount_installment_ktc_flexi = number_format((float)$viewData["max_amount_installment_ktc_flexi"], 2, '.', '');
    }

    $min_amount_installment_scb = null;
    $max_amount_installment_scb = null;

    $has_merchant_route_installment_scb = $viewData['has_merchant_route_installment_scb'];
    if($has_merchant_route_installment_scb > 0){
        $min_amount_installment_scb = number_format((float)$viewData["min_amount_installment_scb"], 2, '.', '');
        $max_amount_installment_scb = number_format((float)$viewData["max_amount_installment_scb"], 2, '.', '');
    }

    $min_amount_installment_krungsri = null;
    $max_amount_installment_krungsri = null;
    $card_type_installment_krungsri = '';

    $has_merchant_route_installment_krungsri = $viewData['has_merchant_route_installment_krungsri'];
    if($has_merchant_route_installment_krungsri > 0){
        $min_amount_installment_krungsri = number_format((float)$viewData["min_amount_installment_krungsri"], 2, '.', '');
        $max_amount_installment_krungsri = number_format((float)$viewData["max_amount_installment_krungsri"], 2, '.', '');
        $card_type_installment_krungsri = $viewData['card_type_installment_krungsri'];
    }

    $min_amount_installment_firstchoice = null;
    $max_amount_installment_firstchoice = null;
    $card_type_installment_firstchoice = '';

    $has_merchant_route_installment_firstchoice = $viewData['has_merchant_route_installment_firstchoice'];
    if($has_merchant_route_installment_firstchoice > 0){
        $min_amount_installment_firstchoice = number_format((float)$viewData["min_amount_installment_firstchoice"], 2, '.', '');
        $max_amount_installment_firstchoice = number_format((float)$viewData["max_amount_installment_firstchoice"], 2, '.', '');
        $card_type_installment_firstchoice = $viewData['card_type_installment_firstchoice'];
    }

?>
<fieldset id="chillpay-form-installment">
    <ul style="list-style:inherit;margin-bottom: 0px;">
        <?php if ( $currency == 'THB') { ?>
            <!-- KBANK -->
            <?php if ( $kbankCards ) : true ?>              
                <li class="item"> 
                <?php if ( $cart_total >= $min_amount_installment_kbank && $cart_total <= $max_amount_installment_kbank ) { ?>
                    <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" />
                    <label for="installment_kbank">
                        <div class="chillpay-form-installment-logo-box kbank">
                            <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <input id="absorb_by_installment_kbank" name="absorb_by_installment_kbank" type="hidden" value="<?php echo $viewData["absorb_by_installment_kbank"]; ?>" />

                    <select id="kbank_installment_terms" name="kbank_installment_terms" class="installment-term-select-box">
                        <option value="0">Select term</option>
                        <?php foreach ( $viewData['installment_kbank_data'] as $installment_plan ) : ?>
                            <?php foreach ( $installment_plan as $data ) : ?>
                                <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                <?php
                                echo sprintf(
                                    __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                    $data['term_length']
                                );
                                ?>

                                <?php
                                echo sprintf(
                                    __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                    number_format((float)$data['monthly_amount'], 2)
                                );
                                ?>
                            
                            <?php endforeach; ?>                                           
                        <?php endforeach; ?>          
                    </select>

                    <br/><span class="chillpay-installment-interest-rate">
                        <label id="interest_kbank"></label>
                    </span>

                <?php } elseif ($cart_total < $min_amount_installment_kbank) {?> 
                    <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" disabled/>
                    <label for="installment_kbank">
                        <div class="chillpay-form-installment-logo-box kbank">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_kbank,2).' THB).', 'chillpay'); ?>
                    </span>
                <?php } elseif ($max_amount_installment_kbank < 0) { ?>   
                    <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" disabled />
                    <label for="installment_kbank">
                        <div class="chillpay-form-installment-logo-box kbank">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('Channel not available', 'chillpay'); ?>
                    </span>
                <?php } else { ?>
                    <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" disabled />
                    <label for="installment_kbank">
                        <div class="chillpay-form-installment-logo-box kbank">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('Channel not available', 'chillpay'); ?>
                    </span>
                <?php } ?>
                </li>                                                
            <?php endif; ?>

            <!-- KTC FIexi -->
            <?php if ( $ktcCards ) : true ?>
                <li class="item">                         
                    <?php if ( $cart_total >= $min_amount_installment_ktc_flexi && $cart_total <= $max_amount_installment_ktc_flexi) { ?>
                        <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" />
                        <label for="installment_ktc_flexi">
                            <div class="chillpay-form-installment-logo-box ktc">
                                <img src="<?php echo plugins_url( '../../assets/images/icon-bank//IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title"><?php _e( 'KTC Flexi', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <input id="absorb_by_installment_ktc_flexi" name="absorb_by_installment_ktc_flexi" type="hidden" value="<?php echo $viewData["absorb_by_installment_ktc_flexi"]; ?>" />
                        
                        <select id="ktc_installment_terms" name="ktc_installment_terms" class="installment-term-select-box">
                            <option value="0">Select term</option>
                            <?php foreach ( $viewData['installment_ktc_flexi_data'] as $installment_plan ) : ?>
                                <?php foreach ( $installment_plan as $data ) : ?>
                                    <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                    <?php
                                    echo sprintf(
                                        __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                        $data['term_length']
                                    );
                                    ?>

                                    <?php
                                    echo sprintf(
                                        __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                        number_format((float)$data['monthly_amount'], 2)
                                    );
                                    ?>
                                <?php endforeach; ?>    
                                
                            <?php endforeach; ?>          
                        </select>

                        <br/><span class="chillpay-installment-interest-rate">
                            <label id="interest_kbank_ktc_flexi"></label>
                        </span>
                    <?php } elseif ($cart_total < $min_amount_installment_ktc_flexi) { ?>
                        <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                        <label for="installment_ktc_flexi">
                            <div class="chillpay-form-installment-logo-box ktc">
                                <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title opacity-1"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_ktc_flexi,2).' THB).', 'chillpay'); ?>
                        </span>
                    <?php } elseif ($max_amount_installment_ktc_flexi < 0) { ?>
                        <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                        <label for="installment_ktc_flexi">
                            <div class="chillpay-form-installment-logo-box ktc">
                                <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title opacity-1"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('Channel not available', 'chillpay'); ?>
                        </span>
                    <?php } else { ?> 
                        <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                        <label for="installment_ktc_flexi">
                            <div class="chillpay-form-installment-logo-box ktc">
                                <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title opacity-1"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('Channel not available', 'chillpay'); ?>
                        </span>
                    <?php } ?>
                </li>              
            <?php endif; ?>    
            
            <!-- SCB -->
            <?php if ( $scbCards ) : true ?>
                <li class="item"> 
                <?php if ( $cart_total >= $min_amount_installment_scb && $cart_total <= $max_amount_installment_scb ) { ?>
                    <input id="installment_scb" type="radio" name="chillpay-offsite" value="installment_scb" />
                    <label for="installment_scb">
                        <div class="chillpay-form-installment-logo-box scb">
                            <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title"><?php _e( 'Siam Commercial Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <input id="absorb_by_installment_scb" name="absorb_by_installment_scb" type="hidden" value="<?php echo $viewData["absorb_by_installment_scb"]; ?>" />

                    <select id="scb_installment_terms" name="scb_installment_terms" class="installment-term-select-box">
                        <option value="0">Select term</option>
                        <?php foreach ( $viewData['installment_scb_data'] as $installment_plan ) : ?>
                            <?php foreach ( $installment_plan as $data ) : ?>
                                <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                <?php
                                echo sprintf(
                                    __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                    $data['term_length']
                                );
                                ?>

                                <?php
                                echo sprintf(
                                    __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                    number_format((float)$data['monthly_amount'], 2)
                                );
                                ?>
                            
                            <?php endforeach; ?>                                           
                        <?php endforeach; ?>          
                    </select>

                    <br/><span class="chillpay-installment-interest-rate">
                        <label id="interest_scb"></label>
                    </span>

                <?php } elseif ($cart_total < $min_amount_installment_scb) {?> 
                    <input id="installment_scb" type="radio" name="chillpay-offsite" value="installment_scb" disabled/>
                    <label for="installment_scb">
                        <div class="chillpay-form-installment-logo-box scb">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Siam Commercial Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_scb,2).' THB).', 'chillpay'); ?>
                    </span>
                <?php } elseif ($max_amount_installment_scb < 0) { ?>   
                    <input id="installment_scb" type="radio" name="chillpay-offsite" value="installment_scb" disabled />
                    <label for="installment_scb">
                        <div class="chillpay-form-installment-logo-box scb">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Siam Commercial Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('Channel not available', 'chillpay'); ?>
                    </span>
                <?php } else { ?>
                    <input id="installment_scb" type="radio" name="chillpay-offsite" value="installment_scb" disabled />
                    <label for="installment_scb">
                        <div class="chillpay-form-installment-logo-box scb">
                            <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-installment-label-box" style="width:100%;">
                            <span class="title opacity-1"><?php _e( 'Siam Commercial Bank', 'chillpay' ); ?></span><br/>
                        </div>
                    </label>
                    <span class="secondary-text">
                        <?php echo __('Channel not available', 'chillpay'); ?>
                    </span>
                <?php } ?>
                </li> 
            <?php endif; ?>

            <!-- Krungsri Consumer -->
            <?php if ( $krungsriCards ) : true ?>
                <?php if ( strpos($card_type_installment_krungsri, 'creditcard') !== False ) {?>
                    <li class="item"> 
                        <?php if ( $cart_total >= $min_amount_installment_krungsri && $cart_total <= $max_amount_installment_krungsri ) { ?>
                            <input id="installment_krungsri" type="radio" name="chillpay-offsite" value="installment_krungsri" data-cardtype="creditcard" />
                            <label for="installment_krungsri">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'Krungsri Consumer', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <input id="absorb_by_installment_krungsri" name="absorb_by_installment_krungsri" type="hidden" value="<?php echo $viewData["absorb_by_installment_krungsri"]; ?>" />

                            <select id="krungsri_installment_terms" name="krungsri_installment_terms" class="installment-term-select-box">
                                <option value="0">Select term</option>
                                <?php foreach ( $viewData['installment_krungsri_data'] as $installment_plan ) : ?>
                                    <?php foreach ( $installment_plan as $data ) : ?>
                                        <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                        <?php
                                        echo sprintf(
                                            __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                            $data['term_length']
                                        );
                                        ?>

                                        <?php
                                        echo sprintf(
                                            __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                            number_format((float)$data['monthly_amount'], 2)
                                        );
                                        ?>
                                    
                                    <?php endforeach; ?>                                           
                                <?php endforeach; ?>          
                            </select>

                            <br/><span class="chillpay-installment-interest-rate">
                                <label id="interest_krungsri"></label>
                            </span>

                        <?php } elseif ($cart_total < $min_amount_installment_krungsri) {?> 
                            <input id="installment_krungsri" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled/>
                            <label for="installment_krungsri">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_krungsri,2).' THB).', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($max_amount_installment_krungsri < 0) { ?>   
                            <input id="installment_krungsri" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled />
                            <label for="installment_krungsri">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } else { ?>
                            <input id="installment_krungsri" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled />
                            <label for="installment_krungsri">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } ?>
                    </li>
                <?php } ?>
                
                <?php if ( strpos($card_type_installment_krungsri, 'loancard') !== False ) { ?>
                    <li class="item"> 
                        <?php if ( $cart_total >= $min_amount_installment_krungsri && $cart_total <= $max_amount_installment_krungsri ) { ?>
                            <input id="installment_krungsri_loancard" type="radio" name="chillpay-offsite" value="installment_krungsri" data-cardtype="loancard" />
                            <label for="installment_krungsri_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'Krungsri Consumer (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <input id="absorb_by_installment_krungsri" name="absorb_by_installment_krungsri" type="hidden" value="<?php echo $viewData["absorb_by_installment_krungsri"]; ?>" />

                            <select id="krungsri_loancard_installment_terms" name="krungsri_loancard_installment_terms" class="installment-term-select-box">
                                <option value="0">Select term</option>
                                <?php foreach ( $viewData['installment_krungsri_data'] as $installment_plan ) : ?>
                                    <?php foreach ( $installment_plan as $data ) : ?>
                                        <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                        <?php
                                        echo sprintf(
                                            __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                            $data['term_length']
                                        );
                                        ?>

                                        <?php
                                        echo sprintf(
                                            __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                            number_format((float)$data['monthly_amount'], 2)
                                        );
                                        ?>
                                    
                                    <?php endforeach; ?>                                           
                                <?php endforeach; ?>          
                            </select>

                            <br/><span class="chillpay-installment-interest-rate">
                                <label id="interest_krungsri_loancard"></label>
                            </span>

                        <?php } elseif ($cart_total < $min_amount_installment_krungsri) {?> 
                            <input id="installment_krungsri_loancard" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled/>
                            <label for="installment_krungsri_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_krungsri,2).' THB).', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($max_amount_installment_krungsri < 0) { ?>   
                            <input id="installment_krungsri_loancard" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled />
                            <label for="installment_krungsri_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } else { ?>
                            <input id="installment_krungsri_loancard" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled />
                            <label for="installment_krungsri_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri Consumer (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } ?>
                    </li>
                <?php } ?>

                <?php if (empty($card_type_installment_krungsri) ) { ?>
                    <li class="item">
                        <input id="installment_krungsri" type="radio" name="chillpay-offsite" value="installment_krungsri" disabled />
                        <label for="installment_krungsri">
                            <div class="chillpay-form-installment-logo-box scb">
                                <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_Krusri_Consumer.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title opacity-1"><?php _e( 'Krungsri Consumer', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('Channel not available', 'chillpay'); ?>
                        </span>
                    </li>
                <?php } ?>
            <?php endif; ?>

            <!-- Krungsri First Choice -->
            <?php if ( $firstchoiceCards ) : true ?>
                <?php if ( strpos($card_type_installment_firstchoice, 'creditcard') !== False ) {?>
                    <li class="item"> 
                        <?php if ( $cart_total >= $min_amount_installment_firstchoice && $cart_total <= $max_amount_installment_firstchoice ) { ?>
                            <input id="installment_firstchoice" type="radio" name="chillpay-offsite" value="installment_firstchoice" data-cardtype="creditcard"/>
                            <label for="installment_firstchoice">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'Krungsri First Choice', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <input id="absorb_by_installment_firstchoice" name="absorb_by_installment_firstchoice" type="hidden" value="<?php echo $viewData["absorb_by_installment_firstchoice"]; ?>" />

                            <select id="firstchoice_installment_terms" name="firstchoice_installment_terms" class="installment-term-select-box">
                                <option value="0">Select term</option>
                                <?php foreach ( $viewData['installment_firstchoice_data'] as $installment_plan ) : ?>
                                    <?php foreach ( $installment_plan as $data ) : ?>
                                        <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                        <?php
                                        echo sprintf(
                                            __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                            $data['term_length']
                                        );
                                        ?>

                                        <?php
                                        echo sprintf(
                                            __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                            number_format((float)$data['monthly_amount'], 2)
                                        );
                                        ?>
                                    
                                    <?php endforeach; ?>                                           
                                <?php endforeach; ?>          
                            </select>

                            <br/><span class="chillpay-installment-interest-rate">
                                <label id="interest_firstchoice"></label>
                            </span>

                        <?php } elseif ($cart_total < $min_amount_installment_firstchoice) {?> 
                            <input id="installment_firstchoice" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled/>
                            <label for="installment_firstchoice">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_firstchoice,2).' THB).', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($max_amount_installment_firstchoice < 0) { ?>   
                            <input id="installment_firstchoice" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled />
                            <label for="installment_firstchoice">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } else { ?>
                            <input id="installment_firstchoice" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled />
                            <label for="installment_firstchoice">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } ?>
                    </li>
                <?php } ?>
                
                <?php if ( strpos($card_type_installment_firstchoice, 'loancard') !== False ) { ?>
                    <li class="item"> 
                        <?php if ( $cart_total >= $min_amount_installment_firstchoice && $cart_total <= $max_amount_installment_firstchoice ) { ?>
                            <input id="installment_firstchoice_loancard" type="radio" name="chillpay-offsite" value="installment_firstchoice" data-cardtype="loancard"/>
                            <label for="installment_firstchoice_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'Krungsri First Choice (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <input id="absorb_by_installment_firstchoice" name="absorb_by_installment_firstchoice" type="hidden" value="<?php echo $viewData["absorb_by_installment_firstchoice"]; ?>" />

                            <select id="firstchoice_loancard_installment_terms" name="firstchoice_loancard_installment_terms" class="installment-term-select-box">
                                <option value="0">Select term</option>
                                <?php foreach ( $viewData['installment_firstchoice_data'] as $installment_plan ) : ?>
                                    <?php foreach ( $installment_plan as $data ) : ?>
                                        <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                        <?php
                                        echo sprintf(
                                            __( '%d months', 'chillpay', 'chillpay_installment_term_option' ),
                                            $data['term_length']
                                        );
                                        ?>

                                        <?php
                                        echo sprintf(
                                            __( '(฿%s / month )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                            number_format((float)$data['monthly_amount'], 2)
                                        );
                                        ?>
                                    
                                    <?php endforeach; ?>                                           
                                <?php endforeach; ?>          
                            </select>

                            <br/><span class="chillpay-installment-interest-rate">
                                <label id="interest_firstchoice_loancard"></label>
                            </span>

                        <?php } elseif ($cart_total < $min_amount_installment_firstchoice) {?> 
                            <input id="installment_firstchoice_loancard" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled/>
                            <label for="installment_firstchoice_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('There are no installment plans available for this purchase amount (minimum amount is '.number_format((float)$min_amount_installment_firstchoice,2).' THB).', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($max_amount_installment_firstchoice < 0) { ?>   
                            <input id="installment_firstchoice_loancard" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled />
                            <label for="installment_firstchoice_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } else { ?>
                            <input id="installment_firstchoice_loancard" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled />
                            <label for="installment_firstchoice_loancard">
                                <div class="chillpay-form-installment-logo-box scb">
                                    <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title opacity-1"><?php _e( 'Krungsri First Choice (Loan Card)', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('Channel not available', 'chillpay'); ?>
                            </span>
                        <?php } ?>
                    </li>
                <?php } ?>

                <?php if ( empty($card_type_installment_firstchoice) ) { ?>
                    <li class="item"> 
                        <input id="installment_firstchoice" type="radio" name="chillpay-offsite" value="installment_firstchoice" disabled />
                        <label for="installment_firstchoice">
                            <div class="chillpay-form-installment-logo-box scb">
                                <img class="opacity-1" src="<?php echo plugins_url( '../../assets/images/icon-installment/IconInstallment_FirstChoice.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title opacity-1"><?php _e( 'Krungsri First Choice', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('Channel not available', 'chillpay'); ?>
                        </span>
                    </li>
                <?php } ?>
            <?php endif; ?>
                             
        <?php } else { ?>
			<p style="color:red;font-size:16px;" >
				<?php echo __( 'Currency Not Supported.', 'chillpay' ); ?>
			</p>
		<?php } ?>	  
    </ul>
    <div class"hidden">
        <input type="hidden" name="cardtype" value="" />
    </div>
</fieldset>

<script>
	(function ($) {

        var selection_kbank = document.getElementById("kbank_installment_terms");
        if(selection_kbank != null)
        {
            selection_kbank.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_kbank').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_kbank').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }    

        var selection_ktc = document.getElementById("ktc_installment_terms");
        if(selection_ktc != null)
        {
            selection_ktc.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_kbank_ktc_flexi').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_kbank_ktc_flexi').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }  

        var selection_scb = document.getElementById("scb_installment_terms");
        if(selection_scb != null)
        {
            selection_scb.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_scb').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_scb').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }

        var selection_krungsri = document.getElementById("krungsri_installment_terms");
        if(selection_krungsri != null)
        {
            selection_krungsri.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_krungsri').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_krungsri').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }

        var selection_krungsri_loancard = document.getElementById("krungsri_loancard_installment_terms");
        if(selection_krungsri_loancard != null)
        {
            selection_krungsri_loancard.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_krungsri_loancard').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_krungsri_loancard').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }

        var selection_firstchoice = document.getElementById("firstchoice_installment_terms");
        if(selection_firstchoice != null)
        {
            selection_firstchoice.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_firstchoice').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_firstchoice').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }

        var selection_firstchoice_loancard = document.getElementById("firstchoice_loancard_installment_terms");
        if(selection_firstchoice_loancard != null)
        {
            selection_firstchoice_loancard.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_firstchoice_loancard').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_firstchoice_loancard').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        }

        var selection_tbank = document.getElementById("tbank_installment_terms");
        if(selection_tbank != null)
        {
            selection_tbank.onchange = function(event){
                var interest = event.target.options[event.target.selectedIndex].dataset.interest;
                if(!interest)
                {
                    document.getElementById('interest_tbank').innerHTML = '';
                }
                else
                {
                    var interest_rate = Number(interest).toFixed(2);
                    document.getElementById('interest_tbank').innerHTML = '( interest '+ interest_rate +'% )';
                }
            };
        } 
     
        $("[name=checkout]").on('submit',function(e){
            e.preventDefault();
            let channel = $("[name=chillpay-offsite]:checked");
            let card_type = channel.data('cardtype');
            if(typeof card_type !== 'undefined') {
                $('[name=cardtype]').val(card_type);
            }
        });

        $("#order_review").on('submit',function(e){
            let channel = $("[name=chillpay-offsite]:checked");
            let card_type = channel.data('cardtype');
            if(typeof card_type !== 'undefined') {
                $('[name=cardtype]').val(card_type);
            }
        });

    }(jQuery));
</script>
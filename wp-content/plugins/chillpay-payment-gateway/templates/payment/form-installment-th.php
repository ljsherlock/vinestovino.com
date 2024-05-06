<?php 
    $kbankCards = $viewData['installment_kbank']; 
    $ktcCards = $viewData['installment_ktc_flexi']; 
    //$tbankCards = $viewData['installment_tbank']; 
    $currency = $viewData['currency'];
    $cart_total = $viewData["cart_total"];

    $min_amount_installment_kbank = null;
    $max_amount_installment_kbank = null;

    $has_merchant_route_installment_kbank = $viewData['has_merchant_route_installment_kbank'];
    $has_merchant_fee_installment_kbank = $viewData['has_merchant_fee_installment_kbank'];
    $has_merchant_service_fee_installment_kbank = $viewData['has_merchant_service_fee_installment_kbank']; 
    if($has_merchant_route_installment_kbank > 0){
        $min_amount_installment_kbank = number_format((float)$viewData["min_amount_installment_kbank"], 2, '.', '');
        $max_amount_installment_kbank = number_format((float)$viewData["max_amount_installment_kbank"], 2, '.', '');
    }

    $min_amount_installment_ktc_flexi = null;
    $max_amount_installment_ktc_flexi = null;

    $has_merchant_route_installment_ktc_flexi = $viewData['has_merchant_route_installment_ktc_flexi'];
    $has_merchant_fee_installment_ktc_flexi = $viewData['has_merchant_fee_installment_ktc_flexi'];
    $has_merchant_service_fee_installment_ktc_flexi = $viewData['has_merchant_service_fee_installment_ktc_flexi'];
    if($has_merchant_route_installment_ktc_flexi > 0){
        $min_amount_installment_ktc_flexi = number_format((float)$viewData["min_amount_installment_ktc_flexi"], 2, '.', '');
        $max_amount_installment_ktc_flexi = number_format((float)$viewData["max_amount_installment_ktc_flexi"], 2, '.', '');
    }

?>
<fieldset id="chillpay-form-installment">
    <ul style="list-style:inherit;margin-bottom: 0px;">
        <?php if ( $currency == 'THB') { ?>
            <!-- KBANK -->
            <?php if ( $kbankCards ) : true ?>
                <?php if ( $has_merchant_route_installment_kbank > 0  && $has_merchant_fee_installment_kbank > 0 && $has_merchant_service_fee_installment_kbank > 0) { ?>
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
                            <option value="0">โปรดเลือกจำนวนเดือน</option>
                            <?php foreach ( $viewData['installment_kbank_data'] as $installment_plan ) : ?>
                                <?php foreach ( $installment_plan as $data ) : ?>
                                    <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                    <?php
                                    echo sprintf(
                                        __( '%d เดือน', 'chillpay', 'chillpay_installment_term_option' ),
                                        $data['term_length']
                                    );
                                    ?>

                                    <?php
                                    echo sprintf(
                                        __( '( %s / เดือน )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                        wc_price( $data['monthly_amount'] )
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
                                <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('ยอดรวมขั้นต่ำที่สามารถผ่อนได้ '.number_format((float)$min_amount_installment_kbank,2).' THB', 'chillpay'); ?>
                        </span>
                    <?php } elseif ($max_amount_installment_kbank < 0) { ?>   
                        <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" disabled />
                        <label for="installment_kbank">
                            <div class="chillpay-form-installment-logo-box kbank">
                                <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('โปรดติดต่อร้านค้าเพื่อแจ้งปัญหาการชำระเงินหรือติดต่อฝ่ายบริการลูกค้า', 'chillpay'); ?>
                        </span>
                    <?php } ?>
                    </li>
                <?php } else { ?>
                    <li class="item"> 
                        <input id="installment_kbank" type="radio" name="chillpay-offsite" value="installment_kbank" disabled />
                        <label for="installment_kbank">
                            <div class="chillpay-form-installment-logo-box kbank">
                                <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KBANK2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title"><?php _e( 'Kasikorn Bank', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('โปรดติดต่อร้านค้าเพื่อแจ้งปัญหาการชำระเงินหรือติดต่อฝ่ายบริการลูกค้า', 'chillpay'); ?>
                        </span>
                    </li>
                <?php } ?>                                      
            <?php endif; ?>

            <!--KTC FIexi-->
            <?php if ( $ktcCards ) : true ?>
                <?php if ( $has_merchant_route_installment_ktc_flexi > 0 && $has_merchant_fee_installment_ktc_flexi > 0 && $has_merchant_service_fee_installment_ktc_flexi > 0) { ?>
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
                                <option value="0">โปรดเลือกจำนวนเดือน</option>
                                <?php foreach ( $viewData['installment_ktc_flexi_data'] as $installment_plan ) : ?>
                                    <?php foreach ( $installment_plan as $data ) : ?>
                                        <option value="<?php echo $data['term_length']; ?>" data-interest="<?php echo $data['interest_rate']; ?>" >
                                        <?php
                                        echo sprintf(
                                            __( '%d เดือน', 'chillpay', 'chillpay_installment_term_option' ),
                                            $data['term_length']
                                        );
                                        ?>

                                        <?php
                                        echo sprintf(
                                            __( '( %s / เดือน )', 'chillpay', 'chillpay_installment_payment_per_month' ),
                                            wc_price( $data['monthly_amount'] )
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
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('ยอดรวมขั้นต่ำที่สามารถผ่อนได้ '.number_format((float)$min_amount_installment_ktc_flexi,2).' THB', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($cart_total > $max_amount_installment_ktc_flexi && $max_amount_installment_ktc_flexi >= 0) { ?>
                            <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                            <label for="installment_ktc_flexi">
                                <div class="chillpay-form-installment-logo-box ktc">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('ยอดรวมที่สามารถผ่อนได้สูงสุด '.number_format((float)$max_amount_installment_ktc_flexi,2).' THB', 'chillpay'); ?>
                            </span>
                        <?php } elseif ($max_amount_installment_ktc_flexi < 0) { ?>
                            <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                            <label for="installment_ktc_flexi">
                                <div class="chillpay-form-installment-logo-box ktc">
                                    <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                                </div>
                                <div class="chillpay-form-installment-label-box" style="width:100%;">
                                    <span class="title"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                                </div>
                            </label>
                            <span class="secondary-text">
                                <?php echo __('โปรดติดต่อร้านค้าเพื่อแจ้งปัญหาการชำระเงินหรือติดต่อฝ่ายบริการลูกค้า', 'chillpay'); ?>
                            </span>
                        <?php } ?>        
                    </li>
                <?php } else { ?>
                    <li class="item">
                        <input id="installment_ktc_flexi" type="radio" name="chillpay-offsite" value="installment_ktc_flexi" disabled />
                        <label for="installment_ktc_flexi">
                            <div class="chillpay-form-installment-logo-box ktc">
                                <img src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTC-Flexi2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                            </div>
                            <div class="chillpay-form-installment-label-box" style="width:100%;">
                                <span class="title"><?php _e( 'KTC-Flexi', 'chillpay' ); ?></span><br/>
                            </div>
                        </label>
                        <span class="secondary-text">
                            <?php echo __('โปรดติดต่อร้านค้าเพื่อแจ้งปัญหาการชำระเงินหรือติดต่อฝ่ายบริการลูกค้า', 'chillpay'); ?>
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
                    document.getElementById('interest_kbank').innerHTML = '( ดอกเบี้ย '+ interest_rate +'% )';
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
                    document.getElementById('interest_kbank_ktc_flexi').innerHTML = '( ดอกเบี้ย '+ interest_rate +'% )';
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

        $("input[name=payment_method]").change(function(){
            $("input[name=chillpay-offsite]").prop("checked", false);
            var id = $(this)[0].id;
            var closest = $(this).closest("." + id);
            var length = closest.find("input[name=chillpay-offsite]").length;
            if(length == 1) {
                $(closest.find("input[name=chillpay-offsite]")[0]).trigger("click")
            }
        });      

    }(jQuery));
</script>
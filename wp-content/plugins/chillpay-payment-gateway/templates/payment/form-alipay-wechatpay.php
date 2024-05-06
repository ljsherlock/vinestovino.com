<?php 
	$alipayCards = $viewData['alipay']; 
    $wechatpayCards = $viewData['wechatpay']; 
    $currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-alipay-wechatpay">
    <ul style="list-style:inherit">
        <?php if ( $currency == 'THB') { ?>	
            <!-- Alipay -->
            <?php if ( $alipayCards ) : true ?>
                <li class="item">
                    <input id="epayment_alipay" type="radio" name="chillpay-offsite" value="epayment_alipay" />
                    <label for="epayment_alipay">
                        <div class="chillpay-form-alipay-wechatpay-logo-box alipay">
                            <img src="<?php echo plugins_url( '../../assets/images/icon-alipay-wechat/Icon_Alipay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-alipay-wechatpay-label-box" style="width:100%;">
                            <span class="title"><?php _e( 'Alipay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_alipay'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_alipay" >
                                    Customer may be charged for fees
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_alipay">
                                    <?php echo $viewData['fee_alipay'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </label>
                </li>
            <?php endif; ?>

            <!-- WeChat Pay -->
            <?php if ( $wechatpayCards ) : true ?>
                <li class="item">
                    <input id="epayment_wechatpay" type="radio" name="chillpay-offsite" value="epayment_wechatpay" />
                    <label for="epayment_wechatpay">
                        <div class="chillpay-form-alipay-wechatpay-logo-box wechatpay">
                            <img src="<?php echo plugins_url( '../../assets/images/icon-alipay-wechat/Icon_WeChatPay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-alipay-wechatpay-label-box" style="width:100%;">
                            <span class="title"><?php _e( 'WeChat Pay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_wechatpay'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_wechatpay" >
                                    Customer may be charged for fees
                                </span>
                            <?php else : ?>
                                <span class="rate secondary-text" id="rate_wechatpay">
                                    <?php echo $viewData['fee_wechatpay'] ?>
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
		let channel = "alipay_wechatpay";
		let radio_id = $("#payment_method_chillpay_" + channel);
		let fieldset_id = $("#chillpay-form-" + channel)

		if(radio_id.is(":checked")) {
			let length = $("[name=chillpay-offsite]:checked").length
			if(length <= 0) {
				let item = fieldset_id.find("li.item");
				if(item.length == 1) {
					$(item[0].children[0]).trigger('click')
				}
			}
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
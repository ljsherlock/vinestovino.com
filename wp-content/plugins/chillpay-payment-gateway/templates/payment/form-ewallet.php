<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

	$rabbitCards = $viewData['rabbit']; 
	$trueCards = $viewData['true'];
	$alipayCards = $viewData['alipay']; 
    $wechatpayCards = $viewData['wechatpay']; 
	$shopeepayCards = $viewData['shopeepay'];
	$currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-ewallet">
    <ul style="list-style:inherit">
    	<?php if ( $currency == 'THB') { ?>	
			<!-- Rabbit LINE Pay -->
			<?php if ( $rabbitCards ) : true ?>		
				<li class="item">
					<input id="epayment_linepay" type="radio" name="chillpay-offsite" value="epayment_linepay" <?php if($viewData['fee_rabbit'] == -1) { echo 'disabled'; } ?>/>
					<label for="epayment_linepay">
						<div class="chillpay-form-ewallet-logo-box">
							<img class="<?php if($viewData['fee_rabbit'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-line-pay/IconLinePay_RabbitLinePay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
						</div>
						<div class="chillpay-form-ewallet-label-box" style="width:100%;">
							<span class="title <?php if($viewData['fee_rabbit'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Rabbit LINE Pay', 'chillpay' ); ?></span><br/>
							<?php if ( $viewData['fee_rabbit'] == -1 ) : ?>
								<span class="rate secondary-text" id="rate_linepay" >
									Channel not available
								</span>
							<?php else : ?>
								<span class="rate secondary-text" id="rate_linepay">
									<?php echo $viewData['fee_rabbit'] ?>
								</span>
							<?php endif; ?>	
						</div>
					</label>
				</li>
			<?php endif; ?>

			<!-- TrueMoney Wallet -->
			<?php if ( $trueCards ) : true ?>
				<li class="item">
					<input id="epayment_truemoney" type="radio" name="chillpay-offsite" value="epayment_truemoney" <?php if($viewData['fee_true'] == -1) { echo 'disabled'; } ?> />
					<label for="epayment_truemoney">
						<div class="chillpay-form-ewallet-logo-box">
							<img class="<?php if($viewData['fee_true'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-epayment/IconEPayment_TrueMoney.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
						</div>
						<div class="chillpay-form-ewallet-label-box" style="width:100%;">
							<span class="title <?php if($viewData['fee_true'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'TrueMoney Wallet', 'chillpay' ); ?></span><br/>
							<?php if ( $viewData['fee_true'] == -1 ) : ?>
								<span class="rate secondary-text" id="rate_true" >
									Channel not available
								</span>
							<?php else : ?>
								<span class="rate secondary-text" id="rate_true">
									<?php echo $viewData['fee_true'] ?>
								</span>
							<?php endif; ?>
						</div>
					</label>
				</li>
			<?php endif; ?>

			<!-- Alipay -->
            <?php if ( $alipayCards ) : true ?>
                <li class="item">
                    <input id="epayment_alipay" type="radio" name="chillpay-offsite" value="epayment_alipay" <?php if($viewData['fee_alipay'] == -1) { echo 'disabled'; } ?> />
                    <label for="epayment_alipay">
                        <div class="chillpay-form-ewallet-logo-box alipay">
                            <img class="<?php if($viewData['fee_alipay'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-alipay-wechat/Icon_Alipay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-ewallet-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_alipay'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Alipay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_alipay'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_alipay" >
									Channel not available
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
                    <input id="epayment_wechatpay" type="radio" name="chillpay-offsite" value="epayment_wechatpay" <?php if($viewData['fee_wechatpay'] == -1) { echo 'disabled'; } ?> />
                    <label for="epayment_wechatpay">
                        <div class="chillpay-form-ewallet-logo-box wechatpay">
                            <img class="<?php if($viewData['fee_wechatpay'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-alipay-wechat/Icon_WeChatPay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
                        </div>
                        <div class="chillpay-form-ewallet-label-box" style="width:100%;">
                            <span class="title <?php if($viewData['fee_wechatpay'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'WeChat Pay', 'chillpay' ); ?></span><br/>
                            <?php if ( $viewData['fee_wechatpay'] == -1 ) : ?>
                                <span class="rate secondary-text" id="rate_wechatpay" >
									Channel not available
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

			<!-- ShopeePay -->
			<?php if ( $shopeepayCards ) : true ?>
				<li class="item">
					<input id="epayment_shopeepay" type="radio" name="chillpay-offsite" value="epayment_shopeepay" <?php if($viewData['fee_shopeepay'] == -1) { echo 'disabled'; } ?> />
					<label for="epayment_shopeepay">
						<div class="chillpay-form-ewallet-logo-box">
							<img class="<?php if($viewData['fee_shopeepay'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-epayment/Icon_ShopeePay.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
						</div>
						<div class="chillpay-form-ewallet-label-box" style="width:100%;">
							<span class="title <?php if($viewData['fee_shopeepay'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'ShopeePay', 'chillpay' ); ?></span><br/>
							<?php if ( $viewData['fee_shopeepay'] == -1 ) : ?>
								<span class="rate secondary-text" id="rate_shopeepay" >
									Channel not available
								</span>
							<?php else : ?>
								<span class="rate secondary-text" id="rate_shopeepay">
									<?php echo $viewData['fee_shopeepay'] ?>
								</span>
							<?php endif; ?>
						</div>
					</label>
				</li>				
			<?php endif ?>

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
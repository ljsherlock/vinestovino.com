<?php 
	require_once dirname( __FILE__ ) . '/main-script.php';

	$scbCards = $viewData['scb']; 
	$ktbCards = $viewData['ktb']; 
	$bayCards = $viewData['bay']; 
	$bblCards = $viewData['bbl'];
	$tbankCards = $viewData['tbank'];
	//$tmbCards = $viewData['tmb'];
	$currency = $viewData['currency'];
?>
<fieldset id="chillpay-form-internetbanking">
	<ul style="list-style:inherit">
		<?php if ( $currency == 'THB') { ?>			
			<!-- SCB -->
			<?php if ( $scbCards ) : true ?>
			<li class="item">
				<input id="internetbank_scb" type="radio" name="chillpay-offsite" value="internetbank_scb" <?php if($viewData['fee_scb'] == -1) { echo 'disabled'; } ?> } />
				<label for="internetbank_scb">
					<div class="chillpay-form-internetbanking-logo-box scb">
						<img class="<?php if($viewData['fee_scb'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_SCB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
					</div>
					<div class="chillpay-form-internetbanking-label-box" style="width:100%;">
						<span class="title <?php if($viewData['fee_scb'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Siam Commercial Bank', 'chillpay' ); ?></span><br/>
						<?php if ( $viewData['fee_scb'] == -1 ) : ?>
							<span class="rate secondary-text" id="rate_scb">
								Channel not available
							</span>
						<?php else : ?>
							<span class="rate secondary-text" id="rate_scb">
								<?php echo $viewData['fee_scb'] ?>
							</span>
						<?php endif; ?>
					</div>
				</label>
			</li>
			<?php endif; ?>

			<!-- KTB -->
			<?php if ( $ktbCards ) : true ?>
			<li class="item">
				<input id="internetbank_ktb" type="radio" name="chillpay-offsite" value="internetbank_ktb" <?php if($viewData['fee_ktb'] == -1) { echo 'disabled'; } ?> />
				<label for="internetbank_ktb">
					<div class="chillpay-form-internetbanking-logo-box ktb">
						<img class="<?php if($viewData['fee_ktb'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_KTB2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
					</div>
					<div class="chillpay-form-internetbanking-label-box" style="width:100%;">
						<span class="title <?php if($viewData['fee_ktb'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Krungthai Bank', 'chillpay' ); ?></span><br/>				
						<?php if ( $viewData['fee_ktb'] == -1 ) : ?>
							<span class="rate secondary-text" id="rate_ktb" >
							Channel not available
							</span>
						<?php else : ?>
							<span class="rate secondary-text" id="rate_ktb">
								<?php echo $viewData['fee_ktb'] ?>
							</span>
						<?php endif; ?>
					</div>
				</label>
			</li>
			<?php endif; ?>

			<!-- BAY -->
			<?php if ( $bayCards ) : true ?>
			<li class="item">
				<input id="internetbank_bay" type="radio" name="chillpay-offsite" value="internetbank_bay" <?php if($viewData['fee_bay'] == -1) { echo 'disabled'; } ?> />
				<label for="internetbank_bay">
					<div class="chillpay-form-internetbanking-logo-box bay">
						<img class="<?php if($viewData['fee_bay'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BAY2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
					</div>
					<div class="chillpay-form-internetbanking-label-box" style="width:100%;">
						<span class="title <?php if($viewData['fee_bay'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Krungsri Bank', 'chillpay' ); ?></span><br/>
						<?php if ( $viewData['fee_bay'] == -1 ) : ?>
							<span class="rate secondary-text" id="rate_bay" >
							Channel not available
							</span>
						<?php else : ?>
							<span class="rate secondary-text" id="rate_bay">
								<?php echo $viewData['fee_bay'] ?>
							</span>
						<?php endif; ?>
					</div>
				</label>
			</li>
			<?php endif; ?>

			<!-- BBL -->
			<?php if ( $bblCards ) : true ?>
			<li class="item">
				<input id="internetbank_bbl" type="radio" name="chillpay-offsite" value="internetbank_bbl" <?php if($viewData['fee_bbl'] == -1) { echo 'disabled'; } ?> />
				<label for="internetbank_bbl">
					<div class="chillpay-form-internetbanking-logo-box bbl">
						<img class="<?php if($viewData['fee_bbl'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_BBL2.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
					</div>
					<div class="chillpay-form-internetbanking-label-box" style="width:100%;">
						<span class="title <?php if($viewData['fee_bbl'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'Bangkok Bank', 'chillpay' ); ?></span><br/>
						<?php if ( $viewData['fee_bbl'] == -1 ) : ?>
							<span class="rate secondary-text" id="rate_bbl" >
							Channel not available
							</span>
						<?php else : ?>
							<span class="rate secondary-text" id="rate_bbl">
								<?php echo $viewData['fee_bbl'] ?>
							</span>
						<?php endif; ?>
					</div>
				</label>
			</li>
			<?php endif; ?>

			<!-- TTB -->
			<?php if ( $tbankCards ) : true ?>
			<li class="item">
				<input id="internetbank_ttb" type="radio" name="chillpay-offsite" value="internetbank_ttb" <?php if($viewData['fee_ttb'] == -1) { echo 'disabled'; } ?> />
				<label for="internetbank_ttb">
					<div class="chillpay-form-internetbanking-logo-box">
						<img class="<?php if($viewData['fee_ttb'] == -1) { echo 'opacity-1'; } ?>" src="<?php echo plugins_url( '../../assets/images/icon-bank/IconBank_TTB.png', __FILE__ ); ?>" style="max-height:100%;width:38px;height:100%;"/>
					</div>
					<div class="chillpay-form-internetbanking-label-box" style="width:100%;">
						<span class="title <?php if($viewData['fee_ttb'] == -1) { echo 'opacity-1'; } ?>"><?php _e( 'TMB Thanachart Bank', 'chillpay' ); ?></span><br/>
						<?php if ( $viewData['fee_ttb'] == -1 ) : ?>
							<span class="rate secondary-text" id="rate_ttb" >
								Channel not available
							</span>
						<?php else : ?>
							<span class="rate secondary-text" id="rate_ttb">
								<?php echo $viewData['fee_ttb'] ?>
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
<div>
    <style>
		.chillpay-notice-sandbox-mode {
			background: #ffce00;
			color: #575D66;
			border: 1px solid #efc200;
			border-left-width: 4px;
            margin-left: 0;
		}
	</style>

	<h1><?php echo $title; ?></h1>

    <?php if ( 'yes' === $settings['sandbox'] ) : ?>
		<div class="notice chillpay-notice-sandbox-mode">
			<p><?php echo _e( 'You are in Sandbox mode. No actual payment is made in this mode', 'chillpay' ); ?></p>
		</div>
	<?php endif; ?>

	<h2><?php echo _e( 'Payment Settings', 'chillpay' ); ?></h2>

	<hr />

	<form method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="setting_order"><?php _e( 'Setting New Order', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<label for="setting_order" style="color:#00a0d2">
								<input name="setting_order" type="checkbox" id="setting_order" value="1" <?php echo 'yes' === $settings['setting_order'] ? 'checked="checked"' : ''; ?>>
								<?php _e( 'Enable to create new order if order status is failed.', 'chillpay' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th colspan="2" scope="row">
						<hr style="margin:0;" />
					</th>
				</tr>
				<tr class="">
					<th scope="row"><label for="sandbox"><?php _e( 'Sandbox Mode', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<label for="sandbox" style="color:#00a0d2">
								<input name="sandbox" type="checkbox" id="sandbox" value="1" <?php echo 'yes' === $settings['sandbox'] ? 'checked="checked"' : ''; ?>>
								<?php _e( 'Enable to using Sandbox mode.', 'chillpay' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="test_merchant_code"><?php _e( 'Merchant Code for Test', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="test_merchant_code" type="text" id="test_merchant_code" value="<?php echo $settings['test_merchant_code']; ?>" maxlength="20" class="regular-text" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="test_api_key"><?php _e( 'Api key for Test', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="test_api_key" type="text" id="test_api_key" value="<?php echo $settings['test_api_key']; ?>" maxlength="100" class="regular-text" style="width: 95%;" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="test_md5_secret_key"><?php _e( 'MD5 Secret Key for Test', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="test_md5_secret_key" type="text" id="test_md5_secret_key" value="<?php echo $settings['test_md5_secret_key']; ?>" maxlength="255" class="regular-text" style="width: 95%;" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="test_route_no"><?php _e( 'Route No. for Test', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="test_route_no" type="text" id="test_route_no" value="<?php echo $settings['test_route_no']; ?>" maxlength="2" class="regular-text" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th colspan="2" scope="row">
						<hr style="margin:0;" />
					</th>
				</tr>
				<tr>
					<th scope="row"><label for="live_merchant_code"><?php _e( 'Merchant Code for Live', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="live_merchant_code" type="text" id="live_merchant_code" value="<?php echo $settings['live_merchant_code']; ?>" maxlength="20" class="regular-text" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="live_api_key"><?php _e( 'Api key for Live', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="live_api_key" type="text" id="live_api_key" value="<?php echo $settings['live_api_key']; ?>" maxlength="100" class="regular-text" style="width: 95%;" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="live_md5_secret_key"><?php _e( 'MD5 Secret Key for Live', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="live_md5_secret_key" type="text" id="live_md5_secret_key" value="<?php echo $settings['live_md5_secret_key']; ?>" maxlength="255" class="regular-text" style="width: 95%;" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="live_route_no"><?php _e( 'Route No. for Live', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<input name="live_route_no" type="text" id="live_route_no" value="<?php echo $settings['live_route_no']; ?>" maxlength="2" class="regular-text" />
						</fieldset>
					</td>
				</tr>
				<!-- <tr>
					<th scope="row"><label for="live_lang_code"><php _e( 'Language Code', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<select name="live_lang_code" id="live_lang_code" style="width:auto;">
							<php foreach ($language_codes as $value) :?>
								<option value="<php echo $value ?>"<php if( $value === $settings['live_lang_code'] ): ?> selected="selected"<php endif; ?>><php echo $value ?></option>
							<php endforeach;?>
							</select>
						</fieldset>
					</td>
				</tr> -->
			</tbody>
		</table>

		<hr />

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label><?php _e( 'URL Background', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<code><?php echo get_site_url() . '/?wc-api=chillpay_callback'; ?></code>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label><?php _e( 'URL Result', 'chillpay' ); ?></label></th>
					<td>
						<fieldset>
							<code><?php echo get_site_url() . '/?wc-api=chillpay_result'; ?></code>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<hr />

		<h3><?php _e( 'Payment Methods', 'boonyisa' ); ?></h3>
		<p><?php _e( 'The table below is a list of available payment methods that you can enable in your WooCommerce store.', 'boonyisa' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="sandbox"><?php _e( 'Available Payment Methods', 'boonyisa' ); ?></label></th>
					<td>
						<table class="widefat fixed striped" cellspacing="0">
							<thead>
								<tr>
									<?php
										foreach ( $columns as $key => $column ) {
											switch ( $key ) {
												case 'status' :
												case 'setting' :
													echo '<th style="text-align: center; padding: 10px;" class="' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
													break;

												default:
													echo '<th style="padding: 10px;" class="' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
													break;
											}
										}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ( $available_gateways as $gateway ) :

										echo '<tr>';

										foreach ( $columns as $key => $column ) :
											switch ( $key ) {
												case 'name' :
													$method_title = $gateway->get_title() ? $gateway->get_title() : __( '(no title)', 'chillpay' );
													echo '<td class="name">
														<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) . '">' . esc_html( $method_title ) . '</a>
													</td>';
													break;

												case 'channel' :
													echo '<td class="channel">';
													echo '<span>' .$gateway->channel. '</span>';
													echo '</td>';
													break;

												/*case 'background' :
													echo '<td class="url">';
													echo '<span style="word-break: break-all;">' . strtolower( $gateway->callback_url ). '</span>';
													echo '</td>';
												break;

												case 'result' :
													echo '<td class="url">';
													echo '<span style="word-break: break-all;">' . strtolower( $gateway->result_url ). '</span>';
													echo '</td>';
													break;*/

												case 'status' :
													echo '<td class="status" style="text-align: center;">';
													echo ( 'yes' === $gateway->enabled ) ? '<span>' . __( 'Yes', 'chillpay' ) . '</span>' : '-';
													echo '</td>';
													break;

												case 'setting' :
													echo '<td class="setting" style="text-align: center;">
														<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) . '">' . __( 'Setting', 'chillpay' ) . '</a>
													</td>';
													break;
											}
										endforeach;

										echo '</tr>';

									endforeach;
								?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button( __( 'Save Settings', 'chillpay' ) ); ?>
	</form>

</div>
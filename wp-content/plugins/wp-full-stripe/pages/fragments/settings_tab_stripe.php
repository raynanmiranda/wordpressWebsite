<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.04.13.
 * Time: 15:26
 */

$options    = get_option( 'fullstripe_options' );

?>
<div id="stripe-tab">
	<p class="alert alert-info"><?php _e( 'The Stripe API keys are required for payments to work. You can find your keys on your <a href="https://manage.stripe.com">Stripe Dashboard</a> -> Account Settings -> API Keys tab', 'wp-full-stripe' ); ?></p>

	<form class="form-horizontal" action="#" method="post" id="settings-stripe-form">
		<p class="tips"></p>
		<input type="hidden" name="action" value="wp_full_stripe_update_settings"/>
		<input type="hidden" name="tab" value="stripe">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php _e( "API mode: ", 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio">
						<input type="radio" name="apiMode" id="modeTest" value="test" <?php echo ( $options['apiMode'] == 'test' ) ? 'checked' : '' ?> >
						Test
					</label> <label class="radio">
						<input type="radio" name="apiMode" id="modeLive" value="live" <?php echo ( $options['apiMode'] == 'live' ) ? 'checked' : '' ?>>
						Live
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="currency"><?php _e( "Payment Currency: ", 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<div class="ui-widget">
						<select id="currency" name="currency">
							<option value=""><?php esc_attr_e( 'Select from the list or start typing', 'wp-full-stripe' ); ?></option>
							<?php
							foreach ( MM_WPFS::get_available_currencies() as $currency_key => $currency_obj ) {
								$option = '<option value="' . $currency_key . '"';
								if ( $options['currency'] === $currency_key ) {
									$option .= 'selected="selected"';
								}
								$option .= '>';
								$option .= $currency_obj['name'] . ' (' . $currency_obj['code'] . ')';
								$option .= '</option>';
								echo $option;
							}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="secretKey_test"><?php _e( "Stripe Test Secret Key: ", 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" name="secretKey_test" id="secretKey_test" value="<?php echo $options['secretKey_test']; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="publishKey_test"><?php _e( "Stripe Test Publishable Key: ", 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input type="text" id="publishKey_test" name="publishKey_test" value="<?php echo $options['publishKey_test']; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="secretKey_live"><?php _e( "Stripe Live Secret Key: ", 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" name="secretKey_live" id="secretKey_live" value="<?php echo $options['secretKey_live']; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="publishKey_live"><?php _e( "Stripe Live Publishable Key: ", 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input type="text" id="publishKey_live" name="publishKey_live" value="<?php echo $options['publishKey_live']; ?>" class="regular-text code">
				</td>
			</tr>
		</table>
		<hr>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php _e( 'Stripe Webhook URL: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input id="stripe-webhook-url" class="large-text" type="text" value="<?php echo esc_attr( add_query_arg( array( 'action'     => 'handle_wpfs_event',
					                                                                                                                'auth_token' => MM_WPFS_Admin::get_webhook_token()
					), admin_url( 'admin-post.php' ) ) ); ?>" readonly>
					<p class="description"><?php printf( __( 'This URL must be set in Stripe as a webhook endpoint. See the <a target="_blank" href="%s">"Setup" chapter</a> of the "Help" page for more information.', 'wp-full-stripe' ), admin_url( "admin.php?page=fullstripe-help#" ) ); ?>
					</p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes' ) ?></button>
			<img src="<?php echo plugins_url( '../img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
		</p>
	</form>
</div>

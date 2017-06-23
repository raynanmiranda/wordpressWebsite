<?php

/** @var stdClass $formData */
/** @var array $plans */

$options        = get_option( 'fullstripe_options' );
$currencySymbol = MM_WPFS::get_currency_symbol_for( $options['currency'] );
$lockEmail      = $options['lock_email_field_for_logged_in_users'];

$emailAddress   = "";
$isUserLoggedIn = is_user_logged_in();
if ( $lockEmail == '1' && $isUserLoggedIn ) {
	$current_user = wp_get_current_user();
	$emailAddress = $current_user->user_email;
}

$form_id = MM_WPFS::esc_html_id_attr( $formData->name );

$plan_details_id = 'fullstripe-plan-details__' . $form_id;

$wpfs_form_count = 0;
if ( array_key_exists( 'wpfs_form_count', $_REQUEST ) ) {
	$wpfs_form_count = $_REQUEST['wpfs_form_count'];
}
$wpfs_form_count             = $wpfs_form_count + 1;
$_REQUEST['wpfs_form_count'] = $wpfs_form_count;

$show_loading_id               = 'show-loading__' . $form_id;
$show_loading_coupon_id        = 'show-loading-coupon__' . $form_id;
$payment_form_submit_id        = 'payment-form-submit__' . $form_id;
$payment_form_coupon_submit_id = 'fullstripe-check-coupon-code__' . $form_id;
$coupon_input_id               = 'fullstripe-coupon-input__' . $form_id;
$setup_fee_input_id            = 'fullstripe-setup-fee__' . $form_id;
$plan_input_id                 = 'fullstripe-plan__' . $form_id;
$address_country_input_id      = 'fullstripe-address-country__' . $form_id;
$payment_errors_class          = 'payment-errors__' . $form_id;
$form_attributes               = 'class="form-horizontal payment-form subscription-form"';
if ( $wpfs_form_count == 1 ) {
	$form_attributes .= ' ';
	$form_attributes .= 'id="payment-form"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
} else {
	$form_attributes .= ' ';
	$form_attributes .= 'id="subscription-form__' . $form_id . '"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
}

?>
<form action="" method="POST" <?php echo $form_attributes; ?>>
	<fieldset>
		<div id="legend__<?php echo $form_id; ?>">
            <span class="fullstripe-form-title">
                <?php MM_WPFS::echo_translated_label( $formData->formTitle ); ?>
            </span>
		</div>
		<input type="hidden" name="action" value="wp_full_stripe_subscription_charge"/>
		<input type="hidden" name="formId" value="<?php echo $formData->subscriptionFormID; ?>"/>
		<input type="hidden" name="formName" value="<?php echo $formData->name; ?>"/>
		<input type="hidden" name="formDoRedirect" value="<?php echo $formData->redirectOnSuccess; ?>"/>
		<input type="hidden" name="formRedirectPostID" value="<?php echo $formData->redirectPostID; ?>"/>
		<input type="hidden" name="formRedirectUrl" value="<?php echo $formData->redirectUrl; ?>"/>
		<input type="hidden" name="formRedirectToPageOrPost" value="<?php echo $formData->redirectToPageOrPost; ?>"/>
		<input type="hidden" name="sendEmailReceipt" value="<?php echo $formData->sendEmailReceipt; ?>"/>
		<input type="hidden" name="showAddress" value="<?php echo $formData->showAddress; ?>"/>
		<input type="hidden" name="fullstripe_setupFee" id="<?php echo $setup_fee_input_id; ?>" value="<?php echo $formData->setupFee; ?>"/>
		<?php if ( $formData->showCustomInput == 1 && $formData->customInputs ): ?>
			<input type="hidden" name="customInputs" value="<?php echo $formData->customInputs; ?>"/>
		<?php endif; ?>
		<p class="<?php echo $payment_errors_class;?>"></p>
		<!-- Name -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Holder\'s Name', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" autocomplete="off" placeholder="Name" class="input-xlarge fullstripe-form-input" name="fullstripe_name" id="fullstripe_name__<?php echo $form_id; ?>" data-stripe="name">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Email Address', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<?php if ( $lockEmail == '1' && $isUserLoggedIn ): ?>
					<label class="fullstripe-data-label"><?php echo $emailAddress; ?></label>
					<input type="hidden" value="<?php echo $emailAddress; ?>" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
				<?php else: ?>
					<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $formData->showCustomInput == 1 ): ?>
			<?php
			$customInputs = array();
			if ( $formData->customInputs != null ) {
				$customInputs = explode( '{{', $formData->customInputs );
			}
			?>
			<?php if ( $formData->customInputs == null ): ?>
				<div class="control-group">
					<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $formData->customInputTitle ); ?></label>

					<div class="controls">
						<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_custom_input" id="fullstripe-custom-input__<?php echo $form_id; ?>">
					</div>
				</div>
			<?php endif; ?>
			<?php foreach ( $customInputs as $i => $label ): ?>
				<div class="control-group">
					<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $label ); ?></label>

					<div class="controls">
						<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_custom_input[]" id="fullstripe-custom-input__<?php echo $form_id . '__' . ( $i + 1 ); ?>">
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Subscription Plan', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<select id="<?php echo $plan_input_id; ?>" name="fullstripe_plan" class="fullstripe-plan fullstripe-form-input" data-form-id="<?php echo $form_id; ?>">
					<?php foreach ( $plans as $plan ): ?>
						<option value="<?php echo esc_attr( $plan->id ); ?>"
						        data-value="<?php echo esc_attr( $plan->id ); ?>"
						        data-amount="<?php echo esc_attr( $plan->amount ); ?>"
						        data-interval="<?php echo esc_attr( MM_WPFS::get_translated_interval_label( $plan->interval, $plan->interval_count ) ); ?>"
						        data-interval-count="<?php echo esc_attr( $plan->interval_count ); ?>"
						        data-currency="<?php echo esc_attr( $currencySymbol ); ?>">
							<?php echo esc_html( MM_WPFS::translate_label( $plan->name ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php if ( $formData->showAddress == 1 ): ?>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Street', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_line1" id="fullstripe_address_line1__<?php echo $form_id; ?>" class="fullstripe-form-input"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Line 2', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_line2" id="fullstripe_address_line2__<?php echo $form_id; ?>" class="fullstripe-form-input"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'City', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_city" id="fullstripe_address_city__<?php echo $form_id; ?>" class="fullstripe-form-input"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Zip', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" style="width: 60px;" name="fullstripe_address_zip" id="fullstripe_address_zip__<?php echo $form_id; ?>" class="fullstripe-form-input"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'State', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" style="width: 60px;" name="fullstripe_address_state" id="fullstripe_address_state__<?php echo $form_id; ?>" class="fullstripe-form-input"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Country', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<select name="fullstripe_address_country" id="<?php echo $address_country_input_id; ?>" class="fullstripe-form-input">
						<option value=""><?php echo esc_html( __( 'Select country', 'wp-full-stripe' ) ); ?></option>
						<?php
						foreach ( MM_WPFS::get_available_countries() as $country_key => $country_obj ) {
							$option = '<option value="' . $country_key . '"';
							$option .= '>';
							$option .= MM_WPFS::translate_label( $country_obj['name'] );
							$option .= '</option>';
							echo $option;
						}
						?>
					</select><br/>
				</div>
			</div>
		<?php endif; ?>
		<!-- Card Number -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Number', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" autocomplete="off" placeholder="4242424242424242" class="input-xlarge fullstripe-form-input" size="20" data-stripe="number">
			</div>
		</div>
		<!-- Expiry-->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Expiry Date', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" style="width: 60px;" size="2" placeholder="10" data-stripe="exp-month" class="fullstripe-form-input"/>
				<span> / </span>
				<input type="text" style="width: 60px;" size="4" placeholder="2016" data-stripe="exp-year" class="fullstripe-form-input"/>
			</div>
		</div>
		<!-- CVV -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card CVV', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="password" autocomplete="off" placeholder="123" class="input-mini fullstripe-form-input" size="4" data-stripe="cvc"/>
			</div>
		</div>
		<?php if ( $formData->showCouponInput == 1 ): ?>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Coupon Code', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" class="input-mini fullstripe-form-input" name="fullstripe_coupon_input" id="<?php echo $coupon_input_id; ?>">
					<button id="<?php echo $payment_form_coupon_submit_id; ?>" class="payment-form-coupon" data-form-id="<?php echo $form_id; ?>"><?php _e( 'Apply', 'wp-full-stripe' ); ?></button>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" id="<?php echo $show_loading_coupon_id; ?>" class="loading-animation"/>
				</div>
			</div>
		<?php endif; ?>
		<!-- Submit -->
		<div class="control-group">
			<div class="controls">
				<button id="<?php echo $payment_form_submit_id; ?>" type="submit"><?php MM_WPFS::echo_translated_label( $formData->buttonTitle ); ?></button>
				<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" id="<?php echo $show_loading_id; ?>" class="loading-animation"/>
				<p id="<?php echo $plan_details_id; ?>"></p>
			</div>
		</div>
	</fieldset>
</form>

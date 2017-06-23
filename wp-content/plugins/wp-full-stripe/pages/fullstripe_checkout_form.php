<?php

/** @var stdClass $formData */

$form_id = MM_WPFS::esc_html_id_attr( $formData['name'] );

$form_attributes = 'class="fullstripe_checkout_form checkout-form"';
$form_attributes .= ' ';
$form_attributes .= 'id="checkout-form__' . $form_id . '"';
$form_attributes .= ' ';
$form_attributes .= 'data-form-id="' . $form_id . '"';

?>
<form action="" method="POST" <?php echo $form_attributes; ?>>
	<input type="hidden" name="action" value="fullstripe_checkout_form_charge"/>
	<input type="hidden" name="companyName" value="<?php echo $formData["companyName"]; ?>"/>
	<input type="hidden" name="productDesc" value="<?php MM_WPFS::echo_translated_label( $formData["productDesc"] ); ?>"/>
	<input type="hidden" name="amount" value="<?php echo $formData["amount"]; ?>"/>
	<input type="hidden" name="buttonTitle" value="<?php MM_WPFS::echo_translated_label( $formData["buttonTitle"] ); ?>"/>
	<input type="hidden" name="sendEmailReceipt" value="<?php echo $formData["sendEmailReceipt"]; ?>"/>
	<input type="hidden" name="showBillingAddress" value="<?php echo $formData["showBillingAddress"]; ?>"/>
	<input type="hidden" name="showRememberMe" value="<?php echo $formData["showRememberMe"]; ?>"/>
	<input type="hidden" name="image" value="<?php echo $formData["image"]; ?>"/>
	<input type="hidden" name="currency" value="<?php echo $formData["currency"]; ?>"/>
	<input type="hidden" name="name" value="<?php echo $formData["name"]; ?>"/>
	<input type="hidden" name="formId" value="<?php echo $formData["checkoutFormID"]; ?>"/>
	<input type="hidden" name="redirectOnSuccess" value="<?php echo $formData["redirectOnSuccess"]; ?>"/>
	<input type="hidden" name="redirectPostID" value="<?php echo $formData["redirectPostID"]; ?>"/>
	<input type="hidden" name="redirectUrl" value="<?php echo $formData["redirectUrl"]; ?>"/>
	<input type="hidden" name="redirectToPageOrPost" value="<?php echo $formData["redirectToPageOrPost"]; ?>"/>
	<input type="hidden" name="useBitcoin" value="<?php echo $formData["useBitcoin"]; ?>">
	<input type="hidden" name="useAlipay" value="<?php echo $formData["useAlipay"]; ?>">
	<p class="payment-errors__<?php echo $form_id; ?>"></p>
	<button class="fullstripe_checkout_button <?php echo ( $formData["disableStyling"] == '0' ) ? 'stripe-button-el' : '' ?> " type="submit">
		<span class="fullstripe_checkout_button_text" <?php echo ( $formData["disableStyling"] == '0' ) ? 'style="display: block; min-height: 30px;"' : '' ?> ><?php MM_WPFS::echo_translated_label( $formData["openButtonTitle"] ); ?></span>
	</button>
	<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="loading-animation" id="show-loading__<?php echo $form_id; ?>" style="display: none;"/>
</form>


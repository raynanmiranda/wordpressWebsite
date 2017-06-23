<?php

/**
 * @var Form
 */
$form = MM_WPFS::getInstance()->get_form_validation_data();

global $wpdb;
//get the data we need
$formID   = - 1;
$formType = "";
if ( isset( $_GET['form'] ) ) {
	$formID = $_GET['form'];
}
if ( isset( $_GET['type'] ) ) {
	$formType = $_GET['type'];
}

$valid = true;
if ( $formID == - 1 || $formType == "" ) {
	$valid = false;
}

$editForm = null;
$plans    = array();

if ( $valid ) {

	if ( $formType == "payment" ) {
		$editForm = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payment_forms WHERE paymentFormID=%d", $formID ) );
	} else if ( $formType == "subscription" ) {
		$editForm = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscription_forms WHERE subscriptionFormID=%d", $formID ) );
		$plans    = MM_WPFS::getInstance()->get_plans();
	} else if ( $formType == "checkout" ) {
		$editForm = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "fullstripe_checkout_forms WHERE checkoutFormID=%d", $formID ) );
	} else {
		$valid = false;
	}

	if ( $editForm == null ) {
		$valid = false;
	}
}

$options        = get_option( 'fullstripe_options' );
$currencySymbol = MM_WPFS::get_currency_symbol_for( $options['currency'] );

?>
<div class="wrap">
	<h2><?php esc_html_e( 'Full Stripe Edit Form', 'wp-full-stripe' ); ?></h2>

	<div id="updateDiv"><p><strong id="updateMessage"></strong></p></div>
	<?php if ( ! $valid ): ?>
		<p>Form not found!</p>
	<?php else: ?>
		<?php if ( $formType == "payment" ): ?>
			<form class="form-horizontal" action="" method="POST" id="edit-payment-form">
				<p class="tips"></p>
				<input type="hidden" name="action" value="wp_full_stripe_edit_payment_form">
				<input type="hidden" name="formID" value="<?php echo $editForm->paymentFormID; ?>">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Name: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_name" id="form_name" value="<?php echo $editForm->name; ?>" maxlength="<?php echo $form::NAME_LENGTH; ?>">

							<p class="description">This name will be used to identify this form in the shortcode i.e.
								[fullstripe_payment form="FormName"]</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Title: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_title" id="form_title" value="<?php echo $editForm->formTitle; ?>" maxlength="<?php echo $form::FORM_TITLE_LENGTH; ?>">

							<p class="description">The title of the form</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Payment Type: </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_custom" id="set_specific_amount" value="specified_amount" <?php echo ( $editForm->customAmount == 'specified_amount' ) ? 'checked' : '' ?>>
								Set Amount
							</label>
							<label class="radio inline">
								<input type="radio" name="form_custom" id="set_amount_list" value="list_of_amounts" <?php echo ( $editForm->customAmount == 'list_of_amounts' ) ? 'checked' : '' ?>>
								Select Amount from List
							</label>
							<label class="radio inline">
								<input type="radio" name="form_custom" id="set_custom_amount" value="custom_amount" <?php echo ( $editForm->customAmount == 'custom_amount' ) ? 'checked' : '' ?>>
								Custom Amount
							</label>

							<p class="description">Choose to set a specific amount or a list of amounts for this
								form, or allow customers to set custom amounts</p>
						</td>
					</tr>
					<tr valign="top" id="payment_amount_row" <?php echo $editForm->customAmount == 'list_of_amounts' ? 'style="display: none;"' : '' ?>>
						<th scope="row">
							<label class="control-label">Payment Amount: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_amount" id="form_amount" value="<?php echo $editForm->amount; ?>">

							<p class="description">The amount this form will charge your customer, in cents. i.e. for
								$10.00 enter 1000</p>
						</td>
					</tr>
					<tr valign="top" id="payment_amount_list_row" <?php echo $editForm->customAmount != 'list_of_amounts' ? 'style="display: none;"' : '' ?>>
						<th scope="row">
							<label class="control-label">Payment Amount Options: </label>
						</th>
						<td>
							<a href="#" class="button button-primary" id="add_payment_amount_button">Add</a><input type="text" id="payment_amount_value" placeholder="Amount" maxlength="6"><input type="text" id="payment_amount_description" placeholder="Description" maxlength="128" class="large-text"><br>

							<ul id="payment_amount_list">
								<?php
								$list_of_amounts = json_decode( $editForm->listOfAmounts );
								if ( isset( $list_of_amounts ) && ! empty( $list_of_amounts ) ) {
									foreach ( $list_of_amounts as $list_element ) {
										$list_item_row = "<li";
										$list_item_row .= " class=\"ui-state-default\"";
										$list_item_row .= " title=\"You can reorder this list by using drag'n'drop.\"";
										$list_item_row .= " data-toggle=\"tooltip\"";
										$list_item_row .= " data-payment-amount-value=\"{$list_element[0]}\"";
										$list_item_row .= " data-payment-amount-description=\"{$list_element[1]}\"";
										$list_item_row .= ">";
										$list_item_row .= "<a href=\"#\" class=\"dd_delete\">Delete</a>";
										$list_item_row .= "<span class=\"amount\">$currencySymbol " . sprintf( '%0.2f', $list_element[0] / 100.0 ) . "</span>";
										$list_item_row .= "<span class=\"desc\">{$list_element[1]}</span>";
										$list_item_row .= "</li>";
										echo $list_item_row;
									}
								}
								?>
							</ul>
							<input type="hidden" name="payment_amount_values">
							<input type="hidden" name="payment_amount_descriptions">

							<p class="description">The amount in cents, i.e. for $10.00 enter 1000. The
								description will be displayed in the dropdown for the amount. Use the {amount}
								placeholder to include the amount value. You can use drag'n'drop to reorder the
								payment amounts.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Payment Button Text: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="<?php echo $editForm->buttonTitle; ?>" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">

							<p class="description">The text on the payment button</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Amount on Button? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_button_amount" id="hide_button_amount" value="0" <?php echo ( $editForm->showButtonAmount == '0' ) ? 'checked' : '' ?> >
								Hide
							</label>
							<label class="radio inline">
								<input type="radio" name="form_button_amount" id="show_button_amount" value="1" <?php echo ( $editForm->showButtonAmount == '1' ) ? 'checked' : '' ?> >
								Show
							</label>

							<p class="description">For set amount forms, choose to show/hide the amount on the payment
								button</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Send Email Receipt? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="0" <?php echo ( $editForm->sendEmailReceipt == '0' ) ? 'checked' : '' ?>>
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="1" <?php echo ( $editForm->sendEmailReceipt == '1' ) ? 'checked' : '' ?>>
								Yes
							</label>

							<p class="description">Send an email receipt on successful payment?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Billing Address Field? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" <?php echo ( $editForm->showAddress == '0' ) ? 'checked' : '' ?> >
								Hide
							</label>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input" id="show_address_input" value="1" <?php echo ( $editForm->showAddress == '1' ) ? 'checked' : '' ?> >
								Show
							</label>

							<p class="description">Should this payment form also ask for the customers billing
								address?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Redirect On Success?</label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect" id="do_redirect_no" value="0" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1" <?php echo ( $editForm->redirectOnSuccess == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">When payment is successful you can choose to redirect to another page
								or post</p>
						</td>
					</tr>
					<?php include( 'fragments/redirect_to_for_edit.php' ); ?>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Style: </label>
						</th>
						<td>
							<select class="regular-text" name="form_style" id="form_style">
								<option value="0" <?php if ( $editForm->formStyle == 0 ) {
									echo 'selected="selected"';
								} ?> >Default
								</option>
								<option value="1" <?php if ( $editForm->formStyle == 1 ) {
									echo 'selected="selected"';
								} ?> >Compact
								</option>
							</select>

							<p class="description">Choose how you'd like the form to look. (More coming soon!)</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Custom Input Fields? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_include_custom_input" id="noinclude_custom_input" value="0" <?php echo ( $editForm->showCustomInput == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_include_custom_input" id="include_custom_input" value="1" <?php echo ( $editForm->showCustomInput == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">You can ask for extra information from the customer to be included in
								the payment details</p>
						</td>
					</tr>
				</table>
				<!-- table for custom inputs -->
				<?php
				$customInputs = array();
				if ( $editForm->customInputs ) {
					$customInputs = explode( '{{', $editForm->customInputs );
				}
				?>
				<table id="customInputSection" class="form-table" style="<?php echo ( $editForm->showCustomInput == '0' ) ? 'display:none;' : '' ?>">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Number of inputs: </label>
						</th>
						<td>
							<select id="customInputNumberSelect">
								<option value="1" <?php echo ( count( $customInputs ) == 1 ) ? 'selected="selected"' : '' ?>>
									1
								</option>
								<option value="2" <?php echo ( count( $customInputs ) == 2 ) ? 'selected="selected"' : '' ?>>
									2
								</option>
								<option value="3" <?php echo ( count( $customInputs ) == 3 ) ? 'selected="selected"' : '' ?>>
									3
								</option>
								<option value="4" <?php echo ( count( $customInputs ) == 4 ) ? 'selected="selected"' : '' ?>>
									4
								</option>
								<option value="5" <?php echo ( count( $customInputs ) == 5 ) ? 'selected="selected"' : '' ?>>
									5
								</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Custom Input Label 1: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_1" id="form_custom_input_label_1" <?php echo ( count( $customInputs ) > 0 ) ? 'value="' . $customInputs[0] . '"' : '' ?> />

							<p class="description">The text for the label next to the custom input field</p>
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci2">
						<th scope="row">
							<label class="control-label">Custom Input Label 2: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_2" id="form_custom_input_label_2" <?php echo ( count( $customInputs ) > 1 ) ? 'value="' . $customInputs[1] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci3">
						<th scope="row">
							<label class="control-label">Custom Input Label 3: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_3" id="form_custom_input_label_3" <?php echo ( count( $customInputs ) > 2 ) ? 'value="' . $customInputs[2] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci4">
						<th scope="row">
							<label class="control-label">Custom Input Label 4: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_4" id="form_custom_input_label_4" <?php echo ( count( $customInputs ) > 3 ) ? 'value="' . $customInputs[3] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci5">
						<th scope="row">
							<label class="control-label">Custom Input Label 5: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_5" id="form_custom_input_label_5" <?php echo ( count( $customInputs ) > 4 ) ? 'value="' . $customInputs[4] . '"' : '' ?> />
						</td>
					</tr>
				</table>

				<p class="submit">
					<button class="button button-primary" type="submit">Save Changes</button>
					<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="button">Cancel</a>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
				</p>
			</form>
		<?php elseif ( $formType == "subscription" ): ?>
			<form class="form-horizontal" action="" method="POST" id="edit-subscription-form">
				<p class="tips"></p>
				<input type="hidden" name="action" value="wp_full_stripe_edit_subscription_form"/>
				<input type="hidden" name="formID" value="<?php echo $editForm->subscriptionFormID; ?>">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Name: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_name" id="form_name" value="<?php echo $editForm->name; ?>" maxlength="<?php echo $form::NAME_LENGTH; ?>">

							<p class="description">This name will be used to identify this form in the shortcode i.e.
								[fullstripe_subscription form="FormName"]</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Title: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_title" id="form_title" value="<?php echo $editForm->formTitle; ?>" maxlength="<?php echo $form::FORM_TITLE_LENGTH; ?>">

							<p class="description">The title of the form</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Coupon Input Field? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_include_coupon_input" id="noinclude_coupon_input" value="0" <?php echo ( $editForm->showCouponInput == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_include_coupon_input" id="include_coupon_input" value="1" <?php echo ( $editForm->showCouponInput == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">You can allow customers to input coupon codes for discounts. Must
								create the coupon in your Stripe account dashboard.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Send Email Receipt? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="0" <?php echo ( $editForm->sendEmailReceipt == '0' ) ? 'checked' : '' ?>>
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="1" <?php echo ( $editForm->sendEmailReceipt == '1' ) ? 'checked' : '' ?>>
								Yes
							</label>

							<p class="description">Send an email receipt on successful payment?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Billing Address Field? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" <?php echo ( $editForm->showAddress == '0' ) ? 'checked' : '' ?> >
								Hide
							</label>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input" id="show_address_input" value="1" <?php echo ( $editForm->showAddress == '1' ) ? 'checked' : '' ?> >
								Show
							</label>

							<p class="description">Should this form also ask for the customers billing address?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Subscribe Button Text: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_button_text_sub" id="form_button_text_sub" value="<?php echo $editForm->buttonTitle; ?>" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">

							<p class="description">The text on the subscribe button</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Setup Fee: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_setup_fee" id="form_setup_fee" value="<?php echo $editForm->setupFee; ?>">

							<p class="description">Amount to charge the customer to setup the subscription. Entering 0
								will disable. (in cents. i.e. for $10.00 enter 1000)</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Plans: </label>
						</th>
						<td>
							<div class="plan_checkboxes">
								<ul class="plan_checkbox_list">
									<?php
									$formPlans     = json_decode( $editForm->plans );
									$ordered_plans = array();
									$plan_order    = array();
									foreach ( $plans['data'] as $plan ) {
										$i = array_search( $plan->id, $formPlans );
										if ( $i !== false ) {
											$ordered_plans[ $i ] = $plan;
										}
									}
									ksort( $ordered_plans );
									?>
									<?php foreach ( $ordered_plans as $plan ): ?>
										<?php $plan_order[] = $plan->id; ?>
										<li class="ui-state-default" data-toggle="tooltip" title="You can reorder this list by using drag'n'drop." data-plan-id="<?php echo esc_attr( $plan->id ); ?>">
											<label class="checkbox inline">
												<input type="checkbox" class="plan_checkbox" id="check_<?php echo esc_attr( $plan->id ); ?>" value="<?php echo esc_attr( $plan->id ); ?>" checked>
                                        <span class="plan_checkbox_text"><?php echo esc_html( $plan->name ); ?> (
	                                        <?php
	                                        $str = sprintf( '$%0.2f', $plan->amount / 100.0 );
	                                        if ( $plan->interval_count == 1 ) {
		                                        $str .= ' ' . ucfirst( $plan->interval ) . 'ly';
	                                        } else {
		                                        $str .= ' every ' . $plan->interval_count . ' ' . $plan->interval . 's';
	                                        }
	                                        echo esc_html( $str );
	                                        ?>
	                                        )</span>
											</label>
										</li>
									<?php endforeach; ?>
									<?php foreach ( $plans['data'] as $plan ): ?>
										<?php if ( ! in_array( $plan->id, $formPlans ) ): ?>
											<?php $plan_order[] = $plan->id; ?>
											<li class="ui-state-default" data-toggle="tooltip" title="You can reorder this list by using drag'n'drop." data-plan-id="<?php echo esc_attr( $plan->id ); ?>">
												<label class="checkbox inline">
													<input type="checkbox" class="plan_checkbox" id="check_<?php echo esc_attr( $plan->id ); ?>" value="<?php echo esc_attr( $plan->id ); ?>">
                                            <span class="plan_checkbox_text"><?php echo esc_html( $plan->name ); ?> (
	                                            <?php
	                                            $str = sprintf( '$%0.2f', $plan->amount / 100.0 );
	                                            if ( $plan->interval_count == 1 ) {
		                                            $str .= ' ' . ucfirst( $plan->interval ) . 'ly';
	                                            } else {
		                                            $str .= ' every ' . $plan->interval_count . ' ' . $plan->interval . 's';
	                                            }
	                                            echo esc_html( $str );
	                                            ?>
	                                            )</span>
												</label>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</div>
							<p class="description">Which subscription plans can be chosen on this form. The list can be
								reordered by using drag'n'drop.</p>
							<input type="hidden" name="plan_order" value="<?php echo rawurlencode( json_encode( $plan_order ) ); ?>"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Redirect On Success?</label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect" id="do_redirect_no" value="0" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1" <?php echo ( $editForm->redirectOnSuccess == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">When payment is successful you can choose to redirect to another page
								or post</p>
						</td>
					</tr>
					<?php include( 'fragments/redirect_to_for_edit.php' ); ?>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Custom Input Fields? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_include_custom_input" id="noinclude_custom_input" value="0" <?php echo ( $editForm->showCustomInput == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_include_custom_input" id="include_custom_input" value="1" <?php echo ( $editForm->showCustomInput == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">You can ask for extra information from the customer to be included in
								the payment details</p>
						</td>
					</tr>
				</table>
				<!-- table for custom inputs -->
				<?php
				$customInputs = array();
				if ( $editForm->customInputs ) {
					$customInputs = explode( '{{', $editForm->customInputs );
				}
				?>
				<table id="customInputSection" class="form-table" style="<?php echo ( $editForm->showCustomInput == '0' ) ? 'display:none;' : '' ?>">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Number of inputs: </label>
						</th>
						<td>
							<select id="customInputNumberSelect">
								<option value="1" <?php echo ( count( $customInputs ) == 1 ) ? 'selected="selected"' : '' ?>>
									1
								</option>
								<option value="2" <?php echo ( count( $customInputs ) == 2 ) ? 'selected="selected"' : '' ?>>
									2
								</option>
								<option value="3" <?php echo ( count( $customInputs ) == 3 ) ? 'selected="selected"' : '' ?>>
									3
								</option>
								<option value="4" <?php echo ( count( $customInputs ) == 4 ) ? 'selected="selected"' : '' ?>>
									4
								</option>
								<option value="5" <?php echo ( count( $customInputs ) == 5 ) ? 'selected="selected"' : '' ?>>
									5
								</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Custom Input Label 1: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_1" id="form_custom_input_label_1" <?php echo ( count( $customInputs ) > 0 ) ? 'value="' . $customInputs[0] . '"' : '' ?> />

							<p class="description">The text for the label next to the custom input field</p>
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci2">
						<th scope="row">
							<label class="control-label">Custom Input Label 2: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_2" id="form_custom_input_label_2" <?php echo ( count( $customInputs ) > 1 ) ? 'value="' . $customInputs[1] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci3">
						<th scope="row">
							<label class="control-label">Custom Input Label 3: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_3" id="form_custom_input_label_3" <?php echo ( count( $customInputs ) > 2 ) ? 'value="' . $customInputs[2] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci4">
						<th scope="row">
							<label class="control-label">Custom Input Label 4: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_4" id="form_custom_input_label_4" <?php echo ( count( $customInputs ) > 3 ) ? 'value="' . $customInputs[3] . '"' : '' ?> />
						</td>
					</tr>
					<tr valign="top" style="display: none;" class="ci5">
						<th scope="row">
							<label class="control-label">Custom Input Label 5: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_custom_input_label_5" id="form_custom_input_label_5" <?php echo ( count( $customInputs ) > 4 ) ? 'value="' . $customInputs[4] . '"' : '' ?> />
						</td>
					</tr>
				</table>

				<p class="submit">
					<button class="button button-primary" type="submit">Save Changes</button>
					<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=forms' ); ?>" class="button">Cancel</a>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
				</p>
			</form>
		<?php elseif ( $formType == "checkout" ): ?>
			<form class="form-horizontal" action="" method="POST" id="edit-checkout-form">
				<p class="tips"></p>
				<input type="hidden" name="action" value="wp_full_stripe_edit_checkout_form">
				<input type="hidden" name="formID" value="<?php echo $editForm->checkoutFormID; ?>">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Name: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_name_ck" id="form_name_ck" value="<?php echo $editForm->name; ?>" maxlength="<?php echo $form::NAME_LENGTH; ?>">

							<p class="description">This name will be used to identify this form in the shortcode i.e.
								[fullstripe_checkout form="FormName"]</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Company Name: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="company_name_ck" id="company_name_ck" value="<?php echo $editForm->companyName; ?>" maxlength="<?php echo $form::COMPANY_NAME_LENGTH; ?>">

							<p class="description">Used as the title of the checkout form</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Product Description: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="prod_desc_ck" id="prod_desc_ck" value="<?php echo $editForm->productDesc; ?>" maxlength="<?php echo $form::PRODUCT_DESCRIPTION_LENGTH; ?>">

							<p class="description">A short description (one line) about the product sold using this
								form</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Payment Amount: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_amount_ck" id="form_amount_ck" value="<?php echo $editForm->amount; ?>"/>

							<p class="description">The amount this form will charge your customer, in cents. i.e. for
								$10.00 enter 1000</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Open Form Button Text: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="open_form_button_text_ck" id="open_form_button_text_ck" value="<?php echo $editForm->openButtonTitle; ?>" maxlength="<?php echo $form::OPEN_BUTTON_TITLE_LENGTH; ?>">

							<p class="description">The text on the button used to pop open this form</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Payment Button Text: </label>
						</th>
						<td>
							<input type="text" class="regular-text" name="form_button_text_ck" id="form_button_text_ck" value="<?php echo $editForm->buttonTitle; ?>" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">

							<p class="description">The text on the payment button. Use {{amount}} to show the payment
								amount on this button.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Send Email Receipt? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="0" <?php echo ( $editForm->sendEmailReceipt == '0' ) ? 'checked' : '' ?>>
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_send_email_receipt" value="1" <?php echo ( $editForm->sendEmailReceipt == '1' ) ? 'checked' : '' ?>>
								Yes
							</label>

							<p class="description">Send an email receipt on successful payment?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Billing Address Field? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input_ck" id="hide_address_input_ck" value="0" <?php echo ( $editForm->showBillingAddress == '0' ) ? 'checked' : '' ?> >
								Hide
							</label>
							<label class="radio inline">
								<input type="radio" name="form_show_address_input_ck" id="show_address_input_ck" value="1" <?php echo ( $editForm->showBillingAddress == '1' ) ? 'checked' : '' ?> >
								Show
							</label>

							<p class="description">Should this payment form also ask for the customers billing
								address?</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Include Remember Me Field? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_show_remember_me_ck" id="hide_remember_me_ck" value="0" <?php echo ( $editForm->showRememberMe == '0' ) ? 'checked' : '' ?>>
								Hide
							</label>
							<label class="radio inline">
								<input type="radio" name="form_show_remember_me_ck" id="show_remember_me_ck" value="1" <?php echo ( $editForm->showRememberMe == '1' ) ? 'checked' : '' ?> >
								Show
							</label>

							<p class="description">Show the Stripe Remember Me checkbox, allowing users to save their
								information with Stripe for later use.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Image</label>
						</th>
						<td>
							<input id="form_checkout_image" type="text" name="form_checkout_image" value="<?php echo $editForm->image; ?>" maxlength="<?php echo $form::IMAGE_LENGTH; ?>">
							<button id="upload_image_button" class="button" type="button" value="Upload Image">Upload
								Image
							</button>
							<p class="description">A square image of your brand or product which is shown on the form.
								Min size 128px x 128px.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Disable Button Styling? </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_no" value="0" <?php echo ( $editForm->disableStyling == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_yes" value="1" <?php echo ( $editForm->disableStyling == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">Disable the styling on the checkout button if you are noticing
								conflicts with your theme.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Redirect On Success?</label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect_ck" id="do_redirect_no_ck" value="0" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'checked' : '' ?> >
								No
							</label>
							<label class="radio inline">
								<input type="radio" name="form_do_redirect_ck" id="do_redirect_yes_ck" value="1" <?php echo ( $editForm->redirectOnSuccess == '1' ) ? 'checked' : '' ?> >
								Yes
							</label>

							<p class="description">When payment is successful you can choose to redirect to another page
								or post</p>
						</td>
					</tr>
					<?php include( 'fragments/redirect_to_for_edit_checkout.php' ); ?>
					<tr valign="top">
						<th scope="row">
							<label class="control-label"><?php _e( 'Use Bitcoin?', 'wp-full-stripe' ); ?></label>
						</th>
						<?php if ( $options['currency'] == 'usd' ): ?>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_use_bitcoin" id="use_bitcoin_no" value="0" <?php echo ( $editForm->useBitcoin == '0' ) ? 'checked' : '' ?> >
									<?php _e( 'No', 'wp-full-stripe' ); ?>
								</label>
								<label class="radio inline">
									<input type="radio" name="form_use_bitcoin" id="use_bitcoin_yes" value="1" <?php echo ( $editForm->useBitcoin == '1' ) ? 'checked' : '' ?> >
									<?php _e( 'Yes', 'wp-full-stripe' ); ?>
								</label>
								<p class="description"><?php _e( 'Allow to use Bitcoin for payments.', 'wp-full-stripe' ); ?></p>
							</td>
						<?php else: ?>
							<td>
								<p class="alert alert-info"><?php printf( __( "In order to use Bitcoin for payments, you have to set the plugin currency to USD on the <a href=\"%s\">Settings page</a>, and you have to link an US bank account to your Stripe account, then <a href=\"%s\">enable Bitcoin</a> on your Stripe account.", "wp-full-stripe" ), admin_url( "admin.php?page=fullstripe-settings" ), "https://dashboard.stripe.com/account/bitcoin/enable" ); ?></p>
							</td>
						<?php endif; ?>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label class="control-label"><?php esc_html_e( 'Use Alipay?', 'wp-full-stripe' ); ?></label>
						</th>
						<?php if ( $options['currency'] == 'usd' ): ?>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_use_alipay" id="use_alipay_no" value="0" <?php echo ( $editForm->useAlipay == '0' ) ? 'checked' : '' ?> >
									<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
								</label>
								<label class="radio inline">
									<input type="radio" name="form_use_alipay" id="use_alipay_yes" value="1" <?php echo ( $editForm->useAlipay == '1' ) ? 'checked' : '' ?> >
									<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
								</label>

								<p class="description"><?php esc_html_e( 'Accept payments from hundreds of millions of new customers using Alipay, China’s most popular payment method.', 'wp-full-stripe' ); ?></p>
							</td>
						<?php else: ?>
							<td>
								<p class="alert alert-info"><?php printf( __( "In order to use AliPay for payments, you have to set the plugin currency to USD on the <a href=\"%s\">Settings page</a>, and you have to link an US bank account to your Stripe account.", "wp-full-stripe" ), admin_url( "admin.php?page=fullstripe-settings" ) ); ?></p>
							</td>
						<?php endif; ?>
					</tr>
				</table>
				<p class="submit">
					<button class="button button-primary" type="submit">Save Changes</button>
					<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="button">Cancel</a>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
				</p>
			</form>
		<?php endif; ?>
	<?php endif; ?>
</div>
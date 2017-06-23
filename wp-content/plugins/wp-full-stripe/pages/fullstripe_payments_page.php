<?php

/**
 * @var Form
 */
$form = MM_WPFS::getInstance()->get_form_validation_data();

global $wpdb;
//get the data we need
$payment_forms  = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payment_forms;" );
$checkout_forms = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "fullstripe_checkout_forms;" );
$options        = get_option( 'fullstripe_options' );

$currency_symbol = MM_WPFS::get_currency_symbol_for( $options['currency'] );

$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'payments';

?>
<div class="wrap">
	<h2> <?php esc_html_e( 'Full Stripe Payments', 'wp-full-stripe' ); ?> </h2>

	<div id="updateDiv"><p><strong id="updateMessage"></strong></p></div>

	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=payments' ); ?>" class="nav-tab <?php echo $active_tab == 'payments' ? 'nav-tab-active' : ''; ?>">Payments</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="nav-tab <?php echo $active_tab == 'forms' ? 'nav-tab-active' : ''; ?>">Payment
			Forms</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=create' ); ?>" class="nav-tab <?php echo $active_tab == 'create' ? 'nav-tab-active' : ''; ?>">Create
			New Form</a>
	</h2>

	<div class="tab-content">
		<?php if ( $active_tab == 'payments' ): ?>
			<div class="" id="payments">
				<h2>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
				</h2>
				<form method="get">
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
					<label><?php _e( 'Customer: ', 'wp-full-stripe' ); ?></label><input type="text" name="customer" size="35" placeholder="<?php _e( 'Enter name, email address, or stripe ID', 'wp-full-stripe' ); ?>" value="<?php echo isset( $_REQUEST['customer'] ) ? $_REQUEST['customer'] : ''; ?>">
					<label><?php _e( 'Payment: ', 'wp-full-stripe' ); ?></label><input type="text" name="payment" placeholder="<?php _e( 'Enter charge ID', 'wp-full-stripe' ); ?>" value="<?php echo isset( $_REQUEST['payment'] ) ? $_REQUEST['payment'] : ''; ?>">
					<label><?php _e( 'Mode: ', 'wp-full-stripe' ); ?></label>
					<select name="mode">
						<option value="" <?php echo ! isset( $_REQUEST['mode'] ) || $_REQUEST['mode'] == '' ? 'selected' : ''; ?>><?php _e( 'All', 'wp-full-stripe' ); ?></option>
						<option value="live" <?php echo isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'live' ? 'selected' : ''; ?>><?php _e( 'Live', 'wp-full-stripe' ); ?></option>
						<option value="test" <?php echo isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'test' ? 'selected' : ''; ?>><?php _e( 'Test', 'wp-full-stripe' ); ?></option>
					</select>
					<span class="wpfs-search-actions">
						<button class="button button-primary"><?php _e( 'Search', 'wp-full-stripe' ); ?></button> <?php _e( 'or', 'wp-full-stripe' ); ?>
						<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments' ); ?>"><?php _e( 'Reset', 'wp-full-stripe' ); ?></a>
					</span>
					<?php
					/** @var WP_List_Table $table */
					$table->prepare_items();
					$table->display();
					?>
				</form>
			</div>
		<?php elseif ( $active_tab == 'forms' ): ?>
			<div class="" id="forms">
				<div style="min-height: 200px;">
					<h2><?php esc_html_e( 'Your Payment Forms', 'wp-full-stripe' ); ?>
						<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
					</h2>
					<?php if ( count( $payment_forms ) === 0 ): ?>
						<p class="alert alert-info">
							<?php esc_html_e( 'You have created no payment forms yet. Use the Create New Form tab to get started', 'wp-full-stripe' ); ?>
						</p>
					<?php else: ?>
						<table class="wp-list-table widefat fixed payment-forms">
							<thead>
							<tr>
								<th class="manage-column column-action column-primary"><?php esc_html_e( 'Actions', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-name"><?php esc_html_e( 'Name', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-title"><?php esc_html_e( 'Title', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-amount"><?php esc_html_e( 'Amount', 'wp-full-stripe' ); ?></th>
							</tr>
							</thead>
							<tbody id="paymentFormsTable">
							<?php foreach ( $payment_forms as $payment_form ): ?>
								<tr>
									<td class="column-action">
										<?php
										$shortcode = "[fullstripe_payment form=\"{$payment_form->name}\"]"
										?>
										<span id="shortcode-payment-tooltip__<?php echo $payment_form->paymentFormID; ?>" class="shortcode-tooltip" data-shortcode="<?php echo esc_attr( $shortcode ); ?>"></span>
										<a id="shortcode-payment__<?php echo $payment_form->paymentFormID; ?>" class="button button-primary shortcode-payment" data-form-id="<?php echo $payment_form->paymentFormID; ?>" title="<?php _e( 'Shortcode', 'wp-full-stripe' ); ?>">
											<i class="fa fa-code fa-fw"></i>
										</a>
										<a class="button button-primary" href="<?php echo add_query_arg(
											array(
												'page' => 'fullstripe-edit-form',
												'form' => $payment_form->paymentFormID,
												'type' => 'payment'
											),
											admin_url( "admin.php" )
										); ?>" title="<?php _e( 'Edit', 'wp-full-stripe' ); ?>"><i class="fa fa-pencil fa-fw"></i></a>
										<span class="form-action-last">
											<button class="button delete" data-id="<?php echo $payment_form->paymentFormID; ?>" data-type="paymentForm" title="<?php _e( 'Delete', 'wp-full-stripe' ); ?>">
												<i class="fa fa-trash-o fa-fw"></i>
											</button>
										</span>
									</td>
									<td class="column-name"><?php echo esc_html( $payment_form->name ); ?></td>
									<td class="column-title"><?php echo esc_html( $payment_form->formTitle ); ?></td>
									<?php if ( $payment_form->customAmount == 'specified_amount' ): ?>
										<td class="column-amount"><?php echo esc_html( sprintf( '%s%0.2f', $currency_symbol, ( $payment_form->amount / 100.0 ) ) ); ?></td>
									<?php elseif ( $payment_form->customAmount == 'list_of_amounts' ): ?>
										<?php
										$table_cell                       = "<td class=\"column-amount\">";
										$initial_table_cell_markup_length = strlen( $table_cell );
										$list_of_amounts                  = json_decode( $payment_form->listOfAmounts );
										foreach ( $list_of_amounts as $list_element ) {
											$list_element_amount = $list_element[0];
											if ( strlen( $table_cell ) == $initial_table_cell_markup_length ) {
												$table_cell .= sprintf( "%s%0.2f", $currency_symbol, ( $list_element_amount / 100.0 ) );
											} else {
												$table_cell .= sprintf( ", %s%0.2f", $currency_symbol, ( $list_element_amount / 100.0 ) );
											}
										}
										$table_cell .= "</td>";
										echo $table_cell;
										?>
									<?php elseif ( $payment_form->customAmount == 'custom_amount' ): ?>
										<td class="column-amount"><?php esc_html_e( 'Custom', 'wp-full-stripe' ); ?></td>
									<?php else: ?>
										<td class="column-amount"><?php esc_html_e( 'Unknown', 'wp-full-stripe' ); ?></td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
				<div style="min-height: 200px;">
					<h2><?php esc_html_e( 'Your Checkout Forms', 'wp-full-stripe' ); ?></h2>
					<?php if ( count( $checkout_forms ) === 0 ): ?>
						<p class="alert alert-info">
							<?php esc_html_e( 'You have created no checkout forms yet. Use the Create New Form tab to get started', 'wp-full-stripe' ); ?>
						</p>
					<?php else: ?>
						<table class="wp-list-table widefat fixed checkout-forms">
							<thead>
							<tr>
								<th class="manage-column column-action column-primary"><?php esc_html_e( 'Actions', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-name"><?php esc_html_e( 'Name', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-product"><?php esc_html_e( 'Product', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-amount"><?php esc_html_e( 'Amount', 'wp-full-stripe' ); ?></th>
							</tr>
							</thead>
							<tbody id="checkoutFormsTable">
							<?php foreach ( $checkout_forms as $checkout_form ): ?>
								<tr>
									<td>
										<?php
										$shortcode = "[fullstripe_checkout form=\"{$checkout_form->name}\"]"
										?>
										<span id="shortcode-checkout-tooltip__<?php echo $checkout_form->checkoutFormID; ?>" class="shortcode-tooltip" data-shortcode="<?php echo esc_attr( $shortcode ); ?>"></span>
										<a id="shortcode-checkout__<?php echo $checkout_form->checkoutFormID; ?>" class="button button-primary shortcode-checkout" data-form-id="<?php echo $checkout_form->checkoutFormID; ?>" title="<?php _e( 'Shortcode', 'wp-full-stripe' ); ?>">
											<i class="fa fa-code fa-fw"></i>
										</a>
										<a class="button button-primary" href="<?php echo add_query_arg(
											array(
												'page' => 'fullstripe-edit-form',
												'form' => $checkout_form->checkoutFormID,
												'type' => 'checkout'
											),
											admin_url( "admin.php" )
										); ?>" title="<?php _e( 'Edit', 'wp-full-stripe' ); ?>"><i class="fa fa-pencil fa-fw"></i></a>
										<span class="form-action-last">
											<button class="button delete" data-id="<?php echo $checkout_form->checkoutFormID; ?>" data-type="checkoutForm" title="<?php _e( 'Delete', 'wp-full-stripe' ); ?>">
												<i class="fa fa-trash-o fa-fw"></i>
											</button>
										</span>
									</td>
									<td class="column-name"><?php echo esc_html( $checkout_form->name ); ?></td>
									<td class="column-product"><?php echo esc_html( $checkout_form->productDesc ); ?></td>
									<td class="column-amounr"><?php echo esc_html( sprintf( '%s%0.2f', $currency_symbol, $checkout_form->amount / 100.0 ) ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
		<?php elseif ( $active_tab == 'create' ): ?>
		<div class="" id="create">
			<div class="choose-form-buttons">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label class="control-label">Form Type: </label>
						</th>
						<td>
							<label class="radio inline">
								<input type="radio" name="payment_form_type" id="set_payment_form_type_payment" value="0" checked="checked">
								Payment
							</label> <label class="radio inline">
								<input type="radio" name="payment_form_type" id="set_payment_form_type_checkout" value="1">
								Checkout
							</label>

							<p class="description">What kind of payment form would you like to create?</p>
						</td>
					</tr>
				</table>
				<hr/>
			</div>
			<div id="createPaymentFormSection">
				<form class="form-horizontal" action="" method="POST" id="create-payment-form">
					<p class="tips"></p>
					<input type="hidden" name="action" value="wp_full_stripe_create_payment_form">
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Form Name: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_name" id="form_name" maxlength="<?php echo $form::NAME_LENGTH; ?>">

								<p class="description">This name will be used to identify this form in the shortcode
									i.e. [fullstripe_payment form="FormName"]</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Form Title: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_title" id="form_title" maxlength="<?php echo $form::FORM_TITLE_LENGTH; ?>">

								<p class="description">The title of the form</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Type: </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_custom" id="set_specific_amount" value="specified_amount" checked="checked">
									Set Amount
								</label>
								<label class="radio inline">
									<input type="radio" name="form_custom" id="set_amount_list" value="list_of_amounts">
									Select Amount from List
								</label>
								<label class="radio inline">
									<input type="radio" name="form_custom" id="set_custom_amount" value="custom_amount">
									Custom Amount
								</label>

								<p class="description">Choose to set a specific amount or a list of amounts for this
									form, or allow customers to set custom amounts</p>
							</td>
						</tr>
						<tr valign="top" id="payment_amount_row">
							<th scope="row">
								<label class="control-label">Payment Amount: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_amount" id="form_amount"/>

								<p class="description">The amount this form will charge your customer, in cents.
									i.e. for $10.00 enter 1000</p>
							</td>
						</tr>
						<tr valign="top" id="payment_amount_list_row" style="display: none;">
							<th scope="row">
								<label class="control-label">Payment Amount Options: </label>
							</th>
							<td>
								<a href="#" class="button button-primary" id="add_payment_amount_button"">Add</a>
								<input type="text" id="payment_amount_value" placeholder="Amount" maxlength="<?php echo $form::PAYMENT_AMOUNT_LENGTH; ?>"><input type="text" id="payment_amount_description" placeholder="Description" maxlength="<?php echo $form::PAYMENT_AMOUNT_DESCRIPTION_LENGTH; ?>"><br>
								<ul id="payment_amount_list"></ul>
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
								<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="Make Payment" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">

								<p class="description">The text on the payment button</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Include Amount on Button? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_button_amount" id="hide_button_amount" value="0">
									Hide
								</label> <label class="radio inline">
									<input type="radio" name="form_button_amount" id="show_button_amount" value="1" checked="checked">
									Show
								</label>

								<p class="description">For set amount forms, choose to show/hide the amount on the
									payment button</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Send Email Receipt? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_send_email_receipt" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_send_email_receipt" value="1"> Yes
								</label>

								<p class="description">Send an email receipt on successful payment? </p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Include Billing Address Field? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" checked="checked">
									Hide
								</label> <label class="radio inline">
									<input type="radio" name="form_show_address_input" id="show_address_input" value="1">
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
									<input type="radio" name="form_do_redirect" id="do_redirect_no" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1"> Yes
								</label>

								<p class="description">When payment is successful you can choose to redirect to
									another page or post</p>
							</td>
						</tr>
						<?php include( 'fragments/redirect_to_for_create.php' ); ?>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Form Style: </label>
							</th>
							<td>
								<select class="regular-text" name="form_style" id="form_style">
									<option value="0">Default</option>
									<option value="1">Compact</option>
								</select>

								<p class="description">Choose how you'd like the form to look. (More coming
									soon!)</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Include Custom Input Fields? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_include_custom_input" id="noinclude_custom_input" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_include_custom_input" id="include_custom_input" value="1">
									Yes
								</label>

								<p class="description">You can ask for extra information from the customer to be
									included in the payment details</p>
							</td>
						</tr>
					</table>
					<!-- table for custom inputs -->
					<table id="customInputSection" class="form-table" style="display: none;">
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Number of inputs: </label>
							</th>
							<td>
								<select id="customInputNumberSelect">
									<option value="1" selected="selected">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Custom Input Label 1: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_custom_input_label_1" id="form_custom_input_label_1"/>

								<p class="description">The text for the label next to the custom input field</p>
							</td>
						</tr>
						<tr valign="top" style="display: none;" class="ci2">
							<th scope="row">
								<label class="control-label">Custom Input Label 2: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_custom_input_label_2" id="form_custom_input_label_2"/>
							</td>
						</tr>
						<tr valign="top" style="display: none;" class="ci3">
							<th scope="row">
								<label class="control-label">Custom Input Label 3: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_custom_input_label_3" id="form_custom_input_label_3"/>
							</td>
						</tr>
						<tr valign="top" style="display: none;" class="ci4">
							<th scope="row">
								<label class="control-label">Custom Input Label 4: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_custom_input_label_4" id="form_custom_input_label_4"/>
							</td>
						</tr>
						<tr valign="top" style="display: none;" class="ci5">
							<th scope="row">
								<label class="control-label">Custom Input Label 5: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_custom_input_label_5" id="form_custom_input_label_5"/>
							</td>
						</tr>
					</table>

					<p class="submit">
						<button class="button button-primary" type="submit">Create Form</button>
						<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
					</p>
				</form>
			</div>
			<div id="createCheckoutFormSection" style="display: none;">
				<form class="form-horizontal" action="" method="POST" id="create-checkout-form">
					<p class="tips"></p>
					<input type="hidden" name="action" value="wp_full_stripe_create_checkout_form">
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Form Name: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_name_ck" id="form_name_ck" maxlength="<?php echo $form::NAME_LENGTH; ?>">

								<p class="description">This name will be used to identify this form in the shortcode
									i.e. [fullstripe_checkout form="FormName"]</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Company Name: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="company_name_ck" id="company_name_ck" maxlength="<?php echo $form::COMPANY_NAME_LENGTH; ?>">

								<p class="description">Used as the title of the checkout form</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Product Description: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="prod_desc_ck" id="prod_desc_ck" maxlength="<?php echo $form::PRODUCT_DESCRIPTION_LENGTH; ?>">

								<p class="description">A short description (one line) about the product sold using
									this form</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Amount: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_amount_ck" id="form_amount_ck"/>

								<p class="description">The amount this form will charge your customer, in cents.
									i.e. for $10.00 enter 1000</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Open Form Button Text: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="open_form_button_text_ck" id="open_form_button_text_ck" value="Pay With Card" maxlength="<?php echo $form::OPEN_BUTTON_TITLE_LENGTH; ?>">

								<p class="description">The text on the button used to pop open this form</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Button Text: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="form_button_text_ck" id="form_button_text_ck" value="Pay {{amount}}" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">

								<p class="description">The text on the payment button. Use {{amount}} to show the
									payment amount on this button.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Send Email Receipt? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_send_email_receipt" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_send_email_receipt" value="1"> Yes
								</label>

								<p class="description">Send an email receipt on successful payment? </p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Include Billing Address Field? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_show_address_input_ck" id="hide_address_input_ck" value="0" checked="checked">
									Hide
								</label> <label class="radio inline">
									<input type="radio" name="form_show_address_input_ck" id="show_address_input_ck" value="1">
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
									<input type="radio" name="form_show_remember_me_ck" id="hide_remember_me_ck" value="0" checked="checked">
									Hide
								</label> <label class="radio inline">
									<input type="radio" name="form_show_remember_me_ck" id="show_remember_me_ck" value="1">
									Show
								</label>

								<p class="description">Show the Stripe Remember Me checkbox, allowing users to save
									their information with Stripe for later use.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Image</label>
							</th>
							<td>
								<input id="form_checkout_image" type="text" name="form_checkout_image" value="http://" maxlength="<?php echo $form::IMAGE_LENGTH; ?>">
								<button id="upload_image_button" class="button" type="button" value="Upload Image">
									Upload Image
								</button>
								<p class="description">A square image of your brand or product which is shown on the
									form. Min size 128px x 128px.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Disable Button Styling? </label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_no" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_yes" value="1">
									Yes
								</label>

								<p class="description">Disable the styling on the checkout button if you are
									noticing conflicts with your theme.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Redirect On Success?</label>
							</th>
							<td>
								<label class="radio inline">
									<input type="radio" name="form_do_redirect_ck" id="do_redirect_no_ck" value="0" checked="checked">
									No
								</label> <label class="radio inline">
									<input type="radio" name="form_do_redirect_ck" id="do_redirect_yes_ck" value="1">
									Yes
								</label>

								<p class="description">When payment is successful you can choose to redirect to
									another page or post</p>
							</td>
						</tr>
						<?php include( 'fragments/redirect_to_for_create_checkout.php' ); ?>
						<tr valign="top">
							<th scope="row">
								<label class="control-label"><?php _e( 'Use Bitcoin?', 'wp-full-stripe' ); ?></label>
							</th>
							<?php if ( $options['currency'] == 'usd' ): ?>
								<td>
									<label class="radio inline">
										<input type="radio" name="form_use_bitcoin" id="use_bitcoin_no" value="0" checked="checked">
										<?php _e( 'No', 'wp-full-stripe' ); ?>
									</label> <label class="radio inline">
										<input type="radio" name="form_use_bitcoin" id="use_bitcoin_yes" value="1">
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
							<td>
								<label class="radio inline">
									<input type="radio" name="form_use_alipay" id="use_alipay_no" value="0" checked="checked">
									<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
								</label> <label class="radio inline">
									<input type="radio" name="form_use_alipay" id="use_alipay_yes" value="1">
									<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
								</label>

								<p class="description"><?php esc_html_e( 'Accept payments from hundreds of millions of new customers using Alipay, China’s most popular payment method.', 'wp-full-stripe' ); ?></p>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button class="button button-primary" type="submit">Create Form</button>
						<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
					</p>
				</form>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

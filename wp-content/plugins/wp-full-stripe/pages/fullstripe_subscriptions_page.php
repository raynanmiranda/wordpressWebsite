<?php

/**
 * @var Form
 */
$form = MM_WPFS::getInstance()->get_form_validation_data();

$options        = get_option( 'fullstripe_options' );
$currencySymbol = MM_WPFS::get_currency_symbol_for( $options['currency'] );

$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'subscribers';

global $wpdb;

//Load based on what tab we have open
$subscription_forms = array();
$plans              = array();
if ( $active_tab == 'forms' ) {
	$subscription_forms = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscription_forms;" );
} else if ( $active_tab == 'plans' || $active_tab == 'createform' ) {
	$plans = MM_WPFS::getInstance()->get_plans();
}
?>
<div class="wrap">
	<h2> <?php esc_html_e( 'Full Stripe Subscriptions', 'wp-full-stripe' ); ?> </h2>
	<div id="updateDiv"><p><strong id="updateMessage"></strong></p></div>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=subscribers' ); ?>" class="nav-tab <?php echo $active_tab == 'subscribers' ? 'nav-tab-active' : ''; ?>">Subscribers</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=forms' ); ?>" class="nav-tab <?php echo $active_tab == 'forms' ? 'nav-tab-active' : ''; ?>">Subscription
			Forms</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=plans' ); ?>" class="nav-tab <?php echo $active_tab == 'plans' ? 'nav-tab-active' : ''; ?>">Subscription
			Plans</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=createform' ); ?>" class="nav-tab <?php echo $active_tab == 'createform' ? 'nav-tab-active' : ''; ?>">Create
			New Form</a>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=createplan' ); ?>" class="nav-tab <?php echo $active_tab == 'createplan' ? 'nav-tab-active' : ''; ?>">Create
			New Plan</a>
	</h2>
	<div class="tab-content">
		<?php if ( $active_tab == 'subscribers' ): ?>
			<div class="" id="subscribers">
				<h2>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
				</h2>
				<form method="get">
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
					<label><?php _e( 'Subscriber: ', 'wp-full-stripe' ); ?></label><input type="text" name="subscriber" size="35" placeholder="<?php _e( 'Enter name, email address, or stripe ID', 'wp-full-stripe' ); ?>" value="<?php echo isset( $_REQUEST['subscriber'] ) ? $_REQUEST['subscriber'] : ''; ?>">
					<label><?php _e( 'Subscription: ', 'wp-full-stripe' ); ?></label><input type="text" name="subscription" placeholder="<?php _e( 'Enter subscription ID', 'wp-full-stripe' ); ?>" value="<?php echo isset( $_REQUEST['subscription'] ) ? $_REQUEST['subscription'] : ''; ?>">
					<label><?php _e( 'Mode: ', 'wp-full-stripe' ); ?></label>
					<select name="mode">
						<option value="" <?php echo ! isset( $_REQUEST['mode'] ) || $_REQUEST['mode'] == '' ? 'selected' : ''; ?>><?php _e( 'All', 'wp-full-stripe' ); ?></option>
						<option value="live" <?php echo isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'live' ? 'selected' : ''; ?>><?php _e( 'Live', 'wp-full-stripe' ); ?></option>
						<option value="test" <?php echo isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'test' ? 'selected' : ''; ?>><?php _e( 'Test', 'wp-full-stripe' ); ?></option>
					</select>
					<span class="wpfs-search-actions">
						<button class="button button-primary"><?php _e( 'Search', 'wp-full-stripe' ); ?></button> <?php _e( 'or', 'wp-full-stripe' ); ?>
						<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions' ); ?>"><?php _e( 'Reset', 'wp-full-stripe' ); ?></a>
					</span>
					<?php
					/** @var WP_List_Table $subscribersTable */
					$subscribersTable->prepare_items();
					$subscribersTable->display();
					?>
				</form>
			</div>
		<?php elseif ( $active_tab == 'forms' ): ?>
			<div class="" id="forms">
				<div style="min-height: 200px;">
					<h2><?php esc_html_e( 'Your Subscription Forms', 'wp-full-stripe' ); ?>
						<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
					</h2>
					<?php if ( count( $subscription_forms ) === 0 ): ?>
						<p class="alert alert-info">
							<?php esc_html_e( 'No subscription forms created. Use the Create New Form tab to get started', 'wp-full-stripe' ); ?>
						</p>
					<?php else: ?>
						<table class="wp-list-table widefat fixed subscription-forms">
							<thead>
							<tr>
								<th class="manage-column column-action column-primary"><?php esc_html_e( 'Actions', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-name"><?php esc_html_e( 'Name', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-title"><?php esc_html_e( 'Title', 'wp-full-stripe' ); ?></th>
								<th class="manage-column column-plan_ids"><?php esc_html_e( 'Plan IDs', 'wp-full-stripe' ); ?></th>
							</tr>
							</thead>
							<tbody id="subscriptionFormsTable">
							<?php foreach ( $subscription_forms as $subscription_form ): ?>
								<?php
								$plans_label = null;
								$sf_plans    = json_decode( $subscription_form->plans );
								if ( json_last_error() == JSON_ERROR_NONE ) {
									$plans_label = implode( ', ', $sf_plans );
								}
								?>
								<tr>
									<td class="column-action">
										<?php
										$shortcode = "[fullstripe_subscription form=\"{$subscription_form->name}\"]"
										?>
										<span id="shortcode-subscription-tooltip__<?php echo $subscription_form->subscriptionFormID; ?>" class="shortcode-tooltip" data-shortcode="<?php echo esc_attr( $shortcode ); ?>"></span>
										<a id="shortcode-subscription__<?php echo $subscription_form->subscriptionFormID; ?>" class="button button-primary shortcode-subscription" data-form-id="<?php echo $subscription_form->subscriptionFormID; ?>" title="<?php _e( 'Shortcode', 'wp-full-stripe' ); ?>">
											<i class="fa fa-code fa-fw"></i>
										</a>
										<a class="button button-primary" href="<?php echo add_query_arg(
											array(
												'page' => 'fullstripe-edit-form',
												'form' => $subscription_form->subscriptionFormID,
												'type' => 'subscription'
											),
											admin_url( "admin.php" )
										); ?>" title="<?php _e( 'Edit', 'wp-full-stripe' ); ?>"><i class="fa fa-pencil fa-fw"></i></a>
										<span class="form-action-last">
											<button class="button delete" data-id="<?php echo $subscription_form->subscriptionFormID; ?>" data-type="subscriptionForm" title="<?php _e( 'Delete', 'wp-full-stripe' ); ?>">
												<i class="fa fa-trash-o fa-fw"></i>
											</button>
										</span>
									</td>
									<td class="column-name"><?php echo esc_html( $subscription_form->name ); ?></td>
									<td class="column-title"><?php echo esc_html( $subscription_form->formTitle ); ?></td>
									<td class="column-plan_ids"><?php echo esc_html( $plans_label ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
		<?php elseif ( $active_tab == 'plans' ): ?>
			<div class="" id="plans">
				<h2><?php esc_html_e( 'Your Subscription Plans', 'wp-full-stripe' ); ?></h2>
				<?php if ( count( $plans ) === 0 ): ?>
					<p class="alert alert-info">
						<?php esc_html_e( 'You have no subscription plans created yet. Use the Create New Plan tab to get started', 'wp-full-stripe' ); ?>
					</p>
				<?php else: ?>
					<table class="wp-list-table widefat fixed subscription-plans">
						<thead>
						<tr>
							<th class="manage-column column-action column-primary"><?php esc_html_e( 'Actions', 'wp-full-stripe' ); ?></th>
							<th class="manage-column column-id_display_name"><?php esc_html_e( 'ID', 'wp-full-stripe' ); ?>
								/ <?php esc_html_e( 'Display Name', 'wp-full-stripe' ); ?></th>
							<th class="manage-column column-amount_interval"><?php esc_html_e( 'Amount', 'wp-full-stripe' ); ?>
								/ <?php esc_html_e( 'Interval', 'wp-full-stripe' ); ?></th>
							<th class="manage-column column-trial_duration"><?php esc_html_e( 'Trial', 'wp-full-stripe' ); ?>
								/ <?php esc_html_e( 'Duration', 'wp-full-stripe' ); ?></th>
						</tr>
						</thead>
						<tbody id="plansTable">
						<?php foreach ( $plans['data'] as $plan ): ?>
							<tr>
								<td class="column-action">
									<a class="button button-primary" href="<?php echo add_query_arg( array(
										'page' => 'fullstripe-edit-plan',
										'plan' => rawurlencode( $plan->id )
									), admin_url( "admin.php" ) ); ?>" title="<?php _e( 'Edit', 'wp-full-stripe' ); ?>"><i class="fa fa-pencil fa-fw"></i></a>
									<span class="form-action-last">
										<button class="button delete" data-id="<?php echo esc_attr( $plan->id ); ?>" data-type="subscriptionPlan" data-confirm="true" title="<?php _e( 'Delete', 'wp-full-stripe' ); ?>">
											<i class="fa fa-trash-o fa-fw"></i>
										</button>
									</span>
								</td>
								<td class="column-id_display_name">
									<b><?php echo esc_html( $plan->id ); ?></b><br>
									<a href="<?php echo add_query_arg( array(
										'page' => 'fullstripe-edit-plan',
										'plan' => rawurlencode( $plan->id )
									), admin_url( 'admin.php' ) ); ?>"><?php echo esc_html( $plan->name ); ?></a>
								</td>
								<td class="column-amount_interval">
									<b><?php echo esc_html( sprintf( '%s%0.2f', $currencySymbol, $plan->amount / 100.0 ) ); ?></b><br>
									<?php if ( $plan->interval_count == 1 ): ?>
										<?php // todo tnagy make invervals localizable ?>
										<?php echo esc_html( ucfirst( $plan->interval ) . 'ly' ); ?>
									<?php else: ?>
										<?php echo esc_html( "{$plan->interval_count} {$plan->interval}s" ); ?>
									<?php endif; ?>
								</td>
								<td class="column-trial_duration">
									<?php
									if ( isset( $plan->trial_period_days ) ) {
										echo esc_html( sprintf( _n( "%d day", "%d days", $plan->trial_period_days, 'wp-full-stripe' ), $plan->trial_period_days ) );
									} else {
										esc_html_e( 'No Trial', 'wp-full-stripe' );
									}
									?><br>
									<?php
									$duration = __( 'Forever', 'wp-full-stripe' );
									if ( isset( $plan->metadata ) ) {
										if ( isset( $plan->metadata->cancellation_count ) && is_numeric( $plan->metadata->cancellation_count ) ) {
											$cancellation_count = intval( $plan->metadata->cancellation_count );
											if ( $cancellation_count > 0 ) {
												$duration = sprintf( _n( '%d charge', '%d charges', $cancellation_count, 'wp-full-stripe' ), $cancellation_count );
											}
										}
									}
									echo esc_html( $duration );
									?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		<?php elseif ( $active_tab == 'createform' ): ?>
			<div class="" id="createform">
				<?php if ( count( $plans ) === 0 ): ?>
					<p class="alert alert-info">You must have at least one subscription plan created before creating a
						subscription form</p>
				<?php else: ?>
					<form class="form-horizontal" action="" method="POST" id="create-subscription-form">
						<p class="tips"></p>
						<input type="hidden" name="action" value="wp_full_stripe_create_subscripton_form"/>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<label class="control-label">Form Name: </label>
								</th>
								<td>
									<input type="text" class="regular-text" name="form_name" id="form_name" maxlength="<?php echo $form::NAME_LENGTH; ?>">
									<p class="description">This name will be used to identify this form in the shortcode
										i.e. [fullstripe_subscription form="FormName"]</p>
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
									<label class="control-label">Include Coupon Input Field? </label>
								</th>
								<td>
									<label class="radio inline">
										<input type="radio" name="form_include_coupon_input" id="noinclude_coupon_input" value="0" checked="checked">
										No
									</label> <label class="radio inline">
										<input type="radio" name="form_include_coupon_input" id="include_coupon_input" value="1">
										Yes
									</label>
									<p class="description">You can allow customers to input coupon codes for discounts.
										Must create the coupon in your Stripe account dashboard.</p>
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
									<p class="description">Should this form also ask for the customers billing
										address?</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="control-label">Subscribe Button Text: </label>
								</th>
								<td>
									<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="Subscribe" maxlength="<?php echo $form::BUTTON_TITLE_LENGTH; ?>">
									<p class="description">The text on the subscribe button</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="control-label">Setup Fee: </label>
								</th>
								<td>
									<input type="text" class="regular-text" name="form_setup_fee" id="form_setup_fee" value="0">
									<p class="description">Amount to charge the customer to setup the subscription.
										Entering 0 will disable. (in cents. i.e. for $10.00 enter 1000)</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="control-label">Plans: </label>
								</th>
								<td>
									<div class="plan_checkboxes">
										<ul class="plan_checkbox_list">
											<?php $plan_order = array(); ?>
											<?php foreach ( $plans['data'] as $plan ): ?>
												<?php $plan_order[] = $plan->id; ?>
												<li class="ui-state-default" data-toggle="tooltip" title="<?php esc_attr_e( 'You can reorder this list by using drag\'n\'drop.', 'wp-full-stripe' ); ?>" data-plan-id="<?php echo esc_attr( $plan->id ); ?>">
													<label class="checkbox inline">
														<input type="checkbox" class="plan_checkbox" id="check_<?php echo esc_attr( $plan->id ); ?>" value="<?php echo esc_attr( $plan->id ); ?>">
                                        <span class="plan_checkbox_text"><?php echo esc_html( $plan->name ); ?> (
	                                        <?php
	                                        // todo tnagy make invervals localizable
	                                        $str = $currencySymbol . sprintf( '%0.2f', $plan->amount / 100.0 );
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
										</ul>
									</div>
									<p class="description">Which subscription plans can be chosen on this form. The list
										can be reordered by using drag'n'drop.</p>
									<input type="hidden" name="plan_order" value="<?php echo rawurlencode( json_encode( $plan_order ) ); ?>"/>
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
									</label>
									<label class="radio inline">
										<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1"> Yes
									</label>
									<p class="description">When payment is successful you can choose to redirect to
										another page or post</p>
								</td>
							</tr>
							<?php include( 'fragments/redirect_to_for_create.php' ) ?>
							<tr valign="top">
								<th scope="row">
									<label class="control-label">Include Custom Input Fields? </label>
								</th>
								<td>
									<label class="radio inline">
										<input type="radio" name="form_include_custom_input" id="noinclude_custom_input" value="0" checked="checked">
										No
									</label>
									<label class="radio inline">
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
							<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
						</p>
					</form>
				<?php endif; ?>
			</div>
		<?php elseif ( $active_tab == 'createplan' ): ?>
			<div class="" id="createplan">
				<form class="form-horizontal" action="" method="POST" id="create-subscription-plan">
					<p class="tips"></p>
					<input type="hidden" name="action" value="wp_full_stripe_create_plan"/>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label class="control-label">ID: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_id" id="sub_id">
								<p class="description">This ID is used to identify this plan when creating a
									subscription form and on your Stripe dashboard</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Display Name: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_name" id="sub_name">
								<p class="description">The name you wish to be displayed to customers for this plan</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Amount: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_amount" id="sub_amount"/>
								<p class="description">The amount this plan will charge your customer, in cents. i.e.
									for $10.00 enter 1000</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Interval: </label>
							</th>
							<td>
								<select id="sub_interval" name="sub_interval">
									<option value="week">Weekly</option>
									<option value="month">Monthly</option>
									<option value="year">Yearly</option>
								</select>
								<p class="description">How often the payment amount is charged to the customer</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Interval Count: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_interval_count" id="sub_interval_count" value="1"/>
								<p class="description">You could specify an interval count of 3 and an interval of
									'Monthly' for quarterly billing (every 3 months). Default is 1 for
									Weekly/Monthly/Yearly.</p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label class="control-label">Payment Cancellation Count: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_cancellation_count" id="sub_cancellation_count" value="0"/>
								<p class="description">You could specify the number of charges after which the
									subscription is cancelled. Set to 0 to let the subscription run forever.</p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label class="control-label">Trial Period Days: </label>
							</th>
							<td>
								<input type="text" class="regular-text" name="sub_trial" id="sub_trial" value="0"/>
								<p class="description">How many trial days the customer has before being charged. Set to
									0 to disable trial period.</p>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button class="button button-primary" type="submit">Create Plan</button>
						<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
					</p>
				</form>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php

/* @var $plans \Stripe\Collection */
$plans = MM_WPFS::getInstance()->get_plans();
/* @var $plan \Stripe\Plan */
$plan = null;

$plan_id = '';
if ( isset( $_GET['plan'] ) ) {
	$plan_id = $_GET['plan'];
}

$valid = false;
if ( $plan_id == '' ) {
	$valid = false;
} else {
	$plan_id = stripslashes( $plan_id );
	if ( $plans['data'] ) {
		foreach ( $plans['data'] as $a_plan ) {
			if ( $a_plan->id === $plan_id ) {
				$plan  = $a_plan;
				$valid = true;
			}
		}
	} else {
		$valid = false;
	}
}

?>
<div class="wrap">
	<h2> <?php esc_html_e( 'Modify subscription plan', 'wp-full-stripe' ); ?> </h2>

	<div id="updateDiv"><p><strong id="updateMessage"></strong></p></div>
	<?php if ( ! $valid ): ?>
		<p>Plan not found!</p>
	<?php else: ?>
		<form class="form-horizontal" action="" method="POST" id="edit-subscription-plan">
			<p class="tips"></p>
			<input type="hidden" name="action" value="wp_full_stripe_edit_subscription_plan"/>
			<input type="hidden" name="plan" value="<?php echo esc_attr( $plan->id ); ?>">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label class="control-label">ID:</label>
					</th>
					<td>
						<?php echo esc_html( $plan->id ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="control-label">Price:</label>
					</th>
					<td>
						<?php
						$price_label = '';
						if ( $plan->interval_count === 1 ) {
							$price_label = sprintf( __( '%s%0.2f / %s', 'wp-full-stripe' ), MM_WPFS::get_currency_symbol_for( $plan->currency ), $plan->amount / 100.0, $plan->interval );
						} else {
							if ( $plan->interval === 'week' ) {
								$price_label = sprintf( _n( '%s%0.2f / %d week', '%s%0.2f / %d weeks', $plan->interval_count, 'wp-full-stripe' ), MM_WPFS::get_currency_symbol_for( $plan->currency ), $plan->amount / 100.0, $plan->interval_count );
							} else if ( $plan->interval === 'month' ) {
								$price_label = sprintf( _n( '%s%0.2f / %d month', '%s%0.2f / %d months', $plan->interval_count, 'wp-full-stripe' ), MM_WPFS::get_currency_symbol_for( $plan->currency ), $plan->amount / 100.0, $plan->interval_count );
							} else if ( $plan->interval === 'year' ) {
								$price_label = sprintf( _n( '%s%0.2f / %d year', '%s%0.2f / %d years', $plan->interval_count, 'wp-full-stripe' ), MM_WPFS::get_currency_symbol_for( $plan->currency ), $plan->amount / 100.0, $plan->interval_count );
							}
						}
						echo esc_html( $price_label );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="control-label">Duration:</label>
					</th>
					<td>
						<?php
						$duration = __( 'Forever', 'wp-full-stripe' );
						if ( isset( $plan->metadata ) ) {
							if ( isset( $plan->metadata->cancellation_count ) && is_numeric( $plan->metadata->cancellation_count ) ) {
								$cancellation_count = intval( $plan->metadata->cancellation_count );
								if ( $cancellation_count > 0 ) {
									$cardinality = $cancellation_count;
									if ( isset( $plan->interval_count ) && is_numeric( $plan->interval_count ) ) {
										$cardinality = intval( $plan->interval_count ) * $cardinality;
									}
									$by_interval = null;
									if ( $plan->interval === 'week' ) {
										$by_interval = sprintf( _n( '%d week', '%d weeks', $cardinality, 'wp-full-stripe' ), $cardinality );
									} else if ( $plan->interval === 'month' ) {
										$by_interval = sprintf( _n( '%d month', '%d months', $cardinality, 'wp-full-stripe' ), $cardinality );
									} else if ( $plan->interval === 'year' ) {
										$by_interval = sprintf( _n( '%d year', '%d years', $cardinality, 'wp-full-stripe' ), $cardinality );
									}
									if ( empty( $by_interval ) ) {
										$duration = sprintf( _n( '%d charge', '%d charges', $cancellation_count, 'wp-full-stripe' ), $cancellation_count );
									} else {
										$duration = sprintf( _n( '%d charge (%s)', '%d charges (%s)', $cancellation_count, 'wp-full-stripe' ), $cancellation_count, $by_interval );
									}
								}
							} else {
								$duration = $plan->metadata->cancellation_count;
							}
						}
						echo esc_html( $duration );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="control-label">Trial:</label>
					</th>
					<td>
						<?php echo isset( $plan->trial_period_days ) ? esc_html( $plan->trial_period_days . ' days' ) : 'No trial' ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="control-label">Display name:*</label>
					</th>
					<td>
						<input type="text" class="regular-text" name="plan_display_name" id="form_plan_display_name" value="<?php echo esc_attr( $plan->name ); ?>"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="control-label">Statement descriptor:</label>
					</th>
					<td>
						<input type="text" class="regular-text" name="plan_statement_descriptor" id="form_plan_statement_descriptor" value="<?php echo esc_attr( $plan->statement_descriptor ); ?>"/>
					</td>
				</tr>
			</table>

			<p class="submit">
				<button class="button button-primary" type="submit">Modify plan</button>
				<a href="<?php echo admin_url( 'admin.php?page=fullstripe-subscriptions&tab=plans' ); ?>" class="button">Cancel</a>
				<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
			</p>

		</form>
	<?php endif; ?>
</div>
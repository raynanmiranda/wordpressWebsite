<?php

require_once( 'wp-full-stripe-logger-configurator.php' );

interface MM_WPFS_Payment_API {
	function charge( $amount, $card, $description, $metadata = null, $stripeEmail = null );

	function subscribe( $plan, $token, $email, $description, $couponCode, $setupFee, $metadata = null );

	function create_plan( $id, $name, $amount, $interval, $trialDays, $intervalCount, $cancellationCount );

	function get_plans();

	function get_recipients();

	function create_recipient( $recipient );

	function create_transfer( $transfer );

	function get_coupon( $code );

	function create_customer_with_card( $card, $email, $metadata );

	function create_customer_with_source( $token, $email, $metadata );

	function charge_customer( $customerId, $amount, $description, $metadata = null, $stripeEmail = null );

	function retrieve_customer( $customerID );

	function update_customer_card( $customerID, $card );

	function add_customer_source( $customerID, $token );

	function subscribe_existing( $stripeCustomerID, $plan, $token, $couponCode, $setupFee, $metadata = null );

	function retrieve_subscription( $customerID, $subscriptionID );

	function update_plan( $plan_id, $plan_data );

	public function delete_plan( $plan_id );

}

//deals with calls to Stripe API
class MM_WPFS_Stripe implements MM_WPFS_Payment_API {

	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR = 'invalid_number';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_MONTH_ERROR = 'invalid_expiry_month';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_YEAR_ERROR = 'invalid_expiry_year';
	/**
	 * @var string
	 */
	const INVALID_CVC_ERROR = 'invalid_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_NUMBER_ERROR = 'incorrect_number';
	/**
	 * @var string
	 */
	const EXPIRED_CARD_ERROR = 'expired_card';
	/**
	 * @var string
	 */
	const INCORRECT_CVC_ERROR = 'incorrect_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_ZIP_ERROR = 'incorrect_zip';
	/**
	 * @var string
	 */
	const CARD_DECLINED_ERROR = 'card_declined';
	/**
	 * @var string
	 */
	const MISSING_ERROR = 'missing';
	/**
	 * @var string
	 */
	const PROCESSING_ERROR = 'processing_error';

	private $log;

	public function __construct() {
		$this->log = Logger::getLogger( "WPFS" );
	}

	function get_error_codes() {
		return array(
			self::INVALID_NUMBER_ERROR,
			self::INVALID_EXPIRY_MONTH_ERROR,
			self::INVALID_EXPIRY_YEAR_ERROR,
			self::INVALID_CVC_ERROR,
			self::INCORRECT_NUMBER_ERROR,
			self::EXPIRED_CARD_ERROR,
			self::INCORRECT_CVC_ERROR,
			self::INCORRECT_ZIP_ERROR,
			self::CARD_DECLINED_ERROR,
			self::MISSING_ERROR,
			self::PROCESSING_ERROR
		);
	}

	function resolve_error_message_by_code( $code ) {
		if ( $code === self::INVALID_NUMBER_ERROR ) {
			$resolved_message =  /* translators: message for Stripe error code 'invalid_number' */
				__( 'Your card number is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_EXPIRY_MONTH_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_month' */
				__( 'Your card\'s expiration month is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_EXPIRY_YEAR_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_year' */
				__( 'Your card\'s expiration year is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_cvc' */
				__( 'Your card\'s security code is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_NUMBER_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_number' */
				__( 'Your card number is incorrect.', 'wp-full-stripe' );
		} elseif ( $code === self::EXPIRED_CARD_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'expired_card' */
				__( 'Your card has expired.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_cvc' */
				__( 'Your card\'s security code is incorrect.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_ZIP_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_zip' */
				__( 'Your card\'s zip code failed validation.', 'wp-full-stripe' );
		} elseif ( $code === self::CARD_DECLINED_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'card_declined' */
				__( 'Your card was declined.', 'wp-full-stripe' );
		} elseif ( $code === self::MISSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'missing' */
				__( 'There is no card on a customer that is being charged.', 'wp-full-stripe' );
		} elseif ( $code === self::PROCESSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'processing_error' */
				__( 'An error occurred while processing your card.', 'wp-full-stripe' );
		} else {
			$resolved_message = null;
		}

		return $resolved_message;
	}

	function charge( $amount, $card, $description, $metadata = null, $stripeEmail = null ) {
		$options = get_option( 'fullstripe_options' );

		$charge = array(
			'card'        => $card,
			'amount'      => $amount,
			'currency'    => $options['currency'],
			'description' => $description
		);
		if ( isset( $stripeEmail ) ) {
			$charge['receipt_email'] = $stripeEmail;
		}
		if ( isset( $metadata ) ) {
			$charge['metadata'] = $metadata;
		}

		$result = \Stripe\Charge::create( $charge );

		return $result;
	}

	function subscribe( $plan, $token, $email, $description, $couponCode, $setupFee, $metadata = null ) {
		$data = array(
			"card"        => $token,
			"plan"        => $plan,
			"email"       => $email,
			"description" => $description
		);

		if ( $couponCode != '' ) {
			$data["coupon"] = $couponCode;
		}

		if ( $metadata ) {
			$data['metadata'] = $metadata;
		}

		if ( $setupFee != 0 ) {
			$data['account_balance'] = $setupFee;
		}

		$customer = \Stripe\Customer::create( $data );

		return $customer;
	}

	// Add subscription to existing customer
	function subscribe_existing( $stripeCustomerID, $plan, $token, $couponCode, $setupFee, $metadata = null ) {
		$data = array(
			"card" => $token,
			"plan" => $plan
		);

		if ( $couponCode != '' ) {
			$data["coupon"] = $couponCode;
		}

		if ( $metadata ) {
			$data['metadata'] = $metadata;
		}

		$stripeCustomer = \Stripe\Customer::retrieve( $stripeCustomerID );
		if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
			// account balances can only be added to customer objects, not subscriptions, so we must add it first
			if ( $setupFee != 0 ) {
				$stripeCustomer->account_balance = $setupFee;
				$stripeCustomer->save();
			}

			// Now create the subscription
			$sub = $stripeCustomer->subscriptions->create( $data );
		} else {
			throw new Exception( "Stripe customer with id '" . $stripeCustomerID . "' doesn't exist." );
		}

		return $sub;
	}


	function create_plan( $id, $name, $amount, $interval, $trialDays, $intervalCount, $cancellationCount ) {
		$options = get_option( 'fullstripe_options' );

		try {
			$planData = array(
				"amount"         => $amount,
				"interval"       => $interval,
				"name"           => $name,
				"currency"       => $options['currency'],
				"interval_count" => $intervalCount,
				"id"             => $id,
				"metadata"       => array(
					"cancellation_count" => $cancellationCount
				)
			);

			if ( $trialDays != 0 ) {
				$planData['trial_period_days'] = $trialDays;
			}

			do_action( 'fullstripe_before_create_plan', $planData );
			\Stripe\Plan::create( $planData );
			do_action( 'fullstripe_after_create_plan' );

			$return = array( 'success' => true, 'msg' => __( 'Subscription plan created ', 'wp-full-stripe' ) );
		} catch ( Exception $e ) {
			//show notification of error
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error creating the plan: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		return $return;
	}

	function retrieve_plan( $planID ) {
		try {
			$plan = \Stripe\Plan::retrieve( $planID );
		} catch ( Exception $ex ) {
			$plan = null;
		}

		return $plan;
	}

	/**
	 * @return array|\Stripe\Collection
	 */
	public function get_plans() {
		try {
			$plans = \Stripe\Plan::all( array( 'count' => 100 ) );
		} catch ( Exception $e ) {
			$plans = array();
		}

		return $plans;
	}

	function get_recipients() {
		try {
			$recipients = \Stripe\Recipient::all();
		} catch ( Exception $e ) {
			$recipients = array();
		}

		return $recipients;
	}

	function create_recipient( $recipient ) {
		return \Stripe\Recipient::create( $recipient );
	}

	function create_transfer( $transfer ) {
		return \Stripe\Transfer::create( $transfer );
	}

	function get_coupon( $code ) {
		return \Stripe\Coupon::retrieve( $code );
	}

	/**
	 * @deprecated
	 *
	 * @param $card
	 * @param $email
	 * @param $metadata
	 *
	 * @return \Stripe\Customer
	 */
	function create_customer_with_card( $card, $email, $metadata ) {
		$customer = array(
			"card"     => $card,
			"email"    => $email,
			"metadata" => $metadata
		);

		return \Stripe\Customer::create( $customer );
	}

	function create_customer_with_source( $token, $email, $metadata ) {
		$customer = array(
			"source" => $token,
			"email"  => $email
		);

		if ( $metadata ) {
			$customer['metadata'] = $metadata;
		}

		return \Stripe\Customer::create( $customer );
	}

	function charge_customer( $customerId, $amount, $description, $metadata = null, $stripeEmail = null ) {
		$options = get_option( 'fullstripe_options' );

		$charge_parameters = array(
			'customer'    => $customerId,
			'amount'      => $amount,
			'currency'    => $options['currency'],
			'description' => $description
		);
		if ( isset( $stripeEmail ) ) {
			$charge_parameters['receipt_email'] = $stripeEmail;
		}
		if ( isset( $metadata ) ) {
			$charge_parameters['metadata'] = $metadata;
		}

		$charge = \Stripe\Charge::create( $charge_parameters );

		return $charge;
	}

	function retrieve_customer( $customerID ) {
		return \Stripe\Customer::retrieve( $customerID );
	}

	/**
	 * @deprecated
	 *
	 * @param $customerID
	 * @param $card
	 *
	 * @return \Stripe\Customer
	 */
	function update_customer_card( $customerID, $card ) {
		$cu       = \Stripe\Customer::retrieve( $customerID );
		$cu->card = $card;
		$cu->save();

		return \Stripe\Customer::retrieve( $customerID );
	}

	function add_customer_source( $customerID, $token ) {
		$cu         = \Stripe\Customer::retrieve( $customerID );
		$cu->source = $token;
		$cu->save();

		return \Stripe\Customer::retrieve( $customerID );
	}

	function retrieve_subscription( $customerID, $subscriptionID ) {
		$cu = \Stripe\Customer::retrieve( $customerID );

		return $cu->subscriptions->retrieve( $subscriptionID );
	}

	function update_plan( $plan_id, $plan_data ) {
		if ( isset( $plan_id ) ) {
			$plan = \Stripe\Plan::retrieve( $plan_id );
			if ( isset( $plan_data ) ) {
				if ( array_key_exists( 'name', $plan_data ) && ! empty( $plan_data['name'] ) ) {
					$plan->name = $plan_data['name'];
				}
				if ( array_key_exists( 'statement_descriptor', $plan_data ) && ! empty( $plan_data['statement_descriptor'] ) ) {
					$plan->statement_descriptor = $plan_data['statement_descriptor'];
				} else {
					$plan->statement_descriptor = null;
				}

				return $plan->save();
			}
		}

		return null;
	}

	public function delete_plan( $plan_id ) {
		if ( isset( $plan_id ) ) {
			$plan = \Stripe\Plan::retrieve( $plan_id );

			return $plan->delete();
		}

		return null;
	}

	public function cancel_subscription( $stripeCustomerID, $stripeSubscriptionID, $atPeriodEnd = false ) {
		if ( isset( $stripeCustomerID ) && isset( $stripeSubscriptionID ) ) {
			if ( ! empty( $stripeCustomerID ) && ! empty( $stripeSubscriptionID ) ) {
				$subscription = $this->retrieve_subscription( $stripeCustomerID, $stripeSubscriptionID );
				if ( $subscription ) {
					$cancellation_result = $subscription->cancel( array( "at_period_end" => $atPeriodEnd ) );
				}
			}
		}
	}

}
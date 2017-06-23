<?php

require_once( 'wp-full-stripe-logger-configurator.php' );

/**
 * Class MM_WPFS_Customer deals with customer front-end input i.e. payment forms submission
 */
class MM_WPFS_Customer {

	/* @var $stripe MM_WPFS_Stripe */
	private $stripe = null;

	/* @var $db MM_WPFS_Database */
	private $db = null;

	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	private $log;

	public function __construct() {
		$this->stripe = new MM_WPFS_Stripe();
		$this->db     = new MM_WPFS_Database();
		$this->mailer = new MM_WPFS_Mailer();
		$this->hooks();

		$this->log = Logger::getLogger( "WPFS" );
	}

	private function hooks() {
		add_action( 'wp_ajax_wp_full_stripe_payment_charge', array( $this, 'fullstripe_payment_charge' ) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_payment_charge', array( $this, 'fullstripe_payment_charge' ) );
		add_action( 'wp_ajax_wp_full_stripe_subscription_charge', array( $this, 'fullstripe_subscription_charge' ) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_subscription_charge', array(
			$this,
			'fullstripe_subscription_charge'
		) );
		add_action( 'wp_ajax_wp_full_stripe_check_coupon', array( $this, 'fullstripe_check_coupon' ) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_check_coupon', array( $this, 'fullstripe_check_coupon' ) );
		add_action( 'wp_ajax_fullstripe_checkout_form_charge', array( $this, 'fullstripe_checkout_charge' ) );
		add_action( 'wp_ajax_nopriv_fullstripe_checkout_form_charge', array( $this, 'fullstripe_checkout_charge' ) );
	}

	function fullstripe_payment_charge() {

		//get POST data from form
		$valid                = true;
		$stripeToken          = $_POST['stripeToken'];
		$name                 = sanitize_text_field( $_POST['fullstripe_name'] );
		$amount               = $_POST['amount'];
		$formId               = isset( $_POST['formId'] ) && is_numeric( $_POST['formId'] ) ? $_POST['formId'] : null;
		$formName             = $_POST['formName'];
		$customAmount         = $_POST['customAmount'];
		$doRedirect           = $_POST['formDoRedirect'];
		$redirectPostID       = $_POST['formRedirectPostID'];
		$redirectUrl          = $_POST['formRedirectUrl'];
		$redirectToPageOrPost = $_POST['formRedirectToPageOrPost'];
		$showAddress          = $_POST['showAddress'];
		$sendReceipt          = $_POST['sendEmailReceipt'];
		$options              = get_option( 'fullstripe_options' );
		$productName          = '';
		$currencySymbol       = MM_WPFS::get_currency_symbol_for( $options['currency'] );

		if ( $customAmount == 'custom_amount' || $customAmount == 'list_of_amounts' ) {
			$amount = $_POST['fullstripe_custom_amount'];
			if ( ! is_numeric( $amount ) ) {
				$valid  = false;
				$return = array(
					'success' => false,
					'msg'     => __( 'The payment amount is invalid, please only use numbers and a decimal point.', 'wp-full-stripe' )
				);
			} else {
				$amount = $amount * 100; //Stripe expects amounts in cents/pence
			}
		}

		if ( $customAmount == 'list_of_amounts' ) {
			$payment_form = $this->db->get_payment_form_by_name( $formName );
			if ( isset( $payment_form ) ) {

			}
			$list_of_amounts = json_decode( $payment_form->listOfAmounts );
			$first_amount    = null;
			foreach ( $list_of_amounts as $list_element ) {
				$list_element_amount      = $list_element[0];
				$list_element_description = $list_element[1];
				if ( $amount == $list_element_amount ) {
					$list_element_amount_label      = sprintf( "%s %0.2f", $currencySymbol, ( $list_element_amount / 100 ) );
					$list_element_description_label = MM_WPFS::translate_label( $list_element_description );
					if ( strpos( $list_element_description, '{amount}' ) !== false ) {
						$list_element_description_label = str_replace( '{amount}', $list_element_amount_label, $list_element_description_label );
					}
					$productName = $list_element_description_label;
				}
			}
		}

		$address1 = isset( $_POST['fullstripe_address_line1'] ) ? sanitize_text_field( $_POST['fullstripe_address_line1'] ) : '';
		$address2 = isset( $_POST['fullstripe_address_line2'] ) ? sanitize_text_field( $_POST['fullstripe_address_line2'] ) : '';
		$city     = isset( $_POST['fullstripe_address_city'] ) ? sanitize_text_field( $_POST['fullstripe_address_city'] ) : '';
		$state    = isset( $_POST['fullstripe_address_state'] ) ? sanitize_text_field( $_POST['fullstripe_address_state'] ) : '';
		$country  = isset( $_POST['fullstripe_address_country'] ) ? MM_WPFS::get_country_name_for( sanitize_text_field( $_POST['fullstripe_address_country'] ) ) : '';
		$zip      = isset( $_POST['fullstripe_address_zip'] ) ? sanitize_text_field( $_POST['fullstripe_address_zip'] ) : '';

		if ( $showAddress == 1 ) {
			$valid = $this->is_valid_address( $address1, $city, $zip, $country );
			if ( ! $valid ) {
				$return = array(
					'success' => false,
					'msg'     => __( 'Please enter a valid billing address.', 'wp-full-stripe' )
				);
			}
		}

		$email = '';
		if ( isset( $_POST['fullstripe_email'] ) ) {
			$email = $_POST['fullstripe_email'];
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$valid  = false;
				$return = array(
					'success' => false,
					'msg'     => __( 'Please enter a valid email address.', 'wp-full-stripe' )
				);
			}
		} else {
			$valid  = false;
			$return = array(
				'success' => false,
				'msg'     => __( 'Please enter a valid email address.', 'wp-full-stripe' )
			);
		}

		if ( $valid ) {
			$customInputs      = isset( $_POST['customInputs'] ) ? $_POST['customInputs'] : null;
			$customInputValues = isset( $_POST['fullstripe_custom_input'] ) ? $_POST['fullstripe_custom_input'] : array();
			$description       = "Payment from $name on form: $formName";
			$metadata          = array(
				'customer_name'           => $name,
				'customer_email'          => $email,
				'billing_address_line1'   => $address1,
				'billing_address_line2'   => $address2,
				'billing_address_city'    => $city,
				'billing_address_state'   => $state,
				'billing_address_country' => $country,
				'billing_address_zip'     => $zip
			);

			try {
				//check email
				$sendPluginEmail = true;
				if ( $options['receiptEmailType'] == 'stripe' && $sendReceipt == 1 && isset( $_POST['fullstripe_email'] ) ) {
					$sendPluginEmail = false;
				}

				do_action( 'fullstripe_before_payment_charge', $amount );
				//create/get customer object
				$stripeCustomer = $this->create_or_get_customer( $stripeToken, $email, $metadata, ( $options['apiMode'] === 'live' ) );
				//try the charge
				$metadata            = $this->add_custom_inputs( $metadata, $customInputs, $customInputValues );
				$charge              = $this->stripe->charge_customer( $stripeCustomer->id, $amount, $description, $metadata, ( $sendPluginEmail == false && $sendReceipt == true ? $email : null ) );
				$charge['wpfs_form'] = $formName;
				do_action( 'fullstripe_after_payment_charge', $charge );

				//save the payment
				$address = array(
					'line1'   => $address1,
					'line2'   => $address2,
					'city'    => $city,
					'state'   => $state,
					'country' => $country,
					'zip'     => $zip
				);
				$this->db->fullstripe_insert_payment( $charge, $address, $stripeCustomer->id, $name, $email, $formId, /* form_type */
					'payment' );

				$return = array( 'success' => true, 'msg' => __( 'Payment Successful!', 'wp-full-stripe' ) );
				if ( $doRedirect == 1 ) {
					if ( $redirectToPageOrPost == 1 ) {
						if ( $redirectPostID != 0 ) {
							$return['redirect']    = true;
							$return['redirectURL'] = get_page_link( $redirectPostID );
						} else {
							$this->log->error( "Inconsistent form data: formName=$formName, doRedirect=$doRedirect, redirectPostID=$redirectPostID" );
						}
					} else {
						$return['redirect']    = true;
						$return['redirectURL'] = $redirectUrl;
					}
				}

				//send email receipt (it is better if done in a background thread...)
				if ( $sendPluginEmail && $sendReceipt == 1 && isset( $_POST['fullstripe_email'] ) ) {
					$this->mailer->send_payment_email_receipt( $email, $amount, $charge['source']['name'], $address, $productName, $customInputValues );
				}

			} catch ( \Stripe\Error\Card $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				$message = $this->stripe->resolve_error_message_by_code( $e->getCode() );
				if ( is_null( $message ) ) {
					$message = MM_WPFS::translate_label( $e->getMessage() );
				}
				$return = array(
					'success' => false,
					'msg'     => $message,
					'ex_msg'  => $e->getMessage()
				);
			} catch ( Exception $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				//show notification of error
				$return = array(
					'success' => false,
					'msg'     => MM_WPFS::translate_label( $e->getMessage() ),
					'ex_msg'  => $e->getMessage()
				);
			}
		}

		//correct way to return JS results in wordpress
		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_payment_charge_return_message', $return ) );
		exit;
	}

	private function create_or_get_customer( $token, $email, $metadata, $livemode = true ) {
		$customer = $this->find_existing_stripe_customer_by_email( $email, $livemode );

		if ( ! isset( $customer ) ) {
			return $this->stripe->create_customer_with_source( $token, $email, $metadata );
		} else {
			// update and return existing customer to charge
			return $this->stripe->add_customer_source( $customer['stripeCustomerID'], $token );
		}
	}

	private function find_existing_stripe_customer_by_email( $email, $livemode ) {
		$customers = $this->db->get_existing_stripe_customers_by_email( $email, $livemode );

		$res = null;
		foreach ( $customers as $customer ) {
			$stripeCustomer = null;
			try {
				$stripeCustomer = $this->stripe->retrieve_customer( $customer['stripeCustomerID'] );
			} catch ( Exception $ex ) {
				//-- Let it just fall through, we will check for isset below
			}

			if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
				$res = $customer;
				break;
			}
		}

		return $res;
	}

	/**
	 * Insert the inputs into the metadata
	 *
	 * @param $metadata
	 * @param $customInputs
	 * @param $customInputValues
	 *
	 * @return mixed
	 */
	private function add_custom_inputs( $metadata, $customInputs, $customInputValues ) {
		// if not set, it's the old version with just one value
		if ( $customInputs == null ) {
			$metadata['custom_input'] = $customInputValues;
		} else {
			$labels = explode( '{{', $customInputs );
			foreach ( $labels as $i => $label ) {
				$metadata[ $label ] = $customInputValues[ $i ];
			}
		}

		return $metadata;
	}

	function fullstripe_subscription_charge() {

		$card                       = $_POST['stripeToken'];
		$name                       = sanitize_text_field( $_POST['fullstripe_name'] );
		$planID                     = stripslashes( html_entity_decode( $_POST['fullstripe_plan'] ) );
		$formId                     = isset( $_POST['formId'] ) && is_numeric( $_POST['formId'] ) ? $_POST['formId'] : null;
		$formName                   = isset( $_POST['formName'] ) ? $_POST['formName'] : null;
		$customInputs               = isset( $_POST['customInputs'] ) ? $_POST['customInputs'] : null;
		$customInputValues          = isset( $_POST['fullstripe_custom_input'] ) ? $_POST['fullstripe_custom_input'] : array();
		$couponCode                 = isset( $_POST['fullstripe_coupon_input'] ) ? $_POST['fullstripe_coupon_input'] : '';
		$amount_with_coupon_applied = isset( $_POST['amount_with_coupon_applied'] ) && is_numeric( $_POST['amount_with_coupon_applied'] ) ? $_POST['amount_with_coupon_applied'] : null;
		$doRedirect                 = $_POST['formDoRedirect'];
		$redirectPostID             = $_POST['formRedirectPostID'];
		$redirectUrl                = $_POST['formRedirectUrl'];
		$redirectToPageOrPost       = $_POST['formRedirectToPageOrPost'];
		$showAddress                = $_POST['showAddress'];
		$address1                   = isset( $_POST['fullstripe_address_line1'] ) ? sanitize_text_field( $_POST['fullstripe_address_line1'] ) : '';
		$address2                   = isset( $_POST['fullstripe_address_line2'] ) ? sanitize_text_field( $_POST['fullstripe_address_line2'] ) : '';
		$city                       = isset( $_POST['fullstripe_address_city'] ) ? sanitize_text_field( $_POST['fullstripe_address_city'] ) : '';
		$state                      = isset( $_POST['fullstripe_address_state'] ) ? sanitize_text_field( $_POST['fullstripe_address_state'] ) : '';
		$country                    = isset( $_POST['fullstripe_address_country'] ) ? MM_WPFS::get_country_name_for( sanitize_text_field( $_POST['fullstripe_address_country'] ) ) : '';
		$zip                        = isset( $_POST['fullstripe_address_zip'] ) ? sanitize_text_field( $_POST['fullstripe_address_zip'] ) : '';
		$setupFee                   = isset( $_POST['fullstripe_setupFee'] ) && is_numeric( trim( $_POST['fullstripe_setupFee'] ) ) ? intval( trim( $_POST['fullstripe_setupFee'] ) ) : null;
		$sendReceipt                = $_POST['sendEmailReceipt'];
		$options                    = get_option( 'fullstripe_options' );
		$productName                = '';

		//validation
		$valid = true;

		if ( $showAddress == 1 ) {
			$valid = $this->is_valid_address( $address1, $city, $zip, $country );
			if ( ! $valid ) {
				$return = array(
					'success' => false,
					'msg'     => __( 'Please enter a valid billing address.', 'wp-full-stripe' )
				);
			}
		}

		$email = '';
		if ( isset( $_POST['fullstripe_email'] ) ) {
			$email = $_POST['fullstripe_email'];
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$valid  = false;
				$return = array(
					'success' => false,
					'msg'     => __( 'Please enter a valid email address.', 'wp-full-stripe' )
				);
			}
		} else {
			$valid  = false;
			$return = array(
				'success' => false,
				'msg'     => __( 'Please enter a valid email address.', 'wp-full-stripe' )
			);
		}

		if ( $valid ) {
			$description = "Subscriber: " . $name;
			$metadata    = array(
				'customer_name'           => $name,
				'customer_email'          => $email,
				'billing_address_line1'   => $address1,
				'billing_address_line2'   => $address2,
				'billing_address_city'    => $city,
				'billing_address_state'   => $state,
				'billing_address_country' => $country,
				'billing_address_zip'     => $zip,
			);
			$metadata    = $this->add_custom_inputs( $metadata, $customInputs, $customInputValues );

			try {
				$sendPluginEmail = true;
				if ( $options['receiptEmailType'] == 'stripe' && $sendReceipt == 1 ) {
					$sendPluginEmail = false;
				}

				// Check if we already have a customer created from a previous time
				$stripeCustomer = $this->find_existing_stripe_customer_by_email( $email, ( $options['apiMode'] === 'live' ) );
				do_action( 'fullstripe_before_subscription_charge', $planID );

				$address = array(
					'line1'   => $address1,
					'line2'   => $address2,
					'city'    => $city,
					'state'   => $state,
					'country' => $country,
					'zip'     => $zip
				);

				if ( $stripeCustomer && $stripeCustomer['stripeCustomerID'] ) {
					$subscription = $this->stripe->subscribe_existing( $stripeCustomer['stripeCustomerID'], $planID, $card, $couponCode, $setupFee, $metadata );
					$customer     = $this->stripe->retrieve_customer( $stripeCustomer['stripeCustomerID'] );
					$customer     = $this->include_customer_subscription( $customer );
					$this->db->fullstripe_insert_subscriber( $customer, $name, $address, $formId );
				} else {
					$customer = $this->stripe->subscribe( $planID, $card, $email, $description, $couponCode, $setupFee, $metadata );
					$customer = $this->include_customer_subscription( $customer );
					$this->db->fullstripe_insert_subscriber( $customer, $name, $address, $formId );
				}

				// Do our after subscription action with the Stripe customer so other plugins can hook in
				do_action( 'fullstripe_after_subscription_charge', $customer );

				$return = array(
					'success' => true,
					'msg'     => __( 'Payment Successful. Thanks for subscribing!', 'wp-full-stripe' )
				);
				if ( $doRedirect == 1 ) {
					if ( $redirectToPageOrPost == 1 ) {
						if ( $redirectPostID != 0 ) {
							$return['redirect']    = true;
							$return['redirectURL'] = get_page_link( $redirectPostID );
						} else {
							$this->log->error( "Inconsistent form data: formName=$formName, doRedirect=$doRedirect, redirectPostID=$redirectPostID" );
						}
					} else {
						$return['redirect']    = true;
						$return['redirectURL'] = $redirectUrl;
					}
				}

				$plan = $this->stripe->retrieve_plan( $planID );

				//send email receipt (it is better if done in a background thread...)
				if ( $sendPluginEmail && $sendReceipt == 1 ) {
					$this->mailer->send_subscription_started_email_receipt( $email, $setupFee, $plan['name'], is_null( $amount_with_coupon_applied ) ? $plan['amount'] : $amount_with_coupon_applied, $name, $address, $productName, $customInputValues );
				}
			} catch ( \Stripe\Error\Card $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				$message = $this->stripe->resolve_error_message_by_code( $e->getCode() );
				if ( is_null( $message ) ) {
					$message = MM_WPFS::translate_label( $e->getMessage() );
				}
				$return = array(
					'success' => false,
					'msg'     => $message,
					'ex_msg'  => $e->getMessage()
				);
			} catch ( Exception $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				//show notification of error
				$return = array(
					'success' => false,
					'msg'     => MM_WPFS::translate_label( $e->getMessage() ),
					'ex_msg'  => $e->getMessage()
				);
			}
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_subscription_charge_return_message', $return ) );
		exit;
	}

	/**
	 * In later versions of the Stripe API, the subscription property is removed so we must create it ourselves for compatibility
	 *
	 * @param $customer
	 *
	 * @return mixed
	 */
	private function include_customer_subscription( $customer ) {
		// the value is already set meaning user has Stripe API version 2013-02-13 or older
		if ( isset( $customer->subscription ) ) {
			return $customer;
		}

		//  get the first item from the subscriptions data as the most recently added
		$customer->subscription = $customer->subscriptions->data[0];

		return $customer;
	}

	function fullstripe_checkout_charge() {

		//get POST data from form
		$token                = $_POST['stripeToken'];
		$form_name            = sanitize_text_field( $_POST['name'] );
		$email                = isset( $_POST['stripeEmail'] ) ? sanitize_text_field( $_POST['stripeEmail'] ) : null;
		$form                 = $_POST['form'];
		$doRedirect           = $_POST['doRedirect'];
		$redirectPostID       = $_POST['redirectId'];
		$redirectUrl          = $_POST['redirectUrl'];
		$redirectToPageOrPost = $_POST['redirectToPageOrPost'];
		$showBillingAddress   = $_POST['showBillingAddress'];
		$sendReceipt          = $_POST['sendEmailReceipt'];
		$useBitcoin           = $_POST['useBitcoin'];

		//get form
		$formData    = $this->db->get_checkout_form_by_name( $form );
		$amount      = $formData["amount"];
		$formId      = isset( $_POST['formId'] ) && is_numeric( $_POST['formId'] ) ? $_POST['formId'] : null;
		$description = "Payment for " . $formData["productDesc"];
		$options     = get_option( 'fullstripe_options' );

		$billingName           = isset( $_POST['billing_name'] ) ? sanitize_text_field( $_POST['billing_name'] ) : null;
		$billingAddressCountry = isset( $_POST['billing_address_country'] ) ? MM_WPFS::get_country_name_for( sanitize_text_field( $_POST['billing_address_country'] ) ) : null;
		$billingAddressZip     = isset( $_POST['billing_address_zip'] ) ? sanitize_text_field( $_POST['billing_address_zip'] ) : null;
		$billingAddressState   = isset( $_POST['billing_address_state'] ) ? sanitize_text_field( $_POST['billing_address_state'] ) : null;
		$billingAddressLine1   = isset( $_POST['billing_address_line1'] ) ? sanitize_text_field( $_POST['billing_address_line1'] ) : null;
		$billingAddressCity    = isset( $_POST['billing_address_city'] ) ? sanitize_text_field( $_POST['billing_address_city'] ) : null;

		$productName = '';

		$valid = true;
		if ( $showBillingAddress == 1 ) {
			$valid = $this->is_valid_address( $billingAddressLine1, $billingAddressCity, $billingAddressZip, $billingAddressCountry );
			if ( ! $valid ) {
				$return = array(
					'success' => false,
					'msg'     => __( 'Please enter a valid billing address.', 'wp-full-stripe' )
				);
			}
		}

		if ( $valid ) {
			try {

				//check email
				$sendPluginEmail = true;
				if ( $options['receiptEmailType'] == 'stripe' && $sendReceipt == 1 && isset( $_POST['stripeEmail'] ) ) {
					$sendPluginEmail = false;
				}

				do_action( 'fullstripe_before_checkout_payment_charge', $amount );
				//create/get customer object
				$stripeCustomer = $this->create_or_get_customer( $token, $email, null, ( $options['apiMode'] === 'live' ) );

				//try the charge
				$metadata = array(
					'customer_email'          => $email,
					'billing_name'            => $billingName,
					'billing_address_line1'   => $billingAddressLine1,
					'billing_address_city'    => $billingAddressCity,
					'billing_address_state'   => $billingAddressState,
					'billing_address_zip'     => $billingAddressZip,
					'billing_address_country' => $billingAddressCountry
				);
				$charge   = $this->stripe->charge_customer( $stripeCustomer->id, $amount, $description, $metadata );

				$charge['wpfs_form'] = $formData["name"];
				do_action( 'fullstripe_after_checkout_payment_charge', $charge );

				//save the payment
				$address1 = '';
				$address2 = '';
				$city     = '';
				$state    = '';
				$country  = '';
				$zip      = '';

				if ( $showBillingAddress == 1 ) {
					$address1 = $billingAddressLine1 != null ? $billingAddressLine1 : '';
					$address2 = '';
					$city     = $billingAddressCity != null ? $billingAddressCity : '';
					$state    = $billingAddressState != null ? $billingAddressState : '';
					$country  = $billingAddressCountry != null ? $billingAddressCountry : '';
					$zip      = $billingAddressZip != null ? $billingAddressZip : '';
				}

				$address = array(
					'line1'   => $address1,
					'line2'   => $address2,
					'city'    => $city,
					'state'   => $state,
					'country' => $country,
					'zip'     => $zip
				);
				$this->db->fullstripe_insert_payment( $charge, $address, $stripeCustomer->id, null, $email, $formId, /* form_type */
					'checkout' );

				$return = array( 'success' => true, 'msg' => __( 'Payment Successful!', 'wp-full-stripe' ) );
				if ( $doRedirect == 1 ) {
					if ( $redirectToPageOrPost == 1 ) {
						if ( $redirectPostID != 0 ) {
							$return['redirect']    = true;
							$return['redirectURL'] = get_page_link( $redirectPostID );
						} else {
							$this->log->error( "Inconsistent form data: formName=$form, doRedirect=$doRedirect, redirectPostID=$redirectPostID" );
						}
					} else {
						$return['redirect']    = true;
						$return['redirectURL'] = $redirectUrl;
					}
				}

				//send email receipt (it is better if done in a background thread...)
				if ( $sendPluginEmail && $sendReceipt == 1 && isset( $_POST['stripeEmail'] ) ) {
					$this->mailer->send_payment_email_receipt( $email, $amount, $billingName != null ? $billingName : '', $address, $productName );
				}

			} catch ( \Stripe\Error\Card $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				$message = $this->stripe->resolve_error_message_by_code( $e->getCode() );
				if ( is_null( $message ) ) {
					$message = MM_WPFS::translate_label( $e->getMessage() );
				}
				$return = array(
					'success' => false,
					'msg'     => $message,
					'ex_msg'  => $e->getMessage()
				);
			} catch ( Exception $e ) {
				$errorMessage = sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() );
				error_log( $errorMessage );
				$this->log->error( $errorMessage );
				//show notification of error
				$return = array(
					'success' => false,
					'msg'     => MM_WPFS::translate_label( $e->getMessage() ),
					'ex_msg'  => $e->getMessage()
				);
			}
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_checkout_charge_return_message', $return ) );
		exit;
	}

	function fullstripe_check_coupon() {
		$code = $_POST['code'];

		try {
			$coupon = $this->stripe->get_coupon( $code );

			if ( $coupon->valid == false ) {
				$return = array( 'msg' => __( 'This coupon has expired.', 'wp-full-stripe' ), 'valid' => false );
			} else {
				$return = array(
					'msg'    => __( 'The coupon has been applied successfully.', 'wp-full-stripe' ),
					'coupon' => array( 'percent_off' => $coupon->percent_off, 'amount_off' => $coupon->amount_off ),
					'valid'  => true
				);
			}
		} catch ( Exception $e ) {
			$this->log->error( sprintf( 'Message=%s, Stack=%s ', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'msg'   => __( 'You have entered an invalid coupon code.', 'wp-full-stripe' ),
				'valid' => false
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	private function is_valid_address( $address1, $city, $zip, $country ) {
		$valid = true;
		if ( $address1 == '' || $city == '' || $zip == '' || $country == '' ) {
			$valid = false;
		}

		return $valid;
	}

}
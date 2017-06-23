<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.02.26.
 * Time: 14:16
 */
class MM_WPFS_Mailer {

	public function send_email( $email, $subject, $message ) {
		$options = get_option( 'fullstripe_options' );

		$name = html_entity_decode( get_bloginfo( 'name' ) );

		$admin_email  = get_bloginfo( 'admin_email' );
		$sender_email = $admin_email;
		if ( isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
			$sender_email = $options['email_receipt_sender_address'];
		}
		$headers[] = "From: $name <$sender_email>";

		$headers[] = "Content-type: text/html";

		wp_mail( $email,
			apply_filters( 'fullstripe_email_subject_filter', $subject ),
			apply_filters( 'fullstripe_email_message_filter', $message ),
			apply_filters( 'fullstripe_email_headers_filter', $headers ) );

		if ( $options['admin_payment_receipt'] == 'website_admin' || $options['admin_payment_receipt'] == 'sender_address' ) {
			$receipt_to = $admin_email;
			if ( $options['admin_payment_receipt'] == 'sender_address' && isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
				$receipt_to = $options['email_receipt_sender_address'];
			}
			wp_mail( $receipt_to,
				"COPY: " . apply_filters( 'fullstripe_email_subject_filter', $subject ),
				apply_filters( 'fullstripe_email_message_filter', $message ),
				apply_filters( 'fullstripe_email_headers_filter', $headers ) );
		}
	}

	public function send_payment_email_receipt( $email, $amount, $billingName, $billingAddress, $productName, $custom_input_values = null ) {

		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options = get_option( 'fullstripe_options' );
		$name    = get_bloginfo( 'name' );

		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->paymentMade->subject;
		$message       = stripslashes( $emailReceipts->paymentMade->html );
		$symbol        = MM_WPFS::get_currency_symbol_for( $options['currency'] );

		$message = str_replace(
			array(
				"%AMOUNT%",
				"%NAME%",
				"%CUSTOMERNAME%",
				"%CUSTOMER_EMAIL%",
				"%ADDRESS1%",
				"%ADDRESS2%",
				"%CITY%",
				"%STATE%",
				"%COUNTRY%",
				"%ZIP%",
				"%PRODUCT_NAME%"
			),
			array(
				$symbol . sprintf( '%0.2f', $amount / 100 ),
				$name,
				$billingName,
				$email,
				$billingAddress['line1'],
				$billingAddress['line2'],
				$billingAddress['city'],
				$billingAddress['state'],
				$billingAddress['country'],
				$billingAddress['zip'],
				$productName
			),
			$message );

		$message = $this->replace_custom_fields( $message, $custom_input_values );

		$this->send_email( $email, $subject, $message );
	}

	public function send_subscription_started_email_receipt( $email, $setupFee, $planName, $planAmount, $cardholderName, $billingAddress, $productName, $custom_input_values = null ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options = get_option( 'fullstripe_options' );
		$name    = get_bloginfo( 'name' );

		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->subscriptionStarted->subject;
		$message       = stripslashes( $emailReceipts->subscriptionStarted->html );
		$symbol        = MM_WPFS::get_currency_symbol_for( $options['currency'] );

		$message = str_replace(
			array(
				"%SETUP_FEE%",
				"%PLAN_NAME%",
				"%PLAN_AMOUNT%",
				"%AMOUNT%",
				"%NAME%",
				"%CUSTOMERNAME%",
				"%CUSTOMER_EMAIL%",
				"%ADDRESS1%",
				"%ADDRESS2%",
				"%CITY%",
				"%STATE%",
				"%COUNTRY%",
				"%ZIP%",
				"%PRODUCT_NAME%"
			),
			array(
				$symbol . sprintf( '%0.2f', $setupFee / 100 ),
				$planName,
				$symbol . sprintf( '%0.2f', $planAmount / 100 ),
				$symbol . sprintf( '%0.2f', $planAmount / 100 ),
				$name,
				$cardholderName,
				$email,
				$billingAddress['line1'],
				$billingAddress['line2'],
				$billingAddress['city'],
				$billingAddress['state'],
				$billingAddress['country'],
				$billingAddress['zip'],
				$productName
			),
			$message );

		$message = $this->replace_custom_fields( $message, $custom_input_values );

		$this->send_email( $email, $subject, $message );
	}

	public function send_subscription_finished_email_receipt( $email, $planName, $planAmount, $cardholderName, $billingAddress, $productName ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options = get_option( 'fullstripe_options' );
		$name    = get_bloginfo( 'name' );

		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->subscriptionFinished->subject;
		$message       = stripslashes( $emailReceipts->subscriptionFinished->html );
		$symbol        = MM_WPFS::get_currency_symbol_for( $options['currency'] );

		$message = str_replace(
			array(
				"%PLAN_NAME%",
				"%PLAN_AMOUNT%",
				"%AMOUNT%",
				"%NAME%",
				"%CUSTOMERNAME%",
				"%CUSTOMER_EMAIL%",
				"%ADDRESS1%",
				"%ADDRESS2%",
				"%CITY%",
				"%STATE%",
				"%ZIP%",
				"%PRODUCT_NAME%"
			),
			array(
				$planName,
				$symbol . sprintf( '%0.2f', $planAmount / 100 ),
				$symbol . sprintf( '%0.2f', $planAmount / 100 ),
				$name,
				$cardholderName,
				$email,
				$billingAddress['line1'],
				$billingAddress['line2'],
				$billingAddress['city'],
				$billingAddress['state'],
				$billingAddress['zip'],
				$productName
			),
			$message );

		$this->send_email( $email, $subject, $message );
	}

	/**
	 * @param $message
	 * @param $custom_input_values
	 *
	 * @return mixed
	 */
	private function replace_custom_fields( $message, $custom_input_values ) {
		$custom_field_count = 5;
		$replace            = array( '', '', '', '', '' );
		if ( isset( $custom_input_values ) ) {
			$replace = $custom_input_values;
			for ( $i = 0; $i < $custom_field_count - count( $custom_input_values ); $i ++ ) {
				$replace[] = '';
			}
		}
		$message = str_replace( array(
			"%CUSTOMFIELD1%",
			"%CUSTOMFIELD2%",
			"%CUSTOMFIELD3%",
			"%CUSTOMFIELD4%",
			"%CUSTOMFIELD5%"
		), $replace, $message );

		return $message;
	}

}
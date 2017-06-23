<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.03.01.
 * Time: 14:43
 */
class MM_WPFS_Patcher {

	public static function apply_patches() {

		error_log( 'WPFS INFO apply_patches(): Apply patches...' );

		$patches         = self::prepare_patches();
		$applied_patches = self::load_applied_patches();

		foreach ( $patches as $patch ) {
			/* @var $patch MM_WPFS_Patch */
			$apply = false;
			if ( array_key_exists( $patch->getId(), $applied_patches ) ) {
				if ( $patch->isRepeatable() ) {
					$apply = true;
				}
			} else {
				$apply = true;
			}
			if ( $apply ) {

				try {

					error_log( 'WPFS INFO apply_patches(): Applying ' . $patch->getId() . '...' );

					$result = $patch->apply();

					if ( $result ) {

						self::book_applied( $patch );

						error_log( 'WPFS INFO apply_patches(): ' . $patch->getId() . ' applied successfully.' );
					} else {
						error_log( 'WPFS ERROR apply_patches(): ' . $patch->getId() . ' failed!' );
					}

				} catch ( Exception $e ) {
					error_log( sprintf( 'WPFS ERROR apply_patches(): Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				}

			}
		}

		error_log( 'WPFS INFO apply_patches(): Patches applied.' );

	}

	private static function book_applied( $patch ) {

		if ( ! isset( $patch ) ) {
			return;
		}

		/* @var $patch MM_WPFS_Patch */

		global $wpdb;

		$data = array(
			'patch_id'       => $patch->getId(),
			'plugin_version' => $patch->getPluginVersion(),
			'applied_at'     => current_time( 'mysql', 1 ),
			'description'    => $patch->getDescription()
		);

		if ( $wpdb->insert( "{$wpdb->prefix}fullstripe_patch_info", $data ) === false ) {
			throw new Exception( 'Cannot insert patch_info: ' . $wpdb->last_error );
		}
	}

	/**
	 * @return array
	 */
	private static function prepare_patches() {

		$convert_subscription_form_plans   = new MM_WPFS_ConvertSubscriptionFormPlansPatch();
		$convert_email_receipts            = new MM_WPFS_ConvertEmailReceiptsPatch();
		$convert_subscription_status       = new MM_WPFS_ConvertSubscriptionStatus();
		$set_current_currency_for_payments = new MM_WPFS_SetCurrentCurrencyForPayments();

		$patches = array(
			$convert_subscription_form_plans->getId()   => $convert_subscription_form_plans,
			$convert_email_receipts->getId()            => $convert_email_receipts,
			$convert_subscription_status->getId()       => $convert_subscription_status,
			$set_current_currency_for_payments->getId() => $set_current_currency_for_payments
		);

		return $patches;
	}

	/**
	 * @return array
	 */
	private static function load_applied_patches() {
		global $wpdb;

		$result = $wpdb->get_results( "select id,patch_id,plugin_version,applied_at,description from {$wpdb->prefix}fullstripe_patch_info" );

		$applied_patches = array();

		foreach ( $result as $applied_patch ) {
			$applied_patches[ $applied_patch->patch_id ] = $applied_patch;
		}

		return $applied_patches;
	}

}

class MM_WPFS_SetCurrentCurrencyForPayments extends MM_WPFS_Patch {

	/**
	 * MM_WPFS_SetCurrentCurrencyForPayments constructor.
	 */
	public function __construct() {
		$this->id             = 'set_current_currency_for_payments';
		$this->plugin_version = '3.7.0';
		$this->description    = 'A patch for setting the current currency for payments made before 3.7.0 without saved currency. JIRA reference: WPFS-240';
		$this->repeatable     = true;
	}

	public function apply() {
		$this->update_currency_for_payments();

		return true;
	}

	private function update_currency_for_payments() {
		$options = get_option( 'fullstripe_options' );
		if ( is_array( $options ) ) {
			if ( array_key_exists( 'currency', $options ) ) {
				$currency = $options['currency'];

				global $wpdb;

				return $wpdb->update( "{$wpdb->prefix}fullstripe_payments", array( 'currency' => $currency ), array( 'currency' => '' ) );
			}
		}

		return false;
	}

}

class MM_WPFS_ConvertSubscriptionStatus extends MM_WPFS_Patch {

	/**
	 * MM_WPFS_ConvertSubscriptionStatus constructor.
	 */
	public function __construct() {
		$this->id             = 'convert_subscription_status';
		$this->plugin_version = '3.6.0';
		$this->description    = 'A patch for converting subscription status fields from version before 3.6.0. JIRA reference: WPFS-194';
		$this->repeatable     = true;
	}

	public function apply() {

		$this->update_subscription_status();

		return true;
	}

	private function update_subscription_status() {
		global $wpdb;

		return $wpdb->update( "{$wpdb->prefix}fullstripe_subscribers", array( 'status' => 'running' ), array( 'status' => '' ) );
	}

}

class MM_WPFS_ConvertEmailReceiptsPatch extends MM_WPFS_Patch {

	/**
	 * MM_WPFS_ConvertEmailReceiptsPatch constructor.
	 */
	public function __construct() {
		$this->id             = 'convert_email_receipts';
		$this->plugin_version = '3.6.0';
		$this->description    = 'A patch for converting email receipts to JSON format. JIRA reference: WPFS-170';
		$this->repeatable     = true;
	}

	public function apply() {
		$options = get_option( 'fullstripe_options' );
		if ( is_array( $options ) ) {
			if (
				array_key_exists( 'email_receipt_subject', $options )
				&& array_key_exists( 'email_receipt_html', $options )
				&& array_key_exists( 'subscription_email_receipt_subject', $options )
				&& array_key_exists( 'subscription_email_receipt_html', $options )
			) {
				$emailReceipts                         = array();
				$paymentMade                           = new stdClass();
				$subscriptionStarted                   = new stdClass();
				$subscriptionFinished                  = new stdClass();
				$paymentMade->subject                  = $options['email_receipt_subject'];
				$paymentMade->html                     = html_entity_decode( $options['email_receipt_html'] );
				$subscriptionStarted->subject          = $options['subscription_email_receipt_subject'];
				$subscriptionStarted->html             = html_entity_decode( $options['subscription_email_receipt_html'] );
				$subscriptionFinished->subject         = 'Subscription ended';
				$subscriptionFinished->html            = '<html><body><p>Hi,</p><p>Your %PLAN_NAME% subscription has come to an end.</p><p>Thanks</p><br/>%NAME%</body></html>';
				$emailReceipts['paymentMade']          = $paymentMade;
				$emailReceipts['subscriptionStarted']  = $subscriptionStarted;
				$emailReceipts['subscriptionFinished'] = $subscriptionFinished;

				$options['email_receipts'] = json_encode( $emailReceipts );
				unset( $options['email_receipt_subject'] );
				unset( $options['email_receipt_html'] );
				unset( $options['subscription_email_receipt_subject'] );
				unset( $options['subscription_email_receipt_html'] );

				update_option( 'fullstripe_options', $options );
			}
		}

		return true;
	}

}

class MM_WPFS_ConvertSubscriptionFormPlansPatch extends MM_WPFS_Patch {

	/**
	 * MM_WPFS_ConvertSubscriptionFormPlansPatch constructor.
	 */
	public function __construct() {
		$this->id             = 'convert_subscription_form_plans';
		$this->plugin_version = '3.6.0';
		$this->description    = 'A patch for converting subscription forms\' plans column to JSON format. JIRA reference: WPFS-15';
		$this->repeatable     = true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function apply() {
		$subscription_forms = $this->load_subscription_forms();

		if ( isset( $subscription_forms ) ) {
			foreach ( $subscription_forms as $form ) {
				json_decode( $form->plans );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$this->update_subscription_form_plans( $form->subscriptionFormID, json_encode( explode( ',', $form->plans ) ) );
				}
			}
		}

		return true;
	}

	private function load_subscription_forms() {
		global $wpdb;

		return $wpdb->get_results( "select * from {$wpdb->prefix}fullstripe_subscription_forms" );
	}

	private function update_subscription_form_plans( $id, $plans ) {
		global $wpdb;

		return $wpdb->update( "{$wpdb->prefix}fullstripe_subscription_forms", array( 'plans' => $plans ), array( 'subscriptionFormID' => $id ) );
	}
}

class MM_WPFS_DummyPatch extends MM_WPFS_Patch {

	/**
	 * MM_WPFS_Dummy constructor.
	 */
	public function __construct() {
		$this->id             = 'dummy';
		$this->plugin_version = '3.6.0';
		$this->description    = 'A dummy patch for testing purposes.';
		$this->repeatable     = false;
	}

	public function apply() {
		error_log( 'WPFS DEBUG apply(): ' . 'Starting DummyPatch...' );
		error_log( 'WPFS DEBUG apply(): ' . 'DummyPatch finished.' );

		return true;
	}
}

abstract class MM_WPFS_Patch {

	/* @var $id string */
	protected $id;
	/* @var $plugin_version string */
	protected $plugin_version;
	/* @var $description string */
	protected $description;
	/* @var $repeatable boolean */
	protected $repeatable = false;

	/**
	 * @return boolean
	 */
	public abstract function apply();

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getPluginVersion() {
		return $this->plugin_version;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return boolean
	 */
	public function isRepeatable() {
		return $this->repeatable;
	}

	/**
	 *
	 * @param $result
	 *
	 * @param $message
	 *
	 * @throws Exception
	 */
	protected function handleDbError( $result, $message ) {
		if ( $result === false ) {
			global $wpdb;
			error_log( sprintf( "%s: Raised exception with message=%s", 'WPFS ERROR', $message ) );
			error_log( sprintf( "%s: SQL last error=%s", 'WPFS ERROR', $wpdb->last_error ) );
			throw new Exception( $message );
		}
	}

}
<?php
/*
WP Full Stripe
https://paymentsplugin.com
Complete Stripe payments integration for Wordpress
Mammothology
3.7.0
https://paymentsplugin.com
*/

require_once( 'wp-full-stripe-logger-configurator.php' );
require_once( ABSPATH . 'wp-includes/pluggable.php' );

class MM_WPFS {
	const VERSION = '3.7.0';
	public static $instance;
	/** @var MM_WPFS_Customer */
	private $customer = null;
	/** @var MM_WPFS_Admin */
	private $admin = null;
	/** @var MM_WPFS_Database */
	private $database = null;
	/** @var MM_WPFS_Stripe */
	private $stripe = null;
	/** @var MM_WPFS_Admin_Menu */
	private $admin_menu = null;
	private $log;

	public function __construct() {

		$this->includes();
		$this->setup();
		$this->hooks();

	}

	function includes() {

		include 'wp-full-stripe-database.php';
		include 'wp-full-stripe-mailer.php';
		include 'wp-full-stripe-customer.php';
		include 'wp-full-stripe-patcher.php';
		include 'wp-full-stripe-payments.php';
		include 'wp-full-stripe-admin.php';
		include 'wp-full-stripe-admin-menu.php';
		include 'wp-full-stripe-news-feed-url.php';

		do_action( 'fullstripe_includes_action' );
	}

	function setup() {

		$this->log = Logger::getLogger( "WPFS" );

		//set option defaults
		$options = get_option( 'fullstripe_options' );
		if ( ! $options || $options['fullstripe_version'] != self::VERSION ) {
			$this->set_option_defaults( $options );
			// tnagy reload saved options
			$options = get_option( 'fullstripe_options' );
		}

		$this->update_option_defaults( $options );

		// tnagy reload saved options and check edd license status
		$options        = get_option( 'fullstripe_options' );
		$license_status = $options['edd_license_status'];
		if ( $license_status == 'unknown' || $license_status == 'inactive' ) {
			$this->activate_license();
		}

		//set API key
		if ( $options['apiMode'] === 'test' ) {
			$this->fullstripe_set_api_key( $options['secretKey_test'] );
		} else {
			$this->fullstripe_set_api_key( $options['secretKey_live'] );
		}

		//setup subclasses to handle everything
		$this->database   = new MM_WPFS_Database();
		$this->customer   = new MM_WPFS_Customer();
		$this->admin      = new MM_WPFS_Admin();
		$this->stripe     = new MM_WPFS_Stripe();
		$this->admin_menu = new MM_WPFS_Admin_Menu();

		do_action( 'fullstripe_setup_action' );

	}

	function set_option_defaults( $options ) {
		if ( ! $options ) {

			$emailReceipts = $this->create_default_email_receipts();

			$arr = array(
				'secretKey_test'                       => 'YOUR_TEST_SECRET_KEY',
				'publishKey_test'                      => 'YOUR_TEST_PUBLISHABLE_KEY',
				'secretKey_live'                       => 'YOUR_LIVE_SECRET_KEY',
				'publishKey_live'                      => 'YOUR_LIVE_PUBLISHABLE_KEY',
				'apiMode'                              => 'test',
				'currency'                             => 'usd',
				'form_css'                             => ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}",
				'includeStyles'                        => '1',
				'receiptEmailType'                     => 'plugin',
				'email_receipts'                       => json_encode( $emailReceipts ),
				'email_receipt_sender_address'         => '',
				'admin_payment_receipt'                => '0',
				'lock_email_field_for_logged_in_users' => '1',
				'fullstripe_version'                   => self::VERSION,
				'webhook_token'                        => $this->create_webhook_token(),
				'edd_license_key'                      => MM_WPFS_License::KEY,
				'edd_license_status'                   => 'unknown'
			);

			update_option( 'fullstripe_options', $arr );
		} else /* different version */ {
			$options['fullstripe_version'] = self::VERSION;
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( 'currency', $options ) ) {
				$options['currency'] = 'usd';
			}
			if ( ! array_key_exists( 'form_css', $options ) ) {
				$options['form_css'] = ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}";
			}
			if ( ! array_key_exists( 'includeStyles', $options ) ) {
				$options['includeStyles'] = '1';
			}
			if ( ! array_key_exists( 'receiptEmailType', $options ) ) {
				$options['receiptEmailType'] = 'plugin';
			}
			if ( ! array_key_exists( 'email_receipts', $options ) ) {
				$emailReceipts             = $this->create_default_email_receipts();
				$options['email_receipts'] = json_encode( $emailReceipts );
			}
			if ( ! array_key_exists( 'email_receipt_sender_address', $options ) ) {
				$options['email_receipt_sender_address'] = '';
			}
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = '0';
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->create_webhook_token();
			}
			if ( ! array_key_exists( 'edd_license_key', $options ) ) {
				$options['edd_license_key'] = MM_WPFS_License::KEY;
			}
			if ( ! array_key_exists( 'edd_license_status', $options ) ) {
				$options['edd_license_status'] = 'unknown';
			}
			update_option( 'fullstripe_options', $options );
		}

		//also, if version changed then the DB might be out of date
		MM_WPFS_Database::fullstripe_setup_db();
	}

	function update_option_defaults( $options ) {
		if ( $options ) {
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( 'currency', $options ) ) {
				$options['currency'] = 'usd';
			}
			if ( ! array_key_exists( 'form_css', $options ) ) {
				$options['form_css'] = ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}";
			}
			if ( ! array_key_exists( 'includeStyles', $options ) ) {
				$options['includeStyles'] = '1';
			}
			if ( ! array_key_exists( 'receiptEmailType', $options ) ) {
				$options['receiptEmailType'] = 'plugin';
			}
			if ( ! array_key_exists( 'email_receipts', $options ) ) {
				$options['email_receipts'] = $this->create_default_email_receipts();
			}
			if ( ! array_key_exists( 'email_receipt_sender_address', $options ) ) {
				$options['email_receipt_sender_address'] = '';
			}
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = 'no';
			} else {
				if ( $options['admin_payment_receipt'] == '0' ) {
					$options['admin_payment_receipt'] = 'no';
				}
				if ( $options['admin_payment_receipt'] == '1' ) {
					$options['admin_payment_receipt'] = 'website_admin';
				}
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->create_webhook_token();
			}
			if ( ! array_key_exists( 'edd_license_key', $options ) ) {
				$options['edd_license_key'] = MM_WPFS_License::KEY;
			}
			if ( ! array_key_exists( 'edd_license_status', $options ) ) {
				$options['edd_license_status'] = 'unknown';
			}

			update_option( 'fullstripe_options', $options );

		}
	}

	function fullstripe_set_api_key( $key ) {
		if ( $key != '' && $key != 'YOUR_TEST_SECRET_KEY' && $key != 'YOUR_LIVE_SECRET_KEY' ) {
			try {
				\Stripe\Stripe::setApiKey( $key );
			} catch ( Exception $e ) {
				//invalid key was set, ignore it
			}
		}
	}

	function hooks() {

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
		add_shortcode( 'fullstripe_payment', array( $this, 'fullstripe_payment_form' ) );
		add_shortcode( 'fullstripe_subscription', array( $this, 'fullstripe_subscription_form' ) );
		add_shortcode( 'fullstripe_checkout', array( $this, 'fullstripe_checkout_form' ) );
		add_action( 'wp_head', array( $this, 'fullstripe_wp_head' ) );

		do_action( 'fullstripe_main_hooks_action' );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new MM_WPFS();
		}

		return self::$instance;
	}

	public static function setup_db() {
		MM_WPFS_Database::fullstripe_setup_db();
		MM_WPFS_Patcher::apply_patches();
	}

	public static function get_translated_interval_label( $interval, $count ) {
		$label = null;
		if ( $interval == 'week' ) {
			$label = _n( 'week', 'weeks', $count, 'wp-full-stripe' );
		} elseif ( $interval == 'month' ) {
			$label = _n( 'month', 'months', $count, 'wp-full-stripe' );
		} elseif ( $interval == 'year' ) {
			$label = _n( 'year', 'years', $count, 'wp-full-stripe' );
		} else {
			$label = $interval;
		}

		return $label;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function esc_html_id_attr( $value ) {
		return preg_replace( '/[^a-z0-9\-_:\.]|^[^a-z]+/i', '', $value );
	}

	public static function get_currency_symbol_for( $currency ) {
		if ( isset( $currency ) ) {

			$available_currencies = MM_WPFS::get_available_currencies();

			if ( isset( $available_currencies ) && array_key_exists( $currency, $available_currencies ) ) {
				$currency_symbol = $available_currencies[ $currency ]['symbol'];
			} else {
				$currency_symbol = strtoupper( $currency );
			}

			return $currency_symbol;
		}

		return null;
	}

	public static function get_available_currencies() {
		return array(
			'aed' => array(
				'code'   => 'AED',
				'name'   => 'United Arab Emirates Dirham',
				'symbol' => 'DH'
			),
			'afn' => array(
				'code'   => 'AFN',
				'name'   => 'Afghan Afghani',
				'symbol' => '؋'
			),
			'all' => array(
				'code'   => 'ALL',
				'name'   => 'Albanian Lek',
				'symbol' => 'L'
			),
			'amd' => array(
				'code'   => 'AMD',
				'name'   => 'Armenian Dram',
				'symbol' => '֏'
			),
			'ang' => array(
				'code'   => 'ANG',
				'name'   => 'Netherlands Antillean Gulden',
				'symbol' => 'ƒ'
			),
			'aoa' => array(
				'code'   => 'AOA',
				'name'   => 'Angolan Kwanza',
				'symbol' => 'Kz'
			),
			'ars' => array(
				'code'   => 'ARS',
				'name'   => 'Argentine Peso',
				'symbol' => '$'
			),
			'aud' => array(
				'code'   => 'AUD',
				'name'   => 'Australian Dollar',
				'symbol' => '$'
			),
			'awg' => array(
				'code'   => 'AWG',
				'name'   => 'Aruban Florin',
				'symbol' => 'ƒ'
			),
			'azn' => array(
				'code'   => 'AZN',
				'name'   => 'Azerbaijani Manat',
				'symbol' => 'm.'
			),
			'bam' => array(
				'code'   => 'BAM',
				'name'   => 'Bosnia & Herzegovina Convertible Mark',
				'symbol' => 'KM'
			),
			'bbd' => array(
				'code'   => 'BBD',
				'name'   => 'Barbadian Dollar',
				'symbol' => 'Bds$'
			),
			'bdt' => array(
				'code'   => 'BDT',
				'name'   => 'Bangladeshi Taka',
				'symbol' => '৳'
			),
			'bgn' => array(
				'code'   => 'BGN',
				'name'   => 'Bulgarian Lev',
				'symbol' => 'лв'
			),
			'bif' => array(
				'code'   => 'BIF',
				'name'   => 'Burundian Franc',
				'symbol' => 'FBu'
			),
			'bmd' => array(
				'code'   => 'BMD',
				'name'   => 'Bermudian Dollar',
				'symbol' => 'BD$'
			),
			'bnd' => array(
				'code'   => 'BND',
				'name'   => 'Brunei Dollar',
				'symbol' => 'B$'
			),
			'bob' => array(
				'code'   => 'BOB',
				'name'   => 'Bolivian Boliviano',
				'symbol' => 'Bs.'
			),
			'brl' => array(
				'code'   => 'BRL',
				'name'   => 'Brazilian Real',
				'symbol' => 'R$'
			),
			'bsd' => array(
				'code'   => 'BSD',
				'name'   => 'Bahamian Dollar',
				'symbol' => 'B$'
			),
			'bwp' => array(
				'code'   => 'BWP',
				'name'   => 'Botswana Pula',
				'symbol' => 'P'
			),
			'bzd' => array(
				'code'   => 'BZD',
				'name'   => 'Belize Dollar',
				'symbol' => 'BZ$'
			),
			'cad' => array(
				'code'   => 'CAD',
				'name'   => 'Canadian Dollar',
				'symbol' => '$'
			),
			'cdf' => array(
				'code'   => 'CDF',
				'name'   => 'Congolese Franc',
				'symbol' => 'CF'
			),
			'chf' => array(
				'code'   => 'CHF',
				'name'   => 'Swiss Franc',
				'symbol' => 'Fr'
			),
			'clp' => array(
				'code'   => 'CLP',
				'name'   => 'Chilean Peso',
				'symbol' => 'CLP$'
			),
			'cny' => array(
				'code'   => 'CNY',
				'name'   => 'Chinese Renminbi Yuan',
				'symbol' => '¥'
			),
			'cop' => array(
				'code'   => 'COP',
				'name'   => 'Colombian Peso',
				'symbol' => 'COL$'
			),
			'crc' => array(
				'code'   => 'CRC',
				'name'   => 'Costa Rican Colón',
				'symbol' => '₡'
			),
			'cve' => array(
				'code'   => 'CVE',
				'name'   => 'Cape Verdean Escudo',
				'symbol' => 'Esc'
			),
			'czk' => array(
				'code'   => 'CZK',
				'name'   => 'Czech Koruna',
				'symbol' => 'Kč'
			),
			'djf' => array(
				'code'   => 'DJF',
				'name'   => 'Djiboutian Franc',
				'symbol' => 'Fr'
			),
			'dkk' => array(
				'code'   => 'DKK',
				'name'   => 'Danish Krone',
				'symbol' => 'kr'
			),
			'dop' => array(
				'code'   => 'DOP',
				'name'   => 'Dominican Peso',
				'symbol' => 'RD$'
			),
			'dzd' => array(
				'code'   => 'DZD',
				'name'   => 'Algerian Dinar',
				'symbol' => 'DA'
			),
			'egp' => array(
				'code'   => 'EGP',
				'name'   => 'Egyptian Pound',
				'symbol' => 'L.E.'
			),
			'etb' => array(
				'code'   => 'ETB',
				'name'   => 'Ethiopian Birr',
				'symbol' => 'Br'
			),
			'eur' => array(
				'code'   => 'EUR',
				'name'   => 'Euro',
				'symbol' => '€'
			),
			'fjd' => array(
				'code'   => 'FJD',
				'name'   => 'Fijian Dollar',
				'symbol' => 'FJ$'
			),
			'fkp' => array(
				'code'   => 'FKP',
				'name'   => 'Falkland Islands Pound',
				'symbol' => 'FK£'
			),
			'gbp' => array(
				'code'   => 'GBP',
				'name'   => 'British Pound',
				'symbol' => '£'
			),
			'gel' => array(
				'code'   => 'GEL',
				'name'   => 'Georgian Lari',
				'symbol' => 'ლ'
			),
			'gip' => array(
				'code'   => 'GIP',
				'name'   => 'Gibraltar Pound',
				'symbol' => '£'
			),
			'gmd' => array(
				'code'   => 'GMD',
				'name'   => 'Gambian Dalasi',
				'symbol' => 'D'
			),
			'gnf' => array(
				'code'   => 'GNF',
				'name'   => 'Guinean Franc',
				'symbol' => 'FG'
			),
			'gtq' => array(
				'code'   => 'GTQ',
				'name'   => 'Guatemalan Quetzal',
				'symbol' => 'Q'
			),
			'gyd' => array(
				'code'   => 'GYD',
				'name'   => 'Guyanese Dollar',
				'symbol' => 'G$'
			),
			'hkd' => array(
				'code'   => 'HKD',
				'name'   => 'Hong Kong Dollar',
				'symbol' => 'HK$'
			),
			'hnl' => array(
				'code'   => 'HNL',
				'name'   => 'Honduran Lempira',
				'symbol' => 'L'
			),
			'hrk' => array(
				'code'   => 'HRK',
				'name'   => 'Croatian Kuna',
				'symbol' => 'kn'
			),
			'htg' => array(
				'code'   => 'HTG',
				'name'   => 'Haitian Gourde',
				'symbol' => 'G'
			),
			'huf' => array(
				'code'   => 'HUF',
				'name'   => 'Hungarian Forint',
				'symbol' => 'Ft'
			),
			'idr' => array(
				'code'   => 'IDR',
				'name'   => 'Indonesian Rupiah',
				'symbol' => 'Rp'
			),
			'ils' => array(
				'code'   => 'ILS',
				'name'   => 'Israeli New Sheqel',
				'symbol' => '₪'
			),
			'inr' => array(
				'code'   => 'INR',
				'name'   => 'Indian Rupee',
				'symbol' => '₹'
			),
			'isk' => array(
				'code'   => 'ISK',
				'name'   => 'Icelandic Króna',
				'symbol' => 'ikr'
			),
			'jmd' => array(
				'code'   => 'JMD',
				'name'   => 'Jamaican Dollar',
				'symbol' => 'J$'
			),
			'jpy' => array(
				'code'   => 'JPY',
				'name'   => 'Japanese Yen',
				'symbol' => '¥'
			),
			'kes' => array(
				'code'   => 'KES',
				'name'   => 'Kenyan Shilling',
				'symbol' => 'Ksh'
			),
			'kgs' => array(
				'code'   => 'KGS',
				'name'   => 'Kyrgyzstani Som',
				'symbol' => 'COM'
			),
			'khr' => array(
				'code'   => 'KHR',
				'name'   => 'Cambodian Riel',
				'symbol' => '៛'
			),
			'kmf' => array(
				'code'   => 'KMF',
				'name'   => 'Comorian Franc',
				'symbol' => 'CF'
			),
			'krw' => array(
				'code'   => 'KRW',
				'name'   => 'South Korean Won',
				'symbol' => '₩'
			),
			'kyd' => array(
				'code'   => 'KYD',
				'name'   => 'Cayman Islands Dollar',
				'symbol' => 'CI$'
			),
			'kzt' => array(
				'code'   => 'KZT',
				'name'   => 'Kazakhstani Tenge',
				'symbol' => '₸'
			),
			'lak' => array(
				'code'   => 'LAK',
				'name'   => 'Lao Kip',
				'symbol' => '₭'
			),
			'lbp' => array(
				'code'   => 'LBP',
				'name'   => 'Lebanese Pound',
				'symbol' => 'LL'
			),
			'lkr' => array(
				'code'   => 'LKR',
				'name'   => 'Sri Lankan Rupee',
				'symbol' => 'SLRs'
			),
			'lrd' => array(
				'code'   => 'LRD',
				'name'   => 'Liberian Dollar',
				'symbol' => 'L$'
			),
			'lsl' => array(
				'code'   => 'LSL',
				'name'   => 'Lesotho Loti',
				'symbol' => 'M'
			),
			'mad' => array(
				'code'   => 'MAD',
				'name'   => 'Moroccan Dirham',
				'symbol' => 'DH'
			),
			'mdl' => array(
				'code'   => 'MDL',
				'name'   => 'Moldovan Leu',
				'symbol' => 'MDL'
			),
			'mga' => array(
				'code'   => 'MGA',
				'name'   => 'Malagasy Ariary',
				'symbol' => 'Ar'
			),
			'mkd' => array(
				'code'   => 'MKD',
				'name'   => 'Macedonian Denar',
				'symbol' => 'ден'
			),
			'mnt' => array(
				'code'   => 'MNT',
				'name'   => 'Mongolian Tögrög',
				'symbol' => '₮'
			),
			'mop' => array(
				'code'   => 'MOP',
				'name'   => 'Macanese Pataca',
				'symbol' => 'MOP$'
			),
			'mro' => array(
				'code'   => 'MRO',
				'name'   => 'Mauritanian Ouguiya',
				'symbol' => 'UM'
			),
			'mur' => array(
				'code'   => 'MUR',
				'name'   => 'Mauritian Rupee',
				'symbol' => 'Rs'
			),
			'mvr' => array(
				'code'   => 'MVR',
				'name'   => 'Maldivian Rufiyaa',
				'symbol' => 'Rf.'
			),
			'mwk' => array(
				'code'   => 'MWK',
				'name'   => 'Malawian Kwacha',
				'symbol' => 'MK'
			),
			'mxn' => array(
				'code'   => 'MXN',
				'name'   => 'Mexican Peso',
				'symbol' => '$'
			),
			'myr' => array(
				'code'   => 'MYR',
				'name'   => 'Malaysian Ringgit',
				'symbol' => 'RM'
			),
			'mzn' => array(
				'code'   => 'MZN',
				'name'   => 'Mozambican Metical',
				'symbol' => 'MT'
			),
			'nad' => array(
				'code'   => 'NAD',
				'name'   => 'Namibian Dollar',
				'symbol' => 'N$'
			),
			'ngn' => array(
				'code'   => 'NGN',
				'name'   => 'Nigerian Naira',
				'symbol' => '₦'
			),
			'nio' => array(
				'code'   => 'NIO',
				'name'   => 'Nicaraguan Córdoba',
				'symbol' => 'C$'
			),
			'nok' => array(
				'code'   => 'NOK',
				'name'   => 'Norwegian Krone',
				'symbol' => 'kr'
			),
			'npr' => array(
				'code'   => 'NPR',
				'name'   => 'Nepalese Rupee',
				'symbol' => 'NRs'
			),
			'nzd' => array(
				'code'   => 'NZD',
				'name'   => 'New Zealand Dollar',
				'symbol' => 'NZ$'
			),
			'pab' => array(
				'code'   => 'PAB',
				'name'   => 'Panamanian Balboa',
				'symbol' => 'B/.'
			),
			'pen' => array(
				'code'   => 'PEN',
				'name'   => 'Peruvian Nuevo Sol',
				'symbol' => 'S/.'
			),
			'pgk' => array(
				'code'   => 'PGK',
				'name'   => 'Papua New Guinean Kina',
				'symbol' => 'K'
			),
			'php' => array(
				'code'   => 'PHP',
				'name'   => 'Philippine Peso',
				'symbol' => '₱'
			),
			'pkr' => array(
				'code'   => 'PKR',
				'name'   => 'Pakistani Rupee',
				'symbol' => 'PKR'
			),
			'pln' => array(
				'code'   => 'PLN',
				'name'   => 'Polish Złoty',
				'symbol' => 'zł'
			),
			'pyg' => array(
				'code'   => 'PYG',
				'name'   => 'Paraguayan Guaraní',
				'symbol' => '₲'
			),
			'qar' => array(
				'code'   => 'QAR',
				'name'   => 'Qatari Riyal',
				'symbol' => 'QR'
			),
			'ron' => array(
				'code'   => 'RON',
				'name'   => 'Romanian Leu',
				'symbol' => 'RON'
			),
			'rsd' => array(
				'code'   => 'RSD',
				'name'   => 'Serbian Dinar',
				'symbol' => 'дин'
			),
			'rub' => array(
				'code'   => 'RUB',
				'name'   => 'Russian Ruble',
				'symbol' => 'руб'
			),
			'rwf' => array(
				'code'   => 'RWF',
				'name'   => 'Rwandan Franc',
				'symbol' => 'FRw'
			),
			'sar' => array(
				'code'   => 'SAR',
				'name'   => 'Saudi Riyal',
				'symbol' => 'SR'
			),
			'sbd' => array(
				'code'   => 'SBD',
				'name'   => 'Solomon Islands Dollar',
				'symbol' => 'SI$'
			),
			'scr' => array(
				'code'   => 'SCR',
				'name'   => 'Seychellois Rupee',
				'symbol' => 'SRe'
			),
			'sek' => array(
				'code'   => 'SEK',
				'name'   => 'Swedish Krona',
				'symbol' => 'kr'
			),
			'sgd' => array(
				'code'   => 'SGD',
				'name'   => 'Singapore Dollar',
				'symbol' => 'S$'
			),
			'shp' => array(
				'code'   => 'SHP',
				'name'   => 'Saint Helenian Pound',
				'symbol' => '£'
			),
			'sll' => array(
				'code'   => 'SLL',
				'name'   => 'Sierra Leonean Leone',
				'symbol' => 'Le'
			),
			'sos' => array(
				'code'   => 'SOS',
				'name'   => 'Somali Shilling',
				'symbol' => 'Sh.So.'
			),
			'std' => array(
				'code'   => 'STD',
				'name'   => 'São Tomé and Príncipe Dobra',
				'symbol' => 'Db'
			),
			'srd' => array(
				'code'   => 'SRD',
				'name'   => 'Surinamese Dollar',
				'symbol' => 'SRD'
			),
			'svc' => array(
				'code'   => 'SVC',
				'name'   => 'Salvadoran Colón',
				'symbol' => '₡'
			),
			'szl' => array(
				'code'   => 'SZL',
				'name'   => 'Swazi Lilangeni',
				'symbol' => 'E'
			),
			'thb' => array(
				'code'   => 'THB',
				'name'   => 'Thai Baht',
				'symbol' => '฿'
			),
			'tjs' => array(
				'code'   => 'TJS',
				'name'   => 'Tajikistani Somoni',
				'symbol' => 'TJS'
			),
			'top' => array(
				'code'   => 'TOP',
				'name'   => 'Tongan Paʻanga',
				'symbol' => '$'
			),
			'try' => array(
				'code'   => 'TRY',
				'name'   => 'Turkish Lira',
				'symbol' => '₺'
			),
			'ttd' => array(
				'code'   => 'TTD',
				'name'   => 'Trinidad and Tobago Dollar',
				'symbol' => 'TT$'
			),
			'twd' => array(
				'code'   => 'TWD',
				'name'   => 'New Taiwan Dollar',
				'symbol' => 'NT$'
			),
			'tzs' => array(
				'code'   => 'TZS',
				'name'   => 'Tanzanian Shilling',
				'symbol' => 'TSh'
			),
			'uah' => array(
				'code'   => 'UAH',
				'name'   => 'Ukrainian Hryvnia',
				'symbol' => '₴'
			),
			'ugx' => array(
				'code'   => 'UGX',
				'name'   => 'Ugandan Shilling',
				'symbol' => 'USh'
			),
			'usd' => array(
				'code'   => 'USD',
				'name'   => 'United States Dollar',
				'symbol' => '$'
			),
			'uyu' => array(
				'code'   => 'UYU',
				'name'   => 'Uruguayan Peso',
				'symbol' => '$U'
			),
			'uzs' => array(
				'code'   => 'UZS',
				'name'   => 'Uzbekistani Som',
				'symbol' => 'UZS'
			),
			'vnd' => array(
				'code'   => 'VND',
				'name'   => 'Vietnamese Đồng',
				'symbol' => '₫'
			),
			'vuv' => array(
				'code'   => 'VUV',
				'name'   => 'Vanuatu Vatu',
				'symbol' => 'VT'
			),
			'wst' => array(
				'code'   => 'WST',
				'name'   => 'Samoan Tala',
				'symbol' => 'WS$'
			),
			'xaf' => array(
				'code'   => 'XAF',
				'name'   => 'Central African Cfa Franc',
				'symbol' => 'FCFA'
			),
			'xcd' => array(
				'code'   => 'XCD',
				'name'   => 'East Caribbean Dollar',
				'symbol' => 'EC$'
			),
			'xof' => array(
				'code'   => 'XOF',
				'name'   => 'West African Cfa Franc',
				'symbol' => 'CFA'
			),
			'xpf' => array(
				'code'   => 'XPF',
				'name'   => 'Cfp Franc',
				'symbol' => 'F'
			),
			'yer' => array(
				'code'   => 'YER',
				'name'   => 'Yemeni Rial',
				'symbol' => '﷼'
			),
			'zar' => array(
				'code'   => 'ZAR',
				'name'   => 'South African Rand',
				'symbol' => 'R'
			),
			'zmw' => array(
				'code'   => 'ZMW',
				'name'   => 'Zambian Kwacha',
				'symbol' => 'ZK'
			)
		);
	}

	public static function echo_translated_label( $label ) {
		echo self::translate_label( $label );
	}

	public static function translate_label( $label ) {
		if ( empty( $label ) ) {
			return '';
		}

		return __( sanitize_text_field( $label ), 'wp-full-stripe' );
	}

	public static function get_country_name_for( $country_code ) {
		if ( isset( $country_code ) ) {
			$available_countries = self::get_available_countries();
			if ( isset( $available_countries ) && array_key_exists( $country_code, $available_countries ) ) {
				$country_name = $available_countries[ $country_code ]['name'];
			} else {
				$country_name = strtoupper( $country_code );
			}

			return $country_name;
		}

		return null;
	}

	/**
	 * @return array ISO 3166-1 list of countries
	 */
	public static function get_available_countries() {
		$countries = array(
			'AF' => array(
				'name'       => 'Afghanistan',
				'alpha-2'    => 'AF',
				'alpha-3'    => 'AFG',
				'code'       => '004',
				'iso-3166-2' => 'ISO 3166-2:AF'
			),
			'AX' => array(
				'name'       => 'Åland Islands',
				'alpha-2'    => 'AX',
				'alpha-3'    => 'ALA',
				'code'       => '248',
				'iso-3166-2' => 'ISO 3166-2:AX'
			),
			'AL' => array(
				'name'       => 'Albania',
				'alpha-2'    => 'AL',
				'alpha-3'    => 'ALB',
				'code'       => '008',
				'iso-3166-2' => 'ISO 3166-2:AL'
			),
			'DZ' => array(
				'name'       => 'Algeria',
				'alpha-2'    => 'DZ',
				'alpha-3'    => 'DZA',
				'code'       => '012',
				'iso-3166-2' => 'ISO 3166-2:DZ'
			),
			'AS' => array(
				'name'       => 'American Samoa',
				'alpha-2'    => 'AS',
				'alpha-3'    => 'ASM',
				'code'       => '016',
				'iso-3166-2' => 'ISO 3166-2:AS'
			),
			'AD' => array(
				'name'       => 'Andorra',
				'alpha-2'    => 'AD',
				'alpha-3'    => 'AND',
				'code'       => '020',
				'iso-3166-2' => 'ISO 3166-2:AD'
			),
			'AO' => array(
				'name'       => 'Angola',
				'alpha-2'    => 'AO',
				'alpha-3'    => 'AGO',
				'code'       => '024',
				'iso-3166-2' => 'ISO 3166-2:AO'
			),
			'AI' => array(
				'name'       => 'Anguilla',
				'alpha-2'    => 'AI',
				'alpha-3'    => 'AIA',
				'code'       => '660',
				'iso-3166-2' => 'ISO 3166-2:AI'
			),
			'AQ' => array(
				'name'       => 'Antarctica',
				'alpha-2'    => 'AQ',
				'alpha-3'    => 'ATA',
				'code'       => '010',
				'iso-3166-2' => 'ISO 3166-2:AQ'
			),
			'AG' => array(
				'name'       => 'Antigua and Barbuda',
				'alpha-2'    => 'AG',
				'alpha-3'    => 'ATG',
				'code'       => '028',
				'iso-3166-2' => 'ISO 3166-2:AG'
			),
			'AR' => array(
				'name'       => 'Argentina',
				'alpha-2'    => 'AR',
				'alpha-3'    => 'ARG',
				'code'       => '032',
				'iso-3166-2' => 'ISO 3166-2:AR'
			),
			'AM' => array(
				'name'       => 'Armenia',
				'alpha-2'    => 'AM',
				'alpha-3'    => 'ARM',
				'code'       => '051',
				'iso-3166-2' => 'ISO 3166-2:AM'
			),
			'AW' => array(
				'name'       => 'Aruba',
				'alpha-2'    => 'AW',
				'alpha-3'    => 'ABW',
				'code'       => '533',
				'iso-3166-2' => 'ISO 3166-2:AW'
			),
			'AU' => array(
				'name'       => 'Australia',
				'alpha-2'    => 'AU',
				'alpha-3'    => 'AUS',
				'code'       => '036',
				'iso-3166-2' => 'ISO 3166-2:AU'
			),
			'AT' => array(
				'name'       => 'Austria',
				'alpha-2'    => 'AT',
				'alpha-3'    => 'AUT',
				'code'       => '040',
				'iso-3166-2' => 'ISO 3166-2:AT'
			),
			'AZ' => array(
				'name'       => 'Azerbaijan',
				'alpha-2'    => 'AZ',
				'alpha-3'    => 'AZE',
				'code'       => '031',
				'iso-3166-2' => 'ISO 3166-2:AZ'
			),
			'BS' => array(
				'name'       => 'Bahamas',
				'alpha-2'    => 'BS',
				'alpha-3'    => 'BHS',
				'code'       => '044',
				'iso-3166-2' => 'ISO 3166-2:BS'
			),
			'BH' => array(
				'name'       => 'Bahrain',
				'alpha-2'    => 'BH',
				'alpha-3'    => 'BHR',
				'code'       => '048',
				'iso-3166-2' => 'ISO 3166-2:BH'
			),
			'BD' => array(
				'name'       => 'Bangladesh',
				'alpha-2'    => 'BD',
				'alpha-3'    => 'BGD',
				'code'       => '050',
				'iso-3166-2' => 'ISO 3166-2:BD'
			),
			'BB' => array(
				'name'       => 'Barbados',
				'alpha-2'    => 'BB',
				'alpha-3'    => 'BRB',
				'code'       => '052',
				'iso-3166-2' => 'ISO 3166-2:BB'
			),
			'BY' => array(
				'name'       => 'Belarus',
				'alpha-2'    => 'BY',
				'alpha-3'    => 'BLR',
				'code'       => '112',
				'iso-3166-2' => 'ISO 3166-2:BY'
			),
			'BE' => array(
				'name'       => 'Belgium',
				'alpha-2'    => 'BE',
				'alpha-3'    => 'BEL',
				'code'       => '056',
				'iso-3166-2' => 'ISO 3166-2:BE'
			),
			'BZ' => array(
				'name'       => 'Belize',
				'alpha-2'    => 'BZ',
				'alpha-3'    => 'BLZ',
				'code'       => '084',
				'iso-3166-2' => 'ISO 3166-2:BZ'
			),
			'BJ' => array(
				'name'       => 'Benin',
				'alpha-2'    => 'BJ',
				'alpha-3'    => 'BEN',
				'code'       => '204',
				'iso-3166-2' => 'ISO 3166-2:BJ'
			),
			'BM' => array(
				'name'       => 'Bermuda',
				'alpha-2'    => 'BM',
				'alpha-3'    => 'BMU',
				'code'       => '060',
				'iso-3166-2' => 'ISO 3166-2:BM'
			),
			'BT' => array(
				'name'       => 'Bhutan',
				'alpha-2'    => 'BT',
				'alpha-3'    => 'BTN',
				'code'       => '064',
				'iso-3166-2' => 'ISO 3166-2:BT'
			),
			'BO' => array(
				'name'       => 'Bolivia',
				'alpha-2'    => 'BO',
				'alpha-3'    => 'BOL',
				'code'       => '068',
				'iso-3166-2' => 'ISO 3166-2:BO'
			),
			'BQ' => array(
				'name'       => 'Bonaire, Sint Eustatius and Saba',
				'alpha-2'    => 'BQ',
				'alpha-3'    => 'BES',
				'code'       => '535',
				'iso-3166-2' => 'ISO 3166-2:BQ'
			),
			'BA' => array(
				'name'       => 'Bosnia and Herzegovina',
				'alpha-2'    => 'BA',
				'alpha-3'    => 'BIH',
				'code'       => '070',
				'iso-3166-2' => 'ISO 3166-2:BA'
			),
			'BW' => array(
				'name'       => 'Botswana',
				'alpha-2'    => 'BW',
				'alpha-3'    => 'BWA',
				'code'       => '072',
				'iso-3166-2' => 'ISO 3166-2:BW'
			),
			'BV' => array(
				'name'       => 'Bouvet Island',
				'alpha-2'    => 'BV',
				'alpha-3'    => 'BVT',
				'code'       => '074',
				'iso-3166-2' => 'ISO 3166-2:BV'
			),
			'BR' => array(
				'name'       => 'Brazil',
				'alpha-2'    => 'BR',
				'alpha-3'    => 'BRA',
				'code'       => '076',
				'iso-3166-2' => 'ISO 3166-2:BR'
			),
			'IO' => array(
				'name'       => 'British Indian Ocean Territory',
				'alpha-2'    => 'IO',
				'alpha-3'    => 'IOT',
				'code'       => '086',
				'iso-3166-2' => 'ISO 3166-2:IO'
			),
			'BN' => array(
				'name'       => 'Brunei',
				'alpha-2'    => 'BN',
				'alpha-3'    => 'BRN',
				'code'       => '096',
				'iso-3166-2' => 'ISO 3166-2:BN'
			),
			'BG' => array(
				'name'       => 'Bulgaria',
				'alpha-2'    => 'BG',
				'alpha-3'    => 'BGR',
				'code'       => '100',
				'iso-3166-2' => 'ISO 3166-2:BG'
			),
			'BF' => array(
				'name'       => 'Burkina Faso',
				'alpha-2'    => 'BF',
				'alpha-3'    => 'BFA',
				'code'       => '854',
				'iso-3166-2' => 'ISO 3166-2:BF'
			),
			'BI' => array(
				'name'       => 'Burundi',
				'alpha-2'    => 'BI',
				'alpha-3'    => 'BDI',
				'code'       => '108',
				'iso-3166-2' => 'ISO 3166-2:BI'
			),
			'CV' => array(
				'name'       => 'Cabo Verde',
				'alpha-2'    => 'CV',
				'alpha-3'    => 'CPV',
				'code'       => '132',
				'iso-3166-2' => 'ISO 3166-2:CV'
			),
			'KH' => array(
				'name'       => 'Cambodia',
				'alpha-2'    => 'KH',
				'alpha-3'    => 'KHM',
				'code'       => '116',
				'iso-3166-2' => 'ISO 3166-2:KH'
			),
			'CM' => array(
				'name'       => 'Cameroon',
				'alpha-2'    => 'CM',
				'alpha-3'    => 'CMR',
				'code'       => '120',
				'iso-3166-2' => 'ISO 3166-2:CM'
			),
			'CA' => array(
				'name'       => 'Canada',
				'alpha-2'    => 'CA',
				'alpha-3'    => 'CAN',
				'code'       => '124',
				'iso-3166-2' => 'ISO 3166-2:CA'
			),
			'KY' => array(
				'name'       => 'Cayman Islands',
				'alpha-2'    => 'KY',
				'alpha-3'    => 'CYM',
				'code'       => '136',
				'iso-3166-2' => 'ISO 3166-2:KY'
			),
			'CF' => array(
				'name'       => 'Central African Republic',
				'alpha-2'    => 'CF',
				'alpha-3'    => 'CAF',
				'code'       => '140',
				'iso-3166-2' => 'ISO 3166-2:CF'
			),
			'TD' => array(
				'name'       => 'Chad',
				'alpha-2'    => 'TD',
				'alpha-3'    => 'TCD',
				'code'       => '148',
				'iso-3166-2' => 'ISO 3166-2:TD'
			),
			'CL' => array(
				'name'       => 'Chile',
				'alpha-2'    => 'CL',
				'alpha-3'    => 'CHL',
				'code'       => '152',
				'iso-3166-2' => 'ISO 3166-2:CL'
			),
			'CN' => array(
				'name'       => 'China',
				'alpha-2'    => 'CN',
				'alpha-3'    => 'CHN',
				'code'       => '156',
				'iso-3166-2' => 'ISO 3166-2:CN'
			),
			'CX' => array(
				'name'       => 'Christmas Island',
				'alpha-2'    => 'CX',
				'alpha-3'    => 'CXR',
				'code'       => '162',
				'iso-3166-2' => 'ISO 3166-2:CX'
			),
			'CC' => array(
				'name'       => 'Cocos (Keeling) Islands',
				'alpha-2'    => 'CC',
				'alpha-3'    => 'CCK',
				'code'       => '166',
				'iso-3166-2' => 'ISO 3166-2:CC'
			),
			'CO' => array(
				'name'       => 'Colombia',
				'alpha-2'    => 'CO',
				'alpha-3'    => 'COL',
				'code'       => '170',
				'iso-3166-2' => 'ISO 3166-2:CO'
			),
			'KM' => array(
				'name'       => 'Comoros',
				'alpha-2'    => 'KM',
				'alpha-3'    => 'COM',
				'code'       => '174',
				'iso-3166-2' => 'ISO 3166-2:KM'
			),
			'CG' => array(
				'name'       => 'Congo',
				'alpha-2'    => 'CG',
				'alpha-3'    => 'COG',
				'code'       => '178',
				'iso-3166-2' => 'ISO 3166-2:CG'
			),
			'CD' => array(
				'name'       => 'Congo (Democratic Republic)',
				'alpha-2'    => 'CD',
				'alpha-3'    => 'COD',
				'code'       => '180',
				'iso-3166-2' => 'ISO 3166-2:CD'
			),
			'CK' => array(
				'name'       => 'Cook Islands',
				'alpha-2'    => 'CK',
				'alpha-3'    => 'COK',
				'code'       => '184',
				'iso-3166-2' => 'ISO 3166-2:CK'
			),
			'CR' => array(
				'name'       => 'Costa Rica',
				'alpha-2'    => 'CR',
				'alpha-3'    => 'CRI',
				'code'       => '188',
				'iso-3166-2' => 'ISO 3166-2:CR'
			),
			'CI' => array(
				'name'       => 'Côte d\'Ivoire',
				'alpha-2'    => 'CI',
				'alpha-3'    => 'CIV',
				'code'       => '384',
				'iso-3166-2' => 'ISO 3166-2:CI'
			),
			'HR' => array(
				'name'       => 'Croatia',
				'alpha-2'    => 'HR',
				'alpha-3'    => 'HRV',
				'code'       => '191',
				'iso-3166-2' => 'ISO 3166-2:HR'
			),
			'CU' => array(
				'name'       => 'Cuba',
				'alpha-2'    => 'CU',
				'alpha-3'    => 'CUB',
				'code'       => '192',
				'iso-3166-2' => 'ISO 3166-2:CU'
			),
			'CW' => array(
				'name'       => 'Curaçao',
				'alpha-2'    => 'CW',
				'alpha-3'    => 'CUW',
				'code'       => '531',
				'iso-3166-2' => 'ISO 3166-2:CW'
			),
			'CY' => array(
				'name'       => 'Cyprus',
				'alpha-2'    => 'CY',
				'alpha-3'    => 'CYP',
				'code'       => '196',
				'iso-3166-2' => 'ISO 3166-2:CY'
			),
			'CZ' => array(
				'name'       => 'Czech Republic',
				'alpha-2'    => 'CZ',
				'alpha-3'    => 'CZE',
				'code'       => '203',
				'iso-3166-2' => 'ISO 3166-2:CZ'
			),
			'DK' => array(
				'name'       => 'Denmark',
				'alpha-2'    => 'DK',
				'alpha-3'    => 'DNK',
				'code'       => '208',
				'iso-3166-2' => 'ISO 3166-2:DK'
			),
			'DJ' => array(
				'name'       => 'Djibouti',
				'alpha-2'    => 'DJ',
				'alpha-3'    => 'DJI',
				'code'       => '262',
				'iso-3166-2' => 'ISO 3166-2:DJ'
			),
			'DM' => array(
				'name'       => 'Dominica',
				'alpha-2'    => 'DM',
				'alpha-3'    => 'DMA',
				'code'       => '212',
				'iso-3166-2' => 'ISO 3166-2:DM'
			),
			'DO' => array(
				'name'       => 'Dominican Republic',
				'alpha-2'    => 'DO',
				'alpha-3'    => 'DOM',
				'code'       => '214',
				'iso-3166-2' => 'ISO 3166-2:DO'
			),
			'EC' => array(
				'name'       => 'Ecuador',
				'alpha-2'    => 'EC',
				'alpha-3'    => 'ECU',
				'code'       => '218',
				'iso-3166-2' => 'ISO 3166-2:EC'
			),
			'EG' => array(
				'name'       => 'Egypt',
				'alpha-2'    => 'EG',
				'alpha-3'    => 'EGY',
				'code'       => '818',
				'iso-3166-2' => 'ISO 3166-2:EG'
			),
			'SV' => array(
				'name'       => 'El Salvador',
				'alpha-2'    => 'SV',
				'alpha-3'    => 'SLV',
				'code'       => '222',
				'iso-3166-2' => 'ISO 3166-2:SV'
			),
			'GQ' => array(
				'name'       => 'Equatorial Guinea',
				'alpha-2'    => 'GQ',
				'alpha-3'    => 'GNQ',
				'code'       => '226',
				'iso-3166-2' => 'ISO 3166-2:GQ'
			),
			'ER' => array(
				'name'       => 'Eritrea',
				'alpha-2'    => 'ER',
				'alpha-3'    => 'ERI',
				'code'       => '232',
				'iso-3166-2' => 'ISO 3166-2:ER'
			),
			'EE' => array(
				'name'       => 'Estonia',
				'alpha-2'    => 'EE',
				'alpha-3'    => 'EST',
				'code'       => '233',
				'iso-3166-2' => 'ISO 3166-2:EE'
			),
			'ET' => array(
				'name'       => 'Ethiopia',
				'alpha-2'    => 'ET',
				'alpha-3'    => 'ETH',
				'code'       => '231',
				'iso-3166-2' => 'ISO 3166-2:ET'
			),
			'FK' => array(
				'name'       => 'Falkland Islands (Malvinas)',
				'alpha-2'    => 'FK',
				'alpha-3'    => 'FLK',
				'code'       => '238',
				'iso-3166-2' => 'ISO 3166-2:FK'
			),
			'FO' => array(
				'name'       => 'Faroe Islands',
				'alpha-2'    => 'FO',
				'alpha-3'    => 'FRO',
				'code'       => '234',
				'iso-3166-2' => 'ISO 3166-2:FO'
			),
			'FJ' => array(
				'name'       => 'Fiji',
				'alpha-2'    => 'FJ',
				'alpha-3'    => 'FJI',
				'code'       => '242',
				'iso-3166-2' => 'ISO 3166-2:FJ'
			),
			'FI' => array(
				'name'       => 'Finland',
				'alpha-2'    => 'FI',
				'alpha-3'    => 'FIN',
				'code'       => '246',
				'iso-3166-2' => 'ISO 3166-2:FI'
			),
			'FR' => array(
				'name'       => 'France',
				'alpha-2'    => 'FR',
				'alpha-3'    => 'FRA',
				'code'       => '250',
				'iso-3166-2' => 'ISO 3166-2:FR'
			),
			'GF' => array(
				'name'       => 'French Guiana',
				'alpha-2'    => 'GF',
				'alpha-3'    => 'GUF',
				'code'       => '254',
				'iso-3166-2' => 'ISO 3166-2:GF'
			),
			'PF' => array(
				'name'       => 'French Polynesia',
				'alpha-2'    => 'PF',
				'alpha-3'    => 'PYF',
				'code'       => '258',
				'iso-3166-2' => 'ISO 3166-2:PF'
			),
			'TF' => array(
				'name'       => 'French Southern Territories',
				'alpha-2'    => 'TF',
				'alpha-3'    => 'ATF',
				'code'       => '260',
				'iso-3166-2' => 'ISO 3166-2:TF'
			),
			'GA' => array(
				'name'       => 'Gabon',
				'alpha-2'    => 'GA',
				'alpha-3'    => 'GAB',
				'code'       => '266',
				'iso-3166-2' => 'ISO 3166-2:GA'
			),
			'GM' => array(
				'name'       => 'Gambia',
				'alpha-2'    => 'GM',
				'alpha-3'    => 'GMB',
				'code'       => '270',
				'iso-3166-2' => 'ISO 3166-2:GM'
			),
			'GE' => array(
				'name'       => 'Georgia',
				'alpha-2'    => 'GE',
				'alpha-3'    => 'GEO',
				'code'       => '268',
				'iso-3166-2' => 'ISO 3166-2:GE'
			),
			'DE' => array(
				'name'       => 'Germany',
				'alpha-2'    => 'DE',
				'alpha-3'    => 'DEU',
				'code'       => '276',
				'iso-3166-2' => 'ISO 3166-2:DE'
			),
			'GH' => array(
				'name'       => 'Ghana',
				'alpha-2'    => 'GH',
				'alpha-3'    => 'GHA',
				'code'       => '288',
				'iso-3166-2' => 'ISO 3166-2:GH'
			),
			'GI' => array(
				'name'       => 'Gibraltar',
				'alpha-2'    => 'GI',
				'alpha-3'    => 'GIB',
				'code'       => '292',
				'iso-3166-2' => 'ISO 3166-2:GI'
			),
			'GR' => array(
				'name'       => 'Greece',
				'alpha-2'    => 'GR',
				'alpha-3'    => 'GRC',
				'code'       => '300',
				'iso-3166-2' => 'ISO 3166-2:GR'
			),
			'GL' => array(
				'name'       => 'Greenland',
				'alpha-2'    => 'GL',
				'alpha-3'    => 'GRL',
				'code'       => '304',
				'iso-3166-2' => 'ISO 3166-2:GL'
			),
			'GD' => array(
				'name'       => 'Grenada',
				'alpha-2'    => 'GD',
				'alpha-3'    => 'GRD',
				'code'       => '308',
				'iso-3166-2' => 'ISO 3166-2:GD'
			),
			'GP' => array(
				'name'       => 'Guadeloupe',
				'alpha-2'    => 'GP',
				'alpha-3'    => 'GLP',
				'code'       => '312',
				'iso-3166-2' => 'ISO 3166-2:GP'
			),
			'GU' => array(
				'name'       => 'Guam',
				'alpha-2'    => 'GU',
				'alpha-3'    => 'GUM',
				'code'       => '316',
				'iso-3166-2' => 'ISO 3166-2:GU'
			),
			'GT' => array(
				'name'       => 'Guatemala',
				'alpha-2'    => 'GT',
				'alpha-3'    => 'GTM',
				'code'       => '320',
				'iso-3166-2' => 'ISO 3166-2:GT'
			),
			'GG' => array(
				'name'       => 'Guernsey',
				'alpha-2'    => 'GG',
				'alpha-3'    => 'GGY',
				'code'       => '831',
				'iso-3166-2' => 'ISO 3166-2:GG'
			),
			'GN' => array(
				'name'       => 'Guinea',
				'alpha-2'    => 'GN',
				'alpha-3'    => 'GIN',
				'code'       => '324',
				'iso-3166-2' => 'ISO 3166-2:GN'
			),
			'GW' => array(
				'name'       => 'Guinea-Bissau',
				'alpha-2'    => 'GW',
				'alpha-3'    => 'GNB',
				'code'       => '624',
				'iso-3166-2' => 'ISO 3166-2:GW'
			),
			'GY' => array(
				'name'       => 'Guyana',
				'alpha-2'    => 'GY',
				'alpha-3'    => 'GUY',
				'code'       => '328',
				'iso-3166-2' => 'ISO 3166-2:GY'
			),
			'HT' => array(
				'name'       => 'Haiti',
				'alpha-2'    => 'HT',
				'alpha-3'    => 'HTI',
				'code'       => '332',
				'iso-3166-2' => 'ISO 3166-2:HT'
			),
			'HM' => array(
				'name'       => 'Heard Island and McDonald Islands',
				'alpha-2'    => 'HM',
				'alpha-3'    => 'HMD',
				'code'       => '334',
				'iso-3166-2' => 'ISO 3166-2:HM'
			),
			'VA' => array(
				'name'       => 'Holy See',
				'alpha-2'    => 'VA',
				'alpha-3'    => 'VAT',
				'code'       => '336',
				'iso-3166-2' => 'ISO 3166-2:VA'
			),
			'HN' => array(
				'name'       => 'Honduras',
				'alpha-2'    => 'HN',
				'alpha-3'    => 'HND',
				'code'       => '340',
				'iso-3166-2' => 'ISO 3166-2:HN'
			),
			'HK' => array(
				'name'       => 'Hong Kong',
				'alpha-2'    => 'HK',
				'alpha-3'    => 'HKG',
				'code'       => '344',
				'iso-3166-2' => 'ISO 3166-2:HK'
			),
			'HU' => array(
				'name'       => 'Hungary',
				'alpha-2'    => 'HU',
				'alpha-3'    => 'HUN',
				'code'       => '348',
				'iso-3166-2' => 'ISO 3166-2:HU'
			),
			'IS' => array(
				'name'       => 'Iceland',
				'alpha-2'    => 'IS',
				'alpha-3'    => 'ISL',
				'code'       => '352',
				'iso-3166-2' => 'ISO 3166-2:IS'
			),
			'IN' => array(
				'name'       => 'India',
				'alpha-2'    => 'IN',
				'alpha-3'    => 'IND',
				'code'       => '356',
				'iso-3166-2' => 'ISO 3166-2:IN'
			),
			'ID' => array(
				'name'       => 'Indonesia',
				'alpha-2'    => 'ID',
				'alpha-3'    => 'IDN',
				'code'       => '360',
				'iso-3166-2' => 'ISO 3166-2:ID'
			),
			'IR' => array(
				'name'       => 'Iran',
				'alpha-2'    => 'IR',
				'alpha-3'    => 'IRN',
				'code'       => '364',
				'iso-3166-2' => 'ISO 3166-2:IR'
			),
			'IQ' => array(
				'name'       => 'Iraq',
				'alpha-2'    => 'IQ',
				'alpha-3'    => 'IRQ',
				'code'       => '368',
				'iso-3166-2' => 'ISO 3166-2:IQ'
			),
			'IE' => array(
				'name'       => 'Ireland',
				'alpha-2'    => 'IE',
				'alpha-3'    => 'IRL',
				'code'       => '372',
				'iso-3166-2' => 'ISO 3166-2:IE'
			),
			'IM' => array(
				'name'       => 'Isle of Man',
				'alpha-2'    => 'IM',
				'alpha-3'    => 'IMN',
				'code'       => '833',
				'iso-3166-2' => 'ISO 3166-2:IM'
			),
			'IL' => array(
				'name'       => 'Israel',
				'alpha-2'    => 'IL',
				'alpha-3'    => 'ISR',
				'code'       => '376',
				'iso-3166-2' => 'ISO 3166-2:IL'
			),
			'IT' => array(
				'name'       => 'Italy',
				'alpha-2'    => 'IT',
				'alpha-3'    => 'ITA',
				'code'       => '380',
				'iso-3166-2' => 'ISO 3166-2:IT'
			),
			'JM' => array(
				'name'       => 'Jamaica',
				'alpha-2'    => 'JM',
				'alpha-3'    => 'JAM',
				'code'       => '388',
				'iso-3166-2' => 'ISO 3166-2:JM'
			),
			'JP' => array(
				'name'       => 'Japan',
				'alpha-2'    => 'JP',
				'alpha-3'    => 'JPN',
				'code'       => '392',
				'iso-3166-2' => 'ISO 3166-2:JP'
			),
			'JE' => array(
				'name'       => 'Jersey',
				'alpha-2'    => 'JE',
				'alpha-3'    => 'JEY',
				'code'       => '832',
				'iso-3166-2' => 'ISO 3166-2:JE'
			),
			'JO' => array(
				'name'       => 'Jordan',
				'alpha-2'    => 'JO',
				'alpha-3'    => 'JOR',
				'code'       => '400',
				'iso-3166-2' => 'ISO 3166-2:JO'
			),
			'KZ' => array(
				'name'       => 'Kazakhstan',
				'alpha-2'    => 'KZ',
				'alpha-3'    => 'KAZ',
				'code'       => '398',
				'iso-3166-2' => 'ISO 3166-2:KZ'
			),
			'KE' => array(
				'name'       => 'Kenya',
				'alpha-2'    => 'KE',
				'alpha-3'    => 'KEN',
				'code'       => '404',
				'iso-3166-2' => 'ISO 3166-2:KE'
			),
			'KI' => array(
				'name'       => 'Kiribati',
				'alpha-2'    => 'KI',
				'alpha-3'    => 'KIR',
				'code'       => '296',
				'iso-3166-2' => 'ISO 3166-2:KI'
			),
			'KP' => array(
				'name'       => 'Korea (Democratic People\'s Republic of)',
				'alpha-2'    => 'KP',
				'alpha-3'    => 'PRK',
				'code'       => '408',
				'iso-3166-2' => 'ISO 3166-2:KP'
			),
			'KR' => array(
				'name'       => 'Korea (Republic of)',
				'alpha-2'    => 'KR',
				'alpha-3'    => 'KOR',
				'code'       => '410',
				'iso-3166-2' => 'ISO 3166-2:KR'
			),
			'KW' => array(
				'name'       => 'Kuwait',
				'alpha-2'    => 'KW',
				'alpha-3'    => 'KWT',
				'code'       => '414',
				'iso-3166-2' => 'ISO 3166-2:KW'
			),
			'KG' => array(
				'name'       => 'Kyrgyzstan',
				'alpha-2'    => 'KG',
				'alpha-3'    => 'KGZ',
				'code'       => '417',
				'iso-3166-2' => 'ISO 3166-2:KG'
			),
			'LA' => array(
				'name'       => 'Laos',
				'alpha-2'    => 'LA',
				'alpha-3'    => 'LAO',
				'code'       => '418',
				'iso-3166-2' => 'ISO 3166-2:LA'
			),
			'LV' => array(
				'name'       => 'Latvia',
				'alpha-2'    => 'LV',
				'alpha-3'    => 'LVA',
				'code'       => '428',
				'iso-3166-2' => 'ISO 3166-2:LV'
			),
			'LB' => array(
				'name'       => 'Lebanon',
				'alpha-2'    => 'LB',
				'alpha-3'    => 'LBN',
				'code'       => '422',
				'iso-3166-2' => 'ISO 3166-2:LB'
			),
			'LS' => array(
				'name'       => 'Lesotho',
				'alpha-2'    => 'LS',
				'alpha-3'    => 'LSO',
				'code'       => '426',
				'iso-3166-2' => 'ISO 3166-2:LS'
			),
			'LR' => array(
				'name'       => 'Liberia',
				'alpha-2'    => 'LR',
				'alpha-3'    => 'LBR',
				'code'       => '430',
				'iso-3166-2' => 'ISO 3166-2:LR'
			),
			'LY' => array(
				'name'       => 'Libya',
				'alpha-2'    => 'LY',
				'alpha-3'    => 'LBY',
				'code'       => '434',
				'iso-3166-2' => 'ISO 3166-2:LY'
			),
			'LI' => array(
				'name'       => 'Liechtenstein',
				'alpha-2'    => 'LI',
				'alpha-3'    => 'LIE',
				'code'       => '438',
				'iso-3166-2' => 'ISO 3166-2:LI'
			),
			'LT' => array(
				'name'       => 'Lithuania',
				'alpha-2'    => 'LT',
				'alpha-3'    => 'LTU',
				'code'       => '440',
				'iso-3166-2' => 'ISO 3166-2:LT'
			),
			'LU' => array(
				'name'       => 'Luxembourg',
				'alpha-2'    => 'LU',
				'alpha-3'    => 'LUX',
				'code'       => '442',
				'iso-3166-2' => 'ISO 3166-2:LU'
			),
			'MO' => array(
				'name'       => 'Macao',
				'alpha-2'    => 'MO',
				'alpha-3'    => 'MAC',
				'code'       => '446',
				'iso-3166-2' => 'ISO 3166-2:MO'
			),
			'MK' => array(
				'name'       => 'Macedonia',
				'alpha-2'    => 'MK',
				'alpha-3'    => 'MKD',
				'code'       => '807',
				'iso-3166-2' => 'ISO 3166-2:MK'
			),
			'MG' => array(
				'name'       => 'Madagascar',
				'alpha-2'    => 'MG',
				'alpha-3'    => 'MDG',
				'code'       => '450',
				'iso-3166-2' => 'ISO 3166-2:MG'
			),
			'MW' => array(
				'name'       => 'Malawi',
				'alpha-2'    => 'MW',
				'alpha-3'    => 'MWI',
				'code'       => '454',
				'iso-3166-2' => 'ISO 3166-2:MW'
			),
			'MY' => array(
				'name'       => 'Malaysia',
				'alpha-2'    => 'MY',
				'alpha-3'    => 'MYS',
				'code'       => '458',
				'iso-3166-2' => 'ISO 3166-2:MY'
			),
			'MV' => array(
				'name'       => 'Maldives',
				'alpha-2'    => 'MV',
				'alpha-3'    => 'MDV',
				'code'       => '462',
				'iso-3166-2' => 'ISO 3166-2:MV'
			),
			'ML' => array(
				'name'       => 'Mali',
				'alpha-2'    => 'ML',
				'alpha-3'    => 'MLI',
				'code'       => '466',
				'iso-3166-2' => 'ISO 3166-2:ML'
			),
			'MT' => array(
				'name'       => 'Malta',
				'alpha-2'    => 'MT',
				'alpha-3'    => 'MLT',
				'code'       => '470',
				'iso-3166-2' => 'ISO 3166-2:MT'
			),
			'MH' => array(
				'name'       => 'Marshall Islands',
				'alpha-2'    => 'MH',
				'alpha-3'    => 'MHL',
				'code'       => '584',
				'iso-3166-2' => 'ISO 3166-2:MH'
			),
			'MQ' => array(
				'name'       => 'Martinique',
				'alpha-2'    => 'MQ',
				'alpha-3'    => 'MTQ',
				'code'       => '474',
				'iso-3166-2' => 'ISO 3166-2:MQ'
			),
			'MR' => array(
				'name'       => 'Mauritania',
				'alpha-2'    => 'MR',
				'alpha-3'    => 'MRT',
				'code'       => '478',
				'iso-3166-2' => 'ISO 3166-2:MR'
			),
			'MU' => array(
				'name'       => 'Mauritius',
				'alpha-2'    => 'MU',
				'alpha-3'    => 'MUS',
				'code'       => '480',
				'iso-3166-2' => 'ISO 3166-2:MU'
			),
			'YT' => array(
				'name'       => 'Mayotte',
				'alpha-2'    => 'YT',
				'alpha-3'    => 'MYT',
				'code'       => '175',
				'iso-3166-2' => 'ISO 3166-2:YT'
			),
			'MX' => array(
				'name'       => 'Mexico',
				'alpha-2'    => 'MX',
				'alpha-3'    => 'MEX',
				'code'       => '484',
				'iso-3166-2' => 'ISO 3166-2:MX'
			),
			'FM' => array(
				'name'       => 'Micronesia',
				'alpha-2'    => 'FM',
				'alpha-3'    => 'FSM',
				'code'       => '583',
				'iso-3166-2' => 'ISO 3166-2:FM'
			),
			'MD' => array(
				'name'       => 'Moldova',
				'alpha-2'    => 'MD',
				'alpha-3'    => 'MDA',
				'code'       => '498',
				'iso-3166-2' => 'ISO 3166-2:MD'
			),
			'MC' => array(
				'name'       => 'Monaco',
				'alpha-2'    => 'MC',
				'alpha-3'    => 'MCO',
				'code'       => '492',
				'iso-3166-2' => 'ISO 3166-2:MC'
			),
			'MN' => array(
				'name'       => 'Mongolia',
				'alpha-2'    => 'MN',
				'alpha-3'    => 'MNG',
				'code'       => '496',
				'iso-3166-2' => 'ISO 3166-2:MN'
			),
			'ME' => array(
				'name'       => 'Montenegro',
				'alpha-2'    => 'ME',
				'alpha-3'    => 'MNE',
				'code'       => '499',
				'iso-3166-2' => 'ISO 3166-2:ME'
			),
			'MS' => array(
				'name'       => 'Montserrat',
				'alpha-2'    => 'MS',
				'alpha-3'    => 'MSR',
				'code'       => '500',
				'iso-3166-2' => 'ISO 3166-2:MS'
			),
			'MA' => array(
				'name'       => 'Morocco',
				'alpha-2'    => 'MA',
				'alpha-3'    => 'MAR',
				'code'       => '504',
				'iso-3166-2' => 'ISO 3166-2:MA'
			),
			'MZ' => array(
				'name'       => 'Mozambique',
				'alpha-2'    => 'MZ',
				'alpha-3'    => 'MOZ',
				'code'       => '508',
				'iso-3166-2' => 'ISO 3166-2:MZ'
			),
			'MM' => array(
				'name'       => 'Myanmar',
				'alpha-2'    => 'MM',
				'alpha-3'    => 'MMR',
				'code'       => '104',
				'iso-3166-2' => 'ISO 3166-2:MM'
			),
			'NA' => array(
				'name'       => 'Namibia',
				'alpha-2'    => 'NA',
				'alpha-3'    => 'NAM',
				'code'       => '516',
				'iso-3166-2' => 'ISO 3166-2:NA'
			),
			'NR' => array(
				'name'       => 'Nauru',
				'alpha-2'    => 'NR',
				'alpha-3'    => 'NRU',
				'code'       => '520',
				'iso-3166-2' => 'ISO 3166-2:NR'
			),
			'NP' => array(
				'name'       => 'Nepal',
				'alpha-2'    => 'NP',
				'alpha-3'    => 'NPL',
				'code'       => '524',
				'iso-3166-2' => 'ISO 3166-2:NP'
			),
			'NL' => array(
				'name'       => 'Netherlands',
				'alpha-2'    => 'NL',
				'alpha-3'    => 'NLD',
				'code'       => '528',
				'iso-3166-2' => 'ISO 3166-2:NL'
			),
			'NC' => array(
				'name'       => 'New Caledonia',
				'alpha-2'    => 'NC',
				'alpha-3'    => 'NCL',
				'code'       => '540',
				'iso-3166-2' => 'ISO 3166-2:NC'
			),
			'NZ' => array(
				'name'       => 'New Zealand',
				'alpha-2'    => 'NZ',
				'alpha-3'    => 'NZL',
				'code'       => '554',
				'iso-3166-2' => 'ISO 3166-2:NZ'
			),
			'NI' => array(
				'name'       => 'Nicaragua',
				'alpha-2'    => 'NI',
				'alpha-3'    => 'NIC',
				'code'       => '558',
				'iso-3166-2' => 'ISO 3166-2:NI'
			),
			'NE' => array(
				'name'       => 'Niger',
				'alpha-2'    => 'NE',
				'alpha-3'    => 'NER',
				'code'       => '562',
				'iso-3166-2' => 'ISO 3166-2:NE'
			),
			'NG' => array(
				'name'       => 'Nigeria',
				'alpha-2'    => 'NG',
				'alpha-3'    => 'NGA',
				'code'       => '566',
				'iso-3166-2' => 'ISO 3166-2:NG'
			),
			'NU' => array(
				'name'       => 'Niue',
				'alpha-2'    => 'NU',
				'alpha-3'    => 'NIU',
				'code'       => '570',
				'iso-3166-2' => 'ISO 3166-2:NU'
			),
			'NF' => array(
				'name'       => 'Norfolk Island',
				'alpha-2'    => 'NF',
				'alpha-3'    => 'NFK',
				'code'       => '574',
				'iso-3166-2' => 'ISO 3166-2:NF'
			),
			'MP' => array(
				'name'       => 'Northern Mariana Islands',
				'alpha-2'    => 'MP',
				'alpha-3'    => 'MNP',
				'code'       => '580',
				'iso-3166-2' => 'ISO 3166-2:MP'
			),
			'NO' => array(
				'name'       => 'Norway',
				'alpha-2'    => 'NO',
				'alpha-3'    => 'NOR',
				'code'       => '578',
				'iso-3166-2' => 'ISO 3166-2:NO'
			),
			'OM' => array(
				'name'       => 'Oman',
				'alpha-2'    => 'OM',
				'alpha-3'    => 'OMN',
				'code'       => '512',
				'iso-3166-2' => 'ISO 3166-2:OM'
			),
			'PK' => array(
				'name'       => 'Pakistan',
				'alpha-2'    => 'PK',
				'alpha-3'    => 'PAK',
				'code'       => '586',
				'iso-3166-2' => 'ISO 3166-2:PK'
			),
			'PW' => array(
				'name'       => 'Palau',
				'alpha-2'    => 'PW',
				'alpha-3'    => 'PLW',
				'code'       => '585',
				'iso-3166-2' => 'ISO 3166-2:PW'
			),
			'PS' => array(
				'name'       => 'Palestine',
				'alpha-2'    => 'PS',
				'alpha-3'    => 'PSE',
				'code'       => '275',
				'iso-3166-2' => 'ISO 3166-2:PS'
			),
			'PA' => array(
				'name'       => 'Panama',
				'alpha-2'    => 'PA',
				'alpha-3'    => 'PAN',
				'code'       => '591',
				'iso-3166-2' => 'ISO 3166-2:PA'
			),
			'PG' => array(
				'name'       => 'Papua New Guinea',
				'alpha-2'    => 'PG',
				'alpha-3'    => 'PNG',
				'code'       => '598',
				'iso-3166-2' => 'ISO 3166-2:PG'
			),
			'PY' => array(
				'name'       => 'Paraguay',
				'alpha-2'    => 'PY',
				'alpha-3'    => 'PRY',
				'code'       => '600',
				'iso-3166-2' => 'ISO 3166-2:PY'
			),
			'PE' => array(
				'name'       => 'Peru',
				'alpha-2'    => 'PE',
				'alpha-3'    => 'PER',
				'code'       => '604',
				'iso-3166-2' => 'ISO 3166-2:PE'
			),
			'PH' => array(
				'name'       => 'Philippines',
				'alpha-2'    => 'PH',
				'alpha-3'    => 'PHL',
				'code'       => '608',
				'iso-3166-2' => 'ISO 3166-2:PH'
			),
			'PN' => array(
				'name'       => 'Pitcairn',
				'alpha-2'    => 'PN',
				'alpha-3'    => 'PCN',
				'code'       => '612',
				'iso-3166-2' => 'ISO 3166-2:PN'
			),
			'PL' => array(
				'name'       => 'Poland',
				'alpha-2'    => 'PL',
				'alpha-3'    => 'POL',
				'code'       => '616',
				'iso-3166-2' => 'ISO 3166-2:PL'
			),
			'PT' => array(
				'name'       => 'Portugal',
				'alpha-2'    => 'PT',
				'alpha-3'    => 'PRT',
				'code'       => '620',
				'iso-3166-2' => 'ISO 3166-2:PT'
			),
			'PR' => array(
				'name'       => 'Puerto Rico',
				'alpha-2'    => 'PR',
				'alpha-3'    => 'PRI',
				'code'       => '630',
				'iso-3166-2' => 'ISO 3166-2:PR'
			),
			'QA' => array(
				'name'       => 'Qatar',
				'alpha-2'    => 'QA',
				'alpha-3'    => 'QAT',
				'code'       => '634',
				'iso-3166-2' => 'ISO 3166-2:QA'
			),
			'RE' => array(
				'name'       => 'Réunion',
				'alpha-2'    => 'RE',
				'alpha-3'    => 'REU',
				'code'       => '638',
				'iso-3166-2' => 'ISO 3166-2:RE'
			),
			'RO' => array(
				'name'       => 'Romania',
				'alpha-2'    => 'RO',
				'alpha-3'    => 'ROU',
				'code'       => '642',
				'iso-3166-2' => 'ISO 3166-2:RO'
			),
			'RU' => array(
				'name'       => 'Russian Federation',
				'alpha-2'    => 'RU',
				'alpha-3'    => 'RUS',
				'code'       => '643',
				'iso-3166-2' => 'ISO 3166-2:RU'
			),
			'RW' => array(
				'name'       => 'Rwanda',
				'alpha-2'    => 'RW',
				'alpha-3'    => 'RWA',
				'code'       => '646',
				'iso-3166-2' => 'ISO 3166-2:RW'
			),
			'BL' => array(
				'name'       => 'Saint Barthélemy',
				'alpha-2'    => 'BL',
				'alpha-3'    => 'BLM',
				'code'       => '652',
				'iso-3166-2' => 'ISO 3166-2:BL'
			),
			'SH' => array(
				'name'       => 'Saint Helena, Ascension and Tristan da Cunha',
				'alpha-2'    => 'SH',
				'alpha-3'    => 'SHN',
				'code'       => '654',
				'iso-3166-2' => 'ISO 3166-2:SH'
			),
			'KN' => array(
				'name'       => 'Saint Kitts and Nevis',
				'alpha-2'    => 'KN',
				'alpha-3'    => 'KNA',
				'code'       => '659',
				'iso-3166-2' => 'ISO 3166-2:KN'
			),
			'LC' => array(
				'name'       => 'Saint Lucia',
				'alpha-2'    => 'LC',
				'alpha-3'    => 'LCA',
				'code'       => '662',
				'iso-3166-2' => 'ISO 3166-2:LC'
			),
			'MF' => array(
				'name'       => 'Saint Martin',
				'alpha-2'    => 'MF',
				'alpha-3'    => 'MAF',
				'code'       => '663',
				'iso-3166-2' => 'ISO 3166-2:MF'
			),
			'PM' => array(
				'name'       => 'Saint Pierre and Miquelon',
				'alpha-2'    => 'PM',
				'alpha-3'    => 'SPM',
				'code'       => '666',
				'iso-3166-2' => 'ISO 3166-2:PM'
			),
			'VC' => array(
				'name'       => 'Saint Vincent and the Grenadines',
				'alpha-2'    => 'VC',
				'alpha-3'    => 'VCT',
				'code'       => '670',
				'iso-3166-2' => 'ISO 3166-2:VC'
			),
			'WS' => array(
				'name'       => 'Samoa',
				'alpha-2'    => 'WS',
				'alpha-3'    => 'WSM',
				'code'       => '882',
				'iso-3166-2' => 'ISO 3166-2:WS'
			),
			'SM' => array(
				'name'       => 'San Marino',
				'alpha-2'    => 'SM',
				'alpha-3'    => 'SMR',
				'code'       => '674',
				'iso-3166-2' => 'ISO 3166-2:SM'
			),
			'ST' => array(
				'name'       => 'Sao Tome and Principe',
				'alpha-2'    => 'ST',
				'alpha-3'    => 'STP',
				'code'       => '678',
				'iso-3166-2' => 'ISO 3166-2:ST'
			),
			'SA' => array(
				'name'       => 'Saudi Arabia',
				'alpha-2'    => 'SA',
				'alpha-3'    => 'SAU',
				'code'       => '682',
				'iso-3166-2' => 'ISO 3166-2:SA'
			),
			'SN' => array(
				'name'       => 'Senegal',
				'alpha-2'    => 'SN',
				'alpha-3'    => 'SEN',
				'code'       => '686',
				'iso-3166-2' => 'ISO 3166-2:SN'
			),
			'RS' => array(
				'name'       => 'Serbia',
				'alpha-2'    => 'RS',
				'alpha-3'    => 'SRB',
				'code'       => '688',
				'iso-3166-2' => 'ISO 3166-2:RS'
			),
			'SC' => array(
				'name'       => 'Seychelles',
				'alpha-2'    => 'SC',
				'alpha-3'    => 'SYC',
				'code'       => '690',
				'iso-3166-2' => 'ISO 3166-2:SC'
			),
			'SL' => array(
				'name'       => 'Sierra Leone',
				'alpha-2'    => 'SL',
				'alpha-3'    => 'SLE',
				'code'       => '694',
				'iso-3166-2' => 'ISO 3166-2:SL'
			),
			'SG' => array(
				'name'       => 'Singapore',
				'alpha-2'    => 'SG',
				'alpha-3'    => 'SGP',
				'code'       => '702',
				'iso-3166-2' => 'ISO 3166-2:SG'
			),
			'SX' => array(
				'name'       => 'Sint Maarten',
				'alpha-2'    => 'SX',
				'alpha-3'    => 'SXM',
				'code'       => '534',
				'iso-3166-2' => 'ISO 3166-2:SX'
			),
			'SK' => array(
				'name'       => 'Slovakia',
				'alpha-2'    => 'SK',
				'alpha-3'    => 'SVK',
				'code'       => '703',
				'iso-3166-2' => 'ISO 3166-2:SK'
			),
			'SI' => array(
				'name'       => 'Slovenia',
				'alpha-2'    => 'SI',
				'alpha-3'    => 'SVN',
				'code'       => '705',
				'iso-3166-2' => 'ISO 3166-2:SI'
			),
			'SB' => array(
				'name'       => 'Solomon Islands',
				'alpha-2'    => 'SB',
				'alpha-3'    => 'SLB',
				'code'       => '090',
				'iso-3166-2' => 'ISO 3166-2:SB'
			),
			'SO' => array(
				'name'       => 'Somalia',
				'alpha-2'    => 'SO',
				'alpha-3'    => 'SOM',
				'code'       => '706',
				'iso-3166-2' => 'ISO 3166-2:SO'
			),
			'ZA' => array(
				'name'       => 'South Africa',
				'alpha-2'    => 'ZA',
				'alpha-3'    => 'ZAF',
				'code'       => '710',
				'iso-3166-2' => 'ISO 3166-2:ZA'
			),
			'GS' => array(
				'name'       => 'South Georgia and the South Sandwich Islands',
				'alpha-2'    => 'GS',
				'alpha-3'    => 'SGS',
				'code'       => '239',
				'iso-3166-2' => 'ISO 3166-2:GS'
			),
			'SS' => array(
				'name'       => 'South Sudan',
				'alpha-2'    => 'SS',
				'alpha-3'    => 'SSD',
				'code'       => '728',
				'iso-3166-2' => 'ISO 3166-2:SS'
			),
			'ES' => array(
				'name'       => 'Spain',
				'alpha-2'    => 'ES',
				'alpha-3'    => 'ESP',
				'code'       => '724',
				'iso-3166-2' => 'ISO 3166-2:ES'
			),
			'LK' => array(
				'name'       => 'Sri Lanka',
				'alpha-2'    => 'LK',
				'alpha-3'    => 'LKA',
				'code'       => '144',
				'iso-3166-2' => 'ISO 3166-2:LK'
			),
			'SD' => array(
				'name'       => 'Sudan',
				'alpha-2'    => 'SD',
				'alpha-3'    => 'SDN',
				'code'       => '729',
				'iso-3166-2' => 'ISO 3166-2:SD'
			),
			'SR' => array(
				'name'       => 'Suriname',
				'alpha-2'    => 'SR',
				'alpha-3'    => 'SUR',
				'code'       => '740',
				'iso-3166-2' => 'ISO 3166-2:SR'
			),
			'SJ' => array(
				'name'       => 'Svalbard and Jan Mayen',
				'alpha-2'    => 'SJ',
				'alpha-3'    => 'SJM',
				'code'       => '744',
				'iso-3166-2' => 'ISO 3166-2:SJ'
			),
			'SZ' => array(
				'name'       => 'Swaziland',
				'alpha-2'    => 'SZ',
				'alpha-3'    => 'SWZ',
				'code'       => '748',
				'iso-3166-2' => 'ISO 3166-2:SZ'
			),
			'SE' => array(
				'name'       => 'Sweden',
				'alpha-2'    => 'SE',
				'alpha-3'    => 'SWE',
				'code'       => '752',
				'iso-3166-2' => 'ISO 3166-2:SE'
			),
			'CH' => array(
				'name'       => 'Switzerland',
				'alpha-2'    => 'CH',
				'alpha-3'    => 'CHE',
				'code'       => '756',
				'iso-3166-2' => 'ISO 3166-2:CH'
			),
			'SY' => array(
				'name'       => 'Syria',
				'alpha-2'    => 'SY',
				'alpha-3'    => 'SYR',
				'code'       => '760',
				'iso-3166-2' => 'ISO 3166-2:SY'
			),
			'TW' => array(
				'name'       => 'Taiwan',
				'alpha-2'    => 'TW',
				'alpha-3'    => 'TWN',
				'code'       => '158',
				'iso-3166-2' => 'ISO 3166-2:TW'
			),
			'TJ' => array(
				'name'       => 'Tajikistan',
				'alpha-2'    => 'TJ',
				'alpha-3'    => 'TJK',
				'code'       => '762',
				'iso-3166-2' => 'ISO 3166-2:TJ'
			),
			'TZ' => array(
				'name'       => 'Tanzania',
				'alpha-2'    => 'TZ',
				'alpha-3'    => 'TZA',
				'code'       => '834',
				'iso-3166-2' => 'ISO 3166-2:TZ'
			),
			'TH' => array(
				'name'       => 'Thailand',
				'alpha-2'    => 'TH',
				'alpha-3'    => 'THA',
				'code'       => '764',
				'iso-3166-2' => 'ISO 3166-2:TH'
			),
			'TL' => array(
				'name'       => 'Timor-Leste',
				'alpha-2'    => 'TL',
				'alpha-3'    => 'TLS',
				'code'       => '626',
				'iso-3166-2' => 'ISO 3166-2:TL'
			),
			'TG' => array(
				'name'       => 'Togo',
				'alpha-2'    => 'TG',
				'alpha-3'    => 'TGO',
				'code'       => '768',
				'iso-3166-2' => 'ISO 3166-2:TG'
			),
			'TK' => array(
				'name'       => 'Tokelau',
				'alpha-2'    => 'TK',
				'alpha-3'    => 'TKL',
				'code'       => '772',
				'iso-3166-2' => 'ISO 3166-2:TK'
			),
			'TO' => array(
				'name'       => 'Tonga',
				'alpha-2'    => 'TO',
				'alpha-3'    => 'TON',
				'code'       => '776',
				'iso-3166-2' => 'ISO 3166-2:TO'
			),
			'TT' => array(
				'name'       => 'Trinidad and Tobago',
				'alpha-2'    => 'TT',
				'alpha-3'    => 'TTO',
				'code'       => '780',
				'iso-3166-2' => 'ISO 3166-2:TT'
			),
			'TN' => array(
				'name'       => 'Tunisia',
				'alpha-2'    => 'TN',
				'alpha-3'    => 'TUN',
				'code'       => '788',
				'iso-3166-2' => 'ISO 3166-2:TN'
			),
			'TR' => array(
				'name'       => 'Turkey',
				'alpha-2'    => 'TR',
				'alpha-3'    => 'TUR',
				'code'       => '792',
				'iso-3166-2' => 'ISO 3166-2:TR'
			),
			'TM' => array(
				'name'       => 'Turkmenistan',
				'alpha-2'    => 'TM',
				'alpha-3'    => 'TKM',
				'code'       => '795',
				'iso-3166-2' => 'ISO 3166-2:TM'
			),
			'TC' => array(
				'name'       => 'Turks and Caicos Islands',
				'alpha-2'    => 'TC',
				'alpha-3'    => 'TCA',
				'code'       => '796',
				'iso-3166-2' => 'ISO 3166-2:TC'
			),
			'TV' => array(
				'name'       => 'Tuvalu',
				'alpha-2'    => 'TV',
				'alpha-3'    => 'TUV',
				'code'       => '798',
				'iso-3166-2' => 'ISO 3166-2:TV'
			),
			'UG' => array(
				'name'       => 'Uganda',
				'alpha-2'    => 'UG',
				'alpha-3'    => 'UGA',
				'code'       => '800',
				'iso-3166-2' => 'ISO 3166-2:UG'
			),
			'UA' => array(
				'name'       => 'Ukraine',
				'alpha-2'    => 'UA',
				'alpha-3'    => 'UKR',
				'code'       => '804',
				'iso-3166-2' => 'ISO 3166-2:UA'
			),
			'AE' => array(
				'name'       => 'United Arab Emirates',
				'alpha-2'    => 'AE',
				'alpha-3'    => 'ARE',
				'code'       => '784',
				'iso-3166-2' => 'ISO 3166-2:AE'
			),
			'GB' => array(
				'name'       => 'United Kingdom',
				'alpha-2'    => 'GB',
				'alpha-3'    => 'GBR',
				'code'       => '826',
				'iso-3166-2' => 'ISO 3166-2:GB'
			),
			'US' => array(
				'name'       => 'United States',
				'alpha-2'    => 'US',
				'alpha-3'    => 'USA',
				'code'       => '840',
				'iso-3166-2' => 'ISO 3166-2:US'
			),
			'UM' => array(
				'name'       => 'United States Minor Outlying Islands',
				'alpha-2'    => 'UM',
				'alpha-3'    => 'UMI',
				'code'       => '581',
				'iso-3166-2' => 'ISO 3166-2:UM'
			),
			'UY' => array(
				'name'       => 'Uruguay',
				'alpha-2'    => 'UY',
				'alpha-3'    => 'URY',
				'code'       => '858',
				'iso-3166-2' => 'ISO 3166-2:UY'
			),
			'UZ' => array(
				'name'       => 'Uzbekistan',
				'alpha-2'    => 'UZ',
				'alpha-3'    => 'UZB',
				'code'       => '860',
				'iso-3166-2' => 'ISO 3166-2:UZ'
			),
			'VU' => array(
				'name'       => 'Vanuatu',
				'alpha-2'    => 'VU',
				'alpha-3'    => 'VUT',
				'code'       => '548',
				'iso-3166-2' => 'ISO 3166-2:VU'
			),
			'VE' => array(
				'name'       => 'Venezuela',
				'alpha-2'    => 'VE',
				'alpha-3'    => 'VEN',
				'code'       => '862',
				'iso-3166-2' => 'ISO 3166-2:VE'
			),
			'VN' => array(
				'name'       => 'Viet Nam',
				'alpha-2'    => 'VN',
				'alpha-3'    => 'VNM',
				'code'       => '704',
				'iso-3166-2' => 'ISO 3166-2:VN'
			),
			'VG' => array(
				'name'       => 'Virgin Islands (British)',
				'alpha-2'    => 'VG',
				'alpha-3'    => 'VGB',
				'code'       => '092',
				'iso-3166-2' => 'ISO 3166-2:VG'
			),
			'VI' => array(
				'name'       => 'Virgin Islands (U.S.)',
				'alpha-2'    => 'VI',
				'alpha-3'    => 'VIR',
				'code'       => '850',
				'iso-3166-2' => 'ISO 3166-2:VI'
			),
			'WF' => array(
				'name'       => 'Wallis and Futuna',
				'alpha-2'    => 'WF',
				'alpha-3'    => 'WLF',
				'code'       => '876',
				'iso-3166-2' => 'ISO 3166-2:WF'
			),
			'EH' => array(
				'name'       => 'Western Sahara',
				'alpha-2'    => 'EH',
				'alpha-3'    => 'ESH',
				'code'       => '732',
				'iso-3166-2' => 'ISO 3166-2:EH'
			),
			'YE' => array(
				'name'       => 'Yemen',
				'alpha-2'    => 'YE',
				'alpha-3'    => 'YEM',
				'code'       => '887',
				'iso-3166-2' => 'ISO 3166-2:YE'
			),
			'ZM' => array(
				'name'       => 'Zambia',
				'alpha-2'    => 'ZM',
				'alpha-3'    => 'ZMB',
				'code'       => '894',
				'iso-3166-2' => 'ISO 3166-2:ZM'
			),
			'ZW' => array(
				'name'       => 'Zimbabwe',
				'alpha-2'    => 'ZW',
				'alpha-3'    => 'ZWE',
				'code'       => '716',
				'iso-3166-2' => 'ISO 3166-2:ZW'
			),
		);

		return $countries;
	}

	public function plugin_action_links( $links, $file ) {
		static $this_plugin;

		if ( ! $this_plugin ) {
			$this_plugin = plugin_basename( 'wp-full-stripe/wp-full-stripe.php' );
		}

		if ( $file == $this_plugin ) {
			$settings_link = '<a href="' . menu_page_url( 'fullstripe-settings', false ) . '">' . esc_html( __( 'Settings', 'fullstripe-settings' ) ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	function fullstripe_payment_form( $atts ) {

		$form = null;

		extract( shortcode_atts( array(
			'form' => 'default',
		), $atts ) );

		//load scripts and styles
		$this->fullstripe_load_css();
		$this->fullstripe_load_js();
		//load form data into scope
		list( $formData, $currencySymbol, $localeState, $localeZip, $creditCardImage ) = $this->load_payment_form_data( $form );

		//get the form style
		$style = 0;
		if ( ! $formData ) {
			$style = - 1;
		} else {
			$style = $formData->formStyle;
		}

		ob_start();
		include $this->get_payment_form_by_style( $style );
		$content = ob_get_clean();

		return apply_filters( 'fullstripe_payment_form_output', $content );
	}

	function fullstripe_load_css() {
		$options = get_option( 'fullstripe_options' );
		if ( $options['includeStyles'] === '1' ) {
			wp_enqueue_style( 'fullstripe-bootstrap-css', plugins_url( '/css/newstyle.css', dirname( __FILE__ ) ), null, MM_WPFS::VERSION );
			wp_enqueue_style( 'fullstripe-bootstrap-form-css', plugins_url( '/css/form-style.css', dirname( __FILE__ ) ), null, MM_WPFS::VERSION );
		}

		do_action( 'fullstripe_load_css_action' );
	}

	function fullstripe_load_js() {
		$options = get_option( 'fullstripe_options' );
		wp_enqueue_script( 'sprintf-js', plugins_url( 'js/sprintf.min.js', dirname( __FILE__ ) ), null, MM_WPFS::VERSION );
		wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v2/', array( 'jquery' ) );
		wp_enqueue_script( 'wp-full-stripe-js', plugins_url( 'js/wp-full-stripe.js', dirname( __FILE__ ) ), array(
			'sprintf-js',
			'stripe-js'
		), MM_WPFS::VERSION );
		wp_localize_script( 'wp-full-stripe-js', 'wpfs_L10n', array(
			'plan_details_with_singular_interval'
			/* translators: 1: currency symbol/code, 2: amount, 3: interval in singular */
			                                           => __( 'Plan is %1$s%2$.2f per %3$s', 'wp-full-stripe' ),
			'plan_details_with_plural_interval'
			/* translators: 1: currency symbol/code, 2: amount, 3: interval count > 1, 4: interval in plural */
			                                           => __( 'Plan is %1$s%2$.2f per %3$d %4$s', 'wp-full-stripe' ),
			'plan_details_with_singular_interval_with_coupon'
			/* translators: 1: currency symbol/count, 2: amount, 3: interval in singular, 4: coupon percentage/amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$s (%4$.2f with coupon)', 'wp-full-stripe' ),
			'plan_details_with_plural_interval_with_coupon'
			/* translators: 1: currency symbol/count, 2: amount, 3: interval count > 1, 4: interval in plural, 5: coupon percentage/amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$d %4$s (%5$.2f with coupon)', 'wp-full-stripe' ),
			'plan_details_with_singular_interval_with_setupfee'
			/* translators: 1: currency symbol/code, 2: amount, 3: interval in singular, 4: setup fee currency symbol/code, 5: setup fee amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$s. SETUP FEE: %4$s%5$.2f', 'wp-full-stripe' ),
			'plan_details_with_plural_interval_with_setupfee'
			/* translators: 1: currency symbol/code, 2: amount, 3: interval count > 1, 4: interval in plural, 5: setup fee currency symbol/code, 6: setup fee amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$d %4$s. SETUP FEE: %5$s%6$.2f', 'wp-full-stripe' ),
			'plan_details_with_singular_interval_with_coupon_with_setupfee'
			/* translators: 1: currency symbol/count, 2: amount, 3: interval in singular, 4: coupon percentage/amount, 5: setup fee currency symbol/code, 6: setup fee amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$s (%4$.2f with coupon). SETUP FEE: %5$s%6$.2f', 'wp-full-stripe' ),
			'plan_details_with_plural_interval_with_coupon_with_setupfee'
			/* translators: 1: currency symbol/count, 2: amount, 3: interval count > 1, 4: interval in plural, 5: coupon percentage/amount, 6: setup fee currency symbol/code, 7: setup fee amount */
			                                           => __( 'Plan is %1$s%2$.2f per %3$d %4$s (%5$.2f with coupon). SETUP FEE: %6$s%7$.2f', 'wp-full-stripe' ),
			'internal_error'
			                                           => __( 'An internal error occured.', 'wp-full-stripe' ),
			MM_WPFS_Stripe::INVALID_NUMBER_ERROR       => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_NUMBER_ERROR ),
			MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR ),
			MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR  => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR ),
			MM_WPFS_Stripe::INVALID_CVC_ERROR          => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_CVC_ERROR ),
			MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR     => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR ),
			MM_WPFS_Stripe::EXPIRED_CARD_ERROR         => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::EXPIRED_CARD_ERROR ),
			MM_WPFS_Stripe::INCORRECT_CVC_ERROR        => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_CVC_ERROR ),
			MM_WPFS_Stripe::INCORRECT_ZIP_ERROR        => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_ZIP_ERROR ),
			MM_WPFS_Stripe::CARD_DECLINED_ERROR        => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::CARD_DECLINED_ERROR ),
			MM_WPFS_Stripe::MISSING_ERROR              => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::MISSING_ERROR ),
			MM_WPFS_Stripe::PROCESSING_ERROR           => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::PROCESSING_ERROR )

		) );
		if ( $options['apiMode'] === 'test' ) {
			wp_localize_script( 'wp-full-stripe-js', 'stripekey', $options['publishKey_test'] );
		} else {
			wp_localize_script( 'wp-full-stripe-js', 'stripekey', $options['publishKey_live'] );
		}

		wp_localize_script( 'wp-full-stripe-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

		do_action( 'fullstripe_load_js_action' );
	}

	function load_payment_form_data( $form ) {
		list ( $currencySymbol, $localeState, $localeZip, $creditCardImage ) = $this->get_locale_strings();

		$formData = array(
			$this->database->get_payment_form_by_name( $form ),
			$currencySymbol,
			$localeState,
			$localeZip,
			$creditCardImage
		);

		return $formData;
	}

	function get_locale_strings() {
		$options         = get_option( 'fullstripe_options' );
		$currencySymbol  = strtoupper( $options['currency'] );
		$localeState     = 'State';
		$localeZip       = 'Zip';
		$creditCardImage = 'creditcards.png';

		if ( $options['currency'] === 'usd' ) {
			$currencySymbol  = '$';
			$localeState     = 'State';
			$localeZip       = 'Zip';
			$creditCardImage = 'creditcards-us.png';
		} elseif ( $options['currency'] === 'eur' ) {
			$currencySymbol  = '€';
			$localeState     = 'Region';
			$localeZip       = 'Zip / Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'jpy' ) {
			$currencySymbol  = '¥';
			$localeState     = 'Prefecture';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'gbp' ) {
			$currencySymbol  = '£';
			$localeState     = 'County';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'aud' ) {
			$currencySymbol  = '$';
			$localeState     = 'State';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'chf' ) {
			$currencySymbol  = 'Fr';
			$localeState     = 'Canton';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'cad' ) {
			$currencySymbol  = '$';
			$localeState     = 'Province';
			$localeZip       = 'Postal Code';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'mxn' ) {
			$currencySymbol  = '$';
			$localeState     = 'Region';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'sek' ) {
			$currencySymbol  = 'kr';
			$localeState     = 'County';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'nok' ) {
			$currencySymbol  = 'kr';
			$localeState     = 'County';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		} elseif ( $options['currency'] === 'dkk' ) {
			$currencySymbol  = 'kr';
			$localeState     = 'Region';
			$localeZip       = 'Postcode';
			$creditCardImage = 'creditcards.png';
		}

		return array(
			$currencySymbol,
			$localeState,
			$localeZip,
			$creditCardImage
		);
	}

	function get_payment_form_by_style( $styleID ) {
		switch ( $styleID ) {
			case - 1:
				return WP_FULL_STRIPE_DIR . '/pages/forms/invalid_shortcode.php';

			case 0:
				return WP_FULL_STRIPE_DIR . '/pages/fullstripe_payment_form.php';

			case 1:
				return WP_FULL_STRIPE_DIR . '/pages/forms/payment_form_compact.php';

			default:
				return WP_FULL_STRIPE_DIR . '/pages/fullstripe_payment_form.php';
		}
	}

	function fullstripe_subscription_form( $atts ) {
		$form = null;

		extract( shortcode_atts( array(
			'form' => 'default',
		), $atts ) );

		$this->fullstripe_load_css();
		$this->fullstripe_load_js();

		//load form data into scope
		list( $formData, $currencySymbol, $localeState, $localeZip, $creditCardImage ) = $this->load_subscription_form_data( $form );
		//get the form style & plans
		$style = 0;
		$plans = array();
		if ( ! $formData ) {
			$style = - 1;
		} else {
			$style    = $formData->formStyle;
			$allPlans = $this->get_plans();
			if ( count( $allPlans['data'] ) === 0 ) {
				$style = - 2;
			} else {
				$planIDs = json_decode( $formData->plans );
				foreach ( $allPlans['data'] as $plan ) {
					$i = array_search( $plan->id, $planIDs );
					if ( $i !== false ) {
						$plans[ $i ] = $plan;
					}
				}
				ksort( $plans );
			}
		}

		ob_start();
		include $this->get_subscription_form_by_style( $style );
		$content = ob_get_clean();

		return apply_filters( 'fullstripe_subscription_form_output', $content );
	}

	function load_subscription_form_data( $form ) {
		list ( $currencySymbol, $localeState, $localeZip, $creditCardImage ) = $this->get_locale_strings();

		$subscription_form = $this->database->get_subscription_form_by_name( $form );
		$formData          = array(
			$subscription_form,
			$currencySymbol,
			$localeState,
			$localeZip,
			$creditCardImage
		);

		return $formData;
	}

	public function get_plans() {
		return $this->stripe != null ? apply_filters( 'fullstripe_subscription_plans_filter', $this->stripe->get_plans() ) : array();
	}

	function get_subscription_form_by_style( $styleID ) {
		switch ( $styleID ) {
			case - 2:
				return WP_FULL_STRIPE_DIR . '/pages/forms/invalid_plans.php';

			case - 1:
				return WP_FULL_STRIPE_DIR . '/pages/forms/invalid_shortcode.php';

			case 0:
				return WP_FULL_STRIPE_DIR . '/pages/fullstripe_subscription_form.php';

			default:
				return WP_FULL_STRIPE_DIR . '/pages/fullstripe_subscription_form.php';
		}
	}

	function fullstripe_checkout_form( $atts ) {

		$form = null;

		extract( shortcode_atts( array(
			'form' => 'default',
		), $atts ) );

		$this->fullstripe_load_css();
		$this->fullstripe_load_checkout_js();

		$options  = get_option( 'fullstripe_options' );
		$formData = $this->database->get_checkout_form_by_name( $form );

		if ( ! $formData ) {
			ob_start();
			include WP_FULL_STRIPE_DIR . '/pages/forms/invalid_shortcode.php';
			$content = ob_get_clean();
		} else {
			//load form specific options
			$formData['currency'] = $options['currency'];

			ob_start();
			include WP_FULL_STRIPE_DIR . '/pages/fullstripe_checkout_form.php';
			$content = ob_get_clean();
		}

		return apply_filters( 'fullstripe_checkout_form_output', $content );
	}

	function fullstripe_load_checkout_js() {
		$options = get_option( 'fullstripe_options' );
		wp_enqueue_script( 'checkout-js', 'https://checkout.stripe.com/checkout.js', array( 'jquery' ) );
		wp_enqueue_script( 'stripe-checkout-js', plugins_url( 'js/wp-full-stripe-checkout.js', dirname( __FILE__ ) ), array( 'checkout-js' ), MM_WPFS::VERSION );
		wp_localize_script( 'wp-full-stripe-js', 'wpfs_L10n', array(
			'internal_error'
			=> __( 'An internal error.', 'wp-full-stripe' )
		) );
		if ( $options['apiMode'] === 'test' ) {
			wp_localize_script( 'stripe-checkout-js', 'stripekey', $options['publishKey_test'] );
		} else {
			wp_localize_script( 'stripe-checkout-js', 'stripekey', $options['publishKey_live'] );
		}

		wp_localize_script( 'stripe-checkout-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

		do_action( 'fullstripe_load_checkout_js_action' );
	}

	function fullstripe_wp_head() {
		//output the custom css
		$options = get_option( 'fullstripe_options' );
		echo '<style type="text/css" media="screen">' . $options['form_css'] . '</style>';
	}

	public function get_recipients() {
		return $this->stripe != null ? apply_filters( 'fullstripe_transfer_receipients_filter', $this->stripe->get_recipients() ) : array();
	}

	public function get_subscription( $customerID, $subscriptionID ) {
		return $this->stripe != null ? apply_filters( 'fullstripe_customer_subscription_filter', $this->stripe->retrieve_subscription( $customerID, $subscriptionID ) ) : array();
	}

	/**
	 * @return array
	 */
	private function create_default_email_receipts() {
		$emailReceipts                         = array();
		$paymentMade                           = new stdClass();
		$subscriptionStarted                   = new stdClass();
		$subscriptionFinished                  = new stdClass();
		$paymentMade->subject                  = 'Payment Receipt';
		$paymentMade->html                     = "<html><body><p>Hi,</p><p>Here's your receipt for your payment of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";
		$subscriptionStarted->subject          = 'Subscription Receipt';
		$subscriptionStarted->html             = "<html><body><p>Hi,</p><p>Here's your receipt for your subscription of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";
		$subscriptionFinished->subject         = 'Subscription Ended';
		$subscriptionFinished->html            = '<html><body><p>Hi,</p><p>Your subscription has ended.</p><p>Thanks</p><br/>%NAME%</body></html>';
		$emailReceipts['paymentMade']          = $paymentMade;
		$emailReceipts['subscriptionStarted']  = $subscriptionStarted;
		$emailReceipts['subscriptionFinished'] = $subscriptionFinished;

		return $emailReceipts;
	}

	/**
	 * Generates a unique random token for authenticating webhook callbacks.
	 *
	 * @return string
	 */
	private function create_webhook_token() {
		$site_url           = get_site_url();
		$session_token      = wp_get_session_token();
		$generated_password = wp_generate_password( 6, false );

		return wp_hash( $site_url . '|' . $session_token . '|' . $generated_password );
	}

	private function activate_license() {
		$options = get_option( 'fullstripe_options' );
		$license = $options['edd_license_key'];

		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( WPFS_EDD_SL_ITEM_NAME ),
			'url'        => home_url()
		);

		$response = wp_remote_post( WPFS_EDD_SL_STORE_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		$options['edd_license_status'] = $license_data->license;
		update_option( 'fullstripe_options', $options );

		return true;
	}

	/**
	 * @return MM_WPFS_Admin_Menu
	 */
	public function getAdminMenu() {
		return $this->admin_menu;
	}

	/**
	 * Create a list of email receipt template objects to render on the Settings page.
	 */
	public function get_email_receipt_templates() {

		$email_receipt_templates = array();

		$payment_made                   = new stdClass();
		$payment_made->id               = 'paymentMade';
		$payment_made->caption          = __( 'Payment receipt', 'wp-full-stripe' );
		$subscription_started           = new stdClass();
		$subscription_started->id       = 'subscriptionStarted';
		$subscription_started->caption  = __( 'Subscription receipt', 'wp-full-stripe' );
		$subscription_finished          = new stdClass();
		$subscription_finished->id      = 'subscriptionFinished';
		$subscription_finished->caption = __( 'Subscription ended', 'wp-full-stripe' );

		array_push( $email_receipt_templates, $payment_made );
		array_push( $email_receipt_templates, $subscription_started );
		array_push( $email_receipt_templates, $subscription_finished );

		return apply_filters( 'fullstripe_settings_email_receipt_templates', $email_receipt_templates );
	}
	
	public function get_form_validation_data() {
		return new Form();
	}

}

class Form {
	
	const NAME_LENGTH = 100;
	const FORM_TITLE_LENGTH = 100;
	const BUTTON_TITLE_LENGTH = 100;
	const REDIRECT_URL_LENGTH = 1024;
	const COMPANY_NAME_LENGTH = 100;
	const PRODUCT_DESCRIPTION_LENGTH = 100;
	const OPEN_BUTTON_TITLE_LENGTH = 100;
	const PAYMENT_AMOUNT_LENGTH = 6;
	const PAYMENT_AMOUNT_DESCRIPTION_LENGTH = 128;
	const IMAGE_LENGTH = 500;
}

MM_WPFS::getInstance();

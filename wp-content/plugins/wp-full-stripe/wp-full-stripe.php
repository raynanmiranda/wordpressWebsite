<?php
/*
Plugin Name: WP Full Stripe - Null24.Net
Plugin URI: https://paymentsplugin.com
Description: Complete Stripe payments integration for Wordpress
Author: Mammothology
Version: 3.7.0
Author URI: https://paymentsplugin.com
Text Domain: wp-full-stripe
Domain Path: /languages
*/

//defines

define( 'STRIPE_API_VERSION', '3.21.0' );

if ( ! defined( 'WP_FULL_STRIPE_NAME' ) ) {
	define( 'WP_FULL_STRIPE_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'WP_FULL_STRIPE_BASENAME' ) ) {
	define( 'WP_FULL_STRIPE_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WP_FULL_STRIPE_DIR' ) ) {
	define( 'WP_FULL_STRIPE_DIR', plugin_dir_path( __FILE__ ) );
}

//Stripe PHP library
if ( ! class_exists( '\Stripe\Stripe' ) ) {
	require_once( dirname( __FILE__ ) . '/vendor/stripe/stripe-php/init.php' );
} else {
	if ( substr( \Stripe\Stripe::VERSION, 0, strpos( \Stripe\Stripe::VERSION, '.' ) ) != substr( STRIPE_API_VERSION, 0, strpos( STRIPE_API_VERSION, '.' ) ) ) {
		wp_die( plugin_basename( __FILE__ ) . ': ' . __( 'Incompatible Stripe API client loaded. Plugin is unserviceable.' ) );
	}
}

if ( ! class_exists( 'MM_WPFS_License' ) ) {
	// load our custom updater if it doesn't already exist
	include( dirname( __FILE__ ) . '/include/wp-full-stripe-edd-license.php' );
}

if ( ! class_exists( 'WPFS_EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist
	include( dirname( __FILE__ ) . '/include/edd/WPFS_EDD_SL_Plugin_Updater.php' );
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'wp-full-stripe-main.php';
register_activation_hook( __FILE__, array( 'MM_WPFS', 'setup_db' ) );

$options     = get_option( 'fullstripe_options' );
$license_key = trim( $options['edd_license_key'] );
$edd_updater = new WPFS_EDD_SL_Plugin_Updater( WPFS_EDD_SL_STORE_URL, __FILE__, array(
	'version'   => MM_WPFS::VERSION,
	'license'   => $license_key,
	'item_name' => WPFS_EDD_SL_ITEM_NAME,
	'author'    => 'Mammothology',
	'url'       => home_url()
) );

function wp_full_stripe_load_plugin_textdomain() {
	load_plugin_textdomain( 'wp-full-stripe', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'wp_full_stripe_load_plugin_textdomain' );
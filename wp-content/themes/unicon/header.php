<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Unicon
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="site">

	<?php
		/**
		 * @see  unicon_skip_links() - 5
		*/			
		do_action( 'unicon_header_before' ); 

		/**
			** @see filter unicon_quick_contact_info_top_header() - 5 
			** @see filter unicon_social_icon_top_header() - 10
		 * @see  unicon_top_header() - 15		  
		 * @see  unicon_main_header() - 20
		 * @see unicon_main_header_content() - 25
		**/			
		do_action( 'unicon_main_header' );


		do_action( 'unicon_header_after' );  
	?>

<div id="content" class="site-content">
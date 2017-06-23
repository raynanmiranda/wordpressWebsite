<?php
/**
 * ParallaxSome Theme Customizer for General Settings Panel.
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

if( ! function_exists( 'parallaxsome_general_panel_register' ) ):
	function parallaxsome_general_panel_register( $wp_customize ) {

		$wp_customize->get_section( 'title_tagline' )->panel = 'parallaxsome_general_settings_panel';
    	$wp_customize->get_section( 'title_tagline' )->priority = '5';
    	$wp_customize->get_section( 'background_image' )->panel = 'parallaxsome_general_settings_panel';
    	$wp_customize->get_section( 'background_image' )->priority = '10';
    	$wp_customize->get_section( 'colors' )->panel = 'parallaxsome_general_settings_panel';
        $wp_customize->get_section( 'colors' )->priority = '15';
        $wp_customize->get_section( 'static_front_page' )->panel = 'parallaxsome_general_settings_panel';
    	$wp_customize->get_section( 'static_front_page' )->priority = '20';        

		/**
		 * General Settings Panel on customizer
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_panel(
	        'parallaxsome_general_settings_panel', 
	        	array(
	        		'priority'       => 5,
	            	'capability'     => 'edit_theme_options',
	            	'theme_supports' => '',
	            	'title'          => esc_html__( 'General Settings', 'parallaxsome' ),
	            ) 
	    );

	} //close fucntion
endif;

add_action( 'customize_register', 'parallaxsome_general_panel_register' );
<?php
/**
 * Revolve Theme Customizer.
 *
 * @package Revolve
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function revolve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'revolve_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function revolve_customize_preview_js() {
	wp_enqueue_script( 'revolve-customize', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'revolve_customize_preview_js' );

/**
 * Enqueue script for custom customize control.
 */
function revolve_custom_customize_enqueue() {
    wp_enqueue_style('revolve-customizer-custom-css', get_template_directory_uri() . '/inc/admin/css/custom-customizer.css');
    wp_enqueue_script( 'revolve-custom-customize', get_template_directory_uri() . '/inc/admin/js/custom-customizer-js.js', array( 'jquery', 'customize-controls' ), false );
}
add_action( 'customize_controls_enqueue_scripts', 'revolve_custom_customize_enqueue' );

/**
 * Add Miscellaneous Customizer Options.
 */
require get_template_directory() . '/inc/cmizer/misc-options.php';

/**
 * Add Home Slider Customizer Options.
 */
require get_template_directory() . '/inc/cmizer/home-slider-options.php';

/**
 * Add Blog Page Customizer Options.
 */
require get_template_directory() . '/inc/cmizer/blog-page-options.php';

/**
 * Add Team Page Customizer Options.
 */
require get_template_directory() . '/inc/cmizer/team-page-options.php';

/**
 * Add Portfolio Page Customizer Options.
 */
require get_template_directory() . '/inc/cmizer/portfolio-page-options.php';
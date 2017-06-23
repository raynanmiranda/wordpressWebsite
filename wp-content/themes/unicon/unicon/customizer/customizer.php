<?php
/**
 * Unicon Theme Customizer.
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 */

/**
 * Load file for customizer sanitization functions
*/
require $unicon_sanitize_functions_file_path = unicon_file_directory('unicon/customizer/unicon-sanitize.php');


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function unicon_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

/**
 * General Settings Panel
*/
$wp_customize->add_panel( 'unicon_general_settings_panel', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'General Settings', 'unicon' ),
) );
	
	$wp_customize->get_section('title_tagline')->panel = 'unicon_general_settings_panel';
	
	$wp_customize->get_section('colors')->panel = 'unicon_general_settings_panel';
	
	$wp_customize->get_section('background_image')->panel = 'unicon_general_settings_panel';
	
	$wp_customize->get_section('static_front_page')->panel = 'unicon_general_settings_panel';
	
	$wp_customize->get_section('colors')->title = __( 'Themes Colors', 'unicon' );
		/*$wp_customize->add_setting('unicon_primary_color', array(
		    'default' => '#0091d5',
		    'capability' => 'edit_theme_options',
		    'sanitize_callback' => 'sanitize_hex_color',        
		));

		$wp_customize->add_control('unicon_primary_color', array(
		    'type'     => 'color',
		    'label'    => __('Primary Colors', 'unicon'),
		    'section'  => 'colors',
		    'setting'  => 'unicon_primary_color',
		));*/
		
	/**
	 * Load Customizer Custom Control File
	*/
	require $unicon_customizer_file_path = unicon_file_directory('unicon/customizer/unicon-custom-controls.php');

	/**
	 * Load header panel file
	*/
	require $unicon_customizer_header_settings_file_path = unicon_file_directory('unicon/customizer/header-section/header-settings.php');

	/**
	 * Load homepage panel file
	*/
	require $unicon_customizer_homepage_settings_file_path = unicon_file_directory('unicon/customizer/homepage-section/homepage-settings.php');

	/**
	 * Load footer panel file
	*/
	require $unicon_customizer_footer_settings_file_path = unicon_file_directory('unicon/customizer/footer-section/footer-settings.php');

	/**
	 * Load footer panel file
	*/
	require $unicon_customizer_blog_settings_file_path = unicon_file_directory('unicon/customizer/blog-section/blog-settings.php');


	/**
	 * Breadcrumb Settings Area
	*/
    $wp_customize->add_section('unicon_breadcrumb_setting', array(
        'title'   => __('Breadcrumb Settings', 'unicon'),
        'priority'=> 36,
        'panel' => 'unicon_general_settings_panel'
    )); 

	    $wp_customize->add_setting( 'unicon_breadcrumb_section', array(
	        'default' => 'show',
	        'sanitize_callback' => 'unicon_sanitize_switch_option',
	    ) );

	    $wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_breadcrumb_section',  array(
	        'type'      => 'switch',                    
	        'label'     => __( 'Enable/Disable Breadcrumb Section', 'unicon' ),
	        'section'   => 'unicon_breadcrumb_setting',
	        'choices'   => array(
	    	        'show'  => __( 'Enable', 'unicon' ),
	    	        'hide'  => __( 'Disable', 'unicon' )
	            )
	    ) ) );

	    $wp_customize->add_setting('unicon_breadcrumb_bg_image', array(
	        'default' =>      '',
	        'sanitize_callback' => 'esc_url_raw',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,'unicon_breadcrumb_bg_image', array(
	        'section'  => 'unicon_breadcrumb_setting',
	        'label'    => __('Upload Breadcrumb Background Image', 'unicon'),
	        'type'     => 'image',
	    ) ) );


	    $wp_customize->add_setting( 'unicon_breadcrumb_menu', array(
	        'default' => 'show',
	        'sanitize_callback' => 'unicon_sanitize_switch_option',
	    ) );

	    $wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_breadcrumb_menu',  array(
	        'type'      => 'switch',                    
	        'label'     => __( 'Enable/Disable Breadcrumb Menu', 'unicon' ),
	        'section'   => 'unicon_breadcrumb_setting',
	        'choices'   => array(
	    	        'show'  => __( 'Enable', 'unicon' ),
	    	        'hide'  => __( 'Disable', 'unicon' )
	            )
	    ) ) );

	/**
	 * Custom CSS Area
	*/
	$wp_customize->add_section('unicon_css_section', array(
	    'priority' => 37,
	    'title' => __('Custom CSS Section', 'unicon'),
	));

		$wp_customize->add_setting( 'unicon_css_section', array(
		    'default' => '',
		    'capability' => 'edit_theme_options',
		    'sanitize_callback' => 'wp_filter_nohtml_kses',
		));

		$wp_customize->add_control('unicon_css_section', array(
		    'type' => 'textarea',
		    'label' => __('Custom CSS', 'unicon'),
		    'section' => 'unicon_css_section',
		    'setting' => 'unicon_css_section',
		));

}
add_action( 'customize_register', 'unicon_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function unicon_customize_preview_js() {
	wp_enqueue_script( 'unicon-customizer', get_template_directory_uri() . '/unicon/customizer/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'unicon_customize_preview_js' );


/**
 * Enqueue scripts and style for customizer
*/
function unicon_customize_backend_scripts() {
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/library/fontawesome/css/font-awesome.min.css');
	wp_enqueue_style( 'unicon-customizer-style', get_template_directory_uri() . '/unicon/customizer/assets/css/customizer-style.css' );
	wp_enqueue_script( 'unicon-customizer-script', get_template_directory_uri() . '/unicon/customizer/assets/js/customizer-scripts.js', array( 'jquery', 'customize-controls' ), '20160714', true );
}
add_action( 'customize_controls_enqueue_scripts', 'unicon_customize_backend_scripts', 10 );

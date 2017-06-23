<?php
/**
 * ParallaxSome custom functions and definitions for widgets
 *
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 */

function parallaxsome_widgets_init() {
	
	/**
	 * Register Right Sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'parallaxsome' ),
		'id'            => 'parallaxsome_right_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Right Sidebar section in every posts/pages/archive.', 'parallaxsome' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/**
	 * Register Left Sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'parallaxsome' ),
		'id'            => 'parallaxsome_left_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Left Sidebar section in every posts/pages/archive.', 'parallaxsome' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/**
	 * Register Home Contact Map widget area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Contact Map', 'parallaxsome' ),
		'id'            => 'parallaxsome_map_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Contact Us section at homepage.', 'parallaxsome' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/**
	 * Register WooCommerce Sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'WooCommerce Sidebar', 'parallaxsome' ),
		'id'            => 'parallaxsome_woo_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Sidebar section only in woocommerce posts/pages/archives.', 'parallaxsome' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/**
	 * Register 4 different Footer Area 
	 *
	 * @since 1.0.0
	 */
	register_sidebars( 4 , array(
		'name'          => esc_html__( 'Footer Area %d', 'parallaxsome' ),
		'id'            => 'parallaxsome_footer_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Footer Widget Area.', 'parallaxsome' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'parallaxsome_widgets_init' );
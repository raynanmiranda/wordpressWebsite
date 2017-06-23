<?php
/**
 * Revolve functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Revolve
 */

if ( ! function_exists( 'revolve_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function revolve_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Revolve, use a find and replace
	 * to change 'revolve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'revolve', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/** Add Woocommerce Support **/
	add_theme_support( 'woocommerce' );

	/** Add Custom Logo **/
	add_theme_support( 'custom-logo', array(
		'height'      => 130,
		'width'       => 220,
	));

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'revolve' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'revolve_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // revolve_setup
add_action( 'after_setup_theme', 'revolve_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function revolve_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'revolve_content_width', 640 );
}
add_action( 'after_setup_theme', 'revolve_content_width', 0 );

/** Adding Image Sizes **/
add_image_size( 'revolve-port-grid-thumb', 480, 480, true );
add_image_size( 'revolve-blog-grid-thumb', 500, 500, true );
add_image_size( 'revolve-team-grid-thumb', 800, 800, true );

/**
 * Enqueue scripts and styles.
 */
function revolve_scripts() {
	$revolve_font_args = array(
        'family' => 'Open+Sans:400,300,400italic,600,700|Open+Sans+Condensed:300,700|Pontano+Sans|Abel|Nova+Square',
    );

    wp_enqueue_style('revolve-google-fonts', add_query_arg($revolve_font_args, "//fonts.googleapis.com/css"));

	wp_enqueue_style( 'revolve-style', get_stylesheet_uri() );
	wp_enqueue_style( 'revolve-responsive-css', get_template_directory_uri().'/css/responsive.css');
    wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/faw/css/font-awesome.css');
    wp_enqueue_style( 'viewbox', get_template_directory_uri().'/assets/tiny-lightbox/viewbox.css');
    wp_enqueue_style( 'jquery-mCustomScrollbar', get_template_directory_uri().'/assets/scbar/jquery.mCustomScrollbar.css');
    
    if(is_page_template('tpl-home.php')) {
        wp_enqueue_style( 'onepage-scroll', get_template_directory_uri() . '/assets/onepage-scroll/onepage-scroll.css' );
        wp_enqueue_script( 'jquery-onepage-scroll', get_template_directory_uri() . '/assets/onepage-scroll/jquery.onepage-scroll.js', array('jquery') );
    }

    wp_enqueue_script( 'jquery-smooth-scroll', get_template_directory_uri() . '/js/jquery.smooth-scroll.js', array('jquery') );
    
    wp_enqueue_script( 'jquery-scrollTo', get_template_directory_uri() . '/js/jquery.scrollTo.js', array('jquery', 'jquery-smooth-scroll') );
    
    wp_enqueue_script( 'jquery-nav', get_template_directory_uri() . '/js/jquery.nav.js', array('jquery', 'jquery-scrollTo') );
    
    wp_enqueue_script( 'jquery-viewbox', get_template_directory_uri() . '/assets/tiny-lightbox/jquery.viewbox.js', array('jquery'));

    wp_enqueue_script( 'jquery-mCustomScrollbar', get_template_directory_uri() . '/assets/scbar/jquery.mCustomScrollbar.js', array('jquery'));
    
    wp_enqueue_script( 'revolve-custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery', 'jquery-nav', 'jquery-viewbox', 'jquery-mCustomScrollbar') );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'revolve_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load extra Customizer Controls.
 */
require get_template_directory() . '/inc/cmizer/revolve-extra-controls.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Extra Customizer Controls
 */

require get_template_directory() . '/inc/revolve-extra-controls.php';

/**
 * Load Nav Menu Custom Added Options
 */
require get_template_directory() . '/inc/revolve-custom-menu.php';

/**
 * Load Revolve Custom Function Page
 */
require get_template_directory() . '/inc/revolve-functions.php';

/**
 * Load Revolve Additional Widgets
 */
require get_template_directory() . '/inc/revolve-widgets.php';

/**
 * Load Revolve TGM Activation Plugin
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/revolve-tgm.php';

/**
 * Load Revolve Woocommerce
 */
require get_template_directory() . '/inc/revolve-woo.php';

function revolve_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'revolve_add_editor_styles' );

/** Add Portfolio Class to Portfoliio Single Page **/
function revolve_portfolio_class( $classes ) {
	global $post;
	$revolve_post_cats = array();
	$revolve_port_class = '';
	if(is_single()) :
		$revolve_categories = get_the_category($post->ID);
		if(!empty($revolve_categories)){
			foreach($revolve_categories as $revolve_cat) {
				$revolve_post_cats[] = $revolve_cat->term_id;
			}
		}

		$revolve_port_category = absint(get_theme_mod('revolve_port_category'));
		if(!empty($revolve_post_cats)) {
			if(in_array($revolve_port_category, $revolve_post_cats)) {
				$revolve_port_class = 'single-portfolio-post';
			}
		}

		$classes[] = $revolve_port_class;
	endif;
	return $classes;
}
add_filter( 'body_class', 'revolve_portfolio_class' );
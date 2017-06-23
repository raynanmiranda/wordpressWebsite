<?php
/**
 * ParallaxSome functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

if ( ! function_exists( 'parallaxsome_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function parallaxsome_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ParallaxSome, use a find and replace
	 * to change 'parallaxsome' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'parallaxsome', trailingslashit( get_template_directory() ) . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for custom logo.
	 *
	 * @since 1.0.0
	 */
	add_image_size( 'parallaxsome-site-logo', 268, 90 );
	add_theme_support( 'custom-logo', array( 'size' => 'parallaxsome-site-logo' ) );

	// Woocommerce Compatibility
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/**
	 * Define custom thumbnail size
	 *
	 * @since 1.0.0
	 */
	add_image_size( 'parallaxsome_project_thumb', 450, 422, true );
	add_image_size( 'parallaxsome_services_thumb', 393, 384, true );	
	add_image_size( 'parallaxsome_team_thumb', 230, 316, true );
	add_image_size( 'parallaxsome_single_thumb', 820, 421, true );


	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'parallaxsome_primary_menu' => esc_html__( 'Primary Menu', 'parallaxsome' ),
		'parallaxsome_top_menu' => esc_html__( 'Top Header Menu', 'parallaxsome' ),
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
	add_theme_support( 'custom-background', apply_filters( 'parallaxsome_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/**
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( 'assets/css/editor-style.css' );
}
endif;
add_action( 'after_setup_theme', 'parallaxsome_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function parallaxsome_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'parallaxsome_content_width', 640 );
}
add_action( 'after_setup_theme', 'parallaxsome_content_width', 0 );

function parallaxsome_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url', 'display' ) );
	}
}
add_action( 'wp_head', 'parallaxsome_pingback_header' );

/**
 * Load ParallaxSome Custom Functions file
 */
require trailingslashit( get_template_directory() ) . '/inc/parallaxsome-functions.php';

/**
 * Load ParallaxSome breadcrumbs Functions file
 */
require trailingslashit( get_template_directory() ) . '/inc/parallaxsome-breadcrumbs.php';

/**
 * Load ParallaxSome Custom Widget Functions file
 */
require trailingslashit( get_template_directory() ) . '/inc/widgets/parallaxsome-widget-functions.php';

/**
 * Implement the Custom Header feature.
 */
require trailingslashit( get_template_directory() ) . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require trailingslashit( get_template_directory() ) . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require trailingslashit( get_template_directory() ) . '/inc/extras.php';

/**
 * Customizer additions.
 */
require trailingslashit( get_template_directory() ) . '/inc/customizer/customizer.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/general-panel.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/header-panel.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/homepage-panel.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/design-panel.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/additional-panel.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/footer-panel.php';

/**
 * Required files for customizer
 */
require trailingslashit( get_template_directory() ) . '/inc/customizer/parallaxsome-customizer-classes.php';
require trailingslashit( get_template_directory() ) . '/inc/customizer/parallaxsome-sanitize.php';

/**
 * Load Jetpack compatibility file.
 */
require trailingslashit( get_template_directory() ) . '/inc/jetpack.php';

/**
 * Load metaboxes file
 */
require trailingslashit( get_template_directory() ) . '/inc/metaboxes/metabox.php';

/**
 * Load Welcome Page
 */
require trailingslashit( get_template_directory() ) . '/welcome/welcome.php';
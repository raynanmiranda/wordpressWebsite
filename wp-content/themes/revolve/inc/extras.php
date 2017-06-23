<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Revolve
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function revolve_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'revolve_body_classes' );

function revolve_dynamic_styles() {
	$header_textcolor = get_theme_mod('header_textcolor');
	?>
		<style type="text/css">
			.revolve-site-tagline a	{
				color: #<?php echo revolve_sanitize_hex_color($header_textcolor); ?> !important;
			}
		</style>
	<?php
}

add_action( 'wp_head', 'revolve_dynamic_styles' );

if(!function_exists('revolve_sanitize_hex_color')) {
	function revolve_sanitize_hex_color( $color ) {
	    if ( '' === $color )
	        return '';
	 
	    // 3 or 6 hex digits, or the empty string.
	    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
	        return $color;
	}
}
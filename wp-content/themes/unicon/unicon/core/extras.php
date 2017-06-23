<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Unicon
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function unicon_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if(is_singular(array( 'post','page' ))){
        global $post;
        $post_sidebar = get_post_meta($post->ID, 'unicon_page_layouts', true);
        if(!$post_sidebar){
            $post_sidebar = 'rightsidebar';
        }
        $classes[] = $post_sidebar;
    }

    if(is_home() || is_archive()){
    	$classes[] = 'rightsidebar';
    }

    if( !(is_home() || is_front_page() ) ){
        $breadcrumb_options = esc_attr( get_theme_mod('unicon_breadcrumb_section', 'show') );
        if( $breadcrumb_options == 'hide' ){
        	$classes[] = 'no-breadcrumbs';
        }
    }

	return $classes;
}
add_filter( 'body_class', 'unicon_body_classes' );

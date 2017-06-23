<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 * @since 1.0.0
 */
function parallaxsome_body_classes( $classes ) {
	
    global $post;
    
    // Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    //Adds extra class while slider is not active
    $ps_slider_option = get_theme_mod( 'homepage_slider_option', 'hide' );
    $ps_slider_cat_id = get_theme_mod( 'slider_cat_id', '0' );
    if( $ps_slider_option != 'hide' || !empty( $ps_slider_cat_id ) ) {
        $classes[] = 'no-front-slider';
    }
    if( is_home() || is_front_page() ){
    if( $ps_slider_option == 'hide' ) {
        $classes[] = 'without-slider';
    }}

    // adds a class of header-sticky for parallax menu
    $ps_menu_type = get_theme_mod( 'primary_menu_type', 'parallax' );
    $ps_header_sticky_opiton = get_theme_mod( 'sticky_header_option', 'enable' );
    if( $ps_menu_type == 'parallax' && $ps_header_sticky_opiton == 'enable' ) {
        $classes[] = 'header-sticky';
    }

	/**
     * Sidebar option for post/page/archive
     */
    if( 'post' === get_post_type() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'ps_post_sidebar_layout', true );
    }

    if( 'page' === get_post_type() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'ps_post_sidebar_layout', true );
    }
     
    if( is_home() ) {
        $set_id = get_option( 'page_for_posts' );
		$sidebar_meta_option = get_post_meta( $set_id, 'ps_post_sidebar_layout', true );
    }
    
    if( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
        $sidebar_meta_option = 'default_sidebar_layout';
    }
    $archive_sidebar = get_theme_mod( 'ps_archive_sidebar_layout', 'right_sidebar' );
    $post_default_sidebar = get_theme_mod( 'ps_default_post_sidebar', 'right_sidebar' );        
    $page_default_sidebar = get_theme_mod( 'ps_default_page_sidebar', 'right_sidebar' );
    
    if( $sidebar_meta_option == 'default_sidebar_layout' ) {
        if( is_single() ) {
            if( $post_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif( $post_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif( $post_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif( $post_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif( is_page() ) {
            if( $page_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif( $page_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif( $page_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif( $page_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif( $archive_sidebar == 'right_sidebar' ) {
            $classes[] = 'right-sidebar';
        } elseif( $archive_sidebar == 'left_sidebar' ) {
            $classes[] = 'left-sidebar';
        } elseif( $archive_sidebar == 'no_sidebar' ) {
            $classes[] = 'no-sidebar';
        } elseif( $archive_sidebar == 'no_sidebar_center' ) {
            $classes[] = 'no-sidebar-center';
        }
    } elseif( $sidebar_meta_option == 'right_sidebar' ) {
        $classes[] = 'right-sidebar';
    } elseif( $sidebar_meta_option == 'left_sidebar' ) {
        $classes[] = 'left-sidebar';
    } elseif( $sidebar_meta_option == 'no_sidebar' ) {
        $classes[] = 'no-sidebar';
    } elseif( $sidebar_meta_option == 'no_sidebar_center' ) {
        $classes[] = 'no-sidebar-center';
    }

	return $classes;
}
add_filter( 'body_class', 'parallaxsome_body_classes' );

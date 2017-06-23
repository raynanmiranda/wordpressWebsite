<?php
/**
 * Page breadcrumb funciton area
*/
if ( ! function_exists( 'unicon_breadcrumb_section' ) ) {    
    function unicon_breadcrumb_section() {
    	global $post;
	    $breadcrumb_section = esc_attr( get_theme_mod('unicon_breadcrumb_section', 'show') );
	    $breadcrumb_menu = esc_attr( get_theme_mod('unicon_breadcrumb_menu', 'show') );
	    $breadcrumb_bg_image = esc_url( get_theme_mod('unicon_breadcrumb_bg_image') );
	    
	    @$breadcrumb_page_image = get_post_meta( $post->ID, 'unicon_bread_bg_image', true );

	    if(!empty( $breadcrumb_page_image ) ){
	        $breadcrumb_bg_image = $breadcrumb_page_image;
	    }elseif(!empty( $breadcrumb_bg_image )){
	    	$breadcrumb_bg_image = $breadcrumb_bg_image;
	    }else{
	      $breadcrumb_bg_image = get_template_directory_uri().'/assets/images/bg-service.jpg';
	    }

    	if($breadcrumb_section == 'show') { ?>
	        <div class="page_header_wrap page-banner" style="background:url('<?php echo $breadcrumb_bg_image; ?>') no-repeat center;">
	            <div class="container-wrap">
	                
	                <header class="entry-header">
	                    <?php if( is_archive() ) {
	                            the_archive_title( '<h1 class="entry-title">', '</h1>' );
	                        } elseif( is_search() ){ ?>
                              <header class="page-header">
                                <h1 class="entry-title"><?php printf( esc_html__( 'Search Results', 'unicon' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                              </header><!-- .page-header --> 
                            <?php } elseif( is_404() ){ ?>
                              <header class="page-header">
                                <h1 class="entry-title"><?php _e('404','unicon') ?></h1>
                              </header><!-- .page-header -->   
                            <?php } else{
	                          the_title( '<h1 class="entry-title">', '</h1>' );   
	                        }
	                    ?>                   
	                </header><!-- .entry-header -->

	                <?php if($breadcrumb_menu == 'show') {  unicon_breadcrumbs_menu(); } ?>

	            </div>
	        </div>
	    <?php }
    }
}
add_action( 'unicon-breadcrumb', 'unicon_breadcrumb_section' );
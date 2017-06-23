<?php
/**
 * Create a metabox to added some custom filed in posts.
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

 add_action( 'add_meta_boxes', 'parallaxsome_post_meta_options' );
 
 if( ! function_exists( 'parallaxsome_post_meta_options' ) ):
 function  parallaxsome_post_meta_options() {
    add_meta_box(
                'parallaxsome_post_meta', // $id
                esc_html__( 'Post Options', 'parallaxsome' ), // $title
                'parallaxsome_post_meta_callback', // $callback
                'post', // $page
                'normal', // $context
                'high'
            ); // $priority
    add_meta_box(
                'parallaxsome_page_meta', // $id
                esc_html__( 'Page Options', 'parallaxsome' ), // $title
                'parallaxsome_post_meta_callback', // $callback
                'page', // $page
                'normal', // $context
                'high'
            ); // $priority
 }
 endif;

 $parallaxsome_post_sidebar_options = array(
        'default-layout' => array(
                        'id'		=> 'post-defalut-layout',
                        'value'     => 'default_sidebar_layout',
                        'label'     => esc_html__( 'Default Sidebar', 'parallaxsome' ),
                        'thumbnail' => get_template_directory_uri() . '/assets/images/default-sidebar.png'
                    ), 
        'left-sidebar' => array(
                        'id'		=> 'post-right-sidebar',
                        'value'     => 'left_sidebar',
                        'label'     => esc_html__( 'Left sidebar', 'parallaxsome' ),
                        'thumbnail' => get_template_directory_uri() . '/assets/images/left-sidebar.png'
                    ), 
        'right-sidebar' => array(
                        'id'		=> 'post-left-sidebar',
                        'value' => 'right_sidebar',
                        'label' => esc_html__( 'Right sidebar', 'parallaxsome' ),
                        'thumbnail' => get_template_directory_uri() . '/assets/images/right-sidebar.png'
                    ),
        'no-sidebar' => array(
                        'id'		=> 'post-no-sidebar',
                        'value'     => 'no_sidebar',
                        'label'     => esc_html__( 'No sidebar Full width', 'parallaxsome' ),
                        'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar.png'
                    ),        
        'no-sidebar-center' => array(
                        'id'		=> 'post-no-sidebar-center',
                        'value'     => 'no_sidebar_center',
                        'label'     => esc_html__( 'No sidebar Content Centered', 'parallaxsome' ),
                        'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar-center.png'
                    )
    );

/**
 * Callback function for post option
 */
if( ! function_exists( 'parallaxsome_post_meta_callback' ) ):
	function parallaxsome_post_meta_callback() {
		global $post, $parallaxsome_post_sidebar_options;
		wp_nonce_field( basename( __FILE__ ), 'parallaxsome_post_meta_nonce' );
?>
		<div class="ps-meta-container clearfix">
			<ul class="ps-meta-menu-wrapper">
				<li class="ps-meta-tab active" id="ps-info-tab"><span class="dashicons dashicons-clipboard"></span><?php esc_html_e( 'Information', 'parallaxsome' ); ?></li>
				<li class="ps-meta-tab" id="ps-sidebar-tab"><span class="dashicons dashicons-exerpt-view"></span><?php esc_html_e( 'Sidebars', 'parallaxsome' ); ?></li>
			</ul><!-- .ps-meta-menu-wrapper -->
			<div class="ps-metabox-content-wrapper">
				
				<!-- Info tab content -->
				<div class="ps-single-meta active" id="ps-info-content">
					<div class="content-header">
						<h4><?php esc_html_e( 'About Metabox Options', 'parallaxsome' ) ;?></h4>
					</div><!-- .content-header -->
					<div class="meta-options-wrap"><?php esc_html_e('If we choose sidebar layout from the metabox optiin the page or post will show sidebar layout form the metabox and if we leave empty the sidebar layout will display form customizer->Design Setting.','parallaxsome') ?></div><!-- .meta-options-wrap  -->
				</div><!-- #ps-info-content -->

				<!-- Sidebar tab content -->
				<div class="ps-single-meta" id="ps-sidebar-content">
					<div class="content-header">
						<h4><?php esc_html_e( 'Available Sidebars', 'parallaxsome' ) ;?></h4>
						<span class="section-desc"><em><?php esc_html_e( 'Select sidebar from available options which replaced sidebar layout from customizer settings.', 'parallaxsome' ); ?></em></span>
					</div><!-- .content-header -->
					<div class="ps-meta-options-wrap">
						<div class="buttonset">
							<?php
			                   	foreach ( $parallaxsome_post_sidebar_options as $field ) {
			                    	$parallaxsome_post_sidebar = get_post_meta( $post->ID, 'ps_post_sidebar_layout', true );
			                ?>
			                    	<input type="radio" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo $field['value']; ?>" name="ps_post_sidebar_layout" <?php checked( $field['value'], $parallaxsome_post_sidebar ); if( empty( $parallaxsome_post_sidebar ) && $field['value'] == 'default_layout' ){ echo "checked='checked'";}  ?> />
			                    	<label for="<?php echo esc_attr( $field['id'] ); ?>">
			                    		<span class="screen-reader-text"><?php echo esc_html( $field['label'] ); ?></span>
			                    		<img src="<?php echo esc_url( $field['thumbnail'] ); ?>" title="<?php echo esc_attr( $field['label'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" />
			                    	</label>
			                    
			                <?php } ?>
						</div><!-- .buttonset -->
					</div><!-- .meta-options-wrap  -->
				</div><!-- #ps-sidebar-content -->

			</div><!-- .ps-metabox-content-wrapper -->
			</div><!-- .ps-meta-container -->
<?php		
	}
endif;

/*--------------------------------------------------------------------------------------------------------------*/
/**
 * Function for save value of meta opitons
 *
 * @since 1.0.0
 */
add_action( 'save_post', 'parallaxsome_save_post_meta' );

if( ! function_exists( 'parallaxsome_save_post_meta' ) ):

function parallaxsome_save_post_meta( $post_id ) {

    global $post, $parallaxsome_post_sidebar_options;

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'parallaxsome_post_meta_nonce' ] ) || !wp_verify_nonce( wp_unslash($_POST['parallaxsome_post_meta_nonce'] ), basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }

    /*Page sidebar*/
    foreach ( $parallaxsome_post_sidebar_options as $field ) {  
        //Execute this saving function
        $old = get_post_meta( $post_id, 'ps_post_sidebar_layout', true ); 
        $new = sanitize_text_field( wp_unslash( $_POST['ps_post_sidebar_layout'] ));
        if ( $new && $new != $old ) {  
            update_post_meta ( $post_id, 'ps_post_sidebar_layout', $new );  
        } elseif ( '' == $new && $old ) {  
            delete_post_meta( $post_id,'ps_post_sidebar_layout', $old );  
        }
    } // end foreach
}
endif;  
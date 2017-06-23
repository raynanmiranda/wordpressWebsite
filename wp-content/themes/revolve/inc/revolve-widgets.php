<?php
    /** ============= Revolve Widgets ====================== **/ 
    /**
     * Include helper functions that display widget fields in the dashboard
     *
     * @since Accesspress Widget Pack 1.0
     */
    require get_template_directory() . '/inc/widgets/widget-fields.php';
    
    /**
     * Register Feature Post Widget
     *
     * @since accesspress Widget Pack 1.0
     */
    require get_template_directory() . '/inc/widgets/widget-feature-post.php';

    /** ===============  Revolve Widget Areas =============== **/   
    
        /** Registering Additional Widget **/
        add_action( 'widgets_init', 'revolve_additional_widgets_init' );
        
        function revolve_additional_widgets_init() {
            /** Widget 1 **/
            register_sidebar( array(
        		'name'          => esc_html__( 'Widget 1', 'revolve' ),
        		'id'            => 'revolve-widget-1',
        		'description'   => '',
        		'before_widget' => '<section id="%1$s" class="widget %2$s">',
        		'after_widget'  => '</section>',
        		'before_title'  => '<h2 class="widget-title">',
        		'after_title'   => '</h2>',
        	) );
        }
<?php
    /** Portfolio Page Customizer Options **/
    function revolve_portpage_customize_register( $wp_customize ) {
        /** Portfolio Page Panel **/
        $wp_customize->add_panel( 'revolve_portpage_panel', array(
            'priority' => 15,
            'capability' => 'edit_theme_options',
            'title' => __( 'Portfolio Page Setting', 'revolve' ),
            'description' => __( 'Configure Portfolio Page.', 'revolve' ),
        ) );
        
        /** Add Portfolio Page Section **/
            /** Portfolio Page Section **/
                $wp_customize->add_section( 'revolve_portarch_section', array(
                    'priority' => 20,
                    'capability' => 'edit_theme_options',
                    'title' => __( 'Portfolio Page', 'revolve' ),
                    'description' => __( 'Configure Portfolio Page', 'revolve' ),
                    'panel' => 'revolve_portpage_panel',
                ) );
                
            /** Portfolio Category Control **/
            $wp_customize->add_setting( 'revolve_port_category' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Single_Cat_Control(
                   $wp_customize,
                   'revolve_port_category',
                   array(
                       'label'      => __( 'Portfolio Category', 'revolve' ),
                       'description' => __('Select the category for portfolio page that you have created using "Portfolio" page template.', 'revolve'),
                       'section'    => 'revolve_portarch_section',
                       'settings'   => 'revolve_port_category',
                   )
               )
           );
    }
    add_action( 'customize_register', 'revolve_portpage_customize_register' );
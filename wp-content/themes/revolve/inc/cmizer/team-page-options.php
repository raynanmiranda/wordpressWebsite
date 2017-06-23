<?php
    /** Team Page Customizer Options **/
    function revolve_teampage_customize_register( $wp_customize ) {
        /** Team Page Panel **/
        $wp_customize->add_panel( 'revolve_teampage_panel', array(
            'priority' => 15,
            'capability' => 'edit_theme_options',
            'title' => __( 'Team Page Setting', 'revolve' ),
            'description' => __( 'Configure Team Page.', 'revolve' ),
        ) );
        
        /** Add Team Page Section **/
            /** Team Page Section **/
                $wp_customize->add_section( 'revolve_teamarch_section', array(
                    'priority' => 20,
                    'capability' => 'edit_theme_options',
                    'title' => __( 'Team Page', 'revolve' ),
                    'description' => __( 'Configure Team Page', 'revolve' ),
                    'panel' => 'revolve_teampage_panel',
                ) );
                
            /** Team Category Control **/
            $wp_customize->add_setting( 'revolve_team_category' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Single_Cat_Control(
                   $wp_customize,
                   'revolve_team_category',
                   array(
                       'label'      => __( 'Team Category', 'revolve' ),
                       'description' => __('Select the category for team page that you have created using "Team" page template.', 'revolve'),
                       'section'    => 'revolve_teamarch_section',
                       'settings'   => 'revolve_team_category',
                   )
               )
           );
    }
    add_action( 'customize_register', 'revolve_teampage_customize_register' );
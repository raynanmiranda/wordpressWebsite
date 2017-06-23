<?php
    /** Miscellaneous Customizer Options **/
    function revolve_misc_customize_register( $wp_customize ) {
        /****** Header Settings *******/
        /** Add General Settings Panel **/
        $wp_customize->add_panel( 'revolve_genset_panel', array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'title' => __( 'General Setting', 'revolve' ),
            'description' => __( 'Configure Blog Page.', 'revolve' ),
        ) );

            /** Social Icon Section **/
                $wp_customize->add_section( 'revolve_sicon_section', array(
                    'priority' => 11,
                    'capability' => 'edit_theme_options',
                    'title' => __( 'Social Icons', 'revolve' ),
                    'description' => __( 'Configure Social Icons for the site', 'revolve' ),
                    'panel' => 'revolve_genset_panel',
                ) );

              /** Facebook Icon **/
              $wp_customize->add_setting( 'revolve_social_fb' , array( 'default' => '', 'sanitize_callback' => 'esc_url_raw') );
              $wp_customize->add_control(
                'revolve_social_fb', 
                array(
                    'label'    => __( 'Facebook URL', 'revolve' ),
                    'description' => __('Enter the url for Facebook', 'revolve'),
                    'type'     => 'text',
                    'section' => 'revolve_sicon_section',
                )
              );

              /** Twitter Icon **/
              $wp_customize->add_setting( 'revolve_social_tw' , array( 'default' => '', 'sanitize_callback' => 'esc_url_raw') );
              $wp_customize->add_control(
                'revolve_social_tw', 
                array(
                    'label'    => __( 'Twitter URL', 'revolve' ),
                    'description' => __('Enter the url for twitter', 'revolve'),
                    'type'     => 'text',
                    'section' => 'revolve_sicon_section',
                )
              );

              /** Google Plus Icon **/
              $wp_customize->add_setting( 'revolve_social_gplus' , array( 'default' => '', 'sanitize_callback' => 'esc_url_raw') );
              $wp_customize->add_control(
                'revolve_social_gplus', 
                array(
                    'label'    => __( 'Google Plus URL', 'revolve' ),
                    'description' => __('Enter the url for Google+', 'revolve'),
                    'type'     => 'text',
                    'section' => 'revolve_sicon_section',
                )
              );

              /** LinkedIn Icon **/
              $wp_customize->add_setting( 'revolve_social_lnk' , array( 'default' => '', 'sanitize_callback' => 'esc_url_raw') );
              $wp_customize->add_control(
                'revolve_social_lnk', 
                array(
                    'label'    => __( 'LinkedIn URL', 'revolve' ),
                    'description' => __('Enter the url for LinkedIn', 'revolve'),
                    'type'     => 'text',
                    'section' => 'revolve_sicon_section',
                )
              );

              /** Youtube Icon **/
              $wp_customize->add_setting( 'revolve_social_ytube' , array( 'default' => '', 'sanitize_callback' => 'esc_url_raw') );
              $wp_customize->add_control(
                'revolve_social_ytube', 
                array(
                    'label'    => __( 'Youtube URL', 'revolve' ),
                    'description' => __('Enter the url for Youtube', 'revolve'),
                    'type'     => 'text',
                    'section' => 'revolve_sicon_section',
                )
              );

        /** Remove Default Sections **/
        $wp_customize->remove_section( 'header_image' );
        $wp_customize->remove_section( 'background_image' );
        
        $wp_customize->remove_section( 'title_tagline' );
        $wp_customize->remove_section( 'colors' );
        $wp_customize->remove_section( 'static_front_page' );
        /** Remove Default Heder Text only option **/
        $wp_customize->remove_control( 'display_header_text' );
        
        /* Site Identity */
        $wp_customize->add_section(
            'title_tagline',
            array(
                'title'=>__('Site Identity', 'revolve'), 'panel' => 'revolve_genset_panel'
            )
        );
        
        /* Colors */
        $wp_customize->add_section(
            'colors',
            array(
                'title'=>__('Colors', 'revolve'), 'panel' => 'revolve_genset_panel'
            )
        );
        
        /* Static Front Page */
        $wp_customize->add_section(
            'static_front_page',
            array(
                'title'=>__('Static Front Page', 'revolve'), 'panel' => 'revolve_genset_panel'
            )
        );

        /** Header Logo Display Control **/
            $wp_customize->add_setting( 'revolve_header_display' , array( 'default' => 'text_only', 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
                'revolve_header_display', 
                array(
                    'label'    => __( 'Display (Logo/Text)', 'revolve' ),
                    'description' => __('Choose the option to display either logo only, site tagline only or both.', 'revolve'),
                    'section'  => 'title_tagline',
                    'type'     => 'select',
                    'choices' => array(
                        'text_only' => __('Text Only', 'revolve'),
                        'logo_only' => __('Logo Only', 'revolve'),
                        'both' => __('Both', 'revolve'),
                    )
                )
            );

    /** Revolve Banner **/
    $wp_customize->add_section( 'revolvepro_features', array( 'title' =>  __('Important Links', 'revolve'), 'priority' => '9' ));
    $wp_customize->add_setting('revolve_theme_info_theme',array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ));
    $wp_customize->add_control( new Revolve_Theme_Info_Custom_Control( $wp_customize ,'revolve_theme_info_theme',array(
        'label' => __( 'Revolve Pro Features' , 'revolve' ),
        'section' => 'revolvepro_features',
    )));
            
    }
    add_action( 'customize_register', 'revolve_misc_customize_register' );
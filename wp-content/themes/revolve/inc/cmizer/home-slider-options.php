<?php
    /** Home Slider Customizer Options **/
    function revolve_hslider_customize_register( $wp_customize ) {
        /** Home Slider Panel **/
        $wp_customize->add_panel( 'revolve_homeslider_panel', array(
            'priority' => 15,
            'capability' => 'edit_theme_options',
            'title' => __( 'Home Slider Setting', 'revolve' ),
            'description' => __( 'Configure Home Slider.', 'revolve' ),
        ) );

        /* ================= Home Slide =================== */
            /** Home Slide Section **/
            $wp_customize->add_section( 'revolve_hslide_section', array(
                'priority' => 1,
                'capability' => 'edit_theme_options',
                'title' => __( 'Home Slide', 'revolve' ),
                'description' => __( 'Configure Home Slide', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Home Slide **/
            $wp_customize->add_setting( 'revolve_hslide' , array( 'default' => 0, 'sanitize_callback' => 'esc_url_raw') );
            $wp_customize->add_control(
               new WP_Customize_Image_Control(
                   $wp_customize,
                   'revolve_hslide',
                   array(
                       'label'      => __( 'Select Image', 'revolve' ),
                       'description' => __('Select the image to appear as home slide', 'revolve'),
                       'section'    => 'revolve_hslide_section',
                       'settings'   => 'revolve_hslide',
                   )
               )
           );
           
           /** Home Slide Caption Title **/
            $wp_customize->add_setting( 'revolve_hslide_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_hslide_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the home slide', 'revolve'),
                    'section'  => 'revolve_hslide_section',
                    'settings' => 'revolve_hslide_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Home Slide Caption Content **/
            $wp_customize->add_setting( 'revolve_hslide_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_hslide_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the home slide', 'revolve'),
                    'section'  => 'revolve_hslide_section',
                    'settings' => 'revolve_hslide_caption_content',
                    'type'     => 'textarea',
                )
            );
        
        /* ================= Slide 1 =================== */
            /** Slider 1 Section **/
            $wp_customize->add_section( 'revolve_slide1_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 1', 'revolve' ),
                'description' => __( 'Configure Slide 1', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 1 Page **/
            $wp_customize->add_setting( 'revolve_slide1' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide1',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for the slide1', 'revolve'),
                       'section'    => 'revolve_slide1_section',
                       'settings'   => 'revolve_slide1',
                   )
               )
           );
           
           /** Slide 1 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide1_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide1_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide1', 'revolve'),
                    'section'  => 'revolve_slide1_section',
                    'settings' => 'revolve_slide1_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 1 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide1_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide1_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide1', 'revolve'),
                    'section'  => 'revolve_slide1_section',
                    'settings' => 'revolve_slide1_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 1 Widget **/
            $wp_customize->add_setting( 'revolve_slide1_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide1_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in the slide1', 'revolve'),
                       'section'    => 'revolve_slide1_section',
                       'settings'   => 'revolve_slide1_widget',
                   )
               )
           );
        
        /* ================= Slide 2 =================== */
            /** Slider 2 Section **/
            $wp_customize->add_section( 'revolve_slide2_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 2', 'revolve' ),
                'description' => __( 'Configure Slide 2', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 2 Page **/
            $wp_customize->add_setting( 'revolve_slide2' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide2',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for slide2', 'revolve'),
                       'section'    => 'revolve_slide2_section',
                       'settings'   => 'revolve_slide2',
                   )
               )
           );
           
           /** Slide 2 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide2_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide2_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide2', 'revolve'),
                    'section'  => 'revolve_slide2_section',
                    'settings' => 'revolve_slide2_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 2 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide2_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide2_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide2', 'revolve'),
                    'section'  => 'revolve_slide2_section',
                    'settings' => 'revolve_slide2_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 2 Widget **/
            $wp_customize->add_setting( 'revolve_slide2_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide2_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide2', 'revolve'),
                       'section'    => 'revolve_slide2_section',
                       'settings'   => 'revolve_slide2_widget',
                   )
               )
           );
        
        /* ================= Slide 3 =================== */
            /** Slider 3 Section **/
            $wp_customize->add_section( 'revolve_slide3_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 3', 'revolve' ),
                'description' => __( 'Configure Slide 3', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 3 Page **/
            $wp_customize->add_setting( 'revolve_slide3' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide3',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for slide3', 'revolve'),
                       'section'    => 'revolve_slide3_section',
                       'settings'   => 'revolve_slide3',
                   )
               )
           );
           
           /** Slide 3 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide3_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide3_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide3', 'revolve'),
                    'section'  => 'revolve_slide3_section',
                    'settings' => 'revolve_slide3_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 3 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide3_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide3_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide3', 'revolve'),
                    'section'  => 'revolve_slide3_section',
                    'settings' => 'revolve_slide3_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 3 Widget **/
            $wp_customize->add_setting( 'revolve_slide3_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide3_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide3', 'revolve'),
                       'section'    => 'revolve_slide3_section',
                       'settings'   => 'revolve_slide3_widget',
                   )
               )
           );
        /* ================= Slide 4 =================== */
            /** Slider 4 Section **/
            $wp_customize->add_section( 'revolve_slide4_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 4', 'revolve' ),
                'description' => __( 'Configure Slide 4', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 4 Page **/
            $wp_customize->add_setting( 'revolve_slide4' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide4',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for the slide4', 'revolve'),
                       'section'    => 'revolve_slide4_section',
                       'settings'   => 'revolve_slide4',
                   )
               )
           );
           
           /** Slide 4 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide4_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide4_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide4', 'revolve'),
                    'section'  => 'revolve_slide4_section',
                    'settings' => 'revolve_slide4_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 4 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide4_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide4_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide4', 'revolve'),
                    'section'  => 'revolve_slide4_section',
                    'settings' => 'revolve_slide4_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 4 Widget **/
            $wp_customize->add_setting( 'revolve_slide4_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide4_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide4', 'revolve'),
                       'section'    => 'revolve_slide4_section',
                       'settings'   => 'revolve_slide4_widget',
                   )
               )
           );
            
        /* ================= Slide 5 =================== */
            /** Slider 5 Section **/
            $wp_customize->add_section( 'revolve_slide5_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 5', 'revolve' ),
                'description' => __( 'Configure Slide 5', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 5 Page **/
            $wp_customize->add_setting( 'revolve_slide5' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide5',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for the slide5', 'revolve'),
                       'section'    => 'revolve_slide5_section',
                       'settings'   => 'revolve_slide5',
                   )
               )
           );
           
           /** Slide 5 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide5_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide5_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide5', 'revolve'),
                    'section'  => 'revolve_slide5_section',
                    'settings' => 'revolve_slide5_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 5 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide5_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide5_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide5', 'revolve'),
                    'section'  => 'revolve_slide5_section',
                    'settings' => 'revolve_slide5_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 5 Widget **/
            $wp_customize->add_setting( 'revolve_slide5_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide5_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide5', 'revolve'),
                       'section'    => 'revolve_slide5_section',
                       'settings'   => 'revolve_slide5_widget',
                   )
               )
           );
        
        /* ================= Slide 6 =================== */
            /** Slider 6 Section **/
            $wp_customize->add_section( 'revolve_slide6_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 6', 'revolve' ),
                'description' => __( 'Configure Slide 6', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 6 Page **/
            $wp_customize->add_setting( 'revolve_slide6' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide6',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for the slide6', 'revolve'),
                       'section'    => 'revolve_slide6_section',
                       'settings'   => 'revolve_slide6',
                   )
               )
           );
           
           /** Slide 6 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide6_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide6_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide6', 'revolve'),
                    'section'  => 'revolve_slide6_section',
                    'settings' => 'revolve_slide6_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 6 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide6_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide6_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide6', 'revolve'),
                    'section'  => 'revolve_slide6_section',
                    'settings' => 'revolve_slide6_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 6 Widget **/
            $wp_customize->add_setting( 'revolve_slide6_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide6_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide6', 'revolve'),
                       'section'    => 'revolve_slide6_section',
                       'settings'   => 'revolve_slide6_widget',
                   )
               )
           );
           
        /* ================= Slide 7 =================== */
            /** Slider 7 Section **/
            $wp_customize->add_section( 'revolve_slide7_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 7', 'revolve' ),
                'description' => __( 'Configure Slide 7', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 7 Page **/
            $wp_customize->add_setting( 'revolve_slide7' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide7',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for the slide7', 'revolve'),
                       'section'    => 'revolve_slide7_section',
                       'settings'   => 'revolve_slide7',
                   )
               )
           );
           
           /** Slide 7 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide7_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide7_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide7', 'revolve'),
                    'section'  => 'revolve_slide7_section',
                    'settings' => 'revolve_slide7_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 7 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide7_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide7_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide7', 'revolve'),
                    'section'  => 'revolve_slide7_section',
                    'settings' => 'revolve_slide7_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 7 Widget **/
            $wp_customize->add_setting( 'revolve_slide7_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide7_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide7', 'revolve'),
                       'section'    => 'revolve_slide7_section',
                       'settings'   => 'revolve_slide7_widget',
                   )
               )
           );
           
        /* ================= Slide 8 =================== */
            /** Slider 8 Section **/
            $wp_customize->add_section( 'revolve_slide8_section', array(
                'priority' => 10,
                'capability' => 'edit_theme_options',
                'title' => __( 'Slide 8', 'revolve' ),
                'description' => __( 'Configure Slide 8', 'revolve' ),
                'panel' => 'revolve_homeslider_panel',
            ) );
            
            /** Slide 8 Page **/
            $wp_customize->add_setting( 'revolve_slide8' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Page_Control(
                   $wp_customize,
                   'revolve_slide8',
                   array(
                       'label'      => __( 'Select Page', 'revolve' ),
                       'description' => __('Select the page for slide8', 'revolve'),
                       'section'    => 'revolve_slide8_section',
                       'settings'   => 'revolve_slide8',
                   )
               )
           );
           
           /** Slide 8 Caption Title **/
            $wp_customize->add_setting( 'revolve_slide8_caption_title' , array( 'default' => __('Caption Title', 'revolve'), 'sanitize_callback' => 'esc_html') );
            $wp_customize->add_control(
                'revolve_slide8_caption_title', 
                array(
                    'label'    => __( 'Caption Title', 'revolve' ),
                    'description' => __('Set the caption title for the slide8', 'revolve'),
                    'section'  => 'revolve_slide8_section',
                    'settings' => 'revolve_slide8_caption_title',
                    'type'     => 'text',
                )
            );
            
            /** Slide 8 Caption Content **/
            $wp_customize->add_setting( 'revolve_slide8_caption_content' , array( 'default' => __('Caption Content', 'revolve'), 'sanitize_callback' => 'esc_textarea') );
            $wp_customize->add_control(
                'revolve_slide8_caption_content', 
                array(
                    'label'    => __( 'Caption Content', 'revolve' ),
                    'description' => __('Set the caption description for the slide8', 'revolve'),
                    'section'  => 'revolve_slide8_section',
                    'settings' => 'revolve_slide8_caption_content',
                    'type'     => 'textarea',
                )
            );
            
            /** Slide 8 Widget **/
            $wp_customize->add_setting( 'revolve_slide8_widget' , array( 'default' => 0, 'sanitize_callback' => 'esc_attr') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Widget_Control(
                   $wp_customize,
                   'revolve_slide8_widget',
                   array(
                       'label'      => __( 'Select Widget', 'revolve' ),
                       'description' => __('Select the widget to appear in slide8', 'revolve'),
                       'section'    => 'revolve_slide8_section',
                       'settings'   => 'revolve_slide8_widget',
                   )
               )
           );
    }
    add_action( 'customize_register', 'revolve_hslider_customize_register' );
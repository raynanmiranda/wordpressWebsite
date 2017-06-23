<?php
    /** Blog Page Customizer Options **/
    function revolve_blogpage_customize_register( $wp_customize ) {
        /** Blog Page Panel **/
        $wp_customize->add_panel( 'revolve_blogpage_panel', array(
            'priority' => 15,
            'capability' => 'edit_theme_options',
            'title' => __( 'Blog Page Setting', 'revolve' ),
            'description' => __( 'Configure Blog Page.', 'revolve' ),
        ) );
        
        /** Add Blog Page Section **/
            /** Blog Page Section **/
                $wp_customize->add_section( 'revolve_blogarch_section', array(
                    'priority' => 20,
                    'capability' => 'edit_theme_options',
                    'title' => __( 'Blog Page', 'revolve' ),
                    'description' => __( 'Configure Blog Page', 'revolve' ),
                    'panel' => 'revolve_blogpage_panel',
                ) );
                
            /** Blog Exclude Category Control  **/
            $wp_customize->add_setting( 'revolve_blog_exclude_cat' , array( 'default' => 0, 'sanitize_callback' => 'sanitize_text_field') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Mul_Cat_Control(
                   $wp_customize,
                   'revolve_blog_exclude_cat',
                   array(
                       'label'      => __( 'Exclude Category', 'revolve' ),
                       'description' => __('Select all the categories you wish to exclude from the blog page.', 'revolve'),
                       'section'    => 'revolve_blogarch_section',
                       'settings'   => 'revolve_blog_exclude_cat',
                   )
               )
           );
           
           /** Featured Blog Post **/
            $wp_customize->add_setting( 'revolve_blog_feat_post' , array( 'default' => 0, 'sanitize_callback' => 'absint') );
            $wp_customize->add_control(
               new Revolve_WP_Customize_Select_Post_Control(
                   $wp_customize,
                   'revolve_blog_feat_post',
                   array(
                       'label'      => __( 'Feature Post', 'revolve' ),
                       'description' => __('Select the blog post you wish to display in the header section of the blog/archive page.', 'revolve'),
                       'section'    => 'revolve_blogarch_section',
                       'settings'   => 'revolve_blog_feat_post',
                   )
               )
           );
    }
    add_action( 'customize_register', 'revolve_blogpage_customize_register' );
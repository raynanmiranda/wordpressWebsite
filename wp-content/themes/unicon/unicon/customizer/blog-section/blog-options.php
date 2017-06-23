<?php
    global $unicon_categories;

    $wp_customize->add_setting( 'blog_categories', array(
        'default'           => array( 'uncategorized' ),
        'sanitize_callback' => 'unicon_multiple_categories_sanitize'
    ) );

    $wp_customize->add_control( new Unicon_Customize_Control_Checkbox_Multiple( $wp_customize, 'blog_categories', array(
        'section' => 'unicon_blogs_setting',
        'label'   => esc_html__( 'Select Blogs Categories', 'unicon' ),
        'choices' => $unicon_categories
    ) ) );

    $wp_customize->add_setting( 'unicon_display_number_blog_post', array(
        'default'           => 10,
        'sanitize_callback' => 'unicon_number_sanitize'
    ) );

    $wp_customize->add_control( 'unicon_display_number_blog_post', array(
        'type'    => 'number',
        'label'    => __( 'Display Number of Blog Posts ', 'unicon' ),
        'section'  => 'unicon_blogs_setting'
    ) );
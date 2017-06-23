<?php
/* adding sections for header social options */
$wp_customize->add_section( 'unicon-header-quickinfo', array(
    'priority'       => 2,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Quick Contact Info', 'unicon' ),
    'panel'          => 'unicon-top-header-panel'
) );

    $wp_customize->add_setting('unicon_address_icon', array(
        'default' => 'fa fa-map-marker',
        'sanitize_callback' => 'unicon_text_sanitize', // done
    ));
    
    $wp_customize->add_control('unicon_address_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'unicon' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Address Icon', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_address_icon',
    ));
    
    $wp_customize->add_setting('unicon_map_address', array(
        'default' => 'Mathuri Sadan, 5th floor Ravi Bhawan, Kathmandu, Nepal',
        'sanitize_callback' => 'unicon_text_sanitize',  // done
    ));
    
    $wp_customize->add_control('unicon_map_address',array(
        'type' => 'text',
        'label' => __('Address', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_map_address',
    ));    
    
    
    $wp_customize->add_setting('unicon_start_open_icon', array(
        'default' => 'fa fa-clock-o',
        'sanitize_callback' => 'unicon_text_sanitize', // done
    ));
    
    $wp_customize->add_control('unicon_start_open_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'unicon' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Start Time Icon', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_start_open_icon',
    ));
    
    $wp_customize->add_setting('unicon_start_open_time', array(
        'default' => 'Mon - Fri : 08:00 - 17:00',
        'sanitize_callback' => 'unicon_text_sanitize',  // done
    ));
    
    $wp_customize->add_control('unicon_start_open_time',array(
        'type' => 'text',
        'label' => __('Opening Time', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_start_open_time',
    ));


    $wp_customize->add_setting('unicon_email_icon', array(
        'default' => 'fa fa-envelope',
        'sanitize_callback' => 'unicon_text_sanitize', // done
    ));
    
    $wp_customize->add_control('unicon_email_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'unicon' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Email Icon', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_email_icon',
    ));
    
    $wp_customize->add_setting('unicon_email_title', array(
        'default' => 'support@accesspressthemes.com',
        'sanitize_callback' => 'unicon_text_sanitize',  // done
    ));
    
    $wp_customize->add_control('unicon_email_title',array(
        'type' => 'text',
        'label' => __('Email Address', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_email_title',
    ));
    
    
    $wp_customize->add_setting('unicon_phone_icon', array(
        'default' => 'fa fa-phone',
        'sanitize_callback' => 'unicon_text_sanitize', // done
    ));
    
    $wp_customize->add_control('unicon_phone_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'unicon' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Phone Icon', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_phone_icon',
    ));
    
    $wp_customize->add_setting('unicon_phone_number', array(
        'default' => '+977-1-4671980',
        'sanitize_callback' => 'unicon_text_sanitize',  // done
    ));
    
    $wp_customize->add_control('unicon_phone_number',array(
        'type' => 'text',
        'label' => __('Phone Number', 'unicon'),
        'section' => 'unicon-header-quickinfo',
        'setting' => 'unicon_phone_number',
    )); 



/* adding sections for header social options */
$wp_customize->add_section( 'unicon-header-socialicon', array(
    'priority'       => 3,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Social Icon Options', 'unicon' ),
    'panel'          => 'unicon-top-header-panel'
) );

    /*facebook url*/
    $wp_customize->add_setting( 'unicon_facebook_url', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'unicon_facebook_url', array(
        'label'		=> __( 'Facebook url', 'unicon' ),
        'section'   => 'unicon-header-socialicon',
        'settings'  => 'unicon_facebook_url',
        'type'	  	=> 'url',
        'priority'  => 20
    ) );

    /*twitter url*/
    $wp_customize->add_setting( 'unicon_twitter_url', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'unicon_twitter_url', array(
        'label'		=> __( 'Twitter url', 'unicon' ),
        'section'   => 'unicon-header-socialicon',
        'settings'  => 'unicon_twitter_url',
        'type'	  	=> 'url',
        'priority'  => 25
    ) );

    /*google plus url*/
    $wp_customize->add_setting( 'unicon_google_plus_url', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'unicon_google_plus_url', array(
        'label'		=> __( 'Google Plus url', 'unicon' ),
        'section'   => 'unicon-header-socialicon',
        'settings'  => 'unicon_google_plus_url',
        'type'	  	=> 'url',
        'priority'  => 25
    ) );


    /*linkedin plus url*/
    $wp_customize->add_setting( 'unicon_linkedin_url', array(
        'capability'        => 'edit_theme_options',
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'unicon_linkedin_url', array(
        'label'     => __( 'Google Plus url', 'unicon' ),
        'section'   => 'unicon-header-socialicon',
        'settings'  => 'unicon_linkedin_url',
        'type'      => 'url',
        'priority'  => 30
    ) );

$wp_customize->get_section('header_image')->panel = 'unicon-top-header-panel';
$wp_customize->get_section('header_image')->title = __( 'Main Header Banner Settings', 'unicon' );
$wp_customize->get_section('header_image')->priority = 1;

    /* Top header settings options */
    $wp_customize->add_setting( 'unicon-top-header-settings-option', array(
        'default' => 'show',
        'sanitize_callback' => 'unicon_sanitize_switch_option',
    ) );

    $wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon-top-header-settings-option',  array(
        'type'      => 'switch',                    
        'label'     => __( 'Enable/Disable Option', 'unicon' ),
        'description'   => __( 'Enable/Disable Top Header Section Option', 'unicon' ),
        'section'   => 'header_image',
        'choices'   => array(
            'show'  => __( 'Enable', 'unicon' ),
            'hide'  => __( 'Disable', 'unicon' )
            )
    ) ) );

    /* Main Header Title */
    $wp_customize->add_setting( 'unicon_main_title', array(
        'sanitize_callback' => 'unicon_text_sanitize',
        'default' => __('SOLUTION FOR YOUR BUSINCESS','unicon'),
    ) );

    $wp_customize->add_control( 'unicon_main_title', array(
        'label'    => __( 'Main Title', 'unicon' ),
        'section'  => 'header_image',
        'settings' => 'unicon_main_title'
    ) );

    /* Very Short Descriptions */
    $wp_customize->add_setting( 'unicon_main_description', array(
        'sanitize_callback' => 'wp_filter_nohtml_kses',
        'default' => __('Better security happens when we work together, Get tips on further steps you can take to protect yourself online. better security happens when we work together happens','unicon'),
    ) );

    $wp_customize->add_control( 'unicon_main_description', array(
        'type' => 'textarea',
        'label'    => __( 'Very Short Description', 'unicon' ),
        'section'  => 'header_image',
        'settings' => 'unicon_main_description'
    ) );

    /* First button */
    $wp_customize->add_setting( 'unicon_first_button_title', array(
        'sanitize_callback' => 'unicon_text_sanitize',
        'default' => __('Read More','unicon'),
    ) );

    $wp_customize->add_control( 'unicon_first_button_title', array(
        'label'    => __( 'First button label', 'unicon' ),
        'section'  => 'header_image',
        'settings' => 'unicon_first_button_title'
    ) );

    $wp_customize->add_setting( 'unicon_first_button_url', array(
        'sanitize_callback' => 'esc_url_raw',
        'default' => esc_url( home_url( '/' ) ).'#focus',
    ) );

    $wp_customize->add_control( 'unicon_first_button_url', array(
        'label'    => __( 'First button link', 'unicon' ),
        'section'  => 'header_image',
        'settingsd' => 'unicon_first_button_url',
    ) );

    /* Second button */
    $wp_customize->add_setting( 'unicon_second_button_title', array(
        'sanitize_callback' => 'unicon_text_sanitize',
        'default' => __('Purchase Now','unicon'),
    ) );

    $wp_customize->add_control( 'unicon_second_button_title', array(
        'label'    => __( 'Second button label', 'unicon' ),
        'section'  => 'header_image',
        'settings' => 'unicon_second_button_title'
    ) );

    $wp_customize->add_setting( 'unicon_second_button_url', array(
        'sanitize_callback' => 'esc_url_raw',
        'default' => esc_url( home_url( '/' ) ).'#focus',
    ) );

    $wp_customize->add_control( 'unicon_second_button_url', array(
        'label'    => __( 'Second button link', 'unicon' ),
        'section'  => 'header_image',
        'settingsd' => 'unicon_second_button_url',
    ) );
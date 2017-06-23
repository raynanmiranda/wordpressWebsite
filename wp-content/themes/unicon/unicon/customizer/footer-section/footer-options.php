<?php
/* Buttom Footer settings options */
$wp_customize->add_section( 'unicon_buttom_footer_setting', array(
    'priority'       => 35,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Footer Settings', 'unicon' )
) );

    $wp_customize->add_setting( 'unicon_buttom_footer_settings_option', array(
        'default' => 'show',
        'sanitize_callback' => 'unicon_sanitize_switch_option',
    ) );

    $wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_buttom_footer_settings_option',  array(
        'type'      => 'switch',                    
        'label'     => __( 'Enable/Disable Option Buttom Footer', 'unicon' ),
        'description'   => __( 'Enable/Disable Buttom Footer Section Option', 'unicon' ),
        'section'   => 'unicon_buttom_footer_setting',
        'choices'   => array(
            'show'  => __( 'Enable', 'unicon' ),
            'hide'  => __( 'Disable', 'unicon' )
            )
    ) ) );


    $wp_customize->add_setting('unicon_footer_copyright', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',  //done
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('unicon_footer_copyright', array(
        'type' => 'textarea',
        'label' => __('Copyright', 'unicon'),
        'section' => 'unicon_buttom_footer_setting',
        'settings' => 'unicon_footer_copyright'
    ));
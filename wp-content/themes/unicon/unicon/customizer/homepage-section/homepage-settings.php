<?php
/* Adding homepage options panel*/
$wp_customize->add_panel( 'unicon-homepage-panel', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'HomePage Settings', 'unicon' ),
    'description'    => __( 'Customize your awesome site homepage settings', 'unicon' )
) );

/**
 * Load header panel file
*/
require $unicon_customizer_header_options_file_path = unicon_file_directory('unicon/customizer/homepage-section/homepage-options.php');
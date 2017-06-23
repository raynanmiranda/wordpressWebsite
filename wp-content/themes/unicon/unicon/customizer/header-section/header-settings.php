<?php
/* Adding header options panel*/
$wp_customize->add_panel( 'unicon-top-header-panel', array(
    'priority'       => 25,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Header Settings', 'unicon' ),
    'description'    => __( 'Customize your awesome site header settings', 'unicon' )
) );

/**
 * Load header panel file
*/
require $unicon_customizer_header_options_file_path = unicon_file_directory('unicon/customizer/header-section/header-options.php');
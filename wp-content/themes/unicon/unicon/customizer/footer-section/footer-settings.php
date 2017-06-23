<?php
/* Adding header options panel*/
/*$wp_customize->add_panel( 'unicon_footer_panel', array(
    'priority'       => 35,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Footer Settings', 'unicon' ),
    'description'    => __( 'Customize your awesome site footer settings', 'unicon' )
) );*/

/**
 * Load header panel file
*/
require $unicon_customizer_footer_options_file_path = unicon_file_directory('unicon/customizer/footer-section/footer-options.php');
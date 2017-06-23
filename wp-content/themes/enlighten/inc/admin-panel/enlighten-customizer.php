<?php
add_action('customize_register','enlighten_customizer');
function enlighten_customizer($wp_customize){
    $wp_customize->get_section( 'title_tagline' )->panel = 'enlighten_general_panel';      
    require get_template_directory().'/inc/admin-panel/enlighten-customizer-option.php';
    require get_template_directory().'/inc/admin-panel/enlighten-sanitize.php'; 
}
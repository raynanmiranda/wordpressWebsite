<?php
/**
 * Main include functions ( to support child theme )
 *
 * @since unicon 1.0.0
 *
 * @param string $file_path, path from the theme
 * @return string full path of file inside theme
 *
 */
if( !function_exists('unicon_file_directory') ){

    function unicon_file_directory( $file_path ){
        if( file_exists( trailingslashit( get_stylesheet_directory() ) . $file_path) ) {
            return trailingslashit( get_stylesheet_directory() ) . $file_path;
        }
        else{
            return trailingslashit( get_template_directory() ) . $file_path;
        }
    }
}


/**
 * Implement the Custom Functions.
 */
require $unicon_custom_functions_file_path = unicon_file_directory('unicon/functions.php');


/**
 * Implement the Custom Hooks.
 */
require $unicon_custom_functions_file_path = unicon_file_directory('unicon/hooks/hooks.php');


/**
 * Implement the Custom Header feature.
 */
require $unicon_custom_header_file_path = unicon_file_directory('unicon/core/custom-header.php');


/**
 * Custom template tags for this theme.
 */
require $unicon_template_tags_file_path = unicon_file_directory('unicon/core/template-tags.php');


/**
 * Custom functions that act independently of the theme templates.
 */
require $unicon_extras_file_path = unicon_file_directory('unicon/core/extras.php');


/**
 * Load Customizer File.
 */
require $unicon_customizer_file_path = unicon_file_directory('unicon/customizer/customizer.php');

/**
 * Load Jetpack compatibility file.
 */
require $unicon_jetpack_file_path = unicon_file_directory('unicon/core/jetpack.php');

/**
 * Load Header Hooks Compatibility file.
 */
require $unicon_header_file_path = unicon_file_directory('unicon/hooks/header.php');

/**
 * Load Footer Hooks Compatibility file.
 */
require $unicon_header_file_path = unicon_file_directory('unicon/hooks/footer.php');

/**
 * Load Suggested plugins activation file.
 */
require $unicon_header_file_path = unicon_file_directory('unicon/core/unicon-plugin-activation.php');
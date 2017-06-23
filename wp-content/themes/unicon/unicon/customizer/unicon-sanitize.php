<?php 

/**
 * Sanitize switch button
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 * @since 1.0.0
 */
function unicon_sanitize_switch_option( $input ) {
    $valid_keys = array(
            'show'  => __( 'Enable', 'unicon' ),
            'hide'  => __( 'Disable', 'unicon' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Sanitize number field
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 * @since 1.0.0
 */
function unicon_sanitize_number( $input ) {
    $output = intval($input);
     return $output;
}

/**
 * Sanitize checkbox field
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 * @since 1.0.0
 */
function unicon_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}

/**
 * Sanitize text field
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 * @since 1.0.0
 */
function unicon_text_sanitize( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Sanitize multiple categories for blog
 *
 * @since 1.0.0
 */
function unicon_multiple_categories_sanitize( $values ) {

    $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}


/**
 * Sanitize number fields
 *
 * @since 1.0.0
 */
function unicon_number_sanitize( $input ) {
    $output = intval($input);
    return $output;
} 
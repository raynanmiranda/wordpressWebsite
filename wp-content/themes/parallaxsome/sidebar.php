<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */
?>

<aside id="secondary" class="right-sidebar widget-area" role="complementary">
	<?php 
		if ( ! is_active_sidebar( 'parallaxsome_right_sidebar' ) ) { return; }
			dynamic_sidebar( 'parallaxsome_right_sidebar' ); 
	?>
</aside><!-- #secondary -->
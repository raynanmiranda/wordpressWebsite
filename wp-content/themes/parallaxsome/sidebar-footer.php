<?php
/**
 * The Sidebar containing the footer widget areas.
 * 
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

/**
 * If none of the sidebars have widgets, then let's bail early.
 */
if ( !is_active_sidebar( 'parallaxsome_footer_sidebar' ) &&
        !is_active_sidebar( 'parallaxsome_footer_sidebar-2' ) &&
        !is_active_sidebar( 'parallaxsome_footer_sidebar-3' ) &&
        !is_active_sidebar( 'parallaxsome_footer_sidebar-4' ) ) {
    return;
}

$parallaxsome_footer_layout = get_theme_mod( 'ps_footer_widget_layout', 'column_three' );

?>
<div class="ps-top-footer footer_<?php echo esc_attr( $parallaxsome_footer_layout ); ?> clearfix">
	<div class="ps-section-container clearfix">
		<div class="ps-footer-widget-wrapper">
			<div class="ps-footer-widget column-first">
				<?php if( is_active_sidebar( 'parallaxsome_footer_sidebar' ) ):
					dynamic_sidebar( 'parallaxsome_footer_sidebar' );
				endif;
				?>
			</div>

			<div class="ps-footer-widget column-second" style="display: <?php if( $parallaxsome_footer_layout == 'column_one' ){ echo 'none'; } else { echo 'block'; }?>;">
				<?php if( is_active_sidebar( 'parallaxsome_footer_sidebar-2' ) ):
					dynamic_sidebar( 'parallaxsome_footer_sidebar-2' );
				endif;
				?>
			</div>

			<div class="ps-footer-widget column-third" style="display: <?php if( $parallaxsome_footer_layout == 'column_one' || $parallaxsome_footer_layout == 'column_two'){ echo 'none'; } else { echo 'block'; }?>;">
				<?php if( is_active_sidebar( 'parallaxsome_footer_sidebar-3' ) ):
					dynamic_sidebar( 'parallaxsome_footer_sidebar-3' );
				endif;
				?>
			</div>

			<div class="ps-footer-widget column-forth" style="display: <?php if( $parallaxsome_footer_layout != 'column_four' ){ echo 'none'; } else { echo 'block'; }?>;">
				<?php if( is_active_sidebar( 'parallaxsome_footer_sidebar-4' ) ):
					dynamic_sidebar( 'parallaxsome_footer_sidebar-4' );
				endif;
				?>
			</div>
		</div><!-- .ps-footer-widget-wrapper -->
	</div><!-- ps-section-container -->
</div><!-- .ps-top-footer -->
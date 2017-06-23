<?php
/**
 * Footer Section Function Area
*/

/**
 * Footer Before Function Area
**/
if ( ! function_exists( 'unicon_footer_before' ) ) { 

	function unicon_footer_before() { ?>
			<footer id="colophon" class="site-footer">
		<?php
	}
}
add_action( 'unicon_footer_before', 'unicon_footer_before', 10 );


/**
 * Footer Widget area function area
**/
if ( ! function_exists( 'unicon_footer_widgets' ) ) {
	function unicon_footer_widgets() {
		if ( is_active_sidebar( 'unicon-sidebar-footer-4' ) ) {
			$widget_columns = apply_filters( 'unicon_footer_widget_regions', 4 );
		} elseif ( is_active_sidebar( 'unicon-sidebar-footer-3' ) ) {
			$widget_columns = apply_filters( 'unicon_footer_widget_regions', 3 );
		} elseif ( is_active_sidebar( 'unicon-sidebar-footer-2' ) ) {
			$widget_columns = apply_filters( 'unicon_footer_widget_regions', 2 );
		} elseif ( is_active_sidebar( 'unicon-sidebar-footer-1' ) ) {
			$widget_columns = apply_filters( 'unicon_footer_widget_regions', 1 );
		} else {
			$widget_columns = apply_filters( 'unicon_footer_widget_regions', 0 );
		}
		
		if ( $widget_columns > 0 ){ ?>
			    <div class="footer-widgetswrap unicon-col-<?php echo intval( $widget_columns ); ?> clearfix">
				    <div class="container-wrap">
					    <div class="inner-footer-widgetswrap">
							<?php if ( is_active_sidebar( 'unicon-sidebar-footer') ) : ?>
										
								<div class="block footer-widget">
						        	<?php dynamic_sidebar( 'unicon-sidebar-footer'); ?>
								
								</div>			
					        <?php endif; ?>

							<?php $i = 0; while ( $i < $widget_columns ) : $i++; ?>
						
								<?php if ( is_active_sidebar( 'unicon-sidebar-footer-' . $i ) ) : ?>
						
									<div class="block footer-widget">
							        	<?php dynamic_sidebar( 'unicon-sidebar-footer-' . intval( $i ) ); ?>
									</div>
						
						        <?php endif; ?>
						
							<?php endwhile; ?>
						</div>
					</div>
			    </div>
		<?php }
	}
}
add_action( 'unicon_footer', 'unicon_footer_widgets', 20 );


/**
 * Main Header Function Area
**/
if ( ! function_exists( 'unicon_buttom_footer' ) ) {
	function unicon_buttom_footer() { ?>
			<div class="footer-buttom">
			<div class="container-wrap">
				<div class="buttom-left">
					<?php apply_filters( 'unicon_credit', 5 ); ?>
				</div>
				<div class="buttom-right">
					<?php apply_filters( 'unicon_buttom_menu', 10 ); ?>
				</div>
			</div>
			</div>
		<?php
	}
}
add_action( 'unicon_footer', 'unicon_buttom_footer', 30 );


/**
 * Footer After Function Area
**/
if ( ! function_exists( 'unicon_footer_after' ) ) { 
	
	function unicon_footer_after() { ?>
		</footer><!-- #colophon -->
		<?php
	}
}
add_action( 'unicon_footer_after', 'unicon_footer_after', 40 );
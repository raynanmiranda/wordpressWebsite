<?php
/**
 * Template part for displaying section content in template-home.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */
?>

<?php
	$section_option = get_theme_mod( 'homepage_service_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'services_section_title', esc_html__( 'Our Services', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'services_section_sub_title', esc_html__( 'Our Works', 'parallaxsome' ) );
		$section_description = get_theme_mod( 'services_section_description', '' );
		$section_bg_image = get_theme_mod( 'service_bg_image', '' );
?>
		<section class="ps-home-section" id="section-services" data-parallax="scroll" data-image-src="<?php echo esc_url( $section_bg_image ); ?>">
			<div class="ps-section-container clearfix">
				<div class="service-title wow fadeInDown" data-wow-duration="0.5s">
					<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description ); ?>
				</div>
				<div class="section-content-wrapper wow fadeInUp" data-wow-duration="1s">
					<ul class="nav service-nav-tab">
						<?php
							$default_nav_icon = array( 'fa-flag', 'fa-database', 'fa-codepen', 'fa-hand-o-left', 'fa-coffee' );
							foreach ( $default_nav_icon as $icon_key => $icon_value ) {
								$service_nav_tab = get_theme_mod( 'service_icon_'.$icon_key, $icon_value );
								$service_page_value = get_theme_mod( 'service_page_id_'.$icon_key, '0' );
								if( !empty( $service_page_value ) ) {
									$tab_title = get_the_title( $service_page_value );
						?>
									<li>
										<a href="javascript:void(0)" data-tab="stab-<?php echo esc_attr( $icon_key ).'-'.esc_attr( $service_page_value ); ?>">
											<div class="tab-icon"><i class="fa <?php echo esc_attr( $service_nav_tab ); ?>"></i></div>
											<div class="tab-void"><span class="tab-bullet"></span></div>
											<div class="tab-title"><h4><?php echo esc_html( $tab_title ); ?></h4></div>
										</a>
									</li>
						<?php
								}							
							}
						?>
						
					</ul>
					<div class="service-tab-content clearfix">
						<?php 
							for ( $i=0;  $i <= 4 ;  $i++ ) {
								$service_page_id_value = get_theme_mod( 'service_page_id_'.$i, '0' );
								if( !empty( $service_page_id_value ) ) {
									$service_page_query = new WP_Query( array( 'page_id' => $service_page_id_value ) );
									if( $service_page_query->have_posts() ) {
										while ( $service_page_query->have_posts() ) {
											$service_page_query->the_post();
						?>
											<div class="tab-pane" id="stab-<?php echo esc_attr( $i ).'-'.esc_attr( $service_page_id_value ); ?>">
												<?php if( has_post_thumbnail() ) { ?>
													<div class="content-left">
														<?php the_post_thumbnail( 'parallaxsome_services_thumb' ); ?>
													</div>
												<?php } ?>												
												<div <?php if( has_post_thumbnail() ) { ?>class="content-right" <?php } ?>><br>
													<?php the_excerpt(); ?>
													<a class="button ps-btn" href="<?php the_permalink(); ?>" target="_blank">
														<?php esc_html_e('Learn More','parallaxsome'); ?>
													</a>
												</div>
											</div>
						<?php
										}
									}
								}
							}
							wp_reset_postdata();
						?>
					</div><!-- . service-tab-content -->
				</div><!-- .section-content-wrapper -->
			</div><!-- .ps-section-container -->
		</section>
<?php } ?>
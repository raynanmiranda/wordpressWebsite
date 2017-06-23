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
	$section_option = get_theme_mod( 'homepage_fact_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'fact_section_title', esc_html__( 'Fact About Us', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'fact_section_sub_title', esc_html__( 'Our Works', 'parallaxsome' ) );
		$section_description = get_theme_mod( 'fact_section_description', '' );
		$section_bg_image = get_theme_mod( 'fact_bg_image', '' );
?>
		<section class="ps-home-section" id="section-fact" data-parallax="scroll" data-image-src="<?php echo esc_url( $section_bg_image ); ?>">
			<div class="ps-section-container">
				<div class="fact-title wow fadeInDown" data-wow-duration="0.5s">
					<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description ); ?>
				</div>
				<div class="section-content-wrapper wow fadeInUp clearfix" data-wow-duration="1s">
					<div class="ps-fact-wrapper">
						<?php
							$ps_default_fact_icon = array( 'fa-coffee', 'fa-rocket', 'fa-code', 'fa-thumbs-o-up' );
							$ps_default_fact_title = array( 'Cups Consumed', 'Projects Lunched', 'Lines of Code', 'Satisfied Clients' );
							$ps_default_fact_number = array( '798', '237', '54871', '25084' );
                            $ps_count = '0';
							foreach ( $ps_default_fact_icon as $icon_key => $icon_value ) {
								$fact_icon = get_theme_mod( 'fact_icon_'.$icon_key, $icon_value );
								$fact_title = get_theme_mod( 'fact_counter_title_'.$icon_key, $ps_default_fact_title[$icon_key] );
								$fact_number = get_theme_mod( 'fact_counter_number_'.$ps_count, $ps_default_fact_number[$ps_count] );
								if( !empty( $fact_title ) ) {
						?>
								<div class="ps-signle-fact-wrap" id="single-fact-<?php echo esc_attr( $icon_key ); ?>">
									<div class="ps-fact-icon"><i class="fa <?php echo esc_attr( $fact_icon ); ?>"></i></div>
									<div class="ps-fact-details">
										<span class="ps-fact-number"><?php echo intval( $fact_number ); ?></span>
										<h3 class="ps-fact-title"><?php echo esc_html( $fact_title ); ?></h3>
									</div>
								</div><!-- .signle-fact-wrap -->
						<?php
								}
                                $ps_count++;
							}
						?>
					</div><!-- .ps-fact-wrapper -->
				</div><!-- .section-content-wrapper -->
			</div><!-- .ps-section-container -->
		</section>
<?php } ?>
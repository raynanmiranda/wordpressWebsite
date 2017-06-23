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
	$section_option = get_theme_mod( 'homepage_testimonials_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'testimonials_section_title', esc_html__( 'Clients Say', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'testimonials_section_sub_title', esc_html__( 'Group Together', 'parallaxsome' ) );
?>
		<section class="ps-home-section wow fadeInDown" data-wow-duration="1s" id="section-testimonials">
			<div class="ps-section-container">
				<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description = null ); ?>
				<div class="section-content-wrapper">
					<?php
						$testimonials_cat_id = get_theme_mod( 'testimonials_cat_id', '0' );
						if( !empty( $testimonials_cat_id ) ) {
							$home_testimonials_args = array( 'cat' => $testimonials_cat_id );
							$home_testimonials_query = new WP_Query( $home_testimonials_args );
							if( $home_testimonials_query->have_posts() ) {
								echo '<ul class="testiSlider">';
								while( $home_testimonials_query->have_posts() ) {
									$home_testimonials_query->the_post();
									$image_id = get_post_thumbnail_id();
									$image_path = wp_get_attachment_image_src( $image_id, 'thumbnail', true );
									$author_position = get_post( $image_id )->post_excerpt;
					?>
									<li>
										<div class="single-testi-wrapper">
											<figure><img src="<?php echo esc_url( $image_path[0] ); ?>" /></figure>
											<div class="testi-info-wrapper">
												<div class="testi-content"><?php the_content(); ?></div>
												<h4 class="testi-author"><?php the_title(); ?></h4>
												<?php if( !empty( $author_position ) ) { ?>
													<span class="position"><?php echo esc_html( $author_position ); ?></span>
												<?php } ?>
											</div><!-- .testi-info -->
										</div><!-- .single-testi-wrapper -->
									</li>
					<?php
								}
								echo '</ul>';
							}
							wp_reset_postdata();
						}
					?>
				</div><!-- .section-content-wrapper -->
			</div>
		</section>

<?php } ?>
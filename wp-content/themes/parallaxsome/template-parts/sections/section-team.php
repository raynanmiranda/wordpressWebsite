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
	$section_option = get_theme_mod( 'homepage_team_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'team_section_title', esc_html__( 'Our Team', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'team_section_sub_title', esc_html__( 'Group Together', 'parallaxsome' ) );
		$section_description = get_theme_mod( 'team_section_description', '' );
?>
		<section class="ps-home-section wow slideInUp" data-wow-duration="1s" id="section-team">
			<div class="ps-section-container">
				<div class="team-title wow fadeInDown" data-wow-duration="0.5s">
				    <?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description ); ?>
                </div>
				<div class="section-content-wrapper wow fadeInUp" data-wow-duration="1s">
					<?php 
						$team_sec_cat_id = get_theme_mod( 'team_cat_id', '0' );
						if( !empty( $team_sec_cat_id ) ) {
							$home_team_args = array( 
												'cat' => $team_sec_cat_id,
												'posts_per_page' => 4
											);
							$home_team_query = new WP_Query( $home_team_args );
							if( $home_team_query->have_posts() ) {
					?>
							<div class="team-wrapper">
								<ul class="clearfix">
					<?php
								while( $home_team_query->have_posts() ) {
									$home_team_query->the_post();
									$image_id = get_post_thumbnail_id();
									$image_path = wp_get_attachment_image_src( $image_id, 'parallaxsome_team_thumb', true );
									$team_position = get_post( $image_id )->post_excerpt;
					?>
									<li class="team-box-item">
										<div class="team-image-container">
											<figure><img src="<?php echo esc_url( $image_path[0] ); ?>" /></figure>
											<div class="team-info-wrapper">
												<div class="team-info-hover">
													<h4 class="team-name"><?php the_title(); ?></h4>
													<span><?php echo esc_html( $team_position ); ?></span>
													<div class="team-bio"><?php the_content(); ?></div>
												</div>
											</div><!-- .team-info-wrapper -->
										</div><!-- .team-image-container -->
									</li><!-- .team-box-item -->
					<?php
								}
					?>
								</ul>
							</div><!-- .team-wrapper -->
					<?php
							}
							wp_reset_postdata();
						}
					?>
				</div><!-- .section-content-wrapper -->
				<?php 
					$team_view_more_text = get_theme_mod( 'team_view_more_txt', esc_html__( 'View All', 'parallaxsome' ) );
				?>
				<div class="ps-section-viewall">
					<a href="<?php echo esc_url( get_category_link( $team_sec_cat_id ) ); ?>"><?php echo esc_html( $team_view_more_text ); ?></a>
				</div>
			</div><!-- .ps-section-container -->
		</section>
<?php } ?>
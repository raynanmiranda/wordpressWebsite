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
	$section_option = get_theme_mod( 'homepage_portfolio_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'portfolio_section_title', esc_html__( 'Portfolio', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'portfolio_section_sub_title', esc_html__( 'Best Porjects', 'parallaxsome' ) );
		$section_description = get_theme_mod( 'portfolio_section_description', '' );
?>
		<section class="ps-home-section" id="section-portfolio">
			<div class="portfolio-sec-title wow fadeInDown" data-wow-duration="0.5s">
				<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description ); ?>
			</div>
			<div class="section-content-wrapper wow fadeInUp" data-wow-duration="0.5s">
				<?php
					$ps_portfolio_cat_id = get_theme_mod( 'portfolio_cat_id', '0' );
					if( !empty( $ps_portfolio_cat_id ) ) {
				?>
						<div class="ps-protfolio-wrapper">
				<?php
						$ps_portfolio_args = array( 
												'cat' => $ps_portfolio_cat_id,
												'posts_per_page' => -1
											);
						$ps_portfolio_query = new WP_Query( $ps_portfolio_args );
						if( $ps_portfolio_query->have_posts() ) {
							echo '<ul id="psProjects" class="cs-hidden">';
							while ( $ps_portfolio_query->have_posts() ) {
								$ps_portfolio_query->the_post();
								$ps_post_categories = get_the_category();
								$ps_post_cat_name = $ps_post_categories[0]->name;
								$image_id = get_post_thumbnail_id();
                            	$image_path = wp_get_attachment_image_src( $image_id, 'full', true );
						?>
								<li>
									<div class="single-project-wrap item">
										<?php if( has_post_thumbnail() ) { ?>
											<div class="project-img-wrap">
												<figure><?php the_post_thumbnail( 'parallaxsome_project_thumb' ); ?></figure>
												<span class="project-icons">
							                         <div class="btn-holder">   
							                            <a class="link-icon" href="<?php the_permalink();?>"><i class="fa fa-chain"></i></a>
							                            <a class="zoom-icon" href="<?php echo esc_url( $image_path[0] );?>" rel="projectPretty"><i class="fa fa-search-plus"></i></a>
							                        </div>
							                    </span>
											</div><!-- .project-img-wrap -->
										<?php } ?>
										
										<div class="project-info-wrap">
											<h3 class="project-title"><?php the_title(); ?></h3>
											<span class="post-cat-name"><?php echo esc_html( $ps_post_cat_name ); ?></span>
										</div><!-- .project-info-wrap -->
									</div><!-- .single-project-wrap -->
								</li>
						<?php
							}
							echo '</ul>';
						}
						wp_reset_postdata();
				?>
						</div><!-- .ps-protfolio-wrapper -->
				<?php
					}
				?>
			</div><!-- .section-content-wrapper -->
		</section>
<?php } ?>
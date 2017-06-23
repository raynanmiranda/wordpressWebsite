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
	$section_option = get_theme_mod( 'homepage_blog_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'blog_section_title', esc_html__( 'Our Blog', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'blog_section_sub_title', esc_html__( 'Latest News', 'parallaxsome' ) );
?>
		<section class="ps-home-section wow zoomIn" data-wow-duration="0.7s" id="section-blog">
			<div class="ps-section-container">
				<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description = null ); ?>
				<div class="section-content-wrapper">
					<div class="ps-blog-wrapper clearfix">
						<?php
							$ps_blog_categories = get_theme_mod( 'ps_blog_categories');							
							if( !empty( $ps_blog_categories ) ) {
								$ps_blog_categories = array_filter( $ps_blog_categories );
								$ps_blog_categories = implode( ',' , $ps_blog_categories );
								$ps_blog_args = array( 
												'category_name' => $ps_blog_categories,
												'posts_per_page' => 3
											);
								$ps_blog_btn_txt = get_theme_mod( 'blog_section_read_button', esc_html__( 'Read More', 'parallaxsome' ) );
								$ps_blog_query = new WP_Query( $ps_blog_args );
								if( $ps_blog_query->have_posts() ) {
									while( $ps_blog_query->have_posts() ) {
										$ps_blog_query->the_post();
						?>
										<div class="ps-single-blog">
											<?php if ( has_post_thumbnail() ) { ?>
												<div class="ps-blog-image">
												    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
												        <?php the_post_thumbnail( 'large' ); ?>
												    </a>
											    </div><!-- .ps-blog-image -->
											<?php } ?>
											<div class="ps-blog-info">
												<h3 class="ps-blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<div class="ps-blog-poston"><?php parallaxsome_home_posted_on(); ?></div>
												<div class="ps-blog-excerpt"><?php the_excerpt(); ?></div>
												<a href="<?php the_permalink(); ?>" class="button ps-more-button"><?php echo esc_html( $ps_blog_btn_txt ); ?></a>
											</div><!-- .ps-blog-info -->
										</div><!-- .ps-single-blog -->
						<?php
									}
								}
								wp_reset_postdata();
							}
						?>
					</div><!-- .ps-blog-wrapper -->
				</div><!-- .section-content-wrapper -->
				<?php 
					$blog_view_all_text = get_theme_mod( 'blog_section_view_all_txt', esc_html__( 'View All', 'parallaxsome' ) );
					$blog_view_all_link = get_theme_mod( 'blog_section_view_all_link', '' );
				?>
				<div class="ps-section-viewall">
					<a href="<?php echo esc_url( $blog_view_all_link ); ?>"><?php echo esc_html( $blog_view_all_text ); ?></a>
				</div>
			</div><!-- .ps-section-container -->
		</section>
<?php } ?>
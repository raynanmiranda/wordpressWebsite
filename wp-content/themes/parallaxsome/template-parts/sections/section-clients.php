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
	$section_option = get_theme_mod( 'homepage_clients_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'clients_section_title', esc_html__( 'Our Clients', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'clients_section_sub_title', esc_html__( 'Latest News', 'parallaxsome' ) );
		$section_bg_image = get_theme_mod( 'clients_bg_image', '' );
?>
		<section class="ps-home-section" id="section-clients" data-parallax="scroll" data-image-src="<?php echo esc_url( $section_bg_image ); ?>">			
			<div class="ps-section-container">
				<div class="client-title wow fadeInDown" data-wow-duration="0.5s">
					<?php parallaxsome_section_header( $section_title, $section_sub_title, $section_description = null ); ?>
				</div>
				<div class="section-content-wrapper wow fadeInUp clearfix" data-wow-duration="1s">
				<?php
					$ps_clients_cat_id = get_theme_mod( 'clients_cat_id', '0' );
					if( !empty( $ps_clients_cat_id ) ) {
						$ps_client_args = array(
											'cat' => $ps_clients_cat_id,
											'posts_per_page' => 8
											);
						$ps_client_query = new WP_Query( $ps_client_args );
						if( $ps_client_query->have_posts() ) {
							while( $ps_client_query->have_posts() ) {
								$ps_client_query->the_post();
								if( has_post_thumbnail() ) {
				?>
									<div class="ps-single-client clearfix">
										<figure><?php the_post_thumbnail( 'medium' ); ?></figure>
									</div><!-- .ps-single-client -->
				<?php
								}
							}
						}
						wp_reset_postdata();
					}
				?>
				</div><!-- .section-content-wrapper -->	
			</div>			
		</section>
<?php } ?>
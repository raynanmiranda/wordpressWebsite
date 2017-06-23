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
	$section_option = get_theme_mod( 'homepage_about_option', 'show' );
	if( $section_option != 'hide' ) {
		$section_title = get_theme_mod( 'about_section_title', esc_html__( 'About', 'parallaxsome' ) );
		$section_sub_title = get_theme_mod( 'about_section_sub_title', esc_html__( 'Who We Are', 'parallaxsome' ) );
		$section_image = get_theme_mod( 'about_section_image', '' );
?>
		<section class="ps-home-section" id="section-about">
			<div class="ps-section-container">
				<?php parallaxsome_section_header( $section_title, $section_sub_title, $description = null ); ?>
				<div class="section-content-wrapper">
					<?php
						$section_page_id = get_theme_mod( 'about_page_id', '0' );
						if( !empty( $section_page_id ) ) {
							$page_query = new WP_Query( 'page_id='.$section_page_id );
							if( $page_query->have_posts() ) {
								while( $page_query->have_posts() ) {
									$page_query->the_post();
									the_excerpt();
									if( !empty( $section_image ) ) {
					?>
										<div class="about-image wow fadeInUp" data-wow-duration="0.7s">
											<figure><img src="<?php echo esc_url( $section_image ); ?>" /></figure>
										</div>
					<?php
									}
								}
							}
							wp_reset_postdata();
						}
					?>
				</div><!-- .section-content-wrapper -->
			</div><!-- .ps-section-container -->
		</section>
<?php
	}
?>
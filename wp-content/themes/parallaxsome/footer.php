<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */
?>

		</div><!-- .ps-container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php get_sidebar( 'footer' ); ?>
		<div class="site-info">
			<div class="ps-section-container">
			<span class="copyright-text"><?php echo wp_kses_post( get_theme_mod( 'ps_copyright_text'));?></span>
			<span class="sep"> | </span>
			<?php 
				$theme_author_url = esc_url( 'https://accesspressthemes.com/' );
				printf( esc_html__( 'ParallaxSome by %1$s.', 'parallaxsome' ), '<a href="'.esc_url( 'https://accesspressthemes.com/' ).'" rel="designer">'.esc_html__('AccessPress Themes', 'parallaxsome').'</a>' ); ?>
			</div><!-- ps-section-container -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	<a href="#masthead" id="scroll-up"><i class="fa fa-angle-up"></i></a>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

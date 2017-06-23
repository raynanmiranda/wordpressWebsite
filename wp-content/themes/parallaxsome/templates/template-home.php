<?php
/**
 * Template Name: Home Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

get_header(); ?>

	<div id="home-primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php

				/**
				 * About Us section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'about' );

				/**
				 * Our Team section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'team' );

				/**
				 * Our Services section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'services' );

				/**
				 * Testimonials section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'testimonials' );

				/**
				 * Fact Counter section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'fact' );

				/**
				 * Portfolio section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'portfolio' );

				/**
				 * Blog section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'blog' );

				/**
				 * Clients section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'clients' );

				/**
				 * Contact Us section
				 *
				 * @since 1.0.0
				 */
				get_template_part( 'template-parts/sections/section', 'contact' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
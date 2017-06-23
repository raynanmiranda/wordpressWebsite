<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Unicon
 */

get_header(); ?>

<?php do_action('unicon-breadcrumb'); ?>

<div class="container-wrap clearfix">
	<div class="inner-container clearfix">
		<section id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
					if ( have_posts() ) : 
						
						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );

						endwhile;

						the_posts_pagination( 
		            		array(
							    'prev_text' => __( 'Prev', 'unicon' ),
							    'next_text' => __( 'Next', 'unicon' ),
							)
			            );

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; 
				?>
			</main><!-- #main -->
		</section><!-- #primary -->

		<?php if ( is_active_sidebar( 'unicon-leftsidebar' ) ) : ?>
			<aside id="secondaryright" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'unicon-leftsidebar' ); ?>
			</aside><!-- #secondary -->
		<?php endif; ?>
		
	</div>
</div>

<?php get_footer();
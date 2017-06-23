<?php
/**
 * Template Name: Blog Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicon
 */

get_header(); ?>

<?php do_action('unicon-breadcrumb'); ?>

<div class="container-wrap clearfix">
	<div class="inner-container unicon-blog clearfix">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
	                $blogs_cat = get_theme_mod('blog_categories');
	                $blog_posts = intval( get_theme_mod( 'unicon_display_number_blog_post', 10 ) );
	                $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	                $args = array(
	                	'post_type' => 'post',
	                	'tax_query' => array(
	                		array(
	                			'taxonomy' => 'category',
	                			'field'    => 'slug',
	                			'terms'    => $blogs_cat,
	                		),
	                	),
	                	'posts_per_page' => $blog_posts,
	                	'paged' => $paged,
	                );

	                $query = new WP_Query($args);

	                if ( $query->have_posts() ): while ($query->have_posts() ) : $query->the_post();
               	
						get_template_part( 'template-parts/content', get_post_format() );
					
					endwhile;

					if (function_exists("unicon_pagination")) :
					    unicon_pagination();
					endif;

				 	endif; wp_reset_query(); 
				?>
			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer();
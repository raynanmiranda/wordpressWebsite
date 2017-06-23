<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Revolve
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			
			<div class="all-articles-wrap clearfix">
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				$revolve_cats = get_the_category(get_the_id() );
				$revolve_chk_cat = array();

				foreach($revolve_cats as $revolve_cat){
					$revolve_chk_cat[] = $revolve_cat->term_id;
				}

				$revolve_port_cat = absint(get_theme_mod('revolve_port_category'));

				if(in_array($revolve_port_cat, $revolve_chk_cat)) {
					get_template_part( 'template-parts/arch', 'portfolio' );
				} else {
					get_template_part( 'template-parts/arch', 'blog' );
				}
			endwhile;

			?>
			</div> <!-- .all-articles-wrap -->
			<?php
			the_posts_navigation();
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
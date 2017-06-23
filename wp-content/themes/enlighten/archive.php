<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package enlighten
 */

get_header();

        ?>
<div  class="ak-container">
	<div id="primary" class="content-area right">
		<main id="main" class="site-main" role="main">
		<?php       
        //echo $cat_data;                
		if ( have_posts() ) : ?>
			<?php /*
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			*/ ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
			?>
				<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
			<?php
			endwhile;

			the_posts_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
    <div id="secondary" class="right_right">
        <?php
            get_sidebar();
        ?>
    </div>
</div>
<?php
get_footer();

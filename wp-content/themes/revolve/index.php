<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Revolve
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>

				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php else : ?>
				<?php do_action('revolve_feat_blog_post'); ?>
			<?php
			endif;
			/* Start the Loop */
			?>
			<div class="all-articles-wrap clearfix">
			<?php
			while ( have_posts() ) : the_post();
                ?>
                <article class="blog-post-wrap">
                	<div class="blg-inner-wrap">
	                    <?php if(has_post_thumbnail()) : ?>
		        		<?php
		        			$revolve_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'revolve-blog-grid-thumb' );
							$revolve_img_src = $revolve_img[0];
		        		?>
		        		<?php else : ?>
	        				<?php $revolve_img_src = get_template_directory_uri().'/images/no-blog-thumb.JPG'; ?>
		        		<?php endif; ?>

	                    <img src="<?php echo esc_url($revolve_img_src); ?>" class="blog-img-thumbnail">
	                    <div class="entry-summary">
	                    	<div class="pub-date"><?php echo get_the_date( get_option( 'date_format' ), get_the_ID() ); ?></div>
	                        <a href="<?php the_permalink(); ?>">
	                            <h2 class="blog-post-title"><?php the_title(); ?></h2>
	                        </a>
	                        <div class="blog-post-excerpt">
	                            <?php echo revolve_get_excerpt(get_the_content(), 200, '&#8594;'); ?>
	                        </div>
	                    </div>
                    </div>
                </article>
                <?php

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
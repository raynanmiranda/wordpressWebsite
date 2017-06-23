<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Unicon
 */

get_header(); ?>

<?php do_action('unicon-breadcrumb'); ?>

<div class="container-wrap clearfix">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'unicon' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'unicon' ); ?></p>							
				</div><!-- .page-content -->

				<div class="backtohome">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" type="button" class="btn-home">
						<span><?php _e('Back To Home','unicon'); ?></span>
					</a>
				</div><!-- .page-content -->

			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer();
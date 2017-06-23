<?php
/**
 * The template for displaying all pages.
 * Template Name: Portfolio
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Revolve
 */

get_header(); ?>

    <div class="revole-page-header clearfix">

        <?php while ( have_posts() ) : the_post(); ?>

		<?php $revolve_parid = get_the_ID(); ?>

        <h1 class="revolve-page-header-left">
           <span> <?php the_title(); ?></span>
        </h1>

        <div class="revolve-page-content-right">
            <?php the_content(); ?>
        </div>

        <?php endwhile; ?>
            
    </div>
    
    <?php
        $revolve_port_cat = absint(get_theme_mod( 'revolve_port_category'));
        $revolve_args = array(
        	'post_status' => 'publish',
            'posts_per_page' => -1,
            'cat' => $revolve_port_cat
        );
        
        $revolve_query = new WP_Query($revolve_args);
    ?>
    
    <!-- Portfolio Posts -->
    <?php if($revolve_query->have_posts()) : ?>
        <div class="portfolio-post-container">
            <?php while($revolve_query->have_posts()) : $revolve_query->the_post(); ?>
                <div class="portfolio-post-wrap">
                    <?php if(has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail( 'revolve-port-grid-thumb' ); ?>
                        <?php $revolve_full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); $revolve_img_src = $revolve_full_img[0]; ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri().'/images/no-port-thumb.JPG'); ?>" />
                        <?php $revolve_img_src = get_template_directory_uri().'/images/no-port-thumb.JPG'; ?>
                    <?php endif; ?>
                    <div class="port-outer-wrap">
                        <a href="<?php the_permalink(); ?>">
                        <span class="port-post-title"><?php the_title(); ?></span>
                        </a>
                        <div class="port-link-wrap">
                            <p>
                                <span class="port-link">
                                    <a href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
                                </span>
                                <span class="port-link">
                                    <a href="<?php echo esc_url($revolve_img_src); ?>" class="port-viewbox"><i class="fa fa-search"></i></a>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>        
    <?php endif; ?>

<?php get_footer(); ?>
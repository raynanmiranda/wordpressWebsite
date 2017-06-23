<?php
/**
 * The template for displaying all pages.
 * Template Name: Scroll Page
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
            <span><?php the_title(); ?></span>
        </h1>

        <div class="revolve-page-content-right">
            <?php the_content(); ?>
        </div>

        <?php endwhile; ?>
            
    </div>
    
    <?php
        $revolve_args = array(
        	'post_parent' => $revolve_parid,
        	'post_type' => 'page',
            'order' => 'ASC',
            'orderby' => 'menu_order',
        );
        
        $revolve_query = new WP_Query($revolve_args);
    ?>
    
    <!-- Subpages Contents -->
    <?php if($revolve_query->have_posts()) : ?>
    
        <?php while($revolve_query->have_posts()) : $revolve_query->the_post(); ?>
            <?php $revolve_sec_id = $post->post_name; ?>
            <div class="sub-block-wrap" id="<?php echo esc_attr($revolve_sec_id); ?>" >
                <?php if(has_post_thumbnail()) : ?>
                    <?php $revolve_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
                    
                    <div class="sub-block-header">
                        <img src="<?php echo esc_url($revolve_img[0]); ?>" />
                        <h2 class="sub-block-title"><?php the_title(); ?></h2>
                    </div>
                    
                <?php endif; ?>
                
                <div class="sub-block-content">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endwhile; ?>
        
    <?php endif; ?>

<?php get_footer(); ?>
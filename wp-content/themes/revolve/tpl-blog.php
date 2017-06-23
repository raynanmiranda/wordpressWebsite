<?php
/**
 * The template for displaying all pages.
 * Template Name: Blog
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
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <!-- Featured Blog Pot -->
            <?php do_action('revolve_feat_blog_post'); ?>
            
            <!-- Blogs Part -->
            <?php
                $revolve_blog_exclude_cat = sanitize_text_field(get_theme_mod('blog_exclude_cat'));
                $revolve_blog_arr = explode(',', $revolve_blog_exclude_cat);
                $revolve_posts_per_page = get_option('posts_per_page', 10);
                $revolve_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
                
                $revolve_bargs = array(
                    'post_type' => 'post',
                    'posts_per_page' => $revolve_posts_per_page,
                    'paged' => $revolve_paged,
                    'post_status' => 'publish',
                    'category__not_in' => $revolve_blog_arr,
                );
                $revolve_bquery = new WP_Query($revolve_bargs);
            ?>
            <?php if($revolve_bquery->have_posts()) : ?>
                <div class="page-blog-container">
                    <?php while($revolve_bquery->have_posts()) : $revolve_bquery->the_post(); ?>
                        <article class="blog-post-wrap">
                            <?php if(has_post_thumbnail()) : ?>
                                <div class="image-wrap">
                                    <?php the_post_thumbnail( 'revolve-blog-grid-thumb', array('class' => 'blog-img-thumbnail') ); ?>
                                </div>
                            <?php else : ?>
                                <?php $revolve_img_src = get_template_directory_uri().'/images/no-blog-thumb.JPG'; ?>
                                <img src="<?php echo esc_url($revolve_img_src); ?>" class="blog-img-thumbnail">
                            <?php endif; ?>
                            <div class="entry-summary">
                                <div class="pub-date"><?php echo get_the_date( get_option( 'date_format' ), get_the_ID() ); ?></div>
                                <a href="<?php the_permalink(); ?>">
                                    <h2 class="blog-post-title"><?php the_title(); ?></h2>
                                </a>
                                <div class="blog-post-excerpt">
                                    <?php echo revolve_get_excerpt(get_the_content(), 200, '&#8594;'); ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php if ($revolve_bquery->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
                  <nav class="prev-next-posts">
                    <div class="prev-posts-link">
                      <?php echo get_next_posts_link( __('Older Entries', 'revolve'), $revolve_bquery->max_num_pages ); // display older posts link ?>
                    </div>
                    <div class="next-posts-link">
                      <?php echo get_previous_posts_link( __('Newer Entries', 'revolve') ); // display newer posts link ?>
                    </div>
                  </nav>
                <?php } ?>
            <?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
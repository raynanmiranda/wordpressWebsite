<?php
/**
 * The template for displaying all pages.
 * Template Name: Home
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
    
    <?php
        $revolve_args = array(
        	'post_parent' => 0,
        	'post_type' => 'page',
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'meta_query' => array(
               array(
                   'key' => 'hm_poster_display',
                   'value' => 1,
                   'compare' => '=',
               )
           )            
        );
        $revolve_query = new WP_Query($revolve_args);
    ?>
    
    <?php $revolve_hsliders = revolve_get_home_slider(); ?>
    
    <?php if(!empty($revolve_hsliders)) : ?>
        <div id="revolve-home-slider">
            <?php
                /** Home Slide **/
                $revolve_hslide = get_theme_mod( 'revolve_hslide', '' );
                $revolve_hslide_caption_title = get_theme_mod( 'revolve_hslide_caption_title', '' );
                $revolve_hslide_caption_content = get_theme_mod( 'revolve_hslide_caption_content', '' );
                $revolve_pst_id = attachment_url_to_postid($revolve_hslide);
                $revolve_image = wp_get_attachment_image_src($revolve_pst_id, 'full');
                $revolve_img_src = $revolve_image[0];
            ?>
            <?php if($revolve_hslide != '' || $revolve_pst_id != 0) : ?>
            <section class="revolve-sliders" data-item="page-item-0" style="background-image: url('<?php echo esc_url($revolve_img_src); ?>');">
                <div class="revolve-sliders-captions">
                    <div class="revolve-bar"></div>
                    <div class="revolve-cap-text">
                        <?php if($revolve_hslide_caption_title != '') : ?>
                            <div class="revolve-caption-title"><?php echo esc_html($revolve_hslide_caption_title); ?></div>
                        <?php endif; ?>
                        
                        <?php if($revolve_hslide_caption_content != '') : ?>
                            <div class="revolve-caption-description"><?php echo wp_kses_post($revolve_hslide_caption_content); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="revolve-move-down"></div>
            </section>
            <?php endif; ?>
            
            <?php foreach($revolve_hsliders as $revolve_hslider) : ?>
                <?php
                    $revolve_post_id = $revolve_hslider[0];
                    $revolve_cap_title = $revolve_hslider[1];
                    $revolve_cap_content = $revolve_hslider[2];
                    $revolve_sl_id = $revolve_hslider[3];
                    $revolve_sl_widget = $revolve_hslider[4];
                ?>
                <?php if(has_post_thumbnail( $revolve_post_id )) : ?>
                    <?php $revolve_feat_img = wp_get_attachment_image_src(get_post_thumbnail_id($revolve_post_id), 'full'); ?>
                    <section id="<?php echo $revolve_sl_id; ?>" class="revolve-sliders" data-item="page-item-<?php echo $revolve_post_id; ?>" style="background-image: url('<?php echo esc_url($revolve_feat_img[0]); ?>');">
                        <div class="revolve-sliders-captions">
                            <div class="revolve-bar"></div>
                            <div class="revolve-cap-text">
                                <a href="<?php echo esc_url(get_page_link($revolve_post_id)); ?>">
                                    <?php if($revolve_cap_title != '') : ?>
                                        <div class="revolve-caption-title"><?php echo esc_html($revolve_cap_title); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if($revolve_cap_content != '') : ?>
                                        <div class="revolve-caption-description"><?php echo wp_kses_post($revolve_cap_content); ?></div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                        <?php if($revolve_sl_widget && is_active_sidebar($revolve_sl_widget)) : ?>
                            <div class="revolve-slider-widget">
                                <?php dynamic_sidebar(esc_attr($revolve_sl_widget)); ?>
                            </div>
                        <?php endif; ?>
                        <div class="revolve-move-down"></div>
                    </section>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php
get_footer();
<?php
/**
 * Displaying Portfolio Post Contents
 *
 *
 * @package Revolve
 */

?>
<div class="portfolio-post-wrap">
    <?php if(has_post_thumbnail()) : ?>
        <?php the_post_thumbnail( 'revolve-port-grid-thumb' ); ?>
        <?php
            $revolve_full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
            $revolve_img_src = $revolve_full_img[0];
        ?>
    <?php else : ?>
        <img src="<?php echo get_template_directory_uri().'/images/no-port-thumb.JPG'; ?>" />
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
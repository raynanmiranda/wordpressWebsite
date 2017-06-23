<?php
	/**
	 * Displaying Blog Post Contents
	 *
	 *
	 * @package Revolve
	 */
?>
	<article class="blog-post-wrap">
		<div class="blg-inner-wrap">
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
	    </div>
	</article>
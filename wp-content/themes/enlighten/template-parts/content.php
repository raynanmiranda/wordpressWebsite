<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package enlighten
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(has_post_thumbnail()) : ?>
		<?php $enlighten_img = wp_get_attachment_image_src(get_post_thumbnail_id(),'single_page'); 
        $enlighten_img_src = $enlighten_img[0]; ?>
		<div class="post-image-wrap">
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo esc_url($enlighten_img_src); ?>" />
			</a>
		</div>
	<?php endif; ?>
	<div class="post-meta">
	<?php
		if ( is_single() ) {
		?> <a href="<?php the_permalink() ?>"><h1 class="entry-title"><?php the_title(); ?></h1></a> <?php
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
	    
		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta clearfix">
			<div class="post-date">
			<?php enlighten_posted_on(); ?>
			</div>
			<div class="post-comment"><a href="<?php comments_link(); ?>"><i class="fa fa-comment-o"></i><?php comments_number(0); ?></a></div>
		</div><!-- .entry-meta -->
		<div class="entry-content">
			<?php echo apply_filters('the_content' , wp_kses_post(wp_trim_words(get_the_content(),80,'...')));?>
			<a href="<?php the_permalink(); ?>"><?php _e('Continue Reading', 'enlighten'); ?></a>
			<?php /*
			<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. *
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'enlighten' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'enlighten' ),
					'after'  => '</div>',
				) );
			?> */ ?>
		</div><!-- .entry-content -->
	<?php endif; ?>
	</div>
</article><!-- #post-## -->

<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicon
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<div class="comment-content wow bounceInUp animated">
		    <div class="comments-wrapper">
		      <h3><?php _e( 'Comments','unicon' ); ?> </h3>
		      <ul class="commentlist">
			      <?php
			      	wp_list_comments('type=comment&callback=unicon_comment');
			      ?>
			  </ul>
		    </div>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'unicon' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'unicon' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'unicon' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>		

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'unicon' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'unicon' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'unicon' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'unicon' ); ?></p>
	<?php
	endif;

		$args = array(
			'fields' => apply_filters(        
			'comment_form_default_fields', array(
			'author' =>'<div class="cmm-box-left"><div class="control-group"><div class="controls">'. '<input id="author" placeholder="'.__( 'Name *', 'unicon' ).'" name="author" type="text" value="' .
			esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />'.
			'</div></div>',

			'email'  => '<div class="control-group"><div class="controls">' . '<input id="email" placeholder="'.__( 'Email Address *', 'unicon' ).'" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" size="30" aria-required="true" />'  .
			'</div></div>'
			)
		),

			'comment_field' => '<div class="cmm-box-right"><div class="control-group"><div class="controls">' .
			'<textarea id="comment" name="comment" placeholder="'.__( 'Comment *', 'unicon' ).'" cols="45" rows="8" aria-required="true"></textarea>' .
			'</div></div></div>',
			'comment_notes_after' => '',
			'label_submit' =>__( 'ADD COMMENT', 'unicon' ),
			'comment_notes_before' => '',
		);
		       
		comment_form($args);
	?>

</div><!-- #comments -->
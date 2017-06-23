<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Revolve
 */

?>

<?php wp_footer(); ?>
<?php if(!is_page_template('tpl-home.php' )) : ?>
	<div class="site-info">
	    &copy; <?php echo date('Y')." "; bloginfo('name'); ?> | <?php _e('Revolve by','revolve'); ?> <a href="<?php echo esc_url('http://accesspressthemes.com/'); ?>" title="AccessPress Themes" target="_blank">AccessPress Themes</a>
	</div><!-- .site-info -->
</div> <!-- #revolve-page-content -->
<?php endif; ?>
</body>
</html>
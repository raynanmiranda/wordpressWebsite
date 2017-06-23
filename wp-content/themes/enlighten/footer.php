<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package enlighten
 */

?>

	</div><!-- #content -->
<?php 
$enlighten_footer_style = get_theme_mod('enlighten_menu_style');
?>
	<footer id="colophon" class="site-footer <?php if($enlighten_footer_style == 'bottom'){echo 'bottom_menu';} ?>" role="contentinfo">
        <div class="ak-container">
        <?php 
            if(is_active_sidebar('enlighten_footer-1') || is_active_sidebar('enlighten_footer-2') || is_active_sidebar('enlighten_footer-3')){
        ?>
        <div class="footer_area clearfix">
            <?php
                if(is_active_sidebar('enlighten_footer-1')){
             ?>
                <div class="footer_area_one">
                    <?php
                        dynamic_sidebar('enlighten_footer-1');
                    ?>
                </div>
            <?php } ?>
            <?php
                if(is_active_sidebar('enlighten_footer-2')){ 
            ?>
                <div class="footer_area_two">
                    <?php
                        dynamic_sidebar('enlighten_footer-2');
                    ?>
                </div>
            <?php } ?>
            <?php
                if(is_active_sidebar('enlighten_footer-3')){
            ?>
                <div class="footer_area_three">
                    <?php
                        dynamic_sidebar('enlighten_footer-3');
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="site-info">
            <?php 
                $enlighten_footer_text = get_theme_mod('enlighten_footer_text');
            ?>
            <span class="footer_text"><?php if($enlighten_footer_text){echo esc_attr($enlighten_footer_text);} ?> <span class="sep"> | </span> <?php _e('WordPress Theme','enlighten');?> : <a target="_blank" href="<?php echo esc_url('http://demo.accesspressthemes.com/enlighten');?>"><?php _e('Enlighten','enlighten'); ?></a> <?php _e('by','enlighten');?> <a target="_blank" href="<?php echo esc_url('http://accesspressthemes.com/');?>"><?php _e('AccessPress Themes','enlighten');?></a></span>
    	</div><!-- .site-info -->
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Unicon
 */

?>
	</div><!-- #content -->

		<?php
		
			do_action( 'unicon_footer_before' ); 

				/**
				 * @hooked unicon_footer_widgets - 20
				 * @hooked unicon_buttom_footer - 30
				*/
				do_action( 'unicon_footer' ); 
			
			 do_action( 'unicon_footer_after' ); 

		?>

	<a href="#" class="scrollup"><i class="fa fa-angle-double-up" aria-hidden="true"></i> </a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

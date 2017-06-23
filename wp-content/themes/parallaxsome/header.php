<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'parallaxsome_before' ); ?>
<div id="page" class="site">
	<?php do_action( 'parallaxsome_before_header' ); ?>
	<div class="ps-whole-header">
		<?php 
			$parallaxsome_top_header_option = get_theme_mod( 'top_header_option', 'show' );
			if( $parallaxsome_top_header_option != 'hide' ) {
		?>
			<div class="ps-top-header-wrapper">
				<div class="ps-container clearfix">
					<nav id="top-site-navigation" class="top-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'parallaxsome_top_menu', 'menu_id' => 'top-menu', 'fallback_cb' => false  ) ); ?>
					</nav><!-- #site-navigation -->
					<?php do_action( 'parallaxsome_top_social_icons' ); ?>
				</div><!-- .ps-container -->
			</div><!-- .ps-top-header-wrapper -->
		<?php } ?>
		<header id="masthead" class="site-header" role="banner">
			<div class="ps-container">
				<div class="ps-header-wrapper clearfix">
					<div class="site-branding">
						<?php 
                            if(get_theme_mod('custom_logo')){
    							if ( function_exists( 'the_custom_logo' ) ) {
    								the_custom_logo();
    							}
                            }
                            else{
    						?>
        						<div class="site-title-wrapper">
        							<?php
        								if ( is_front_page() && is_home() ) : ?>
        									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        							<?php else : ?>
        									<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
        							<?php
        								endif;
        
        								$description = get_bloginfo( 'description', 'display' );
        								if ( $description || is_customize_preview() ) : ?>
        									<p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
        							<?php
        								endif; 
        							?>
        						</div><!-- .site-title-wrapper -->
                            <?php
                            }
                        ?>
					</div><!-- .site-branding -->

					<?php do_action( 'parallaxsome_main_menu' ); ?>
				</div><!-- .ps-header-wrapper-- >
			</div><!-- .ps-container -->
		</header><!-- #masthead -->
		
	</div><!-- .ps-whole-header -->
	<?php 
		if( is_front_page() ) {
			do_action( 'parallaxsome_homepage_slider' );	
		} else {
			do_action( 'parallaxsome_innerpage_header' );
		}
	?>

	<div id="content" class="site-content">
		<div class="ps-container">
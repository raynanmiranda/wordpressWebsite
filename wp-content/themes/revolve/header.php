<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Revolve
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
        <div id="menu-toggle"><i class="fa fa-bars"></i></div>
        <div id="revolve-side-nav" class="nano">
        	<header id="revolve-masthead" class="revolve-site-header" role="banner">
                <?php
                    $revolve_header_logo = '';
                    $revolve_header_display = esc_attr(get_theme_mod('revolve_header_display', 'text_only'));
                    $revolve_slg_class = ($revolve_header_display == 'both') ? 'both' : 'one';
                ?>
        		<div id="revolve-site-branding" class="<?php echo $revolve_slg_class; ?>">
                    <?php if($revolve_header_display == 'text_only') : ?>
                        <div class="revolve-site-tagline">
            				<p class="revolve-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                			<?php            
                			$revolve_description = get_bloginfo( 'description', 'display' );
                			if ( $revolve_description || is_customize_preview() ) : ?>
                				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><p class="revolve-site-description"><?php echo esc_textarea($revolve_description); ?></p></a>
                			<?php
                			endif; ?>
                        </div>
                    <?php elseif($revolve_header_display == 'logo_only') : ?>
                        <?php if(has_custom_logo()) : ?>
                            <div class="revolve-site-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if(has_custom_logo()) : ?>
                            <div class="revolve-site-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="revolve-site-tagline">
            				<p class="revolve-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                			<?php
                
                			$revolve_description = get_bloginfo( 'description', 'display' );
                			if ( $revolve_description || is_customize_preview() ) : ?>
                				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><p class="revolve-site-description"><?php echo esc_textarea($revolve_description); ?></p></a>
                			<?php
                			endif; ?>
                        </div>
                    <?php endif; ?>
        		</div><!-- .site-branding -->
            </header><!-- #masthead -->

        	<nav id="site-navigation" class="main-navigation" role="navigation">
                <?php if(has_nav_menu('primary')) : ?>
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'walker' => new Revolve_Walker_Nav_Menu() ) ); ?>
                <?php else : ?>
                    <ul class="menu">

                    <li><a href="<?php echo home_url(); ?>/">Home</a></li>
                    <?php wp_list_pages('title_li=' ); ?>

                    </ul><!-- end .menu -->
                <?php endif; ?>
        	</nav><!-- #site-navigation -->

            <?php
                /** Social Icons **/
                $revolve_social_fb = get_theme_mod('revolve_social_fb', '');
                $revolve_social_tw = get_theme_mod('revolve_social_tw', '');
                $revolve_social_gplus = get_theme_mod('revolve_social_gplus', '');
                $revolve_social_lnk = get_theme_mod('revolve_social_lnk', '');
                $revolve_social_ytube = get_theme_mod('revolve_social_ytube', '');
            ?>
            <?php if($revolve_social_fb != '' || $revolve_social_tw != '' || $revolve_social_gplus != '' || $revolve_social_lnk != '' || $revolve_social_ytube != '') : ?>
                <div class="header-social-icons-container">
                    <div class="rvl-header-social-icons">
                        <ul class="social-icons">
                            <?php if($revolve_social_fb != '') : ?>
                            <li class="c-circle-nav__item">
                                <a href="<?php echo esc_url($revolve_social_fb); ?>" class="c-circle-nav__link">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($revolve_social_tw != '') : ?>
                            <li class="c-circle-nav__item">
                                <a href="<?php echo esc_url($revolve_social_tw); ?>" class="c-circle-nav__link">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($revolve_social_gplus != '') : ?>
                            <li class="c-circle-nav__item">
                                <a href="<?php echo esc_url($revolve_social_gplus); ?>" class="c-circle-nav__link">
                                    <i class="fa fa-google"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($revolve_social_lnk) : ?>
                            <li class="c-circle-nav__item">
                                <a href="<?php echo esc_url($revolve_social_lnk); ?>" class="c-circle-nav__link">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($revolve_social_ytube != '') : ?>
                            <li class="c-circle-nav__item">
                                <a href="<?php echo esc_url($revolve_social_ytube); ?>" class="c-circle-nav__link">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="social-toggle-wrap">
                        <a class="social-toggle" href="#"><i class="fa fa-share-alt"></i></a>
                    </div>
                </div>
            <?php endif; ?>
    	</div> <!-- revolve-side-nav -->
        <?php if(!is_page_template('tpl-home.php' )) : ?>
        <div id="revolve-page-content">
        <?php endif; ?>
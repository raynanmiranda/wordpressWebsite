<?php
/**
 * Header Section Function Area
*/

if ( ! function_exists( 'unicon_skip_links' ) ) {
	/**
	 * Skip links
	 * @since  1.0.0
	 * @return void
	*/
	function unicon_skip_links() {
		?>
			<a class="skip-link screen-reader-text" href="#site-navigation"><?php _e( 'Skip to navigation', 'unicon' ); ?></a>
			<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'unicon' ); ?></a>
		<?php
	}
}
add_action( 'unicon_header_before', 'unicon_skip_links', 5 );


/**
 * Header Before Function Area
**/
if ( ! function_exists( 'unicon_header_before' ) ) { 

	function unicon_header_before() { ?>
		<header id="masthead" class="site-header <?php if( ( is_front_page() ) ){ echo 'kr-homepage'; }else{ echo 'kr-innerheader'; } ?> " <?php if( is_front_page() ) { if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . ');"'; } } ?>>
			<?php if( is_front_page() ) { ?><div class="kr-headerwrap"> <?php } ?>
		<?php
	}
}
add_action( 'unicon_header_before', 'unicon_header_before', 10 );


/**
 * Top Header Function Area
**/
if ( ! function_exists( 'unicon_top_header' ) ) {
	function unicon_top_header() {
		$top_header = esc_attr( get_theme_mod('unicon-top-header-settings-option','show') );
		$about_toggle = intval( get_theme_mod( 'unicon_top_header_toggle', 1 ));
		if( !empty( $top_header ) && $top_header == 'show' ) { ?>			
			<div class="topheader clearfix">
				<div class="container-wrap">
					<div class="quickinfo">
						<?php apply_filters( 'unicon_quick_contact_info_top_header', 5 ); ?>
					</div>
					<div class="right-header-wrap">
					
					<?php if(!empty( $about_toggle ) && $about_toggle == 1) { ?>
						<div class="abouttoggle">
							<span class="first-line"></span>
							<span class="second-line"></span>
							<span class="third-line"></span>
						</div>
						<div class="abouttoggle-content">
						</div>
					<?php } ?>
					<div class="socialicon">
						<?php apply_filters( 'unicon_social_icon_top_header', 10 ); ?>
					</div>
					</div>
				</div>
			</div>				
			<?php
		}
	}
}
add_action( 'unicon_main_header', 'unicon_top_header', 15 );


/**
 * Main Header Function Area
**/
if ( ! function_exists( 'unicon_main_header' ) ) {
	function unicon_main_header() { ?>
		<div class="container-wrap">
			<div class="mainheader clearfix">
				<div class="site-branding">
					<div class="logo">
						<?php
							if ( function_exists( 'the_custom_logo' ) ) {
								the_custom_logo();
							}
						?>
					</div>
					<div class="logo-textwrap">
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<?php 
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) { ?>
								<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php } ?>
					</div>
				</div><!-- .site-branding -->
				<div class="kr-navmenu">
				<div class="kr-toggle">
					<div class="one"></div>
					<div class="two"></div>
					<div class="three"></div>
				</div>
				<div class="menulink">				
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>

				</div>
				<div class="mask"></div>
				</div>
			</div>
		</div>			
		<?php
	}
}
add_action( 'unicon_main_header', 'unicon_main_header', 20 );


/**
 * Main Header Banner Content Function Area
**/
if ( ! function_exists( 'unicon_main_header_content' ) ) {
	function unicon_main_header_content() { 
			$unicon_main_title = esc_attr( get_theme_mod( 'unicon_main_title', 'SOLUTION FOR YOUR BUSINCESS' ) );
			$unicon_main_description = wp_filter_nohtml_kses( get_theme_mod( 'unicon_main_description', 'Better security happens when we work together, Get tips on further steps you can take to protect yourself online. better security happens when we work together happens' ) );

			$unicon_first_button_title = esc_attr( get_theme_mod( 'unicon_first_button_title', 'Read More' ) );
			$unicon_first_button_url = esc_url( get_theme_mod( 'unicon_first_button_url', esc_url( home_url( '/' ) ).'#focus' ) );

			$unicon_second_button_title = esc_attr( get_theme_mod( 'unicon_second_button_title', 'Purchase Now' ) );
			$unicon_second_button_url = esc_url( get_theme_mod( 'unicon_second_button_url', esc_url( home_url( '/' ) ).'#focus' ) );
		if( is_front_page() ) { ?>
			<div class="mainbanner-content">
				<div class="mainbanner-wrap">
					<?php if(!empty( $unicon_main_title )) { ?>
						<h1 class="wow slideInDown"><?php echo $unicon_main_title; ?></h1>
					<?php } ?>
					<?php if(!empty( $unicon_main_description )) { ?>
						<div class="main-content wow slideInDown"><?php echo $unicon_main_description; ?></div>
					<?php } ?>
				</div>
				<div class="mainbanner-button-wrap">
					<?php if( !empty( $unicon_first_button_title ) ) { ?>
						<div class="first-button kr-styleone wow fadeInLeft">
							<a href="<?php echo $unicon_first_button_url; ?>">
								<?php echo $unicon_first_button_title; ?>
							</a>
						</div>
					<?php } ?>
					<?php if( !empty( $unicon_second_button_title ) ) { ?>
						<div class="first-button wow fadeInRight">
							<a href="<?php echo $unicon_second_button_url; ?>">
								<?php echo $unicon_second_button_title; ?>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>		
		<?php }
	}
}
add_action( 'unicon_main_header', 'unicon_main_header_content', 20 );

/**
 * Header After Function Area
**/
if ( ! function_exists( 'unicon_header_after' ) ) { 
	
	function unicon_header_after() { ?>
			<?php if( is_front_page() ) { ?></div><?php } ?>
		</header>
		<?php
	}
}
add_action( 'unicon_header_after', 'unicon_header_after', 30 );
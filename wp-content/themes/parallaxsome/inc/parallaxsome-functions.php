<?php
/**
 * ParallaxSome custom functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Define variable for theme version
 *
 * @since 1.0.0
 */
	$parallaxsome_theme_details = wp_get_theme();
	$parallaxsome_theme_version = $parallaxsome_theme_details->Version;
	

/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function parallaxsome_scripts() {
	global $parallaxsome_theme_version;
	
	$parallaxsome_font_args = array(
        'family' => 'Open+Sans:400,600,700,800,300|PT+Sans:400,700|Lato:400,700,300|BenchNine:300|Roboto+Slab:300|Source+Sans+Pro:400,300,600,700|Raleway:400,500,600,700,800,300',
    );
    wp_enqueue_style( 'parallaxsome-google-fonts', add_query_arg( $parallaxsome_font_args, "//fonts.googleapis.com/css" ) );

	wp_enqueue_style( 'lightslider-style', get_template_directory_uri() . '/assets/library/lightslider/css/lightslider.css', array(), '1.1.3' );
	wp_enqueue_style( 'bxSlider-style', get_template_directory_uri() . '/assets/library/bxSlider/css/jquery.bxslider.css', array(), '4.1.2' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.6.3' );
	wp_enqueue_style( 'jquery-prettyPhoto-style', get_template_directory_uri() . '/assets/library/prettyphoto/css/prettyPhoto.css', array(), '3.1.6' );
	wp_enqueue_style ( 'animate', get_template_directory_uri() . '/assets/css/animate.css', array(), '3.5.1' );
	wp_enqueue_style( 'parallaxsome-style', get_stylesheet_uri(), array(), $parallaxsome_theme_version );

	wp_enqueue_script( 'lightslider', get_template_directory_uri() . '/assets/library/lightslider/js/lightslider.min.js', array( 'jquery' ), '1.1.3', true );
	wp_enqueue_script( 'jquery-bxslider', get_template_directory_uri() . '/assets/library/bxSlider/js/jquery.bxslider.min.js', array( 'jquery' ), '4.1.2', true );
	wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/assets/library/counterup/js/jquery.counterup.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jquery-waypoints', get_template_directory_uri() . '/assets/library/waypoints/js/jquery.waypoints.min.js', array( 'jquery' ), '2.0.5', true );
	wp_enqueue_script( 'jquery-nav', get_template_directory_uri() . '/assets/library/jquery-nav/js/jquery.nav.js', array( 'jquery' ), '2.2.0', true );
	wp_enqueue_script( 'jquery-scrollTo', get_template_directory_uri() . '/assets/library/jquery-scrollTo/js/jquery.scrollTo.js', array( 'jquery' ), '2.1.1', true );
	wp_enqueue_script( 'jquery-prettyPhoto', get_template_directory_uri() . '/assets/library/prettyphoto/js/jquery.prettyPhoto.js', array( 'jquery' ), '3.1.6', true );
	wp_enqueue_script( 'parallax', get_template_directory_uri() . '/assets/library/parallax-js/js/parallax.min.js', array( 'jquery' ), '1.4.2', true );
	wp_enqueue_script( 'wow', get_template_directory_uri() . '/assets/js/wow.min.js', array( 'jquery' ), '1.1.2', true );
	wp_enqueue_script( 'parallaxsome-custom-scripts', get_template_directory_uri() . '/assets/js/custom-scripts.js', array( 'jquery' ), $parallaxsome_theme_version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$ps_header_sticky_option = get_theme_mod( 'sticky_header_option', 'enable' );
	if( $ps_header_sticky_option != 'disable' ) {
		wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/assets/library/jquery-sticky/js/jquery.sticky.js', array( 'jquery' ), '1.0.2', true );
		wp_enqueue_script( 'parallaxsome-sticky-setting', get_template_directory_uri() . '/assets/library/jquery-sticky/js/sticky-setting.js', array( 'jquery-sticky' ), '1.0.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'parallaxsome_scripts' );

/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts/styles for admin area
 *
 * @since 1.0.0
 */
if( ! function_exists( 'parallaxsome_admin_scripts' ) ):
	function parallaxsome_admin_scripts() {
		wp_enqueue_style( 'parallaxsome-admin-style', get_template_directory_uri() . '/assets/css/admin-styles.css' );

		wp_enqueue_script( 'jquery-ui-button' );

		wp_enqueue_script( 'parallaxsome-admin-scripts', get_template_directory_uri() . '/assets/js/admin-scripts.js', array( 'jquery', 'jquery-ui-button' ), true );
	}
endif;

add_action( 'admin_enqueue_scripts', 'parallaxsome_admin_scripts' );

/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Create a global variable for single page menu
 *
 * @return array
 * @since 1.0.0
 */
$parallaxsome_single_menu_fields = array(
		'slider' =>  array( 
						'default'=> esc_html__( 'Main', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Top Section', 'parallaxsome' ) 
					),
		'about' =>  array( 
						'default'=> esc_html__( 'About', 'parallaxsome' ), 
						'label'=>  esc_html__( 'About Us', 'parallaxsome' ) 
					),
		'team' =>  array( 
						'default'=> esc_html( '', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Our Team', 'parallaxsome' ) 
					),
		'services' =>  array( 
						'default'=> esc_html__( 'Services', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Our Services', 'parallaxsome' ) 
					),
		'testimonials' =>  array( 
						'default'=> esc_html( '', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Client Says', 'parallaxsome' ) 
					),
		'fact' =>  array( 
						'default'=> esc_html( '', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Fact Us', 'parallaxsome' ) 
					),
		'portfolio' =>  array( 
						'default'=> esc_html__( 'Portfolio', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Portfolio', 'parallaxsome' ) 
					),
		'blog' =>  array( 
						'default'=> esc_html__( 'Blog', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Our Blog', 'parallaxsome' ) 
					),
		'clients' =>  array( 
						'default'=> esc_html( '', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Our Clients', 'parallaxsome' ) 
					),
		'contact' =>  array( 
						'default'=> esc_html__( 'Contact', 'parallaxsome' ), 
						'label'=>  esc_html__( 'Contact Us', 'parallaxsome' ) 
					)
	);

/*------------------------------------------------------------------------------------------------------------------*/

if( ! function_exists( 'parallaxsome_parallax_menu_cb' ) ):

	/**
	 * Define the Parallax Menu Tab
	 *
	 * @param string
	 * @return HTML
	 * @since 1.0.0
	 */

	function parallaxsome_parallax_menu_cb() {
		global $parallaxsome_single_menu_fields;
		$parallax_menu_type = get_theme_mod( 'parallax_menu_type', 'default' );
		foreach ( $parallaxsome_single_menu_fields as $section_id => $section_value ) {
			$parallaxsome_menu_mod_id = $section_id.'_menu_title';
			$parallaxsome_menu_mod_default = $section_value['default'];
			$parallaxsome_menu_title = get_theme_mod( $parallaxsome_menu_mod_id, $parallaxsome_menu_mod_default );
			if( !empty( $parallaxsome_menu_title ) ) {
				$parallaxsome_menu_tab = '';
                $parallaxsome_menu_tab .= '<li class="ps-menu-tab">';
                if( $parallax_menu_type == 'float' ) {
                	$parallaxsome_menu_tab .= '<a href="'. esc_url( home_url() ) .'/#section-'.$section_id.'"><span></span></a>';
                	$parallaxsome_menu_tab .= '<div class="px-tooltip">'. esc_attr( $parallaxsome_menu_title ) .'</div>';
                } else {
                	$parallaxsome_menu_tab .= '<a href="'. esc_url( home_url() ) .'/#section-'.$section_id.'">'. esc_attr( $parallaxsome_menu_title ) .'</a>';
                }                
                $parallaxsome_menu_tab .= '</li>';
                echo  $parallaxsome_menu_tab;
			}
		}
	}
endif;

add_action( 'parallaxsome_parallax_menu', 'parallaxsome_parallax_menu_cb', 10 );
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Primary menu section
 *
 */
if( ! function_exists( 'parallaxsome_main_menu_hook' ) ):
	function parallaxsome_main_menu_hook() {
		$parallaxsome_menu_style = get_theme_mod( 'primary_menu_type', 'parallax' );
		$parallax_menu_type = get_theme_mod( 'parallax_menu_type', 'default' );
		if( $parallax_menu_type == 'float' ) {
			$nav_class = 'ps-floating-menu';
		} else {
			$nav_class = 'ps-parallax-menu';
		}
?>
		<nav id="site-navigation" class="main-navigation ps-nav <?php echo esc_attr( $nav_class ); ?>" role="navigation">
			<div class="nav-wrapper">
				<div class="nav-toggle hide">
		            <span class="one"> </span>
		            <span class="two"> </span>
		            <span class="three"> </span>
		        </div>

                <?php
                if ( has_nav_menu( 'parallaxsome_primary_menu' ) ) {
                     wp_nav_menu( array( 'theme_location' => 'parallaxsome_primary_menu', 'menu_id' => 'primary-menu' ) );
                }else{
                    if( $parallaxsome_menu_style == 'parallax' ) { ?>
					<div class="menu-main-menu-container">
						<ul id="primary-parallax-menu" class="menu parallax-menu">
							<?php do_action( 'parallaxsome_parallax_menu' ); ?>
						</ul>
					</div>
				<?php }
                } ?> 
			</div><!-- .nav-wrapper -->
		</nav><!-- #site-navigation -->
		<div class="ps-head-search">
			<?php 
				$ps_search_option = get_theme_mod( 'primary_menu_search_option', 'show' );
				if( $ps_search_option != 'hide' ) {
			?>
					<span class="ps-search-icon"></span>
					<div class="search-form"><?php get_search_form(); ?></div>
			<?php } ?>
		</div><!-- .ps-head-search -->
<?php 
	}
endif;

add_action( 'parallaxsome_main_menu', 'parallaxsome_main_menu_hook', 10, 2 );
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Define function for fontawewome icons 
 * 
 * @param null
 * @return array
 * @since 1.0.0
 */
 function parallaxsome_icons_array(){
    $ap_icon_list_raw = 'fa-glass, fa-music, fa-search, fa-envelope-o, fa-heart, fa-star, fa-star-o, fa-user, fa-film, fa-th-large, fa-th, fa-th-list, fa-check, fa-times, fa-search-plus, fa-search-minus, fa-power-off, fa-signal, fa-cog, fa-trash-o, fa-home, fa-file-o, fa-clock-o, fa-road, fa-download, fa-arrow-circle-o-down, fa-arrow-circle-o-up, fa-inbox, fa-play-circle-o, fa-repeat, fa-refresh, fa-list-alt, fa-lock, fa-flag, fa-headphones, fa-volume-off, fa-volume-down, fa-volume-up, fa-qrcode, fa-barcode, fa-tag, fa-tags, fa-book, fa-bookmark, fa-print, fa-camera, fa-font, fa-bold, fa-italic, fa-text-height, fa-text-width, fa-align-left, fa-align-center, fa-align-right, fa-align-justify, fa-list, fa-outdent, fa-indent, fa-video-camera, fa-picture-o, fa-pencil, fa-map-marker, fa-adjust, fa-tint, fa-pencil-square-o, fa-share-square-o, fa-check-square-o, fa-arrows, fa-step-backward, fa-fast-backward, fa-backward, fa-play, fa-pause, fa-stop, fa-forward, fa-fast-forward, fa-step-forward, fa-eject, fa-chevron-left, fa-chevron-right, fa-plus-circle, fa-minus-circle, fa-times-circle, fa-check-circle, fa-question-circle, fa-info-circle, fa-crosshairs, fa-times-circle-o, fa-check-circle-o, fa-ban, fa-arrow-left, fa-arrow-right, fa-arrow-up, fa-arrow-down, fa-share, fa-expand, fa-compress, fa-plus, fa-minus, fa-asterisk, fa-exclamation-circle, fa-gift, fa-leaf, fa-fire, fa-eye, fa-eye-slash, fa-exclamation-triangle, fa-plane, fa-calendar, fa-random, fa-comment, fa-magnet, fa-chevron-up, fa-chevron-down, fa-retweet, fa-shopping-cart, fa-folder, fa-folder-open, fa-arrows-v, fa-arrows-h, fa-bar-chart, fa-twitter-square, fa-facebook-square, fa-camera-retro, fa-key, fa-cogs, fa-comments, fa-thumbs-o-up, fa-thumbs-o-down, fa-star-half, fa-heart-o, fa-sign-out, fa-linkedin-square, fa-thumb-tack, fa-external-link, fa-sign-in, fa-trophy, fa-github-square, fa-upload, fa-lemon-o, fa-phone, fa-square-o, fa-bookmark-o, fa-phone-square, fa-twitter, fa-facebook, fa-github, fa-unlock, fa-credit-card, fa-rss, fa-hdd-o, fa-bullhorn, fa-bell, fa-certificate, fa-hand-o-right, fa-hand-o-left, fa-hand-o-up, fa-hand-o-down, fa-arrow-circle-left, fa-arrow-circle-right, fa-arrow-circle-up, fa-arrow-circle-down, fa-globe, fa-wrench, fa-tasks, fa-filter, fa-briefcase, fa-arrows-alt, fa-users, fa-link, fa-cloud, fa-flask, fa-scissors, fa-files-o, fa-paperclip, fa-floppy-o, fa-square, fa-bars, fa-list-ul, fa-list-ol, fa-strikethrough, fa-underline, fa-table, fa-magic, fa-truck, fa-pinterest, fa-pinterest-square, fa-google-plus-square, fa-google-plus, fa-money, fa-caret-down, fa-caret-up, fa-caret-left, fa-caret-right, fa-columns, fa-sort, fa-sort-desc, fa-sort-asc, fa-envelope, fa-linkedin, fa-undo, fa-gavel, fa-tachometer, fa-comment-o, fa-comments-o, fa-bolt, fa-sitemap, fa-umbrella, fa-clipboard, fa-lightbulb-o, fa-exchange, fa-cloud-download, fa-cloud-upload, fa-user-md, fa-stethoscope, fa-suitcase, fa-bell-o, fa-coffee, fa-cutlery, fa-file-text-o, fa-building-o, fa-hospital-o, fa-ambulance, fa-medkit, fa-fighter-jet, fa-beer, fa-h-square, fa-plus-square, fa-angle-double-left, fa-angle-double-right, fa-angle-double-up, fa-angle-double-down, fa-angle-left, fa-angle-right, fa-angle-up, fa-angle-down, fa-desktop, fa-laptop, fa-tablet, fa-mobile, fa-circle-o, fa-quote-left, fa-quote-right, fa-spinner, fa-circle, fa-reply, fa-github-alt, fa-folder-o, fa-folder-open-o, fa-smile-o, fa-frown-o, fa-meh-o, fa-gamepad, fa-keyboard-o, fa-flag-o, fa-flag-checkered, fa-terminal, fa-code, fa-reply-all, fa-star-half-o, fa-location-arrow, fa-crop, fa-code-fork, fa-chain-broken, fa-question, fa-info, fa-exclamation, fa-superscript, fa-subscript, fa-eraser, fa-puzzle-piece, fa-microphone, fa-microphone-slash, fa-shield, fa-calendar-o, fa-fire-extinguisher, fa-rocket, fa-maxcdn, fa-chevron-circle-left, fa-chevron-circle-right, fa-chevron-circle-up, fa-chevron-circle-down, fa-html5, fa-css3, fa-anchor, fa-unlock-alt, fa-bullseye, fa-ellipsis-h, fa-ellipsis-v, fa-rss-square, fa-play-circle, fa-ticket, fa-minus-square, fa-minus-square-o, fa-level-up, fa-level-down, fa-check-square, fa-pencil-square, fa-external-link-square, fa-share-square, fa-compass, fa-caret-square-o-down, fa-caret-square-o-up, fa-caret-square-o-right, fa-eur, fa-gbp, fa-usd, fa-inr, fa-jpy, fa-rub, fa-krw, fa-btc, fa-file, fa-file-text, fa-sort-alpha-asc, fa-sort-alpha-desc, fa-sort-amount-asc, fa-sort-amount-desc, fa-sort-numeric-asc, fa-sort-numeric-desc, fa-thumbs-up, fa-thumbs-down, fa-youtube-square, fa-youtube, fa-xing, fa-xing-square, fa-youtube-play, fa-dropbox, fa-stack-overflow, fa-instagram, fa-flickr, fa-adn, fa-bitbucket, fa-bitbucket-square, fa-tumblr, fa-tumblr-square, fa-long-arrow-down, fa-long-arrow-up, fa-long-arrow-left, fa-long-arrow-right, fa-apple, fa-windows, fa-android, fa-linux, fa-dribbble, fa-skype, fa-foursquare, fa-trello, fa-female, fa-male, fa-gratipay, fa-sun-o, fa-moon-o, fa-archive, fa-bug, fa-vk, fa-weibo, fa-renren, fa-pagelines, fa-stack-exchange, fa-arrow-circle-o-right, fa-arrow-circle-o-left, fa-caret-square-o-left, fa-dot-circle-o, fa-wheelchair, fa-vimeo-square, fa-try, fa-plus-square-o, fa-space-shuttle, fa-slack, fa-envelope-square, fa-wordpress, fa-openid, fa-university, fa-graduation-cap, fa-yahoo, fa-google, fa-reddit, fa-reddit-square, fa-stumbleupon-circle, fa-stumbleupon, fa-delicious, fa-digg, fa-pied-piper-pp, fa-pied-piper-alt, fa-drupal, fa-joomla, fa-language, fa-fax, fa-building, fa-child, fa-paw, fa-spoon, fa-cube, fa-cubes, fa-behance, fa-behance-square, fa-steam, fa-steam-square, fa-recycle, fa-car, fa-taxi, fa-tree, fa-spotify, fa-deviantart, fa-soundcloud, fa-database, fa-file-pdf-o, fa-file-word-o, fa-file-excel-o, fa-file-powerpoint-o, fa-file-image-o, fa-file-archive-o, fa-file-audio-o, fa-file-video-o, fa-file-code-o, fa-vine, fa-codepen, fa-jsfiddle, fa-life-ring, fa-circle-o-notch, fa-rebel, fa-empire, fa-git-square, fa-git, fa-hacker-news, fa-tencent-weibo, fa-qq, fa-weixin, fa-paper-plane, fa-paper-plane-o, fa-history, fa-circle-thin, fa-header, fa-paragraph, fa-sliders, fa-share-alt, fa-share-alt-square, fa-bomb, fa-futbol-o, fa-tty, fa-binoculars, fa-plug, fa-slideshare, fa-twitch, fa-yelp, fa-newspaper-o, fa-wifi, fa-calculator, fa-paypal, fa-google-wallet, fa-cc-visa, fa-cc-mastercard, fa-cc-discover, fa-cc-amex, fa-cc-paypal, fa-cc-stripe, fa-bell-slash, fa-bell-slash-o, fa-trash, fa-copyright, fa-at, fa-eyedropper, fa-paint-brush, fa-birthday-cake, fa-area-chart, fa-pie-chart, fa-line-chart, fa-lastfm, fa-lastfm-square, fa-toggle-off, fa-toggle-on, fa-bicycle, fa-bus, fa-ioxhost, fa-angellist, fa-cc, fa-ils, fa-meanpath, fa-buysellads, fa-connectdevelop, fa-dashcube, fa-forumbee, fa-leanpub, fa-sellsy, fa-shirtsinbulk, fa-simplybuilt, fa-skyatlas, fa-cart-plus, fa-cart-arrow-down, fa-diamond, fa-ship, fa-user-secret, fa-motorcycle, fa-street-view, fa-heartbeat, fa-venus, fa-mars, fa-mercury, fa-transgender, fa-transgender-alt, fa-venus-double, fa-mars-double, fa-venus-mars, fa-mars-stroke, fa-mars-stroke-v, fa-mars-stroke-h, fa-neuter, fa-genderless, fa-facebook-official, fa-pinterest-p, fa-whatsapp, fa-server, fa-user-plus, fa-user-times, fa-bed, fa-viacoin, fa-train, fa-subway, fa-medium, fa-y-combinator, fa-optin-monster, fa-opencart, fa-expeditedssl, fa-battery-full, fa-battery-three-quarters, fa-battery-half, fa-battery-quarter, fa-battery-empty, fa-mouse-pointer, fa-i-cursor, fa-object-group, fa-object-ungroup, fa-sticky-note, fa-sticky-note-o, fa-cc-jcb, fa-cc-diners-club, fa-clone, fa-balance-scale, fa-hourglass-o, fa-hourglass-start, fa-hourglass-half, fa-hourglass-end, fa-hourglass, fa-hand-rock-o, fa-hand-paper-o, fa-hand-scissors-o, fa-hand-lizard-o, fa-hand-spock-o, fa-hand-pointer-o, fa-hand-peace-o, fa-trademark, fa-registered, fa-creative-commons, fa-gg, fa-gg-circle, fa-tripadvisor, fa-odnoklassniki, fa-odnoklassniki-square, fa-get-pocket, fa-wikipedia-w, fa-safari, fa-chrome, fa-firefox, fa-opera, fa-internet-explorer, fa-television, fa-contao, fa-500px, fa-amazon, fa-calendar-plus-o, fa-calendar-minus-o, fa-calendar-times-o, fa-calendar-check-o, fa-industry, fa-map-pin, fa-map-signs, fa-map-o, fa-map, fa-commenting, fa-commenting-o, fa-houzz, fa-vimeo, fa-black-tie, fa-fonticons, fa-reddit-alien, fa-edge, fa-credit-card-alt, fa-codiepie, fa-modx, fa-fort-awesome, fa-usb, fa-product-hunt, fa-mixcloud, fa-scribd, fa-pause-circle, fa-pause-circle-o, fa-stop-circle, fa-stop-circle-o, fa-shopping-bag, fa-shopping-basket, fa-hashtag, fa-bluetooth, fa-bluetooth-b, fa-percent, fa-gitlab, fa-wpbeginner, fa-wpforms, fa-envira, fa-universal-access, fa-wheelchair-alt, fa-question-circle-o, fa-blind, fa-audio-description, fa-volume-control-phone, fa-braille, fa-assistive-listening-systems, fa-american-sign-language-interpreting, fa-deaf, fa-glide, fa-glide-g, fa-sign-language, fa-low-vision, fa-viadeo, fa-viadeo-square, fa-snapchat, fa-snapchat-ghost, fa-snapchat-square, fa-pied-piper, fa-first-order, fa-yoast, fa-themeisle, fa-google-plus-official, fa-font-awesome' ;
    $ap_icon_list = explode( ", " , $ap_icon_list_raw);
    return $ap_icon_list;
 }

/*------------------------------------------------------------------------------------------------------------------*/
 /**
  * Define function for homepage slider
  *
  * @since 1.0.0
  */

if( ! function_exists( 'parallaxsome_homepage_slider_hook' ) ):
function parallaxsome_homepage_slider_hook() {
	$ps_slider_option = get_theme_mod( 'homepage_slider_option', 'hide' );
	$ps_slider_cat_id = get_theme_mod( 'slider_cat_id', '0' );
	if( $ps_slider_option != 'hide' && !empty( $ps_slider_cat_id ) ) {
?>
		<div id="section-slider" class="ps-front-slider-wrapper">
			<?php
				$ps_slider_args = array(
									'category__in' => $ps_slider_cat_id,
									'posts_per_page' => 5
									);
				$ps_slider_query = new WP_Query( $ps_slider_args );
				if( $ps_slider_query->have_posts() ) {
					echo '<ul class="frontSlider">';
					while( $ps_slider_query->have_posts() ) {
						$ps_slider_query->the_post();
						$image_id = get_post_thumbnail_id();
						$image_path = wp_get_attachment_image_src( $image_id, 'full', true );
						if( has_post_thumbnail() ) {

		?>
						<div class="single-slide-wrap" style="background-image: url('<?php echo esc_url( $image_path[0] ); ?>');">
							<div class="slider-info">
								<div class="ps-container">
									<h2 class="slider-title"><?php the_title(); ?></h2>
									<div class="slider-desc"><?php the_excerpt(); ?></div>
									<span class="slide-button">
										<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Learn More', 'parallaxsome' ); ?></a>										
									</span>
								</div>
							</div><!-- .slider-info -->
						</div><!-- .single-slide-wrap -->
		<?php
						}
					}
					echo '</ul>';
				}
                wp_reset_postdata();
			?>
			
		</div><!-- .ps-front-slider-wrapper -->
<?php
	}
}
endif;

add_action( 'parallaxsome_homepage_slider', 'parallaxsome_homepage_slider_hook', 10 );

/*------------------------------------------------------------------------------------------------------------------*/
 /**
  * Define header function for innerpages
  *
  * @since 1.0.0
  */

if( ! function_exists( 'parallaxsome_innerpage_header_hook' ) ):
	function parallaxsome_innerpage_header_hook() {
		
?>
 	<div class="ps-innerpages-header-wrapper" style="background-image: url(<?php header_image(); ?>);">
 		<div class="ps-container">
	 		<header class="entry-header">
				<?php
					if( is_archive() ) {
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					} elseif( is_single() && 'post' === get_post_type() ) {
						$post_category = get_the_category();
						$first_cat_name = $post_category[0]->name;
						echo '<h1 class="page-title">'. $first_cat_name .'</h1>';
					} elseif( is_page() ) {
						the_title( '<h1 class="entry-title">', '</h1>' );
					} elseif( is_search() ) {
				?>
						<header class="entry-header">
							<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'parallaxsome' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
						</header><!-- .page-header -->
				<?php
					}
					
					parallaxsome_breadcrumbs();
				?>
			</header><!-- .entry-header -->
		</div><!-- .ps-container -->
 	</div><!-- .ps-innerpages-header-wrapper -->
<?php
	}
endif;

add_action( 'parallaxsome_innerpage_header', 'parallaxsome_innerpage_header_hook' );
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Parallaxsome Social Icons
 *
 * @since 1.0.0
 */
if( !function_exists( 'parallaxsome_social_icons' ) ):
	function parallaxsome_social_icons() {
		$fb_link = get_theme_mod( 'fb_link', '' );
		$tw_link = get_theme_mod( 'tw_link', '' );		
		$ln_link = get_theme_mod( 'ln_link', '' );
		$pin_link = get_theme_mod( 'pin_link', '' );
		$gp_link = get_theme_mod( 'gp_link', '' );
		$yt_link = get_theme_mod( 'yt_link', '' );
?>
		<div class="ps-social-icons-wrapper">
			<?php if( !empty ( $fb_link ) ) { ?> <a href="<?php echo esc_url( $fb_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-facebook"></span></a> <?php } ?>
			<?php if( !empty ( $tw_link ) ) { ?> <a href="<?php echo esc_url( $tw_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-twitter"></span></a> <?php } ?>
			<?php if( !empty ( $ln_link ) ) { ?> <a href="<?php echo esc_url( $ln_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-linkedin"></span></a> <?php } ?>
			<?php if( !empty ( $pin_link ) ) { ?> <a href="<?php echo esc_url( $pin_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-pinterest"></span></a> <?php } ?>
			<?php if( !empty ( $gp_link ) ) { ?> <a href="<?php echo esc_url( $gp_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-google-plus"></span></a> <?php } ?>
			<?php if( !empty ( $yt_link ) ) { ?> <a href="<?php echo esc_url( $yt_link ); ?>" target="_
			blank"> <span class="ps-social-icon fa fa-youtube"></span></a> <?php } ?>
		</div><!-- .ps-social-icons-wrapper -->
<?php
	}
endif;
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Top header social icons
 *
 * @since 1.0.0
 */
if( ! function_exists( 'parallaxsome_top_social_icons_hook' ) ):
	function parallaxsome_top_social_icons_hook() {
		$top_social_option = get_theme_mod( 'top_header_social_option', 'show' );
		if( $top_social_option != 'hide' ) {
?>
			<div class="top-social-icons"><?php parallaxsome_social_icons(); ?></div>
<?php
		}
	}
endif;

add_action( 'parallaxsome_top_social_icons', 'parallaxsome_top_social_icons_hook', 10 );
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Homepage Section header
 *
 * @since 1.0.0
 */
if( ! function_exists( 'parallaxsome_section_header' ) ) {
	function parallaxsome_section_header( $title, $sub_title, $description ) {
?>
		<header class="section-header">
			
			<span class="section-sub-title"><?php echo esc_html( $sub_title ); ?></span>
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="section-description"><?php echo esc_html( $description ); ?></p>
			
		</header><!-- .entry-header -->
<?php
	}
}
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Get categories
 *
 * @since 1.0.0
 */
$parallaxsome_raw_categories = get_categories();
foreach ( $parallaxsome_raw_categories  as $categories ) {
	$parallaxsome_categories[$categories->slug] = $categories->name;
}
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Changed excerpt more
 *
 * @since 1.0.0
 */
add_filter( 'excerpt_more', 'parallaxsome_custom_excerpt_more' );

if( ! function_exists( 'parallaxsome_custom_excerpt_more' ) ):
	function parallaxsome_custom_excerpt_more( $more ) {
		return ' ';
	}
endif;
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Function define about page/post/archive sidebar
 *
 * @since 1.0.0
 */
if( ! function_exists( 'parallaxsome_get_sidebar' ) ):
function parallaxsome_get_sidebar() {
    global $post;

    if( 'post' === get_post_type() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'ps_post_sidebar_layout', true );
    }

    if( 'page' === get_post_type() ) {
    	$sidebar_meta_option = get_post_meta( $post->ID, 'ps_post_sidebar_layout', true );
    }
     
    if( is_home() ) {
        $set_id = get_option( 'page_for_posts' );
		$sidebar_meta_option = get_post_meta( $set_id, 'ps_post_sidebar_layout', true );
    }
    
    if( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
        $sidebar_meta_option = 'default_sidebar_layout';
    }
    
    $archive_sidebar      = get_theme_mod( 'ps_archive_sidebar_layout', 'right_sidebar' );
    $post_default_sidebar = get_theme_mod( 'ps_default_post_sidebar', 'right_sidebar' );
    $page_default_sidebar = get_theme_mod( 'ps_default_page_sidebar', 'right_sidebar' );
    
    if( $sidebar_meta_option == 'default_sidebar_layout' ) {
        if( is_single() ) {
            if( $post_default_sidebar == 'right_sidebar' ) {
                get_sidebar();
            } elseif( $post_default_sidebar == 'left_sidebar' ) {
                get_sidebar( 'left' );
            }
        } elseif( is_page() ) {
            if( $page_default_sidebar == 'right_sidebar' ) {
                get_sidebar();
            } elseif( $page_default_sidebar == 'left_sidebar' ) {
                get_sidebar( 'left' );
            }
        } elseif( $archive_sidebar == 'right_sidebar' ) {
            get_sidebar();
        } elseif( $archive_sidebar == 'left_sidebar' ) {
            get_sidebar( 'left' );
        }
    } elseif( $sidebar_meta_option == 'right_sidebar' ) {
        get_sidebar();
    } elseif( $sidebar_meta_option == 'left_sidebar' ) {
        get_sidebar( 'left' );
    }
}
endif;

/**
 * Remove the title prefix from archive pages
 *
 * @since 1.0.0
 */
add_filter( 'get_the_archive_title', function ($title) {
    if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard ps-admin">' . get_the_author() . '</span>' ;
        }
    return $title;
});
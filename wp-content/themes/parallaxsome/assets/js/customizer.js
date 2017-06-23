/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	/**
	* Header Section
	*/
	wp.customize( 'top_header_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('.ps-top-header-wrapper').fadeIn();
			} else {
				$('.ps-top-header-wrapper').fadeOut();
			}
		} );
	} );

	wp.customize( 'top_header_social_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('.top-social-icons').fadeIn();
			} else {
				$('.top-social-icons').fadeOut();
			}
		} );
	} );

	wp.customize( 'primary_menu_search_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('.ps-head-search').fadeIn();
			} else {
				$('.ps-head-search').fadeOut();
			}
		} );
	} );

	

	/**
	 * About Us Sections
	 */
	wp.customize( 'homepage_about_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-about').fadeIn();
			} else {
				$('#section-about').fadeOut();
			}
		} );
	} );

	wp.customize( 'about_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-about h2' ).text( to );
		} );
	} );

	wp.customize( 'about_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-about .section-sub-title' ).text( to );
		} );
	} );

	/**
	 * Team Sections
	 */
	wp.customize( 'homepage_team_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-team').fadeIn();
			} else {
				$('#section-team').fadeOut();
			}
		} );
	} );

	wp.customize( 'team_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-team h2' ).text( to );
		} );
	} );

	wp.customize( 'team_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-team .section-sub-title' ).text( to );
		} );
	} );

	wp.customize( 'team_section_description', function( value ) {
		value.bind( function( to ) {
			$( '#section-team .section-description' ).text( to );
		} );
	} );

	/**
	 * Service Sections
	 */
	wp.customize( 'homepage_service_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-services').fadeIn();
			} else {
				$('#section-services').fadeOut();
			}
		} );
	} );

	wp.customize( 'services_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-services h2' ).text( to );
		} );
	} );

	wp.customize( 'services_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-services .section-sub-title' ).text( to );
		} );
	} );

	wp.customize( 'services_section_description', function( value ) {
		value.bind( function( to ) {
			$( '#section-services .section-description' ).text( to );
		} );
	} );

	/**
	 * Testimonials Sections
	 */
	wp.customize( 'homepage_testimonials_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-testimonials').fadeIn();
			} else {
				$('#section-testimonials').fadeOut();
			}
		} );
	} );

	wp.customize( 'testimonials_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-testimonials h2' ).text( to );
		} );
	} );

	wp.customize( 'testimonials_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-testimonials .section-sub-title' ).text( to );
		} );
	} );

	/**
	 * Fact Sections
	 */
	wp.customize( 'homepage_fact_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-fact').fadeIn();
			} else {
				$('#section-fact').fadeOut();
			}
		} );
	} );

	wp.customize( 'fact_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-fact h2' ).text( to );
		} );
	} );

	wp.customize( 'fact_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-fact .section-sub-title' ).text( to );
		} );
	} );

	wp.customize( 'fact_section_description', function( value ) {
		value.bind( function( to ) {
			$( '#section-fact .section-description' ).text( to );
		} );
	} );

	/*------ First fact --- */
	wp.customize( 'fact_icon_0', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-0 i' ).attr( 'class', 'fa '+to );
		} );
	} );

	wp.customize( 'fact_counter_title_0', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-0 .ps-fact-title' ).text( to );
		} );
	} );

	wp.customize( 'fact_counter_number_0', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-0 .ps-fact-number' ).text( to );
		} );
	} );

	/*------ Second fact --- */
	wp.customize( 'fact_icon_1', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-1 i' ).attr( 'class', 'fa '+to );
		} );
	} );

	wp.customize( 'fact_counter_title_1', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-1 .ps-fact-title' ).text( to );
		} );
	} );

	wp.customize( 'fact_counter_number_1', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-1 .ps-fact-number' ).text( to );
		} );
	} );

	/*------ Third Fact --- */
	wp.customize( 'fact_icon_2', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-2 i' ).attr( 'class', 'fa '+to );
		} );
	} );

	wp.customize( 'fact_counter_title_2', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-2 .ps-fact-title' ).text( to );
		} );
	} );

	wp.customize( 'fact_counter_number_2', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-2 .ps-fact-number' ).text( to );
		} );
	} );

	/*------ Forth fact --- */
	wp.customize( 'fact_icon_3', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-3 i' ).attr( 'class', 'fa '+to );
		} );
	} );

	wp.customize( 'fact_counter_title_3', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-3 .ps-fact-title' ).text( to );
		} );
	} );

	wp.customize( 'fact_counter_number_3', function( value ) {
		value.bind( function( to ) {
			$( '#single-fact-3 .ps-fact-number' ).text( to );
		} );
	} );

	/**
	 * Portfolio Sections
	 */
	wp.customize( 'homepage_portfolio_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-portfolio').fadeIn();
			} else {
				$('#section-portfolio').fadeOut();
			}
		} );
	} );

	wp.customize( 'portfolio_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-portfolio h2' ).text( to );
		} );
	} );

	wp.customize( 'portfolio_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-portfolio .section-sub-title' ).text( to );
		} );
	} );

	wp.customize( 'portfolio_section_description', function( value ) {
		value.bind( function( to ) {
			$( '#section-portfolio .section-description' ).text( to );
		} );
	} );

	/**
	 * Blog Sections
	 */
	wp.customize( 'homepage_blog_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-blog').fadeIn();
			} else {
				$('#section-blog').fadeOut();
			}
		} );
	} );

	wp.customize( 'blog_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-blog h2' ).text( to );
		} );
	} );

	wp.customize( 'blog_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-blog .section-sub-title' ).text( to );
		} );
	} );

	/**
	 * Clients Sections
	 */
	wp.customize( 'homepage_clients_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-clients').fadeIn();
			} else {
				$('#section-clients').fadeOut();
			}
		} );
	} );

	wp.customize( 'clients_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-clients h2' ).text( to );
		} );
	} );

	wp.customize( 'clients_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-clients .section-sub-title' ).text( to );
		} );
	} );

	/**
	 * Contact Sections
	 */
	wp.customize( 'homepage_contact_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'show' ) {
				$('#section-contact').fadeIn();
			} else {
				$('#section-contact').fadeOut();
			}
		} );
	} );

	wp.customize( 'contact_section_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-contact h2' ).text( to );
		} );
	} );

	wp.customize( 'contact_section_sub_title', function( value ) {
		value.bind( function( to ) {
			$( '#section-contact .section-sub-title' ).text( to );
		} );
	} );
	wp.customize( 'team_view_more_txt', function( value ) {
		value.bind( function( to ) {
			$( '#section-team .ps-section-viewall a' ).html( to );
		} );
	} );
    wp.customize( 'blog_section_read_button', function( value ) {
		value.bind( function( to ) {
			$( '#section-blog .ps-more-button' ).html( to );
		} );
	} );
    wp.customize( 'blog_section_view_all_txt', function( value ) {
		value.bind( function( to ) {
			$( '#section-blog .ps-section-viewall a' ).html( to );
		} );
	} );
    
    wp.customize( 'contact_section_phone', function( value ) {
		value.bind( function( to ) {
			$( '#section-contact .ps-ctn span a' ).html( to );
		} );
	} );
    wp.customize( 'contact_section_address', function( value ) {
		value.bind( function( to ) {
			$( '#section-contact .address-home' ).html( to );
		} );
	} );
    wp.customize( 'contact_map_caption', function( value ) {
		value.bind( function( to ) {
			$( '#section-contact .ps-mag-caption' ).html( to );
		} );
	} );

} )( jQuery );

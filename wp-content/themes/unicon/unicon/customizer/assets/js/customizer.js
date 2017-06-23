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
	 * Unicon live preview scripts
	*/
	/*wp.customize("unicon_webpage_layout", function(value) {
	    value.bind(function(to) {
	        if( to == 'boxed' && $('body').hasClass('fullwidth-layout') ) {
	            $('body').removeClass('fullwidth-layout');
	            $('body').addClass('boxed-layout');
	        } else {
	            $('body').removeClass('boxed-layout');
	            $('body').addClass('fullwidth-layout');
	        }
	    } );
	});*/

	
	/**
	 * About Section
	*/
    wp.customize("unicon_about_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".about-section").css("display", "block");
            } else {
                $(".about-section").css("display", "none");
            }
        } );
    });

    wp.customize("unicon_about_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".about-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_about_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".about-section .section-title h3" ).text( to );
        } );
    });
	
	wp.customize("unicon_counter_section_title_1", function(value) {
        value.bind(function(to) {
            $(".count_1 > .title-one").text(to);
        } );
    });

	wp.customize("unicon_counter_section_number_1", function(value) {
        value.bind(function(to) {
            $(".count_1 > .counter-one").text(to);
        } );
    });

    wp.customize( 'unicon_counter_section_icon_1', function( value )  {
        value.bind( function( to )  {          
           $( '.count_1 > .icon-one' ).find( 'i' ).attr( 'class', 'fa fa-2x '+ to );
        } );
    } );


    wp.customize("unicon_counter_section_title_2", function(value) {
        value.bind(function(to) {
            $(".count_2 > .title-one").text(to);
        } );
    });

	wp.customize("unicon_counter_section_number_2", function(value) {
        value.bind(function(to) {
            $(".count_2 > .counter-one").text(to);
        } );
    });

    wp.customize( 'unicon_counter_section_icon_2', function( value )  {
        value.bind( function( to )  {          
           $( '.count_2 > .icon-one' ).find( 'i' ).attr( 'class', 'fa fa-2x '+ to );
        } );
    } );

    wp.customize("unicon_counter_section_title_3", function(value) {
        value.bind(function(to) {
            $(".count_3 > .title-one").text(to);
        } );
    });

	wp.customize("unicon_counter_section_number_3", function(value) {
        value.bind(function(to) {
            $(".count_3 > .counter-one").text(to);
        } );
    });

    wp.customize( 'unicon_counter_section_icon_3', function( value )  {
        value.bind( function( to )  {          
           $( '.count_3 > .icon-one' ).find( 'i' ).attr( 'class', 'fa fa-2x '+ to );
        } );
    } );


    wp.customize("unicon_counter_section_title_4", function(value) {
        value.bind(function(to) {
            $(".count_4 > .title-one").text(to);
        } );
    });

	wp.customize("unicon_counter_section_number_4", function(value) {
        value.bind(function(to) {
            $(".count_4 > .counter-one").text(to);
        } );
    });

    wp.customize( 'unicon_counter_section_icon_4', function( value )  {
        value.bind( function( to )  {          
           $( '.count_4 > .icon-one' ).find( 'i' ).attr( 'class', 'fa fa-2x '+ to );
        } );
    } );

	/**
	 * Our Services Section
	*/
    wp.customize("unicon_services_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".unicon-services-section").css("display", "block");
            } else {
                $(".unicon-services-section").css("display", "none");
            }
        } );
    });

    wp.customize("unicon_services_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".unicon-services-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_services_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".unicon-services-section .section-title h3" ).text( to );
        } );
    });


	/**
	 * Sucess Graph Section
	*/
    wp.customize("unicon_sucess_graph_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".unicon-sucess-section").css("display", "block");
            } else {
                $(".unicon-sucess-section").css("display", "none");
            }
        } );
    });


    wp.customize("unicon_sucess_graph_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".unicon-sucess-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_sucess_graph_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".unicon-sucess-section .section-title h3" ).text( to );
        } );
    });

	/**
	 * Counter Section
	*/
    wp.customize("unicon_counter_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".unicon-counter-section").css("display", "block");
            } else {
                $(".unicon-counter-section").css("display", "none");
            }
        } );
    });


	/**
	 * Our Team Member Section
	*/
    wp.customize("unicon_team_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".team-section").css("display", "block");
            } else {
                $(".team-section").css("display", "none");
            }
        } );
    });

    wp.customize("unicon_team_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".team-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_team_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".team-section .section-title h3" ).text( to );
        } );
    });

	/**
	 * Full Bg Video Section
	*/
    wp.customize("unicon_vieo_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".video-section").css("display", "block");
            } else {
                $(".video-section").css("display", "none");
            }
        } );
    });


    wp.customize("unicon_video_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".videoinfo h1" ).text( to );
        } );
    });

    wp.customize("unicon_video_description", function(value) {
        value.bind(function(to) {
            $( ".videoinfo p" ).text( to );
        } );
    });


    /**
	 * Our Works Section
	*/
    wp.customize("unicon_works_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".works-section").css("display", "block");
            } else {
                $(".works-section").css("display", "none");
            }
        } );
    });


    wp.customize("unicon_works_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".works-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_works_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".works-section .section-title h3" ).text( to );
        } );
    });

    /**
	 * Call To Action Section
	*/
    wp.customize("unicon_call_to_action_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".call-action-section").css("display", "block");
            } else {
                $(".call-action-section").css("display", "none");
            }
        } );
    });

    wp.customize('unicon_call_to_action_bg', function(value) {
        value.bind(function(to) {
            $( '.call-action-section' ).css( 'background-image', 'url( ' + to + ')' );
        });
    });


    wp.customize("unicon_call_to_action_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".call-content-wrapper h1" ).text( to );
        } );
    });

    wp.customize("unicon_call_to_action_description", function(value) {
        value.bind(function(to) {
            $( ".call-content-wrapper p" ).text( to );
        } );
    });


    wp.customize("unicon_call_to_action_first_button_title", function(value) {
        value.bind(function(to) {
            $( ".first-button a" ).text( to );
        } );
    });

    wp.customize("unicon_call_to_action_first_button_url", function(value) {
        value.bind(function(to) {
            $( ".first-button a" ).attr('href', to );
        } );
    });

    wp.customize("unicon_call_to_action_second_button_title", function(value) {
        value.bind(function(to) {
            $( ".second-button a" ).text( to );
        } );
    });

    wp.customize("unicon_call_to_action_second_button_url", function(value) {
        value.bind(function(to) {
            $( ".second-button a" ).attr('href', to );
        } );
    });

    /**
	 * Testimonial Section
	*/
    wp.customize("unicon_testimonial_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".testimonial-section").css("display", "block");
            } else {
                $(".testimonial-section").css("display", "none");
            }
        } );
    });

    wp.customize("unicon_testimonial_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".testimonial-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_testimonial_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".testimonial-section .section-title h3" ).text( to );
        } );
    });

    /**
	 * Our Partners Section
	*/
    wp.customize("unicon_our_partners_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".partners-section").css("display", "block");
            } else {
                $(".partners-section").css("display", "none");
            }
        } );
    });

    wp.customize('unicon_our_partners_bg_image', function(value) {
        value.bind(function(to) {
            $( '.partners-section' ).css( 'background-image', 'url( ' + to + ')' );
        });
    });

    wp.customize("unicon_our_partners_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".partners-wrapper h1" ).text( to );
        } );
    });

    wp.customize("unicon_our_partners_description", function(value) {
        value.bind(function(to) {
            $( ".partners-wrapper p" ).text( to );
        } );
    });

    wp.customize('unicon_our_partners_logo_1', function(value) {
        value.bind(function(to) {
            $( '.partners_1 img' ).attr('src', to );
        });
    });

    wp.customize('unicon_our_partners_logo_2', function(value) {
        value.bind(function(to) {
            $( '.partners_2 img' ).attr('src', to );
        });
    });

    wp.customize('unicon_our_partners_logo_3', function(value) {
        value.bind(function(to) {
            $( '.partners_3 img' ).attr('src', to );
        });
    });

    wp.customize('unicon_our_partners_logo_4', function(value) {
        value.bind(function(to) {
            $( '.partners_4 img' ).attr('src', to );
        });
    });

    wp.customize('unicon_our_partners_logo_5', function(value) {
        value.bind(function(to) {
            $( '.partners_5 img' ).attr('src', to );
        });
    });

    
    /**
	 * Our Blogs Section
	*/
    wp.customize("unicon_blog_section_option", function(value) {
        value.bind(function(to) {
            if( to == 'show') {
               $(".blog-section").css("display", "block");
            } else {
                $(".blog-section").css("display", "none");
            }
        } );
    });


    wp.customize("unicon_blog_section_main_title", function(value) {
        value.bind(function(to) {
            $( ".blog-section .section-title h1" ).text( to );
        } );
    });

    wp.customize("unicon_blog_section_sub_title", function(value) {
        value.bind(function(to) {
            $( ".blog-section .section-title h3" ).text( to );
        } );
    });

    /**
     * Copyright 
    */
    wp.customize("unicon_footer_copyright", function(value) {
        value.bind(function(to) {
            $( ".site-info p" ).text( to );
        } );
    });   



} )( jQuery );

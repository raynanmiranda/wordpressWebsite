jQuery(document).ready(function($) {
	'use strict';

	/**
     * Parallax menu
     */
    $(window).load(function(){
        if( $('body').hasClass('header-sticky') ) {
            var headerHeight = $('.ps-header-wrapper').outerHeight();
        } else {
            var headerHeight = 5;
        }
        $('.page-template-template-home .parallax-menu').onePageNav({
            currentClass: 'current',
            changeHash: false,
            scrollSpeed: 2200,
            scrollOffset: headerHeight,
            scrollThreshold: 0.5
        });
    });

    /**
     * Search icon at primary menu
     */
    $('.ps-search-icon').click(function() {
        $('.ps-head-search').find('.search-form').toggleClass('active-form');
        $('.ps-head-search').find('.search-form').fadeToggle();
    });

    /**
	 * Main Slider
	 */
	$('.frontSlider').bxSlider({
		auto:true,
		speed:1000,
		pause:6500,
		controls:false,
		mode:'fade',
        pager:true
	});

	/**
	 * Main slider height
	 */
	if( $('body').hasClass('home') ){
        /*reduceHeight = 75 ;
        if( $('#wpadminbar').length ) {
          reduceHeight = 92;
        }*/
        if( $('.ps-front-slider-wrapper').length ) {
            $(window).resize(function() {
                var wHeight = ( $(window).height() );
                $('.ps-front-slider-wrapper').find( '.bx-viewport' ).height(wHeight);
                $('.single-slide-wrap').height(wHeight);
            }).resize();
        }
    }

	/**
	 * Service section
	 */
	$('.service-tab-content .tab-pane').hide();
	$('.service-nav-tab li').first().addClass('active');
	$('.service-tab-content .tab-pane').first().addClass('active');
	$('.service-tab-content .tab-pane').first().show();
	$('.service-nav-tab li a').on('click', function(){
		var tabId = $(this).attr('data-tab');
		$('.service-tab-content .tab-pane').hide();
		$('.service-tab-content .tab-pane').removeClass('active');
		$('.service-nav-tab li').removeClass('active');
		$(this).parent('li').addClass('active');
		$('#'+tabId).show();
        $('#'+tabId).addClass('animated slideInRight');
		$('#'+tabId).addClass('active');
        $('#section-services').resize();
	});

	/**
	 * Testimonials Section
	 */
	$('.testiSlider').bxSlider();

	/**
	 * Fact Counter
	 */
    $('.ps-fact-number').counterUp({
        delay: 20,
        time: 2000
    });

    /**
     * Portfolio Section
     */
    $('#psProjects').lightSlider({
    	item:5,
    	loop:true,
    	slideMove:1,
    	speed:600,
        enableDrag: false,
        slideMargin: 0,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        responsive : [
            {
                breakpoint:800,
                settings: {
                    item:3,
                    slideMove:1,
                    slideMargin:6,
                  }
            },
            {
                breakpoint:480,
                settings: {
                    item:1,
                    slideMove:1
                  }
            }
        ],
        onSliderLoad: function() {
           $('.featuredSlider').removeClass( 'cS-hidden' );
       	}
    });

    /**
     * Map section
     */
    $('.ps-mag-caption').on('click', function(){
        $(this).toggleClass('active');
        $('.ps-map-frame').toggleClass('active');
        $('.ps-map-frame').fadeToggle();
    });

    /**
     * Image lightbox
     */
    $("a[rel^='projectPretty']").prettyPhoto({
        social_tools: false,
        deeplinking: false,
        theme:'pp_default'
    });

    /**
     * Nav toggle
     */
    $('.nav-toggle').click(function() {
        $('.nav-wrapper .menu').slideToggle('slow');
        $(this).parent('.nav-wrapper').toggleClass('active');
    });

    $('.nav-wrapper .menu-item-has-children').append('<span class="sub-toggle"> <i class="fa fa-angle-right"></i> </span>');
    $('.nav-wrapper .page_item_has_children').append('<span class="sub-toggle-children"> <i class="fa fa-angle-right"></i> </span>');

    $('.nav-wrapper .sub-toggle').click(function() {
        $(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
        $(this).children('.fa-angle-right').first().toggleClass('fa-angle-down');
    });

    $('.nav-wrapper .sub-toggle-children').click(function() {
        $(this).parent('.page_item_has_children').children('ul.children').first().slideToggle('1000');
        $(this).children('.fa-angle-right').first().toggleClass('fa-angle-down');
    });

    /**
     * Wow
     */
    if( $('body').hasClass('page-template-template-home') ) {
        new WOW().init();
    }

    /** 
     *Top up arrow
     */
    $("#scroll-up").hide();
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 1000) {
                $('#scroll-up').fadeIn();
            } else {
                $('#scroll-up').fadeOut();
            }
        });
        $('a#scroll-up').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });


});
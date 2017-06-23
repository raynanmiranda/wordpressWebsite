jQuery(document).ready(function($){

	/**
	 * Animation with wow
	*/
	new WOW().init();

	/**
	 * Nav Toggle
	*/
	$('.kr-toggle').click(function(){
	   $('.kr-toggle').toggleClass('on');
	   $('.menulink').toggleClass('open');
	   $('.mask').toggleClass('show');
	   $('body').toggleClass('menu_mobile_open');
	});

	$('.mask').click(function(){
		$('.kr-toggle').removeClass('on');
	   $('.mask').removeClass('show');
	   $('.menulink').removeClass('open');
	   $('body').removeClass('menu_mobile_open');
	});
	
	$('.nav-toggle').click(function() {
	   $('.nav-wrapper').find('#apmag-header-menu').slideToggle('slow');
	   $(this).parent('.nav-wrapper').toggleClass('active');
	});

	$('.menulink .menu-item-has-children').append('<span class="sub-toggle"> <i class="fa fa-angle-right"></i> </span>');

	$('.menulink .sub-toggle').click(function() {
	   $(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
	   $(this).children('.fa-angle-right').first().toggleClass('fa-angle-down');
	});

	/**
	 * LightSlider Carousel About Services Section
	*/	
	$(".aboutserviceswrap").lightSlider({
		item:2,
		pager:false,
		loop:true,
		controls:true,
		prevHtml: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
		nextHtml : '<i class="fa fa-angle-right" aria-hidden="true"></i>',
		responsive : [            
            {
                breakpoint:480,
                settings: {
                    item:1,
                    slideMove:1
                  }
            }
        ]
	});

	/**
	 * LightSlider Carousel Team Member Section
	*/	
	$(".teamwrap").lightSlider({
		item:1,
		pager:true,
		loop:true,
		controls:true,
		prevHtml: 'prev',
		nextHtml : 'next',
	});

	/**
	 * LightSlider Carousel Testimonial Section
	*/	
	$(".testimonialwrap").lightSlider({
		item:1,
		pager:true,
		loop:true,
		controls:false,
	});

	/**
	 * LightSlider Carousel Testimonial Section
	*/	
	$(".blogwrap").lightSlider({
		item : 2,
		pager:false,
		loop:true,
		controls:true,
		prevHtml: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
		nextHtml : '<i class="fa fa-angle-right" aria-hidden="true"></i>',
		responsive : [            
            {
                breakpoint:480,
                settings: {
                    item:1,
                    slideMove:1
                  }
            }
        ]
	});


	/**
	 * Youtube Video Section
	*/
	jQuery("#uniconbgndVideo").YTPlayer({
		showControls: false,
		containment:'.video-section',
		autoPlay:false, 
		mute:true, 
		startAt:0, 
		opacity:1
	});
	$('#togglePlay').click(function(){
		$('#uniconbgndVideo').YTPTogglePlay();
		$("#togglePlay").toggleClass('play');
		$('.video-wrapper .videoinfo').toggleClass('play');
		return false;
	});

	$('.mbYTP_wrapper').click(function(){
		$('#uniconbgndVideo').YTPTogglePlay();
		$("#togglePlay").toggleClass('play');
		$('#uniconvideoframe').find('.videoinfo').toggleClass('play');
		return false;
	});
	

	/**
	 * Number Counter
	*/
	$('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    /**
     * jQuery Our Works
    */
	var scrollPane = $(".scrollPane"),
	    scrollContent = $(".workcontentwrap");
	//build slider
	var scrollbar = $(".scroll-bar").slider({
	    slide: function (event, ui) {
	        if (scrollContent.width() > scrollPane.width()) {
	            scrollContent.css("margin-left", Math.round(
	                    ui.value / 100 * (scrollPane.width() - scrollContent.width())
	                    ) + "px");
	        } else {
	            scrollContent.css("margin-left", 0);
	        }
	    }
	});


    /**
     * ScrollUp
    */
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > 1000) {
			jQuery('.scrollup').fadeIn();
		} else {
			jQuery('.scrollup').fadeOut();
		}
	});

	jQuery('.scrollup').click(function() {
		jQuery("html, body").animate({
			scrollTop: 0
		}, 2000);
		return false;
	});

	/**
	 * Header Banner Image
	*/	
    $(window).load(function() {
    	$(window).resize(function(){
    		var bannerheight = $(window).height();
			$('.kr-homepage').css("height", bannerheight);
		}).resize();
	});

});


/**
 * Generate Sucess Graph
*/
var unicon_sucess_per = unicon_pro_ajax_script.sucess_per;
var unicon_sucess_year = unicon_pro_ajax_script.sucess_year;
//var randomScalingFactor = function(){ return Math.round(Math.random()*6) };
var unicon_barChartData = {	
    labels : unicon_sucess_year,
    datasets : [
        {
            fillColor : "rgba(162, 202, 77,1)",
            strokeColor : "rgba(162, 202, 77,1)",
            data: unicon_sucess_per
        }
    ]
}
window.onload = function(){
    var unicon_ctx = document.getElementById("canvas").getContext("2d");
    window.myBar = new Chart(unicon_ctx).Bar(unicon_barChartData, {
        responsive : true,
        datasetStrokeWidth : 2
    });
}
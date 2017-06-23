jQuery(document).ready(function ($) {
    /** Home Slider **/
	if($('body').hasClass('page-template-tpl-home')){
		$("#revolve-home-slider").onepage_scroll({
	       sectionContainer: ".revolve-sliders",
	       easing: "ease",
	       animationTime: 500,
	       pagination: false,
	       loop: false,
	       keyboard: true,
	       responsiveFallback: false,
	       afterMove: function(index) {
	        var activeItem = $('.revolve-sliders.active').data('item');
	        $(".main-navigation > ul #mCSB_1_container > li").removeClass('active');
	        $(".main-navigation > ul #mCSB_1_container > li."+activeItem).addClass('active');
	       },
	       beforeMove: function(index) {
	      		//alert('fdsfsf');
	    	}
	    });

		$('.revolve-move-down').click(function(){
			$("#revolve-home-slider").moveDown();	
		});
	    
	    var activeItem = $('#revolve-home-slider').find('.revolve-sliders:first').data('item');
	    $(".main-navigation > ul > li."+activeItem).addClass('active');
    }

    /** Sub Menu Navigation **/
    $('.sub-nav li > a').on('click', function () {
        if(($(this).parents('.sub-nav').children('li').hasClass('active'))) {
        } else {
            if($(this).parents('li').hasClass('current-menu-item')) {
            } else {
                var link = $(this).attr('href');
                window.open(link, '_self', 'no', true);
                $(this).parent('li').addClass('active');
            }
        }
    });

    $(".sub-nav").onePageNav({
        currentClass: 'active',
        changeHash: true,
        scrollSpeed: 750,
        scrollThreshold: 0.5,
    });
    
    /** Team Member Switch Layout Style **/
        $(".team-disp-style a").click(function (e) {
            e.preventDefault();
            var oself = $(this);
            var dispstyle = oself.attr("data-format");
            
            $(".team-all-members").hide();
            $(".team-disp-style a").removeClass('icon-active');
            oself.addClass('icon-active');
            $(".team-"+dispstyle+"-layout").fadeIn();
        });
        
    /** Team Member Display/Hide Info **/
        /** Display Info **/
        $(".team-all-members .team-member").on('click', function (e) {
            e.preventDefault();
            var oself = $(this);
            var alldispin = $(".team-info-block")
            var dispin = oself.parents('.team-list-block').next('.team-info-block');
            var teaminfo = oself.next('.team-info').html();

            $.scrollTo('#team-infos-block', 800);

            oself.toggleClass('active');
            oself.siblings('.team-member').toggleClass('inactive');
            oself.parents('.team-list-block').siblings('.team-list-block').find('.team-member').toggleClass('inactive');
            oself.parents('.letter-group').siblings('.letter-group').find('.team-member').toggleClass('inactive');
            dispin.html(teaminfo).slideToggle(1500); //.addClass('active');;

            var offset = $(this).parents('.team-list-block').next('.team-info-block').offset();
            $('html, body').animate({
                scrollTop: offset.top
              }, 1000);
        });
        
        /** Hide Info **/
        $(document).on('click', '.close-team-info', function(e){
            e.preventDefault();
            
            $(".team-all-members .team-member").removeClass('active');
            $(".team-all-members .team-member").removeClass('inactive');
            $(".team-all-members .team-info-block").slideUp(1500);
        });
        
    /** Team Member Search **/
        $(".team-search-box").keyup(function (e) {
            e.preventDefault();
            var idstring = $(this).val();
            var istring = idstring.toLowerCase();
            var chkdstring = '';
            
            $(".team-member-name").each(function (){
                chkdstring = $(this).html();
                var chkstring = chkdstring.toLowerCase();

                if(chkstring.indexOf(istring) < 0) {
                    $(this).parents('.team-member').hide();
                } else {
                    $(this).parents('.team-member').show();
                }
            });
        });
        
    /** Portfolio Lightbox **/
        $('.port-viewbox').viewbox({
    		setTitle: true,
    		margin: 20,
    		resizeDuration: 300,
    		openDuration: 200,
    		closeDuration: 200,
    		closeButton: true,
    		navButtons: true,
    		closeOnSideClick: true,
    		nextOnContentClick: true
    	});
        
    /** Responsive Menu **/
    var rv_menuflag = true;
    $("#menu-toggle").click(function (){
        $("#revolve-home-slider, #revolve-side-nav, #menu-toggle").toggleClass('menu-slide');
        $("#revolve-page-content").toggleClass('menu-slide');
        
        if(rv_menuflag) {
            $(this).html('<i class="fa fa-times"></i>');
            rv_menuflag = false;
        } else {
            $(this).html('<i class="fa fa-bars"></i>');
            rv_menuflag = true;
        }
    });

    /** Nano Scroller **/
    $('.social-toggle').on('click', function (e){
        e.preventDefault();
        $('.rvl-header-social-icons').slideToggle();
    });

    /** Header Social Links (Responsive Tweak below 768) **/
    var rvlwidth = $(window).width();
    if(parseInt(rvlwidth) <= 768) {
        $("#menu-toggle").on('click', function () {
            $(".header-social-icons-container").toggleClass('res-toggle');
        });
        $(".social-toggle").on('click', function () {
            $(".aps-each-icon").css("right","1px");
        });
    }

    /** Scrollbar **/
        $(".menu").mCustomScrollbar({
            theme: "minimal",
            axis:"y"
        });
        
        $(window).load(function(){
            var headerHeight = $('#revolve-masthead').height();
            
            $('.main-navigation').css('top', headerHeight);
        });

});

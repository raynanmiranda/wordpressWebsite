jQuery(document).ready(function ($) {
    $(document).on('click' , 'input.cus-menu-check', function() {
        if ($(this).is(":checked")) {
            $(this).parent('label').next('input').val('yes');
        } else{
            $(this).parent('label').next('input').val('no');
        }
    });
    
    $("#parent_id").change(function (){
        var me = $("option:selected", this);
        var myval = me.val();
        if(myval) {
            $("#revolve-page-options .home-slider-poster-options").fadeOut();
            $("#revolve-page-options .child-page-id-options").fadeIn();
        } else {
            $("#revolve-page-options .home-slider-poster-options").fadeIn();
            $("#revolve-page-options .child-page-id-options").fadeOut();
        }
    });

    $('#customize-control-revolve_theme_info_theme').prepend(
        '<div class="user_sticky_note">'+
        '<span class="sticky_info_row"><a class="button" href="http://demo.accesspressthemes.com/revolve/" target="_blank">Live Demo</a>'+
        '<span class="sticky_info_row"><a class="button" href="http://accesspressthemes.com/documentation/revolve/" target="_blank">Documentation</a></span>'+
        '<span class="sticky_info_row"><a class="button" href="https://accesspressthemes.com/support/forum/themes/free-themes/revolve/" target="_blnak">Support Forum</a></span>'+
        '</div>'
    ); 

    jQuery(window).load(function() {

      var upgrade_notice = '<a class="upgrade-pro" target="_blank" href="https://accesspressthemes.com/wordpress-themes/revolve-pro/">UPGRADE TO REVOLVE PRO</a>';
      upgrade_notice += '<a class="upgrade-pro" target="_blank" href="http://accesspressthemes.com/theme-demos/?theme=revolve-pro">REVOLVE PRO DEMO</a>'

      jQuery('#customize-info .preview-notice').append(upgrade_notice);

    });
});
jQuery(document).ready(function($) {
	'use strict';

	/**
     * Radio Image control in customizer
     */
    // Use buttonset() for radio images.
    $( '.ps-meta-options-wrap .buttonset' ).buttonset();


    /**
     * Meta tabs and its content
     */
    $('.ps-meta-menu-wrapper li').click(function() {
        var tabIdRaw = $(this).attr('id');
        var tabId = tabIdRaw.split('-');
        $('.ps-single-meta').hide();
        $('#ps-'+tabId[1]+'-content').fadeIn();
    });
});
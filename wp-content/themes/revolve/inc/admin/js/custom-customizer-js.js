jQuery(document).ready(function ($) {
    $('#ex-cat-wrap input:checkbox').on('change', function (e) {
        e.preventDefault();
        var chkbox = $('#ex-cat-wrap input:checkbox');
        var id = '';
        
        $.each( chkbox, function () {
            var oid = $(this).val(); 
            
            if($(this).attr('checked')) {
                id += oid;
                id += ','; 
            }
        });
        
        $('#ex-cat-wrap').next('input:hidden').val(id).change();
    });
});
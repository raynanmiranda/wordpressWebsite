jQuery(document).ready(function ($){
    /** Revolve Background Image **/
    $(document).on('click' , '.poster_upload_btn', function(e) {
    	e.preventDefault();
        var $this = $(this);
    	var image = wp.media({ 
    		title: 'Upload Image',
    		// mutiple: true if you want to upload multiple files at once
    		multiple: false
    	}).open()
    	.on('select', function(e){
    		// This will return the selected image from the Media Uploader, the result is an object
    		var uploaded_image = image.state().get('selection').first();
    		// We convert uploaded_image to a JSON object to make accessing it easier
    		// Output to the console uploaded_image
    		var image_url = uploaded_image.toJSON().url;
    		// Let's assign the url value to the input field
    		$this.prev('.upload').val(image_url);
            
            //var img = "<img src='"+image_url+"' style='width: 275px; height: auto' /><a class='remove-image remove-screenshot'><i class='fa fa-times-circle fa-2x'></i></a>";
            var img = '<img src="'+image_url+'" /><a class="img-rem-btn">X</a>';
            $this.next('.img_preview').html(img);
    	});
    });
    
     $(".img-rem-btn").click(function () {
        $(this).parent('.img_preview').prev('span').prev('input').val('');
        $(this).parent('.img_preview').html('');
     });
});
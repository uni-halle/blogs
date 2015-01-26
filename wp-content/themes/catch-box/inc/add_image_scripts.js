// For media uploader
jQuery(document).ready(function($){
	var custom_uploader;
    
    $( '.catchbox_upload_image' ).click(function(e) {
        e.preventDefault();

        title 			= $(this).val();

        this_selector 	= $(this); //For later use

        button_text 	= $(this).attr("ref");
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: title,
            button: {
                text: button_text
            },
            multiple: true
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on( 'select', function() {
            attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
        	this_selector.prev().val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
});
$hdQuJ=jQuery.noConflict();

    // Check to see if user has Image Answers enabled
    $hdQuJ("#hdQue-post-class23").click(function() {
	$hdQuJ( ".answerFeaturedImage" ).toggleClass( "showImage" );
    });

    // check to see if user is using Question as Title
    $hdQuJ("#hdQue-post-class24").click(function() {
	$hdQuJ( "#hdQuestionnaire" ).toggle( "slow" );
    });
    if ($hdQuJ('#hdQue-post-class24').is(':checked')) { $hdQuJ( "#hdQuestionnaire" ).hide(); }


	// Uploading files
	var file_frame;
	$hdQuJ('.uploadimage').live('click', function( event ){

	selectedImage = $hdQuJ(this).attr('class').split(' ')[1];
	nextTextFieldId = $hdQuJ(this).next().attr("id");
	event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $hdQuJ( this ).data( 'uploader_title' ),
      button: {
        text: $hdQuJ( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
      imgURL = attachment.url;
        // Do something with attachment.id and/or attachment.url here
	$hdQuJ( '.'+ selectedImage ).attr("src", imgURL);
	$hdQuJ( '#'+ nextTextFieldId ).val(imgURL);

    });

    // Finally, open the modal
    file_frame.open();
});
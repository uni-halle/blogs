jQuery(document).ready(function($){
	$("#AGLBSettingsForm").change(function(e){

		var AGLBLayout        = $("select[name='AGPressGraph_like_type']").val();
		var AGLBFaces         = ($("input[name='AGPressGraph_like_face']").is(":checked") ? true : false);;
		var AGLBActionType    = $("select[name='AGPressGraph_like_verb']").val();
		var AGLBIncludeShare  = ($("input[name='AGPressGraph_like_include_share']").is(":checked") ? true : false);
		var AGLBAppID 		  = $("input[name='AGPressGraph_like_appid']").val();
		var AGLBLightScheme   = $("select[name='AGPressGraph_like_color']").val();
		var AGLBWidth         = $("input[name='AGPressGraph_like_width']").val();
		var AGLBKidRestricted = ($("input[name='AGPressGraph_like_kid_restricted']").is(":checked") ? true : false);
		
		
		var button = '<div class="fb-like" data-colorscheme="'+AGLBLightScheme+'" data-href="http://ahmedgeek.com" data-layout="'+AGLBLayout+'" data-kid-directed-site="'+AGLBKidRestricted+'" data-width="'+AGLBWidth+'" data-action="'+AGLBActionType+'" data-show-faces="'+AGLBFaces+'" data-share="'+AGLBIncludeShare+'"></div>';
		
		$("#AGLBTheButton").html(button);
		
		try{
	        FB.XFBML.parse(); 
	    }catch(ex){}
	});
	
	$("#AGLBSelectDImage").click(function(e){
        e.preventDefault();
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
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('input[name="AGPressGraph_like_dimage"]').prop("value", image_url);
            $("#AGLBOGImage").prop("src", image_url);
            $("#AGLBOGImage").show();
            
        });
	});
	
	$("#AGLBPostCustomOG").click(function(e){
		 e.preventDefault();
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
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            image.close();
            // Let's assign the url value to the input field
            $('input[name="AGLBCustomOGImage"]').prop("value", image_url);
            $("#AGLBCustomOGImage").prop("src", image_url);
            $("#AGLBCustomOGImage").show();
            $("#TB_overlay").click();
        });
	});
});
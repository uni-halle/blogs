jQuery(document).ready(function($){
	
	$('#tab-container').easytabs();
	
});


function updateOptions(options){
	
	var opt = jQuery.parseJSON(options);
	

	/*
	 * getting action from object
	 */
	
	
	/*
	 * extractElementData
	 * defined in nm-globals.js
	 */
	var data = extractElementData(opt);
	
	
	if (data.bug) {
		//jQuery("#reply_err").html('Red are required');
		alert('bug here');
	} else {

		data.action = 'saveRepoSettings';

		jQuery.post(ajaxurl, data, function(resp) {

			//jQuery("#reply_err").html(resp);
			alert(resp);

		});
	}
	
	/*jQuery.each(res, function(i, item){
		
		alert(i);
		
	});*/
}


function itIsPro(){
	
	jQuery("#pro-feature").fadeIn(500, function(){
		
		jQuery(this).fadeOut(10000);
	});
}
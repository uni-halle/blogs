(function($){
	// menu
	$('#settingslink').click(function() {
		$('#notes,#guide').hide('fast'); 
		$('#settings').show('fast');
		$('#settingslink').css('color','#d54e21');
		$('#noteslink').css('color','#264761'); 
		$('#guidelink').css('color','#264761');
		return false;
	});

	$('#guidelink').click(function() {	
		$('#notes,#settings').hide('fast');  
		$('#guide').show('fast'); 
		$('#settingslink').css('color','#264761'); 
		$('#noteslink').css('color','#264761'); 
		$('#guidelink').css('color','#d54e21');
		return false;
	});
	$('#noteslink').click(function() {	
		$('#guide,#settings').hide('fast'); 
		$('#notes').show(); 
		$('#settingslink').css('color','#264761'); 
		$('#noteslink').css('color','#d54e21'); 
		$('#guidelink').css('color','#264761');
		return false;
	});

	// hide stuff
	$("#loading").hide();
	
	// theme previews
	$('#button_theme').change(function () {
		var id = $('#button_theme option:selected').val();
		$('#previewdiv .alternate div.preview-wrapper').filter(":visible").hide('fast');
		$('#'+id).show('fast');
	});

	$('#button_template').change(function () {
		$('#button_theme').val('custom_edit').change();
	});
	
	// info blocks
	$('.info').click(function () {
		var id = $(this).attr('href');
		$(id).toggle('fast');
		return false;
	});

})(jQuery);


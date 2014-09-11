(function($) {
$(function() {
	bind_save_setting();
});

function bind_save_setting() {
	message ='<div id="message" class="updated"><p><strong>Settings saved</strong></p></div>';
	
	$('#toggle_title_submit').on('click', _do_update_option);
	
	function _do_update_option() {
		$('#message').slideUp('slow', function() {
			$(this).remove();
		});
		
		if ($('#hook_toggle_btn_title_autosave').is(':checked')) var autosaveValve = 'checked="checked"';
		else var autosaveValve = '';

		if ($('#hook_toggle_btn_title_disableAllPages').is(':checked')) var disableAllPagesValve = 'checked="checked"';
		else var disableAllPagesValve = '';
			
		var data = {
			action: 'update_title_options',
			isAutoSaveValveChecked: autosaveValve,
			isDisableAllPagesValveChecked: disableAllPagesValve,
		};
		TitleToggler_post(data);
	}
}

function TitleToggler_post(data) {
	jQuery.post(ajaxurl, data, function(response) {
		$('#message').remove();
		$('.wrap').append(message).find('#message').hide().slideDown('slow');
	});
}

})(jQuery);
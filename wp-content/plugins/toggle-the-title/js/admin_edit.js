(function($) {
$(function() {
	bind_save_page_title_status();
});

function bind_save_page_title_status() {
	$('#hook_toggle_page_title').bind('change._save_page_title_status', _save_page_title_status);
	
	function _save_page_title_status() {
		$('#publish').trigger('click');
	}
}

})(jQuery);
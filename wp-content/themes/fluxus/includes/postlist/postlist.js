jQuery(function(){
	check_for_onlyeventlist();
	var posttypes = jQuery('#postlist input[type="checkbox"]').parent('p');
	posttypes.each(function(){
		if(!jQuery(this).children('input[type="checkbox"]').attr('checked')){
			jQuery(this).children('select').css('visibility', 'hidden');
		}
		jQuery(this).children('input[type="checkbox"]').click(function(){
			if(jQuery(this).attr('checked')){
				jQuery(this).parent('p').children('select').css('visibility', 'visible');
			} else {
				jQuery(this).parent('p').children('select').css('visibility', 'hidden');
			}
			if(jQuery(this).children('input[type="checkbox"]').attr('name') != "event_show" && jQuery(this).children('input[type="checkbox"]').attr('checked')){
				onlyevents = false;
			} else {
				onlyevents = true;
			}
			check_for_onlyeventlist();
		});
		if(jQuery(this).children('input[type="checkbox"]').attr('name') != "event_show" && jQuery(this).children('input[type="checkbox"]').attr('checked')){
			onlyevents = false;
		}
	});
	function check_for_onlyeventlist(){
		var onlyevents = true;
		var checkboxes = jQuery('#postlist input[type="checkbox"]');
		checkboxes.each(function(){
			if(jQuery(this).attr('name') != "event_show" && jQuery(this).attr('checked')){
				onlyevents = false;
			}
		});
		if(!onlyevents){
			jQuery('#postlist input[name="event_show"]').parent('p').children('select[name="timespanevent"]').hide();
		} else {
			jQuery('#postlist input[name="event_show"]').parent('p').children('select[name="timespanevent"]').show();
		}
	}
});
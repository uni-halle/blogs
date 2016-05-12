/*
set today's date as default
*/

function timezonecalculator_calculator_set_default_date() {
	datePickerController.setSelectedDate('timezonecalculator_date', timezonecalculator_get_today_date());
	jQuery('#timezonecalculator_date').val('now');

	document.activeElement.blur();
}

/*
automatically update _ajax_nonce
*/

var timezonecalculator_calculator_query_params=new Hashtable();

timezonecalculator_calculator_query_params.put('action', 'timezonecalculator_calculator_ajax_nonce');

timezonecalculator_calculator_query_params.put('_ajax_nonce', timezonecalculator_calculator_settings._ajax_nonce);

if (parseInt(timezonecalculator_calculator_settings.refresh_nonce, 10)==1)
	window.setInterval(function(){
		timezonecalculator_refresh(new Hashtable(), timezonecalculator_calculator_query_params);
		},
		600*1000
	);

/*
display error/success message
*/
		
function timezonecalculator_calculator_display_message(message) {
	var divid='timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id;
	jQuery('#'+divid).html(message);
}

/*
display error/success message
*/
		
function timezonecalculator_calculator_error_calculation(error) {
	var message='A communication error occurred! - Please try again!';

	if (error==-2 || error==-3)
		message='An error occurred! - Please check your input!';

	timezonecalculator_calculator_display_message(message);
}

/*
display result container and
scroll to top of buttons
*/

function timezonecalculator_calculator_show_results() {
	jQuery('#timezonecalculator_calculator_results').show();

	if (jQuery('#timezonecalculator_calculator_results').css('float')=='none')
		jQuery('html, body').animate({
			scrollTop: jQuery('#timezonecalculator_calculate_time').offset().top-10
		}, 500);
}

/*
calculate_time button
retrieve timezones via AJAX Call

- display results-block
- disable calculate_time button
- disable form_reset button
- display pre-loader
*/

function timezonecalculator_calculator_calculation() {
	timezonecalculator_calculator_display_message('');

	var query_time=jQuery('#timezonecalculator_date').val();

	if (query_time.length<3) {
		timezonecalculator_calculator_display_message('Please enter a date!');
		timezonecalculator_calculator_show_results();

		return;
	}

	jQuery('#timezonecalculator_date').prop('disabled', true);
	jQuery('#timezonecalculator_hour').prop('disabled', true);
	jQuery('#timezonecalculator_minute').prop('disabled', true);
	jQuery('#timezonecalculator_continent').prop('disabled', true);
	jQuery('#timezonecalculator_timezone').prop('disabled', true);
	datePickerController.disable('timezonecalculator_date');

	jQuery('#timezonecalculator_calculate_time').prop('disabled', true);
	jQuery('#timezonecalculator_form_reset').prop('disabled', true);

	jQuery('#timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id).addClass('timezonecalculator_spinner');
	timezonecalculator_adapt_spinner();

	timezonecalculator_calculator_show_results();

	var params = timezonecalculator_refresh_create_params('timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id, '<div id="timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id+'" class="timezonecalculator-output"');

	params.put('callback_finished', timezonecalculator_calculator_after_calculation);
	params.put('callback_error', timezonecalculator_calculator_error_calculation);

	if (jQuery('#timezonecalculator_hour').prop('selectedIndex')>0) {
		query_time+=jQuery('#timezonecalculator_hour').val()+':';

		if (jQuery('#timezonecalculator_minute').prop('selectedIndex')>0)
			query_time+=jQuery('#timezonecalculator_minute').val();

		else
			query_time+='00';
	}

	var query_timezone=jQuery('#timezonecalculator_timezone').val();
	var query_timezones=escape(jQuery('#timezonecalculator_timezones').val());

	var query_string='id='+timezonecalculator_calculator_settings.block_id+'&query_time='+query_time+'&query_timezone='+query_timezone+'&timezones='+query_timezones+'&before_list='+timezonecalculator_calculator_settings.before_list+'&after_list='+timezonecalculator_calculator_settings.after_list+'&format_timezone='+timezonecalculator_calculator_settings.format_timezone+'&format_datetime='+timezonecalculator_calculator_settings.format_datetime;

	var query_params=timezonecalculator_refresh_create_query_params_basis(timezonecalculator_calculator_query_params.get('_ajax_nonce'), query_string);

	query_params.put('action', 'timezonecalculator_calculator');

	timezonecalculator_refresh(params, query_params);
}

function timezonecalculator_calculator_after_calculation() {
	jQuery('#timezonecalculator_date').prop('disabled', false);
	jQuery('#timezonecalculator_hour').prop('disabled', false);
	jQuery('#timezonecalculator_minute').prop('disabled', false);
	jQuery('#timezonecalculator_continent').prop('disabled', false);
	jQuery('#timezonecalculator_timezone').prop('disabled', false);
	datePickerController.enable('timezonecalculator_date');

	jQuery('#timezonecalculator_calculate_time').prop('disabled', false);
	jQuery('#timezonecalculator_form_reset').prop('disabled', false);

	jQuery('#timezonecalculator_calculator_results .timezonecalculator-output').removeClass('timezonecalculator_spinner');
}

/*
reset button
*/

function timezonecalculator_calculator_reset_form() {
	if (jQuery('#timezonecalculator_calculator_input').css('float')=='none')
		jQuery('html, body').animate({
			scrollTop: jQuery('#timezonecalculator_calculator_input').siblings('h3').offset().top-10
		}, 500);

	jQuery('#timezonecalculator_calculator_results').hide();

	jQuery('#timezonecalculator_date').val('');
	jQuery('#timezonecalculator_hour').prop('selectedIndex', 0);
	jQuery('#timezonecalculator_minute').prop('selectedIndex', 0);

	jQuery('#timezonecalculator_continent').prop('selectedIndex', 0);
	timezonecalculator_set_timezone_array('timezonecalculator_');

	jQuery('#timezonecalculator_timezone').prop('selectedIndex', 0);
	timezonecalculator_calculator_set_default_date();
}

/*
center spinner
*/

function timezonecalculator_adapt_spinner() {
	var divid='timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id;

	if (jQuery('#'+divid).hasClass('timezonecalculator_spinner')) {
		jQuery('#'+divid).css('left', (jQuery('#'+divid).parent().width()/2)-20);

		jQuery('#'+divid).css('top', (jQuery('#'+divid).parent().height()/2)-15);
	}
}

/*
- adjusts min-height of
results-container

- behavior of containers

if viewport < 960px for collapsed menu
and < 1100px for default menu view

- shows section-links only if menu is visible

- hides calculator-page-menu and
displays only calculator

- adapts width of
input-container and results-container

if viewport < 440px

- adapt spinner
*/

function timezonecalculator_resize_calculator_page() {
	if ((jQuery(window).width()<960 && jQuery('body').hasClass('folded')) || (jQuery(window).width()<1100 && !jQuery('body').hasClass('folded'))) {
		jQuery('#timezonecalculator_calculator_results').css('min-height', 55);
		jQuery('#timezonecalculator_calculator_input, #timezonecalculator_calculator_results').css('float', 'none');
		jQuery('#timezonecalculator_calculator_input').css('margin-right', '0');
	}

	else {
		jQuery('#timezonecalculator_calculator_results').css('min-height', jQuery('#timezonecalculator_calculator_input').height());
		jQuery('#timezonecalculator_calculator_input, #timezonecalculator_calculator_results').css('float', 'left');
		jQuery('#timezonecalculator_calculator_input').css('margin-right', '20px');
	}

	if (jQuery(window).width()<440) {
		jQuery('.timezonecalculator_section_link').hide();
		jQuery('.timezonecalculator_section_text').show();

		jQuery('#timezonecalculator_menu').hide();
		jQuery('#timezonecalculator_content > div').show();
		jQuery('#timezonecalculator_selected_timezones').hide();

		jQuery('#timezonecalculator_calculator_input, #timezonecalculator_calculator_results').width(jQuery(window).width()-40);
	}

	else {
		timezonecalculator_resize_settings_page();

		jQuery('#timezonecalculator_calculator_input, #timezonecalculator_calculator_results').width(400);
	}

	if (jQuery(window).width()<300)
		jQuery('a.date-picker-control:link, a.date-picker-control:visited').css('display', 'none');

	else
		jQuery('a.date-picker-control:link, a.date-picker-control:visited').css('display', 'table-cell');

	timezonecalculator_adapt_spinner();
}
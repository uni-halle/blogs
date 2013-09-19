/*
set today's date as default
*/

function timezonecalculator_calculator_set_default_date() {
	datePickerController.setSelectedDate('timezonecalculator_date', timezonecalculator_get_today_date());
}

/*
automatically update _ajax_nonce
*/

var timezonecalculator_calculator_query_params = new Hash();

timezonecalculator_calculator_query_params.set('action', 'timezonecalculator_calculator_ajax_nonce');

timezonecalculator_calculator_query_params.set('_ajax_nonce', timezonecalculator_calculator_settings._ajax_nonce);

if (parseInt(timezonecalculator_calculator_settings.refresh_nonce, 10)==1)
	Event.observe(window, 'load', function(e){ new PeriodicalExecuter(function(pe){
		timezonecalculator_refresh(new Hash(), timezonecalculator_calculator_query_params);
		},
		600);
	});

/*
display error/success message
*/
		
function timezonecalculator_calculator_display_message(message) {

	var divid='timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id;
	Element.replace($(divid), '<div id="'+divid+'">'+message+'</div>');
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
calculate_time button
retrieve timezones via AJAX Call

display 'none yet...'
disable calculate_time button
disable form_reset button
display wait-cursor
*/

function timezonecalculator_calculator_calculation() {

	timezonecalculator_calculator_display_message('none yet...');

	var query_time=$('timezonecalculator_date').value;
	if (query_time.length<3) {
		timezonecalculator_calculator_display_message('no date entered!');
		return;
	}

	$('timezonecalculator_calculate_time').disabled='disabled';
	$('timezonecalculator_form_reset').disabled='disabled';
	$('timezonecalculator_wait_calculator').style.display='inline';

	var params = timezonecalculator_refresh_create_params('timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id, '<div id="timezonecalculator_block_'+timezonecalculator_calculator_settings.block_id+'" class="timezonecalculator-output"');

	params.set('callback_finished', timezonecalculator_calculator_after_calculation);
	params.set('callback_error', timezonecalculator_calculator_error_calculation);

	if ($('timezonecalculator_hour').selectedIndex>0 && $('timezonecalculator_minute').selectedIndex>0) {
		query_time+=$('timezonecalculator_hour').value+":"+$('timezonecalculator_minute').value;
	}

	var query_timezone=$('timezonecalculator_timezone').value;
	var query_timezones=escape($('timezonecalculator_timezones').value);

	var query_string='id='+timezonecalculator_calculator_settings.block_id+'&query_time='+query_time+'&query_timezone='+query_timezone+'&timezones='+query_timezones+'&before_list='+timezonecalculator_calculator_settings.before_list+'&after_list='+timezonecalculator_calculator_settings.after_list+'&format_timezone='+timezonecalculator_calculator_settings.format_timezone+'&format_datetime='+timezonecalculator_calculator_settings.format_datetime;

	var query_params = timezonecalculator_refresh_create_query_params_basis(timezonecalculator_calculator_query_params.get('_ajax_nonce'), query_string);

	query_params.set('action', 'timezonecalculator_calculator');

	timezonecalculator_refresh(params, query_params);
}

function timezonecalculator_calculator_after_calculation() {
	$('timezonecalculator_calculate_time').disabled=null;
	$('timezonecalculator_form_reset').disabled=null;
	$('timezonecalculator_wait_calculator').style.display='none';
}

/*
reset button
*/

function timezonecalculator_calculator_reset_form() {

	timezonecalculator_calculator_display_message('none yet...');
	$('timezonecalculator_date').value='';
	$('timezonecalculator_hour').selectedIndex=0;
	$('timezonecalculator_minute').selectedIndex=0;

	$('timezonecalculator_continent').selectedIndex=0;
	timezonecalculator_set_timezone_array('timezonecalculator_');

	$('timezonecalculator_timezone').selectedIndex=0;
	timezonecalculator_calculator_set_default_date();
}
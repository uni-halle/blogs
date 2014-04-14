function timezonecalculator_set_timezone_array(name) {
	jQuery('#'+name+'timezone').replaceWith('<select class="timezone" name="'+name+'timezone" id="'+name+'timezone">'+timezonecalculator_timezones_array[(jQuery('#'+name+'continent').prop('selectedIndex'))]+'</select>');
}
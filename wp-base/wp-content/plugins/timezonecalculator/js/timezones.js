function timezonecalculator_set_timezone_array(name) {
	Element.replace(name+'timezone', '<select class="timezone" name="'+name+'timezone" id="'+name+'timezone">'+timezonecalculator_timezones_array[($(name+'continent').selectedIndex)]+'</select');
}
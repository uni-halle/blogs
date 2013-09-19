/*
converts a boolean to 0 (false) or 1 (true)
*/

function timezonecalculator_convert_boolean_to_int(bol) {
	if (bol===true)
		return 1;
	else
		return 0;
}

/*
checks if a field is null or empty
*/

function timezonecalculator_is_field_empty(textfield) {
	if ((textfield.value.length===0) || (textfield.value===null)) {
		return true;
	}
	else {
		return false;
	}
}

/*
checks if value of textfield
is an integer
between minval and maxval
*/

function timezonecalculator_check_integer(object, minval, maxval) {

	var value=object.value;

	if (isNaN(value)) {
		alert('You did not enter a numeric value!');
		return false;
	}

	var parsed_value=parseInt(value, 10);

	if (isNaN(parsed_value) || parsed_value!=value) {
		alert('You did not enter a numeric value!');
		return false;
	}

	if (!isNaN(minval) && parsed_value < minval) {
		alert('Your entry has to be larger or equal than '+minval);
		return false;
	}

	if (!isNaN(maxval) && parsed_value > maxval) {
		alert('Your entry has to be smaller or equal than '+maxval);
		return false;
	}

	return true;

}

/*
toggle related fields
checkbox and array of fields
*/

function timezonecalculator_toggle_related_fields(element, fields, checked) {

	if (element.checked==checked) {
		for (var i=0;i<fields.length;i++) {
			$('timezonecalculator_'+fields[i]).disabled=null;
		}
	}

	else {
		for (var i=0;i<fields.length;i++) {
			$('timezonecalculator_'+fields[i]).disabled='disabled';
		}
	}
}

/*
selects a certain option-value in a html select
*/

function timezonecalculator_select_value_in_select(select, value) {
	for (var i=0; i<select.length; i++) {
		if (select[i].value == value) {
			select[i].selected = true;
		}
	}
}

/*
get today's date
*/

function timezonecalculator_get_today_date() {
	var currentTime = new Date();
	var year = currentTime.getUTCFullYear();
	var month = (currentTime.getUTCMonth()+1).toPaddedString(2);
	var day = (currentTime.getUTCDate()).toPaddedString(2);

	return year+''+month+''+day;
}
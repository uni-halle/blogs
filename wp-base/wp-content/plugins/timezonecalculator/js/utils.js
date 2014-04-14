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
displays or removes an inline error-message
*/

function timezonecalculator_inline_error(element, message, error) {
	element.siblings().remove('.error');

	if (error)
		element.after('<div class="error">'+message+'</div>');
}

/*
checks if value of an element
is an integer
between minval and maxval
*/

function timezonecalculator_check_integer(element, minval, maxval) {
	var value=element.val();

	if (isNaN(value)) {
		timezonecalculator_inline_error(element, 'You did not enter a numeric value.', true);
		return false;
	}

	var parsed_value=parseInt(value, 10);

	if (isNaN(parsed_value) || parsed_value!=value) {
		timezonecalculator_inline_error(element, 'You did not enter a numeric value.', true);
		return false;
	}

	if (!isNaN(minval) && parsed_value < minval) {
		timezonecalculator_inline_error(element, 'Your entry has to be larger or equal than '+minval+'.', true);
		return false;
	}

	if (!isNaN(maxval) && parsed_value > maxval) {
		timezonecalculator_inline_error(element, 'Your entry has to be smaller or equal than '+maxval+'.', true);
		return false;
	}

	timezonecalculator_inline_error(element, '', false);

	return true;
}

/*
toggle related fields
checkbox and array of fields
*/

function timezonecalculator_toggle_related_fields(element, fields, checked) {
	if (element.prop('checked')==checked) {
		for (var i=0;i<fields.length;i++) {
			jQuery('#timezonecalculator_'+fields[i]).prop('disabled', false);
		}
	}

	else {
		for (var i=0;i<fields.length;i++) {
			jQuery('#timezonecalculator_'+fields[i]).prop('disabled', true);
		}
	}
}

/*
selects a certain option-value in a html select
*/

function timezonecalculator_select_value_in_select(select, value) {
	if(jQuery('#'+select+' option[value="'+value+'"]').length)
		jQuery('#'+select).val(value);
}

/*
get today's date
*/

function timezonecalculator_get_today_date() {
	var currentTime = new Date();
	var year = currentTime.getUTCFullYear();
	var month = timezonecalculator_pad((currentTime.getUTCMonth()+1), 2);
	var day = timezonecalculator_pad(currentTime.getUTCDate(), 2);

	return year+''+month+''+day;
}

/*
left-padding of input value
*/

function timezonecalculator_pad(str, max) {
	return str.toString().length < max ? timezonecalculator_pad('0' + str, max) : str;
}
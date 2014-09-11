/*
conducts an Ajax refresh

params (used only locally)
 - field: the id of the field which should be updated
 - fields: all elements with the given class-name will be updated
 - compare_string: this string will be used for comparison with the result attribute in the json-response (starting at position 0)
 - callback_init
 - callback_finished(json/null)
 - callback_error

query_params (will be transferred to the server)
 - action: corresponding wp_ajax or wp_ajax_nopriv hook
 - _ajax_nonce: WordPress nonce
 - query_string: function parameters, urlencoded

error-messages:

-2 no result
-3 result does not match compare_string
-4 transport error
*/

function timezonecalculator_refresh(params, query_params) {
	jQuery.ajax({
		url: timezonecalculator_refresh_settings.ajax_url,
		cache: false,
		type: 'POST',
		data: query_params.toQueryString(),
		dataType: 'json',

		beforeSend: function(XMLHttpRequest) {
			XMLHttpRequest.params=params;
			XMLHttpRequest.query_params=query_params;

			if (params.containsKey('callback_init') && params.get('callback_init')!==null) {
				var callback_init_function=params.get('callback_init');
				window[callback_init_function()];
			}
		},

		success: function(data, textStatus, XMLHttpRequest) {
			var json=data;

			try {
				if (!timezonecalculator_is_undefined(json._ajax_nonce) && json._ajax_nonce!==null && json._ajax_nonce.length)
					XMLHttpRequest.query_params.put('_ajax_nonce', json._ajax_nonce);

				var blocks=new jQuery();

				if (XMLHttpRequest.params.containsKey('fields') && XMLHttpRequest.params.get('fields')!==null && XMLHttpRequest.params.get('fields').length)
					blocks=jQuery(XMLHttpRequest.params.get('fields'));

				var field=new jQuery();

				if (XMLHttpRequest.params.containsKey('field') && XMLHttpRequest.params.get('field')!==null && XMLHttpRequest.params.get('field').length)
					field=jQuery('#'+XMLHttpRequest.params.get('field'));

				if (blocks.length>0 || field.length>0) {
					if (timezonecalculator_is_undefined(json.result) || json.result===null || !json.result.length)
					throw -2;

					var result=json.result;

					if (XMLHttpRequest.params.containsKey('compare_string') && XMLHttpRequest.params.get('compare_string')!==null && XMLHttpRequest.params.get('compare_string').length && result.indexOf(XMLHttpRequest.params.get('compare_string'))!==0)
						throw -3;

					blocks.replaceWith(result);
					field.replaceWith(result);
				}
			}

			catch(error) {
				if (XMLHttpRequest.params.containsKey('callback_error') && XMLHttpRequest.params.get('callback_error')!==null) {
					var callback_error_function=XMLHttpRequest.params.get('callback_error');
					window[callback_error_function(error)];
				}
			}
		},

		error: function(XMLHttpRequest, textStatus, errorThrown) {
			if (XMLHttpRequest.params.containsKey('callback_error') && XMLHttpRequest.params.get('callback_error')!==null) {
				var callback_error_function=XMLHttpRequest.params.get('callback_error');
				window[callback_error_function(-4)];
			}
		},

		complete: function(XMLHttpRequest, textStatus) {
			if (XMLHttpRequest.params.containsKey('callback_finished') && XMLHttpRequest.params.get('callback_finished')!==null) {
				var callback_finished_function=XMLHttpRequest.params.get('callback_finished');

				var json;

				try {
					json=jQuery.parseJSON(XMLHttpRequest.responseText);
				}

				catch(error) {
					json=null;
				}
		
				window[callback_finished_function(json)];
			}
		}
	});

}

function timezonecalculator_refresh_create_params(field, compare_string) {
	var params=new Hashtable();

	params.put('compare_string', compare_string);
	params.put('field', field);

	return params;
}

function timezonecalculator_refresh_create_query_params_basis(_ajax_nonce, query_string) {
	var query_params=new Hashtable();

	query_params.put('_ajax_nonce', _ajax_nonce);
	query_params.put('query_string', query_string);

	return query_params;
}

function timezonecalculator_refresh_create_query_params_output(_ajax_nonce, query_string) {
	var query_params=timezonecalculator_refresh_create_query_params_basis(_ajax_nonce, query_string);

	query_params.put('action', 'timezonecalculator_output');

	return query_params;
}

function timezonecalculator_register_refresh(params, query_params) {
	window.setInterval(function(){
			timezonecalculator_refresh(params, query_params);
		},
		parseInt(timezonecalculator_refresh_settings.refresh_time, 10)*1000
	);
}

function timezonecalculator_initiate_refresh(params, query_params) {
	jQuery(window).load(function(){
		timezonecalculator_register_refresh(params, query_params);
	});
}

/*
check if variable is undefined
*/

function timezonecalculator_is_undefined(myvar) {
	return (myvar===undefined);
}

var timezonecalculator_params=new Hashtable();

var timezonecalculator_query_params=new Hashtable();

jQuery(window).load(function(){
	if (jQuery('div.timezonecalculator-refreshable-output').length>0) {
		timezonecalculator_params.put('compare_string', '<div class="timezonecalculator-refreshable-output"');

		timezonecalculator_params.put('fields', 'div.timezonecalculator-refreshable-output');

		timezonecalculator_query_params.put('action', 'timezonecalculator_output');

		timezonecalculator_query_params.put('_ajax_nonce', timezonecalculator_refresh_settings._ajax_nonce);

		timezonecalculator_register_refresh(timezonecalculator_params, timezonecalculator_query_params);
	}
});
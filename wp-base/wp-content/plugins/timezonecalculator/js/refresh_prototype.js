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

-1 no json
-2 no result
-3 result does not match compare_string
-4 transport error
*/

function timezonecalculator_refresh(params, query_params) {
	if (!Object.isUndefined(params.get('callback_init')) && params.get('callback_init')!==null) {
		var callback_init_function = params.get('callback_init');
		window[callback_init_function()];
	}

	new Ajax.Request(
		timezonecalculator_refresh_settings.ajax_url,
		{
			method: 'post',
			params: params,
			query_params: query_params,
			parameters: query_params.toQueryString(),
			evalJS: false,
			evalJSON: false,
			onSuccess: function(response) {
			if (200 == response.status) { try {
				if (Object.isUndefined(response.responseText) || response.responseText===null)
					throw -1;

				var json=response.responseText.evalJSON(true);

				if (!Object.isUndefined(json._ajax_nonce) && json._ajax_nonce!==null && json._ajax_nonce.length)
					response.request.options.query_params.set('_ajax_nonce', json._ajax_nonce);

				var blocks = new Array();

				if (!Object.isUndefined(response.request.options.params.get('fields')) && response.request.options.params.get('fields')!==null && response.request.options.params.get('fields').length)
					blocks=$$(response.request.options.params.get('fields'));

				if (!Object.isUndefined(response.request.options.params.get('field')) && response.request.options.params.get('field')!==null && response.request.options.params.get('field').length)
					blocks.push($(response.request.options.params.get('field')));

				if (blocks.length>0) {
					if (Object.isUndefined(json.result) || json.result===null || !json.result.length)
						throw -2;

					var result=json.result;

					if (!Object.isUndefined(response.request.options.params.get('compare_string')) && response.request.options.params.get('compare_string')!==null && response.request.options.params.get('compare_string').length && result.indexOf(response.request.options.params.get('compare_string'))!==0)
						throw -3;

					for (var i=0;i<blocks.length;i++)
						Element.replace($(blocks[i]), result);
				}

			}

			catch(error) {
				if (!Object.isUndefined(response.request.options.params.get('callback_error')) && response.request.options.params.get('callback_error')!==null) {
					var callback_error_function = response.request.options.params.get('callback_error');
					window[callback_error_function(error)];
				}
			}

			}

			else {
				if (!Object.isUndefined(response.request.options.params.get('callback_error')) && response.request.options.params.get('callback_error')!==null) {
					var callback_error_function = response.request.options.params.get('callback_error');
					window[callback_error_function(-4)];
				}
			}

			if (!Object.isUndefined(response.request.options.params.get('callback_finished')) && response.request.options.params.get('callback_finished')!==null) {
				var callback_finished_function = response.request.options.params.get('callback_finished');

				var json;

				try {
					json=response.responseText.evalJSON(true);
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
	var params = new Hash();

	params.set('compare_string', compare_string);
	params.set('field', field);

	return params;
}

function timezonecalculator_refresh_create_query_params_basis(_ajax_nonce, query_string) {
	var query_params = new Hash();

	query_params.set('_ajax_nonce', _ajax_nonce);
	query_params.set('query_string', query_string);

	return query_params;
}

function timezonecalculator_refresh_create_query_params_output(_ajax_nonce, query_string) {
	var query_params = timezonecalculator_refresh_create_query_params_basis(_ajax_nonce, query_string);

	query_params.set('action', 'timezonecalculator_output');

	return query_params;
}

function timezonecalculator_register_refresh(params, query_params) {
	new PeriodicalExecuter(function(pe){
		timezonecalculator_refresh(params, query_params); },
		parseInt(timezonecalculator_refresh_settings.refresh_time, 10));
}

function timezonecalculator_initiate_refresh(params, query_params) {
	Event.observe(window, 'load', function(e){
		timezonecalculator_register_refresh(params, query_params);
	});
}

var timezonecalculator_params = new Hash();

var timezonecalculator_query_params = new Hash();

Event.observe(window, 'load', function(e){
	if ($$('div.timezonecalculator-refreshable-output').length>0) {

			timezonecalculator_params.set('compare_string', '<div class="timezonecalculator-refreshable-output"');

			timezonecalculator_params.set('fields', 'div.timezonecalculator-refreshable-output');

			timezonecalculator_query_params.set('action', 'timezonecalculator_output');

			timezonecalculator_query_params.set('_ajax_nonce', timezonecalculator_refresh_settings._ajax_nonce);

			timezonecalculator_register_refresh(timezonecalculator_params, timezonecalculator_query_params);
	}
});
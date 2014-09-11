/*
 * this file is going to be magic
 * for all N-Media plugins/themes 
 */

/*
 * extract data from html elements
 */

function extractElementData(elements) {

	var data = new Object;

	data.bug = false;
	jQuery.each(elements,
			function(i, item) {

			if(item.req == undefined || item.req == 0){
				item.req = false;
				
			}else{
				item.req = true;
				
			}
			
				switch (item.type) {
				
				case 'text':

					data[i] = jQuery("input[name^='" + i + "']").val();
					if(jQuery("input[name^='" + i + "']").val() == '' && item.req){
						jQuery("input[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;

				case 'select':

					data[i] = jQuery("select[name^='" + i + "']").val();
					if(jQuery("select[name^='" + i + "']").val() == '' && item.req){
						jQuery("select[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;

				case 'checkbox':
					
					var checkedVals = [];
					jQuery('input:checkbox[name^="' + i + '"]:checked').each(function() {
						checkedVals.push(jQuery(this).val());
					});
					
					data[i] = (checkedVals.length == 0) ? null : checkedVals;
					
					if (!jQuery("input:checkbox[name^='" + i + "']").is(':checked') && item.req){
						
						jQuery("input:checkbox[name^='" + i + "']").parent('label').css('color', 'red');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
						
					break;

				case 'radio':

					data[i] = jQuery(
							"input:radio[name^='" + i + "']:checked").val();
					if (!jQuery("input:radio[name^='" + i + "']").is(':checked') && item.req){
											
						jQuery("input:radio[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						alert(item.type+' is required');
					}
					break;
					
				case 'textarea':

					data[i] = jQuery("textarea[name^='" + i + "']").val();
					
					if(jQuery("textarea[name^='" + i + "']").val() == '' && item.req){
						jQuery("textarea[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;
				}

			});

	return data;
}


/*
 * function checking the checkbox for current value
 * current value: json object
 * @Author: Najeeb
 * 13 Oct, 2012
 */

function setChecked(elementName, currentValue){
	
	var elementCB = jQuery('input:checkbox[name="' + elementName + '"]');
	
	var currentValues = jQuery.parseJSON(currentValue);
	
	
	//console.log(currentValues);
	
	jQuery.each(elementCB, function(i, item){
		
		//console.log(item.id);
		var current_cb_id = item.id;
		
		jQuery.each(currentValues, function(i, item){
			
			//console.log(item + jQuery("#"+current_cb_id).attr('value'));
			if(jQuery("#"+current_cb_id).attr('value') == item){
				
				jQuery("#"+current_cb_id).attr('checked', true);
			}else{
				if ( jQuery("#"+current_cb_id).attr('checked') == true)
					jQuery("#"+current_cb_id).attr('checked', false);
			}
			//jQuery('input:checkbox[value="' + item + '"]').attr("checked", "checked");
		});
	});
	
	
	
}

/*
 * function checking the RADIO for current value
 * current value: single
 * @Author: Najeeb
 * 3 July, 2012
 */

function setCheckedRadio(elementName, currentValue) {

	var elementRadio = jQuery('input:radio[name="' + elementName + '"]');

	//console.log(elementRadio);
	jQuery.each(elementRadio, function(i, item) {

		//console.log(item.id);
		var current_radio_id = item.id;
		
		if (jQuery("#" + current_radio_id).attr('value') == currentValue) {

			jQuery("#" + current_radio_id).attr('checked', true);
		} else {
			if (jQuery("#" + current_radio_id).attr('checked') == true)
				jQuery("#" + current_radio_id).attr('checked', false);
		}
						
	});

}




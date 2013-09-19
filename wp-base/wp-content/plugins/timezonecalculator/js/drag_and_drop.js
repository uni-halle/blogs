var timezonecalculator_edit_abbr_fields = ["edit_abbr_standard", "edit_abbr_daylightsaving"];
var timezonecalculator_edit_name_fields = ["edit_name_standard", "edit_name_daylightsaving"];

/*
toggles the visibility of an element
*/

function timezonecalculator_toggle_element(link, element) {
	if ($(element).style.display=='none') {
		$(element).style.display='block';
		$(link).alt='hide details';
	}
	else {
		$(element).style.display='none';
		$(link).alt='show details';
	}
}

/*
open timeanddate.com in a
pop-up window
*/

function timezonecalculator_search_timeanddate_open_window() {
	window.open('http://www.timeanddate.com/search/results.html?query='+$('timezonecalculator_search_timeanddate_query').value,'timeanddate','width=600,height=400,top=200,left=200,toolbar=yes,location=yes,directories=np,status=yes,menubar=no,scrollbars=yes,copyhistory=no,resizable=yes');
}

/*
moves an element in a drag and drop list
one position up

modified by Nikk Folts, http://www.nikkfolts.com/
*/

function timezonecalculator_move_element_up_for_list(list, row) {
	return timezonecalculator_move_row(list, row, 1);
}

/*
moves an element in a drag and drop list
one position down

modified by Nikk Folts, http://www.nikkfolts.com/
*/

function timezonecalculator_move_element_down_for_list(list, row) {
	return timezonecalculator_move_row(list, row, -1);
}

/*
moves an element in a drag and drop list
one position

modified by Nikk Folts, http://www.nikkfolts.com/
*/

function timezonecalculator_move_row(list, row, dir) {
	var sequence=Sortable.sequence(list);
	var found=false;

	/*
	move only, if there is more than
	one element in the list
	*/

	if (sequence.length>1) for (var j=0; j<sequence.length; j++) {

		/*
		element found
		*/

		if (sequence[j]==row) {
			found=true;

			var i = j - dir;

			if (i >= 0 && i <= sequence.length) {
				var temp=sequence[i];
				sequence[i]=row;
				sequence[j]=temp;
				break;
			}
		}
	}

	Sortable.setSequence(list, sequence);

	return found;
}

/*
handles moving up for both lists
*/

function timezonecalculator_move_element_up(key) {

	/*
	try to move the element in first list
	*/

	if (timezonecalculator_move_element_up_for_list('timezonecalculator_list_selected', key)===false) {

		/*
		if we didn't find it, try
		to move the element in the
		second list
		*/

		timezonecalculator_move_element_up_for_list('timezonecalculator_list_available', key);
	}

	/*
	update the lists
	*/

	timezonecalculator_update_drag_and_drop_lists();
}

/*
handles moving down for both lists
*/

function timezonecalculator_move_element_down(key) {

	/*
	try to move the element in first list
	*/

	if (timezonecalculator_move_element_down_for_list('timezonecalculator_list_selected', key)===false) {

		/*
		if we didn't find it, try
		to move the element in the
		second list
		*/

		timezonecalculator_move_element_down_for_list('timezonecalculator_list_available', key);
	}

	/*
	update the lists
	*/

	timezonecalculator_update_drag_and_drop_lists();
}

/*
initializes or reinitializes the
drag_and_drop lists
*/

function timezonecalculator_initialize_drag_and_drop() {

	Sortable.create("timezonecalculator_list_selected", {
		dropOnEmpty:true,
		containment:["timezonecalculator_list_selected", "timezonecalculator_list_available"],
		constraint:false,
		onUpdate:function(){
			timezonecalculator_update_drag_and_drop_lists();
		}
	});

	/*
	as we have two lists,
	the second list will be
	automatically updated
	if the first list is updated
	*/

	Sortable.create("timezonecalculator_list_available", {
		dropOnEmpty:true,
		containment:["timezonecalculator_list_selected", "timezonecalculator_list_available"],
		constraint:false
	});
}

/*
returns the sorted ids of a
drag_and_drop list
*/

function timezonecalculator_get_sorted_ids(list) {

	/*
	get current timezones order
	*/

	var list=escape(Sortable.sequence('timezonecalculator_list_'+list));

	var sorted_ids = [-1];

	/*
	if we got at least one element
	in the list,
	retrieve the sorted_ids
	*/

	if (list && list.length>0) {
		var maybe_sorted_ids = unescape(list).split(',');
		var ret_sorted_ids=[];

		/*
		loop through all ids and
		filter out empty elements
		*/

		for (var i=0;i<maybe_sorted_ids.length;i++) {
			if (maybe_sorted_ids[i] && maybe_sorted_ids[i]>-1) {
				ret_sorted_ids.push(maybe_sorted_ids[i]);
			}
		}

		if (ret_sorted_ids.length>0)
			sorted_ids=ret_sorted_ids;
	}

	return sorted_ids;
}

/*
sets list height of drag_and_drop list
according to the number of elements
*/

function timezonecalculator_set_list_height(list, sorted_ids) {

	var element_height=32;

	/*
	calculate new list height of list_selected
	*/

	var list_length=element_height;

	if (sorted_ids.length>1)
		list_length=sorted_ids.length*element_height;

	/*
	set new list height
	*/

	$('timezonecalculator_list_'+list).style.height = list_length+'px';
}

/*
drag and drop lists update function
updates timezones textbox
*/

function timezonecalculator_update_drag_and_drop_lists() {

	var selected_sorted_ids=timezonecalculator_get_sorted_ids('selected');

	/*
	clear current entries in textarea
	*/

	$('timezonecalculator_timezones').value='';

	/*
	loop through all selected_sorted_ids
	and append them to the textarea
	*/

	for (var i = 0; i<selected_sorted_ids.length; i++) {

		/*
		check for id
		*/

		if (selected_sorted_ids[i]!=-1) {

			/*
			get value of timestamp-entry
			and remove blank at the end
			*/

			var timezone_from_element=($('timezonecalculator_timezone_'+selected_sorted_ids[i]).childNodes[2].nodeValue).split('\n');

			/*
			retrieve current value of
			timezones-textarea
			*/

			var old_value=$('timezonecalculator_timezones').value;

			/*
			append retrieved entry
			to existing entries
			in timezones-textarea
			*/

			$('timezonecalculator_timezones').value = old_value+timezone_from_element[0]+';'+($('timezonecalculator_timezone_'+selected_sorted_ids[i]).childNodes[3].value)+"\n";
		}
	}

	/*
	set lists-heights
	*/

	timezonecalculator_set_list_height('selected', selected_sorted_ids);

	var available_sorted_ids=timezonecalculator_get_sorted_ids('available');

	timezonecalculator_set_list_height('available', available_sorted_ids);
}

/*
load selected field in edit panel
populate timezone attributes
*/

function timezonecalculator_populate_drag_and_drop (key) {

	/*
	reset form
	*/

	timezonecalculator_reset_edit_form();

	/*
	hide message + change button text
	*/

	$('timezonecalculator_edit_success_label').style.display='none';
	$('timezonecalculator_edit_create').value='Edit';

	/*
	set hidden id of timezone-entry
	*/

	$('timezonecalculator_edit_selected_timezone').value=key;

	/*
	get entry from drag_and_drop list
	visible value + hidden field
	*/

	var timezone_from_element=$('timezonecalculator_timezone_'+key).childNodes[2].nodeValue+';'+$('timezonecalculator_timezone_'+key).childNodes[3].value;

	/*
	split into attributes
	*/

	var timezone_from_element_attributes=timezone_from_element.split(';');

	/*
	set values of edit fields
	*/

	$('timezonecalculator_edit_timezone').selectedIndex=0;

	/*
	split continent and timzone
	*/

	var timezone_id_attributes=timezone_from_element_attributes[0].split('/');

	/*
	select value of continents select
	*/

	timezonecalculator_select_value_in_select($('timezonecalculator_edit_continent'), timezone_id_attributes[0]);

	/*
	set options of timezones-array
	to match selected continent
	*/

	timezonecalculator_set_timezone_array('timezonecalculator_edit_');

	/*
	select value of timezones select
	*/

	timezonecalculator_select_value_in_select($('timezonecalculator_edit_timezone'), timezone_from_element_attributes[0]);

	/*
	do not use db_abbrevations
	*/

	if (timezone_from_element_attributes[5]!=1) {
		$('timezonecalculator_edit_use_db_abbreviations').checked='';
		$('timezonecalculator_'+timezonecalculator_edit_abbr_fields[0]).value=timezone_from_element_attributes[1];
		$('timezonecalculator_'+timezonecalculator_edit_abbr_fields[1]).value=timezone_from_element_attributes[2];
	}

	/*
	use db_abbrevations
	*/

	else {
		$('timezonecalculator_edit_use_db_abbreviations').checked='checked';
	}

	timezonecalculator_toggle_related_fields($('timezonecalculator_edit_use_db_abbreviations'), timezonecalculator_edit_abbr_fields, false);

	/*
	do not use db_names
	*/

	if (timezone_from_element_attributes[6]!=1) {
		$('timezonecalculator_edit_use_db_names').checked='';
		$('timezonecalculator_'+timezonecalculator_edit_name_fields[0]).value=timezone_from_element_attributes[3];
		$('timezonecalculator_'+timezonecalculator_edit_name_fields[1]).value=timezone_from_element_attributes[4];
	}

	/*
	use db_names
	*/

	else {
		$('timezonecalculator_edit_use_db_names').checked='checked';
	}

	timezonecalculator_toggle_related_fields($('timezonecalculator_edit_use_db_names'), timezonecalculator_edit_name_fields, false);

	$('timezonecalculator_edit_continent').focus();
}

/*
append created entry to textarea or
apply changes of currently selected timezone
*/

function timezonecalculator_change_or_append_entry() {

	/*
	get hidden id
	*/

	var selected_entry=$('timezonecalculator_edit_selected_timezone').value;

	/*
	hide success-label
	*/

	$('timezonecalculator_edit_success_label').style.display='none';

	/*
	errormsg
	*/

	var errormsg="";

	/*
	validity check for abbrevation fields
	*/

	for (var i=0; i<timezonecalculator_edit_abbr_fields.length; i++) {

		/*
		check for ; in all fields
		because we
		don't want to break
		the timezones-syntax
		*/

		if ($('timezonecalculator_'+timezonecalculator_edit_abbr_fields[i]).value.indexOf(';')>-1)
			errormsg+="\n - Semicolons are not allowed in Field "+timezonecalculator_edit_abbr_fields[i];
	}

	/*
	validity check for name fields
	*/

	for (var i=0; i<timezonecalculator_edit_name_fields.length; i++) {

		/*
		check for ; in all fields
		because we
		don't want to break
		the timezones-syntax
		*/

		if ( $('timezonecalculator_'+timezonecalculator_edit_name_fields[i]).value.indexOf(';')>-1)
			errormsg+="\n - Semicolons are not allowed in Field "+timezonecalculator_edit_name_fields[i];
	}

	/*
	do we have an error so far?
	*/

	if (errormsg.length===0) {		

		/*
		store retrieved information in variables;
		*/

		/*
		timezone-id
		*/

		var edit_timezone_id=$('timezonecalculator_edit_timezone').options[ $('timezonecalculator_edit_timezone').selectedIndex ].value;

		/*
		use_db_abbreviations
		*/

		var edit_use_db_abbreviations= timezonecalculator_convert_boolean_to_int($('timezonecalculator_edit_use_db_abbreviations').checked);

		/*
		abbr-fields
		*/

		var edit_abbr_standard='';
		var edit_abbr_daylightsaving='';

		/*
		use value of abbr-fields only
		if use_db_abbrs has been
		unselected
		*/

		if (edit_use_db_abbreviations===0) {
			edit_abbr_standard= $('timezonecalculator_edit_abbr_standard').value;
			edit_abbr_daylightsaving= $('timezonecalculator_edit_abbr_daylightsaving').value;
		}

		/*
		name-fields
		*/

		var edit_name_standard='';
		var edit_name_daylightsaving='';

		/*
		use value of name-fields only
		if use_db_names has been
		unselected
		*/

		var edit_use_db_names= timezonecalculator_convert_boolean_to_int($('timezonecalculator_edit_use_db_names').checked);

		if (edit_use_db_names===0) {
			edit_name_standard= $('timezonecalculator_edit_name_standard').value;
			edit_name_daylightsaving= $('timezonecalculator_edit_name_daylightsaving').value;
		}

		/*
		built timezone-entry
		*/

		var ret=edit_abbr_standard+";"+edit_abbr_daylightsaving+";"+edit_name_standard+";"+edit_name_daylightsaving+";"+edit_use_db_abbreviations+";"+edit_use_db_names;

		/*
		change timezone attributes
		*/

		if (selected_entry.length>0) {

			/*
			visible timezone-id
			*/

			$('timezonecalculator_timezone_'+selected_entry).childNodes[2].nodeValue=edit_timezone_id;

			/*
			hidden value
			*/
			$('timezonecalculator_timezone_'+selected_entry).childNodes[3].value=ret;

			/*
			update drag and drop lists
			and show user info
			*/

			timezonecalculator_update_drag_and_drop_lists();

			new Effect.Highlight($('timezonecalculator_timezone_'+selected_entry),{startcolor:'#30df8b'});
		}

		/*
		insert new timezone
		*/

		else {
			var next_tag_id=0;

			/*
			if timezones are available
			in at least one of both lists,
			get max tag id of both lists
			*/

			var list_selected_sorted_ids = timezonecalculator_get_sorted_ids('selected');
			var list_available_sorted_ids = timezonecalculator_get_sorted_ids('available');

			/*
			does at least one list have entries?
			*/

			if (list_selected_sorted_ids[0]!=-1 || list_available_sorted_ids[0]!=-1) {

				var last_tag_id=0;

				/*
				get max tag id from list_selected
				*/

				for (var j = 0; j < list_selected_sorted_ids.length; j++) {
					last_tag_id=Math.max(last_tag_id, parseInt(list_selected_sorted_ids[j], 10));
				}

				/*
				get max tag id from list_available
				*/

				for (var j = 0; j < list_available_sorted_ids.length; j++) {
					last_tag_id=Math.max(last_tag_id, parseInt(list_available_sorted_ids[j], 10));
				}

				next_tag_id=last_tag_id+1;
			}

			/*
			insert new timezone
			into drag and drop list
			*/

			var up_arrow='<img class="timezonecalculator_arrowbutton" src="'+timezonecalculator_settings.plugin_url+'arrow_up_blue.png" onclick="timezonecalculator_move_element_up('+next_tag_id+');" alt="move element up" />';
			var down_arrow='<img class="timezonecalculator_arrowbutton" style="margin-right:20px;" src="'+timezonecalculator_settings.plugin_url+'arrow_down_blue.png" onclick="timezonecalculator_move_element_down('+next_tag_id+');" alt="move element down" />';

			var options='<input type="hidden" value="" />';

			var new_element='<li class="timezonecalculator_sortablelist" id="timezonecalculator_timezone_'+next_tag_id+'">'+up_arrow+down_arrow+edit_timezone_id+options+'</li>';
			new Insertion.Bottom('timezonecalculator_list_selected',new_element);

			Event.observe('timezonecalculator_timezone_'+next_tag_id, 'click', function(e){ timezonecalculator_populate_drag_and_drop(next_tag_id); });

			timezonecalculator_initialize_drag_and_drop();

			/*
			hidden value
			*/
			$('timezonecalculator_timezone_'+next_tag_id).childNodes[3].value=ret;

			/*
			update drag_and_drop lists
			*/

			timezonecalculator_update_drag_and_drop_lists();
			new Effect.Highlight($('timezonecalculator_timezone_'+next_tag_id),{startcolor:'#30df8b'});

		}

		/*
		user information
		*/

		new Effect.Highlight($('timezonecalculator_edit'),{startcolor:'#30df8b'});
		new Effect.Appear($('timezonecalculator_edit_success_label'));
	}

	/*
	display error message
	*/

	else {
		new Effect.Highlight($('timezonecalculator_edit'),{startcolor:'#FF0000'});
		alert('The following error(s) occured:'+errormsg);
	}

}

/*
reset edit form
*/

function timezonecalculator_reset_edit_form() {
	$('timezonecalculator_edit_continent').selectedIndex=0;

	timezonecalculator_set_timezone_array('timezonecalculator_edit_');

	$('timezonecalculator_edit_timezone').selectedIndex=0;
	$('timezonecalculator_edit_selected_timezone').value='';
	$('timezonecalculator_edit_create').value='Insert';

	$('timezonecalculator_edit_success_label').style.display='none';

	$('timezonecalculator_edit_use_db_abbreviations').checked='checked';

	timezonecalculator_toggle_related_fields($('timezonecalculator_edit_use_db_abbreviations'), timezonecalculator_edit_abbr_fields, false);

	for (var i = 0; i<timezonecalculator_edit_abbr_fields.length; i++) {
		$('timezonecalculator_'+timezonecalculator_edit_abbr_fields[i]).value='';
	}


	$('timezonecalculator_edit_use_db_names').checked='checked';

	timezonecalculator_toggle_related_fields($('timezonecalculator_edit_use_db_names'), timezonecalculator_edit_name_fields, false);

	for (var i = 0; i<timezonecalculator_edit_name_fields.length; i++) {
		$('timezonecalculator_'+timezonecalculator_edit_name_fields[i]).value='';
	}
}
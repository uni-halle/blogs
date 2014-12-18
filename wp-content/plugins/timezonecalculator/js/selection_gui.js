/*
toggles the visibility of an element
*/

function timezonecalculator_toggle_element(link, element) {
	if (jQuery('#'+element).css('display')=='none') {
		jQuery('#'+element).show();
		jQuery(link).attr('title', 'hide details');
		jQuery(link).removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
	}
	else {
		jQuery('#'+element).hide();
		jQuery(link).attr('title', 'show details');
		jQuery(link).removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
	}
}

/*
moves an element
in a list
one position up
*/

function timezonecalculator_move_element_up(row) {

	/*
	move the element up
	*/

	var current=jQuery('#timezonecalculator_timezone_'+row);
	current.prev().before(current);

	/*
	update the lists
	*/

	timezonecalculator_update_lists();
}

/*
moves an element
in a list
one position down
*/

function timezonecalculator_move_element_down(row) {

	/*
	move the element down
	*/

	var current=jQuery('#timezonecalculator_timezone_'+row);
	current.next().after(current);

	/*
	update the lists
	*/

	timezonecalculator_update_lists();
}

/*
moves an element
to the other list
*/

function timezonecalculator_move_element(row) {

	/*
	move the element to other list
	*/

	var current=jQuery('#timezonecalculator_timezone_'+row);

	if (current.parent('ul').attr('id')=='timezonecalculator_list_selected')
		current.appendTo('#timezonecalculator_list_available');

	else if (current.parent('ul').attr('id') =='timezonecalculator_list_available')
		current.appendTo('#timezonecalculator_list_selected');

	/*
	update the lists
	*/

	timezonecalculator_update_lists();
}

/*
initializes the
drag and drop lists
*/

function timezonecalculator_initialize_drag_and_drop() {

	/*
	add sortable to
	both lists
	*/

	jQuery(function() {
		jQuery('#timezonecalculator_list_selected, #timezonecalculator_list_available').sortable({
			connectWith: '.timezonecalculator_sortablelist'
		}).disableSelection();
	});

	/*
	add event handlers
	to watch update
	on list_selected

	- stop
	- receive
	- remove
	*/

	jQuery('#timezonecalculator_list_selected').on('sortstop sortreceive sortremove', function() {
		timezonecalculator_update_lists();
	});
}

/*
returns the sorted ids of a
timezones list
*/

function timezonecalculator_get_sorted_ids(list) {

	/*
	get current timezones order
	*/

	var maybe_sorted_ids=jQuery('#timezonecalculator_list_'+list).sortable('toArray');

	var sorted_ids=[-1];

	/*
	if we got at least one element
	in the list,
	retrieve the sorted_ids
	*/

	if (maybe_sorted_ids && maybe_sorted_ids.length>0) {
		var ret_sorted_ids=[];

		/*
		loop through all ids and
		filter out empty elements
		*/

		for (var i=0; i<maybe_sorted_ids.length; i++) {
			maybe_sorted_ids[i]=maybe_sorted_ids[i].replace('timezonecalculator_timezone_', '');

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
sets list height of timezones list
according to the number of elements
*/

function timezonecalculator_set_list_height(list, sorted_ids) {
	var element_height=34;

	/*
	calculate new list height of list_selected
	*/

	var list_length=element_height;

	if (sorted_ids.length>1)
		list_length=sorted_ids.length*element_height;

	/*
	set new list height
	*/

	jQuery('#timezonecalculator_list_'+list).height(list_length);
}

/*
timezones lists update function
updates timezones textarea
*/

function timezonecalculator_update_lists() {

	/*
	get sorted ids of
	list selected
	*/

	var selected_sorted_ids=timezonecalculator_get_sorted_ids('selected');

	/*
	clear current entries in textarea
	*/

	jQuery('#timezonecalculator_timezones').val('');

	/*
	loop through all selected_sorted_ids
	and append them to the textarea
	*/

	for (var i=0; i<selected_sorted_ids.length; i++) {

		/*
		check for id
		*/

		if (selected_sorted_ids[i]!=-1) {

			/*
			get value of timestamp-entry
			and remove blank at the end
			*/

			var timezone_from_element=(jQuery('#timezonecalculator_timezone_'+selected_sorted_ids[i]+' span').text()).split('\n');

			/*
			retrieve current value of
			timezones-textarea
			*/

			var old_value=jQuery('#timezonecalculator_timezones').val();

			/*
			append retrieved entry
			to existing entries
			in timezones-textarea
			*/

			jQuery('#timezonecalculator_timezones').val(old_value+timezone_from_element[0]+';'+(jQuery('#timezonecalculator_timezone_'+selected_sorted_ids[i]+' input').val())+"\n");
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
and populate timezone attributes
*/

function timezonecalculator_populate_list_edit(key) {

	/*
	reset form
	*/

	timezonecalculator_reset_edit_form();

	/*
	change button text
	*/

	jQuery('#timezonecalculator_edit_create').val('Edit');

	/*
	set hidden id of timezone-entry
	*/

	jQuery('#timezonecalculator_edit_selected_timezone').val(key);

	/*
	mark entry in list
	*/

	jQuery('#timezonecalculator_timezone_'+key).addClass('timezonecalculator_sortablelist_active');

	/*
	get entry from timezones list
	visible value and hidden field
	*/

	var timezone_from_element=jQuery('#timezonecalculator_timezone_'+key+' span').text()+';'+jQuery('#timezonecalculator_timezone_'+key+' input').val();

	/*
	split into attributes
	*/

	var timezone_from_element_attributes=timezone_from_element.split(';');

	/*
	split continent and timzone
	*/

	var timezone_id_attributes=timezone_from_element_attributes[0].split('/');

	/*
	select value of continents select
	*/

	timezonecalculator_select_value_in_select('timezonecalculator_edit_continent', timezone_id_attributes[0]);

	/*
	set options of timezones-array
	to match selected continent
	*/

	timezonecalculator_set_timezone_array('timezonecalculator_edit_');

	/*
	select value of timezones select
	*/

	timezonecalculator_select_value_in_select('timezonecalculator_edit_timezone', timezone_from_element_attributes[0]);

	/*
	in case
	db_abbrevations
	are not used
	*/

	if (timezone_from_element_attributes[5]!=1) {
		jQuery('#timezonecalculator_edit_use_db_abbreviations').prop('checked', false);
		jQuery('#timezonecalculator_'+timezonecalculator_edit_abbr_fields[0]).val(timezone_from_element_attributes[1]);
		jQuery('#timezonecalculator_'+timezonecalculator_edit_abbr_fields[1]).val(timezone_from_element_attributes[2]);
	}

	/*
	in case
	db_abbrevations
	are used
	*/

	else {
		jQuery('#timezonecalculator_edit_use_db_abbreviations').prop('checked', true);
	}

	timezonecalculator_toggle_related_fields(jQuery('#timezonecalculator_edit_use_db_abbreviations'), timezonecalculator_edit_abbr_fields, false);

	/*
	in case
	db_names
	are not used
	*/

	if (timezone_from_element_attributes[6]!=1) {
		jQuery('#timezonecalculator_edit_use_db_names').prop('checked', false);
		jQuery('#timezonecalculator_'+timezonecalculator_edit_name_fields[0]).val(timezone_from_element_attributes[3]);
		jQuery('#timezonecalculator_'+timezonecalculator_edit_name_fields[1]).val(timezone_from_element_attributes[4]);
	}

	/*
	in case
	db_names
	are used
	*/

	else {
		jQuery('#timezonecalculator_edit_use_db_names').prop('checked', true);
	}

	timezonecalculator_toggle_related_fields(jQuery('#timezonecalculator_edit_use_db_names'), timezonecalculator_edit_name_fields, false);
}

/*
append created entry to textarea or
apply changes of currently selected timezone
*/

function timezonecalculator_change_or_append_entry() {

	/*
	get hidden id
	*/

	var selected_entry=jQuery('#timezonecalculator_edit_selected_timezone').val();

	/*
	check for errors
	*/

	if (jQuery('#timezonecalculator_edit').find('.error').length==0) {

		/*
		store retrieved information in variables
		*/

		/*
		timezone-id
		*/

		var edit_timezone_id=jQuery('#timezonecalculator_edit_timezone').val();

		/*
		use_db_abbreviations
		*/

		var edit_use_db_abbreviations=timezonecalculator_convert_boolean_to_int(jQuery('#timezonecalculator_edit_use_db_abbreviations').prop('checked'));

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
			edit_abbr_standard=jQuery('#timezonecalculator_edit_abbr_standard').val();
			edit_abbr_daylightsaving=jQuery('#timezonecalculator_edit_abbr_daylightsaving').val();
		}

		/*
		use_name_abbreviations
		*/

		var edit_use_db_names=timezonecalculator_convert_boolean_to_int(jQuery('#timezonecalculator_edit_use_db_names').prop('checked'));

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

		if (edit_use_db_names===0) {
			edit_name_standard=jQuery('#timezonecalculator_edit_name_standard').val();
			edit_name_daylightsaving=jQuery('#timezonecalculator_edit_name_daylightsaving').val();
		}

		/*
		built timezone-entry
		*/

		var ret=edit_abbr_standard+';'+edit_abbr_daylightsaving+';'+edit_name_standard+';'+edit_name_daylightsaving+';'+edit_use_db_abbreviations+';'+edit_use_db_names;

		/*
		change timezone attributes
		*/

		if (selected_entry.length>0) {

			/*
			visible timezone-id
			*/

			jQuery('#timezonecalculator_timezone_'+selected_entry+' span').text(edit_timezone_id);

			/*
			hidden value
			*/

			jQuery('#timezonecalculator_timezone_'+selected_entry+' input').val(ret);
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

			var list_selected_sorted_ids=timezonecalculator_get_sorted_ids('selected');
			var list_available_sorted_ids=timezonecalculator_get_sorted_ids('available');

			/*
			does at least one list have entries?
			*/

			if (list_selected_sorted_ids[0]!=-1 || list_available_sorted_ids[0]!=-1) {

				var last_tag_id=0;

				/*
				get max tag-id from list_selected
				*/

				for (var i=0; i<list_selected_sorted_ids.length; i++) {
					last_tag_id=Math.max(last_tag_id, parseInt(list_selected_sorted_ids[i], 10));
				}

				/*
				get max tag-id from list_available
				*/

				for (var i=0; i<list_available_sorted_ids.length; i++) {
					last_tag_id=Math.max(last_tag_id, parseInt(list_available_sorted_ids[i], 10));
				}

				next_tag_id=last_tag_id+1;
			}

			/*
			insert new timezone
			into timezones list
			*/

			var up_arrow='<div role="button" class="timezonecalculator_dashicons dashicons dashicons-arrow-up" onclick="timezonecalculator_move_element_up('+next_tag_id+');" title="move element up"></div>';
			var down_arrow='<div role="button" class="timezonecalculator_dashicons dashicons dashicons-arrow-down" style="margin-right:5px;" onclick="timezonecalculator_move_element_down('+next_tag_id+');" title="move element down"></div>';
			var move_arrow='<div role="button" class="timezonecalculator_dashicons dashicons dashicons-leftright" style="margin-right:15px;" onclick="timezonecalculator_move_element('+next_tag_id+');" title="move element to other list"></div>';

			var options='<input type="hidden" value="'+ret+'" />';

			var new_element='<li class="timezonecalculator_sortablelist" id="timezonecalculator_timezone_'+next_tag_id+'">'+up_arrow+down_arrow+move_arrow+'<span>'+edit_timezone_id+'</span>'+options+'</li>';
			jQuery('#timezonecalculator_list_selected').append(new_element);

			jQuery('#timezonecalculator_timezone_'+next_tag_id).click(function(){ timezonecalculator_populate_list_edit(next_tag_id); });

			/*
			we select the newly
			created entry
			*/

			selected_entry=next_tag_id;
		}

		/*
		populate edit panel as
		instant feedback
		to changed values
		*/

		timezonecalculator_populate_list_edit(selected_entry);

		/*
		update timezones lists
		*/

		timezonecalculator_update_lists();

		/*
		user feedback
		*/

		jQuery('#timezonecalculator_timezone_'+selected_entry).effect('highlight', {color:'#30df8b'}, 1000);
		jQuery('#timezonecalculator_edit').effect('highlight', {color:'#30df8b'}, 1000);
	}

	/*
	signalize error
	*/

	else
		jQuery('#timezonecalculator_edit').effect('highlight', {color:'#FF0000'}, 1000);
}

/*
reset edit form
*/

function timezonecalculator_reset_edit_form() {
	jQuery('#timezonecalculator_edit').find('.error').remove();
	jQuery('#timezonecalculator_edit_create').prop('disabled', false);

	jQuery('#timezonecalculator_selection_gui .timezonecalculator_sortablelist, #timezonecalculator_selected_timezones .timezonecalculator_sortablelist').removeClass('timezonecalculator_sortablelist_active');

	jQuery('#timezonecalculator_edit_continent').prop('selectedIndex', 0);

	timezonecalculator_set_timezone_array('timezonecalculator_edit_');

	jQuery('#timezonecalculator_edit_timezone').prop('selectedIndex', 0);

	jQuery('#timezonecalculator_edit_selected_timezone').val('');

	jQuery('#timezonecalculator_edit_create').val('Insert');

	jQuery('#timezonecalculator_edit_use_db_abbreviations').prop('checked', true);

	timezonecalculator_toggle_related_fields(jQuery('#timezonecalculator_edit_use_db_abbreviations'), timezonecalculator_edit_abbr_fields, false);

	for (var i=0; i<timezonecalculator_edit_abbr_fields.length; i++)
		jQuery('#timezonecalculator_'+timezonecalculator_edit_abbr_fields[i]).val('');

	jQuery('#timezonecalculator_edit_use_db_names').prop('checked', true);

	timezonecalculator_toggle_related_fields(jQuery('#timezonecalculator_edit_use_db_names'), timezonecalculator_edit_name_fields, false);

	for (var i=0; i<timezonecalculator_edit_name_fields.length; i++)
		jQuery('#timezonecalculator_'+timezonecalculator_edit_name_fields[i]).val('');
}
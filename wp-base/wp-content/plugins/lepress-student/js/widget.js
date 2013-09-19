var visible_id = false;
jQuery(document).click(function(event) {
    var rel = jQuery(event.target).attr('rel');
    if(rel != visible_id && typeof rel != "undefined") {
        jQuery('#'+visible_id).hide();
        jQuery('#'+rel).show();
        visible_id = rel;
        return true;
    }
    if(!visible_id) {
        jQuery('#'+rel).show();
        visible_id = rel;
     }  else {
        jQuery('#'+visible_id).hide();
        visible_id = false;
     }
});


function change_course_stnt( widget_id, ajax_handler ){
	var course_id = document.getElementById( 'widget-' + widget_id + '-course_id' ).options[document.getElementById( 'widget-' + widget_id + "-course_id" ).selectedIndex].value;
	
	//write ajax req using wp jquery
	jQuery.ajax({
		url: ajax_handler,
		type: 'get',
		data: 'action=getLePressStudentWidgetBody&course_id=' + course_id + '&widget_id=' + widget_id,
		dataType: 'html',
		error: function( xmlHttpReq, textStatus, errorThrown  ){
			alert( 'ERROR: ' + textStatus + '\n eT: ' + errorThrown );
		},
		success: function( response ){ //so, if data is retrieved, store it in html
			//alert( response );
			jQuery('#'+'widget-'+widget_id+'-body').html(response);
		}
		}); //close jQuery.ajax(

}
function get_calendar_stnt( widget_id, course_id, month, year, ajax_handler ){
	//write ajax req using wp jquery
	jQuery.ajax({
		url: ajax_handler,
		type: 'get',
		data: 'action=getLePressStudentWidgetCalendar&course_id=' + course_id + '&month=' + month + '&year=' + year + '&widget_id=' + widget_id,
		dataType: 'html',
		error: function( xmlHttpReq, textStatus, errorThrown  ){
			alert( 'ERROR: ' + textStatus + '\n eT: ' + errorThrown );
		},
		success: function( response ){ //so, if data is retrieved, store it in html
			//alert( response );
			jQuery( '#'+'widget-'+widget_id+'-calendar' ).html(response);
		}
		});
}

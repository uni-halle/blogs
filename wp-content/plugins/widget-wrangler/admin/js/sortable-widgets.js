
(function ($) {
  
WidgetWrangler.sortable = {};

/*
 * Show & Hide the "no widget" list item if corral is empty
 */
WidgetWrangler.sortable.toggle_no_widgets = function(){
	var lists = $("ul.ww-sortable");
	$.each(lists, function(){
		var num_items = $(this).children('.ww-item');
		if(num_items.length > 0){
			$(this).children('.ww-no-widgets').hide();
		}
		else{
			$(this).children('.ww-no-widgets').show();
		}
	});
}

/*
 * Update Widget weight value when sorted
 */
WidgetWrangler.sortable.update_widget_weights = function(){
	var lists = $("ul.ww-sortable");
	$.each(lists, function(){
		var this_list = $(this).children(".ww-item");
			$.each(this_list, function(i){
				$(this).children(".ww-widget-weight").val(i+1);
			});
	});
}

/*
 * Indicate changes have occured
 */
WidgetWrangler.sortable.message = function() {
	$('#ww-post-edit-message').css('visibility', 'visible');
}

/*
 * Refresh all the sortable lists
 */
WidgetWrangler.sortable.refresh_all = function() {
	// Auto change sort order when drag and drop
	var sortable_lists = $("ul.ww-sortable");
	$("ul.ww-sortable").sortable({
		items: '> li:not(.ww-no-widgets)',
		connectWith: '.ww-sortable',
		cancel: 'select,input',
    start: function(event, ui){
      ui.item.addClass('ww-dragging');
    },
    stop: function(event, ui){
      ui.item.removeClass('ww-dragging');
    }
	})
    .on('sortupdate', function(event,ui){
      var active_widgets = $(this).children(".ww-item");
      var corral_name = $(this).attr("name");
      $.each(active_widgets, function(i){
        $(this).children("select").val(corral_name);
      });
      WidgetWrangler.sortable.toggle_no_widgets();
      WidgetWrangler.sortable.update_widget_weights();
      WidgetWrangler.sortable.message();
    })
    .disableSelection();
	



	var selects = $("#widget-wrangler-form .nojs select");
	$.each(selects, function(){
		$(this).parent('.ww-item').removeClass('nojs');
		$(this).change(function(){
			var select_val = $(this).val();
			var select_name = $(this).attr("name");
			
			if ( select_val != 'disabled')
			{
				$(this).parent('.ww-item').clone()
					.addClass('nojs').prependTo("#ww-corral-"+select_val+"-items").removeClass("disabled");
				$(this).parent('.ww-item').remove();
				$("#ww-corral-"+select_val+"-items select[name='"+select_name+"']").val(select_val);
				
				var this_list = $("#ww-corral-"+select_val+"-items").children(".ww-item");
			}
			else {
				$(this).parent('.ww-item').remove();
			}
			WidgetWrangler.sortable.update_widget_weights();
			WidgetWrangler.sortable.toggle_no_widgets();
			WidgetWrangler.sortable.refresh_all();
			WidgetWrangler.sortable.message();
		});
	});
}

/*
 * Disable sorting
 */
WidgetWrangler.sortable.disable = function() {
	var selects = $("#widget-wrangler-form select, #widget-wrangler-form input[type='text']");
	$.each(selects, function(i, element){
		$(element).attr('disabled','disabled');
	});
}

/*
 * Enable sorting
 */
WidgetWrangler.sortable.enable = function() {
	var selects = $("#widget-wrangler-form select, #widget-wrangler-form input[type='text']");
	$.each(selects, function(i, element){
		$(element).removeAttr('disabled');
	});
	WidgetWrangler.sortable.refresh_all();	
}

/*
 * Initialize sorting and enable if no preset is selected
 */
WidgetWrangler.sortable.init = function() {
  WidgetWrangler.sortable.enable();
  
	// fix some form input issues
	// http://stackoverflow.com/questions/13898027/jquery-ui-sortable-cant-type-in-input-fields-with-cancel-method
	$("#widget-wrangler-form select, #widget-wrangler-form input[type='text']").live('click', function(e) {
		$(this).trigger({
			type: 'mousedown',
			which: 3
		});
	});
	
	$("#widget-wrangler-form select, #widget-wrangler-form input[type='text']").live('mousedown', function(e) {
		if(e.which == 3){
			$(this).focus();   
		}
	});
	// */
}

WidgetWrangler.ajax = {
	
	init: function() {
		$('select#ww_post_preset').live('change' , WidgetWrangler.ajax.replace_edit_widgets);
	},
	
	replace_edit_widgets: function() {
    var preset_ajax_op = $('input#widget_wrangler_preset_ajax_op').val();
    if (preset_ajax_op){
      // show throbber
      $('.ajax-working').show();
      
      // store original message
      var original_message = $('#ww-post-preset-message').html();
      
      // prepare post data
      var post_data_form = {
        'action': 'ww_form_ajax',
        'op': preset_ajax_op, 
        'preset_id': $('select#ww_post_preset').val(),
        'context_id': $('input#ww_ajax_context_id').val(),
      };
      
      // make ajax call
      $.ajax({
        url: WidgetWrangler.data.ajaxURL,
        type: 'POST',
        async: false,
        data: post_data_form,
        //dataType: 'json',
        success: function(data){
          // replace panel contents with new widgets
          $('#widget-wrangler-form-wrapper').html(data);
          
          // restore original messages
          $('#ww-post-preset-message').html(original_message);
          WidgetWrangler.sortable.message();
          
          // disable the wrangler for presets, enable for no preset
          WidgetWrangler.is_disabled = (post_data_form.preset_id) ? true : false;
          
          // re init
          WidgetWrangler.sortable.refresh_all();
        },
        error: function(s,m) {}
      });
    }
  },
    
  changes_message: function(){
    $('#ww-post-edit-message').show();
  }
};

  /**
   * Add any widget to any corral any number of times
   */
  WidgetWrangler.addWidget = {
    row_template: wp.template('add-widget'),

    // starting row index large to avoid collisions
    // decremented as widgets are added
    row_index: 10000,

    init: function(){
      $('#ww-add-new-widget-button').click( function(){
        var $widget = $('#ww-add-new-widget-widget');
        var $corral = $('#ww-add-new-widget-corral');

        if ( $widget.val() === '0' ){}
        else if ( $corral.val() === '0' ){}
        else {
          WidgetWrangler.addWidget.add(
            {
              ID: $widget.val(),
              post_title: $widget.find('option:selected').text()
            },
            $corral.val(),
            0
          );
        }
      });
    },

    add: function( widget, corral_slug, weight ){
      var $sortable_corral = $('#ww-corral-'+corral_slug+'-items');

      // replace all occurrences of tokens in template
      var row = WidgetWrangler.addWidget.row_template()
        .replace(/__widget-ID__/g, widget.ID )
        .replace(/__widget-post_title__/g, widget.post_title )
        .replace(/__widget-corral_slug__/g, corral_slug )
        .replace(/__widget-weight__/g, weight)
        .replace(/__ROW-INDEX__/g, corral_slug + '-' + WidgetWrangler.addWidget.row_index);

      WidgetWrangler.addWidget.row_index--;

      $sortable_corral.prepend(row).trigger('sortupdate');
    }
  };

$(document).ready(function(){
	WidgetWrangler.ajax.init();
	WidgetWrangler.sortable.init();
  WidgetWrangler.addWidget.init();
});

})(jQuery);
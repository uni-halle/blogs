(function($){

WidgetWrangler.z_editor = {
  // if ever activated
  sortable_initialized: false,
  
  // current page/screen context_id
  context_id: 0,
  context: '',
  
  // matching corral slugs to wp sidebar ids
  corral_wpsidebar_ids: {},
  
  disabled_widgets: {},
  
  /*
   * Initialize admin-bar buttons
   */
  init: function (){
   //console.log('init');
    $('#wp-admin-bar-widget-wrangler-disable').addClass('ww-menu-hidden');
    
    //this.overlay();
    this.get_context();
    this.get_disabled_widgets();
    this.refresh();
    this.corral_buttons();
    this.widget_buttons();
    // editor buttons
    $('#wp-admin-bar-widget-wrangler .ab-submenu li').click(function(){
      var op = $(this).attr('id').replace('wp-admin-bar-widget-wrangler-', '');
      
      switch (op){
        case 'enable':
          WidgetWrangler.z_editor.enable();
          break;

        case 'disable':
          WidgetWrangler.z_editor.disable();
          break;

        case 'save':
          $('li#wp-admin-bar-widget-wrangler-save').removeClass('ww-saved').addClass('ww-saving');
          WidgetWrangler.z_editor.save();
          break;
      }
    });
  },

  get_disabled_widgets: function(){
    if (WidgetWrangler.data.allWidgetOptions){
      var remaining_options = WidgetWrangler.data.allWidgetOptions;
      var lists = $(".ww-z-editor-corral-html");
      
      $.each(lists, function(i, list){
        var widgets = $(list).children(".ww-z-editor-widget");
        $.each(widgets, function(j, widget){
          var widget_id = $(widget).find(".ww-widget-id").val();
          delete remaining_options[widget_id];
        });
      });
      WidgetWrangler.z_editor.disabled_widgets = remaining_options;
    }
  },
  
  set_add_widget_options: function(){
    var options = '';
    $.each(WidgetWrangler.z_editor.disabled_widgets, function(widget_id, widget_title){
      options+= '<option value="'+widget_id+'">'+widget_title+'</option>';
    });
    $('.ww-z-editor-corral-action-add-content select[name=widget_id]').html(options);
  },
  
  get_context: function(){
    // find context, we only need it once
    $(".ww-z-editor-corral").each(function(i, corral) {
      var context_id = $(corral).find('.ww-z-editor-corral-data input[name=context_id]').val();
      var context = $(corral).find('.ww-z-editor-corral-data input[name=context]').val();
      
      if (context_id && context){
        WidgetWrangler.z_editor.context_id = context_id;
        WidgetWrangler.z_editor.context = context;
        return false; // break each
      }
      return true; // continue looking
    });    
  },
  
  /*
   * 
   */
  refresh: function(){
    WidgetWrangler.z_editor.get_disabled_widgets();
    WidgetWrangler.z_editor.set_add_widget_options();
    WidgetWrangler.z_editor.update_widgets();
    WidgetWrangler.z_editor.toggle_no_widgets();
  },
  
  toggle_menu_classes: function (){
    $('#wp-admin-bar-widget-wrangler-enable').toggleClass('ww-menu-hidden');
    $('#wp-admin-bar-widget-wrangler-disable').toggleClass('ww-menu-hidden');
  },
  
  /*
   *
   */
  disable: function() {
    $(".ww-z-editor-corral-html").sortable("disable");
    $(".ww-z-editor-corral, .ww-z-editor-widget").addClass('ww-z-editor-disabled');
    $('.ww-z-editor-widget-content').show();
    WidgetWrangler.z_editor.toggle_menu_classes();
  },
  
  /*
   *
   */
  overlay: function(){
    var overlay_html = '<div id="z-editor-overlay"><div id="ww-z-editor-message"></div></div>';
    $('body').append(overlay_html);
  },
  
  /*
   * Show & Hide the "no widget" list item if corral is empty
   */
  toggle_no_widgets: function(){
    var lists = $(".ww-z-editor-corral-html");
    $.each(lists, function(){
      var num_items = $(this).children('.ww-z-editor-widget').not('.ww-removed-by-user');
      if(num_items.length > 0){
        $(this).children('.ww-no-widgets').hide();
      }
      else{
        $(this).children('.ww-no-widgets').show();
      }
    });
  },

  /*
   * Update Widget weights and corral value when sorted
   */
  update_widgets: function(){
    var lists = $(".ww-z-editor-corral-html");
    $.each(lists, function(i, list){
      var corral_slug = $(list).parent().find('.ww-z-editor-corral-data input[name=corral_slug]').val();
      var widgets = $(list).children(".ww-z-editor-widget");
        $.each(widgets, function(j, widget){
          $(widget).find(".ww-widget-weight").val(j+1);
          $(widget).find('select.ww-corral-slug').val(corral_slug);
        });
    });
  },

  
  /*
   * Show the editor
   */
  enable: function (){
    // just re-enable the sortable() and show
    if (WidgetWrangler.z_editor.sortable_initialized) {
      $(".ww-z-editor-corral-html").sortable("enable");
    }
    // intialize the sortable() and show the editor
    else {
      // make sortable
      $(".ww-z-editor-corral-html").sortable({
        items: '> div:not(.ww-no-widgets)',
        connectWith: '.ww-z-editor-corral-html',
        cancel: 'select,input',
        start: function(event, ui){
          ui.item.addClass('ww-dragging');
        },
        stop: function(event, ui){
          ui.item.removeClass('ww-dragging');
        },
        update: function(event,ui){
          // remove saved indicator
          $('li#wp-admin-bar-widget-wrangler-save').removeClass('ww-saved');
    
          var active_widgets = $(this).children(".ww-z-editor-widget");
          var corral_name = $(this).attr("name");
           $.each(active_widgets, function(i){
              $(this).children("input.ww-corral-slug").val(corral_name);
          });
          WidgetWrangler.z_editor.refresh();
        }
      }).disableSelection();
      WidgetWrangler.z_editor.sortable_initialized = true;
    }
    
    WidgetWrangler.z_editor.toggle_menu_classes();
    $('.ww-z-editor-disabled').removeClass('ww-z-editor-disabled');
    $('.ww-z-editor-widget-content').hide();
    WidgetWrangler.z_editor.refresh();
  },
  
  /*
   * POST to our custom ajax handler
   */
  save: function(){
		// prepare post data
		var post_data_form = {
      'action': 'ww_z_editor_ajax',
			'z-editor-op': 'save_z_editor_widgets',
      'z-editor-widgets': WidgetWrangler.z_editor.serialize_widgets(),
      'context_id': WidgetWrangler.z_editor.context_id,
      'context': WidgetWrangler.z_editor.context
		};
    
		// make ajax call
		$.ajax({
			url: WidgetWrangler.data.ajaxURL,
			type: 'POST',
			async: false,
			data: post_data_form,
			//dataType: 'json',
			success: function(data){
        //console.log(data);
        $('#ww-z-editor-message').html(data);
        $('li#wp-admin-bar-widget-wrangler-save').removeClass('ww-saving').addClass('ww-saved');
			},
			error: function(s,m) {},
      complete: function(){}
		});
  },
  
  /*
   * Organize the widget data like ww_post_widgets post_meta expects
   */
  serialize_widgets: function(){
    var active_widgets = {};
    
    // get all corrals
    $('.ww-z-editor-corral').each(function(i, corral){
      var corral_slug = $(corral).find('.ww-z-editor-corral-data input[name=corral_slug]').val();
      active_widgets[corral_slug] = {};
      
      // get all widgets in the corral
      $(corral).find('.ww-z-editor-widget').each(function (j, widget){
        var weight = $(widget).find('.ww-widget-weight').val();
        
        active_widgets[corral_slug][weight] = {
          'id': $(widget).find('.ww-widget-id').val(),
          'weight': weight,
          'sidebar': corral_slug,
          'removed': $(widget).find('.ww-widget-removed').val()
        };
      });
    });
   
    return JSON.stringify(active_widgets);
  },
  
  /*
   *
   */
  corral_buttons: function () {
    $('.ww-z-editor-corral-action-add').click(function(event){
      var add_content = $(event.target).next('.ww-z-editor-corral-action-add-content').slideToggle();
      // make sure it doesn't go offscreen
      if (add_content.offset().left < 0){
        $(add_content).parent().css({"left":0,"top":0,"right":"auto"});
      }
    });
    
    $('.ww-z-editor-corral-action-add-submit').click(function(event){
      $(event.target).addClass('ww-saving');
      var widget_id = $(event.target).siblings('select[name=widget_id]').val();
      var corral_slug = $(event.target).siblings('input[name=corral_slug]').val();
      
      // prepare post data
      var post_data_form = {
        'action': 'ww_z_editor_ajax',
        'z-editor-op': 'add_z_editor_widget',
        'widget_id': widget_id,
        'corral_slug': corral_slug,
        'context_id': WidgetWrangler.z_editor.context_id,
        'context': WidgetWrangler.z_editor.context,
      };
    
      // make ajax call
      $.ajax({
        url: WidgetWrangler.data.ajaxURL,
        type: 'POST',
        data: post_data_form,
        //dataType: 'json',
        success: function(data){
          $(event.target).removeClass('ww-saving').addClass('ww-saved');
      
          // cast into new div for jqueryness
          var $widget = $('<div>').html(data);
          // clean up
          $widget.find('.ww-z-editor-widget').removeClass('ww-z-editor-disabled').find('.ww-z-editor-widget-content').hide();
          // output
          $('#ww-z-editor-corral-' + corral_slug + ' .ww-z-editor-corral-html').prepend($widget.html());
          $('#ww-z-editor-corral-' + corral_slug + ' .ww-z-editor-corral-action-add-content').slideUp();
          $(event.target).removeClass('ww-saved');
          WidgetWrangler.z_editor.refresh();
        },
        error: function(s,m) {},
        complete: function(){}
      });
      // */
    });
  },
  
  /*
   * Control widget buttons and select boxes
   */
  widget_buttons: function(){
    // enforce remove = 0
    $('.ww-z-editor-widget-remove .ww-z-editor-widget-data .ww-widget-removed').val(0);
    
    // remove widget button
    $('.ww-z-editor-widget-remove').click(function(){
      var widget_id = $(this).attr('name');
      
      $('#ww-z-editor-widget-' + widget_id).addClass('ww-removed-by-user').find('.ww-z-editor-widget-data .ww-widget-removed').val(1);
      $('#ww-z-editor-widget-' + widget_id).fadeOut(700, function(){
          WidgetWrangler.z_editor.toggle_no_widgets();  
      });
    });
    
    // fix some form input issues
    // http://stackoverflow.com/questions/13898027/jquery-ui-sortable-cant-type-in-input-fields-with-cancel-method
    $(".ww-z-editor-widget-title select").live('click', function(e) { $(this).trigger({ type: 'mousedown', which: 3}); });
    $(".ww-z-editor-widget-title select").live('mousedown', function(e) { if(e.which == 3){ $(this).focus(); } });

    // move widget on corral select
    $(".ww-z-editor-widget-title select").live('change', function(event){
			var select_val = $(this).val();
      //console.log(select_val);
      
      $(this).closest('.ww-z-editor-widget').clone().prependTo("#ww-z-editor-corral-"+select_val+" .ww-z-editor-corral-html").find('.ww-z-editor-widget-title select').val(select_val);
      $(this).closest('.ww-z-editor-widget').remove();
      
      WidgetWrangler.z_editor.refresh();
    });    
  }
}


$(document).ready(function(){
  WidgetWrangler.z_editor.init();
});

})(jQuery);
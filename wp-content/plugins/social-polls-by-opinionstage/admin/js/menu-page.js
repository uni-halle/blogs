jQuery(function($) {
  var toggleSettingsAjax = function(currObject, action) {
    $.post(ajaxurl, {action: action, activate: currObject.is(':checked')}, function(response) { });
  };

  $('#fly-out-switch').change(function(){
    toggleSettingsAjax($(this), "opinionstage_ajax_toggle_flyout");
  });

  $('#article-placement-switch').change(function(){
    toggleSettingsAjax($(this), "opinionstage_ajax_toggle_article_placement");
  });

  $('#sidebar-placement-switch').change(function(){
    toggleSettingsAjax($(this), "opinionstage_ajax_toggle_sidebar_placement");
  });
});

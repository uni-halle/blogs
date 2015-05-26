(function($){
  
  // hide adv parsing if not checked
  if (!$('#ww-adv-parse-toggle').prop('checked')){
    $('#ww-adv-parse-content').hide();
  }
  
  $('#ww-adv-parse-toggle').change(function(event){
    $('#ww-adv-parse-content').toggle();
  });

  // hide adv parsing if not checked
  if (!$('#ww-display-logic-toggle').prop('checked')){
    $('#ww-display-logic-content').hide();
  }
  
  $('#ww-display-logic-toggle').change(function(event){
    $('#ww-display-logic-content').toggle();
  });
  
  // hide override html if not checked
  if (!$('#ww-override-html-toggle').prop('checked')){
    $('#ww-override-html-content').hide();
  }
  
  $('#ww-override-html-toggle').change(function(event){
    $('#ww-override-html-content').toggle();
  });
  
  // show/hide preview html
  $('#ww-preview-html-content').hide();
  $('#ww-preview-html-toggle').click(function(event){
    $('#ww-preview-html-content').toggle();
  });

})(jQuery)
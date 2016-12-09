
 jQuery(document).ready(function (){
 


    jQuery('#views_select').click(function () {
	
    jQuery('#drop_down_views').stop(true, true).delay(200).slideDown(500);
  }, function () {
  
    jQuery('#drop_down_views').stop(true, true).slideUp(500);
	
  });
  
  if(jQuery(window).width() > 640 )
  {
	
	jQuery('drop_down_views').hide();
  }

	
	});
	
	

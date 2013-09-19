    jQuery(document).ready(function(){
		jQuery('.archiveslist').slideUp();
		
		jQuery('.archivesection h3').click(function(){		
			if(jQuery(this).next('.archiveslist').css('display')=='none')
				{	jQuery(this).removeClass('inactive');
					jQuery(this).addClass('active');
					jQuery(this).children('img').removeClass('inactive');
					jQuery(this).children('img').addClass('active');
					
				}
			else
				{	jQuery(this).removeClass('active');
					jQuery(this).addClass('inactive');		
					jQuery(this).children('img').removeClass('active');			
					jQuery(this).children('img').addClass('inactive');
				}
				
			jQuery(this).next('.archiveslist').slideToggle('slow');	
		});
});
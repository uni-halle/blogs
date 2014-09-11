jQuery(document).ready(function() {

jQuery('.squarebanner ul li:nth-child(even)').addClass('rbanner');

/* Navigation */
jQuery('#submenu ul.sfmenu').superfish({ 
		delay:       500,								// 0.1 second delay on mouseout 
		animation:   {opacity:'show',height:'show'},	// fade-in and slide-down animation 
		dropShadows: true								// disable drop shadows 
	});	


jQuery('.flexslider ').hover( function() {
			jQuery(this).find('.next').fadeIn(150);
			jQuery(this).find('.prev').fadeIn(150);
			}, function() {
		
			jQuery(this).find('.next').fadeOut(150);
			jQuery(this).find('.prev').fadeOut(150);
		});		 
		
	
jQuery('#menu-primary').mobileMenu();

jQuery('.flexslider').flexslider({
		controlNav: false
		});
	
	
});
function mainmenu(){
jQuery(" #top-menu ul ").css({display: "none"}); // Opera Fix
jQuery(" #top-menu li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(300);
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
}

 
 
 jQuery(document).ready(function(){					
	mainmenu();
});
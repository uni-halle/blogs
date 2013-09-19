		$(document).ready(function() { 
			$('ul.nav').superfish({ 
				delay:       200,                            // one second delay on mouseout 
				animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
				speed:       'fast',                          // faster animation speed 
				autoArrows:  false,                           // disable generation of arrow mark-up 
				dropShadows: false                            // disable drop shadows 
			}); 
		}); 
/*
 * jQuery Reveal Plugin 1.0
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Modified : Ankur Agarwal
 * 
 * Added functionality to mention scrollheight in the config
 * 
*/

(function($) {

/*---------------------------
 Defaults for Reveal
----------------------------*/
	 
/*---------------------------
 Listener for data-reveal-id attributes
----------------------------*/

	$('a[data-reveal-id]').live('click', function(e) {
		e.preventDefault();
		var modalLocation = $(this).attr('data-reveal-id');
		$('#'+modalLocation).reveal($(this).data());
	});

/*---------------------------
 Extend and Execute
----------------------------*/

    $.fn.reveal = function(options) {
        
        var defaults = {
	    	animation: 'fadeAndPop' //fade, fadeAndPop, none
		    , animationspeed: 300 //how fast animtions are
		    , closeonbackgroundclick: true //if you click background will modal close?
		    , dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
            , backdrop: true //Show the modal by default or not
            , autoshow: true //Show the modal by default or not
            , showevent: 'show'   // event to listen on the modal container to trigger show
            , shownevent: 'shown' // event to listen on the modal container after the modal box is shown
            , hideevent: 'hide' // event to listen on the modal container to trigger hide
            , hiddenevent: 'hidden' // event to listen on the modal container after the modal is hidden
            //scrollHeight: <integer>  This attribute if not passed will be calculated at the runtime and used
    	};
    	
        //Extend dem' options
        var options = $.extend({}, defaults, options);
        return this.each(function() {
        
/*---------------------------
 Global Variables
----------------------------*/
        	var modal = $(this),
        		topMeasure = parseInt(modal.css('top')),
				topOffset = modal.height() + topMeasure,
          		locked = false,
				modalBG = $('.reveal-modal-bg');
                
/*---------------------------
 Create Modal BG
----------------------------*/
			if(modalBG.length == 0) {
				modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
			}		    
     
/*---------------------------
 Open & Close Animations
----------------------------*/
			//Entrance Animations
			modal.bind('reveal:open ' + options.showevent, function () {
				$('.' + options.dismissmodalclass).unbind('click.modalEvent');
				if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
                        var h = typeof options.scrollheight !== "undefined" ? options.scrollheight : $(document).scrollTop();
						modal.css({'top': h -topOffset, 'opacity' : 0, 'visibility' : 'visible'});
						options.backdrop &&modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"top": h+topMeasure + 'px',
							"opacity" : 1
						}, options.animationspeed,shown());					
					}
					if(options.animation == "fade") {
						modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': $(document).scrollTop()+topMeasure});
						modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"opacity" : 1
						}, options.animationspeed,shown());					
					} 
					if(options.animation == "none") {
						modal.css({'visibility' : 'visible', 'top':$(document).scrollTop()+topMeasure});
						modalBG.css({"display":"block"});	
						shown()				
					}
				}
				modal.unbind('reveal:open');
			}); 	

			//Closing Animation
			modal.bind('reveal:close ' + options.hideevent, function () {           
			  if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
                        var h = typeof options.scrollheight !== "undefined" ? options.scrollheight : $(document).scrollTop();
						options.backdrop && modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"top": h - topOffset + 'px',
							"opacity" : 0
						}, options.animationspeed/2, function() {
							modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
							hidden();
                            
						});					
					}  	
					if(options.animation == "fade") {
						modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"opacity" : 0
						}, options.animationspeed, function() {
							modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : topMeasure});
							hidden();
						});					
					}  	
					if(options.animation == "none") {
						modal.css({'visibility' : 'hidden', 'top' : topMeasure});
						modalBG.css({'display' : 'none'});	
					}		
				}
				modal.unbind('reveal:close');
			});
   	
/*---------------------------
 Open and add Closing Listeners
----------------------------*/
        	//Open Modal Immediately
            options.autoshow && modal.trigger('reveal:open')
			
			//Close Modal Listeners
			var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
			  modal.trigger('reveal:close')
			});
			
			if(options.closeonbackgroundclick) {
				modalBG.css({"cursor":"pointer"})
				modalBG.bind('click.modalEvent', function () {
				  modal.trigger(options.hideevent)
				});
			}
			$('body').keyup(function(e) {
        		if(e.which===27){ modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
			});
			
            function shown () {
                modal.trigger(options.shownevent);
                unlockModal();
            }
            function hidden () {
                modal.trigger(options.hiddenevent);
                unlockModal();
            }
			
/*---------------------------
 Animations Locks
----------------------------*/
			function unlockModal() { 
				locked = false;
			}
			function lockModal() {
				locked = true;
			}	
			
        });//each call
    }//orbit plugin call
})(jQuery);
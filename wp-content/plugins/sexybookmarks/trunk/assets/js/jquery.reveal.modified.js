/*
 * jQuery Reveal Plugin 1.0
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Modified : Dan Horan
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

$.widget("ui.reveal", {
    options: {
        animation: 'fadeAndPop' //fade, fadeAndPop, none
        , animationspeed: 300 //how fast animtions are
        , closeonbackgroundclick: true //if you click background will modal close?
        , closeonesc: true //if you hit the ESC key will modal close?
        , dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
        , backdrop: true //Show the modal by default or not
        , autoshow: true //Show the modal by default or not
        , showevent: 'show'   // event to listen on the modal container to trigger show
        , shownevent: 'shown' // event to listen on the modal container after the modal box is shown
        , hideevent: 'hide' // event to listen on the modal container to trigger hide
        , hiddenevent: 'hidden' // event to listen on the modal container after the modal is hidden
        , draggable: false
        , draghandle: null
        , fixed: true
        , topPosition: 150
        //scrollHeight: <integer>  This attribute if not passed will be calculated at the runtime and used
    },
    _create : function() {
        var self = this, options = self.options;
            /*---------------------------
             Global Variables
            ----------------------------*/
            var modal = ( self.modal = self.element.addClass('reveal-modal') ),
                topMeasure = ( self._topMeasure = parseInt(modal.css('top')) ),
                topOffset = ( self._topOffset = modal.height() + topMeasure ),
                locked = ( self._locked = false ),
                modalBG = ( self.modalBG = $('.reveal-modal-bg') );

                if(!modal.attr('id')){
                  modal.attr('id', (new Date).getTime() + Math.floor((Math.random()*100)+1))
                }

                self._isOpen = false;

                if(options.draggable){
                    var drag_opts = { handle : options.draghandle };
                    modal.draggable(drag_opts);
                }

            /*---------------------------
             Create Modal BG
            ----------------------------*/

            if(modalBG.length == 0) {
                modalBG = ( self.modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal) );
            }

            /*---------------------------
             Add Closing Listeners
            ----------------------------*/

            //Close Modal Listeners
            var closeButton = $('.' + options.dismissmodalclass).off('click.reveal').on('click.reveal', function () {
              self.close();
            });

            if(options.closeonbackgroundclick) {
                self.modalBG.css({"cursor":"pointer"})
                self.modalBG.off('click.reveal').on('click.reveal', function () {
                  self.close();
                });
            }

        },

        _init : function(){
            var self = this, options = self.options;

            //Entrance Animations
            self.modal.on('reveal:open ' + options.showevent, function () {
                if(!self._isOpen){ self.open(); }
            });

            //Closing Animation
            self.modal.on('reveal:close ' + options.hideevent, function () {
                if(self._isOpen){ self.close(); }
            });

            //Open Modal Immediately
            options.autoshow && self.modal.trigger('reveal:open');


        },

        open : function(){
            var self = this, options = self.options;
            $('.' + options.dismissmodalclass).off('click.modalEvent');
            if(!self._locked) {
                self.lockModal();
                self.modal.css({left: '50%'}); // reset the left position in the event it was dragged over.
                if(options.animation == "fadeAndPop") {
                    var h = typeof options.scrollheight !== "undefined" ? options.scrollheight : $(document).scrollTop();
                    self.modal.css({'top': options.fixed ? 0 : h -self._topOffset, 'opacity' : 0, 'visibility' : 'visible'});
                    options.backdrop && self.modalBG.fadeIn(options.animationspeed/2);
                    self.modal.show().delay(options.animationspeed/2).animate({
                        "top": options.fixed ? options.topPosition : h+self._topMeasure + 'px',
                        "opacity" : 1
                    }, options.animationspeed,self.shown());
                }
                if(options.animation == "fade") {
                    self.modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': $(document).scrollTop()+self._topMeasure});
                    self.modalBG.fadeIn(options.animationspeed/2);
                    self.modal.show().delay(options.animationspeed/2).animate({
                        "opacity" : 1
                    }, options.animationspeed,self.shown());
                }
                if(options.animation == "none") {
                    self.modal.css({'visibility' : 'visible', 'top':$(document).scrollTop()+self._topMeasure});
                    self.modalBG.show();
                    self.shown()
                }
            }
            if(options.closeonesc){
              $('body').on('keyup.reveal_' + self.modal.attr('id'), function(e) {
                if(e.which===27){ self.close(); } // 27 is the keycode for the Escape key
              });
            }
            self.modal.unbind('reveal:open');
        },

        close : function(event){
            var self = this, options = self.options;
            if(!self._locked) {
                self.lockModal();
                if(options.animation == "fadeAndPop") {
                       var h = typeof options.scrollheight !== "undefined" ? options.scrollheight : $(document).scrollTop();
                    options.backdrop && self.modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                    self.modal.animate({
                        "top": options.fixed ? 0 : h - self._topOffset + 'px',
                        "opacity" : 0
                    }, options.animationspeed/2, function() {
                        self.modal.css({'top':self._topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
                        self.hidden(event);
                    });
                }
                if(options.animation == "fade") {
                    self.modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                    self.modal.animate({
                        "opacity" : 0
                    }, options.animationspeed, function() {
                        self.modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : self._topMeasure});
                        self.hidden(event);
                    });
                }
                if(options.animation == "none") {
                    self.modal.css({'visibility' : 'hidden', 'top' : self._topMeasure});
                    self.modalBG.hide();
                    self.hidden(event);
                }
            }
            $('body').off('keyup.reveal_' + self.modal.attr('id'));
            self.modal.unbind('reveal:close');
        },

         shown : function() {
            var self = this, options = self.options;
             self.modal.trigger(options.shownevent);
             self._isOpen = true;
             self.unlockModal();
         },

         hidden : function(event) {
            var self = this, options = self.options;
             self.modal.trigger(options.hiddenevent);
             self._trigger( "close", event );
             self._isOpen = false;
             self.unlockModal();
         },

        /*---------------------------
         Animations Locks
        ----------------------------*/
        unlockModal: function() {
            self._locked = false;
        },

        lockModal : function() {
            self._locked = true;
        }

});//Widget definition

})(sQuery);

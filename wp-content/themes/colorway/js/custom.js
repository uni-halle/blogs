//Tipsy	
jQuery(function() {    
    jQuery('.social_logo a').tipsy();
}); 
jQuery(function(){
    jQuery('ul.sf-menu').superfish();
})
//Fade images
jQuery(document).ready(function(){
    jQuery(".columns img, .post .postimg").hover(function() {
        jQuery(this).stop().animate({
            opacity: "0.8"
        }, '300');
    },
    function() {
        jQuery(this).stop().animate({
            opacity: "1"
        }, '300');
    });
    jQuery('#menu li.current_page_item a').click(function(){
       jQuery(this).css('border-radius','5px'); 
    });
});
// Can also be used with $(document).ready()
jQuery(window).load(function() {
  jQuery('.flexslider_blog').flexslider({
   animation: "slide", //String: Select your animation type, "fade" or "slide"
        slideDirection: "horizontal", //String: Select the sliding direction, "horizontal" or "vertical"
        slideshow: true, //Boolean: Animate slider automatically
        slideshowSpeed: 3000, //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationDuration: 600, //Integer: Set the speed of animations, in milliseconds
		smoothHeight: true, //Boolean: Animate the height of the slider smoothly for slides of varying height.
        //directionNav: true, //Boolean: Create navigation for previous/next navigation? (true/false)
        controlNav: false, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
        keyboardNav: true, //Boolean: Allow slider navigating via keyboard left/right keys
        mousewheel: false, //Boolean: Allow slider navigating via mousewheel
        //prevText: "", //String: Set the text for the "previous" directionNav item
       //nextText: "", //String: Set the text for the "next" directionNav item
        pausePlay: false, //Boolean: Create pause/play dynamic element
        pauseText: 'Pause', //String: Set the text for the "pause" pausePlay item
        playText: 'Play', //String: Set the text for the "play" pausePlay item
        randomize: false, //Boolean: Randomize slide order
        slideToStart: 0, //Integer: The slide that the slider should start on. Array notation (0 = first slide)
        animationLoop: true, //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
        pauseOnAction: true, //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
        pauseOnHover: true,
		//controlsContainer: jQuery(".custom-controls-container"),
        //customDirectionNav: jQuery(".flex-direction-nav a")
  });
});
//]]>
//Home page Animation
jQuery(document).ready(function (jQuery) {
    var animated_element = jQuery('.animated');
    function image_animation() {
        animated_element.each(function () {
            var get_offset = jQuery(this).offset();
            if (jQuery(window).scrollTop() + jQuery(window).height() > get_offset.top + jQuery(this).height() / 2) {
                jQuery(this).addClass('animation_started');
                // var el = $(this).find('.animated');
                setTimeout(function () {
                    jQuery(this).removeClass('animated').removeAttr('style');
                }, 300);
            }
        });
    }
    jQuery(window).scroll(function () {
        image_animation();
    });
    jQuery(window).load(function () {
        setInterval(image_animation, 300);
    });
});
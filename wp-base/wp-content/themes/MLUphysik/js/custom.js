jQuery(function($){
	$(document).ready(function(){
if (!Modernizr.svg) { 
    $('.olglogo a').css({'background': '#fff url(/wp-content/themes//MLUphysik/content/ico/apple-touch-icon-114-precomposed.png) no-repeat center','border':'1px solid #ccc','filter':'glow(color=black, strength=2)','zoom':'1'});
    $('#outer').remove();
};
		//masonry
		$('#masonry-wrap').masonry({
		  itemSelector: '.tile',
  columnWidth: 80,
			  isAnimated: true,
			  animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			  }
		});
function olgprettyPhoto() {
		//prettyPhoto
				$(".slides a, .gallery-icon a, a[rel^='prettyPhoto']").prettyPhoto({
				animation_speed:'normal',
				allow_resize: false,
				keyboard_shortcuts: true,
				show_title: false,
				show_description: true,
				social_tools: false,
				autoplay_slideshow: false
			});
			
			$('.flexslider').flexslider({
					pauseOnHover: true,
    animation: "slide",
    controlNav: false
  });
			}; // END function
olgprettyPhoto();

function olgajaxhelper() {
           	$('.olgaccordeon h4:first').addClass('trigger_active');
           	$('.olgaccordeon h4').not('.trigger_active').next('p').hide();
           		$('.olgaccordeon h4').click(function() {
		var trig = $(this);
		if ( trig.hasClass('trigger_active') ) {
			trig.next('p').slideToggle('slow');
			trig.removeClass('trigger_active');
		} else {
			$('.trigger_active').next('p').slideToggle('slow');
			$('.trigger_active').removeClass('trigger_active');
			trig.next('p').slideToggle('slow');
			trig.addClass('trigger_active');
		};
		return false;
	});
	$("#wrap a[href^='http']").attr("target","_blank");

           	olgprettyPhoto();
           	
           	
           	$.getScript("/wp-content/plugins/contact-form-7/includes/js/scripts.js", function(data, textStatus, jqxhr) {
/*
   console.log(data); //data returned
   console.log(textStatus); //success
   console.log(jqxhr.status); //200
   console.log('Load was performed.');
*/
}); //end get script

$('.post27 em strong').css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href='http://studieninfo.physik.uni-halle.de/?p=160']").click();
 });
 $('.post27 strong em').css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href='http://studieninfo.physik.uni-halle.de/?p=204']").click();
 });
 
}; // END function
	
	$(function (){
        $('.loop-entry a').bind("click",function(event){
            $('#wrap .loop-entry').removeClass('double-vertical quadro').css('overflow-y','hidden').find('.box').empty().scrollTop();
            $('#wrap .loop-entry').find('a').show();
var url = $(this).hide().attr('href')+'#wrap .post';
            //console.log(this.attr());
$(this).parent().addClass('double-vertical quadro').css('overflow-y','auto').find('.box').html('<h4>Inhalte werden geladen...</h4>').load(url,function(){
olgajaxhelper()
});         
            $('#masonry-wrap').masonry( 'reload' );
       event.preventDefault(event);
       event.stopPropagation();
       //return false;
});
});

	}); // END doc ready
	

}); // END function
$(window).load(function(){
$('.loop-entry.first-activ a').click();
$('.flexslider .slides > li').css('display','none');
});
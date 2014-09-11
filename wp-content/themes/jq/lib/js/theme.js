// Author:Devolux
// Author URI:http://devolux.org/

$(document).ready(function(){

  var currentPosition = 0;
  var slideWidth = 480;
  var slides = $('.slide');
  var numberOfSlides = slides.length;

  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl">Clicking moves left</span>')
    .append('<span class="control" id="rightControl">Clicking moves right</span>');

  // Hide left arrow control on first load
  manageControls(currentPosition);

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    
	// Hide / show controls
    manageControls(currentPosition);
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    });
  });

  // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
	// Hide right arrow if position is last slide
    if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
  }

//Superfish menu
$("ul.sf-menu").supersubs().superfish(
{
            delay:       1000,                            // one second delay on mouseout
            animation:   {opacity:'show'},  // fade-in and slide-down animation
            speed:       'normal',                          // faster animation speed
            autoArrows:  false,                           // disable generation of arrow mark-up
            dropShadows: false                            // disable drop shadows
        }
);

//Toggle functions
 $("#toggle-all").toggle(
                    function(){
                         $(".excerpt").hide('slow');
			 $("#toggle").attr("class","show-all");
                    }, function() {
                         $(".excerpt").show('slow');
			 $("#toggle").attr("class","hide-all");
                    });

$("#sidebar_show").hide();

$("#hide_s").click(function (event) {
	event.preventDefault();
                         $("#right").hide();
			 $("#left").css("width","880px");
			 $("#sidebar_show").show();
    });

$("#show_s").click(function (event) {
	event.preventDefault();
                         $("#right").show();
			 $("#left").css("width","560px");
			 $("#sidebar_show").hide();
    });

$(".view-excerpt").click(function (event) {
	event.preventDefault();
      $(this).parents(".headline").next(".excerpt").toggle("normal");
    });

$(".widget h4").click(function (event) {
	event.preventDefault();
      $(this).next().toggle();
    });

 });

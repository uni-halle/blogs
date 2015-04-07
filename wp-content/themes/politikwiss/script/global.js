$(document).ready(function() {


  $(".item > ul li:last-child").has("a").css("list-style-type", "none");   
  $(".item > p").append("<span>+</span>" );
  $('.flexslider').flexslider({
    animation: "fade",
    randomize: false,
    directionNav: false,
    slideshowSpeed: 15000
  });
    
  $('#menutoggle').click(function(){
      if($('#nav_ul').hasClass("closed")){ 
    $('#nav_ul').css("height","17.1rem");
    $('.logowrap').css("margin-bottom","1rem");
    $( "#nav_ul" ).removeClass( "closed" ).addClass( "open" );
    $( "#close_toggle").show('fade');
     $('#menutoggle span').hide('fade');
  }else{
    $('#nav_ul').css("height","0");
      $( "#nav_ul" ).removeClass( "open" ).addClass( "closed" );
      $('.logowrap').css("margin-bottom","0");
   $( "#close_toggle").hide('fade');
     $('#menutoggle span').show('fade');
}
    
    });

  $(document).ready(scaleBackground);
  $(window).on("resize", scaleBackground);

  function scaleBackground() {

    var windowHeight = $(window).height();
    var windowWidth = $(window).width();

    var backgroundNativeWidth = $(".sliderwrap img").width();
    var backgroundNativeHeight = $(".sliderwrap img").height();

    var widthScaleFactor = windowWidth / backgroundNativeWidth;
    var heightScaleFactor = windowHeight / backgroundNativeHeight;

    //Get the highest scale factor
    if (widthScaleFactor > heightScaleFactor) {
      var scale = widthScaleFactor;

    } else {
      var scale = heightScaleFactor;
    }
        var scaleBackgroundWidth = backgroundNativeWidth * scale;
    var scaleBackgroundHeight = backgroundNativeHeight * scale;

    $(".sliderwrap img").width(scaleBackgroundWidth);
    $(".sliderwrap img").height(scaleBackgroundHeight);

  }



$('.item').mouseenter(function(){
  $('p:first-child', this).hide();
  $('ul', this).show();
});
$('.item').mouseleave(function(){
  $('p:first-child', this).show('fade');
  $('ul', this).hide('fade');
  });

$('#impressum_link').click(function(event){
 event.preventDefault();
 $("html, body").animate({ scrollTop: $(document).height()});
 $('#impressum').css("height","auto");
  $('#impressum').css("padding","2rem");
  $('.footer').css("max-height","800px");

 $('#impressum').click(function(){
   $('#impressum').css("height","0");
     $('#impressum').css("padding","0rem");
     $('.footer').css("max-height","auto");



});
});
/*Kombinationstoggle*/
$('#kombi_toggle').click(function(){
if($('#kombi_toggle').hasClass("closed")) { 
  $('#kombi_content').css("padding","1rem");
  $('#kombi_content').css("height","auto");
  $('.kombi_plus > span:first-child').hide('slow');
  $('.kombi_plus > span:last-child').show('slow');
  $('#kombi_toggle').removeClass( "closed" ).addClass( "open" );
} else {
  $('#kombi_content').css("padding","0");
  $('#kombi_content').css("height","0");
  $('.kombi_plus > span:first-child').show('slow');
  $('.kombi_plus > span:last-child').hide('slow');
  $('#kombi_toggle').removeClass( "open" ).addClass( "closed" );
}

});




if ( $(window).width() > 989 )
    {
        return false;
} else {

  $("#absolventen article > p").each(function() {
    $(this).insertAfter($(this).next("div"));
});
}

if ( $(window).width() > 600 )
    {
        return false;
} else {

  $("#absolventen article > p").each(function() {
    $(this).insertBefore($(this).prev("div"));
});
}






});



/* $(window).scroll(function(){
    if($(window).width() < 800)
    {
        return false;	
    
    }else{
    
    if($(document).scrollTop() > 190)
    {
      
            
            $('.header_socialwrap').stop().hide();
            $('.header_logo_wrap').stop().hide();
            $('.header_lang_log_wrap').stop().hide();
            $('.menu_wrap').stop().hide();
            $('.menu_wrap2').stop().show();
            $('.header_logo_thin').stop().show();
            $('#log_small').stop().show();
            $('#main-nav').stop().css("float","left");
            $('.header_wrap').stop().css("height","197px");

        
        
    }
    else
    {
       
            
            $('.header_socialwrap').stop().show();
            $('.header_logo_wrap').stop().show();
            $('.header_lang_log_wrap').stop().show();
            $('.header_wrap').stop().show();
            $('.menu_wrap').stop().show();
            $('.header_logo_thin').stop().hide();
            $('#log_small').stop().hide();
            $('.menu_wrap2').stop().hide();
            $('#main-nav').stop().css("float","none");
            $('#main-nav').stop().css("margin","0, auto");
            $('.header_wrap').stop().css("height","160px");   
    }}
});
  
$(document).ready(function() {
   $('.menu_button').click(function(){
    $("#main-nav").show('fade');
    $(".menu_button").hide('fade');
    $(".menu_button_close").show('fade');
    $('.flexslider').css("margin-top","315px");
    
}); 
$('.menu_button_close').click(function(){
    $("#main-nav").hide('fade');
    $(".menu_button").show('fade');
    $(".menu_button_close").hide('fade');
    $('.flexslider').css("margin-top","60px");
}); 
});
var myClass;
$(document).ready(function()
 {
   $('.thumb').click(function(){

  myClass = $(this).attr('id');
  $('.' + myClass).prevAll().hide()
  $('.' + myClass).nextAll().hide()
  $('.seiten_inhalt').hide();
$('.' + myClass).fadeIn('slow');


}); 
$('#prod_nav1').click(function(){
    $('#prod_nav1').addClass('product_nav_active');
    $('#prod_nav2').addClass('prod_nav');
    $('#prod_nav2').removeClass('product_nav_active');
    $('#prod_nav1').removeClass('prod_nav');



    
    $('.seite1').show();
    $('.seite2').hide();
});
$('#prod_nav2').click(function(){
    $('#prod_nav2').addClass('product_nav_active');
    $('#prod_nav1').addClass('prod_nav');
    $('#prod_nav1').removeClass('product_nav_active');
    $('#prod_nav2').removeClass('prod_nav');



    
    $('.seite2').show();
    $('.seite1').hide();
});

});

*/





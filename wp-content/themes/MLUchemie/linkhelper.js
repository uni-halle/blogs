jQuery(function($) {
/*$(document).ready(function(){
/*
if (!Modernizr.svg) { 
    $('.chemie .tile.olglogo a').css({'background':'#A00000 url(/wp-content/themes//MLUchemie/bg.png)','zoom':'1'});
}; //wird in der parent-theme custom.js abgefangen
*/



	$('.tile').on('hover', '.linkzukontakt', function(){
	//console.info($(this));
	
$('.linkzukontakt').css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href$='/kontakt/']").click();
 });
});
	
	
	
	$('.tile').on('hover', 'strong a', function(){
 olghref = $(this).attr('href');
$(this).removeAttr("href").css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href='" + olghref + "']").click(); 
return false;

 });
});



});

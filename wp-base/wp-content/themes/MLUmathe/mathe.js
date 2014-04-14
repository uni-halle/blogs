$(document).ready(function(){
	
	$('.tile').on('hover', '.linkzukontakt', function(){
	//console.info($(this));
	
$('.linkzukontakt').css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href$='/kontakt/']").click();
 });
});
	
	
	
	$('.tile').on('hover', 'strong a', function(){
	//console.info($(this));
 olghref = $(this).attr('href');
$(this).removeAttr("href").css({'border-bottom':'1px dotted','cursor':'pointer'}).click(function(){ 
$("a[href='" + olghref + "']").click(); 
return false;

 });
});

/*
	
	$('.tile').on('hover', '.post a[href^="https://blogs.urz.uni-halle.de/"]', function(){
	//console.info($(this));
 olghref2 = $(this).attr('href');
$(this).css({'border-bottom':'1px dotted','cursor':'pointer','color':'red'}).click(function(){ 
$(".tile a[href=' + olghref2 + ']").click();
event.preventDefault();
 });
});

	$('.tile').on('hover', '.post a[href^="http://blogs.urz.uni-halle.de/"]', function(){
	//console.info($(this));
 olghref3 = $(this).attr('href');
$(this).css({'border-bottom':'1px dotted','cursor':'pointer','color':'red'}).click(function(){ 
$(".tile a[href=' + olghref3 + ']").click();
event.preventDefault();
 });
});
*/



});
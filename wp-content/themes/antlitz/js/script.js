/* Author: Christoph Knoth & Konrad Renner */


function onAfter(curr, next, opts) {
	if(opts.currSlide > 0) {
		$('#previous, #next').show();
	}
}


function myOwnMediaQueries() {
	if ($(window).width() > 1004) {
		$(".topline-1, .topline-2, .topline-3").show();
		$(".topline-4, .topline-5").hide();	
		}
	else if ($(window).width() > 686) {
		$(".topline-1, .topline-2, .topline-5").hide();
		$(".topline-4").show();	
	} else{
		$(".topline-1, .topline-2, .topline-3, .topline-4").hide();
		$(".topline-5").show();
	}
}

$(document).ready(function() {

	
	$("p").addClass("hyphenate text");
	$("em, a").addClass("donthyphenate text");
    
    Hyphenator.addExceptions('de','Einstein');    
	Hyphenator.run();	

	$("article").css("overflow","hidden");
				
	$("article").each(function() {
		//if you have no javascript, you can scroll in the articles, but we do not want that
		$(this).append('<div class="cross">×</div>');
	});
	
	//if you click on the image you can see the content
	$("article figure").click(function() {
		$(this).hide();
		$(this).parent().find("h1").css({"position":"relative"});

		if ($(this).closest("article").hasClass("post-22")) {
			$(this).closest("article").jScrollPane();
		}
	});
		
	$(".cross").click(function() {
		$(this).parent().find("figure").show();
		$(this).parent().find("h1").css({"position":"absolute"});		
	});	
	
	//if you click on the heading you can see the content
	$("article h1").click(function() {
		$(this).parent().find("figure").hide();
		$(this).css({"position":"relative"});
		
		/*$(this).jScrollPane();*/
		if ($(this).closest("article").hasClass("post-22")) {
			$(this).closest("article").jScrollPane();
		}
	});
	
			
	/*$("article").hover(
		function () {
			$(this).find("figure").hide();
			$(this).find("h1").css({"position":"relative"});
		}, 
		function () {
			$(this).find("figure").show();
			$(this).find("h1").css({"position":"absolute"});		
		}
	);*/

	//button goes to second picture
	$('#center, #start-image').click(function() {
    	$('#works').cycle(1);
    	
    	$('#previous, #next').show();

		return false;  
	});
		
	//button goes to last slide
	$('#right').click(function() {
    	$('#works').cycle(9);
		return false;  
	});

	$("#previous, #next, #right, #center span").hover(
		function () {$(this).addClass("underline");},
  		function () {$(this).removeClass("underline");}
	);
	

	//500 is the height of the image box
	$("#works").css("top", ( Math.max(($(window).height() - 500 )/2, 70)) + "px");

	$("#previous, #next").css("top", ( Math.max($(window).height()/2, 70)) + "px");
	
	
	//social popup

	$('.popupfb').popupWindow({ 
	height:440, 
	width:700, 
	top:50, 
	left:50 
	}); 
	
	
	$('.popupgp').popupWindow({ 
	height:500, 
	width:600, 
	top:50, 
	left:50 
	}); 
	
	$('.popuptw').popupWindow({ 
	height:400, 
	width:800, 
	top:50, 
	left:50 
	}); 
	
	$(window).resize(function() {
		myOwnMediaQueries();
		//log($(window).width());
	});

	myOwnMediaQueries();
});




/* jQuery functions for Esquire Theme by Matthew Buchanan */

function handleImage(ref) {
	if( ref.width() < 400 )
		ref.css({"padding-left":(560-ref.width())/2+"px","padding-right":(560-ref.width())/2+"px"});
	if( ref.height() < 300 )
		ref.parents(".photo").addClass("short");
}

jQuery(function() {
	// Handle sidebar nav tooltips
	jQuery(".buttons a").hover(function() {
		jQuery("#buttontext").html(jQuery(this).attr("title"));
	}, function() {
		jQuery("#buttontext").html("&nbsp;");
	});

	// Toggle search field
	jQuery("#showsearch").click(function(ev){
		ev.preventDefault();
		jQuery("#search").slideToggle();
	});

	// Move header to correct position in page markup, to sit beside the datebox
	var datebox = jQuery("#posts .post:first .datebox");
	jQuery("#header").remove().insertAfter(datebox).show();

	// Handle permalink date toggling
	jQuery(".permalink a").hover(function() {
		ob = jQuery(this).find("span");
		timeago = ob.text();
		ob.text(ob.attr("rel"));
	}, function() {
		ob.text(timeago);
	});

	// Allow ampersands to be styled in headings
	jQuery("h1").each(function() {
		var hed = jQuery(this).html();
		hed = hed.replace(/&amp;/gi, "<span class='amp'>&amp;</span>");
		jQuery(this).html(hed);
	});
});

jQuery(window).load(function() {
	jQuery(".photo .frame img").each(function() {
		handleImage(jQuery(this));
	});
});
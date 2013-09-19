/*
 * main JS Document for Blogs@MLU Default Theme
 * Copyright (c) 2009 Matthias Kretschmann | krema@jpberlin.de
 * http://matthiaskretschmann.com
 * http://kremalicious.com
 */

var $ = jQuery.noConflict();

/*
 * In-Field Label jQuery Plugin
 * http://fuelyourcoding.com/scripts/infield.html
 *
 * Copyright (c) 2009 Doug Neiner
 * Dual licensed under the MIT and GPL licenses.
 * Uses the same license as jQuery, see:
 * http://docs.jquery.com/License
 *
 * @version 0.1
 */
(function($){$.InFieldLabels=function(b,c,d){var f=this;f.$label=$(b);f.label=b;f.$field=$(c);f.field=c;f.$label.data("InFieldLabels",f);f.showing=true;f.init=function(){f.options=$.extend({},$.InFieldLabels.defaultOptions,d);if(f.$field.val()!=""){f.$label.hide();f.showing=false};f.$field.focus(function(){f.fadeOnFocus()}).blur(function(){f.checkForEmpty(true)}).bind('keydown.infieldlabel',function(e){f.hideOnChange(e)}).change(function(e){f.checkForEmpty()}).bind('onPropertyChange',function(){f.checkForEmpty()})};f.fadeOnFocus=function(){if(f.showing){f.setOpacity(f.options.fadeOpacity)}};f.setOpacity=function(a){f.$label.stop().animate({opacity:a},f.options.fadeDuration);f.showing=(a>0.0)};f.checkForEmpty=function(a){if(f.$field.val()==""){f.prepForShow();f.setOpacity(a?1.0:f.options.fadeOpacity)}else{f.setOpacity(0.0)}};f.prepForShow=function(e){if(!f.showing){f.$label.css({opacity:0.0}).show();f.$field.bind('keydown.infieldlabel',function(e){f.hideOnChange(e)})}};f.hideOnChange=function(e){if((e.keyCode==16)||(e.keyCode==9))return;if(f.showing){f.$label.hide();f.showing=false};f.$field.unbind('keydown.infieldlabel')};f.init()};$.InFieldLabels.defaultOptions={fadeOpacity:0.5,fadeDuration:300};$.fn.inFieldLabels=function(c){return this.each(function(){var a=$(this).attr('for');if(!a)return;var b=$("input#"+a+"[type='text'],"+"input#"+a+"[type='password'],"+"textarea#"+a);if(b.length==0)return;(new $.InFieldLabels(this,b[0],c))})}})(jQuery);

//Live Search plug-in | http://andreaslagerkvist.com/jquery/live-search/
jQuery.fn.liveSearch=function(conf){var config=jQuery.extend({url:'/?module=SearchResults&q=',id:'jquery-live-search',duration:400,typeDelay:200,loadingClass:'loading',onSlideUp:function(){}},conf);var liveSearch=jQuery('#'+config.id);if(!liveSearch.length){liveSearch=jQuery('<div id="'+config.id+'"></div>').appendTo(document.body).hide().slideUp(0);jQuery(document.body).click(function(event){var clicked=jQuery(event.target);if(!(clicked.is('#'+config.id)||clicked.parents('#'+config.id).length||clicked.is('input'))){liveSearch.slideUp(config.duration,function(){config.onSlideUp()})}})}return this.each(function(){var input=jQuery(this).attr('autocomplete','off');var resultsShit=parseInt(liveSearch.css('paddingLeft'),10)+parseInt(liveSearch.css('paddingRight'),10)+parseInt(liveSearch.css('borderLeftWidth'),10)+parseInt(liveSearch.css('borderRightWidth'),10);input.focus(function(){if(this.value!==''){if(liveSearch.html()==''){this.lastValue='';input.keyup()}else{liveSearch.slideDown(config.duration)}}}).keyup(function(){if(this.value!=this.lastValue){input.addClass(config.loadingClass);var q=this.value;if(this.timer){clearTimeout(this.timer)}this.timer=setTimeout(function(){jQuery.get(config.url+q,function(data){input.removeClass(config.loadingClass);if(data.length&&q.length){var tmpOffset=input.offset();var inputDim={left:tmpOffset.left,top:tmpOffset.top,width:input.outerWidth(),height:input.outerHeight()};inputDim.topNHeight=inputDim.top+inputDim.height;inputDim.widthNShit=inputDim.width-resultsShit;liveSearch.css({position:'absolute',left:inputDim.left+'px',top:inputDim.topNHeight+'px',width:inputDim.widthNShit+'px'});liveSearch.html(data).slideDown(config.duration)}else{liveSearch.slideUp(config.duration,function(){config.onSlideUp()})}})},config.typeDelay);this.lastValue=this.value}})})};

// jQuery Cookie plug-in
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('o.5=q(b,9,2){7(h 9!=\'x\'){2=2||{};7(9===j){9=\'\';2=$.v({},2);2.3=-1}4 3=\'\';7(2.3&&(h 2.3==\'m\'||2.3.l)){4 6;7(h 2.3==\'m\'){6=t u();6.s(6.w()+(2.3*r*p*p*z))}k{6=2.3}3=\'; 3=\'+6.l()}4 8=2.8?\'; 8=\'+(2.8):\'\';4 a=2.a?\'; a=\'+(2.a):\'\';4 c=2.c?\'; c\':\'\';d.5=[b,\'=\',E(9),3,8,a,c].y(\'\')}k{4 e=j;7(d.5&&d.5!=\'\'){4 g=d.5.F(\';\');D(4 i=0;i<g.f;i++){4 5=o.C(g[i]);7(5.n(0,b.f+1)==(b+\'=\')){e=B(5.n(b.f+1));G}}}A e}};',43,43,'||options|expires|var|cookie|date|if|path|value|domain|name|secure|document|cookieValue|length|cookies|typeof||null|else|toUTCString|number|substring|jQuery|60|function|24|setTime|new|Date|extend|getTime|undefined|join|1000|return|decodeURIComponent|trim|for|encodeURIComponent|split|break'.split('|'),0,{}))

//Start the real fun
jQuery(function($) {//DOMdiDOM
	
	//initiate Infield Labels
	//$('label').inFieldLabels();
	
	//Front-End login and admin
	var $panel = $('#front-admin > *:not(#login-link)')
	$('#login-link').click(function(){
		if ($panel.is(":hidden")) {
			$panel.slideDown('normal');
			$.cookie('panelState', 'expanded');
		return false;
	} else {
		$panel.slideUp('normal');
		$.cookie('panelState', 'collapsed');
	return false;
	}
	});
    var panelCookie = $.cookie('panelState');
    if (panelCookie == 'collapsed') {
 		$panel.hide();
 	}
 	
 	//Open pdf links in new window
    $('a[href*=".pdf"]').click(function(){
		window.open(this.href);
	return false;
	});
	
	//Style external links
	$('#content .hentry a').not(":has(img)").filter(function() {
    	return this.hostname && this.hostname !== location.hostname;
  	}).addClass('external').attr({rel:'external'});
	
	//Thickbox on linked images
	$('.hentry a').filter('[href$=".png"],[href$=".PNG"],[href$=".jpg"],[href$=".JPG"],[href$=".gif"],[href$=".GIF"]').addClass('thickbox').attr('rel','thickbox-gallery');

});//don't delete me or the DOM will collaps

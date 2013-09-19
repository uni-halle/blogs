jQuery.getPos = function (e){
	var l = 0;
	var t = 0;
	var w = jQuery.intval(jQuery.css(e,'width'));
	var h = jQuery.intval(jQuery.css(e,'height'));
	var wb = e.offsetWidth;
	var hb = e.offsetHeight;
	while (e.offsetParent){
	l += e.offsetLeft + (e.currentStyle?jQuery.intval(e.currentStyle.borderLeftWidth):0);
	t += e.offsetTop + (e.currentStyle?jQuery.intval(e.currentStyle.borderTopWidth):0);
	e = e.offsetParent;
	}
	l += e.offsetLeft + (e.currentStyle?jQuery.intval(e.currentStyle.borderLeftWidth):0);
	t += e.offsetTop + (e.currentStyle?jQuery.intval(e.currentStyle.borderTopWidth):0);
	return {x:l, y:t, w:w, h:h, wb:wb, hb:hb};
};
jQuery.getClient = function(e){
	if (e) {
		w = e.clientWidth;
		h = e.clientHeight;
	} else {
		w = (window.innerWidth) ? window.innerWidth : (document.documentElement && document.documentElement.clientWidth) ? document.documentElement.clientWidth : document
		.body.offsetWidth;
		h = (window.innerHeight) ? window.innerHeight : (document.documentElement && document.documentElement.clientHeight) ? document.documentElement.clientHeight : 
		document.body.offsetHeight;
	}
	return {w:w,h:h};
};
jQuery.getScroll = function (e){
	 if (e) {
		 t = e.scrollTop;
		 l = e.scrollLeft;
		 w = e.scrollWidth;
		 h = e.scrollHeight;
	 } else {
		 if (document.documentElement && document.documentElement.scrollTop) {
		 t = document.documentElement.scrollTop;
		 l = document.documentElement.scrollLeft;
		 w = document.documentElement.scrollWidth;
		 h = document.documentElement.scrollHeight;
	 } else if (document.body) {
		 t = document.body.scrollTop;
		 l = document.body.scrollLeft;
		 w = document.body.scrollWidth;
		 h = document.body.scrollHeight;
		 }
	 }
	 return { t: t, l: l, w: w, h: h };
};
jQuery.intval = function (v){
	v = parseInt(v);
	return isNaN(v) ? 0 : v;
};
jQuery.fn.ScrollTo = function(s){
	o = jQuery.speed(s);
	return this.each(function(){
	new jQuery.fx.ScrollTo(this, o);
	});
};
jQuery.fx.ScrollTo = function (e, o){
	var z = this;
	z.o = o;
	z.e = e;
	z.p = jQuery.getPos(e);
	z.s = jQuery.getScroll();
	z.clear = function(){
					clearInterval(z.timer);z.timer=null
					};
	z.t=(new Date).getTime();
	z.step = function(){
		var t = (new Date).getTime();
		var p = (t - z.t) / z.o.duration;
		if (t >= z.o.duration+z.t){
			z.clear();
			setTimeout(function(){z.scroll(z.p.y, z.p.x)},13);
		} else {
			st = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.y-z.s.t) + z.s.t;
			sl = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.x-z.s.l) + z.s.l;
			z.scroll(st, sl);
		}
	};
	z.scroll = function (t, l){window.scrollTo(l, t)};
	z.timer=setInterval(function(){z.step();},13);
};

$(document).ready(function(){
//switchTab
	$('#trackbacktab').click(function(){$(this).addClass('curtab');$('#commenttab').addClass('tab').removeClass('curtab');$('#thecomments').addClass('nodisplay');$('#thetrackbacks').removeClass('nodisplay').addClass('display')});
	$('#commenttab').click(function(){$(this).addClass('curtab');$('#trackbacktab').addClass('tab').removeClass('curtab');$('#thecomments').removeClass('nodisplay').addClass('display');$('#thetrackbacks').addClass('nodisplay')});
	
//toggle-comment-author-info
	$('#show_author_info a').toggle(function(){$('#author_info').slideDown();$(this).html(closeMsg);},function(){$('#author_info').slideUp();$(this).html(changeMsg);});

//reply
$('#thecomments li').hover(function(){$(this).find('.reply').css('visibility','visible')},function(){$(this).find('.reply').css('visibility','hidden')});

//Scroll
$('.addcomment').click(function(){$('#respond').ScrollTo(800);return false; });
$('#totop').click(function(){$('#header').ScrollTo(400);return false; });
$('.reply a').click(function(){$($(this).attr('href')).ScrollTo(400);return false; });
//clone tip
var A=/^#comment-/;
var B=/^@/;
$('#thecomments li p a').each(function(){
	if($(this).attr('href').match(A)&& $(this).text().match(B)){
		$(this).addClass('cool');
		}
	});
$('.cool').hover(
	function(){
		$($(this).attr('href')).clone().hide().attr('id','').insertAfter($(this).parents('li')).addClass('tip').fadeIn('fast');
		},
	function(){
	$('.tip').animate({opacity: "hide", top: "+=100px"},500,function(){$(this).remove()});
	});
	$('.cool').mousemove(
			function(e){
				$('.tip').css({left:(e.clientX+18),top:(e.pageY+18)});
				});
	$('.cool').click(function(){
	$($(this).attr('href')).ScrollTo(400);return false;
	});

/*
//out links
$("a[rel='external']").click(function(){window.open(this.href);return false});
$("a[href*='http://philna.com'],a[href*='javascript'],a:has(img),a[href*=#]").attr("rel","inlinks");
$("a[rel!='inlinks']").click(function(){window.open(this.href);return false;});
*/
	//ajax reply
	if ($('#commentform').length){
	$('#commentform').submit(function(){
		$.ajax({
			url:ajaxCommentsURL,
			data: $('#commentform').serialize(),
			type: 'POST',
			beforeSend: function() {
				$('#commentload').hide();
				$('#commentload').fadeIn('fast');
				$('#submit').attr('disabled', true).css('opacity','0.5');
				$('#comment').attr('disabled', true);
			},
			error: function(request) {
				$('#commentload').fadeOut('fast');
				alert(request.responseText);
				$('#submit').attr('disabled', false).css('opacity','1');
				$('#comment').attr('disabled', false);
			},
			success:function(data){
				$('textarea').each(function(){this.value='';});
				if (!$('#thecomments').length ){
					$('#pinglist').before('<ol class="commentlist"></ol>');
					}
				$('#thecomments').append(data);
				var new_comment = $('#thecomments li:last').hide();
				new_comment.animate({backgroundColor: '#B1D0ED'},500).animate({opacity:'show'},500).animate({backgroundColor:'#FFF'},1000);
				$('.nub').text(parseInt($('#allcmnub').text())+1).removeClass('nub').addClass('cmnub');
				$('#allcmnub').text(parseInt($('#allcmnub').text())+1);
				$('#commentload').fadeOut('fast');
				setTimeout(function() {$('#submit').attr('disabled', false).css('opacity','1');$('#comment').attr('disabled', false);}, 3000);
			}
		});
		return false;
	});
	};
})



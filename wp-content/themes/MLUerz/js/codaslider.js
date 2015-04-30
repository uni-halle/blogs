(function($){ // jQuery code here 
	$(document).ready(function () {
		
	var $panels = $('.tx-kiwislider-pi1 .scrollContainer > div');
	var $container = $('.tx-kiwislider-pi1 .scrollContainer');
		
	// if false, we'll float all the panels left and fix the width 
	// of the container
	var horizontal = true;
		
	// float the panels left if we're going horizontal
	if (horizontal) {
			$panels.css({
		'float' : 'left',
		'position' : 'relative' // IE fix to ensure overflow is hidden
			});
		
			// calculate a new width for the container (so it holds all panels)
			$container.css('width', $panels[0].offsetWidth * $panels.length);
	}
		
	// collect the scroll object, at the same time apply the hidden overflow
	// to remove the default scrollbars that will appear
	var $scroll = $('.tx-kiwislider-pi1 .scroll').css('overflow', 'hidden');
		
	// apply our left + right buttons
	$scroll
	
			//.before('<img class="scrollButtons left" src="uploads/tf/back.gif">')
			//.after('<img class="scrollButtons right" src="uploads/tf/vor.gif">');
			.before('<div class="scrollButtons kiwileft"><div class="genericon genericon-leftarrow">&nbsp;</div></div>')
	.after('<div class="scrollButtons kiwiright"><div class="genericon genericon-rightarrow">&nbsp;</div></div>');
		
	// handle nav selection
	function selectNav() {
			$(this)
		.parents('ul:first')
				.find('a')
			.removeClass('selected')
				.end()
		.end()
		.addClass('selected');
	}
		
	$('.tx-kiwislider-pi1 .navigation').find('a').click(selectNav);
		
	// go find the navigation link that has this target and select the nav
	function trigger(data) {
			var el = $('.tx-kiwislider-pi1 .navigation').find('a[href$="' + data.id + '"]').get(0);
			selectNav.call(el);
	}
		
	if (window.location.hash) {
			trigger({ id : window.location.hash.substr(1) });
	} else {
			$('.tx-kiwislider-pi1 ul.navigation a:first').click();
	}
		
	// offset is used to move to *exactly* the right place, since I'm using
	// padding on my example, I need to subtract the amount of padding to
	// the offset.  Try removing this to get a good idea of the effect
	var offset = parseInt((horizontal ? 
			$container.css('paddingTop') : 
			$container.css('paddingLeft')) 
			|| 0) * -1;

		
		
	var scrollOptions = {
			target: $scroll, // the element that has the overflow
		
			// can be a selector which will be relative to the target
			items: $panels,
		
			navigation: '.navigation a',
		
			// selectors are NOT relative to document, i.e. make sure they're unique
			//prev: 'img.left', 
			//next: 'img.right',
		prev: 'div.kiwileft', 
			next: 'div.kiwiright',
		    
		
			// allow the scroll effect to run both directions::: war: 			axis: 'xy',
			axis: 'xy',
		
			onAfter: trigger, // our final callback
		
			offset: offset,
		
			// duration of the sliding effect ::: hier stand: duration: 500,
			duration: 0,
		
			// easing - can be used with the easing plugin: 
			// http://gsgd.co.uk/sandbox/jquery/easing/   ::: war:    			easing: 'swing'
			
			easing: 'def'
	};
		
	// apply serialScroll to the slider - we chose this plugin because it 
	// supports// the indexed next and previous scroll along with hooking 
	// in to our navigation.
	$('.tx-kiwislider-pi1').serialScroll(scrollOptions);
		
	// now apply localScroll to hook any other arbitrary links to trigger 
	// the effect
	$.localScroll(scrollOptions);
		
	// finally, if the URL has a hash, move the slider in to position, 
	// setting the duration to 1 because I don't want it to scroll in the
	// very first page load.  We don't always need this, but it ensures
	// the positioning is absolutely spot on when the pages loads.
	scrollOptions.duration = 0;
	$.localScroll.hash(scrollOptions);
			});
	})(jQuery); 	// when the DOM is ready...
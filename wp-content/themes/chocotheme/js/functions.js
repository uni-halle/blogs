;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);

	$doc.ready(function() {
		var body = $('body'),
			sidebar = $('#sidebar'),
			expandedClass = 'expanded';

		// mobile navigation
		$('.open-sidebar').on('click', function(e) {
			sidebar.addClass(expandedClass);

			// Navigation swiping
			if ($('html').attr('dir') == 'rtl') {
				$('#sidebar.expanded').swipe({
					swipeLeft: function(event, direction, distance, duration, fingerCount) {
						$(this).removeClass(expandedClass);
					},
					threshold: 40
				});
			} else {
				$('#sidebar.expanded').swipe({
					swipeRight: function(event, direction, distance, duration, fingerCount) {
						$(this).removeClass(expandedClass);
					},
					threshold: 40
				});
			};

			e.preventDefault();
		});

		$('.close-sidebar').on('click', function(e) {
			sidebar.removeClass(expandedClass);

			e.preventDefault();
		});
	});
})(jQuery, window, document);
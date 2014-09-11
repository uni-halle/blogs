jQuery(document).ready(function($) {
	
	// PRETTY PHOTO
	$("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png'], a[rel='portfolio_group'], .portfolio_group a[rel=''], a[rel^='prettyPhoto']").prettyPhoto({
		theme:'pp_default',
		show_title:true,
		social_tools: false
	});
		
	// add rel value to all gallery items
	$('.gallery a').attr('rel', 'prettyPhoto[gallery]')
	
	// TOOLTIP
	$("#social_icons li a[title]").tooltip({effect: 'slide', slideSpeed: 200});
	
	// NAVIGATION	
	$('#menu_list').dcAccordion({
		classParent: 'menu_list_parent',
		eventType: 'hover',
		autoClose: false,
		menuClose: false,
		saveState: true,
		disableLink: false,
		hoverDelay: 0,
		classArrow: 'menu_list_icon',
		speed: 300
	});			
		
	// QUICKSAND PLUGIN
	function portfolio_quicksand() {
		
		// Setting Up Our Variables
		var $filter;
		var $container;
		var $containerClone;
		var $filterLink;
		var $filteredItems
		
		// Set Our Filter
		$filter = $('.filter li.active a').attr('class');
		
		// Set Our Filter Link
		$filterLink = $('.filter li a');
		
		// Set Our Container
		$container = $('ul.filterable-grid');
		
		// Clone Our Container
		$containerClone = $container.clone();
		
		// Apply our Quicksand to work on a click function
		// for each for the filter li link elements
		$filterLink.click(function(e) 
		{
			// Remove the active class
			$('.filter li').removeClass('active');
			
			// Split each of the filter elements and override our filter
			$filter = $(this).attr('class').split(' ');
			
			// Apply the 'active' class to the clicked link
			$(this).parent().addClass('active');
			
			// If 'all' is selected, display all elements
			// else output all items referenced to the data-type
			if ($filter == 'all') {
				$filteredItems = $containerClone.find('li'); 
			}
			else {
				$filteredItems = $containerClone.find('li[data-type~=' + $filter + ']'); 
			}
			
			// Finally call the Quicksand function
			$container.quicksand($filteredItems, 
			{
				// The Duration for animation
				duration: 750,
				// the easing effect when animation
				easing: 'easeInOutCirc',
				// height adjustment becomes dynamic
				adjustHeight: 'dynamic'
			});
			
			//Initalize our PrettyPhoto Script When Filtered
			$container.quicksand($filteredItems, 
				function () { lightbox(); }
			);			
		});
	}
		
	if(jQuery().quicksand) {
		portfolio_quicksand();	
	}
		
	function lightbox() {
		// Apply PrettyPhoto to find the relation with our portfolio item
		$("a[rel^='prettyPhoto']").prettyPhoto({
			// Parameters for PrettyPhoto styling
			animationSpeed:'fast',
			slideshow:5000,
			theme:'pp_default',
			show_title:false,
			overlay_gallery: false,
			social_tools: false
		});
	}
	
	if(jQuery().prettyPhoto) {
		lightbox();
	}
})
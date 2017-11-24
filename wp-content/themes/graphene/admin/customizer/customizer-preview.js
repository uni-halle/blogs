jQuery(document).ready(function($) {

	/* Add a custom style element to add our styling */
	$('head').append('<style type="text/css" id="graphene-preview-css"></style>');

	/* Site title and description */
	wp.customize('blogname', function(value){ value.bind(function(to){
		if ( $('.navbar-header .header_title a').length > 0 ) $('.navbar-header .header_title a').html( to );
		else $('.navbar-header .header_title').html( to );
	});	});

	wp.customize('blogdescription', function(value){ value.bind(function(to){
		$('.navbar-header .header_desc').html( to );
	});	});

	/* Header text colour */
	wp.customize('header_textcolor', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.header_title, .header_title a, .header_title a:visited, .header_title a:hover, .header_desc {color:' + to + '}');
	});	});
  
  	/* Header image height */
	wp.customize('graphene_settings[header_img_height]', function(value){
		value.bind(function(to){
			$('#header').css('max-height', to + 'px');
		});
	});

  	/* Slider height */
	wp.customize('graphene_settings[slider_height]', function(value){
		value.bind(function(to){
			$('#graphene-preview-css').append('@media (min-width: 768px){.carousel, .carousel .item{height:' + to + 'px;}}');
		});
	});

	/* Slider height (mobile) */
	wp.customize('graphene_settings[slider_height_mobile]', function(value){
		value.bind(function(to){
			$('#graphene-preview-css').append('@media (max-width: 767px){.carousel, .carousel .item{height:' + to + 'px;}}');
		});
	});
	
	/* Copyright text */
	wp.customize('graphene_settings[copy_text]', function(value){
		value.bind(function(to){
			$('#copyright').html(to);
		});
	});

	/* Container width */
	wp.customize('graphene_settings[container_width]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('@media (min-width: 1200px) {.container {width:' + to + 'px}}');
	});	});

	/* Columns width */
	wp.customize('graphene_settings[column_width]', function(value){ value.bind(function(to){
		colWidths = JSON.parse(to);
		
		if ( $('#sidebar1, #sidebar2').length == 0 ) return;

		$("#content-main, #sidebar1, #sidebar2").removeClass(function (index, className) {
		    return (className.match(/\bcol-md-\d+/g) || []).join(' ');
		});

		if ( $('#sidebar1, #sidebar2').length == 1 ) {
			$('#content-main').addClass('col-md-' + colWidths.two_col.content);
			$('#sidebar1').addClass('col-md-' + colWidths.two_col.sidebar);
		} else {
			$('#content-main').addClass('col-md-' + colWidths.three_col.content);
			$('#sidebar1').addClass('col-md-' + colWidths.three_col.sidebar_right);
			$('#sidebar2').addClass('col-md-' + colWidths.three_col.sidebar_left);
		}
	});	});

	
	/**
	 * Colours
	 */
	
	/* Top Bar */
	wp.customize('graphene_settings[top_bar_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#top-bar {background-color:' + to + '}');
	});	});


	/* Primary Menu */
	wp.customize('graphene_settings[menu_primary_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar, .navbar #header-menu-wrap, .navbar #header-menu-wrap {background:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_primary_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar-inverse .nav > li > a {color:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_primary_active_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #header-menu-wrap .nav li:focus, .navbar #header-menu-wrap .nav li:hover, .navbar #header-menu-wrap .nav li.current-menu-item, .navbar #header-menu-wrap .nav li.current-menu-ancestor, .navbar #header-menu-wrap .dropdown-menu li, .navbar #header-menu-wrap .dropdown-menu > li > a:focus, .navbar #header-menu-wrap .dropdown-menu > li > a:hover, .navbar #header-menu-wrap .dropdown-menu > .active > a, .navbar #header-menu-wrap .dropdown-menu > .active > a:focus, .navbar #header-menu-wrap .dropdown-menu > .active > a:hover, .navbar #header-menu-wrap .navbar-nav>.open>a, .navbar #header-menu-wrap .navbar-nav>.open>a:focus, .navbar #header-menu-wrap .navbar-nav>.open>a:hover, .navbar .navbar-nav>.active>a, .navbar .navbar-nav>.active>a:focus, .navbar .navbar-nav>.active>a:hover {background:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_primary_active_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #header-menu-wrap .navbar-nav>.active>a, .navbar #header-menu-wrap .navbar-nav>.active>a:focus, .navbar #header-menu-wrap .navbar-nav>.active>a:hover, .navbar #header-menu-wrap .navbar-nav>.open>a, .navbar #header-menu-wrap .navbar-nav>.open>a:focus, .navbar #header-menu-wrap .navbar-nav>.open>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a:focus, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a:focus, .navbar #header-menu-wrap .navbar-nav>li>a:focus, .navbar #header-menu-wrap .navbar-nav>li>a:hover {color:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_primary_dd_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar-inverse .nav ul li a {color:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_primary_dd_active_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar-inverse .nav .dropdown-menu li:hover > a, .navbar-inverse .nav .dropdown-menu li.current-menu-item > a, .navbar-inverse .nav .dropdown-menu li.current-menu-ancestor > a {color:' + to + '}');
	});	});


	/* Secondary Menu */
	wp.customize('graphene_settings[menu_sec_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap {background:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap, .navbar-inverse .dropdown-submenu > .dropdown-menu {border-color:' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu > li > a {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_active_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap .nav li:focus, .navbar #secondary-menu-wrap .nav li:hover, .navbar #secondary-menu-wrap .nav li.current-menu-item, .navbar #secondary-menu-wrap .nav li.current-menu-ancestor, .navbar #secondary-menu-wrap .dropdown-menu li, .navbar #secondary-menu-wrap .dropdown-menu > li > a:focus, .navbar #secondary-menu-wrap .dropdown-menu > li > a:hover, .navbar #secondary-menu-wrap .dropdown-menu > .active > a, .navbar #secondary-menu-wrap .dropdown-menu > .active > a:focus, .navbar #secondary-menu-wrap .dropdown-menu > .active > a:hover, .navbar #secondary-menu-wrap .navbar-nav>.open>a, .navbar #secondary-menu-wrap .navbar-nav>.open>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.open>a:hover {background-color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_active_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap .navbar-nav>.active>a, .navbar #secondary-menu-wrap .navbar-nav>.active>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.active>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.open>a, .navbar #secondary-menu-wrap .navbar-nav>.open>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.open>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a:focus, .navbar #secondary-menu-wrap .navbar-nav>li>a:focus, .navbar #secondary-menu-wrap .navbar-nav>li>a:hover {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_dd_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap .nav ul li a {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[menu_sec_dd_active_item]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.navbar #secondary-menu-wrap .nav .dropdown-menu li:hover > a, .navbar #secondary-menu-wrap .nav .dropdown-menu li.current-menu-item > a, .navbar #secondary-menu-wrap .nav .dropdown-menu li.current-menu-ancestor > a {color: ' + to + '}');
	});	});


	/* Slider */
	wp.customize('graphene_settings[slider_caption_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.carousel-caption {background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[slider_caption_text]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.carousel-caption, .carousel .slider_post_title, .carousel .slider_post_title a {color: ' + to + '}');
	});	});


	/* Content Area */
	wp.customize('graphene_settings[content_wrapper_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#content {background-color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[content_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.post, .singular .hentry {background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[meta_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.entry-footer {border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[content_font_colour]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('body, blockquote p {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[title_font_colour]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.post-title, .post-title a, .post-title a:hover, .post-title a:visited {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[link_colour_normal]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('a, .post .date .day, .pagination>li>a, .pagination>li>a:hover, .pagination>li>span, #comments > h4.current a, #comments > h4.current a .fa, .post-nav-top p, .post-nav-top a {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[link_colour_hover]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('a:focus, a:hover, .post-nav-top a:hover {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[sticky_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.sticky {border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[child_page_content_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.child-page {background-color: ' + to + ';}');
	});	});


	/* Widgets */
	wp.customize('graphene_settings[widget_item_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.sidebar .sidebar-wrap {background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[widget_header_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.sidebar .sidebar-wrap {border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[widget_list]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.sidebar ul li {border-color: ' + to + '}');
	});	});
	

	/* Buttons and Labels */
	wp.customize('graphene_settings[button_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.btn, .Button, .colour-preview .button, input[type="submit"], button[type="submit"], #commentform #submit, .wpsc_buy_button, #back-to-top {background: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[button_label]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.btn, .Button, .colour-preview .button, input[type="submit"], button[type="submit"], #commentform #submit, .wpsc_buy_button, #back-to-top {color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[label_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.label-primary, .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover, .list-group-item.parent, .list-group-item.parent:focus, .list-group-item.parent:hover {background: ' + to + '; border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[label_text]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.label-primary, .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover, .list-group-item.parent, .list-group-item.parent:focus, .list-group-item.parent:hover {color: ' + to + ';}');
	});	});


	/* Archives */
	wp.customize('graphene_settings[archive_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.post-nav-top, .archive-title, .page-title, .category-desc {background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[archive_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.post-nav-top, .archive-title, .page-title, .category-desc {border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[archive_label]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.archive-title span {color: ' + to + '}');
	});	});
	wp.customize('graphene_settings[archive_text]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('.page-title, .archive-title, .category-desc {color: ' + to + '}');
	});	});


	/* Comments */
	wp.customize('graphene_settings[comments_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#comments .comment, #comments .pingback, #comments .trackback {background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[comments_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#comments .comment, #comments .pingback, #comments .trackback {border-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[comments_box_shadow]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#comments .comment, #comments .pingback, #comments .trackback {box-shadow: 0 0 3px ' + to + ';}');
	});	});
	wp.customize('graphene_settings[comments_text]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#comments .comment, #comments .pingback, #comments .trackback {color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[author_comments_border]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#comments ol.children li.bypostauthor, #comments li.bypostauthor.comment {border-color: ' + to + '}');
	});	});


	/* Footer */
	wp.customize('graphene_settings[footer_bg]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#footer, .graphene-footer{background-color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[footer_text]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#footer, .graphene-footer{color: ' + to + ';}');
	});	});
	wp.customize('graphene_settings[footer_link]', function(value){ value.bind(function(to){
		$('#graphene-preview-css').append('#footer a, #footer a:visited {color: ' + to + '}');
	});	});

});
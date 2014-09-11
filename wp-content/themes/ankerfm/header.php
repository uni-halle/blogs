<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title>
<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Search Results', 'woothemes'); ?><?php } ?>
<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Author Archives', 'woothemes'); ?><?php } ?>
<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
<?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Tag Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
</title>

<!-- Skripte für airtime-widgets-->
	<script src="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/airtime-widgets/js/jquery-1.6.1.min.js" type="text/javascript"></script>
	<script src="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/airtime-widgets/js/jquery-ui-1.8.10.custom.min.js" type="text/javascript"></script>
	<script src="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/airtime-widgets/js/jquery.showinfo.js" type="text/javascript"></script>
	<link href="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/airtime-widgets/css/airtime-widgets.css" rel="stylesheet" type="text/css" />
	
<!-- Anfang Show Infos -->
<script>
$(document).ready(function() {
 $("#headerLiveHolder").airtimeLiveInfo({
            sourceDomain:"http://airtime.medienkomm.uni-halle.de",
   text: {onAirNow:"Jetzt on air", offline:"Offline", current:"Jetzt", next:"Danach"},
   updatePeriod: 20 //seconds
 });
});
</script>

<script>
$(document).ready(function() {
 $("#onAirToday").airtimeShowSchedule({
            sourceDomain:"http://airtime.medienkomm.uni-halle.de",
   text: {onAirToday:"ANKER FM"},
   updatePeriod: 5 //seconds
 });
});
</script>
<!-- Ende Skripte für airtime-widgets-->


<!-- Player-Script -->
<link href="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/player/skin/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />


<script type="text/javascript" src="http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/player/js/jquery.jplayer.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	$("#jquery_jplayer_1").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				mp3: "http://airtime.medienkomm.uni-halle.de:8000/airtime_128"
				oga: "http://airtime.medienkomm.uni-halle.de:8000/airtime_ogg"				
			}).jPlayer("play");
		},
		ended: function (event) {
			$(this).jPlayer("play");
		},
		swfPath: "http://blogs.urz.uni-halle.de/ankerfm/wp-content/themes/ankerfm/player/js",
		supplied: "mp3,oga"
	});
});
</script>
<!-- Ende Player-Script -->
    
    
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
   
	<!--[if IE 6]>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie6.css" />
    <![endif]-->	
	
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" />
	<![endif]-->
   
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

	<script type="text/javascript">
		Cufon.replace('h1, h2, h3', { fontFamily: 'ChunkFive' });
	</script>

</head>

<body>

<div id="container">

	<div id="navigation">
	
		<div class="col-full">
	
	        <!-- <ul id="catnav" class="nav fl">
	            <?php wp_list_categories('sort_column=menu_order&depth=3&title_li=&exclude='.get_option('woo_nav_exclude')); ?>
	        </ul> /#nav -->
	        
	        <div id="description"><?php bloginfo('description'); ?></div>
	        
	        <div id="topsearch" class="fr">
	   			<form method="get" id="searchform_top" action="<?php bloginfo('url'); ?>">
        			<input type="text" class="field" name="s" value="<?php _e('Search...', 'woothemes') ?>" onfocus="if (this.value == '<?php _e('Search...', 'woothemes') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search...', 'woothemes') ?>';}" />
       				<input type="submit" class="submit" name="submit" value="<?php _e('Search', 'woothemes'); ?>" />
 		  		</form>
 		  	</div><!-- /#topsearch -->
        
        </div><!-- /.col-full -->
        
	</div><!-- /#navigation -->
        
	<div id="header" class="col-full">
   
		<div id="logo" class="fl">
		<a href="<?php bloginfo('url'); ?>" class="site-title"><?php bloginfo('name'); ?></a>
	       
	      	<?php /* if(is_single() || is_page()) : ?>
	      		<span class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
	      	<?php else: ?>
	      		<h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
	      	<?php endif; */ ?>
	      	
	      	<?php /*	<span class="site-description"><?php bloginfo('description'); ?></span> */ ?>
	      	
		</div><!-- /#logo -->
	       
	   	<div id="pagenav" class="nav fr">
	   	
	   		<ul>
	   			<?php if (is_page()) { $highlight = "page_item"; } else {$highlight = "page_item current_page_item"; } ?>
	            <li class="b <?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'woothemes') ?></a></li>
		    	<?php wp_list_pages('sort_column=menu_order&depth=3&title_li=&exclude='.get_option('woo_nav_exclude')); ?>
		    	<li class="rss"><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><?php _e('RSS', 'woothemes') ?></a></li>
	    	</ul>
	    	
	    </div><!-- /#pagenav -->
	    <div id="header-image">
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>"><img class="title" src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo'); } else { bloginfo('t
emplate_directory'); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a>

	    </div>
       
	</div><!-- /#header -->

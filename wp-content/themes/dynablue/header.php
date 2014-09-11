<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if IE]>
<link rel="stylesheet" href="<?=bloginfo('template_url')?>/style-ie.css" type="text/css" media="screen" />
<script type="text/javascript">
	var png_blank = "<?=bloginfo('template_url')?>/images/transparent.gif";
</script>
<![endif]-->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>


	<!-- Main Menu -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.1.2.6.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jqueryslidemenu/jqueryslidemenu.js"></script>
	<!-- /Main Menu -->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/carousel/stepcarousel.js"></script>


<script type="text/javascript">

stepcarousel.setup({
	galleryid: 'board_carusel', //id of carousel DIV
	beltclass: 'belt', //class of inner "belt" DIV containing all the panel DIVs
	panelclass: 'board_item', //class of panel DIVs each holding content
	autostep: {enable:true, moveby:1, pause:<?php echo FEATURED_SPEED*1000; ?>},
	panelbehavior: {speed:500, wraparound:false, persist:false},
	defaultbuttons: {enable: false, moveby: 1, leftnav: ['http://i34.tinypic.com/317e0s5.gif', -5, 80], rightnav: ['http://i38.tinypic.com/33o7di8.gif', -20, 80]},
	statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
	contenttype: ['inline'] //content setting ['inline'] or ['external', 'path_to_external_file']
})

</script>

<?php wp_head(); ?>
</head>

<body>
<div id="page">

<div id="header">
	<div id="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a><span class="description"><?php bloginfo('description'); ?></span></div>
</div>

<div id="menu">
	<div id="mainmenu">
		<ul>
			<li class="first <? if(is_home()) echo 'current_page_item'; ?>"><a href="<?php echo get_option('home'); ?>/">Home</a></li>
			<?php wp_list_pages2('title_li=')?>
		</ul>
	</div>
    <div id="main_search">
        <form method="get" id="searchform_top" action="<?php bloginfo('url'); ?>/">
            <div>
                <input type="text" value="Type your search here..." name="s" id="searchform_top_text" onclick="this.value='';" />
                <input type="image" src="<?php bloginfo('template_url')?>/images/button_go.gif" id="gosearch" />
            </div>
        </form>
    </div>
</div>

<div id="board">
	<div id="board_left">
		<div id="board_items">
			<div id="board_body">
				<h2>Featured Posts</h2>
				<div id="board_carusel">
					<div class="belt">
					<?php $coint_i = carousel_featured_posts(FEATURED_POSTS); ?>
					</div>
				</div>
			</div>
			<ul id="board_carusel_nav">
				<li><a href="javascript:stepcarousel.stepBy('board_carusel', -1)"><img src="<?php bloginfo('template_url')?>/images/button_prev.png" alt="Prev" /></a></li>
				<li><a href="javascript:stepcarousel.stepBy('board_carusel', 1)"><img src="<?php bloginfo('template_url')?>/images/button_next.png" alt="Next" /></a></li>
			</ul>
		</div>
	</div>
	<div id="board_links">
		<a href="<?php bloginfo('rss2_url'); ?>" title="Rss"><img src="<?php bloginfo('template_url')?>/images/button_rss.png" alt="<?php bloginfo('name'); ?> Rss" /></a>
		<a href="<?php echo theme_twitter_link_show(); ?>"><img src="<?php bloginfo('template_url')?>/images/button_twitter.png" alt="" /></a>
	</div>
</div>

<div id="body">
<div id="body_top">
<div id="body_end">

	<div id="body_left">
    	<div id="body_left_content">
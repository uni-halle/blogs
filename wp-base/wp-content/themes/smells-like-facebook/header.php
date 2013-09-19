<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<meta name="home" content="<?php bloginfo('url') ?>" />
	<meta name="url" content="<?php bloginfo('wpurl') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<?php wp_head() // For plugins ?>
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory').'/ie.css' ?>" />
	<style type="text/css" media="screen">
		body{behavior:url(<?php echo get_bloginfo('template_directory').'/iehover.htc' ?>);}
	</style>
	<![endif]-->
</head>

<body>

<div id="wrapper">
	<div id="header">
		<div id="menu">
			<ul id="pagemenu">
				<?php echo slf_logo(); ?>
				<li><a href="<?php bloginfo('url') ?>">Home</a></li>
				<?php wp_list_pages('depth=2&sort_column=menu_order&title_li='); ?>
			</ul>
			
			<div id="more">
				<?php get_search_form(); ?>
				<ul id="moremenu">
					<li>
						<a href="#" onclick="return false">Feed</a>
						<ul id="feedmenu">
							<li class="feedli"><a class="notajax" href="<?php bloginfo('rss2_url'); ?>">Latest posts</a></li>
							<li class="feedli"><a class="notajax" href="<?php bloginfo('comments_rss2_url'); ?>">Latest comments</a></li>
						</ul>
					</li>
					<li>
					<?php wp_loginout(); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	
	<div id="container">
	
		<?php get_sidebar(); ?>
		
		<div id="content">
			<div id="title">
				<h1>
					<a href="<?php bloginfo('url') ?>" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
						<?php bloginfo('name') ?>
					</a>
				</h1>
				<div id="desc"><?php bloginfo('description') ?></div>
			</div>
			
			<div id="categories">
				<ul>
					<?php $showcats = abs(intval(get_option("slf_showcats"))); ?>
					<?php $showcats = ($showcats != 0) ? $showcats : 5; ?>
					<li class="cat-item<?php echo is_category() ? '' : ' current-cat' ?>"><a href="<?php bloginfo('url') ?>/">All</a></li>
					<?php wp_list_categories("orderby=count&order=desc&number=$showcats&depth=1&title_li="); ?>
					<li id="liplus">
						<a id="plus" href="#" title="More categories.." onclick="showbox(this); return false" class="none">&nbsp;</a>
						
						<div id="hiddencats" style="display: none;">
							<div id="hiddenleft"><div id="hiddenplus" onclick="hidebox()">&nbsp;</div></div>
							<div id="hiddenright">
								<ul>
									<li class="browsecat">Browse more categories</li>
									<?php wp_list_categories("orderby=count&order=desc&number=100&offset=$showcats&depth=1&title_li="); ?>
								</ul>
							</div>
						</div>
						
					</li>
				</ul>
			</div>
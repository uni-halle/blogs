<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title>
<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Search Results<?php } ?>
<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Author Archives<?php } ?>
<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
<?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
</title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" /><![endif]-->
<!--[if IE 6]>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>
<![endif]-->

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

</head>

<body id="woothemes">

	<div id="wrap">

		<div id="header">
			
            <ul id="nav" class="nav">

				<li<?php if ( is_home() ) : ?> class="current_page_item"<?php endif; ?>><a href="<?php echo get_option('home'); ?>/">Home</a></li>
				<?php 
                if (get_option('woo_nav')) 
                    wp_list_categories('sort_column=menu_order&depth=3&title_li=&exclude='); 
                else
                    wp_list_pages('sort_column=menu_order&depth=3&title_li=&exclude='); 
                ?>

			</ul>
		
			<form id="topSearch" class="search" method="get" action="<?php bloginfo('url'); ?>">
					
				<p class="fields">
					<input type="text" value="search" name="s" id="s" onfocus="if (this.value == 'search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search';}" />
					<button class="replace" type="submit" name="submit">Search</button>
				</p>

			</form>
			
            <div id="logo">
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>"><img class="title" src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo').'"'; } else { bloginfo('template_directory'); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a>
                <h1 class="replace"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>	
                		
            </div>
            
			<div id="hi">
			
				<p class="nomargin"><?php echo stripslashes( get_option( 'woo_about' ) ); ?></p>
				
				<p><a href="<?php echo stripslashes( get_option( 'woo_aboutlink' ) ); ?>">read more</a></p>
			
			</div>
			
		</div>
		
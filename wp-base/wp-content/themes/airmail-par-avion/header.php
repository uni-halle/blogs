<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <title><?php
        if ( is_single() ) { single_post_title(); }       
        elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
        elseif ( is_page() ) { single_post_title(''); }
        elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
        elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
        else { bloginfo('name'); wp_title('|'); get_page_number(); }
    ?></title>
	
	<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php wp_head(); ?>
	
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />	
	<script src="<?php bloginfo('template_url')?>/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url')?>/js/cufon-yui.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url')?>/js/Inkburrow_400.font.js" type="text/javascript">
	</script>
		<script type="text/javascript">
			Cufon.replace('.menu a');
		</script>
</head>

<body <?php body_class(); ?>>
<div id="stripe"></div>
<div id="wrapper" class="hfeed">
	<div id="header">

		<div id="masthead">
		
			<div id="branding">
				<div id="blog-title"><span><a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home">
					<?php bloginfo( 'name' ) ?>
				</a></span><div id="blog-description"><?php bloginfo( 'description' ) ?></div></div>
				
				<div id="rss-search">
					<a  href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_url')?>/images/subscribe.png"></a>
					<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
							<input type="text" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" size="15" />
							<input type="button" src="<?php bloginfo('template_url')?>/images/search.png" alt="Search" value="Search"/>
					</form>
				</div><!--rss-search-->

			</div><!-- #branding -->
			
			<div id="access">
				<div class="skip-link"><a href="#content" title="<?php _e( 'Skip to content', 'your-theme' ) ?>"><?php _e( 'Skip to content', 'your-theme' ) ?></a></div>
				<?php wp_page_menu(); ?>		
				<?php //wp_list_pages('title_li=&sort_column=menu_order&depth=1'); ?>

			</div><!-- #access -->
			
		</div><!-- #masthead -->	
	</div><!-- #header -->
	
	<div id="main">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.2.6.min.js"></script>
	
	<?php 
		ob_start(); 

		global $style, $options;
				
		if(!empty($_COOKIE['style']))
			$style = $_COOKIE['style'];
		else 
			$style = 'teal';
		
		wp_head(); 
	?>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $style; ?>.css" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>
<body>
	<a name="top"></a>
	<div id="footer-back"><div id="footer-bg">
		<div id="main">
			<div id="main-top">
				<div id="navigationed">
					<ul id="navigation" class="clearfix">
						<li<?php if(is_home()): ?> class="current_page_item"<?php endif; ?>><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">Home</a></li>
						<?php wp_list_pages('title_li=&depth=1'); ?>
					</ul>
				</div>
				<div id="container">
					<div id="content-back" class="clearfix"><div id="content-bottom">
						<div id="left-col">
							<div class="clearfix">
								<div id="colors">
									<a href="<?php bloginfo('template_directory'); ?>/switcher.php?style=teal" class="color-blue">Blue</a>
									<a href="<?php bloginfo('template_directory'); ?>/switcher.php?style=orange" class="color-orange">Orange</a>
									<a href="<?php bloginfo('template_directory'); ?>/switcher.php?style=green" class="color-green">Green</a>
									<a href="<?php bloginfo('template_directory'); ?>/switcher.php?style=pink" class="color-pink">Pink</a>
									<a href="<?php bloginfo('template_directory'); ?>/switcher.php?style=purple" class="color-purple">Purple</a>
								</div>
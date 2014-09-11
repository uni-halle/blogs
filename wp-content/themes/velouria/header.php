<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head profile="http://gmpg.org/xfn/11">

		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

		<title><?php if (is_single() || is_home() || is_page() || is_archive()) { ?><?php bloginfo('name'); ?> <?php } ?><?php wp_title('&minus;',true); ?></title>

		<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
		
		<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
		
		<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		
		<?php wp_get_archives('type=monthly&format=link'); ?>

		<?php wp_head(); ?>
	
	</head>
	
	<body>

		<ul class="menu">

		<?php if (is_home()) {
		
			echo "<li class=\"current_page_item\">Home</li>";
		
		} 
		
		else {
              
              echo "<li><a href=\""; bloginfo('url'); echo "\">Home</a></li>";
        }

		?>
								
			<?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?>
			
			<li><a href="<?php bloginfo('rss2_url'); ?>" class="rss" title="Subscribe (RSS)">RSS</a></li>
								
		</ul> 
		
		<?php $blog_title = get_bloginfo('name'); ?>
		
		<?php $blog_url = get_bloginfo('url'); ?>
			
		<?php if (is_home()) {
		
			echo "<h1>$blog_title</h1>";
		
		} 
		
		else {
              
              echo "<h1><a href=\"$blog_url\">$blog_title</a></h1>";
        }

		?>

		<div id="about">
		
			<?php if (is_home()) {
									
					echo "<p>"; bloginfo('description'); echo "</p>";
			
			}
			
			else if (is_single()) {
				
				while (have_posts()) : the_post();
									
					echo "<div class=\"quoteit\">"; the_excerpt(); echo "</div>";
									
				endwhile;
				
			}
			
			else  {
			
				echo "<p>"; bloginfo('description'); echo "</p>";
				
			}

			?>
									
		</div>
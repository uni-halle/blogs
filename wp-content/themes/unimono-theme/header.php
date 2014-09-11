<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>
		<?php if (is_home()) { 
		echo bloginfo('name');
		echo ' | ';
		echo bloginfo('description');
		} elseif (is_page('blog')) {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		} elseif (is_category()) {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		} elseif (is_search()) {
		echo 'Search Results';
		echo ' | ';
		echo bloginfo('name');
		} elseif ( is_day() || is_month() || is_year() ) {
		echo 'Archives:'; wp_title('');
		} else {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		}
		?>
	</title>
	
	<!-- Dynamic Description stuff -->
	<meta name="description" content="<?php if (have_posts() && is_single() OR is_page()):while(have_posts()):the_post();
	$out_excerpt = str_replace(array("\r\n", "\r", "\n"), "", get_the_excerpt());
	echo apply_filters('the_excerpt_rss', $out_excerpt);
	endwhile;
	else:
	bloginfo('name');
	echo ' - ';
	bloginfo('description');
	endif; ?>" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.png" type="image/x-icon" />
    
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php wp_head(); ?>
	
</head>
<body>
<div class="wrap">
    <div id="header">
        <h1 class="logo"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
        <p class="description"><?php bloginfo('description'); ?></p>
        
        <!-- the main nav menu -->
        <?php 
        wp_nav_menu(  array( 'theme_location' => 'header-menu' ));  ?>
        
        <!-- secondary meta menu -->
        <ul id="metamenu">
        <?php if ( is_user_logged_in() ) { ?>
        	<li><a class="dashboard" href="<?php echo site_url('/wp-admin/', 'https');?>">Admin</a></li>
        <?php } ?>
        	<li><?php wp_loginout($_SERVER['REQUEST_URI']); ?></li>
        </ul>

    </div><!-- END #header -->
    
	<div class="content">
		<div class="content_left">
			<div class="content_right">
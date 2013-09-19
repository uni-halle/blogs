<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

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
	
	<!-- Block the hungry search robots on some dynamic pages -->
	<?php if(is_search() || is_archive() ) { ?>
	<meta name="robots" content="noindex, nofollow" />
    <?php }?>
	
	<!-- This brings in the delicious styles -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>" type="text/css" media="screen" />
	<!--[if lt IE 9]>
		<link rel="stylesheet" media="screen" type="text/css" href="<?php bloginfo('template_directory') ?>/style/css/ie-old.css"  />
	<![endif]-->
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="shortcut icon" href="<?php bloginfo('template_directory') ?>/style/images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?php bloginfo('template_directory') ?>/style/images/favicon.ico" type="image/x-icon" />
	
	<!-- Mobile stuff -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- For iPhone 4 with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-114x114-precomposed.png">
	<!-- For first-generation iPad: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-72x72-precomposed.png">
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-precomposed.png">
	
	<?php wp_get_archives('type=monthly&format=link'); ?>

	<!-- Lets just hope plugin authors wont throw in too much scripts here in the head hook -->
	<?php wp_enqueue_script('thickbox'); ?>
	<?php 
	if ( is_single() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
	?>

</head>

<body <?php body_class(); ?>>
	<div id="top">
		<div id="topbar">
			<!-- Check if theres a custom menu, if not use the old page listing -->
			<?php if ( has_nav_menu( 'header-menu' ) ) { ?>
				
				<div class="topmenu">
					<?php wp_nav_menu(  array( 'theme_location' => 'header-menu' ));  ?>
				</div>
				
			<?php } else { ?>
			
				<?php wp_page_menu('show_home=1&menu_class=topmenu&sort_column=menu_order'); ?>
			
			<?php } ?>
		</div>
	</div>
	
	<div id="wrapper">
		<a id="subscribe" href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> <?php _e('Subscribe to RSS-Feed','blogsmlu'); ?>"><?php _e('Subscribe to RSS-Feed','blogsmlu'); ?></a>
		<div id="header">
			
			<div id="blogname">
				<h2><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h2>
				<p><?php bloginfo('description'); ?></p>
			</div>
			
		</div>
		
		<?php include('admin-menu.php'); ?>
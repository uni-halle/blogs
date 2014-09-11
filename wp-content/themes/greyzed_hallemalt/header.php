<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />    
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/validation.js"></script>
<style type="text/css" media="screen"></style>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page">
	<div id="nav">
		
		<!-- Check if theres a custom menu, if not use the old page listing -->
		<?php if ( has_nav_menu( 'main-menu' ) ) { ?>
			
			<div class="topmenu">
				<?php wp_nav_menu(  array( 'theme_location' => 'main-menu' ));  ?>
			</div>
			
		<?php } else { ?>
		
			<?php wp_page_menu('show_home=1&menu_class=topmenu&sort_column=menu_order'); ?>
		
		<?php } ?>
		
	</div>

<div id="header" role="banner">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	<div class="rss">stay updated via <a href="<?php if (get_option('greyzed_feedburner') == "#") { bloginfo('rss_url'); } else echo get_option('greyzed_feedburner');?>" title="RSS">rss</a> <?php if (get_option('greyzed_feedburner_email') != "#") { ?> or <a href="<?php echo get_option('greyzed_feedburner_email'); ?>">email</a><?php } ?></div>
</div>
<hr />

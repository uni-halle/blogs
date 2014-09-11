<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php if ( is_single() ) {
      wp_title('');
      echo (' | ');
      bloginfo('name');
 
} else if ( is_page() || is_paged() ) {
      bloginfo('name');
      wp_title('|');
 
} else if ( is_author() ) {
      bloginfo('name');
      wp_title(' | Archive for ');	  
	  
} else if ( is_archive() ) {
      bloginfo('name');
      echo (' | Archive for ');
      wp_title('');
 
} else if ( is_search() ) {
      bloginfo('name');
      echo (' | Search Results');
 
} else if ( is_404() ) {
      bloginfo('name');
      echo (' | 404 Error (Page Not Found)');
	  
} else if ( is_home() ) {
      bloginfo('name');
      echo (' | ');
      bloginfo('description');
 
} else {
      bloginfo('name');
      echo (' | ');
      echo (''.$blog_longd.'');
}
 ?></title>
	
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> 

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/nav.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/posts.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/print.css" type="text/css" media="print" />
<?php if (is_home() ) { ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/home.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/jquery.jcarousel.css" />
<?php include(TEMPLATEPATH . '/icons.php'); ?>
<?php } ?>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php 
ob_start();
bloginfo('template_url');
$template_url = ob_get_contents();
ob_end_clean();
?>

<?php 
wp_enqueue_script('dropdowns', $template_url . '/js/dropdowns.js', array('jquery'), '1.0'); 
if (is_home() ) {
	wp_enqueue_script('contact', $template_url . '/js/contact.js', array('jquery'), '1.0');
	wp_enqueue_script('jcarousel', $template_url . '/js/jquery.jcarousel.pack.js', array('jquery'), '0.2.3');
} 
?>

<?php wp_head(); ?>
</head>

<body<?php if (is_home()) { ?> id="home"<?php } else { ?> class="page-<?php echo $post->post_name; ?>" id="interior"<?php } ?>>

<?php if (is_home() ) { ?>
<?php if($_REQUEST['submit']): ?>
		<script type="text/javascript">
		jQuery(window).load(function () {
			  jQuery("#success-message").fadeOut(4000); 
		});
		</script>

		<p id="success-message"><?php echo get_option('contact_success'); ?></p>
		<?php endif; ?>
		<?php } ?>

<div id="top">  
<div class="wrapper">

<div id="navbar" class="clearfloat">
<ul id="nav">
<li<?php if(is_home()) { ?> class="current-cat"<?php } ?> id="nav-home"><a href="<?php bloginfo('home'); ?>/">Home</a></li>
<?php wp_list_categories('title_li='); ?>
</ul>

<ul id="toolbar" class="clearfloat">
<li><a href="#" id="icon-page" title="Browse Pages">Browse Pages</a>
	<ul class="children">
	<?php wp_list_pages('title_li=&depth=1'); ?>
	</ul>
</li>
<li><a href="<?php bloginfo('url'); ?>/archives" id="icon-archives" title="Browse Archives">Browse Archives</a></li>
<li><a href="#" id="icon-search" title="Search">Search</a>
	<ul class="children" id="searchmenu">
	<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
	</ul> 
</li>
<li class="last"><a href="<?php bloginfo('rss2_url'); ?>" id="icon-rss" title="RSS Feed">RSS</a></li>
</ul><!--END TOOLBAR-->
</div><!--END NAVBAR-->



		
<div id="branding">
<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
<div id="description"><?php bloginfo('description'); ?></div>
</div>


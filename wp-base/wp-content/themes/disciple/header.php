<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="icon" type="image/png" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" /> 
<!--[if lt IE 7]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="all" />
<![endif]-->

<!-- custom scripts -->

<!-- Deutsche Sprachdatei, nicht entfernen für die einwandfreie deutsche Anzeige. -->
<?php load_theme_textdomain('disciplede');?>

<!-- /custom scripts -->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' );?>

<?php wp_head(); ?>
</head>
<body>

<div id="topbar"></div>

<div id="wrap">

	<div id="c0">
		<div id="subscribe">
			<a href="<?php bloginfo('rss2_url'); ?>" target="_blank" alt=""><?php _e('subscribe','disciplede') ;?> <img src="<?php bloginfo('stylesheet_directory'); ?>/images/58.png" width="16" height="16" alt="" border="0"></a>
		</div>
	</div>
	
	<div id="c1">

		<div id="head">
			<div id="blog-name"><a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></div>
			<div id="blog-desc"><?php bloginfo('description'); ?></div>
		</div>

		<div id="menu">
			<ul>
				<li><a href="<?php echo get_settings('home'); ?>"><?php _e('Home','disciplede') ;?></a></li>
				<?php wp_list_pages('depth=1&title_li=');?>
				<li>&nbsp;</li>
			</ul>
		</div>

		<div id="c1-inner">

	<p style="text-align:center;">
	<?php if ( is_category() || is_day() || is_month() ||
				is_year() || is_search() || is_tag() ) {
	?>
		<?php /* If this is a tag archive */ if (is_tag()) { ?>
		<?php _e('Browsing the archives for the','disciplede') ;?> <b><?php single_tag_title(''); ?></b> <?php _e('tag','disciplede') ;?>

		<?php /* If this is a category archive */ } elseif (is_category()) { ?>
		<?php _e('Browsing the archives for the','disciplede') ;?> <b><?php single_cat_title(''); ?></b> <?php _e('category','disciplede') ;?>

		<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
		<?php _e('Brpwsing the blog archives for the day','disciplede') ;?> <B><?php the_time(__('l, F jS, Y') ); ?></B>

		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<?php _e('Browsing the blog archives for','disciplede') ;?> <B><?php the_time(__('F, Y') ); ?></B>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<?php _e('Browsing the blog archives for the year','disciplede') ;?> <B><?php the_time(__('Y') ); ?></B>

		<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
		<?php _e('Search result for','disciplede') ;?> <strong>'<?php the_search_query(); ?>'</strong>

		<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<?php _e('Browsing the blog archives','disciplede') ;?>

		<?php } ?>

	<?php }?>
	</p>
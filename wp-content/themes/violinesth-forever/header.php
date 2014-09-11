<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php if ( is_home() ) { ?><?php bloginfo('description'); ?> <?php bloginfo('name'); ?><?php } else { ?><?php wp_title(''); ?> - <?php bloginfo('name'); ?><?php } ?></title>

<link rel="stylesheet" href="<?php violinesth_css(); ?>" type="text/css" media="screen" />

<?php if ( get_bloginfo('language') == 'zh' ) { ?>
<style type='text/css'>
.narrowcolumn .entry { font-size: 1.2em;color:#777; }
#sidebars .submenu li, #sidebars #submenu li,#sidebars h4,#sidebars h2{font-size: 12px;}
</style>
<?php } ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if IE]>
<style type="text/css">
<!-- #sidebars .submenu, #sidebars #submenu{margin-left: -10px;} -->
</style>
<![endif]-->

<?php //if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body>
<div id="page">

<div id="title">
	<div class="description"><?php bloginfo('description'); ?>
		<form method="get" action="<?php bloginfo('url'); ?>/" class="topsearch">
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
			<input type="hidden" id="searchsubmit" value="Search" />
		</form>
	</div>

	<h1><?php if (is_home()) { ?>
			<a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
		<?php } elseif (is_category()) { ?>
			Category: <?php single_cat_title(''); ?>
		<?php } elseif (is_day()) { ?>
			Blog archives for the day <?php the_time('l, F jS, Y'); ?>
		<?php } elseif (is_month()) { ?>
			Blog archives for <?php the_time('F, Y'); ?>
		<?php } elseif (is_year()) { ?>
			Blog archives for the year <?php the_time('Y'); ?>
		<?php } elseif (is_search()) { ?>
			Blog result for <?php the_search_query(); ?>
		<?php } elseif( is_tag() ) { ?>
			Posts Tagged: <?php single_tag_title(); ?>
		<?php } else { ?>
			<?php wp_title('');?>
		<?php } ?></h1>
	
</div>

<div id="header">
	<div id="headerimg">
		<ul>
			<li<?php if ( is_home() || is_single() || is_search() || is_category() || is_day() || is_month() || is_year() || is_tag() ) { echo ' class="page_item current_page_item"'; } else { echo ' class="page_item"'; } ?>><a href="<?php echo get_option('home'); ?>/"><?php _e('Blog')?></a></li>
			<?php wp_list_pages('exclude=&depth=1&sort_column=menu_order&title_li='); ?>
		</ul>

		<div id="ap">
			<?php /* Uncomment following three line if you want show your avatar at top. */
				//echo get_avatar( get_bloginfo('admin_email'), 96 );
				//echo get_avatar( get_bloginfo('admin_email'), 96 );
				//echo get_avatar( get_bloginfo('admin_email'), 96 );
			?>
		</div>
	</div>
</div>
<hr />

<?php if ( ! get_option('violinesth') ) { ?>
<div class="leftcol">
	<div id="sidebars">
		<ul class="submenu">
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('left-sidebar') ) : ?>

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h4>Author</h4>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

			<li><h4>Archives</h4>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<?php wp_list_categories('show_count=1&title_li=<h4>Categories</h4>'); ?>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
				<?php wp_list_bookmarks(); ?>

			<?php } ?>

			<?php endif; ?>
		</ul>
	</div>
</div>
<?php } ?>
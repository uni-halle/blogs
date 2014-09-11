<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php dynamictitles(); ?></title>

<?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<meta name="description" content="<?php the_excerpt_rss(); ?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php endif; ?>

<?php if(is_search()) { ?>
<meta name="robots" content="noindex, nofollow" /> 
<?php }?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/print.css" type="text/css" media="print" />

<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/dropdowns.js"></script>

<?php if ( is_singular() ) wp_enqueue_script('comment-reply'); // support for comment threading ?>

<?php wp_head(); ?>

</head>


<body <?php if (is_home()) { ?>id="home" <?php } ?>class="<?php bodystyle(); ?>">

<div id="page">

<ul id="nav" class="clearfloat">
<li <?php if ( is_home() ) { ?> class="current_page_item"<?php } ?>><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','Mimbo'); ?></a></li> 
<?php wp_list_pages('title_li='); ?>
</ul>

<div id="wrapper" class="clearfloat">


<div class="clearfloat" id="masthead">
<h1><a href="<?php echo get_option('home'); ?>/" title="<?php _e('Return Home','Mimbo'); ?>"><?php bloginfo('name'); ?></a></h1>

<div id="description"><?php bloginfo('description'); ?></div>

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" /><input type="image" src="<?php bloginfo('template_url'); ?>/images/magnify.gif" id="searchsubmit" />
</form>
</div><!--END MASTHEAD-->


<ul id="nav-cat" class="clearfloat">
<?php wp_list_categories('title_li='); ?>
</ul>
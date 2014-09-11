<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php if(is_home() || is_search())  { bloginfo('name'); echo ' &raquo; '; bloginfo('description'); } else { wp_title(''); echo ' &raquo; '; bloginfo('name'); } ?></title>

<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/blueprint/print.css" type="text/css" media="print" />
<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_url') ?>/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() && get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<body<?php if (function_exists('body_class')) { echo ' '; body_class(); } ?>>

<div class='container headercontainer'>    
	<div class='span-24 last'>
        <div class="header">

			<?php if (function_exists('wp_nav_menu') && has_nav_menu('top-menu' )) {
                wp_nav_menu( array( 'theme_location' => 'top-menu', 'container' => '', 'menu_id' => 'pagemenu') );
            } else { ?>
            <ul id="pagemenu">
            	<li><a href="<?php bloginfo('url') ?>" title="Home">Home</a></li>
                <?php wp_list_pages('title_li=&depth=1' ); ?>
            </ul>
            <?php } ?>


			<div style="clear:both"></div>

			<div id="searchbox">
            <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
			<input type="text" name="s" id="s" class="search_input" value="<?php the_search_query(); ?>" />
			<input type="image" src="<?php bloginfo('template_url') ?>/images/transparent.gif" id="searchsubmit" />
			</form>
            </div>
            
			<div class="logo">
            <a href="<?php bloginfo('url') ?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="logo" /></a>
			<div class="description"><?php bloginfo('description'); ?></div>
            </div>
        </div>
    </div>
</div>
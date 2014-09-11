<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title> 
<?php if (is_home()) { ?>
<?php bloginfo('description'); ?>
: 
<?php bloginfo('name'); ?>
<?php }  ?>
<?php if (is_page()) { ?>
<?php wp_title(' '); ?>
<?php if(wp_title(' ', false)) { echo ' : '; } ?>
<?php bloginfo('name'); ?>
<?php }  ?>
<?php if (is_404()) { ?>
Page not found : 
<?php bloginfo('name'); ?>
<?php }  ?>
<?php if (is_archive()) { ?>
<?php wp_title(' '); ?>
<?php if(wp_title(' ', false)) { echo ' : '; } ?>
<?php bloginfo('name'); ?>
<?php }  ?>
<?php if(is_search()) { ?>
<?php echo wp_specialchars($s, 1); ?>: 
<?php bloginfo('name'); ?>
<?php } else if (is_single()){ ?>
<?php { 
wp_title(' ');
if(wp_title(' ', false)) { echo ' : '; }
single_cat_title();
echo " : "; 
bloginfo('name');
} ?>
<?php } ?>
</title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> 
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/dhtml.js"> </script>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>

<body>
<div id="main">
	<div class="header">
    
        <div class="title">
        	<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('title'); ?></a></h1>
     	</div>
        <div id="header_bg"><h3><?php bloginfo('description'); ?></h3></div>
    </div><!-- close header -->
    
		
    <div class="content">
    	<div class="content_bg">
        	<div class="content_top">
        
                <div class="innerContent">
                	
                    <div class="nav_section">
                    
                        <div class="nav">
                            <ul id="nav">
                                <?php if(is_home()) { ?>
                                    <li class="current_page_item"><a href="<?php bloginfo('url');?>">Home</a></li>
                                <?php } else { ?>
                                    <li><a href="<?php bloginfo('url');?>">Home</a></li>
                                <?php } ?>
                                <?php wp_list_pages('title_li'); ?>
                            </ul>
                        </div>
                        
                        <div class="search1">
                            <form action="<?php bloginfo('url'); ?>" method="get">
                                <input class="txt" type="text" name="s" value="Search..." onfocus="this.value=(this.value=='Search...') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Search...' : this.value;" />
                                <div style="clear:both"></div>
                             </form>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                            
                    <div id="main_content">
                    
                    

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<meta http-equiv="content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.png" type="image/x-icon" />
    
	<?php wp_get_archives('type=monthly&format=link'); ?>
<script type="text/javascript">
/* <![CDATA[ */
startList = function() {
if (document.all&&document.getElementById) {
navRoot = document.getElementById("nav");
for (i=0; i<navRoot.childNodes.length; i++) {
node = navRoot.childNodes[i];
if (node.nodeName=="LI") {
node.onmouseover=function() {
this.className+=" over";
  }
  node.onmouseout=function() {
  this.className=this.className.replace(" over", "");
   }
   }
  }
 }
}
window.onload=startList;
/* ]]> */
</script>
	<?php wp_head(); ?>
</head>
<body>
<div class="whole">
    <div class="topper">        	
    </div> 
    <div class="wrap">
    	<div class="container">
        	<div class="span-16 logo">
            	<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
                <h5><?php bloginfo('description'); ?></h5>                
            </div>
            <div class="span-8 last search_box">
            	<div class="span-5 margin_left_10" style="margin-top:17px;">
                    <form method="get" class="sidebar_search" id="searchform" action="<?php bloginfo('url'); ?>/">
                    	<input type="text" class="search_input" name="s" id="s" /><input type="submit" class="search_btn" value=" "/>                      
                    </form>
                </div>
                <div class="rss"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe to RSS</a></div>
            </div>       
            <div class="clear"></div> 
            <div class="span-24 menu">
                <div class="span-24 menu_left">
                    <div class="span-24 menu_right">
                    	<ul>
                        	<?php wp_list_pages('hide_empty=0&title_li='); ?>
                        </ul>
                    </div>  
                </div>  
            </div>  
            <div class="clear"></div>                
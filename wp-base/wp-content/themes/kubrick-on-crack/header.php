<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link href="<?php bloginfo('template_directory'); ?>/thickbox.css" type="text/css" media="screen" rel="stylesheet" charset="utf-8" />	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!--[if lt IE 7]>       
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/unitpngfix.js"></script>
	<link href="<?php bloginfo('template_directory'); ?>/forie6.css" rel="stylesheet" type="text/css" media="screen" title="no title" charset="utf-8"/>	
	<![endif]--> 

	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	
	
	<?php wp_head(); ?>	
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/jquery.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/thickbox.js"></script>
	
</head>

<body>
<div id="wrap">
<?
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<div id="header">
<div id="rss-sprites">
	<ul>
		<li><a id="srch" href="#TB_inline?height=50&width=310&inlineId=searchform" title="What Are You Looking For?" class="thickbox">Search</a></li>
		<li><a id="twt" href="http://twitter.com/<?php echo $koc_twt_acct; ?>">Twitter</a></li>
		<li><a id="rss" href="<?php if (!$koc_cst_rss){bloginfo('rss2_url');}else{echo $koc_cst_rss;}?>">RSS</a></li>
	</ul>
</div><!--rss-sprites-->
<a href="<?php bloginfo('url'); ?>"><img src="<?php if (!$koc_header_logo){ 
											 	bloginfo('template_directory');
												echo "/images/logo-default.png";											
												}else{
												echo $koc_header_logo;?>
												<?php } ?>" /></a>
<div id="searchform">
<form method="get" action="<?php bloginfo('home'); ?>/">
<input name="s" type="text" class="inputs" id="s" value="<?php echo wp_specialchars($s, 1); ?>" size="28" />
<input type="submit" class="go" value="" />	     
</form>
</div><!--searchform-->



<div id="nav">
<ul>
<li class="<?php if (is_front_page()){echo "current_page_item";}?>"><a href="<?php bloginfo('url');?>">Home</a></li>
<?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?>
</ul>
</div><!--nav-->
</div><!--header-->

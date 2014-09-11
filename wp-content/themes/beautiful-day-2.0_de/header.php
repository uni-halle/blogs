<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>
<?php bloginfo('name'); ?>
<?php if ( is_single() ) { ?>
&raquo; Blog Archiv
<?php } ?>
<?php wp_title(' &raquo; '); ?>
</title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php wp_head(); ?>
<script type="text/javascript">
<!--
// IE flicker fix

try {
document.execCommand('BackgroundImageCache', false, true);
} catch(e) { /* unless it's not IE6... */ }
//-->
</script>
</head>
<body>
<div class="top">
  <div class="header">
    <div class="left">
      <?php bloginfo('name'); ?>
    </div>
    <div class="right">
      <h2>
        <?php bloginfo('description'); ?>
      </h2>
    </div>
  </div>
</div>
<div class="container">
<div class="navigation">
  <ul>
    <li><a href="<?php bloginfo('url'); ?>/">Home</a></li>
    <?php wp_list_pages('title_li=&depth=1');?>
  </ul>
  <div class="clearer"></div>
</div>

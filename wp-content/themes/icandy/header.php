<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<?php $options = get_option('candy_options'); ?>
<!--The Favicon-->
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.png" type="image/x-icon" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/dropdown.css" type="text/css" media="screen" />
<!--[if IE]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" type="text/css" media="screen" />
<![endif]-->
<style type="text/css">
   body { background-color: #<?php if(trim($options['backcolor']) == "") echo "111111"; else echo str_replace('#','',$options['backcolor']); ?>; }
   <?php if($options['twocolcat'] != true and $options['twocolcat'] != false) { $options['twocolcat'] = true; }
      if($options['twocolcat']) { ?>
   /* two column categories */
   #sidebar ul li .cat-item { float: left; width: 43%; }
   #sidebar ul ul .cat-item .children { display: none; }
   <?php } ?>
   <?php
      if(trim($options['sidebarwidth']) == "") $options['sidebarwidth'] = 270;
      $sidebarwidth = intval($options['sidebarwidth']);
      $containerwidth = 860 - $sidebarwidth;
   ?>
   <?php if($options['sidebarloc'] == 'none') { ?>
   #container { width: 860px; }
   <?php } else { ?>
   #sidebar { width: <?php echo $sidebarwidth; ?>px; }
   #container { width: <?php echo $containerwidth; ?>px; }
   <?php } ?>
</style>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_enqueue_script("jquery"); ?>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/dropdown.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/candytheme.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
 mainmenuid: "smoothmenu1", //menu DIV id
 orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
 classname: 'ddsmoothmenu', //class added to menu's outer DIV
 contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
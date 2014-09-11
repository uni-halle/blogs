<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html>
<head>
    <?php get_header(); ?>
    <?php include(TEMPLATEPATH . "/initoptions.php"); ?>
</head>
<body>
<div id="wrapper">
    <?php include(TEMPLATEPATH . "/menu.php"); ?>
    <?php include(TEMPLATEPATH . "/banner.php"); ?>
    <?php if($options['sidebarloc'] == 'left') { ?>
    <?php get_sidebar(); ?>
    <?php } ?>
    <div id="container">
        <?php include(TEMPLATEPATH . "/postlist.php"); ?>
    </div>
    <?php if($options['sidebarloc'] == 'right') { ?>
    <?php get_sidebar(); ?>
    <?php } ?>
    <div class="clear"></div>
    <?php get_footer(); ?>
</div>
<script type="text/javascript">a2a_linkurl="<?php the_permalink(); ?>";a2a_onclick=1;a2a_color_main="c6fac1";a2a_color_border="c1dbae";a2a_color_link_text="333333";a2a_color_link_text_hover="0f9f11";a2a_color_bg="f8f7f7";</script><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
</body>
</html>

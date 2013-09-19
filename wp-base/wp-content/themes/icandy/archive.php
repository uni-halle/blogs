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
        <?php if($options['templatetitleshow']) { ?><h2 class="templatetitle"><?php _e('All articles in category:'); ?> &quot;<?php the_category(','); ?>&quot;</h2><?php } ?>
        <?php include(TEMPLATEPATH . "/postlist.php"); ?>
    </div>
    <?php if($options['sidebarloc'] == 'right') { ?>
    <?php get_sidebar(); ?>
    <?php } ?>
    <div class="clear"></div>
    <?php get_footer(); ?>
</div>
</body>
</html>

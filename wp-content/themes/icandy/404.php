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
        <h2 class="notfoundtitle">Error 404 - Page not found !</h2>
        <p style="margin: 20px 0 0 68px;">Try using the search to find what you are looking for.</p>
    </div>
    <?php if($options['sidebarloc'] == 'right') { ?>
    <?php get_sidebar(); ?>
    <?php } ?>
    <div class="clear"></div>
    <?php get_footer(); ?>
</div>
</body>
</html>

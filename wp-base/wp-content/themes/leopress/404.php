<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="maincontent">

	<div class="post">
		<h1>Not Found - Error 404</h1>
		<p>Sorry, but you are looking for something that isn't here.</p>
 		<p>Please try to make a new search or go to <a href="<?php bloginfo('home'); ?>" title="Homepage" rel="home">homepage.</a></p>
	</div>
    
</div><!-- #maincontent -->
<?php include (TEMPLATEPATH . '/sidebar-extra.php'); ?>

<?php get_footer(); ?>

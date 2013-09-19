<?php
if(!isset($_REQUEST['nofl'])){
;?>

<?php if (have_posts()) : ?>

<!--		<h2>Search Results for &#8216;<?php the_search_query(); ?>&#8217;</h2>-->


<?php print "<?xml version='1.0' encoding='UTF-8' ?>\n";?>
 <?php print "<studylog>\n";?>		
<?php while (have_posts()) : the_post(); ?>

 <?php print "<log>\n";?>
 <?php print "<id>";?><?php the_ID(); ?><?php print "</id>\n";?>
 <?php print "</log>\n";?>

  <?php endwhile; ?>

 <?php print "</studylog>\n";?>
 
	<?php endif; ?>


<?php
} ?>
<?php
if(isset($_REQUEST['nofl'])){
;?>


<?php get_header(); ?>
	

<?php get_sidebar(); ?>

<?php get_footer(); ?>



<?php
} ?>
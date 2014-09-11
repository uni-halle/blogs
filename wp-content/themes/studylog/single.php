<?php get_header(); ?>
<?php if(isset($_REQUEST['nofl'])) { ?>
	<?php get_sidebar(); ?>	
	<div id="htmlcontent">
<?php }  else {  ?>
	<div id="content">
<?php }  ?>

<?php if( isset($_REQUEST['nofl']) || isset($_REQUEST['tb_content']) ) { ?>   
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>&bookmark" title="<?php the_title(); ?>" ><?php the_title(); ?><a></h2>
			
			<div class="postmetadata"><?php the_time(get_option('date_format'))  ?>, <?php the_author(); ?><?php edit_post_link('Bearbeiten', ' | ', ''); ?><br /><?php the_tags('Tags: ',', ','<br />'); ?></div>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>	
			</div>


<?php if ( function_exists('wp_related_posts') ) { 
	echo ('<div class="relatedposts">');
	wp_related_posts();  
	echo ('</div>');
	} ?>

			
		</div>

	<?php comments_template(); ?>

	<?php endwhile; ?>
		
	

	<?php else: ?>

		<p>Kein Eintrag.</p>

<?php endif; ?>

	</div>

<?php }  ?>	

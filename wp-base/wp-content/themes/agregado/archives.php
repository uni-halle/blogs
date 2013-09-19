<?php
/*
Template Name: archives
*/
?>
<?php get_header(); ?>


<div id="main" class="clearfloat">
	
  <div id="content" class="entry clearfloat">
    
	<h2>Site Archives</h2>
		
		
				<?php
 
	// Declare some helper vars
	$previous_year = $year = 0;
	$previous_month = $month = 0;
	$ul_open = false;
	 
	// Get the posts
	$myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
	 
	?>
 
<?php foreach($myposts as $post) : ?>	
 
	<?php
 
	// Setup the post variables
	setup_postdata($post);
 
	$year = mysql2date('Y', $post->post_date);
	$month = mysql2date('n', $post->post_date);
	$day = mysql2date('j', $post->post_date);
 
	?>
 
	<?php if($year != $previous_year || $month != $previous_month) : ?>
 
		<?php if($ul_open == true) : ?>
		</ul>
		
		<?php endif; ?>
 		
		
		<br />
		<h3><?php the_time('F Y'); ?></h3>
 
		<ul id="archive-list" class="clearfloat">
 
		<?php $ul_open = true; ?>
 
	<?php endif; ?>
 
	<?php $previous_year = $year; $previous_month = $month; ?>
 
	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span class="commentcount">(<?php comments_number('0', '1', '%'); ?>)</span></li>
 
<?php endforeach; ?>
			</ul>	
				
		
				</div>	
	
	

<?php get_sidebar(); ?>
</div><!--END MAIN-->
	
	
	</div><!--END WRAPPER-->
</div><!--END TOP-->

<?php get_footer(); ?>

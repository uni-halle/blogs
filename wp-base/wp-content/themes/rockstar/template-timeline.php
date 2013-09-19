<?php
/*
Template Name: Timeline
*/
?>

<?php get_header(); ?>
	
	<div id="content">
	
<?php query_posts('posts_per_page=-1');
	$dates_array 			= Array();
	$year_array 			= Array();
	$i 				= 0;
	$prev_post_ts    		= null;
	$prev_post_year  		= null;
	$distance_multiplier 	        =  9;
?>
	
<div class="post">
	
	<h2><?php the_title(); ?></h2>
	
	<div id="archives" class="entry">	

	<?php while (have_posts()) : the_post();

		$post_ts    =  strtotime($post->post_date);
		$post_year  =  date( 'Y', $post_ts );

		/* Handle the first year as a special case */
		if ( is_null( $prev_post_year ) ) {
			?>
			<h4 class="archive_year"><?=$post_year?></h4>
			<ul class="archives_list">
			<?php
		}
		else if ( $prev_post_year != $post_year ) {
			/* Close off the OL */
			?>
			</ul>
			<?php

			$working_year  =  $prev_post_year;

			/* Print year headings until we reach the post year */
			while ( $working_year > $post_year ) {
				$working_year--;
				?>
				<h3 class="archive_year"><?=$working_year?></h3>
				<?php
			}

			/* Open a new ordered list */
			?>
			<ul class="archives_list">
			<?php
		}

		/* Compute difference in days */
		if ( ! is_null( $prev_post_ts ) && $prev_post_year == $post_year ) {
			$dates_diff  =  ( date( 'z', $prev_post_ts ) - date( 'z', $post_ts ) ) * $distance_multiplier;
		}
		else {
			$dates_diff  =  0;
		}
	?>
		<li style="margin-top:<?=$dates_diff?>px"><span class="date"><?php the_time('F j'); ?><sup><?php the_time('S') ?></sup></span> <a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <?php comments_popup_link('(0)', '(1)', '(%)'); ?></li>
	<?php
		/* For subsequent iterations */
		$prev_post_ts    =  $post_ts;
		$prev_post_year  =  $post_year;
	endwhile;

	/* If we've processed at least *one* post, close the ordered list */
	if ( ! is_null( $prev_post_ts ) ) {
		?>
	</ul>
	<?php } ?>
	
	</div><!--entry-->

	</div><!--post-->	
	
	</div><!--content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
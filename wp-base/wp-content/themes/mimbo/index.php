<?php get_header(); ?>

<?php if (have_posts()) { ?>

<div id="content">

 <?php
	$postCount = 0;
	$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	query_posts( 'paged=$page&post_per_page=-1&cat=' . get_query_var('cat') );
	while (have_posts()) { the_post(); 
		if( $postcount == 0 ) { 
		//GETS LATEST OR STICKY POST
	?>
	        
    <div id="lead" class="clearfloat">
			 
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
<?php echo get_post_image (get_the_id(), '', '', '' .get_bloginfo('template_url') .'/scripts/timthumb.php?zc=1&amp;w=260&amp;h=230&amp;src='); ?></a>
    
	<div id="lead-text">
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
    <?php the_title(); ?></a> <span class="commentcount"> (<?php comments_popup_link('0', '1', '%'); ?>)</span></h2>
    
   
    <p class="date"><?php the_time('n/d/y'); ?> &bull; </p>
	<?php the_excerpt(); ?>
	</div>
			</div><!--END LEAD/STICKY POST-->
			
		
		<div id="more-posts">
		<h3><?php _e('Recent Posts','Mimbo'); ?></h3>
		
		<?php
		}
		elseif( $postcount > 0 && $postcount <= 4 ) { 
		//GETS NEXT FOUR EXCERPTS
		?>
			
		<div class="clearfloat recent-excerpts">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
<?php echo get_post_image (get_the_id(), '', '', '' .get_bloginfo('template_url') .'/scripts/timthumb.php?zc=1&amp;w=105&amp;h=85&amp;src='); ?></a>

<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> <span class="commentcount">(<?php comments_popup_link('0', '1', '%'); ?>)</span></h4>

<p class="date"><?php the_time('n/d/y'); ?> &bull; </p>
			<?php the_excerpt(); ?>
		</div>
						
<?php //GETS NEXT HEADLINES
		}
		else { 
			ob_start();
			echo '<li><a href="'; 
			the_permalink();
			echo '">';
			the_title();
			echo '</a></li>';
			$links[] = ob_get_contents();
			ob_end_clean();			
		}
		$postcount ++;
		}
	}
	else {
?>

<?php } ?>
	
	
<?php 
	if(count($links)): ?>

	 <h3><?php _e('Older Posts','Mimbo'); ?></h3>
	 <ul class="headlines"><?php echo join("\n", $links); ?></ul>
			
	<?php endif; ?>
	</div><!--END RECENT/OLDER POSTS-->
	
    
    
	<div id="featured-cats"> 
	<h3><?php _e('Featured Categories','Mimbo'); ?></h3>

		<?php
        $display_categories = get_option('openbook_cats');
        foreach ($display_categories as $category) { 
        $showposts = get_option('openbook_featured_posts');	
        query_posts("showposts=$showposts&cat=$category");
        ?>

<h5><a href="<?php echo get_category_link($category);?>"><?php single_cat_title(); ?>&raquo;</a></h5>

        <ul>
        <?php while (have_posts()) : the_post(); ?>
        <li class="clearfloat"><p class="date"><?php the_time('n/d/y'); ?> &bull; </p><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
        </ul>
	<?php } ?>
    
</div><!--END FEATURED CATS-->

	
</div><!--END CONTENT-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

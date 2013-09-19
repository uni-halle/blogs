<?php get_header(); ?>

    <?php if (is_search()) {
    
        echo "<h2 id='page-sub-title'>Search Results</h2>";
    
    } elseif (is_month()) {
    
        echo "<h2 id='page-sub-title'>Archive of "; ?> <?php the_time('F, Y') ?> <?php echo "</h2>";
    
    } ?> 

		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		
		<div class="post clearfix" id="post-<?php the_ID(); ?>">
			<p class="byline">
				<span class="date"><?php the_time('F jS, Y'); ?></span><br /><em>by</em> <?php the_author(); ?>
			</p>		
			<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<p class="dots clear">.........................................................</p>
			
			<?php if ((!is_search()) and (!is_archive())) { ?>
			<span class="para">&#182;</span>
			<div class="entry">
				<?php the_content(''); ?>
				<p class="continue"><a href="<?php the_permalink(); ?>"><em>Continue Reading</em></a></p>
			</div>
			<?php } ?>
				
		</div> <!-- .post -->
		
		<?php endwhile; else : ?>
		
		<div class="post">
				
			<p class="byline">
				Total<br />Failure
			</p>
			<h2 class="title">Nothing Found</h2>
			
			<br /><br />
				
		</div> <!-- .post -->
		
		<?php endif; ?>
			
		<div class="navigation clear">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>		

<?php get_footer(); ?>
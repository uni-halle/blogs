<?php if($_GET['ajax'] == '1'){ ?>  
  	
  	<?php global $wp_query;
	query_posts(
		array_merge(
			array('showposts' => 5),
			$wp_query->query
		)
	); ?>

    <?php if (have_posts()) : ?>  
  
        <?php while (have_posts()) : the_post(); ?>  
  
            <div class="search-result">  
                <h4 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>  
            </div>
  
        <?php endwhile; ?>
        
        	<a id="more-results" href="<?php bloginfo('home'); ?>?s=<?php echo htmlentities(html_entity_decode($_GET['s'])); ?>"><?php _e('All Results &raquo;','blogsmlu') ?></a>
  
    <?php else : ?>  
  
        <h4><?php _e('Nothing found.','blogsmlu') ?> <?php _e('Try a different search?','blogsmlu') ?></h4>  
  
    <?php endif; ?>  
  
<?php }else{ ?>

	<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : ?>
		
		<h2 class="pagetitle"><?php _e('Search results for ','blogsmlu') ?> '<?php the_search_query(); ?>'</h2>
		
		<?php get_search_form(); ?>
		
		<?php while (have_posts()) : the_post(); ?>
		
		<div <?php post_class() ?>>
		
			<p class="date"><?php the_time('j.'); ?>
				<span><?php the_time('M'); ?></span>
				<?php the_time('Y'); ?>
			</p>
			<h2 id="post-<?php the_ID(); ?>" class="posttitle"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			
				<div class="entry">
				
					<?php the_excerpt(); ?>
			
				</div>
		
			<p class="postmetadata"><?php if( !is_author()){?><?php _e('Written by ','blogsmlu'); ?> <?php the_author_posts_link(); ?><br /><?php } ?><?php the_category(', ') ?> | <a href="<?php comments_link(); ?>" title=""><?php comments_number(__( 'No Comments', 'blogsmlu'), __('1 Comment', 'blogsmlu'), __('% Comments', 'blogsmlu'));?></a> <?php edit_post_link(__('Edit ', 'blogsmlu'), ' | ', ''); ?></p>	
		
		</div>
		
		<?php endwhile; ?>
		
			<div class="pagenavigation2">
				<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','blogsmlu')); ?></div>
				<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','blogsmlu')); ?></div>
			</div>
		
		<?php else : ?>
		
			<h2><?php _e('Nothing found.','blogsmlu') ?></h2>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<h2><?php _e('Try a different search?','blogsmlu') ?></h2>
			<?php get_search_form(); ?>
			<?php endif; ?>
	</div>
	<?php get_sidebar('Sidebar'); ?>
	<?php get_footer(); ?>

<?php } ?> 
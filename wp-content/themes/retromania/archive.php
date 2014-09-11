<?php get_header(); ?>
<!-- Mainbar -->
   <div class="mainbar">
    <div class="mainbar_top">
        <div class="mainbar_bottom">
            <div class="mainbar_inner">
    <?php if (have_posts()) : ?>
    
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h1 class="search_results">Archive for the '<?php echo single_cat_title(); ?>' Category</h1>
		
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="search_results">Archive for <?php the_time('F jS, Y'); ?></h1>
		
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="search_results">Archive for <?php the_time('F, Y'); ?></h1>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="search_results">Archive for <?php the_time('Y'); ?></h1>
		
		<?php /* If this is a search */ } elseif (is_search()) { ?>
		<h1 class="search_results">Search Results</h1>
		
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="search_results">Author Archive</h1>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="search_results">Blog Archives</h1>

		<?php } ?>
               
      <?php while (have_posts()) : the_post(); ?>
        
        <div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
            <div class="post_date">
                <div class="post_date_top"><?php the_time('d')?></div>
                <div class="post_date_bottom"><?php the_time('M') ?></div>                                            
            </div>
            <h2 class="post_header" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
            <div class="post_line"></div>
            <div class="post_content">
                <?php the_content('Read more...'); ?> 
                <?php the_tags( '<div class="tags">Tags: ', ', ', '</div> '); ?>
                <!--
                <?php trackback_rdf(); ?>
                -->
            </div>
            <div class="post_data">
            posted by <?php the_author() ?> in <?php the_category(',') ?> and have <?php comments_popup_link('No Comments', 'Comment (1)', 'Comments (%)'); ?> <?php edit_post_link('Edit',''); ?>
            
            </div>  
        </div>
        
        <?php endwhile; ?>
		<div class="navigation">
            <h5 class="float-left">
                <?php previous_posts_link('&larr; Older Entries') ?>
            </h5>
            <h5 class="float-right">
                <?php next_posts_link('Newer Entries &rarr;') ?>
            </h5>
            <div class="clear"></div>
        </div>
		<?php else :
		
				if ( is_category() ) { // If this is a category archive
					printf("<h1>Not Found</h1><p>Sorry, but there aren't any posts in the %s category yet.</p>", single_cat_title('',false));
				} else if ( is_date() ) { // If this is a date archive
					echo("<h1>Not Found</h1><p>Sorry, but there aren't any posts with this date.</p>");
				} else if ( is_author() ) { // If this is a category archive
					$userdata = get_userdatabylogin(get_query_var('author_name'));
					printf("<h1>Not Found</h1><p>Sorry, but there aren't any posts by %s yet.</p>", $userdata->display_name);
				} else {
					echo("<h1>Not Found</h1><p>No posts found.</p>");
				}
				
		
			endif;
		?>
		
	                
            </div>
        </div>
    </div>
</div>  

<?php get_sidebar(); ?>
<?php get_footer(); ?>
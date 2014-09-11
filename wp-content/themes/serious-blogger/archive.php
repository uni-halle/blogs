<?php get_header(); ?>
<!-- Mainbar -->
<div class="mainbar">
    <div class="span-16">
        <div class="content">
    <?php if (have_posts()) : ?>
    
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h2 class="search_results">Archive for the '<?php echo single_cat_title(); ?>' Category</h2>
		
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="search_results">Archive for <?php the_time('F jS, Y'); ?></h2>
		
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="search_results">Archive for <?php the_time('F, Y'); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="search_results">Archive for <?php the_time('Y'); ?></h2>
		
		<?php /* If this is a search */ } elseif (is_search()) { ?>
		<h2 class="search_results">Search Results</h2>
		
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="search_results">Author Archive</h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="search_results">Blog Archives</h2>

		<?php } ?>
               
         <?php while (have_posts()) : the_post(); ?>
         <div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
                <h2 class="post_header" id="post-<?php the_ID(); ?>"><span><?php the_time('d M')?></span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="clear"></div>
                <div class="post_meta">Posted by <?php the_author() ?> in <?php the_category(',') ?></div><div class="post_meta"><?php comments_popup_link('No Comments', 'Comment (1)', 'Comments (%)'); ?></div><div class="post_meta"><?php edit_post_link('Edit',''); ?></div>
                <div class="clear"></div>
                <div class="post_content">

                <?php the_content('Read more...'); ?> 
                <!--
                <?php trackback_rdf(); ?>
                -->
                 </div>
                <?php the_tags( '<div class="tags">Tags: ', ', ', '</div> '); ?><div class="clear"></div>
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
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<?php get_header(); ?>
<!-- Mainbar -->
   <div class="mainbar">
    <div class="mainbar_top">
        <div class="mainbar_bottom">
            <div class="mainbar_inner">
		<h1 class="search_results">Search results</h1>
		<?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        
		<div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
            <div class="post_date">
                <div class="post_date_top"><?php the_time('d')?></div>
                <div class="post_date_bottom"><?php the_time('M') ?></div>                                            
            </div>
            <h2 class="post_header" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
            <div class="post_line"></div>
            <div class="post_content">
                <?php the_excerpt() ?>
            </div>
            <div class="post_data">
            posted by <?php the_author() ?> in <?php the_category(',') ?> and have <?php comments_popup_link('No Comments', 'Comment (1)', 'Comments (%)'); ?> <?php edit_post_link('Edit',''); ?>
            </div>  
        </div>
        <?php endwhile; ?>
		<?php else : ?>

		<h1 class="search_results">Not Found</h1>
		

		<?php endif; ?>
	                
            </div>
        </div>
    </div>
</div> 

<?php get_sidebar(); ?>
<?php get_footer(); ?>
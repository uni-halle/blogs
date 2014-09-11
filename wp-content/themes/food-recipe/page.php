<?php get_header() ?> 
<div class="container containercontent">

    <div class='span-11 prepend-1'>
    	<div class="content">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div <?php if (function_exists('post_class')) { post_class(); } else { echo 'class="post"'; } ?> id="post-<?php the_ID(); ?>">

				<div class="postwrapper">
                <h1 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                    <hr style="height:0; border-top:1px dashed #b8b8b8;" />
                    <div class="postcontent">
                    <?php the_content(); ?>
	                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                    <?php edit_post_link('Edit page'); ?>
                    </div>
				</div>
            </div>

			<div style="clear:both"></div>

			<?php comments_template(); ?>
            
		<?php endwhile; ?>
		
		<?php else : ?>
			<h1 class="posttitle">Oops... No Items Found</h1>
			<?php get_search_form(); ?>
		<?php endif; ?>
        
        </div>
    </div>
    
    <div class='span-11 append-1 last'>
		<?php get_sidebar() ?>
    </div>
    
<?php get_footer() ?>
<?php get_header() ?> 
<div class="container containercontent">

    <div class='span-11 prepend-1'>
    	<div class="content">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div <?php if (function_exists('post_class')) { post_class(); } else { echo 'class="post"'; } ?> id="post-<?php the_ID(); ?>">

				<div class="postwrapper">
                <h1 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                    <dl>
                    <dt class="dtcategory"><?php the_category(', ') ?></dt>
                    <dd class="postmetacomment"><a href="#comments"><?php comments_number('0 Comments', '1 Comment', '% Comments' );?></a></dd>
                    <dd class="postcontent">
                    <?php the_content(); ?>
	                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                    <div style="clear:both"></div>
                    {<em><?php the_date(); ?></em>}
                    <?php if (function_exists('the_tags')) { ?>
                    <?php the_tags(' {<em>Tags: ', ', ', '</em>}'); ?>
                    <?php } ?>
                    <?php edit_post_link('Edit', ' | '); ?>
                    </dd>
                    </dl>
				</div>
            </div>
            
            <div class="navigation">
                <div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
                <div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
            </div>
            
            <div style="clear:both;">&nbsp;</div>

			<?php comments_template(); ?>
            
		<?php endwhile; ?>
		
		<div style="clear:both"></div>
		
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
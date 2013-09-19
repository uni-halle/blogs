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
                    <dd class="postmetacomment"><?php comments_popup_link('0 Comments', '1 Comment', '% Comments' );?></dd>
                    <dd class="postcontent">
                    <b><?php the_date(); ?></b>  
                    <?php echo apply_filters('the_excerpt_rss', str_replace('[...]','',get_the_excerpt())); ?>
                    <?php if (function_exists('the_tags')) { ?>
                    <?php the_tags(' {<em>Tags: ', ', ', '</em>}'); ?>
                    <?php } ?>
                    {<em><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">More...</a></em>}
                    <?php edit_post_link('Edit', ' | '); ?>
                    </dd>
                    </dl>
				</div>
            </div>
            
		<?php endwhile; ?>
		
		<div style="clear:both"></div>
		
		<?php else : ?>
			<h1 class="posttitle">Oops... No Items Found</h1>
			<?php get_search_form(); ?>
		<?php endif; ?>          
		<div class="navigation">
			<span class="alignleft"><?php next_posts_link('&laquo; Previous Posts') ?></span>
			<span class="alignright"><?php previous_posts_link('Next Posts &raquo;') ?></span>
  			<div style="clear:both"></div>
		</div>
        
        </div>
    </div>
    
    <div class='span-11 append-1 last'>
		<?php get_sidebar() ?>
    </div>
    
<?php get_footer() ?>
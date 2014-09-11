<?php get_header(); ?>

<div id="content">
	<?php if (have_posts()) :?>
		<?php while (have_posts()) : the_post();?>
            <div class="entry">

               <div class="icon-comments">
				   <?php comments_popup_link('0', '1', '%', 'commentslink'); ?>
               </div>
               
               <div class="entrytitle">
                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2> 
                </div>

                <div class="entrymeta">
                    <div class="postinfo">
                        <span class="date"><?php the_time('j F'); ?></span>
                        <?php the_tags('<span class="tags"> ', ', ', '</span>'); ?>
                    </div>
                </div>

                <div class="entrybody">
                    <?php the_content('<br /><br />Continue reading &raquo;'); ?>                   
                </div>    
                <div class="hr-entry"><hr /></div>                           
            </div>
            <div class="commentsblock">
                <?php comments_template(); ?>
            </div>
		<?php endwhile; ?>

        <div class="navigation">
            <div class="alignleft"><?php next_posts_link('&laquo; Previous posts') ?></div>
            <div class="alignright"><?php previous_posts_link('Next posts &raquo;') ?></div>
        </div>
            
	<?php else : ?>

        <h2>Not found</h2>
        <div class="entrybody">You were looking for something that wasn't here...</div>

    <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
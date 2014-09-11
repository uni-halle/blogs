<?php get_header(); ?>
<!-- ************************************************************* -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="postid" id="post-<?php the_ID(); ?>">
                    <div class="story">
<!-- Title ************************************************************* -->
                           <div class="pageTitle"><h2><?php the_title(); ?></h2></div>
<!-- Close Title ************************************************************* -->
<!-- Main Content Text ************************************************************* -->
                           <div class="storyContent">
								<?php the_content(); ?>
                                <div style="clear:both;"></div>
                           </div>
                           <div class="comment_template"> <?php comments_template(); ?> </div>
<!-- Close Main Content Text ************************************************************* -->
                   </div>
</div><!-- close postid-->

<?php endwhile; else: ?>
	<div class="error"><?php _e('Sorry, no posts matched your criteria.'); ?></div>
<?php endif; ?>
<!-- ************************************************************* -->
        </div>
    </div><!-- close innerContent -->
<?php get_sidebar();?>
<?php get_footer(); ?>
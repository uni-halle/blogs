<?php get_header(); ?>

<div id="blog">
		<?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        
        <div class="post" id="post-<?php the_ID(); ?>">
        
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2><br />
        
        <div class="content"><?php the_content('Read More &raquo;'); ?></div>
        
        </div><!--post-->
        
                <?php endwhile; ?>
        
                <div class="navigation">
                    <div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
                    <div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
                </div>
        
            <?php else : ?>
                <div class="post">
                <h2 class="center search">Search could not find anything!</h2>
                <p class="center">Sorry, but you are looking for something that isn't here.</p>
                </div>
            <?php endif; ?>
</div><!--blog-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
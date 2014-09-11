<?php get_header(); ?>

    <h1 class="catheader">
		<?php single_cat_title(); ?>
    </h1>
	<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="posts">
    <h2><a href="<?php the_permalink() ?>" title="Zum Lesen klicke auf <?php the_title(); ?>"><?php the_title(); ?></a></h2>
    <div class="meta">
				By <?php the_author() ?>
			</div>
                <?php getImage('1'); ?>

	<?php theme_excerpt('60'); ?>
    </div>
    
    <?php endwhile; ?>
    	<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; &Auml;ltere Eintr&auml;ge') ?></div>
			<div class="alignright"><?php previous_posts_link('Neuere Eintr&auml;ge &raquo;') ?></div>
		</div>
            
    <?php else : ?>
    <h2>Nichts gefunden...</h2>
    <div>
    	Entschuldige, aber was du hier suchst gibt es hier leider nicht.
    </div>
    <?php endif; ?>

<?php get_footer(); ?>

<?php get_header(); ?>



	<?php if (have_posts()) : ?>
    <h1 class="catheader">
		Suchergebnisse
    </h1>    <?php while (have_posts()) : the_post(); ?>
    <div class="posts">
    <h2><a href="<?php the_permalink() ?>" title="Zum Lesen klicke <?php the_title(); ?>"><?php the_title(); ?></a></h2>
    <div class="meta">
				Von <?php the_author() ?>
			</div>
	<?php the_excerpt(); ?>
    </div>
    
    <?php endwhile; ?>
    	<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; &Auml;ltere Eintr&auml;ge') ?></div>
			<div class="alignright"><?php previous_posts_link('Neuere Eintr&auml;ge &raquo;') ?></div>
		</div>
            
    <?php else : ?>
    <h1 class="catheader">Nichts gefunden...</h1>
    <div>
    	<p>Die Suche hat keinen Artikel auf deine Kriterien gefunden.</p>
    </div>
    <?php endif; ?>

<?php get_footer(); ?>

<?php get_header(); ?>
<div id="content">

		<h1 class="pagetitle"><?php _e('Error 404 - Not found','blogsmlu'); ?></h1>
					
					<p><?php _e('Sorry, but something went wrong.','blogsmlu'); ?></p>
					
					<p><p><?php _e('Maybe try a search:','blogsmlu'); ?></p></p>
					
					<p><?php include ('searchform.php'); ?></p>
										
					<h3><?php _e('Recent Entries','blogsmlu'); ?></h3>
					<p><?php _e('Or have a look at the recent entries:','blogsmlu'); ?></p>

						<ul>
							<?php query_posts('showposts=10');
							if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>
							<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title();?> </a></li>
							<?php endwhile; ?><?php endif; ?>
						</ul>

</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>
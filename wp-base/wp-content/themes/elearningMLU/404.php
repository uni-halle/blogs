<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">

			<div class="column two">
				<div class="edge-alt"></div>
				
				<div class="entry-extended">
					
					<h1 class="pagetitle">Fehler 404 - Nicht gefunden</h1>
					
					<p>Sorry, aber irgend etwas ging schief.</p>
					
					<p>Vielleicht hilft dir eine Suche weiter:</p>
					
					<p><?php include ('searchform.php'); ?></p>
										
					<h3>Letzte Beitr&auml;ge</h3>
					<p>Oder du wirfst einen Blick auf die letzten Beitr&auml;ge:</p>

						<ul>
							<?php query_posts('showposts=5');
							if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>
							<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title();?> </a></li>
							<?php endwhile; ?><?php endif; ?>
						</ul>
		
				</div>
			
			</div><!-- end column -->
	
	</div><!-- end content -->
	
	<?php get_footer(); ?>
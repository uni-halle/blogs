<?php get_header() ?>

	<div id="container">
<div class="left-col">	
			<h2 class="entry-title"><?php _e('Things Change', 'sandbox') ?></h2>
<p class="archive-meta">Fiddlesticks. Things change. While you&rsquo;re here, though, why not have a look around?</p>
			</div>

		<div id="content">
				

<div id="post-0" class="post error404">
	<div class="entry-content">
					<p>Apologies, but we can&rsquo;t find what you were looking for. Whatever it was, it probably never existed, or has been moved, deleted, or *gasp* lost.</p><p> You might try searching (see above, right). If you head over to the archives, you&rsquo;re sure to find something interesting.</p>
					
					<?php if (function_exists('random_posts')) : ?>
					<p> Or, just have a look at one of the random posts listed below.</p>
					<ul>
						<?php random_posts('10','25','<li>','<br />','',' [...]</li>','false','true'); ?>
					</ul>
					<?php endif; ?>
				</div>
				
				
				</div><!-- .post -->

		</div><!-- #content -->
		<?php get_sidebar() ?>
		</div><!-- #container -->
<?php include (TEMPLATEPATH . '/bottom.php'); ?>	
<?php get_footer() ?>
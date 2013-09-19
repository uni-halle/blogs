<?php get_header() ?>

	<div id="container">
		<div id="content">

			<div id="post-0" class="post error404">
				<div class="category">
<h2 class="entry-title"><?php _e('Not Found', 'sandbox') ?></h2></div>
					<div class="entry-content">
						<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps browseing the archive will help.', 'sandbox') ?></p>
					</div>
					<div class="archive-content">
						<?php get_sidebar() ?>
					</div><!-- .archive-content -->
			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
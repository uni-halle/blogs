<?php get_header() ?>

			<div id="post-0" class="singlepost error404">
				<h2 class="entry-title"><?php _e('Not Found') ?></h2>
				<div class="entry-content">
					<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps  searching will help.') ?></p>
				</div>
				<form id="error404-searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="error404-s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="40" />
						<input id="error404-searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find') ?>" />
					</div>
				</form>
			</div><!-- .post -->

		</div><!-- #content -->
	
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>
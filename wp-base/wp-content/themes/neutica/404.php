<?php get_header() ?>

	<div id="container">
		<div id="content">

			<div id="post-0" class="post error404">

				<div class="entry-content">
					<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps  searching will help?', 'sandbox') ?></p>
				</div>

				<div class="entry-meta">
					<h2 class="entry-title"><?php _e('404 Error. Page not found. Try a search', 'sandbox') ?></h2>
					<form id="error404-searchform" method="get" action="<?php bloginfo('home') ?>">
						<div>
							<input id="error404-s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="40" />
							<input id="error404-searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'sandbox') ?>" />
						</div>
					</form>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>
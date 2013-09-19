<?php get_header(); ?>

	<div id="content_box">
	
		<div id="left_box">
	
			<div id="content" class="page">
			
				<div id="content_inner">
					<h1>404! Danger, Will Robinson!</h1>
					<div class="format_text">
						<p>Somehow, you ended up in the wrong place, but don't worry! Instead, try one of the following:</p>
						<ul>
							<li>Hit the "back" button on your browser.</li>
							<li>Head on over to the <a href="<?php bloginfo('url'); ?>">home page</a>.</li>
							<li>Use the navigation menu at left.</li>
							<li>Click on a link in either sidebar.</li>
							<li>Try searching using the form in the sidebar.</li>
							<li>Punt.</li>
						</ul>
					</div>
				</div>
			</div>
		
			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>
		
		</div>

		<?php get_sidebar(); ?>
		
	</div>

<?php get_footer(); ?>
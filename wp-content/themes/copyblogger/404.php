<?php get_header(); ?>

	<div id="content_box">
	
		<div id="content" class="page">
			<h1>My 404 page is better than yours.</h1>
			<div class="entry">
				<p>Welcome to the seedy underbelly of this otherwise upstanding Web site. Please choose from the following in order to get back on track:</p>
				<ul>
					<li>Try the ol' back button on your browser&#8212;it <em>is</em> the most used button on the Web, you know.</li>
					<li>Head on back <a href="<?php bloginfo('url'); ?>">home</a>.</li>
					<li>Try the navigation menu at the top &uarr; of the page.</li>
					<li>Search or click on a link over in the sidebar.</li>
					<li><a href="<?php bloginfo('rss2_url'); ?>">Subscribe to this site's feed</a> so you don't have to come here for updates.</li>
					<li>Relive the glory days of high school football and punt, but <em>please</em> do not strain your groin.</li>
				</ul>
			</div>
		</div>

		<?php get_sidebar(); ?>
		
	</div>

<?php get_footer(); ?>
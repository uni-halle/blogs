<div id="sidebar">
	
	<div class="sidelist">
		<h2>blogs@URZ Status</h2>
		<ul id="status">

			<!--
			<li class="status-light aufbau"></li>
			<li class="status-message">Der Dienst befindet sich im Aufbau.</li>
			-->
			
			<li class="status-light green"></li>
			<li class="status-message">Alles im gr&uuml;nen Bereich.</li>

			<!--
			<li class="status-light yellow"></li>
			<li class="status-message">Eingeschr&auml;nkte Funktionen.</li>
			-->

			<!--
			<li class="status-light red"></li>
			<li class="status-message">Der Dienst ist nicht verf&uuml;gbar.</li>
			-->
		
		</ul>
		<p>Aktuelle Neuigkeiten zum Status des Blog-Dienstes erf&auml;hrst du im <a title="Blogs@URZ Hauptblog" rel="me" href="/blog">Hauptblog</a>, &uuml;ber <a rel="me" href="https://twitter.com/mlublogs">Twitter</a> oder auf unserer <a rel="me" href="http://www.facebook.com/pages/blogsURZ/156259138175">Facebook Seite</a>.</p>
	</div>

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar('Sidebar') ) : ?>

	<div class="sidelist">
		<h2>Suche</h2>
		<?php get_search_form(); ?>
	</div>
	
	<div class="sidelist">
		<h2>Kategorien</h2>
		<ul>
			<?php wp_list_categories('title_li='); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2>Archiv</h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2>Blogroll</h2>
		<ul>
			<?php wp_list_bookmarks('category_name=Blogroll&categorize=0&title_li='); ?>
		</ul>
	</div>

<?php endif; ?>

</div><!-- END Sidebar -->
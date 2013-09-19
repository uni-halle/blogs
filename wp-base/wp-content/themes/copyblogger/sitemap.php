<?php
/*
Template Name: Sitemap
*/
?>

<?php get_header(); ?>
		
	<div id="content_box">

		<div id="content" class="page">
		
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<h1><?php the_title(); ?></h1>
			<div class="entry">
				<p><strong>All internal pages:</strong></p>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>
				<p><strong>All internal blog posts:</strong></p>
				<ul>
					<?php $archive_query = new WP_Query('showposts=1000');
						while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
					<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a> <strong><?php comments_number('0', '1', '%'); ?></strong></li>
					<?php endwhile; ?>
				</ul>
				<p><strong>Monthly archive pages:</strong></p>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
				<p><strong>Topical archive pages:</strong></p>
				<ul>
					<?php wp_list_categories('title_li=0'); ?>
				</ul>
				<p><strong>Available RSS Feeds:</strong></p>
				<ul>
					<li><a href="<?php bloginfo('rdf_url'); ?>" title="RDF/RSS 1.0 feed"><acronym title="Resource Description Framework">RDF</acronym>/<acronym title="Really Simple Syndication">RSS</acronym> 1.0 feed</a></li>
					<li><a href="<?php bloginfo('rss_url'); ?>" title="RSS 0.92 feed"><acronym title="Really Simple Syndication">RSS</acronym> 0.92 feed</a></li>
					<li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS 2.0 feed"><acronym title="Really Simple Syndication">RSS</acronym> 2.0 feed</a></li>
					<li><a href="<?php bloginfo('atom_url'); ?>" title="Atom feed">Atom feed</a></li>
				</ul>
			</div>
				
			<?php endwhile; endif; ?>
			
		</div>	
		
		<?php get_sidebar(); ?>
			
	</div>
		
<?php get_footer(); ?>
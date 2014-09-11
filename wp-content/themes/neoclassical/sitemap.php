<?php
/*
Template Name: Sitemap
*/
?>

<?php get_header(); ?>

	<div id="content_box">
	
		<div id="left_box">
	
			<div id="content" class="archives">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<h1><?php the_title(); ?></h1>
			
				<div id="content_inner">
					<div class="format_text">
						<h3 class="top">All internal pages:</h3>
						<ul>
							<?php wp_list_pages('title_li='); ?>
						</ul>
						<h3>All internal blog posts:</h3>
						<ul>
							<?php $archive_query = new WP_Query('showposts=1000');
								while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
							<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>" rel="nofollow"><?php the_title(); ?></a> <strong><?php comments_number('0', '1', '%'); ?></strong></li>
							<?php endwhile; ?>
						</ul>
						<h3>Monthly archive pages:</h3>
						<ul>
							<?php wp_get_archives('type=monthly'); ?>
						</ul>
						<h3>Topical archive pages:</h3>
						<ul>
							<?php wp_list_categories('title_li=0'); ?>
						</ul>
						<h3>Available RSS Feeds:</h3>
						<ul>
							<li><a href="<?php bloginfo('rdf_url'); ?>" alt="RDF/RSS 1.0 feed" rel="nofollow"><acronym title="Resource Description Framework">RDF</acronym>/<acronym title="Really Simple Syndication">RSS</acronym> 1.0 feed</a></li>
							<li><a href="<?php bloginfo('rss_url'); ?>" alt="RSS 0.92 feed" rel="nofollow"><acronym title="Really Simple Syndication">RSS</acronym> 0.92 feed</a></li>
							<li><a href="<?php bloginfo('rss2_url'); ?>" alt="RSS 2.0 feed" rel="nofollow"><acronym title="Really Simple Syndication">RSS</acronym> 2.0 feed</a></li>
							<li><a href="<?php bloginfo('atom_url'); ?>" alt="Atom feed" rel="nofollow">Atom feed</a></li>
						</ul>
					</div>
				</div>
				
				<?php endwhile; endif; ?>

			</div>

			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>

		</div>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
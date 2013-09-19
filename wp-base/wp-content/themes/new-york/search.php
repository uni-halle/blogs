<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results for: <?php the_post(); echo '<span class="search-term">'. $s .'</span>'; rewind_posts(); ?></h2>
<br />
<br />
		<?php while (have_posts()) : the_post(); ?>

			<div class="post">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<small><?php the_time('l, F jS, Y') ?></small>
<?php the_excerpt(); ?>
				<p class="postmetadata"></p>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="newerposts"><?php next_posts_link('&laquo; Older Posts') ?></div>
			<div class="olderposts"><?php previous_posts_link('Newer Posts &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">No posts found. Try a different search term?</h2>
		<br />
		<form id="searchform" method="get" action="<?php bloginfo('home'); ?>/"> <input type="text"  size="18" maxlength="50" name="s" id="s" /> 
		<input name="submit" type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/go2.png" value="Search" Title="Search" style="outline-style:none;outline-width:0;border:none;background:none;margin:8px 5px -8px 0;"/></form> 

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
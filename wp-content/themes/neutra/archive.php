<?php get_header(); ?>

<div id="page">
	<div id="left">
	<?php if (have_posts()) : ?>
	
	<div class="padding">
		<?php /* If category archive */ if (is_category()) { ?>
		<h2>Category: <strong><?php single_cat_title(); ?></strong></h2>
		<?php /* If tag archive */ } elseif( is_tag() ) { ?>
		<h2>Tag browsing: <strong><?php single_tag_title(); ?></strong></h2>
		<?php /* If daily archive */ } elseif (is_day()) { ?>
		<h2>Archive for <strong><?php the_time('F jS, Y'); ?></strong></h2>
		<?php /* If monthly archive */ } elseif (is_month()) { ?>
		<h2>Archive for <strong><?php the_time('F Y'); ?></strong></h2>
		<?php /* If yearly archive */ } elseif (is_year()) { ?>
		<h2>Archive for <strong><?php the_time('Y'); ?></strong></h2>
		<?php /* If paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2>Blog Archive</h2>
		<?php } ?>
	</div><!-- end .padding -->
		
	<div class="border-gray"></div>
		
	<?php while (have_posts()) : the_post(); ?>
		<div class="padding">
			<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4><br />
		</div><!-- end .padding -->

		
		<?php endwhile; ?>
			
		<div class="navigation">
			<div class="old-posts floatleft"><?php next_posts_link('See old posts') ?></div>
			<div class="new-posts floatright"><?php previous_posts_link('See new posts') ?></div>
			<div class="clearing"></div>
		</div><!-- end .navigation -->
			
		<?php else : ?>

		<div class="post">
			<div class="padding">
				<h2>I'm sorry, there's no archive!</h2><br />
				<p>Don't worry, you can always <strong>search the blog</strong> or browse the <strong>categories</strong>.</p>
			</div><!-- end .padding -->
		</div><!-- end .post -->

	<?php endif; ?>
	
	</div><!-- end #left -->
	
	<div id="right">
		<?php get_sidebar(); ?>
	</div><!-- end #right -->
	
<div class="clearing"></div>
</div><!-- end #page -->

<?php get_footer(); ?>
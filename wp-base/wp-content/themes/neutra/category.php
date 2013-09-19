<?php get_header(); ?>

<div id="page">
	<div id="left">
	<?php if (have_posts()) : ?>
	
	<div class="padding">
		<h2>Category: <strong><?php the_category(); ?></strong></h2>
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
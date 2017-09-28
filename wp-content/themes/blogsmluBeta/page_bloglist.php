<?php 
/*
Template Name: Blogliste
*/
get_header(); ?>

	<div id="content">
		
		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>				
			
				<div <?php post_class() ?>>
	
					<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
					
					<div class="entrytext">
						<?php the_content('<p>| Weiterlesen ...</p>'); ?>
						<?php link_pages('<p><strong>Seiten:</strong> ', '</p>', 'number'); ?>
					</div>
				
					<p class="postmetadata"><?php edit_post_link('Bearbeiten ', '', ''); ?></p>
					
					<?php $stats = get_sitestats(); ?>
					<p id="statistic">Bisher tummeln sich <span><?php echo $stats['blogs']?></span> Blogs und <span><?php echo $stats['users']?></span> Benutzer in unserem Dienst.</p>
					
					<ul class="bloglist">
						<?php list_all_wpmu_blogs('', 'name', '<li>', '</li>', 'last_created'); ?>
					</ul>
					
				</div>
	
		<?php endwhile; endif; ?>
		
	</div><!-- end #content -->
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>
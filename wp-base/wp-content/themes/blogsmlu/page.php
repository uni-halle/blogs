<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
	
	<div <?php post_class() ?>>
	
		<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
		
		<div class="entrytext">
			<?php the_content(__('[ Read On ... ]', 'blogsmlu')); ?>
			<?php link_pages(__('Pages: ','blogsmlu'), '', 'number'); ?>
		</div>
	
	</div>
	
	<?php endwhile; endif; ?>
	
	<p class="postmetadata"><?php edit_post_link(__('Edit ', 'blogsmlu'), ' | ', ' | '); ?></p>
	
	<!-- Show sub-pages for the page if any -->
	<?php
	  if($post->post_parent)
	  $children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
	  else
	  $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
	  if ($children) { ?>
	  <h3><?php _e('Sub Pages', 'blogsmlu'); ?></h3>
	  <ul id="subpages">
	  	<?php echo $children; ?>
	  </ul>
	  <?php } ?>
	  
	  <?php comments_template('', true); ?>
	
</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>
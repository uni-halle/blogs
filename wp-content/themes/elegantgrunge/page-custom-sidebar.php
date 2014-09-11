<?php
/*
Template Name: Page with custom sidebar
*/
?>

<?php define('EG_BODY_CLASS', 'right-sidebar'); get_header(); ?>

<div id="content-container">

<div id="content">

<div id="body">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">
	<h2><?php the_title(); ?></h2>
		<div class="entry">
			<?php the_content(__('<p class="serif">Read the rest of this page &raquo;</p>', 'elegant-grunge')); ?>

			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'elegant-grunge').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>
	</div>
	<?php endwhile; endif; ?>
	<?php edit_post_link(__('Edit this entry.', 'elegant-grunge'), '<p>', '</p>'); ?>

</div>
	  
<div id="sidebar" class="sidebar">
	<ul>
	
	<?php if ( ($content = get_post_meta(get_the_ID(), 'sidebar_content', true)) ) : ?>
   	<li>
		<?php echo $content ?>
	</li>
	<?php endif; ?>

	<?php if ( ($tags = get_post_meta(get_the_ID(), 'related_tags', true)) ) : ?>
		
		<?php
		$relatedTitle = get_post_meta(get_the_ID(), 'related_title', true);
		if ( !$relatedTitle ) $relatedTitle = __('Related posts', 'elegant-grunge');
		?>
		
		<li>
		<h2><?php echo $relatedTitle ?></h2>
		<ul>
			<?php query_posts("tag=".$tags); ?>
			<?php while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
		</ul>
		<div class="more-link"><a href="/?tag=<?php echo $tags; ?>"><?php _e('More updates', 'elegant-grunge') ?></a></div>
		</li>
		
	<?php endif; ?>
	</ul>
</div>

<div class="clear"></div>

</div>
</div>

<?php get_footer(); ?>
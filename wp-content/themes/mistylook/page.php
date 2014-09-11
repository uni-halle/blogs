<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info"><?php the_time(__('M jS, Y','ml')) ?> <?php _e('by','ml');?> <?php the_author_posts_link() ?> <?php edit_post_link(__('Edit','ml'), '', ' | '); ?> </p>
				</div>

				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					<?php $sub_pages = wp_list_pages( 'sort_column=menu_order&depth=1&title_li=&echo=0&child_of=' . $id );?>
					<?php if ($sub_pages <> "" ){?>
						<p class="post-info"><?php _e('This page has the following sub pages.','ml');?></p>
						<ul><?php echo $sub_pages; ?></ul>
					<?php }?>
				</div>
			</div>
			<?php comments_template(); ?>
		<?php endwhile; ?>
	<?php else : include_once(TEMPLATEPATH.'/notfound.php');?>
	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>
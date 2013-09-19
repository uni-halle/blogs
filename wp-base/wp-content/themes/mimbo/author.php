<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>


<h2><?php _e('Author Archives','Mimbo'); ?> <a href="<?php echo get_author_posts_url( $author, ""); ?>feed/"><img src="<?php bloginfo('template_url'); ?>/images/rss.gif" alt="rss" /></a></h2>


<div id="writer" class="clearfloat">
<?php
	global $wp_query;
	$curauth = $wp_query->get_queried_object();
	echo get_avatar( $curauth->user_email, '80' );
?>
<p><?php echo $curauth->user_description; ?></p>

</div>

	<ul class="archive-list clearfloat">
			<?php while (have_posts()) : the_post(); ?>

					<?php include (TEMPLATEPATH . '/archivelist.php'); ?>

			<?php endwhile; ?>
		</ul>
		
		<?php include (TEMPLATEPATH . '/pagination.php'); ?>
		
	<?php else : ?>

		<h2 class="pagetitle"><?php _e('Search Results','Mimbo'); ?></h2>
		<p><?php _e('No posts found.','Mimbo'); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
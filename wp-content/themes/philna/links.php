<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<div class="post">

<?php the_title('<h1 class="post-title entry-title">', '</h1>'); ?>

	<div class="info">
	<?php if ( $user_ID ) : ?>
	<span class="editpost"><a href="<?php echo get_settings('home'); ?>/wp-admin/link-manager.php"><?php _e('Edit links', 'philna'); ?></a></span>
	<?php endif; ?>
	<?php if(comments_open()) : ?>
		<span class="comments-link addcomment"><a href="#respond" title="<?php _e('Add a comment', 'philna') ?>"><?php _e('Add a comment', 'philna') ?></a></span>
	<?php endif;?>
	
	<span class="published"><?php the_time(__('F jS, Y', 'philna')) ?></span>
	</div>

	<div class="entry-content">
		<div id="linkcat">
		<ul><?php wp_list_bookmarks('title_li=&categorize=0&orderby=rand'); ?></ul>
		<div class="fixed"></div>
		</div>
		
	<?php the_content(); ?>
	<?php wp_link_pages("before=<p class='pages'>".__('Pages:','philna')."&after=</p>"); ?>
	<div class="fixed"></div>
	</div>

</div>

	<?php endwhile; ?>

<?php else: ?>

<p class="no-data"><?php _e('Sorry, no posts matched your criteria.','philna'); ?></p>

<?php endif; ?>


<?php comments_template(); ?>

<?php get_footer(); ?>
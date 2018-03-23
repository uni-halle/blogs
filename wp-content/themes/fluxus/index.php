<?php get_header(); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">
	<?php if (have_posts()): ?>
	
		<div class="content__postlist postlist">
	
			<?php 
				while(have_posts()) {
					the_post();
					get_template_part('templates/parts/postlistitem', '');
				}
			?>
	
			<?php if(get_the_posts_pagination()): ?>
				<div class="content__pagination">
					<?php the_posts_pagination(array("prev_text" => "zurück", "next_text" => "weiter")); ?>
				</div>
			<?php endif; ?>
	
		</div>
	
	<?php else : ?>
	
		<article <?php post_class("content__article article typo"); ?>>
			<section class="article__header">Keine Inhalte gefunden.</section>
		</article>
	
	<?php endif; ?>
</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>

<?php
	// to solve problem with pagination on front-page:
	// set wp-frontpage-option to index (last posts)
	// and then redirect in your index to your startpage like this:
	//wp_redirect(get_permalink(get_page_by_title("TITLE_OF_STARTPAGE"))); 
?>
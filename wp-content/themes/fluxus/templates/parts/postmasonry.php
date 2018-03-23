<?php query_posts( get_postlist_query_args() ); ?>

<?php if (have_posts()): ?>
	<?php 
		$countclass = "postmasonry--only1";
		if($wp_query->found_posts > 1) { $countclass = "postmasonry--morethan1"; }
	?>

	<div class="content__postmasonry postmasonry <?php echo $countclass; ?> masonry">
		<?php 
			while(have_posts()) { 
				the_post(); 
				get_template_part('templates/parts/postmasonryitem', ''); 
			} 
		?>
	</div>

	<?php if(get_the_posts_pagination()): ?>
		<div class="content__pagination">
			<?php the_posts_pagination(array("prev_text" => "zurück", "next_text" => "weiter")); ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<article <?php post_class("content__article article typo"); ?>>
		<section class="article__header">Keine Inhalte gefunden.</section>
	</article>

<?php endif; ?>

<?php wp_reset_query(); ?>
<?php query_posts( get_postlist_query_args() ); ?>

<?php if (have_posts()): ?>

	<div class="content__postlist postlist">

		<?php 
			while(have_posts()) {
				the_post();
				if($post->post_type == 'event') {
					get_template_part('templates/parts/postlinklistitem', 'event'); 
				} else {
					get_template_part('templates/parts/postlistitem', ''); 
				}
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

<?php wp_reset_query(); ?>
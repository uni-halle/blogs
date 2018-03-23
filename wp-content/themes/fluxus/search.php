<?php get_template_part('header', 'search'); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

<!--
	<article class="content__article article typo">
		<h3>Suchergebnisse für »<?php echo get_search_query(); ?>«</h3>
	</article>
-->

	<?php if (have_posts()): ?>

		<article <?php post_class("content__article article typo"); ?>>
			<p class="titleblock titleblock--slash"><strong>In Ausstellungsbereichen</strong></p>
			<?php while(have_posts()): the_post(); //get_template_part('templates/parts/postlinklistitem', 'search'); ?>
				<p>
					<a href="<?php echo get_permalink(); ?>?searchkey=<?php echo get_search_query(); ?>"><?php the_title(); ?></a>
				</p> 
			<?php endwhile; ?>

		</article>

		<?php if(get_the_posts_pagination()): ?>
			<div class="content__pagination">
				<?php the_posts_pagination(array("prev_text" => "zurück", "next_text" => "weiter")); ?>
			</div>
		<?php endif; ?>
	
	<?php else : ?>
	
		<article <?php post_class("content__article article typo"); ?>>
			<p class="titleblock titleblock--slash"><strong>In Ausstellungsbereichen</strong></p>
			<section class="article__header">Keine Inhalte gefunden.</section>
		</article>
	
	<?php endif; ?>




	
	<?php // results in attachments
		$resultattachments = array();
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'posts_per_page' => -1
		));
		foreach($attachments as $attachment) {
			setup_postdata($attachment);
			if (stripos(get_post_meta( $attachment->ID, '_imagetext', true ), get_search_query())) {
				$resultattachments[] = $attachment;
			}
		}
	?>
	<?php if(count($resultattachments) > 0): ?>
		<article <?php post_class("content__article article typo"); ?>>
			<p class="titleblock titleblock--slash"><strong>In Exponattexten und Zitaten</strong></p>
			<div class='contentgallerymasonry gallery masonry'>
			<?php foreach($resultattachments as $resultattachment): ?>
				<div class='contentgallerymasonry__item contentgallerymasonry__item--6columns'>
				<?php
					echo wp_get_attachment_link($resultattachment, "full", "", "", "", array("class" => "contentgallerymasonry__img", "title" => $resultattachment->post_excerpt, "alt" => $resultattachment->post_excerpt ));
					if( $attachment->post_excerpt ){
						echo "<div class='contentgallerymasonry__caption'>" . $attachment->post_excerpt . "</div>";
					}
					if( get_post_meta( $resultattachment->ID, '_imagetext', true ) ){
						echo "<div class='imagelightbox__imgtext' style='display: none;'>" . wpautop(get_post_meta( $resultattachment->ID, '_imagetext', true )) . "</div>";
					}
				?>
				</div>
			<?php endforeach; ?>
			</div>
		</article>
	<?php endif; ?>





</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>
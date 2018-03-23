<article <?php post_class("postlinklist__item postlinklist__item--search typo"); ?>>
	<a href="<?php echo get_permalink(); ?>?dir=search">
		<div class="postlinklist__head">
			<?php //the_post_thumbnail('thumbnail', array("class" => "postlinklist__postthumbnail", "size" => "size-thumbnail")); ?>
			<?php the_title("<h4 class='postlinklist__title'>", "</h4>"); ?>
<!--
			<div class="postlinklist__headinfo">
				<?php the_title("<h4 class='postlinklist__title'>", "</h4>"); ?>
				<?php if($post->post_type == "event"): ?>
					<p class='postlinklist__info'>Veranstaltung&nbsp;| <?php echo format_date( get_post_meta( $post->ID, "_start_time", true ), get_post_meta( $post->ID, "_end_time", true ), false, true ); ?></p>
				<?php elseif($post->post_type == "post"): ?>
					<p class='postlinklist__info'>Beitrag&nbsp;| <?php echo get_the_date(); ?></p>
				<?php elseif($post->post_type == "page"): ?>
					<p class='postlinklist__info'>Seite</p>
				<?php endif; ?>
			</div>
-->
		</div>
		<div class="postlinklist__content">
			<?php the_excerpt(); ?>
		</div>
	</a>
</article>
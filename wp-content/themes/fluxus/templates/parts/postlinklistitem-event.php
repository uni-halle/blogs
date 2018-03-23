<article <?php post_class("postlinklist__item postlinklist__item--event typo"); ?>>
	<a href="<?php the_permalink(); ?>">
		<div class="postlinklist__head">
			<?php the_post_thumbnail('thumbnail', array("class" => "postlinklist__postthumbnail", "size" => "size-thumbnail")); ?>
			<div class="postlinklist__headinfo">
				<p class='postlinklist__date'><?php echo format_date( get_post_meta( $post->ID, "_start_time", true ), get_post_meta( $post->ID, "_end_time", true ), false, true ); ?></p>
				<?php the_title("<h4 class='postlinklist__title'>", "</h4>"); ?>
			</div>
		</div>
	</a>
</article>
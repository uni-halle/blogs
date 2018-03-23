<article <?php post_class("postlinklist__item typo"); ?>>
	<a href="<?php the_permalink(); ?>">
		<div class="postlinklist__head">
			<?php the_post_thumbnail('thumbnail', array("class" => "postlinklist__postthumbnail", "size" => "size-thumbnail")); ?>
			<div class="postlinklist__headinfo">
				<?php the_date("", "<p class='postlinklist__date'>", "</p>"); ?>
				<?php the_title("<h4 class='postlinklist__title'>", "</h4>"); ?>
			</div>
		</div>
		<div class="postlinklist__content">
			<?php the_excerpt(); ?>
		</div>
	</a>
</article>
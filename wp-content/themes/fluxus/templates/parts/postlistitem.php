<article <?php post_class("postlist__item postlist__item--" . $post->post_type . " typo"); ?>>
	<div class="postlist__head">
		<?php the_post_thumbnail('thumbnail', array("class" => "postlist__postthumbnail", "size" => "size-thumbnail")); ?>
		<div class="postlist__headinfo">
			<?php the_date("", "<p class='postlist__date'>", "</p>"); ?>
			<?php the_title("<h4 class='postlist__title'>", "</h4>"); ?>
		</div>
	</div>
	<div class="postlist__content">
		<?php the_content("weiterlesen"); ?>
	</div>
</article>
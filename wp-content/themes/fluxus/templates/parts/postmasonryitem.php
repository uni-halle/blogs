<div class="postmasonry__item">
	<a class="postmasonry__link" href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail('large', array("class" => "postmasonry__postthumbnail", "size" => "size-large")); ?>
		<?php the_title("<p class='postmasonry__title'>", "</p>"); ?>
	</a>
</div>
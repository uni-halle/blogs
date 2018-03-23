<article <?php post_class("postaccordion__item accordionsection typo"); ?>>
	<section class="postaccordion__tab accordionsection__tab">
		<div class="postaccordion__head">
			<?php the_post_thumbnail('thumbnail', array("class" => "postaccordion__postthumbnail", "size" => "size-thumbnail")); ?>
			<div class="postaccordion__headinfo">
				<?php the_date("", "<p class='postaccordion__date'>", "</p>"); ?>
				<?php the_title("<h4 class='postaccordion__title'>", "</h4>"); ?>
			</div>
		</div>
	</section>
	<div class="postaccordion__content accordionsection__content">
		<?php the_excerpt(); ?>
	</div>
</article>
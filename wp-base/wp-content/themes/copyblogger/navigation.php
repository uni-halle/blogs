<?php if (is_single()) : ?>

<div class="navigation">
	<p><?php previous_post_link('&larr; %link') ?></p>
	<p class="next"><?php next_post_link('%link &rarr;') ?></p>
</div>

<?php else : ?>

<div class="navigation">
	<p><?php next_posts_link('&larr; Previous Entries') ?></p>
	<p class="next"><?php previous_posts_link('Next Entries &rarr;') ?></p>
</div>

<?php endif; ?>
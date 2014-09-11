<?php if (is_single()) : ?>

<div class="navigation">
	<p class="previous"><?php previous_post_link('&larr; %link') ?></p>
	<p><?php next_post_link('%link &rarr;') ?></p>
</div>

<?php else : ?>

<div class="navigation">
	<p><span class="older"><?php next_posts_link('&larr; Previous Entries') ?></span><?php previous_posts_link('Next Entries &rarr;') ?></p>
</div>

<?php endif; ?>
<div class="entry-meta">
	<?php 
	if (is_multi_author()) {
		echo omega_apply_atomic_shortcode( 'entry_author', __( 'Posted by [post_author_posts_link] ', 'omega' ) ); 
	} else {
		echo omega_apply_atomic_shortcode( 'entry_author', __( 'Posted ', 'omega' ) ); 
	}?>
	<?php
	echo omega_apply_atomic_shortcode( 'entry_byline', __( 'on [post_date] [post_comments] [post_edit before=" | "]', 'omega' ) ); 
	
	?>
</div><!-- .entry-meta -->
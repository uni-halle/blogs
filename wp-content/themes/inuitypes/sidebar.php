<div class="sidebar <?php if ( !get_option('bizzthemes_right_sidebar') ) { echo 'sidebar_left'; } else { echo 'sidebar_right'; } ?>">
    
    	<?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

		<?php endif; ?>
    
</div>
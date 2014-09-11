<div class="sidebar1">
	<ul>
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>

        <li>
        <h2><?php _e('Categories'); ?></h2>
            <ul>
            <?php wp_list_cats('sort_column=name&hierarchical=0'); ?>
            </ul>
        </li>

        
	<?php endif; ?>
	</ul>
</div>

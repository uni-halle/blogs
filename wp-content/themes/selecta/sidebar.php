	<div id="sidebar">
		<?php if(is_active_sidebar(3)) : ?>
			<ul class="clearfix"><?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(3) );  ?></ul>
        <?php endif; ?>
		<?php if(is_active_sidebar(4)) : ?>
            <ul class="clearfix"><?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(4) );  ?></ul>
        <?php endif; ?>
	</div>
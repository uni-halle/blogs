<?php if (function_exists('mdv_recent_comments_edit')) { ?>
<li class="break"><h1><?php _e('Latest Comments','avenue'); ?></h1>
<ul><?php mdv_recent_comments_edit(2, 12, '<li>', '</li>', true, 0); ?>
</ul></li><?php } ?>
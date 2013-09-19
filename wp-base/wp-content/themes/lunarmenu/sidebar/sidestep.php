<?php if ( is_single() ) { ?>
<li><h1><?php _e('Sidestep','avenue'); ?></h1><ul>
<li><?php _e('next Post<br />','avenue'); ?><?php next_post('%','','yes','no') ?></li>
<li><?php _e('previous Post<br />','avenue'); ?><?php previous_post('%','','yes','no') ?></li>
</ul></li><?php } ?>
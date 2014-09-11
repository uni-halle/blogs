<?php if (function_exists('mdv_most_commented_edit')) { ?>
<li><h1><?php _e('Most commented','avenue'); ?></h1>
<ul><?php mdv_most_commented_edit(3, '<li>', '</li>', true); ?></ul></li>
<?php } ?>
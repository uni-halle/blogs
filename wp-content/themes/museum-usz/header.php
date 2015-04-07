<?php

/* load config to display the modifications accordingly */
require( AP_CORE_OPTIONS );

/* load parent's header file and add customization */
require(dirname(__FILE__).'/../museum-core/header.php');

?>
<!-- custom header extension -->
<div class="sidebar breadcrumbs span-9<?php echo $right; ?>"><ul>
    <?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('Before Content Box') ?>
</ul></div>
<!-- end custom header extension -->

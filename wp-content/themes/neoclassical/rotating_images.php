<?php

$random_image = rand(1,5); // the second number should equal the total number of images that you want to rotate

?>
				<img src="<?php bloginfo('template_url'); ?>/headers/header_<?php echo $random_image; ?>.jpg" alt="Random header image... Refresh for more!" />
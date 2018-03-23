<div class="content__pagelist pagelist">

	<?php 
		if (function_exists('get_pagelist')) {
			$pages = get_pagelist();
			$oldthumbnail = "";
			$oldaddimg = "";
			$i = 1;
			$j = 1;
			foreach($pages as $page){
				include('pagelistitem.php');
				$i++;
			}
		}
	?>

</div>
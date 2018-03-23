<div class="content__pagelist pagelist">

	<?php 
		if (function_exists('get_pagelist')) {
			$pages = get_pagelist();
			echo "<div class='pagelist__imgbreak'>";

			foreach($pages as $page){
				$thumbnailurl = "";
				if (has_post_thumbnail($page->ID)) {
					$thumbnailurl = get_the_post_thumbnail_url( $page->ID, 'thumbnail');
				} else {
					//search for ancestors thumbnails
					$ancestors = get_ancestors($page->ID, 'page');
					$ancestors = array_reverse($ancestors);
					if($ancestors){
						foreach($ancestors as $ancestor){
							if (has_post_thumbnail($ancestor)) {
								$thumbnailurl = get_the_post_thumbnail_url( $ancestor, 'thumbnail');
							}
						}
					}
				}



				if ($thumbnailurl != $lastthumbnailurl){
					echo "</div>";
					echo "<div class='pagelist__imgbreak'>";
					$lastthumbnailurl = $thumbnailurl;
				}



				include('pagelistitem.php');
			}
			echo "</div>";
		}
	?>

</div>
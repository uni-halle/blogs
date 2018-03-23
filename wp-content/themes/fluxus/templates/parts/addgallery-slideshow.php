<?php 
if(function_exists("get_page_or_pageancestor_gallery_ids")) {
	foreach ( get_post_types( '', 'names' ) as $post_type ) {
		if($post_type == $post->post_type) {
			if($post_type == "page"){
				// is page – look for added gallery or added gallery of ancestor
				if(count(get_page_or_pageancestor_gallery_ids($post->ID)) > 0) {
					echo "<div class='addgalleryslideshow'>";
					foreach(get_page_or_pageancestor_gallery_ids($post->ID) as $key => $id) {
						$image = wp_get_attachment_image_src($id, 'large');
						echo "<div class='addgalleryslideshow__item' style='background: url(" . $image[0] . "); background-size: cover; background-position: center center; background-repeat: no-repeat;'></div>";
					}
					echo "</div>";
				}
			} else {
				// is another posttype – look for added gallery of the post-type-homepage
				if(count(get_page_or_pageancestor_gallery_ids(get_option('homefor_' . $post_type))) > 0) {
					echo "<div class='addgalleryslideshow'>";
					foreach(get_page_or_pageancestor_gallery_ids(get_option('homefor_' . $post_type)) as $key => $id) {
						$image = wp_get_attachment_image_src($id, 'large');
						echo "<div class='addgalleryslideshow__item' style='background: url(" . $image[0] . "); background-size: cover; background-position: center center; background-repeat: no-repeat;'></div>";
					}
					echo "</div>";
				}
			}
		}
	}
}
?>
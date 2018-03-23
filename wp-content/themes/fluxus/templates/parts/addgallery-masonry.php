<?php 
if(function_exists("get_post_gallery_ids") && count(get_post_gallery_ids($post->ID)) > 0) {
	echo "<div class='addgallery addgallerymasonry masonry'>";
	echo "<div class='grid-sizer'></div>";
	$count = 0;
	foreach(get_post_gallery_ids($post->ID) as $key => $id) {
		$count++;

		$attachment = get_post($id);
		if(get_post_meta($id, '_wp_attachment_image_alt', true)) {
			$alttext = get_post_meta($id, '_wp_attachment_image_alt', true);
		} else {
			$alttext = $attachment->post_excerpt;
		}

		if($count == 1){
			echo "<div class='addgallerymasonry__item addgallerymasonry__item--first'>";
			echo wp_get_attachment_link($id, "small", "", "", "", array("class" => "addgallerymasonry__img", "title" => $attachment->post_excerpt, "alt" => $alttext));
			echo "</div>";
		} else {
			echo "<div class='addgallerymasonry__item'>";
			echo wp_get_attachment_link($id, "thumbnail", "", "", "", array("class" => "addgallerymasonry__img", "title" => $attachment->post_excerpt, "alt" => $alttext));
			echo "</div>";
		}
	}
	echo "</div>";
}
?>
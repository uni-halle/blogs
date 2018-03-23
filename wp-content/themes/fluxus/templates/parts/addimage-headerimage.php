<?php 
if(function_exists("get_fluxus_addimage")) {
	foreach ( get_post_types( '', 'names' ) as $post_type ) {
		if($post_type == $post->post_type) {
			if($post_type == "page"){
				// is page – look for added image or added image of ancestor
				if(get_fluxus_addimage($post)) {
					echo "<div class='addimageheaderimage' style='background: url(" . get_fluxus_addimage_url($post) . "); background-size: cover; background-position: center center; background-repeat: no-repeat;'></div>";
				}
			} else {
				// is another posttype – look for added gallery of the post-type-homepage
				if(get_option('homefor_' . $post_type)){
					$ancestor = get_post(get_option('homefor_' . $post_type));
				}
				if(get_fluxus_addimage($post)) {
					echo "<div class='addimageheaderimage' style='background: url(" . get_fluxus_addimage_url($ancestor) . "); background-size: cover; background-position: center center; background-repeat: no-repeat;'></div>";
				}
			}
		}
	}
}
?>
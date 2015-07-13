<?php

namespace AGPressGraph;


/**
 * theButtons class.
 * @since 6.0
 */
class theButtons{
	public function buttonWithOptions($url, $wrapped = true,$echo = true){
		$customClass = get_option("AGPressGraph_like_css", false);
		
		$button = "";
		if($wrapped)
			$button .= "<div ".($customClass !== false ? "class='$customClass'" : "").">";
			
		$button .= '<div class="fb-like" data-href="'.$url.'" data-colorscheme="'.get_option("AGPressGraph_like_color", "light").'" data-width="'.get_option("AGPressGraph_like_type", "40px").'" data-kid-directed-site="'.get_option("AGPressGraph_like_kid_restricted", "false").'" data-layout="'.get_option("AGPressGraph_like_type", "standard").'" data-action="'.get_option("AGPressGraph_like_verb", "like").'" data-show-faces="'.get_option("AGPressGraph_like_face", "false").'" data-share="'.get_option("AGPressGraph_like_include_share", "false").'" ></div>';
		
		if($wrapped)
			$button .= "</div>";
		
		if($echo)
			echo $button;
		else
			return $button;
	}
	
	public function customizedButton($atts){
		
		if(is_singular() || is_page()){
			$postID = get_the_ID();
			$postURL = get_permalink($postID);
		}else{
			$postURL = "";
		}
		
		$atts = shortcode_atts(
		array(
			'url' => postURL,
			'colorscheme' => 'light',
			'width' => '100px',
			'kid_restricted' => 'false',
			'layout' => 'standard',
			'action' => 'like',
			'show_faces' => "false",
			'share' => "false",
		), $atts, 'PressGraphLike' );
		
		$button = '<div class="fb-like" data-href="'.$atts["url"].'" data-colorscheme="'.$atts["colorscheme"].'" data-width="'.$atts["width"].'" data-kid-directed-site="'.$atts["kid_restricted"].'" data-layout="'.$atts["layout"].'" data-action="'.$atts["action"].'" data-show-faces="'.$atts["show_faces"].'" data-share="'.$atts["share"].'" ></div>';
		
		return $button;
	}
	
	public function addButtonsShortcodes(){
		add_shortcode("PressGraphLike", array(__CLASS__, "customizedButton"));
	}
}
?>
<?php
/*
Plugin Name: fluxus contentgallery
Description: Replace standard gallery with two types of image galleries (masonry and slider) for content-area.
Version: 0.1
Author: Friedrich Lux
Author URI: http://www.behnelux.de
License: GPLv2
*/



//activate scripts
function contentgallery_enqueue() {
	wp_enqueue_script('contentgallery', get_template_directory_uri() . '/includes/contentgallery/contentgallery.js', array('jquery'), 'r3', true);
}
add_action( 'wp_enqueue_scripts', 'contentgallery_enqueue' );



function theme_gallery_defaults( $settings ) {
	$settings['galleryDefaults']['columns'] = 5;
	$settings['galleryDefaults']['size'] = 'medium';
	$settings['galleryDefaults']['link'] = 'file';
	return $settings;
}
add_filter( 'media_view_settings', 'theme_gallery_defaults' );



add_filter('post_gallery', 'my_post_gallery', 10, 2);
function my_post_gallery($output, $attr) {
	global $post;



	// set to masonry if is not specified
	if(!array_key_exists( "type", $attr )) { $attr["type"] = "masonry"; }



	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby'])
			unset($attr['orderby']);
	}

	extract(shortcode_atts(array(
		'order' => 'ASC',
		'orderby' => 'menu_order ID',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'size' => 'medium',
		'include' => '',
		'exclude' => ''
	), $attr));

	$id = intval($id);
	if ('RAND' == $order) $orderby = 'none';

	if (!empty($include)) {
		$include = preg_replace('/[^0-9,]+/', '', $include);
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	}

	if (empty($attachments)) return '';
	



	
	// slider
	if ($attr["type"] == "slider") {
		$stack = "";
		$count = 0;
		foreach ($attachments as $id => $attachment) {
			if(get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)) {
				$alttext = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
			} else {
				$alttext = $attachment->post_excerpt;
			}
			$count++;
			$stack .= '<div class="contentgalleryslider__item contentgalleryslider__item--' . $count . '" data="' . $count . '">';
			$stack .= wp_get_attachment_link($id, "full", "", "", "", array("class" => "contentgalleryslider__img", "title" => $attachment->post_excerpt, "alt" => $alttext));
			$stack .= '</div>';
			}
		
		
		
		$thumbs = "";
		$count = 0;
		foreach ($attachments as $id => $attachment) {
			$count++;
			$thumbs .= wp_get_attachment_image($id, "small", "", array("class" => "contentgalleryslider__thumb contentgalleryslider__thumb--" . $count, "data" => $count));
			}
		
		
		
		$captions = "";
		$count = 0;
		foreach ($attachments as $id => $attachment) {
			$count++;
			$captions .= "<div class='contentgalleryslider__caption contentgalleryslider__caption--" . $count . "'>" . $attachment->post_excerpt . "</div>";
			}
		
		
		
		$controls = "";
		$count = 0;
		if (count($attachments) > 1) {
			foreach ($attachments as $id => $attachment) {
				$count++;
				$controls .= "<div class='contentgalleryslider__control contentgalleryslider__control--" . $count . "'>";
				if($count == "1") {
					$controls .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlprev contentgalleryslider__controlitem--hidden'><img src='" . get_template_directory_uri() . "/img/prev.svg' alt='previous'/></span>"; 
				} else {
					$controls .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlprev'><img src='" . get_template_directory_uri() . "/img/prev.svg' alt='previous'/></span>"; 
				}
				$controls .= "<span class='contentgalleryslider__controltext'>" . $count . "&nbsp;/ " . count($attachments) . "</span>";
				if($count == count($attachments)) { 
					$controls .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlnext contentgalleryslider__controlitem--hidden'><img src='" . get_template_directory_uri() . "/img/next.svg' alt='next'/></span>"; 
				} else {
					$controls .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlnext'><img src='" . get_template_directory_uri() . "/img/next.svg' alt='next'/></span>"; 
				}
				$controls .= "</div>";
			}
		}
		
		
		
		$controlcaptionmix = "";
		$count = 0;
		if (count($attachments) > 1) {
			foreach ($attachments as $id => $attachment) {
				$count++;
				$controlcaptionmix .= "<div class='contentgalleryslider__controlcaptionmixitem contentgalleryslider__controlcaptionmixitem--" . $count . "'>";
				if($count == "1") {
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlprev contentgalleryslider__controlitem--hidden'><img src='" . get_template_directory_uri() . "/img/prev.svg' alt='previous'/></span>"; 
				} else {
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlprev'><img src='" . get_template_directory_uri() . "/img/prev.svg' alt='previous'/></span>"; 
				}
				$controlcaptionmix .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controltext'>" . $count . "&nbsp;/ " . count($attachments) . "</span>";
				if($count == count($attachments)) { 
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlnext contentgalleryslider__controlitem--hidden'><img src='" . get_template_directory_uri() . "/img/next.svg' alt='next'/></span>"; 
				} else {
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem contentgalleryslider__controlnext'><img src='" . get_template_directory_uri() . "/img/next.svg' alt='next'/></span>"; 
				}
				if($attachment->post_excerpt) { 
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem'>|</span>"; 
					$controlcaptionmix .= "<span class='contentgalleryslider__controlitem'>" . $attachment->post_excerpt . "</span>";
				}
				$controlcaptionmix .= "</div>";
			}
		}
		
		
		
		$output = "<div class='contentgalleryslider gallery'>";
		$output .= "<div class='contentgalleryslider__slider'>" . $stack . "</div>";
		//$output .= "<div class='contentgalleryslider__thumbs'>" . $thumbs . "</div>";
		//$output .= "<div class='contentgalleryslider__controls'>" . $controls . "</div>";
		//$output .= "<div class='contentgalleryslider__captions'>" . $captions . "</div>";
		$output .= "<div class='contentgalleryslider__controlcaptionmix'>" . $controlcaptionmix . "</div>";
		$output .= "</div>";
		
		return $output;
	} else {
	// masonry
		$output = "<div class='contentgallerymasonry gallery masonry'>";
		foreach ($attachments as $id => $attachment) {
			if(get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)) {
				$alttext = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
			} else {
				$alttext = $attachment->post_excerpt;
			}
			$output .= "<div class='contentgallerymasonry__item contentgallerymasonry__item--" . $attr["columns"] . "columns'>";

			////////// sonderfall winckelmann
/*
			if(strstr(basename(get_attached_file($id)), "quote") && strstr(basename(get_attached_file($id)), ".svg")) { 
				$output .= "<a href='" . wp_get_attachment_image_src($id, $attr["size"])[0] . "' class='imagelightbox__imglink'>";
				$output .= file_get_contents(get_template_directory_uri()."/img/quote.svg");
				$output .= "</a>";
			} else {
				if(empty($attr["link"]) || $attr["link"] == "none"){
					$output .= wp_get_attachment_image($id, $attr["size"], "", array( "class" => "contentgallerymasonry__img" ));
				} else {
					$output .= wp_get_attachment_link($id, $attr["size"], "", "", "", array("class" => "contentgallerymasonry__img", "title" => $attachment->post_excerpt, "alt" => $alttext ));
				}
			}
*/

			if(empty($attr["link"]) || $attr["link"] == "none"){
				$output .= wp_get_attachment_image($id, $attr["size"], "", array( "class" => "contentgallerymasonry__img" ));
			} else {
				$output .= wp_get_attachment_link($id, $attr["size"], "", "", "", array("class" => "contentgallerymasonry__img", "title" => $attachment->post_excerpt, "alt" => $alttext ));
			}
			if( $attachment->post_excerpt ){
				$output .= "<div class='contentgallerymasonry__caption'>" . $attachment->post_excerpt . "</div>";
			}
			if( get_post_meta( $id, '_imagetext', true ) ){
				$output .= "<div class='imagelightbox__imgtext' style='display: none;'>" . wpautop(get_post_meta( $id, '_imagetext', true )) . "</div>";
			}
			$output .= "</div>";
		}
		$output .= "</div>";
		
		return $output;
	}
}




// add option to admin-page
add_action('print_media_templates', function(){

	echo "<script type='text/html' id='tmpl-custom-gallery-setting'>";
	echo "<label class='setting'>";
	echo "<hr />";
	echo "<span>Gallery Type</span>";
	echo "<select name='type' data-setting='type' onchange='getval(this);'>";
	echo "<option value='masonry'>Masonry</option>";
	echo "<option value='slider'>Slider</option>";
	echo "</select>";
	echo "</label>";
	echo "</script>";

	echo "<script>";
	echo "jQuery(document).ready(function() {";
	echo "wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({";
	echo "template: function(view){ return wp.media.template('gallery-settings')(view) + wp.media.template('custom-gallery-setting')(view); }";
	echo "});";
	echo "});";
	echo "</script>";

});

?>
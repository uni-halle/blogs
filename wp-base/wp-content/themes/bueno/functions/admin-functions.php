<?php

/*
WooThemes Framework Version & Theme Version

*/
function woo_version(){

    $woo_framework_version = "1.2.2";

    $theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
    $theme_version = $theme_data['Version'];

    echo '<meta name="generator" content="'. get_option('woo_themename').' '. $theme_version .'" />' ."\n";
    echo '<meta name="generator" content="Woo Framework Version '. $woo_framework_version .'" />' ."\n";
   
}
add_action('wp_head','woo_version');

/*
Get Image from custom field
This function gets the custom field image and uses thumb.php to resize it
Parameters: 
        $key = Custom field key eg. "image"
        $width = Set width manually without using $type
        $height = Set height manually without using $type
         $class = CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
        $quality = Enter a quality between 80-100. Default is 90
        $id = Assign a custom ID, if alternative is required.
        $link = Echo with image links ('src') or just echo as image ('img').
        $repeat = Auto Img Function. Adjust amount of images to return for the post attachments.
        $offset = Auto Img Function. Offset the $repeat with assigned amount of objects.
        $before = Auto Img Function. Add Syntax before image output.
        $after = Auto Img Function. Add Syntax after image output.
        $single = Auto Img Function Only. Forces "img" return on images, like on single.php template
        $force = Force smaller images to not be effected with image width and height dimentions (proportions fix)
        $return = Return results instead of echoing out.
*/


function woo_get_image($key = 'image', $width = null, $height = null, $class = "thumbnail", $quality = 90,$id = null,$link = 'src',$repeat = 1,$offset = 0,$before = '', $after = '',$single = false, $force = false, $return = false) {

    if(empty($id))
    {
    global $post;
    $id = $post->ID;
    } 
    $output = '';

    $custom_field = get_post_meta($id, $key, true);

    $set_width = ' width="' . $width .'" ';
    $set_height = ' height="' . $height .'" '; 
    
    if($height == null OR $height == ''){
        $set_height = '';
    }

    if(!empty($custom_field)) { // If the user set a custom field
        
		// Check if WPMU and set correct path
		global $blog_id;
		if (isset($blog_id) && $blog_id > 0) {
			$imageParts = explode('/files/', $custom_field);
			if (isset($imageParts[1])) {
				$custom_field = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
			}
		}

		// Do check to verify if images are smaller then specified.
        $force_all = get_option('woo_force_all');
        $force_single = get_option('woo_force_single');
        if($force == true OR $force_all == true OR ($force_single == true AND is_single())){  
                $set_width = '';
                $set_height = '';
        }
    
        if (get_option('woo_resize') == 'true') { 
        
            $img_link = '<img src="'. get_bloginfo('template_url'). '/thumb.php?src='. $custom_field .'&amp;w='. $width .'&amp;h='. $height .'&amp;zc=1&amp;q='. $quality .'" alt="'. get_the_title($id) .'" class="'. $class .'" '. $set_width . $set_height . ' />';
            
            if($link == 'img'){  // Just output the image
                $output .= $before; 
                $output .= $img_link;
                $output .= $after;  
            }
            else {  // Default - output with link
                 if ((is_single() OR is_page()) AND $single == false) {
                    $href = $custom_field; 
					$rel = 'rel="lightbox"';
                 }
                 else { 
                    $href = get_permalink($id);
					$rel = '';
                 }
                 
                 $output .= $before; 
                 $output .= '<a title="'. get_the_title($id) .'" href="' . $href .'" '.$rel.'>' . $img_link . '</a>';
                 $output .= $after;  
            }
        } 
        else {  // Not Resize
            
             $img_link =  '<img src="'. $custom_field .'" alt="'. get_the_title($id) .'" '. $set_width . $set_height . ' class="'. $class .'" />';
             if($link == 'img'){  // Just output the image 
             
                   $output .= $before;                   
                   $output .= $img_link; 
                   $output .= $after;  
             } 
             
             else {  // Default - output with link
                 if ((is_single() OR is_page()) AND $single == false) 
                 { 
                    $href = $custom_field;
					$rel = 'rel="lightbox"';
                 }
                 else { 
                    $href = get_permalink($id);
					$rel = '';
                 }
                 
                 $output .= $before;   
                 $output .= '<a title="'. get_the_title($id) .'" href="' . $href .'" '. $rel .'>' . $img_link . '</a>';
                 $output .= $after;   
            }
        }
             if($return == TRUE)
                {
                    return $output;
                }
                else 
                {
                    echo $output; // Done  
                }
        
    } 
    elseif(empty($custom_field) && get_option('woo_auto_img') == 'true'){
        
        if($offset >= 1){$repeat = $repeat + $offset;}
    
        $attachments = get_children( array(
                'post_parent' => $id,
                'numberposts' => $repeat,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
				'order' => 'DESC', 
				'orderby' => 'menu_order date')
                );
        if ( empty($attachments) )
        return;
        
        $counter = -1;
        $size = 'large';
        foreach ( $attachments as $att_id => $attachment ) {
            
            $counter++;
            
            if($counter < $offset) { continue; }
        
            $output = '';
            $src = wp_get_attachment_image_src($att_id, $size, true);
            //$link = get_attachment_link($id);
            $custom_field = $src[0];
            
            // Do check to verify if images are smaller then specified.
            $force_all = get_option('woo_force_all');
            $force_single = get_option('woo_force_single');
            if($force == true OR $force_all == true OR ($force_single == true AND is_single())){  
                $set_width = '';
                $set_height = '';
            }
            
            if (get_option('woo_resize') == 'true') { 
			
				// Check if WPMU and set correct path
				global $blog_id;
				if (isset($blog_id) && $blog_id > 0) {
					$imageParts = explode('/files/', $custom_field);
					if (isset($imageParts[1])) {
						$custom_field = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
					}
				}		
			
				$img_link = '<img src="'. get_bloginfo('template_url'). '/thumb.php?src='. $custom_field .'&amp;w='. $width .'&amp;h='. $height .'&amp;zc=1&amp;q='. $quality .'" alt="'. get_the_title($id) .'" class="'. $class .'" '. $set_width . $set_height . '   />';
            
            if($link == 'img' AND $single == false){  // Just output the image  
            
                $output .= $before; 
                $output .= $img_link;
                $output .= $after;  
            }
                
            else {  // Default - output with link
                 if ((is_single() OR is_page()) AND $single == false) {
                    $href = $custom_field;
					$rel = 'rel="lightbox"';
				 }
                 else { 
                    $href = get_permalink($id);
					$rel = '';
                 }
                 
                 $output .= $before;
                 $output .= '<a title="'. get_the_title($id) .'" href="' . $href .'" '.$rel.'>' . $img_link . '</a>';
                 $output .= $after;   
            }
        } 
        else {  // Not Resize
             $img_link =  '<img src="'. $custom_field .'" alt="'. get_the_title($id) .'" '. $set_width . $set_height . ' class="'. $class .'"  />';
             if($link == 'img'){  // Just output the image  
                $output .= $before; 
                $output .= $img_link;
                $output .= $after;  
             } 
             else {  // Default - output with link
                 if ((is_single() OR is_page()) AND $single == false) {
                    $href = $custom_field; 
					$rel = 'rel="lightbox"';
                 }
                 else { 
                    $href = get_permalink($id);
					$rel = '';
                  }
                  
                $output .= $before;   
                $output .= '<a title="'. get_the_title($id) .'" href="' . $href .'" '. $rel .'>' . $img_link . '</a>';
                $output .= $after; 
            }
        }
            if($return == TRUE)
            {
                return $output;
            }
            else 
            {
                echo $output; // Done  
            }
      }
      
    }
    else {
       return;
    }

}


/*
Get Video
This function gets the embed code from the custom field
Parameters: 
        $key = Custom field key eg. "embed"
        $width = Set width manually without using $type
        $height = Set height manually without using $type
*/

function woo_get_embed($key, $width, $height, $class = 'video', $id = null) {

  if(empty($id))
    {
    global $post;
    $id = $post->ID;
    } 
    

$custom_field = get_post_meta($id, $key, true);

if ($custom_field) : 

    $org_width = $width;
    $org_height = $height;
    
    // Get custom width and height
    $custom_width = get_post_meta($id, 'width', true);
    $custom_height = get_post_meta($id, 'height', true);    
    
    // Set values: width="XXX", height="XXX"
    if ( !$custom_width ) $width = 'width="'.$width.'"'; else $width = 'width="'.$custom_width.'"';
    if ( !$custom_height ) $height = 'height="'.$height.'"'; else $height = 'height="'.$custom_height.'"';
    $custom_field = stripslashes($custom_field);
    $custom_field = preg_replace( '/width="([0-9]*)"/' , $width , $custom_field );
    $custom_field = preg_replace( '/height="([0-9]*)"/' , $height , $custom_field );    

    // Set values: width:XXXpx, height:XXXpx
    if ( !$custom_width ) $width = 'width:'.$org_width.'px'; else $width = 'width:'.$custom_width.'px';
    if ( !$custom_height ) $height = 'height:'.$org_height.'px'; else $height = 'height:'.$custom_height.'px';
    $custom_field = stripslashes($custom_field);
    $custom_field = preg_replace( '/width:([0-9]*)px/' , $width , $custom_field );
    $custom_field = preg_replace( '/height:([0-9]*)px/' , $height , $custom_field );    

	// Suckerfish menu hack
	$custom_field = str_replace('<embed ','<param name="wmode" value="transparent"></param><embed wmode="transparent" ',$custom_field);

	$output = '';
    $output .= '<div class="'. $class .'">' . $custom_field . '</div>';
    
    return $output; 
    
endif;

}

// Show menu in header.php
// Exlude the pages from the slider
function woo_show_pagemenu( $exclude="" ) {
    // Split the featured pages from the options, and put in an array
    if ( get_option('woo_ex_featpages') ) {
        $menupages = get_option('woo_featpages');
        $exclude = $menupages . ',' . $exclude;
    }
    
    $pages = wp_list_pages('sort_column=menu_order&title_li=&echo=0&depth=1&exclude='.$exclude);
    $pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
    $pages = str_replace('</a>','</span></a>', $pages);
    echo $pages;
}

// Get the style path currently selected
function woo_style_path() {
    $style = $_REQUEST[style];
    if ($style != '') {
        $style_path = $style;
    } else {
        $stylesheet = get_option('woo_alt_stylesheet');
        $style_path = str_replace(".css","",$stylesheet);
    }
    if ($style_path == "default")
      echo 'images';
    else
      echo 'styles/'.$style_path;
}

// Get the style path currently selected
function get_page_id($page_name){
    global $wpdb;
    $page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."' AND post_status = 'publish' AND post_type = 'page'");
    return $page_name;
}

//Short Codes
function woo_post_insert_shortcode($attr) {

    // Allow plugins/themes to override the default gallery template.
    $output = apply_filters('insert', '', $attr);
    if ( $output != '' )
        return $output;

    extract(shortcode_atts(array(
        'name'      => null,
        'id'         => null,
        'before'    => '',
        'after'     => ''
    ), $attr));

    $id = intval($id);
    
    global $wpdb;
    if($name == ''){
    $query = "SELECT post_content FROM $wpdb->posts WHERE id = $id";

    } 
    else
    {
       $query = "SELECT post_content FROM $wpdb->posts WHERE post_name = '$name'";   
    }
    
    $result = $wpdb->get_var($query);
    
    if(!empty($result)){
        $result = wpautop( $result, $br = 1 ); 
        return $before . $result . $after;
    }
    else
        return;

}

add_shortcode('insert', 'woo_post_insert_shortcode');  // use "[page]" in a post


?>
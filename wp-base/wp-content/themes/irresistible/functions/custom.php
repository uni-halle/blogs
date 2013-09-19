<?php 

// Custom fields for WP write panel
// This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/

$woo_metaboxes = array(
		"embed" => array (
			"name"		=> "embed",
			"default" 	=> "",
			"label" 	=> "Embed Code",
			"type" 		=> "text",
			"desc"      => "Paste the embed code for your video here. Video will be resized automatically. Add <strong>video</strong> as a tag in your post to make your video appear in the sidebar video widget.",
			"input"     => "textarea"
		),
	);
	
function woothemes_meta_box_content() {
	global $post, $woo_metaboxes;
	echo '<table>'."\n";
	foreach ($woo_metaboxes as $woo_metabox) {
		$woo_metaboxvalue = get_post_meta($post->ID,$woo_metabox["name"],true);
		if ($woo_metaboxvalue == "" || !isset($woo_metaboxvalue)) {
			$woo_metaboxvalue = $woo_metabox['default'];
		}
		echo "\t".'<tr>';
		echo "\t\t".'<th style="text-align: right; width:100px;"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].':</label></th>'."\n";
		if ( $woo_metabox['input'] == "textarea" ) {
		echo "\t\t".'<td><textarea rows="8" cols="90" style="width:100%; font-size:10px; line-height:16px; font-family:verdana,sans-serif;" onclick="this.select()" onfocus="this.select()" type="'.$woo_metabox['type'].'" name="woothemes_'.$woo_metabox["name"].'" id="'.$woo_metabox.'"/>'.$woo_metaboxvalue.'</textarea></td>'."\n";
		} else {
		echo "\t\t".'<td><input size="60" type="'.$woo_metabox['type'].'" value="'.$woo_metaboxvalue.'" name="woothemes_'.$woo_metabox["name"].'" id="'.$woo_metabox.'"/></td>'."\n";
		}
		echo "\t".'</tr>'."\n";
		echo "\t\t".'<tr><td></td><td><span style="font-size:11px">'.$woo_metabox['desc'].'</span></td></tr>'."\n";				
	}
	echo '</table>'."\n\n";
}

function woothemes_metabox_insert($pID) {
	global $woo_metaboxes;
	foreach ($woo_metaboxes as $woo_metabox) {
		$var = "woothemes_".$woo_metabox["name"];
		if (isset($_POST[$var])) {			
			if( get_post_meta( $pID, $woo_metabox["name"] ) == "" )
				add_post_meta($pID, $woo_metabox["name"], $_POST[$var], true );
			elseif($_POST[$var] != get_post_meta($pID, $woo_metabox["name"], true))
				update_post_meta($pID, $woo_metabox["name"], $_POST[$var]);
			elseif($_POST[$var] == "")
				delete_post_meta($pID, $woo_metabox["name"], get_post_meta($pID, $woo_metabox["name"], true));
		}
	}
}

function woothemes_meta_box() {
	if ( function_exists('add_meta_box') ) {
		add_meta_box('woothemes-settings',$GLOBALS['themename'].' Custom Settings','woothemes_meta_box_content','post','normal');
		add_meta_box('woothemes-settings',$GLOBALS['themename'].' Custom Settings','woothemes_meta_box_content','page','normal');
	}
}

add_action('admin_menu', 'woothemes_meta_box');
add_action('wp_insert_post', 'woothemes_metabox_insert');
?>
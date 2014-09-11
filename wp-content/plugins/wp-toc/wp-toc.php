<?php

/* wp-content/plugins/wp-toc/wp-toc.php */

/*
Plugin Name: WP-TOC
Plugin URI: http://wordpress.org/extend/plugins/wp-toc/
Description: Inserts a Table of Contents into your posts.
Author: Brendon Boshell
Version: 1.1
Author URI: http://infinity-infinity.com/
*/

function wptoc_init() {
	$domain = "wptoc";
	$plugin_dir = str_replace( basename(__FILE__) , "" , plugin_basename(__FILE__) );
	load_plugin_textdomain( $domain, "wp-content/plugins/" . $plugin_dir , $plugin_dir );
}

function wptoc_options_page() {
	if( isset($_POST["Submit"]) ):
	
		check_admin_referer('wptoc-update-options');
		
		$depth = (int)$_POST["depth"];
		update_option( "wptoc_depth", $depth );
		
		$list_type = "ul";
		if (in_array($_POST["list_type"], array("ul", "ol")))
			$list_type = $_POST["list_type"];
		
		update_option( "wptoc_list_type", $list_type );
		
		$style = "ul";
		if (in_array($_POST["style"], array("mediawiki", "none")))
			$style = $_POST["style"];
		
		update_option( "wptoc_style", $style );
		
		update_option( "wptoc_title", $_POST["title"] );
		
		?>
		<p><div id="message" class="updated">
			<p><strong>
			<?php _e("Your settings have been updated.", "wptoc"); ?>
			</strong></p>
		</div></p>
		<?php
	
	endif;
	$depth     = (int)get_option("wptoc_depth");
	$depth     = $depth ? $depth : 4;
	$list_type = get_option("wptoc_list_type");
	$list_type = $list_type ? $list_type : "ol";
	$style     = get_option("wptoc_style");
	$style     = $style ? $style : "mediawiki";
	$title     = get_option("wptoc_title");
	$title     = $title ? $title : "Contents";
	?>
	<div class="wrap">
		
		<h2><?php _e("WP-TOC: Table Of Contents", "wptoc"); ?></h2>
		
		<h3><?php _e("Default Options", "wptoc"); ?></h3>
		
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__) ?>">
			<?php wp_nonce_field('wptoc-update-options'); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"> 
							<label for="depth"><?php _e("Depth", "wptoc"); ?></label>
						</th>
						<td>
							<input type="text" size="7" name="depth" value="<?php echo esc_attr($depth); ?>" class="small-text " tabindex="1" />			
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"> 
							<label for="depth"><?php _e("Title", "wptoc"); ?></label>
						</th>
						<td>
							<input type="text" name="title" value="<?php echo esc_attr($title); ?>" class="text " tabindex="1" />			
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"> 
							<label for="list_type"><?php _e("List Type", "wptoc"); ?></label>
						</th>
						<td>
							<select name="list_type">
								<option value="ul"<?php if($list_type == "ul") { ?> selected="selected"<?php } ?>><?php _e("ul (Unordered)", "wptoc"); ?></option>
								<option value="ol"<?php if($list_type == "ol") { ?> selected="selected"<?php } ?>><?php _e("ol (Ordered)", "wptoc"); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"> 
							<label for="style"><?php _e("Style", "wptoc"); ?></label>
						</th>
						<td>
							<select name="style">
								<option value="none"><?php _e("None", "wptoc"); ?></option>
								<option value="mediawiki"<?php if($style == "mediawiki") { ?> selected="selected"<?php } ?>><?php _e("Mediawiki / Wikipedia", "wptoc"); ?></option>
								
							</select>
						</td>
					</tr>
				</tbody>				
			</table>
			
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
			</p>
			
		</form>
		
		<h3><?php _e("Usage", "wptoc"); ?></h3>
		
		<p><?php _e("To insert a Table of Contents, simply place the following code into a post:", "wptoc"); ?></p>
		
		<p><code><?php _e("[toc]", "wptoc"); ?></code></p>
		
		<p><?php _e("You can override the various setting on a per-post basis using parameters:", "wptoc"); ?></p>
		
		<p><code><?php _e("[toc depth=\"2\" listtype=\"ul\" title=\"Contents\"]", "wptoc"); ?></code></p>
			
	</div>
	<?php
}

function wptoc_admin_menu() {
	add_options_page( __("WP-TOC", "wptoc"), __("WP-TOC", "wptoc"), 8, __FILE__, "wptoc_options_page");
}

// open a nested list
function wptoc_open_level($new, $cur, $first, $type) {
	$levels = $new - $cur;
	$out = "";
	for($i = $cur; $i < $new; $i++) {
	
		$level = $i - $first + 2;
		if(($level % 2) == 0)
			$out .= "<{$type} class='toc-even level-{$level}'>\n";
		else
			$out .= "<{$type} class='toc-odd level-{$level}'>\n";
	}
	return $out;
}

// close the list
function wptoc_close_level( $new, $cur, $first, $type ) {
	$out = "";
	for($i = $cur; $i > $new; $i--)
		$out .= "</{$type}>\n";
	return $out;
}

$wptoc_used_names = array();

function wptoc_get_unique_name($heading) {
	global $wptoc_used_names;
	
	$n = str_replace(" ", "_", strip_tags($heading));
	$n = preg_replace("#[^A-Za-z0-9\-_\:\.]#", "", $n);
	$n = preg_replace("#^[^A-Za-z]*?([A-Za-z])#", "$1", $n);
	
	
	if (isset($wptoc_used_names[$n])) {
		$wptoc_used_names[$n]++;
		$n .= "_" . $wptoc_used_names[$n];	
		$wptoc_used_names[$n] = 0;	
	} else {
		$wptoc_used_names[$n] = 0;
	}
	
	return $n;
}

function wptoc_unique_names_reset() {
	global $wptoc_used_names;
	$wptoc_used_names = array();
	return true;
}

function wptoc_shortcode_toc($attribs) {
	global $post;
	
	wptoc_unique_names_reset();
	
	// replace with default values
	$attribs = shortcode_atts(
		array(
			"depth" => get_option("wptoc_depth"),
			"listtype" => get_option("wptoc_list_type"),
			"title" => get_option("wptoc_title")
		), 
		$attribs);
		
	extract($attribs);
	
	$depth = $depth ? $depth : 4;	
	$list_type = $listtype ? $listtype : "ol";
	$title = $title ? $title : "Contents";

	// get the post
	// don't consider stuff in <pre>s
	$content = preg_replace("#<pre.*?>(.|\n|\r)*?<\/pre>#i", "", $post->post_content);
	
	$lowest_heading = 1;
	
	// calculate the lowest value heading (ie <hN> where N is a number)
	// in the post
	for($i = 1; $i <= 6; $i++)
		if( preg_match("#<h" . $i . "#i", $content) ) {
			$lowest_heading = $i;
			break;
		}
		
	// maximum
	$max_heading = $lowest_heading + $depth - 1;
	
	// find page separation points
	$next_pages = array();
	preg_match_all("#<\!--nextpage-->#i", $content, $next_pages, PREG_OFFSET_CAPTURE);
	$next_pages = $next_pages[0];
	
	// get all headings in post
	$headings = array();
	preg_match_all("#<h([1-6])>(.*?)</h[1-6]>#i", $content, $headings, PREG_OFFSET_CAPTURE);
	
	$cur_level = $lowest_heading;
	
	$out = "<div class='toc wptoc'>\n";
	
	if ($title) 
		$out .= "<h2>" . $title . "</h2>\n";
		
	$out .= wptoc_open_level($lowest_heading, $lowest_heading-1, $lowest_heading, $list_type);	
	
	$first = true;
	
	$tabs = 1;
	
	// headings...
	foreach($headings[2] as $i => $heading) {
		$level = $headings[1][$i][0]; // <hN>
		
		if($level > $max_heading) // heading too deep
			continue;
		
		if($level > $cur_level) { // this needs to be nested
			$out .= str_repeat("\t", $tabs+1) . wptoc_open_level( $level, $cur_level, $lowest_heading, $list_type );
			$first = true;
			$tabs += 2;
		}
			
		if(!$first)
			$out .= str_repeat("\t", $tabs) . "</li>\n";
		$first = false;
			
		if($level < $cur_level) { // jump back up from nest
			$out .= str_repeat("\t", $tabs-1) . wptoc_close_level( $level, $cur_level, $lowest_heading, $list_type );
			$tabs -= 2;
		}
			
		$name = wptoc_get_unique_name($heading[0]);
		
		$page_num = 1;
		$pos = $heading[1];
		
		// find the current page
		foreach($next_pages as $p) {
			if($p[1] < $pos)
				$page_num++;
		}
		
		// output the Contents item with link to the heading. Uses
		// unique ID based on the $prefix variable.
		if($page_num != 1)
			$out .= str_repeat("\t", $tabs) . "<li>\n" . str_repeat("\t", $tabs+1) . "<a href=\"?p=" . $post->ID . "&page=" . $page_num . "#" . esc_attr($name). "\">" . $heading[0] . "</a>\n";
		else
			$out .= str_repeat("\t", $tabs) . "<li>\n" . str_repeat("\t", $tabs+1) . "<a href=\"#" . esc_attr($name). "\">" . $heading[0] . "</a>\n";
			
		$cur_level = $level; // set the current level we are at
	}
	
	if(!$first)
		$out .= str_repeat("\t", $tabs) . "</li>\n";
	
	// close up the list
	$out .= wptoc_close_level( 0, $cur_level, $lowest_heading, $list_type );
	
	$out .= "</div>\n";
	
	$out .= "<div class='wptoc-end'>&nbsp;</div>";
	
	// return value to repalce the Shortcode
	return $out;
}

function wptoc_heading_anchor($match) {
	//$name = str_replace(array("&#8216;", "&#8217;"), "'", $match[2]);
	
	$name = wptoc_get_unique_name($match[2]);
	
	return '<span id="' . esc_attr($name) . '">' . $match[0] . '</span>';
}

function wptoc_the_content($content) {
	global $wp_toc_prefix;
	
	wptoc_unique_names_reset();
	
	$out = preg_replace_callback("#<h([1-6])>(.*?)</h[1-6]>#i", "wptoc_heading_anchor", $content);
	return $out;
}

function wptoc_wp_head() {

	$style = get_option("wptoc_style");
	$style = $style ? $style : "mediawiki";

	switch($style) {

		case "mediawiki":

	?>
<style type="text/css">
	.wptoc {
		border: 1px solid #AAAAAA;
		padding: 5px;
		float: left;
		font-size: 0.95em;
	}
	.wptoc h2 {
		text-align: center;
		font-size: 1em;
		font-weight: bold;
	}
	.wptoc ul, .wptoc ol {
		list-style: none;
		padding: 0;
	}
	.wptoc ul ul, .wptoc ol ol {
		margin: 0 0 0 2em;
	}
	.wptoc-end {
		clear: both;
	}
</style>
	<?php
	
		break;
		default:
	
		break;
	}
}

add_action( "init", "wptoc_init" );
add_action( "admin_menu", "wptoc_admin_menu" );
add_shortcode( "toc", "wptoc_shortcode_toc" );
add_action( "the_content", "wptoc_the_content" );
add_action( "wp_head", "wptoc_wp_head" );

?>
<?php

define("ELEGANT_GRUNGE_FRAME_MAX_WIDTH", 415);
define("ELEGANT_GRUNGE_FRAME_SMALL_WIDTH", 140);
define("ELEGANT_GRUNGE_FRAME_SMALL_HEIGHT", 140);
define("ELEGANT_GRUNGE_FRAME_MIN_WIDTH", 25);
define("ELEGANT_GRUNGE_FRAME_MIN_HEIGHT", 25);


if ( function_exists('register_sidebar') ) {
	if ( get_option('page_setup') != 'no-sidebar' ) {
		register_sidebar(array(
			'name' => 'Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
	}
	if ( get_option('page_setup') == 'double-right-sidebar' ) {
		register_sidebar(array(
			'name' => 'Sidebar 2',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
	}
	register_sidebar(array(
		'name' => 'Footer',
		'before_widget' => '<div class="widget-wrap"><div class="widget %2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
}

if( !function_exists('array_combine') ) {
    function array_combine($a, $b) {
        $c = array();
        $at = array_values($a);
        $bt = array_values($b);
        foreach($at as $key=>$aval) $c[$aval] = $bt[$key];
        return $c;
    }
}

/**
 * Add frames
 *
 * Filters content, adding frames to all objects with class 'frame', and all images if
 *	corresponding option is set.
 */
function elegant_grunge_filter( $content , $arg2=null, $arg3=null ) {
	global $post;

	// Not for feeds
	if ( is_feed() ) return $content;
	
	// Look-ahead for class 'frame'
	$frameClass = '(?=[^>]+class=["\'][^"\']*frame)';
	
	// Skipped classes
	$classes = "(?:".join("|", array_map("trim", explode(",",get_option('frame_class_skip')))).")";
	$skippedClasses = '(?![^>]+class=["\'][^"\']*'.$classes.')';
	
	// Content which we want to include inside the frame
	$aStart = '(?:<\s*a[^>]+>\s*)?';
	$aEnd = '(?:\s*</a\s*>)?';
	$caption = '(?:\s*<p class="wp-caption-text">.*?</p>)?';

	// Beginning tag, including class check
	$startTagWithFrameClass = "<\s*((?:(img)|[a-zA-Z]+))${skippedClasses}${frameClass}[^>]*";
	
	// End of tag: Short form
	$endSingleTag = '(?(2)\s*/?>|\s*/>)';
	
	// End of tag: Long form
	$endOriginalTag = '</\s*\\g{1}\s*>';

	// Any tag
	$anyStartTag = '<\s*([a-z]+)[^>]*';
	$endLastTag = '</\s*\\g{-1}\s*>';
	
	// Nested content - tags within tags
	$nestedContent = "([^<]+|(?:$anyStartTag(?:$endSingleTag|>(?-2)*$endLastTag)))*";

	// Composite expression - look for a block with class of 'frame', and include all content
	$regex = "$aStart$startTagWithFrameClass(?:$endSingleTag|>$nestedContent$endOriginalTag)$aEnd$caption";
	$regexes = array("@".$regex."@is");

	// Also replace all images
	$frame_all_images = get_option("frame_all_images");
	$frame_all_images_post = get_post_meta($post->ID, 'frame_all_images', true);
	if ( $frame_all_images_post == "true" )
		$frame_all_images = true;
	else if ( $frame_all_images_post == "false" )
		$frame_all_images = false;
		
	if ( $frame_all_images ) {
		
		// Look-ahead for not class of frame (becuase we caught these above)
		$notFrameClass = '(?![^>]+class=["\'][^"\']*frame)';
		
		// Composite expression - any images not with class of 'frame'
		$regex = "$aStart\s*<\s*(img)${notFrameClass}${skippedClasses}[^>]+>\s*$aEnd$caption";
		$regexes[] = "@".$regex."@is";
	}
	
	// Perform replacement with helper function below
	$newContent = @preg_replace_callback($regexes, 'elegant_grunge_replace', $content);
	if ( !$newContent ) {
		return $content;
	}
	
	return $newContent;
}


/**
 * Filter helper function
 *
 *	Perform replacements for blocks discovered in the filter function above.
 *	Adds surrounding divs for frame, styled according to original block,
 * resizes too-large images, and ignores entire block if it is a too-small 
 * image (because it will look weird, otherwise)
 */
function elegant_grunge_replace($matches) {

	$inner = $matches[0];
	
	// Look for align and style attributes
	$align = '(^(?:<\s*a[^>]+>)?\s*<[^>]*?)align=["\'](left|right)["\']';
	$style = '(^(?:<\s*a[^>]+>)?\s*<[^>]*?)style=["\']([^"\']+)["\']';
	$class = '(^(?:<\s*a[^>]+>)?\s*<[^>]*?)class=["\']([^"\']+)["\']';

	$styles = "";
	if ( preg_match( "@$align@is", $inner, $newmatch ) ) {
		// Align attribute found: Add an equivalent float
		$styles .= "float: ".$newmatch[2].";";
	}
	
	if ( preg_match( "@$style@is", $inner, $newmatch ) ) {
		// Style attribute found: Remember content, but minus some undesirable elements
		$styles .= preg_replace("@(border(-[a-z]+)?|padding(-[a-z]+)?|margin-([a-z]+)?)\s*:[^;\"']*;?@is", "", $newmatch[2]);
	}
	
	if ( preg_match( "@$class@is", $inner, $newmatch ) ) {
		// Style attribute found: Remember content
		$classes = trim(preg_replace("@(?<![a-z])frame(?!=[a-z])@is", "", $newmatch[2]));
	}
	
	// Check width and height
	$widthRegex = '@(^(?:<\s*a[^>]+>)?\s*<[^>]*?(?:width=[\'"]|width:\s*))([0-9]+)@is';
	$heightRegex = '@(^(?:<\s*a[^>]+>)?\s*<[^>]*?(?:height=[\'"]|height:\s*))([0-9]+)@is';

	if ( preg_match( $widthRegex, $inner, $newmatch ) ) {
		$width = $newmatch[2];
	}
	if ( preg_match( $heightRegex, $inner, $newmatch ) ) {
		$height = $newmatch[2];
	}

	if ( ( $width && $width < ELEGANT_GRUNGE_FRAME_MIN_WIDTH ) || ( $height && $height < ELEGANT_GRUNGE_FRAME_MIN_HEIGHT ) ) {
		// Image is too small - just skip this one: return original content
		return $inner;
	} 
	
	if ( $width && $width > ELEGANT_GRUNGE_FRAME_MAX_WIDTH ) {
		// Image is too large - scale down proportionately
		if ( $height ) {
			$ratio = $width / $height;
			$height = round(ELEGANT_GRUNGE_FRAME_MAX_WIDTH / $ratio);
			
			// Replace height value
			$inner = preg_replace( $heightRegex, "\${1}$height", $inner );
		}
		$width = ELEGANT_GRUNGE_FRAME_MAX_WIDTH;
		
		// Replace width value
		$inner = preg_replace( $widthRegex, "\${1}$width", $inner );	
	}
	
	$small = '';
	if ( ( $width && $width < ELEGANT_GRUNGE_FRAME_SMALL_WIDTH ) || ( $height && $height < ELEGANT_GRUNGE_FRAME_SMALL_HEIGHT ) ) {
		// Image is too small for the large frame - use the small frame
		$small = ' small';
	}
	
	// Wrap content, and remove align/style from inner tag
	return '<span class="frame-outer '.$small.' '.$classes.'"'.($styles ? ' style="'.trim($styles).'"' : '' ).'><span><span><span><span>' .
					preg_replace(array("@${align}@is","@${style}@is"), '\\1', $inner).
				'</span></span></span></span></span>';
}



// Photoblog routines


/**
 * Prepare image from post
 *
 *	Scans post for images and sets a few variables on post object
 */
function image_setup(&$post) {
	
	if ( !preg_match("@(?:<a([^>]+?)/?>\s*)?<img([^>]+?)/?>(?:\s*</a>)?@", $post->post_content, $matches) ) {
        return;
    }

	$tagRegex = "@([a-zA-Z]+)(?:=([\"'])(.*?)\\2)@";
    if ( !preg_match_all($tagRegex, $matches[2], $tag) ) {
        return;
    } 

    $image = array_combine($tag[1], $tag[3]);

    if ( $matches[1] && preg_match_all($tagRegex, $matches[1], $tag) ) {
        $link = array_combine($tag[1], $tag[3]);
    }

    if ( !$image["src"] ) {
		return;
	}
	
	$post->thumb_url = $post->image_url = clean_url( $image["src"], 'raw' );
	$post->image_tag = $matches[0];
	
	if ( $link["href"] && preg_match("/jpg|jpeg|jpe|png|gif/i", $link["href"]) ) {
	    $post->image_url = $link["href"];
	}
	
	$post->image_dimensions = array("width" => $image["width"], "height" => $image["height"]);
	
	$post->image_info = $image;
	$post->image_link_info = $link;
	
	$description = trim(strip_tags(preg_replace("/\[[a-zA-Z][^\]]+\]/", "", $post->post_content)));
	if ( strlen($description) > 250 ) $description = substr($description, 0, 250)."...";
	$post->image_info["description"] = $description;
}

/**
 * Template tag: Get the image from the post
 *
 * @param	return	boolean	If true, returns the image tag instead of printing it
 */
function the_image($return = null) {
	global $post;
	if(!$post->image_tag) {
		image_setup($post);
	}
	if ($return) 
		return $post->image_tag; 
	else
		echo $post->image_tag;
}


/**
 * Template tag: Get the image URL from the post
 *
 * @param	return	boolean	If true, returns the image URL instead of printing it
 */
function the_image_url($return = null) {
	global $post;
	if(!$post->image_url) {
		image_setup($post);
	}
	if ($return) 
		return $post->image_url;
	else
		echo $post->image_url;
}

/**
 * Template tag: Get the thumbnail URL from the post
 *
 * @param	return	boolean	If true, returns the thumb URL instead of printing it
 */
function the_image_thumb_url($return = false) {
	global $post;
	if(!$post->thumb_url) {
		image_setup($post);
	}
	if ($return) 
		return $post->thumb_url;
	else
		echo $post->thumb_url;
}

/**
 * Get post image information
 *
 * @returns	Information about the image
 */
function the_image_info() {
	global $post;
	if(!$post->thumb_url) {
		image_setup($post);
	}
	return $post->image_info;
}

/**
 * Template tag: Determine if post has an image
 *
 * @returns	True if an image exists, false otherwise
 */
function has_image() {
	return (the_image_info() != null);
}

/**
 * Template tag: Get the scaled thumbnail
 *
 *	Attempts to create a new image derived from the original image and
 *	scaled down to width x height. Will crop out of center of image if
 *	aspect ratio does not match
 *
 * @param	width		int	Width of thumbnail
 * @param	height	int	Height of thumbnail
 *	@returns	Path to scaled thumbnail, or false on failure
 */
function the_image_scaled_thumb_url($width, $height) {
	
	global $post;
	$thumb = the_image_thumb_url(true);
	if ( !$thumb ) return false;
	
	if ( substr($thumb, 0, strlen(WP_CONTENT_URL)) == WP_CONTENT_URL ) {
		if ( file_exists($f = WP_CONTENT_DIR."/".substr($thumb, strlen(WP_CONTENT_URL))) )
			$thumb = $f;
	}
	
	$path = "elegant-grunge-thumbnails/".preg_replace("/[^a-zA-Z0-9]/", "-", $thumb)."-$width.$height.jpg";
	
	if ( file_exists(WP_CONTENT_DIR."/".$path) ) {
		return clean_url(WP_CONTENT_URL."/".$path, 'raw');
	}
	
	if ( !get_option('create_photoblog_thumbnails') ) return false;
	
	// Check for GD support
	if ( !function_exists('imagejpeg') ) return false;
	
 	require_once("Image.class.php");
	$image = Image::Load($thumb);
	if ( !$image ) return false;
	
	$image->Scale($width, $height, true);
	
	if ( !file_exists(WP_CONTENT_DIR."/elegant-grunge-thumbnails") ) {
		mkdir(WP_CONTENT_DIR."/elegant-grunge-thumbnails", 0755);
	}
	
	if ( !$image->Save(WP_CONTENT_DIR."/".$path) ) {
		return false;
	}
	
	return clean_url(WP_CONTENT_URL."/".$path, 'raw');
}

/**
 * Template tag: Get the thumbnail
 *
 * @param	width		int		Width of thumbnail (optional)
 * @param	height	int		Height of thumbnail (optional)
 * @param	return	boolean	If true, returns the thumb URL instead of printing it (optional)
 */
function the_thumbnail($width = 0, $height = 0, $return = false) {
	global $post;
	$url = the_image_url(true);
	if ( !$url ) return;
	
	$info = the_image_info();
	
	if ( !$width && !$height ) {
		$width = 100;
		$height = 80;
		if ( $info["width"] && $info["height"] ) {
			if ( $info["width"] > $info["height"] ) {
				$height = 100;
				$width = ($info["width"] / $info["height"]) * $height;
				if ( $width > 300 ) {
					$width = 300;
					$height = ($info["height"] / $info["width"]) * $width;
				}
			} else {
				$height = 100;
				$width = ($info["width"] / $info["height"]) * $height;
			}
		}
	
		$width = round($width);
		$height = round($height);
	}
	else if ( $width && !$height ) {
		$height = (3/4) * $width;
		if ( $info["width"] && $info["height"] ) {
			$height = ($info["height"] / $info["width"]) * $width;
		}
	}
	
	$thumb = the_image_scaled_thumb_url($width, $height);
	if ( !$thumb )
		$thumb = the_image_thumb_url(true);
	
	$link = (get_option('photoblog_lightbox') ? $url : get_permalink());
	
	ob_start();
	?>
	<div class="photoblog-thumbnail">
	<a href="<?php echo $link ?>" rel="lightbox[photoblog]"><img src="<?php echo $thumb; ?>" width="<?php echo $width; ?>" <?php echo ($height ? "height=\"$height\"" : ""); ?> alt="<?php the_title(); ?>" title="&lt;a href=&quot;<?php the_permalink(); ?>&quot;&gt;<?php the_title(); ?>&lt;/a&gt;<?php echo ($info["description"]?" - ".$info["description"]:""); ?>" /></a>
	</div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	
	if ( $return )
		return $content;
	echo $content;
}

/**
 * Favicon template tag
 */
function elegant_grunge_the_favicon() {
	if ( file_exists(WP_CONTENT_DIR."/favicon.ico") ) {	
		?><link rel="icon" href="<?php echo WP_CONTENT_URL?>/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="<?php echo WP_CONTENT_URL?>/favicon.ico" type="image/x-icon" /><?php
	}
}


/**
 * Administration
 */
function elegant_grunge_admin() {
	?>
	<div class="wrap">
	<h2>Elegant Grunge</h2>
	
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	
	<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><?php _e('Header image:', 'elegant-grunge') ?></th>
		<td>
			<input type="text" class="text" style="width: 400px;" name="header_image" value="<?php echo htmlspecialchars(get_option('header_image')) ?>" /><br/>
			 <small><?php _e('If specified, the image (typically a transparent PNG) at the above URL will be used for the header.', 'elegant-grunge') ?></small>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('RSS subscription:', 'elegant-grunge') ?></th>
		<td>
			<input type="checkbox" name="show_rss" <?php echo (get_option('show_rss') ? "checked" : ""); ?> />
			 <?php _e('Display RSS subscription link', 'elegant-grunge') ?>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Post info:', 'elegant-grunge') ?></th>
		<td>
			<input type="checkbox" name="show_author" <?php echo (get_option('show_author') ? "checked" : ""); ?> />
			 <?php _e('Display post author', 'elegant-grunge') ?>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Page setup:', 'elegant-grunge') ?></th>
		<td>
			<select name="page_setup">
				<option value="right-sidebar" <?php echo (get_option('page_setup')=='right-sidebar' ? 'selected' : '') ?>>Right sidebar</option>
				<option value="double-right-sidebar" <?php echo (get_option('page_setup')=='double-right-sidebar' ? 'selected' : '') ?>>Double Right sidebar</option>
				<option value="no-sidebar" <?php echo (get_option('page_setup')=='no-sidebar' ? 'selected' : '') ?>>No sidebar</option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Copyright message:', 'elegant-grunge') ?></th>
		<td>
			<input type="text" class="text" style="width: 300px;" name="copyright" value="<?php echo htmlspecialchars(get_option('copyright')) ?>" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Image frames:', 'elegant-grunge') ?></th>
		<td>
			<input type="checkbox" name="frame_all_images" <?php echo (get_option('frame_all_images') ? "checked" : ""); ?> />
			 <?php _e('Apply frame to all images', 'elegant-grunge') ?><br/>
			<small><?php printf(__('If enabled, all images larger than %1$d x %2$d
				will have a frame with drop shadow applied. Otherwise, only images and other elements with a class
				of \'frame\' will have this style applied.<br/>
				This setting can be configured per-post and per-page, also.', 'elegant-grunge'), ELEGANT_GRUNGE_FRAME_MIN_WIDTH, ELEGANT_GRUNGE_FRAME_MIN_HEIGHT) ?></small>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e("Don't frame images with class:", 'elegant-grunge') ?></th>
		<td>
			<input type="text" class="text" style="width: 300px;" name="frame_class_skip" value="<?php echo htmlspecialchars(get_option('frame_class_skip')) ?>" />
			<br/><small><?php _e('Separate multiple classes with commas \',\'', 'elegant-grunge') ?></small>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Extra header content:', 'elegant-grunge') ?></th>
		<td>
			<textarea style="width: 300px; height: 100px;" name="extra_header"><?php echo htmlspecialchars(get_option('extra_header')) ?></textarea><br/>
			<small><?php _e('This can be used to add extra RSS feed links from your page, for example, such as a Twitter feed.', 'elegant-grunge') ?></small>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Photoblog thumbnails:', 'elegant-grunge') ?></th>
		<td>
			<p>
			<input type="checkbox" name="create_photoblog_thumbnails" <?php echo (get_option('create_photoblog_thumbnails') ? "checked" : ""); ?> />
			 <?php _e('Create scaled thumbnails', 'elegant-grunge') ?><br/>
			<small><?php _e('If enabled, will generate thumbnail files. Otherwise, will use the original images, resulting in slower loading times.
			Note that the first photoblog page load after this is enabled will be slow, while images are being created, so you should
			<a href="/tag/photoblog" target="_blank">load this</a> yourself.', 'elegant-grunge') ?></small>
			</p>
			<p>
			<?php _e('Thumbnail size:', 'elegant-grunge') ?><br/>
			<input type="text" class="text" size="5" name="photoblog_thumb_width" value="<?php echo get_option('photoblog_thumb_width') ?>" /> x
			<input type="text" class="text" size="5" name="photoblog_thumb_height" value="<?php echo get_option('photoblog_thumb_height') ?>" /><br />
			<small><?php _e('Leave blank for flexible size', 'elegant-grunge') ?></small>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Photoblog display:', 'elegant-grunge') ?></th>
		<td>
			<p>
			<?php _e('Number of thumbnails per page:', 'elegant-grunge') ?><br/>
			<input type="text" class="text" size="5" name="photoblog_thumb_count" value="<?php echo get_option('photoblog_thumb_count') ?>" />
			</p>
			<p>
			<input type="checkbox" name="photoblog_lightbox" <?php echo (get_option('photoblog_lightbox') ? "checked" : ""); ?> />
			 <?php _e('Use lightbox mode', 'elegant-grunge') ?><br/>
			<small><?php _e('Requires a lightbox plugin to be installed, such as <a href="http://www.stimuli.ca/lightbox/">Lightbox 2</a>.', 'elegant-grunge') ?></small>
			</p>
			<p>
			<input type="checkbox" name="photoblog_frames" <?php echo (get_option('photoblog_frames') ? "checked" : ""); ?> />
			 <?php _e('Draw frames around photoblog items', 'elegant-grunge') ?><br/>
			</p>
		</td>
	</tr>
	
	
	</table>
	
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="header_image,show_rss,show_author,page_setup,copyright,frame_all_images,frame_class_skip,extra_header,create_photoblog_thumbnails,photoblog_thumb_count,photoblog_thumb_width,photoblog_thumb_height,photoblog_lightbox,photoblog_frames" />
	
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Save Changes', 'elegant-grunge') ?>" />
	</p>
	
	</form>
	</div>
	<?php
}

/**
 * Per-post setup
 */
function elegant_grunge_post_options() {
	global $post;
	$post_id = $post;
   if (is_object($post_id)) {
   	$post_id = $post_id->ID;
   }
	
	
	$frame_all_images = get_option("frame_all_images");
	$frame_all_images_post = get_post_meta($post_id, 'frame_all_images', true);
	if ( $frame_all_images_post == "true" )
		$frame_all_images = true;
	else if ( $frame_all_images_post == "false" )
		$frame_all_images = false;
	
	
	?>
	<div class="postbox closed">
   <h3><?php _e('Elegant Grunge Theme Options', 'elegant-grunge') ?></h3>
   <div class="inside">
	<input value="eg_edit" type="hidden" name="eg_edit" />
	<table class="form-table">
	<tr>
		<th style="text-align:left;" colspan="2"><?php _e('Image frames:', 'elegant-grunge') ?></th>
		<td>
			<input type="hidden" name="frame_all_images" id="eg_frame_all_images" value="" />
			<input type="checkbox" name="frame_all_images_disabled" <?php echo ($frame_all_images ? "checked" : ""); ?> 
				onchange="document.getElementById('eg_frame_all_images').value = (this.checked ? 'true' : 'false');" />
			 <?php _e('Apply frame to all images', 'elegant-grunge') ?><br/>
			<small><?php printf(__('If enabled, all images larger than %1$d x %2$d 
				will have a frame with drop shadow applied. Otherwise, only images and other elements with a class
				of \'frame\' will have this style applied.', 'elegant-grunge'), ELEGANT_GRUNGE_FRAME_MIN_WIDTH, ELEGANT_GRUNGE_FRAME_MIN_HEIGHT) ?></small>
		</td>
	</tr>
	</table>
	</div>
	</div>
	<?php
}

/**
 * Per-page setup
 */
function elegant_grunge_page_options() {
	global $post;
	$post_id = $post;
   if (is_object($post_id)) {
   	$post_id = $post_id->ID;
   }
	
	
	$frame_all_images = get_option("frame_all_images");
	$frame_all_images_post = get_post_meta($post_id, 'frame_all_images', true);
	if ( $frame_all_images_post == "true" )
		$frame_all_images = true;
	else if ( $frame_all_images_post == "false" )
		$frame_all_images = false;
	
	$relatedTitle = get_post_meta($post_id, 'related_title', true);
	if ( !$relatedTitle ) $relatedTitle = "Related posts";
	
	?>
	<div class="postbox closed">
   <h3><?php _e('Elegant Grunge Theme Options', 'elegant-grunge') ?></h3>
   <div class="inside">
	<input value="eg_edit" type="hidden" name="eg_edit" />
	<table class="form-table">
		<tr>
			<th style="text-align:left;" colspan="2"><?php _e('Image frames:', 'elegant-grunge') ?></th>
			<td>
				<input type="hidden" name="frame_all_images" id="eg_frame_all_images" value="" />
				<input type="checkbox" name="frame_all_images_disabled" <?php echo ($frame_all_images ? "checked" : ""); ?> 
					onchange="document.getElementById('eg_frame_all_images').value = (this.checked ? 'true' : 'false');" />
				 <?php _e('Apply frame to all images', 'elegant-grunge') ?><br/>
				<small><?php printf(__('If enabled, all images larger than %1$d x %2$d 
					will have a frame with drop shadow applied. Otherwise, only images and other elements with a class
					of \'frame\' will have this style applied.', 'elegant-grunge'), ELEGANT_GRUNGE_FRAME_MIN_WIDTH, ELEGANT_GRUNGE_FRAME_MIN_HEIGHT) ?></small>
			</td>
		</tr>
		
		<tr>
			<th style="text-align:left;" colspan="2"><?php _e('Related tag(s):', 'elegant-grunge') ?></th>
			<td>
				<input value="<?php echo htmlspecialchars(get_post_meta($post_id, 'related_tags', true)); ?>" name="related_tags" /><br />
				<small>
					<?php _e("If specified and 'Page with custom sidebar' page template is selected, will display posts from these tags
					in the sidebar. Specify multiple tags by separating with a comma ','.", 'elegant-grunge') ?>
				</small>
			</td>
		</tr>
		
		<tr>
			<th style="text-align:left;" colspan="2"><?php _e('Related posts title:', 'elegant-grunge') ?></th>
			<td>
				<input type="text" class="text" name="related_title" value="<?php echo htmlspecialchars($relatedTitle); ?>" /><br />
				<small>
					<?php _e("If 'Page with custom sidebar' page template is selected, and one or more related tags are provided above, this will be the title
					above a list of related posts.", 'elegant-grunge') ?>
				</small>
			</td>
		</tr>
		
		<tr>
			<th style="text-align:left;" colspan="2"><?php _e('Extra sidebar content:', 'elegant-grunge') ?></th>
			<td>
				<textarea name="sidebar_content" style="width:300px; height:100px;"><?php echo htmlspecialchars(get_post_meta($post_id, 'sidebar_content', true)); ?></textarea><br/>
				<small>
					<?php _e("If 'Page with custom sidebar' page template is selected, this text/HTML will be displayed in the sidebar.", 'elegant-grunge') ?>
				</small>
			</td>
		</tr>
	</table>
	</div>
	</div>
	<?php
}

/**
 * Save setup from post/page
 */
function elegant_grunge_save_post($id) {
	if ( !isset($_REQUEST['eg_edit']) ) return;
	
	if ( isset($_REQUEST['frame_all_images']) && $_REQUEST['frame_all_images'] != '' ) {
		delete_post_meta($id, 'frame_all_images');
		add_post_meta($id, 'frame_all_images', $_REQUEST['frame_all_images']);
	}
	
	if ( isset($_REQUEST['related_tags']) ) {
		delete_post_meta($id, 'related_tags');
		if ( $_REQUEST['related_tags'] )
			add_post_meta($id, 'related_tags', stripcslashes($_REQUEST['related_tags']));
	}
	
	if ( isset($_REQUEST['related_title']) ) {
		delete_post_meta($id, 'related_title');
		if ( $_REQUEST['related_title'] )
			add_post_meta($id, 'related_title', stripcslashes($_REQUEST['related_title']));
	}
	
	if ( isset($_REQUEST['sidebar_content']) ) {
		delete_post_meta($id, 'sidebar_content');
		if ( $_REQUEST['sidebar_content'] )
			add_post_meta($id, 'sidebar_content', stripcslashes($_REQUEST['sidebar_content']));
	}
}

function elegant_grunge_setup_admin() {
	add_theme_page('Elegant Grunge Setup', 'Elegant Grunge', 8, __FILE__, 'elegant_grunge_admin');
}

function elegant_grunge_photoblog($args) {
	
	$thumbs = array();
	$count = get_option('elegant_grunge_photoblog_entries');
	
	$width = get_option('elegant_grunge_photoblog_width');
	$height = get_option('elegant_grunge_photoblog_height');
	
	global $post;
	
	$posts = get_posts('tag='.get_option('elegant_grunge_photoblog_tags').'&numberposts='.$count.(get_option('elegant_grunge_photoblog_order')=='random' ? '&orderby=rand' : ''));
	foreach ( $posts as $post ) {
		
		$thumb = the_thumbnail($width, $height, true);
		
		if ( $thumb ) {
			$thumbs[] = $thumb;
		}
		if ( count($thumbs) == $count ) break;
	}
	
	if ( count($thumbs) == 0 ) return;
	
	echo $args["before_widget"];
	if ( get_option('elegant_grunge_photoblog_heading') ) {
		echo $args["before_title"].htmlspecialchars(get_option('elegant_grunge_photoblog_heading')).$args["after_title"];
	}
	echo '<div>';
	foreach ( $thumbs as $thumb ) {
		echo $thumb;
	}
	echo '</div>';
	echo $args["after_widget"];
	
}

function elegant_grunge_photoblog_setup() {
	
	$options = array("elegant_grunge_photoblog_heading",
					   "elegant_grunge_photoblog_entries", 
					   "elegant_grunge_photoblog_order", 
						"elegant_grunge_photoblog_tags",
						"elegant_grunge_photoblog_width",
						"elegant_grunge_photoblog_height");
	
	foreach ( $options as $option ) {
		if ( isset($_REQUEST[$option]) ) {
			update_option($option, stripslashes($_REQUEST[$option]));
		}
	}
	
	?>
	<p>
	<label for="photoblog_heading"><?php _e('Heading:', 'elegant-grunge') ?></label>
	<input name="elegant_grunge_photoblog_heading" id="photoblog_heading" type="text" class="widefat text" value="<?php echo get_option('elegant_grunge_photoblog_heading') ?>" />
	</p>
	
	<p>
	<label for="photoblog_entries"><?php _e('Display:', 'elegant-grunge') ?></label>
	<input name="elegant_grunge_photoblog_entries" id="photoblog_entries" type="text" class="text" size="5" value="<?php echo get_option('elegant_grunge_photoblog_entries') ?>" /> <?php _e('entries', 'elegant-grunge')?><br/>
	</p>
	
	<p>
	<input type="radio" name="elegant_grunge_photoblog_order" id="photoblog_order_random" value="random" <?php echo ((get_option('elegant_grunge_photoblog_order')=='random') ? 'checked' : '') ?> />
	<label for="photoblog_order_random"><?php _e('Display random entries', 'elegant-grunge') ?></label><br />
	<input type="radio" name="elegant_grunge_photoblog_order" id="photoblog_order_recent" value="recent" <?php echo ((get_option('elegant_grunge_photoblog_order')=='recent') ? 'checked' : '') ?> />
	<label for="photoblog_order_recent"><?php _e('Display recent entries', 'elegant-grunge') ?></label><br />
	</p>
	
	<p>
	<label for="photoblog_tags"><?php _e('Tags to display:', 'elegant-grunge') ?></label>
	<input name="elegant_grunge_photoblog_tags" id="photoblog_tags" type="text" class="widefat text" value="<?php echo get_option('elegant_grunge_photoblog_tags') ?>" /><br/>
	<small><?php _e('Separate multiple tags with commas', 'elegant-grunge') ?></small>
	</p>
	
	<p>
	<label for="photoblog_width"><?php _e('Image width:', 'elegant-grunge') ?></label>
	<input name="elegant_grunge_photoblog_width" id="photoblog_width" type="text" class="text" size="5" value="<?php echo get_option('elegant_grunge_photoblog_width') ?>" />
	</p>
	
	<p>
	<label for="photoblog_height"><?php _e('Image height:', 'elegant-grunge') ?></label>
	<input name="elegant_grunge_photoblog_height" id="photoblog_height" type="text" class="text" size="5" value="<?php echo get_option('elegant_grunge_photoblog_height') ?>" />
	</p>
	<?php
}


function elegant_grunge_widget_init() {
	register_sidebar_widget('Photoblog', 'elegant_grunge_photoblog');
	register_widget_control('Photoblog', 'elegant_grunge_photoblog_setup');
	add_option('elegant_grunge_photoblog_tags', 'photoblog');
	add_option('elegant_grunge_photoblog_heading', 'Photoblog');
	add_option('elegant_grunge_photoblog_entries', '4');
	add_option('elegant_grunge_photoblog_order', 'random');
	add_option('elegant_grunge_photoblog_width', '100');
	add_option('elegant_grunge_photoblog_height', '70');
}

load_theme_textdomain('elegant-grunge');

add_filter( 'the_content', 'elegant_grunge_filter', 15 );
add_option( 'header_image', '' );
add_option( 'show_rss', true );
add_option( 'show_author', false );
add_option( 'copyright', 'Copyright &copy; '.strftime('%Y').' '.get_bloginfo( 'name' ) );
add_option( 'frame_all_images', true );
add_option( 'frame_class_skip', 'noframe,wp-smiley' );
add_option( 'create_photoblog_thumbnails', false );
add_option( 'photoblog_thumb_count', 30 );
add_option( 'photoblog_thumb_width', '' );
add_option( 'photoblog_thumb_height', '' );
add_option( 'photoblog_lightbox', function_exists('autoexpand_rel_wlightbox') );
add_option( 'photoblog_frames', false );
add_option( 'page_setup', 'right-sidebar' );
add_action( 'admin_menu', 'elegant_grunge_setup_admin' );

add_action( 'edit_form_advanced', 'elegant_grunge_post_options' );
add_action( 'edit_page_form', 'elegant_grunge_page_options' );
add_action( 'save_post', 'elegant_grunge_save_post' );
add_action( 'widgets_init', 'elegant_grunge_widget_init' );

/**
 * Debug
 */
function EGDEBUG($text) {
	global $__EGDEBUG_FD;
	if ( !$__EGDEBUG_FD ) {
		$__EGDEBUG_FD = fopen("eg_debug.txt", "a");
	}
	
	fwrite($__EGDEBUG_FD, $text."\n");
}

?>
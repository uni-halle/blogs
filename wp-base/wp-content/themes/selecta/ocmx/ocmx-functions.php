<?php
// The OCMX custom options form
function ocmx_header(){
?>   
		<script src="<?php bloginfo('template_directory'); ?>/ocmx/scripts/jquery.js"></script>
    	<script src="<?php bloginfo('template_directory'); ?>/ocmx/scripts/ocmx_jquery.js"></script>
        <script src="<?php bloginfo('template_directory'); ?>/ocmx/scripts/multifile.js"></script>
	    <link href="<?php bloginfo('template_directory'); ?>/ocmx/ocmx_styles.css" rel="stylesheet" type="text/css" />
		<div id="template-directory" style="display: none;"><?php bloginfo("template_directory"); ?></div>
		<div id="wp-url" style="display: none;"><?php bloginfo("wpurl"); ?></div>
<?php
};
global $themename, $options;

if($_POST["save_options"]) :
		global $wpdb;
		//Clear our preset options, because we're gonna add news ones.
		wp_cache_flush(); 
		if($_POST["general_options"]) :
			if(isset($_COOKIE["ocmx_theme_style"])){setcookie ("ocmx_theme_style", "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN);}
			echo $_COOKIE["ocmx_theme_style"];	
			$clear_menu = $wpdb->query("DELETE FROM $wpdb->options
			WHERE `option_name` LIKE 'ocmx_main_%'
			OR `option_name` LIKE 'ocmx_menu_page%'
			OR `option_name` = 'ocmx_gallery_page'
			OR `option_name` = 'ocmx_archives_page'");
		endif;
		while (list($key,$value) = each($_POST)){
			if (substr($key, 0, 4) == "ocmx") :
				wp_cache_flush(); 
				$clear_options = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '".$key."'");
				if(!get_option($key)):					
					add_option($key, $value);						
				else :						
					update_option($key, $value);
				endif;
				
			endif;					
		}
		update_option("posts_per_page", get_option("ocmx_home_page_posts"));
		if(!$_POST["ocmx_gallery_update"]) :
			while (list($key,$value) = each($_FILES)){
				$image_name = strtolower($_FILES[$key]["name"]);
				if($image_name !== "") :
					$final_upload = ABSPATH."wp-content/uploads/".$image_name;
					if(move_uploaded_file($_FILES[$key]["tmp_name"], $final_upload) === true) :
						$test = move_uploaded_file($_FILES[$key]["tmp_name"], $final_upload);
					else :
						$test = "0";
					endif;
					if($test !== "0") :
						if(!get_option($key)):
							add_option($key, $image_name);						
						else :						
							update_option($key, $image_name);
						endif;
					endif;
				endif;
			}
	endif;
endif;
function ocmx_save_resample($upload_image, $new_image, $mime_type, $resize_type, $resize_height, $resize_width, $resize_percent, $image_width, $image_height)
	{
		global $upload_image, $new_image, $mime_type, $resize_type, $resize_height, $resize_width, $resize_percent;
		
		if($resize_type == "w") :
			if($resize_width !== "0") :
				$new_width = $resize_width;
			else :
				$new_width = $resize_height;
			endif;
			$new_height = ($image_height*($resize_width/$image_width));
		elseif($resize_type == "h") :
			if($resize_height !== "0") :
				$new_height = $resize_height;
			else :
				$new_height = $resize_width;
			endif;
			$new_width = ($image_width*($resize_height/$image_height));
		elseif($resize_type = "p") :
			/*  Set Resize Percentage */
			$resize_percent = ($resize_percent);
			$new_width = ($image_width*$resize_percent);
			$new_height = ($image_height*$resize_percent);
		endif;
		
		// Creat Canvas
		$canvas = imagecreatetruecolor($new_width, $new_height);
		
		// Resample
		if(imagecopyresampled($canvas, $new_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height) === true) :
				// Save		
			if($mime_type == "gif") :
				imagegif($canvas, $upload_image, 100);
			elseif($mime_type == "jpg") :	
				imagejpeg($canvas, $upload_image, 100);
			elseif($mime_type == "png") :
				imagepng($canvas, $upload_image);
			endif;				
		endif;
		
	}
function ocmx_commentmeta_update($cid)//, $comment_twitter, $comment_subscribe, $comment_author_email
	{
		global $wpdb;
		$commentId = (int) $cid;
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";
		if($_POST['twitter'] == "undefined" && $_POST['twitter'] == "Twitter Name") :
			$use_twitter = "";
		else :
			$use_twitter = $_POST['twitter'];
		endif;
		
		if($_POST['email_subscribe'] == "true") :
			$subscribe_me = 1;
		else :
			$subscribe_me = 0;
		endif;
		
		$meta_update = $wpdb->query
			($wpdb->prepare
				("INSERT INTO $comment_table
					(commentId, twitter, email_subscribe)
				VALUES
					(%d, %s, %s);", 
        		$cid, $use_twitter, $subscribe_me)
			);
		ocmx_comment_email($cid);
	}	
function ocmx_comment_email($cid)//, $comment_twitter, $comment_subscribe, $comment_author_email
	{
		global $wpdb;
		$commentId = (int) $cid;
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";
		
		$comment = $wpdb->get_row("SELECT $wpdb->comments.*,  $comment_table.* FROM $wpdb->comments INNER JOIN $comment_table ON $wpdb->comments.comment_ID = $comment_table.commentId WHERE $wpdb->comments.comment_ID='".$cid."'");
		$post_details = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='".$comment->comment_post_ID."' LIMIT 1");		
		
		$fetch_subscribers = $wpdb->get_results("SELECT $wpdb->comments.*,  $comment_table.*
		FROM $wpdb->comments INNER JOIN $comment_table
		ON $wpdb->comments.comment_ID = $comment_table.commentId
		WHERE $wpdb->comments.comment_post_ID = '".$comment->comment_post_ID."'
		AND $comment_table.email_subscribe = '1'
		GROUP BY $wpdb->comments.comment_author_email");
		
		foreach($fetch_subscribers as $subscriber) :
			$to = $subscriber->comment_author_email;
			$headers  = "From: \"".get_bloginfo("name")."\" Comments";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
			$body = "<style>body{margin: 20px;font-size: 9pt;font-family: Arial, Helvetica, sans-serif;color: ##333333;background-color: ##ffffff;}.articles_item{padding-bottom: 10px; border-bottom: 1px solid black; margin-bottom: 10px;}</style>";
			$body .=  "<h4>".$post->post_title."</h4>";
			$body .=  "<p><strong>".$comment->comment_author."</strong> has commented on <strong>". $post_details->post_title."</strong></p>";
			$body .=  "<p><strong>Link:</strong> <a href=\"".$comment->comment_author."\">".$comment->comment_author_url."</a></p>";
			$body .=  "<p><strong>Twitter Name:</strong> <a href=\"http://twitter.com/".$comment->twitter."\">".$comment->twitter."</a></p>";
			$body .=  "<p><strong>Comment:</strong></p>";
			$body .=  "<div class=\"articles_item\">";
			$body .=  "<p>".$comment->comment_content."</p>";
			$body .=  "</div>";  
			$body .=  "<p>Go go straight to the post <a href=\"".get_permalink($comment->comment_post_ID)."#comments\">".get_permalink($comment->comment_post_ID)."</a>.</p>";
			wp_mail($to, $subject, $body, $headers);
		endforeach;
	}
?>
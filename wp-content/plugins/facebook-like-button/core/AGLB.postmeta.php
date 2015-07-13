<?php
	
namespace AGPressGraph;


/**
 * postMeta class.
 * @since 6.0
 */
 
class postMeta{
	public function postMetaBox(){
		$AGPressGraph_like_show_in = get_option('AGPressGraph_like_show_in');
		if(is_array($AGPressGraph_like_show_in)){
			foreach($AGPressGraph_like_show_in as $postType){
				add_meta_box( "AGPressGraph_like_meta_settings", "Like Button Settings",
				array(__CLASS__, "postMetaBoxLayout"), $postType, "side" );
			}
		}
		
	}
	
	public function postMetaBoxLayout(){
		if(isset($_GET["post"]) && !empty($_GET["post"])){
			$AGLBIsDisabled = get_post_meta($_GET["post"],"AGLBIsDisabled", true);
			$AGLBCustomOGImage = get_post_meta($_GET["post"],"AGLBCustomOGImage", true);
		}
		?>
			<table>
				<tr>
					<td>Disable Like Button: <input type="checkbox" name="AGLBIsDisabled" value="1" <?php checked(1, $AGLBIsDisabled); ?>/></td>
				</tr>
				<tr>
					<td><img src='<?php echo ($AGLBCustomOGImage != null && !empty($AGLBCustomOGImage) ? $AGLBCustomOGImage : ""); ?>' id="AGLBCustomOGImage" style="display:<?php echo ($AGLBCustomOGImage != null && !empty($AGLBCustomOGImage) ? "block" : "none"); ?>; width:100%;" class="attachment-post-thumbnail" /><br /><a title="Set Custom og Image" id="AGLBPostCustomOG" class="thickbox"> Set Custom og Image</a> <span class="description">This image will be used when post is shared on Facebook.</span><input type="hidden" value="<?php echo $AGLBCustomOGImage; ?>" name="AGLBCustomOGImage" /></td>
				</tr>
			</table>
			<p></p>
		<?php
	}
	
	public function savePostCustomMeta($postID, $post, $updated){
		if(isset($_POST["AGLBCustomOGImage"]) && !empty($_POST["AGLBCustomOGImage"])){
			if($updated)
				update_post_meta($postID, "AGLBCustomOGImage" , $_POST["AGLBCustomOGImage"]);
			else
				add_post_meta($postID, "AGLBCustomOGImage" , $_POST["AGLBCustomOGImage"]);
		}
		
		if(isset($_POST["AGLBIsDisabled"]) && !empty($_POST["AGLBIsDisabled"])){
			if($updated)
				update_post_meta($postID, "AGLBIsDisabled" , 1);
			else
				add_post_meta($postID, "AGLBIsDisabled" , 1);
		}else{
			if($updated)
				update_post_meta($postID, "AGLBIsDisabled" , 0);
		}
	}
}
	
?>
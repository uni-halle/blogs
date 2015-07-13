<?php
	
namespace AGPressGraph;


/**
 * manipulator class.
 * @since 6.0
 */
class manipulator{
	
	
	/**
	 * insertTheButtonForContent function.
	 * 
	 * @access public
	 * @param mixed $content
	 * @description Appends the facebook like button to the content according to the user settings
	 */
	public function insertTheButtonForContent($content){
		$theButtons = new theButtons();
		$buttonPosition = get_option("AGPressGraph_like_pos", "after");
		$thePostID = get_the_ID();
		if(!is_null($thePostID) && $thePostID != -1){
			$isDisabled = get_post_meta($thePostID, "AGLBIsDisabled", true);
			if(!(bool)$isDisabled){
				switch($buttonPosition){
					case "after":
						$content = $content . "" . $theButtons->buttonWithOptions(get_permalink($thePostID), true, false);
						break;
					case "before":
					    $content = $content . "" . $theButtons->buttonWithOptions(get_permalink($thePostID), true, false);
					    break;
					case "baf":
						$button = $theButtons->buttonWithOptions(get_permalink($thePostID), true, false);
						$content = $button . "" . $content . "" . $button;
						break;
				}
				
				return $content;
			}else{
				return $content;
			}
		}else{
			return $content;
		}
	}
	
	
	/**
	 * getTheSDK function.
	 * 
	 * @access public
	 * @description outpus the SDK string with App ID
	 */
	public function getTheSDK(){
		include_once(plugin_dir_path(__FILE__) . "layout/facebookSDK.php");
	}
	
	
	/**
	 * getJSSDK function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getJSSDK(){
		include_once(plugin_dir_path(__FILE__) . "layout/facebookJSSDK.php");
	}
	
	
	/**
	 * openGraphData function.
	 * 
	 * @access public
	 * @return void
	 */
	public function openGraphData(){
		
		
		?>
			<script>
				jQuery(document).ready(function($){
					var script = document.createElement( 'script' );
					$(script).text("<?php manipulator::getJSSDK(); ?>");

					$("body").prepend(script);
				});
			</script>
			<!-- PressGraph Site Meta Tags -->
			<meta property="og:site_name" content="<?php echo get_bloginfo("name"); ?>"/>
			<meta property="fb:admins" content="<?php echo get_option("AGPressGraph_like_admin_id", ""); ?>" />
			<meta property="fb:app_id" content="<?php echo get_option("AGPressGraph_like_appid", ""); ?>" />
			<!-- PressGraph Site Meta Tags -->

    	<?php			
	
		if(is_singular() || is_page()){
			$postType = get_post_type(get_the_ID());
			$supportedPostTypes = get_option("AGPressGraph_like_show_in", false);
			if(!$supportedPostTypes){
				$supportedPostTypes = array();
			}
			if( in_array($postType, $supportedPostTypes)){
			$metaImage = get_post_meta(get_the_ID(), "AGLBCustomOGImage", true);
			$defaultImage  = get_option("AGPressGraph_like_dimage", false);
			$postThumbnail = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
			$ogImage = (!empty($metaImage) ? $metaImage : ($postThumbnail !== false ? $postThumbnail : $defaultImage ));
			
			?>
				<!-- PressGraph Post Meta Tags -->
				<meta property="og:title" content="<?php echo the_title(); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php echo get_permalink(get_the_id()); ?>" />
				<meta property="og:image" content="<?php echo $ogImage; ?>" />
				<meta property="og:description" content="<?php echo strip_tags(the_excerpt()); ?>" />
				<!-- PressGraph Post Meta Tags -->
	
			<?php			
			}
		
		}		
	}
	
	
	/**
	 * addOpenGraphHTMLAttributes function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addOpenGraphHTMLAttributes(){
		return 'xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"';
	}
			
}
?>
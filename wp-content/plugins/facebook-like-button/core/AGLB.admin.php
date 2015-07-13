<?php

namespace AGPressGraph;


/**
 * adminManager class.
 * @since 6.0
 */
 
class adminManager{
	public $coreDirPath;
	public $page_sections = array();

	public function __construct(){
		$coreDirPath = plugin_dir_path(__FILE__);
	}
	
	
	/**
	 * registerAdminMenu function.
	 * 
	 * @access public
	 * @return void
	 */
	public function registerAdminMenu(){
	
		$likeMenuPage = add_menu_page( "PressGraph Settings", "PressGraph", "manage_options", "AGPressGraph", array(__CLASS__, "likeSettingsPageLayout"), plugin_dir_url(__FILE__) . "layout/img/pressGraphLogo.png");
				
		add_action("admin_footer-$likeMenuPage", array(__CLASS__, 'AGPressGraph_like_footer_script'));
		add_action("admin_init",array(__CLASS__, 'registerSettingsMetaBoxes'));
		
		
		if(isset($_GET["updateFromNotice"]) && isset($_GET["page"]) && 
				 $_GET["page"] == "AGPressGraph" && $_GET["updateFromNotice"]  == "true"){
			update_option("AGPressGraph_like_didUpdateOptions", true);
		} 
		
	}
	
	
	/**
	 * likeSettingsFieldsAndSections function.
	 * 
	 * @access public
	 * @return void
	 */
	public function likeSettingsFieldsAndSections(){
		add_settings_section('AGPressGraphLike_general', 'General Settings', 
		array(__CLASS__, "likeOptionsGeneralSectionCallback"), 'AGPressGraphGeneral');

		add_settings_section('AGPressGraphLike_layout', 'Layout Settings', 
		array(__CLASS__, "likeOptionsLayoutSectionCallback"), 'AGPressGraphLayout');

		update_option("AGPressGraphMigrated", 1);



		//Assign General Settings Fields

		add_settings_field( "AGPressGraph_like_appid", "App ID", 
		array(__CLASS__, "AGPressGraph_like_appid_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );

		add_settings_field( "AGPressGraph_like_admin_id", "Admin ID", 
		array(__CLASS__, "AGPressGraph_like_admin_id_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );

		add_settings_field( "AGPressGraph_like_pos", "Position", 
		array(__CLASS__, "AGPressGraph_like_position_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );

		add_settings_field( "AGPressGraph_like_align", "Alignment", 
		array(__CLASS__, "AGPressGraph_like_alignment_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );


		add_settings_field( "AGPressGraph_like_show_in", "Show In", 
		array(__CLASS__, "AGPressGraph_like_show_in_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );


		add_settings_field( "AGPressGraph_like_dimage", "Default Image", 
		array(__CLASS__, "AGPressGraph_like_dimage_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );

		//add_settings_field( "AGPressGraph_like_locale", "Button Language", 
		//array(__CLASS__, "AGPressGraph_like_locale_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );
		
		add_settings_field( "AGPressGraph_like_kid_restricted", "Kid Restricted Site?", 
		array(__CLASS__, "AGPressGraph_like_kid_restricted_callback"), "AGPressGraphGeneral", "AGPressGraphLike_general" );



		//Assign Layout Settings Fields

		add_settings_field( "AGPressGraph_like_layout", "Button Layout", 
		array(__CLASS__, "AGPressGraph_like_layout_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );
		
		add_settings_field( "AGPressGraph_like_color", "Color Scheme", 
		array(__CLASS__, "AGPressGraph_like_color_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );

		add_settings_field( "AGPressGraph_like_face", "Show Faces?", 
		array(__CLASS__, "AGPressGraph_like_show_faces_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );

		add_settings_field( "AGPressGraph_like_verb", "Action Type", 
		array(__CLASS__, "AGPressGraph_like_verb_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );

		add_settings_field( "AGPressGraph_like_include_share", "Include Share Button", 
		array(__CLASS__, "AGPressGraph_like_include_share_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );

		add_settings_field( "AGPressGraph_like_height", "Container Height", 
		array(__CLASS__, "AGPressGraph_like_height_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );

		add_settings_field( "AGPressGraph_like_width", "Container Width", 
		array(__CLASS__, "AGPressGraph_like_width_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );
		
		add_settings_field( "AGPressGraph_like_css", "Custom Container Class", 
		array(__CLASS__, "AGPressGraph_like_css_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout" );
		
		add_settings_field("AGPressGraph_like_didUpdateOptions", "", 
		array(__CLASS__, "AGPressGraph_like_didUpdateOptions_callback"), "AGPressGraphLayout", "AGPressGraphLike_layout");
	}

	
	/**
	 * likeSettingsPageLayout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function likeSettingsPageLayout(){
		include_once($coreDirPath . "layout/likeButtonSettingsPage.php");
	}
	
	/**
	 * likeOptionsGeneralSectionCallback function.
	 * 
	 * @access public
	 * @param mixed $option
	 * @return void
	 */
	public function likeOptionsGeneralSectionCallback($option){}
	
	/**
	 * likeOptionsLayoutSectionCallback function.
	 * 
	 * @access public
	 * @param mixed $option
	 * @return void
	 */
	public function likeOptionsLayoutSectionCallback($option){}
	
	/**
	 * AGPressGraph_like_appid_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_appid_callback(){
		$AGPressGraph_like_appid = get_option('AGPressGraph_like_appid');
?>
			 <input name="AGPressGraph_like_appid" value="<?php echo $AGPressGraph_like_appid; ?>" />

		     <span class="description"><a href="http://developers.facebook.com/setup/" target="_blank" title="Register Your Site on Facebook">Don’t have one?</a></span>
		<?php

	}

	/**
	 * AGPressGraph_like_type_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_type_callback(){
		$AGPressGraph_like_type = get_option('AGPressGraph_like_type');
?>
			 <select name="AGPressGraph_like_type">
				 <option value="xfbml"  <?php selected( "xfbml",$AGPressGraph_like_type); ?>>XFBML</option>
				 <option value="iframe" <?php selected( "iframe",$AGPressGraph_like_type); ?>>iFrame</option>
			 </select>

		     <span class="description"></span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_position_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_position_callback(){
		$AGPressGraph_like_pos = get_option('AGPressGraph_like_pos');
?>
			 <select name="AGPressGraph_like_pos">
				 <option value="after" <?php selected( "after", $AGPressGraph_like_pos); ?>>After Content</option>
				 <option value="before" <?php selected( "before", $AGPressGraph_like_pos); ?>>Before Content</option>
				 <option value="baf" <?php selected( "baf", $AGPressGraph_like_pos); ?>>Before and After Content</option>
				 <option value="man" <?php selected( "man", $AGPressGraph_like_pos); ?>>Manual</option>
			 </select>

		     <span class="description"></span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_alignment_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_alignment_callback(){
		$AGPressGraph_like_alignment = get_option('AGPressGraph_like_align');
?>
			 <select name="AGPressGraph_like_align">
				 <option value="right" <?php selected( "right", $AGPressGraph_like_alignment); ?>>Right</option>
				 <option value="left" <?php selected( "left", $AGPressGraph_like_alignment); ?>>Left</option>
				 <option value="center" <?php selected( "center", $AGPressGraph_like_alignment); ?>>Center</option>
			 </select>

		     <span class="description"></span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_show_in_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_show_in_callback(){

		$all_post_types = get_post_types();

		$AGPressGraph_like_show_in = get_option('AGPressGraph_like_show_in');
?>
			 <select name="AGPressGraph_like_show_in[]" multiple="multiple">
			 	<?php
		foreach($all_post_types as $type){
			?><option value="<?php echo $type; ?>" <?php echo (is_array($AGPressGraph_like_show_in)  ? (in_array($type, $AGPressGraph_like_show_in) ? "selected" : "") : "" ); ?>><?php echo $type; ?></option><?php
		}
?>
			 </select>

		     <span class="description">Post Types where the like button is allowed to show.</span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_admin_id_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_admin_id_callback(){

		$AGPressGraph_like_admin_id = get_option('AGPressGraph_like_admin_id');

?>
			 <input name="AGPressGraph_like_admin_id" value="<?php echo $AGPressGraph_like_admin_id; ?>" />
		     <span class="description">User ID or Username who has access to insights.</span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_dimage_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_dimage_callback(){
		$AGPressGraph_like_dimage = get_option('AGPressGraph_like_dimage', null);

?>
			 <?php echo ($AGPressGraph_like_dimage != null ? "<img id='AGLBOGImage' style='width:200px;' class='thumbnail' src='$AGPressGraph_like_dimage'/>" : "<img id='AGLBOGImage' style='display:none;width:200px;' class='thumbnail' src=''/>")?>
			 <input type="hidden" name="AGPressGraph_like_dimage" value="<?php echo $AGPressGraph_like_dimage; ?>"/>
		     <span class="description"><a href="#" id="AGLBSelectDImage">Select/Upload Image</a> <i>Default image that will show on Facebook if article/post doesn't have thumbnail, leave empty to use post thumbnail.</i></span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_locale_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_locale_callback(){
		$AGPressGraph_like_locale = get_option('AGPressGraph_like_locale');
		$locales = adminManager::getFBLocales();
		?><select name="AGPressGraph_like_locale"><?php
		foreach($locales as $locale){
?>
				<option value="<?php echo $locale; ?>" <?php selected($locale, $AGPressGraph_like_locale)?>><?php echo $locale; ?></option>
			<?php
		}
		?></select><?php

	}
		
	/**
	 * AGPressGraph_like_kid_restricted_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_kid_restricted_callback(){
		$AGPressGraph_like_kid_restricted = get_option('AGPressGraph_like_kid_restricted');
		?><input type="checkbox" name="AGPressGraph_like_kid_restricted" value="true" <?php checked("true", $AGPressGraph_like_kid_restricted) ?> /><?php
	}
	
	/**
	 * AGPressGraph_like_layout_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_layout_callback(){
		$AGPressGraph_like_layout = get_option('AGPressGraph_like_type');
		?><select name="AGPressGraph_like_type">
			<option value="standard" <?php selected("standard", $AGPressGraph_like_layout)?>>Standard</option>
			<option value="box_count" <?php selected("box_count", $AGPressGraph_like_layout)?>>Box Count</option>
			<option value="button_count" <?php selected("button_count", $AGPressGraph_like_layout)?>>Button Count</option>
			<option value="button" <?php selected("button", $AGPressGraph_like_layout)?>>Button</option>
		</select><?php
	}
	
	
	/**
	 * AGPressGraph_like_color_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_color_callback(){
		$AGPressGraph_like_color = get_option('AGPressGraph_like_color');
		?><select name="AGPressGraph_like_color">
			<option value="light" <?php selected("light", $AGPressGraph_like_color)?>>Light</option>
			<option value="dark" <?php selected("dark", $AGPressGraph_like_color)?>>Dark</option>
		</select><?php
	}

	
	/**
	 * AGPressGraph_like_show_faces_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_show_faces_callback(){
		$AGPressGraph_like_layout = get_option('AGPressGraph_like_face');
		?><input type="checkbox" name="AGPressGraph_like_face" value="true" <?php checked("true", $AGPressGraph_like_layout) ?> /><?php
	}
	
	/**
	 * AGPressGraph_like_verb_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_verb_callback(){
		$AGPressGraph_like_verb = get_option('AGPressGraph_like_verb');
		?><select name="AGPressGraph_like_verb">
			<option value="like" <?php selected("like", $AGPressGraph_like_verb)?>>like</option>
			<option value="recommend" <?php selected("recommend", $AGPressGraph_like_verb)?>>recommend</option>
		</select><?php
	}
	

	/**
	 * AGPressGraph_like_include_share_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_include_share_callback(){
		$AGPressGraph_like_include_share = get_option('AGPressGraph_like_include_share');
		?>	<input type="checkbox" name="AGPressGraph_like_include_share" value="true" <?php checked("true", $AGPressGraph_like_include_share) ?> /><?php
	}
	
	/**
	 * AGPressGraph_like_height_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_height_callback(){
		$AGPressGraph_like_height = get_option('AGPressGraph_like_height', 40);
?>
		<input type="number" name="AGPressGraph_like_height" value="<?php echo $AGPressGraph_like_height; ?>" />
		<span class="description">Container height in px.</span>
		<?php
	}
	
	/**
	 * AGPressGraph_like_width_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_width_callback(){
		$AGPressGraph_like_width = get_option('AGPressGraph_like_width');
?>
		<input type="number" name="AGPressGraph_like_width" value="<?php echo $AGPressGraph_like_width; ?>" />
		<span class="description">Container width in px.</span>
		<?php
	}
	
	
	/**
	 * AGPressGraph_like_css_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_css_callback(){
		$AGPressGraph_like_css = get_option('AGPressGraph_like_css');
?>
		<input type="text" name="AGPressGraph_like_css" value="<?php echo $AGPressGraph_like_css; ?>" />
		<span class="description">Custom CSS class for like button wrapping div.</span>
		<?php
	}
	
	
	/**
	 * AGPressGraph_like_didUpdateOptions_callback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_didUpdateOptions_callback(){
		?><input name="AGPressGraph_like_didUpdateOptions" value="true" type="hidden" /><?php
	}
	
	/**
	 * AGPressGraphLikeGeneralSettingsMetaBoxCallback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraphLikeGeneralSettingsMetaBoxCallback(){
		do_settings_sections("AGPressGraphGeneral");
	}
	
	/**
	 * AGPressGraphLikeLayoutSettingsMetaBoxCallback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraphLikeLayoutSettingsMetaBoxCallback(){
		do_settings_sections("AGPressGraphLayout");
	}
	
	/**
	 * AGPressGraphLikeAboutMetaBoxCallback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraphLikeAboutMetaBoxCallback(){
		?>
			<p>This plugin is provided by <a href="http://ahmedgeek.com?ref=pluginSettingsPage" target="_blank">Ahmed The Geek</a> for free, and will remain free forever.</p><hr />
			
			<p>Check my other plugins:</p><b><a href="https://wordpress.org/plugins/pace-pro-epic-loading-progress-bar/" target="_blank">PACE Pro</a></b><hr />
			<p>Icon Is Designed By Anisha Varghese on <a href="https://thenounproject.com/search/?q=network&i=14181">thenounproject</a></p>
			<p>Plugin Banner Background: <a href="http://www.freepik.com/free-vector/abstract-connected-dots-background_719714.htm">Designed by Freepik</a></p>
		<?php
	}
	
	/**
	 * AGPressGraphLikeLivePreviewMetaBoxCallback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraphLikeLivePreviewMetaBoxCallback(){
		  global $AGFBButtons;
	      include_once($coreDirPath . "layout/facebookSDK.php");
	      
	      
	      
	      ?>
	      
	      	<div id="AGLBTheButton">
		      	
		      	<?php $AGFBButtons = new theButtons(); $AGFBButtons->buttonWithOptions("http://ahmedgeek.com");?>
		      	
	      	</div>
	      	<br />
	      	
	      	<div style="float:right;"><?php submit_button( esc_attr( "Update Settings" ), 'primary', 'submit', false ); ?></div>
	      	<br />
	      	<br />
	      <?php
	}
	
	/**
	 * registerSettingsMetaBoxes function.
	 * 
	 * @access public
	 * @return void
	 */
	public function registerSettingsMetaBoxes(){
		wp_enqueue_script('postbox');

		add_meta_box( "AGPressGraph_live_box", "Live Preview",
			array(__CLASS__, "AGPressGraphLikeLivePreviewMetaBoxCallback"), "AGPressGraph", "side" );

		add_meta_box( "AGPressGraph_layout_about", "About Developer",
			array(__CLASS__, "AGPressGraphLikeAboutMetaBoxCallback"), "AGPressGraph", "side" );

		add_meta_box( "AGPressGraph_general_box", "General Settings",
			array(__CLASS__, "AGPressGraphLikeGeneralSettingsMetaBoxCallback"), "AGPressGraph", "normal" );

		add_meta_box( "AGPressGraph_layout_box", "Layout Settings",
			array(__CLASS__, "AGPressGraphLikeLayoutSettingsMetaBoxCallback"), "AGPressGraph", "normal" );


		add_meta_box( "AGPressGraph_layout_box", "Layout Settings",
			array(__CLASS__, "AGPressGraphLikeLayoutSettingsMetaBoxCallback"), "AGPressGraph", "normal" );

	}

	
	/**
	 * AGPressGraph_like_footer_script function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_footer_script() {
		echo "<script>jQuery(document).ready(function($){ postboxes.add_postbox_toggles(pagenow); $('#AGPressGraph_general_box .inside h3').remove(); $('#AGPressGraph_layout_box .inside h3').remove(); });</script>";
	}
	
	
	/**
	 * AGPressGraph_like_admin_scripts function.
	 * 
	 * @access public
	 * @return void
	 */
	public function AGPressGraph_like_admin_scripts(){
		wp_enqueue_script( 'livePreview', plugin_dir_url(__FILE__) . 'layout/js/livePreview.js', array( 'jquery' ) );
		wp_enqueue_script('thickbox');
		wp_enqueue_media();
	}
	
	
	/**
	 * getFBLocales function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getFBLocales() {

		$facebookLocales = wp_remote_get("http://www.facebook.com/translations/FacebookLocales.xml");
		$locales = array();

		if(is_array($facebookLocales)) {
			preg_match_all('/<locale>\s*<englishName>([^<]+)<\/englishName>\s*<codes>\s*<code>\s*<standard>.+?<representation>([^<]+)<\/representation>/s', utf8_decode($facebookLocales['body']), $localesArray, PREG_PATTERN_ORDER);
			foreach ($localesArray[1] as $i => $englishName) {
				$locales[$localesArray[2][$i]] = $englishName;
			}
		}

		if ($locales == array()) {
			// something went wrong, fall back to default locale "en_US"
			$locales['default'] = "Default";
		}

		return $locales;
	}
	
	
	/**
	 * upgradeNotice function.
	 * 
	 * @access public
	 * @return void
	 */
	public function upgradeNotice(){
		?>
	    <div class="update-nag">
	        <p><b>PressGraph <i>(Formerly Facebook Like Button)</i>:</b> been updated to version 6.0, and that requires you to update the plugin <a href="<?php echo admin_url('admin.php?page=AGPressGraph&updateFromNotice=true'); ?>">settings</a>.</p>
	    </div>
	    <?php
	}

}

?>
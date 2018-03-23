<?php
if( !defined('ABSPATH') ){ exit();}
add_action('admin_menu', 'xyz_smap_menu');

function xyz_smap_add_admin_scripts()
{
	wp_enqueue_script('jquery');
	wp_register_script( 'xyz_notice_script_smap', plugins_url('social-media-auto-publish/js/notice.js') );
	wp_enqueue_script( 'xyz_notice_script_smap' );
	
	wp_register_style('xyz_smap_style', plugins_url('social-media-auto-publish/css/style.css'));
	wp_enqueue_style('xyz_smap_style');
}

add_action("admin_enqueue_scripts","xyz_smap_add_admin_scripts");


function xyz_smap_menu()
{
	add_menu_page('Social Media Auto Publish - Manage settings', 'Social Media Auto Publish', 'manage_options', 'social-media-auto-publish-settings', 'xyz_smap_settings',plugin_dir_url( XYZ_SMAP_PLUGIN_FILE ) . 'images/smap.png');
	$page=add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Manage settings', ' Settings', 'manage_options', 'social-media-auto-publish-settings' ,'xyz_smap_settings');
	add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Logs', 'Logs', 'manage_options', 'social-media-auto-publish-log' ,'xyz_smap_logs'); 
	add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - About', 'About', 'manage_options', 'social-media-auto-publish-about' ,'xyz_smap_about');
}


function xyz_smap_settings()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);	
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}



function xyz_smap_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function xyz_smap_logs()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/logs.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

add_action('wp_head', 'xyz_smap_insert_og_image_tag_for_fb');
function xyz_smap_insert_og_image_tag_for_fb(){

 	global $post;
 	if (empty($post))
 		$post=get_post();
 	if (!empty($post)){
	$postid= $post->ID;
	if(isset($postid ) && $postid>0)
	{
		$get_post_meta_insert_og=0;
		$get_post_meta_insert_og=get_post_meta($postid,"xyz_smap_insert_og",true); 
		if (($get_post_meta_insert_og==1)&&(strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false))
		{
			$attachmenturl=xyz_smap_getimage($postid, $post->post_content);
			if(!empty($attachmenturl))
			{
				echo '<meta property="og:image" content="'.$attachmenturl.'" />';
				update_post_meta($postid, "xyz_smap_insert_og", "0");
			}
		}
	}
}
}
?>
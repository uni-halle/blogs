<?php
/*
Plugin Name: Accessible Video Library
Plugin URI: http://www.joedolson.com/accessible-video-library/
Description: Accessible video library manager. Write transcripts and upload captions. 
Author: Joseph C Dolson
Author URI: http://www.joedolson.com
Version: 1.1.1
*/

/*  Copyright 2013-2015  Joe Dolson (email : joe@joedolson.com) */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$avl_version = '1.1.1';
// Filters
add_filter( 'post_updated_messages', 'avl_posttypes_messages');

// Enable internationalisation
load_plugin_textdomain( 'avl-video',false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); 

// Actions
add_action( 'init', 'avl_taxonomies', 0);
add_action( 'init', 'avl_posttypes' );
add_action( 'admin_menu', 'avl_add_outer_box' );

register_activation_hook( __FILE__, 'avl_plugin_activated' );
function avl_plugin_activated() {
	$avl_fields = array( 
					'captions'=>array( 'label'=>__('Captions (SRT/DFXP)','accessible-video-library'),'input'=>'upload', 'format'=>'srt', 'type'=>'caption' ),
					//'audio_desc'=>array( 'Audio Description (mp3)','upload', 'audio' ),
					'mp4'=>array( 'label'=>__('Video (mp4)','accessible-video-library'),'input'=>'upload', 'format'=>'mp4', 'type'=>'video' ),
					'ogv'=>array( 'label'=>__('Video (ogv)','accessible-video-library'),'input'=>'upload', 'format'=>'ogv', 'type'=>'video' ),
					'external'=>array( 'label'=>__('YouTube Video URL','accessible-video-library'),'input'=>'text', 'format'=>'youtube', 'type'=>'video' ),
					//'config'=>array( 'Configuration','text','video' )
				);
	add_option( 'avl_fields', $avl_fields );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'avl_plugin_activated' );
function avl_plugin_deactivated() {
	flush_rewrite_rules();
}

add_action( 'plugins_loaded', 'avl_update_check' );

function avl_update_check() {
	global $avl_version;
	if ( version_compare( $avl_version, '1.0.4', '<') ) {
		$posts = get_posts( array( 'post_type' => 'avl-video' ) );
		foreach ( $posts as $post ) {
			if ( get_post_field( 'post_content', $post->ID, 'raw' ) == '' ) {
				add_post_meta( $post->ID, '_notranscript', 'true' );
			}
		}
	}
	update_option( 'avl_version', $avl_version );
}

// Add the administrative settings to the "Settings" menu.
function avl_add_support_page() {
    if ( function_exists( 'add_submenu_page' ) ) {
		$submenu_page = add_submenu_page( 'edit.php?post_type=avl-video', __('Accessible Video Library > Help & Settings','accessible-video-library'), 'Video Help/Settings', 'edit_posts', 'avl-help', 'avl_support_page' );
		add_action( 'admin_head-'. $submenu_page, 'avl_styles' );
    }
}
function avl_styles() {
	if ( $_GET['page'] == "avl-help" ) {
		echo '<link type="text/css" rel="stylesheet" href="'.plugins_url('avl-styles.css', __FILE__ ).'" />';
	}
}
add_action( 'admin_menu', 'avl_add_support_page' );

function avl_support_page() { ?>

<?php 
if ( isset( $_POST['avl_settings'] ) ) {
	$responsive = ( isset( $_POST['avl_responsive'] ) ) ? 'true' : 'false';
	update_option( 'avl_responsive', $responsive );
	$avl_default_caption = ( isset( $_POST['avl_default_caption'] ) ) ? $_POST['avl_default_caption'] : '';
	update_option( 'avl_default_caption', $avl_default_caption );
	echo "<div class='notice updated'><p>".__( 'Accessible Video Library Settings Updated', 'accessible-video-library' )."</p></div>";
}
?>
<div class="wrap avl-settings" id="accessible-video-library">
<h2><?php _e('Accessible Video Library','accessible-video-library'); ?></h2>
	<div id="avl_settings_page" class="postbox-container" style="width: 70%">
		<div class='metabox-holder'>
			<div class="settings meta-box-sortables">
				<div class="postbox" id="settings">
				<h3><?php _e('Help','accessible-video-library'); ?></h3>
					<div class="inside">
					<form action='<?php echo admin_url('edit.php?post_type=avl-video&page=avl-help'); ?>' method='post'>
						<p>
						<label for="avl_default_caption"><?php _e( 'Enable Subtitles by Default', 'accessible-video-library' ); ?></label>
						<select id="avl_default_caption" name="avl_default_caption">
						<?php
						$output = '';
						$fields = apply_filters( 'avl_add_custom_fields', get_option( 'avl_fields' ) );
						foreach ( $fields as $key => $field ) {
							if ( $field['type'] == 'subtitle' || $field['type'] == 'caption' ) {
								$label = esc_attr( $field['label'] );
								$value = esc_attr( $key );
								$selected = selected( $value, get_option( 'avl_default_caption' ), false );
								if ( $value ) {
									$output .= "<option value='$value'$selected>$label</option>";
								}
							}
						}
						echo $output;
						?>
						</select>
						</p>
						<p>
							<input type='checkbox' name='avl_responsive' id='avl_responsive' value='true'<?php echo ( get_option( 'avl_responsive' ) == 'true' ) ? ' checked="checked"' : ''; ?> /> <label for='avl_responsive'><?php _e( 'Responsive Videos','accessible-video-library' ); ?></label>
						</p>
						<p>
							<input type='submit' name='avl_settings' value='<?php _e( 'Update Settings', 'accessible-video-library' ); ?>' />
						</p>
					</form>
					<p>
					<?php 
					_e( 'You can customize some aspects of your videos using filters.','accessible-video-library' ); 
					_e( 'The use of videos from your video library is largely through shortcodes, documented below.','accessible-video-library' );
					?>
					</p>
					<h4><?php _e('Shortcodes','accessible-video-library' ); ?></h4>
					<p><input type='text' size="60" disabled value='[avl_video id="$video_id" width="$width" height="$height"]' /></p>
					<p>
					<?php
						_e( 'The only required field is the ID of the video you want to display. You can also enter a width and a height, and the video will be displayed with those dimensions.' );
					?>
					</p>
					<h4><?php _e( 'Custom Filters','accessible-video-library' ); ?></h4>
					<p><?php _e( 'Out of the box, Accessible Video Library supports captions, ogv and mp4 video formats, the addition of Spanish subtitles, and a YouTube video reference.'); _e( 'Using a custom WordPress filter, you can easily add support for additional video formats and additional subtitle languages.'); ?></p>
					<p><?php printf( __( 'Read more about <a href="%s">WordPress filters</a>', 'accessible-video-player' ), 'http://codex.wordpress.org/Function_Reference/add_filter' ); ?></p>
					<h4><?php _e( 'Add Video Formats', 'accessible-video-library' ); ?></h4>
<pre>
add_filter( 'avl_add_custom_fields', 'your_function_add_formats' );
/** 
* Filter to insert or remove video formats.
* @return array Array of all post meta fields shown with video library post type.
*
**/
function your_function_add_formats( $fields ) {
	$fields['mov'] = array( 'label'=>'Video (.mov)', 'input'=>'upload', 'format'=>'mov','type'=>'video' );	
	return $fields;
}
</pre>

					<h4><?php _e( 'Add Additional Languages', 'accessible-video-library' ); ?></h4>
<pre>
add_filter( 'avl_add_custom_fields', 'your_function_add_languages' );
function your_function_add_formats( $fields ) {
	$fields['de_DE'] = array( 'label'=>'German Subtitles (SRT/DFXP)', 'input'=>'upload', 'format'=>'srt','type'=>'subtitle' );	
	return $fields;
}
</pre>
					</div>
				</div>
			</div>
			<div class="avl-support meta-box-sortables">
				<div class="postbox" id="get-support">
				<h3><?php _e('Get Plug-in Support','accessible-video-library'); ?></h3>
					<div class="inside">
					<?php avl_get_support_form(); ?>
					</div>
				</div>
			</div>
		</div>	
	</div>
<?php avl_show_support_box(); ?>
</div>
<?php
}


function avl_get_support_form() {
global $current_user, $avl_version;
get_currentuserinfo();
	// send fields for Accessible Video Library
	$version = $avl_version;
	// send fields for all plugins
	$wp_version = get_bloginfo('version');
	$home_url = home_url();
	$wp_url = site_url();
	$language = get_bloginfo('language');
	$charset = get_bloginfo('charset');
	// server
	$php_version = phpversion();

	// theme data
	$theme = wp_get_theme();
	$theme_name = $theme->Name;
	$theme_uri = $theme->ThemeURI;
	$theme_parent = $theme->Template;
	$theme_version = $theme->Version;	

	// plugin data
	$plugins = get_plugins();
	$plugins_string = '';
		foreach( array_keys($plugins) as $key ) {
			if ( is_plugin_active( $key ) ) {
				$plugin =& $plugins[$key];
				$plugin_name = $plugin['Name'];
				$plugin_uri = $plugin['PluginURI'];
				$plugin_version = $plugin['Version'];
				$plugins_string .= "$plugin_name: $plugin_version; $plugin_uri\n";
			}
		}
	$data = "
================ Installation Data ====================
==Accessible Video Library:==
Version: $version

==WordPress:==
Version: $wp_version
URL: $home_url
Install: $wp_url
Language: $language
Charset: $charset

==Extra info:==
PHP Version: $php_version
Server Software: $_SERVER[SERVER_SOFTWARE]
User Agent: $_SERVER[HTTP_USER_AGENT]

==Theme:==
Name: $theme_name
URI: $theme_uri
Parent: $theme_parent
Version: $theme_version

==Active Plugins:==
$plugins_string
";
	if ( isset($_POST['avl_support']) ) {
		$nonce=$_REQUEST['_wpnonce'];
		if (! wp_verify_nonce($nonce,'accessible-video-library-nonce') ) die("Security check failed");	
		$request = stripslashes($_POST['support_request']);
		$has_donated = ( isset( $_POST['has_donated'] ) && $_POST['has_donated'] == 'on')?"Donor":"No donation";
		$has_read_faq = ( isset( $_POST['has_read_faq'] ) && $_POST['has_read_faq'] == 'on')?"Read FAQ":true; // has no faq, for now.
		$subject = "Accessible Video Library support request. $has_donated";
		$message = $request ."\n\n". $data;
		// Get the site domain and get rid of www. from pluggable.php
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
				$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;		
		$from = "From: \"$current_user->display_name\" <$from_email>\r\nReply-to: \"$current_user->display_name\" <$current_user->user_email>\r\n";

		if ( !$has_read_faq ) {
			echo "<div class='message error'><p>".__('Please read the FAQ and other Help documents before making a support request.','accessible-video-library')."</p></div>";
		} else {
			wp_mail( "plugins@joedolson.com",$subject,$message,$from );
		
			if ( $has_donated == 'Donor' ) {
				echo "<div class='message updated'><p>".__('Thank you for supporting the continuing development of this plug-in! I\'ll get back to you as soon as I can.','accessible-video-library')."</p></div>";		
			} else {
				echo "<div class='message updated'><p>".__('I\'ll get back to you as soon as I can, after dealing with any support requests from plug-in supporters.','accessible-video-library')."</p></div>";				
			}
		}
	} else {
		$request = '';
	}
	echo "
	<form method='post' action='".admin_url('edit.php?post_type=avl-video&page=avl-help')."'>
		<div><input type='hidden' name='_wpnonce' value='".wp_create_nonce('accessible-video-library-nonce')."' /></div>
		<div>
		<p>".
		__('Please note: I do keep records of those who have donated, but if your donation came from somebody other than your account at this web site, please note this in your message.','accessible-video-library')
		."<!--<p>
		<input type='checkbox' name='has_read_faq' id='has_read_faq' value='on' /> <label for='has_read_faq'>".__('I have read <a href="http://www.joedolson.com/accessible-video-library/">the FAQ for this plug-in</a>.','accessible-video-library')." <span>(required)</span></label>
		</p>-->
		<p>
		<input type='checkbox' name='has_donated' id='has_donated' value='on' /> <label for='has_donated'>".__('I have <a href="http://www.joedolson.com/donate/">made a donation to help support this plug-in</a>.','accessible-video-library')."</label>
		</p>
		<p>
		<label for='support_request'>Support Request:</label><br /><textarea name='support_request' required aria-required='true' id='support_request' cols='80' rows='10'>".stripslashes($request)."</textarea>
		</p>
		<p>
		<input type='submit' value='".__('Send Support Request','accessible-video-library')."' name='avl_support' class='button-primary' />
		</p>
		<p>".
		__('The following additional information will be sent with your support request:','accessible-video-library')
		."</p>
		<div class='avl_support'>
		".wpautop($data)."
		</div>
		</div>
	</form>";
}

function avl_show_support_box() {
?>
<div class="postbox-container" style="width:20%">
<div class="metabox-holder">
	<div class="meta-box-sortables">
		<div class="postbox">
		<h3><?php _e('Support this Plug-in','accessible-video-library'); ?></h3>
		<div id="support" class="inside resources">
		<ul>
			<li><p>
				<a href="https://twitter.com/intent/tweet?screen_name=joedolson&text=Accessible%20Video%20Library%20Rocks!" class="twitter-mention-button" data-size="large" data-related="joedolson">Tweet to @joedolson</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</p>
			</li>
			<li><p><?php _e('<a href="http://www.joedolson.com/donate.php">Make a donation today!</a> Every donation counts - donate $5, $10, or $100 and help me keep this plug-in running!','accessible-video-library'); ?></p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<div>
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="WVDV542WW56KG" />
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="Donate" />
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
					</div>
				</form>			
			</li>
			<li><a href="http://profiles.wordpress.org/users/joedolson/"><?php _e('Check out my other plug-ins','accessible-video-library'); ?></a></li>
			<li><a href="http://wordpress.org/plugins/accessible-video-library/"><?php _e('Rate this plug-in','accessible-video-library'); ?></a></li>		
		</ul>
		</div>
		</div>
	</div>
</div>
</div>
<?php
}

// defaults
$d_avl_args = array(
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search'=> false,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_ui' => true, 
				'menu_icon' => null,
				'supports' => array('title','editor','author','thumbnail','excerpt','custom-fields')
			);

$avl_types = array( 
	'avl-video'=>array(
		__('video','accessible-video-library'),
		__('videos','accessible-video-library'),
		__('Video','accessible-video-library'),
		__('Videos','accessible-video-library'),
		$d_avl_args),
);
global $avl_types;

add_filter( 'avl_add_custom_fields', 'avl_add_basic_languages' );
function avl_add_basic_languages( $fields ) {
	if ( get_bloginfo('language') != 'en-us' ) {
		$fields['en-us'] = array( 'label'=>__('US English Subtitles (SRT/DFXP)','accessible-video-library') , 'input'=>'upload', 'format'=>'srt','type'=>'subtitle' );
	}
	if ( get_bloginfo( 'language' ) != 'es-ES' ) {	
		$fields['es_ES'] = array( 'label'=>__('Spanish Subtitles (SRT/DFXP)','accessible-video-library') ,'input'=>'upload', 'format'=>'srt','type'=>'subtitle' );	
	}
	return $fields;
}

// begin add boxes
function avl_add_outer_box() {
	add_meta_box( 'avl_custom_div',__('Video Data','accessible-video-library'), 'avl_add_inner_box', 'avl-video', 'side','high' );			
}
function avl_add_inner_box() {
	$fields = apply_filters( 'avl_add_custom_fields', get_option('avl_fields') );
	global $post_id;
	$format = sprintf(
		'<input type="hidden" name="%1$s" id="%1$s" value="%2$s" />',
		'mcm_nonce_name', wp_create_nonce( plugin_basename( __FILE__ ) )
	);
	foreach ( $fields as $key=>$value ) {
		$label = $value['label'];
		$input = $value['input'];
		$choices = ( isset($value['choices']) )?$value['choices']:false;
		$format .= avl_create_field( $key, $label, $input, $post_id, $choices );
	}
	$shortcode = "<div class='avl-shortcode'><label for='shortcode'>".__('Shortcode','avl-video').":</label> <input type='text' id='shortcode' disabled value='[avl_video id=\"$post_id\"]' /></div>";
	echo '<div class="avl_post_fields">'.$shortcode.$format.'</div>';
}

function avl_create_options( $choices, $selected ) {
	$return = '';
	if (is_array($choices) ) {
		foreach($choices as $value ) {
			$v = esc_attr( $value);
			$chosen = ( $v == $selected )?' selected="selected"':'';
			$return .= "<option value='$value'$chosen>$value</option>";
		}
	}
	return $return;
}

add_action( 'wp_enqueue_scripts', 'avl_enqueue_scripts' );
function avl_enqueue_scripts() {
	wp_register_style( 'avl-mediaelement', plugins_url( 'css/avl-mediaelement.css', __FILE__ ) );
	wp_enqueue_style( 'avl-mediaelement' );
	wp_deregister_script( 'wp-mediaelement' );
	wp_register_script( 'wp-mediaelement', plugins_url( 'js/avl-mediaelement.js', __FILE__ ), array( 'jquery', 'mediaelement' ) );
	$args = apply_filters( 'avl_mediaelement_args', array( 
			'pluginPath' => includes_url( 'js/mediaelement/','relative'),
			'alwaysShowControls'=>'true',
		) );
	wp_localize_script( 'wp-mediaelement', '_avlmejsSettings', $args );
}

add_filter( 'avl_mediaelement_args', 'avl_options' );
function avl_options( $args ) {
	if ( get_option( 'avl_default_caption' ) != '' ) {
		$args['startLanguage'] = strtolower( get_option( 'avl_default_caption' ) );
	}
	return $args;
}

add_action( 'admin_enqueue_scripts', 'avl_enqueue_admin_scripts' );
function avl_enqueue_admin_scripts() {
	$screen = get_current_screen();
	if ( $screen->base == 'post' ) {
		if( function_exists('wp_enqueue_media') && !did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
		wp_enqueue_script( 'avl-admin-script', plugins_url( 'js/uploader.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'avl-admin-script', 'baseUrl', home_url() );		
	}
}

function avl_create_field( $key, $label, $type, $post_id, $choices=false ) {
	$value = false;
	$custom = esc_attr( get_post_meta($post_id, "_".$key,true ) );

	switch( $type ) {
		case 'text':
			$value = '
		<div>
			<label for="_'.$key.'">'.$label.'</label><br />'.
			'<input style="width: 80%;" type="text" name="_'.$key.'" value="'.$custom.'" />
		</div>';
		break;
		case 'upload':
			$value = '
		<div class="field-holder"><label for="_'.$key.'">'.$label.'</label><br />'.
			'<input style="width: 70%;" type="text" class="textfield" name="_'.$key.'" value="'.$custom.'" id="_'.$key.'" /> <a href="#" class="button textfield-field">'.__('Upload','accessible-video-library').'</a>
			<div class="selected"></div>
		</div>'."\n";
		break;
		case 'select':
			$value = '
		<div>
			<label for="_'.$key.'">'.$label.'</label><br />'.
			'<select name="_'.$key.'">'.
				avl_create_options( $choices, $custom ).
			'</select>
		</div>';
		break;
	}
	return $value;
}
add_action( 'admin_menu','avl_add_outer_box' );

function avl_post_meta( $id ) {
	$fields = apply_filters( 'avl_add_custom_fields', get_option('avl_fields') );
	if ( isset($_POST['_inline_edit']) ) { return; }
	foreach ( $fields as $key=>$value ) {
		if ( isset( $_POST["_".$key ] ) ) {
			$value = $_POST[ "_".$key ];
			if ( $key == 'external' ) {
				avl_register_attachment( $value, $id );
			}
			update_post_meta( $id, "_".$key, $value );			
		}
	}
	// for post screen filters
	if ( get_post_field( 'post_content', $id, 'raw' ) == '' ) {
		add_post_meta( $id, '_notranscript', 'true' );
	} else {
		delete_post_meta( $id, '_notranscript' );
	}
}

function avl_register_attachment( $url, $id ) {
	if ( $url ) {
		$title = get_the_title( $id ) . '/YouTube';
		if ( !avl_is_url( $url ) ) {
			$url = "http://youtu.be/$url";
		}
		$attachment = array(
			'post_mime_type'    => "video/youtube",
			'post_title'        => $title,
			'post_content'      => '',
			'post_excerpt'		=> '',
			'post_status'       => 'inherit',
			'post_parent'       => $id,
			'guid' 				=> $url
		);
		if ( get_option( $id, '_external_id', true ) == '' ) {
			$attach_id = wp_insert_attachment( $attachment, $url );
			update_post_meta( $attach_id, '_wp_attached_file', $url );
			update_post_meta( $attach_id, '_wp_attachment_metadata', array( 'mime_type'=>"video/youtube" ) ); 
			update_post_meta( $id, '_external_id', $attach_id );
		}
	}
}

function avl_posttypes() {
	global $avl_types;
	$types = $avl_types;
	$enabled = array( 'avl-video' );
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value =& $types[$key];		
			$labels = array(
				'name' => $value[3],
				'singular_name' => $value[2],
				'add_new' => __( 'Add New' , 'avl-video' ),
				'add_new_item' => sprintf( __( 'Create New %s','accessible-video-library' ), $value[2] ),
				'edit_item' => sprintf( __( 'Modify %s','accessible-video-library' ), $value[2] ),
				'new_item' => sprintf( __( 'New %s','accessible-video-library' ), $value[2] ),
				'view_item' => sprintf( __( 'View %s','accessible-video-library' ), $value[2] ),
				'search_items' => sprintf( __( 'Search %s','accessible-video-library' ), $value[3] ),
				'not_found' =>  sprintf( __( 'No %s found','accessible-video-library' ), $value[1] ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash','accessible-video-library' ), $value[1] ), 
				'parent_item_colon' => ''
			);
			$raw = $value[4];
			$args = array(
				'labels' => $labels,
				'public' => $raw['public'],
				'publicly_queryable' => $raw['publicly_queryable'],
				'exclude_from_search'=> $raw['exclude_from_search'],
				'show_ui' => $raw['show_ui'],
				'show_in_menu' => $raw['show_in_menu'],
				'show_ui' => $raw['show_ui'], 
				'menu_icon' => ($raw['menu_icon']==null)?plugins_url('images',__FILE__)."/$key.png":$raw['menu_icon'],
				'query_var' => true,
				'rewrite' => array( 'with_front'=>false, 'slug'=>'avl-video' ),
				'hierarchical' => false,
				'supports' => $raw['supports']
			); 
			register_post_type($key,$args);
		}
	}
}

// filter to auto replace content with full template
add_filter( 'the_content','avl_replace_content', 10, 2 );
function avl_replace_content( $content, $id=false ) {
	if ( !is_main_query() && !$id ) { return $content; }
	if ( is_singular( 'avl-video' ) && !isset( $_GET['transcript'] ) ) {
		$id = get_the_ID();
		return avl_video( $id );
	} else {
		return $content;
	}
}

function avl_posttypes_messages( $messages ) {
	global $post, $post_ID, $avl_types;
	$types = $avl_types; 
	$enabled = array('avl-video');
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value = $types[$key];
			$messages[$key] = array(
				0 => '', // Unused. Messages start at index 1.
				1 => sprintf( __( '%1$s updated. <a href="%2$s">View %1$s</a>' ), $value[2], esc_url( get_permalink($post_ID) ) ),
				2 => __('Custom field updated.'),
				3 => __('Custom field deleted.'),
				4 => sprintf( __('%s updated.'), $value[2] ),
				/* translators: %s: date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf( __('%1$s restored to revision from %2$ss'), $value[2], wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('%1$s published. <a href="%2$s">View %3$s</a>'), $value[2], esc_url( get_permalink($post_ID) ), $value[0] ),
				7 => sprintf( __( '%s saved.' ), $value[2] ),
				8 => sprintf( __('%1$s submitted. <a target="_blank" href="%2$s">Preview %3$s</a>'), $value[2], esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ), $value[0] ),
				9 => sprintf( __('%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %4$s item</a>'),
				  $value[2], date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ), $value[0] ),
				10 => sprintf( __('%1$s draft updated. <a target="_blank" href="%2$s">Preview %3$s</a>'), $value[2], esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ), $value[0] ),
			);
		}
	}
	return $messages;
}

function avl_taxonomies() {
	global $avl_types;
	$types = $avl_types; 
	$enabled = array( 'avl-video' );
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value =& $types[$key];
			register_taxonomy(
				"avl_category_$key",	// internal name = machine-readable taxonomy name
				array( $key ),	// object type = post, page, link, or custom post-type
				array(
					'hierarchical' => true,
					'label' => sprintf( __('%s Categories','accessible-video-library'), $value[2] ),	// the human-readable taxonomy name
					'query_var' => true,	// enable taxonomy-specific querying
					'rewrite' => array( 'slug' => "$key-group" ),	// pretty permalinks for your taxonomy?
				)
			);
		}
	}
}
add_action( 'save_post','avl_post_meta', 10 );

function avl_get_custom_field($field,$id='') {
	global $post;
	$id = ($id != '')?$id:$post->ID;
	$custom_field = get_post_meta($id, $field, true);
	return $custom_field;
}

function get_avl_video( $atts ) {
	extract( shortcode_atts( array(
				'id' => '',
				'height' => false,
				'width' => false
			), $atts ) );
	return avl_video( $id, $height, $width );
}

function get_avl_media( $atts ) {
	extract( shortcode_atts( array(
				'category' => '',
				'header' => 'h4',
				'orderby' => 'menu_order',
				'order' => 'asc', 
				'height' => false, 
				'width' => false,
			), $atts ) );
	return avl_media( $category, $header, $orderby, $order, $height, $width );	
}

// add shortcode interpreter
add_shortcode('avl_video','get_avl_video');
add_shortcode('avl_media','get_avl_media');

function avl_media( $category, $header='h4', $orderby='menu_order', $order='asc', $height=false, $width=false ) {
	$args = array( 'post_type'=>'avl-video', 'orderby'=>$orderby, 'order'=>$order );
	$args['numberposts'] = -1;
	$media = '';
	if ( $category ) { 
		$args['tax_query'] = array( 
			array( 
				'taxonomy'=>'avl_category_avl-video', 
				'field'=>'slug', 
				'terms'=>$category 
			)
		); 
	} 
	$posts = get_posts( $args );
	foreach ( $posts as $p ) {
		$permalink = get_permalink( $p->ID );
		$media .= "\n
		<div class='avl-video avl-video-$p->ID'>
			<$header><a href='$permalink'>$p->post_title</a></$header>		
			<div class='avl-video-description'>
				<div class='avl-video-thumbnail'>".avl_video( $p->ID, 135, 240 )."</div>
				".wpautop( $p->post_excerpt ) . "
			</div>
			\n
		</div>\n";
	}	
	return $media;
}

function avl_is_url($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function avl_video( $id, $height=false, $width=false ) {
	global $content_width;
	$fields = apply_filters( 'avl_add_custom_fields', get_option('avl_fields') );
	$yt_url = $image = $has_video = false;
	if ( !is_numeric( $id ) ) { 
		$video = get_page_by_title( $id, OBJECT, 'avl-video' ); $id = $video->ID; 
	}
	$youtube = avl_get_custom_field( '_external', $id );
	
	if ( $youtube && avl_is_url( $youtube ) ) {
		$yt_url = "$youtube"; 
	} else if ( $youtube && !avl_is_url( $youtube ) ) {
		$yt_url = "http://youtu.be/$youtube"; 
	}
	$params = '';	
	foreach ( $fields as $k => $field ) { // need to id videos
		if ( $field['type'] == 'video' && $k != 'external' ) {
			${$field['format']} = avl_get_custom_field( '_'.$field['format'],$id );
			if ( ${$field['format']} ) { $params .= $field['format'].'="'.${$field['format']}.'" '; $has_video = true; }
		}
	}
	if ( has_post_thumbnail( $id ) ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumbnail_name' );
		$image = $thumb[0]; // thumbnail url
	}	
	if ( !$image && $youtube ) {
		$replace = array( "http://youtu.be/", "http://www.youtube.com/watch?v=", "https://youtu.be/",  "https://www.youtube.com/watch?v=" );
		$youtube = str_replace( $replace,'',$youtube );
		$image = "http://img.youtube.com/vi/$youtube/0.jpg"; 
	}
	//$audio_desc = avl_get_custom_field( '_audio_desc', $id ); MediaElements.js does not support audio description.
	$captions = avl_get_custom_field( '_captions', $id );
	$content = get_post_field( 'post_content',$id );
	
	if ( $content ) {
		$transcript = "<a href='".add_query_arg( 'transcript','true',get_permalink( $id ) )."' class='video-transcript-link'>".sprintf( __( 'Transcript<span class="screen-reader-text"> to %s</span>','accessible-video-library'), get_post_field( 'post_title', $id ) )."</a>"; 
	} else { 
		$transcript = ''; 
	}
	$transcript = apply_filters( 'avl_transcript_link', $transcript, $id, get_post_field( 'post_title', $id ) );
	// player selector in settings
	// to test YouTube, need to not have any video attached (WP auto uses first attached vid]
	if ( get_option( 'avl_responsive' ) == 'true' ) {
		$height = $width = '100%';
	} else {
		$height = $height; $width = $width;
	}
	if ( $height && $width ) { $params .= " height='$height' width='$width'"; } else { $params .= " height='360' width='640'"; }
	if ( get_option( 'avl_responsive' ) == 'true' ) {
		$vid = do_shortcode( "[video $params poster='$image']" );
		$html = str_replace( array( 'px;', 'width="100"', 'height="100"' ), array( '%;', 'width="100%"', 'height="100%"' ), $vid );
	} else {
		$html = do_shortcode("[video $params poster='$image']");
	}
	if ( !$html && $youtube ) {
		// this won't return any results when there's only YouTube and we're not on the AVL media page, so need to generate them.
		$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
		if ( 'mediaelement' === $library && did_action( 'init' ) ) {
			if ( get_option( 'avl_responsive' ) != 'true' ) {
				$content_width = ( !$content_width ) ? apply_filters( 'avl_default_width', 640 ) : $content_width; 
				$width = ( $width ) ? $width : $content_width ;
				$height = ( $height ) ? $height : round( $content_width / apply_filters( 'avl_default_aspect', 1.6 ) ) ;
				$width = $width . 'px';
				$height = $height . 'px';
			}
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
			$html = '<div class="avl_media_container" style="width: '.$width.'; height: '.$height.'; max-width: 100%;">';
			$html .= "<!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->";
			$html .= '<video class="wp-video-shortcode" id="video-'.$id.'-1" width="'.$width.'" height="'.$height.'" poster="http://img.youtube.com/vi/'.$youtube.'/0.jpg" preload="metadata" controls="controls">
						<a href="http://youtu.be/'.$youtube.'">http://youtu.be/'.$youtube.'</a>
						<source type="video/youtube" src="http://youtu.be/'.$youtube.'" />
					</video>
					</div>';
		}
	}
	$html = apply_filters( 'avl_implementation', $html, $id, $captions, $yt_url ).$transcript;
	return $html;
}

add_filter( 'avl_implementation', 'avl_add_a11y', 10, 4 );
function avl_add_a11y( $html, $id=false, $captions='', $youtube='' ) {
	$fields = apply_filters( 'avl_add_custom_fields', get_option('avl_fields') );
	if ( $captions ) {
		$html = str_replace( '</video>','<track kind="subtitles" src="'.$captions.'" label="'.__( 'Captions','accessible-video-library').'" srclang="'.get_bloginfo('language').'" /></video>', $html );
	}
	
	foreach ( $fields as $key => $field ) {
		if ( $field['type'] == 'subtitle' ) {
			$label = esc_attr( $field['label'] );
			$value = get_post_meta( $id, '_'.$key, true );
			if ( $value ) {
				$html = str_replace( '</video>','<track kind="subtitles" src="'.$value.'" label="'.$label.'" srclang="'.$key.'" /></video>', $html );
			}
		}
	}
	if ( $youtube ) { // this is kludgy, but it's what I've got for now.
		$att_source = content_url() . '/uploads/'. $youtube;
		$html = str_replace( $att_source, $youtube, $html );
		$html = str_replace( '<source type="" src="'.$youtube.'" />', '', $html );	
		$html = str_replace( '</video>','<source type="video/youtube" src="'.$youtube.'" /></video>', $html );
	}
	return $html;
}

function avl_admin_styles() {
	//wp_enqueue_style('thickbox');
}

add_action('admin_print_styles', 'avl_admin_styles');

add_filter( 'get_media_item_args', 'avl_custom' );
function avl_custom( $args ) {
	$args['send'] = true;
	return $args; 
}

add_filter('upload_mimes','avl_custom_mimes');
function avl_custom_mimes( $mimes=array() ) {
	$mimes['srt'] = 'text/plain';
	$mimes['dfxp'] = 'application/ttaf+xml';
	//$mimes['sub'] = 'text/plain';
	return $mimes;
}


function avl_column($cols) {
	$cols['avl_captions'] = __('Captions','accessible-video-library');
	$cols['avl_transcript'] = __('Transcript','accessible-video-library');
	$cols['avl_id'] = __('ID','accessible-video-library');
	return $cols;
}

// Echo the ID for the new column
function avl_custom_column( $column_name, $id ) {
	$no = __( 'No','accessible-video-library' );
	$yes = __( 'Yes','accessible-video-library' );
	switch ( $column_name ) {
		case 'avl_captions' :
			$srt = get_post_meta( $id, '_captions',true );
			$notes = "<span class='avl no-captions'>$no</span>";
			if ( $srt ) { $notes = "<span class='avl has-captions'>$yes</span>"; }
			echo $notes;
		break;
		case 'avl_transcript' :
			$transcript = get_post_field( 'post_content', $id );
			$notes = "<span class='avl no-transcript'>$no</span>";		
			if ( $transcript ) { $notes = "<span class='avl has-transcript'>$yes</span>"; }
			echo $notes;
		break;
		case 'avl_id' :
			echo $id;
		break;		
	}
}

function avl_return_value($value, $column_name, $id) {
	if ( $column_name == 'avl_captions' || $column_name == 'avl_transcript' || $column_name == 'avl_id' ) {
		$value = $id;
	}
	return $value;
}

// Output CSS for width of new column
function avl_css() {
?>
<style type="text/css">
#avl_captions, #avl_transcript { width: 70px; }
#avl_id { width: 50px; }
.avl_captions, .avl_transcript { text-align: center; vertical-align: middle; }
.avl { color: #fff; padding: 2px 4px; border-radius: 3px; width: 3em; display: inline-block; box-shadow: 1px 1px #333; }
.no-transcript, .no-captions { background: #c00; }
.has-transcript, .has-captions { background: #070;}
.avl-shortcode { padding: 4px; background: #fff; margin-bottom: 4px; }
.avl-shortcode label { font-weight: 700; }
.avl-shortcode input { border: none; font-size: 1.2em; }
</style>
<?php	
}

// Actions/Filters for various tables and the css output
add_action('admin_init', 'avl_add');
function avl_add() {
	add_action( 'admin_head', 'avl_css' );
	add_filter( "manage_avl-video_posts_columns", 'avl_column' );			
	add_action( "manage_avl-video_posts_custom_column", 'avl_custom_column', 10, 2 );
}

/* Column sorting/filtering 
add_filter( "pre_get_posts", 'orderby_featured_image_title' );
function orderby_featured_image_title( $query ) {
	if ( !is_admin() ) {
		return;
	}

	$order_by = $query->get( 'orderby' );

	if ( $order_by === $this->column_slug ) {
		$query->set( 'meta_key', '_thumbnail_id' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
*/

add_filter( "pre_get_posts", 'filter_avl_videos' );
function filter_avl_videos( $query ) {
	global $pagenow;
	if ( !is_admin() ) { return; }

	$qv = & $query->query_vars;
	
	if ( $pagenow == 'edit.php' && !empty( $qv['post_type'] ) && $qv['post_type'] == 'avl-video' ) {
		if ( empty( $_GET['avl_filter'] ) ) { return; }

		if ( $_GET['avl_filter'] == 'transcripts' ) {
			$query->set(
				  'meta_query',
				  array(
					   array(
						   'key'     => '_notranscript',
						   'value'   => 'true',
						   'compare' => '='
					   )
				  )
			);		
		} else if ( $_GET['avl_filter'] == 'captions' ) {
			$query->set(
				  'meta_query',
				  array(
					   array(
						   'key'     => '_captions',
						   'value'   => '',
						   'compare' => '='
					   )
				  )
			);
		}
	}
}

add_action( "restrict_manage_posts", 'filter_avl_dropdown' );
function filter_avl_dropdown() {
	global $wp_query, $typenow;
	if ( $typenow == 'avl-video' ) {
		$post_type = get_post_type_object( $typenow );
		if ( isset( $_GET['avl_filter'] ) ) {
			$captions = ( $_GET['avl_filter'] == 'captions' ) ? ' selected="selected"' : '';
			$transcripts = ( $_GET['avl_filter'] == 'transcripts' ) ? ' selected="selected"' : '';
		} else {
			$captions = $transcripts = '';
		}
		?>
		<select class="postform" id="avl_filter" name="avl_filter">
			<option value="all"><?php _e( 'All videos', 'accessible-video-library' ); ?></option>		
			<option value="captions"<?php echo $captions; ?>><?php _e( 'Videos missing Captions', 'accessible-video-library' ); ?></option>
			<option value="transcripts"<?php echo $transcripts; ?>><?php _e( 'Videos missing Transcripts', 'accessible-video-library' ); ?></option>
		</select>
	<?php
	}
}
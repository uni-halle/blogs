<?php

/*
Plugin Name: FollowMe
Plugin URI: http://wpburn.com/wordpress-plugins/wp-followme-plugin
Description: WP FollowMe is a wordpress plugin that allow you to add a twitter "Follow Me" badge on your wordpress blog.
Author: wpBurn.com
Version: 2.0.8
Author URI: http://wpburn.com
*/

function wp_followme_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function activate_wp_followme() {
	$wp_followme_opts1 = get_option('wp_followme_options');
	$wp_followme_opts2 =array();
	if ($wp_followme_opts1) {
		$wp_followme = $wp_followme_opts1 + $wp_followme_opts2;
		update_option('wp_followme_options',$wp_followme);
	}
	else {
		$iconu = wp_followme_url('icons/11.png');
		$wp_followme_opts1 = array(	'twitter'=>'',
			'top'=>'200',
			'align'=>'right',
			'followmsg'=>'Follow Me',
			'icon'=>$iconu,
			'color'=>'59B7FF',
			'iconbgcolor'=>'FFFFFF',
			'textcolor'=>'FFFFFF',
			'textsize'=>'14',
			'textfont'=>'Verdana',
			'bordercolor'=>'FFFFFF',
			'powered_by'=>''
		);
		$wp_followme = $wp_followme_opts1 + $wp_followme_opts2;
		add_option('wp_followme_options',$wp_followme);
	}
}

global $wp_followme_nett;
$wp_followme_nett = array( 'twitter', 'icon', 'followmsg');


function wp_followme_credits ($translation, $text, $domain)
{
	global $wp_followme;
	if ($wp_followme[powered_by] && $text == 'Proudly powered by %s') return $text . '</a> using <a href="http://wpburn.com">FollowMe official extension';
	return $translation;
}
add_filter('gettext', 'wp_followme_credits', 100, 3);


register_activation_hook( __FILE__, 'activate_wp_followme' );
global $wp_followme;
$wp_followme = get_option('wp_followme_options');
define("wp_followme_VER", "2.0.7", false);

function wp_followme_scripts() {
	global $wp_followme;
	//wp_enqueue_style( 'wp_followme_css_file', wp_followme_url( 'followme-style.css' ), false, false, false);
	wp_enqueue_script( 'wp_followme_swfobject', "http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js", false, false, false );
}

add_action( 'init', 'wp_followme_scripts' );
add_action( 'init', 'followme_admin_warnings' );

function followme_admin_warnings() {
	global $wp_followme;

	function followme_warning() {
		global $wp_followme;
		if ( !$wp_followme[twitter] ) {
			echo '<div id="followme-warning" class="updated fade"><p><strong>WP FollowMe plugin is not configured yet.</strong>
			You must <a href="options-general.php?page=followme.php">enter your Twitter URL</a> for it to work.</p>';
			if (!$wp_followme[powered_by]) echo '<p>“Powered by” info is not displayed by default. <a href="options-general.php?page=followme.php">Enable it</a> to encourage further development.</p>';
			echo '</div>';
		}
	}

	function followme_wrong_settings(){
		global $wp_followme;
		if ( substr($wp_followme[twitter], 0, 4) != "http" && $wp_followme['twitter'] != ""){
			echo '<div id="followme-warning" class="updated fade"><p><strong>WP FollowMe plugin is not properly configured.</strong>
			The <a href="options-general.php?page=followme.php">Twitter URL</a> must begin with http.</p></div>';
		}
	}
	add_action('admin_notices', 'followme_warning');
	add_action('admin_notices', 'followme_wrong_settings');
	return;
}


//inline styles
function wp_followme_css() {
	?>
<script type="text/javascript">
	swfobject.registerObject("wpFollowmeFlash", "9.0.0");
</script>
<style type="text/css">
	.getflash { font-size:8px; }
	.wp_followme_c2 {
		position:fixed;
		background:#<?php global $wp_followme; echo $wp_followme[color]; ?>;
		top:<?php global $wp_followme; echo $wp_followme['top'];?>px;
		<?php global $wp_followme; echo $wp_followme['align'];?>:0px;
		width:32px;
		height:160px;
		border:1px solid #<?php global $wp_followme; echo $wp_followme['bordercolor'];?>;
		color:#<?php global $wp_followme; echo $wp_followme[textcolor]; ?>;
	}
</style>

<?php
}
add_action('wp_head', 'wp_followme_css');

function show_followme() {
	global $wp_followme;
	?>

<div style="position:relative;">
	<div class="wp_followme_c2">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="32" height="160" id="wpFollowmeFlash">
			<param name="movie" value="<?php echo wp_followme_url('flash/wp_followme.swf'); ?>" />
			<param name="allowfullscreen" value="false" />
			<param name="allowscriptaccess" value="always" />
			<param name="flashvars" value="twit_icon=<?php global $wp_followme; echo $wp_followme['icon'];?>&amp;turl=<?php global $wp_followme; echo $wp_followme['twitter'] ?>&amp;twitmsg=<?php global $wp_followme; echo $wp_followme['followmsg'] ?>&amp;twitmsgcolor=<?php global $wp_followme; echo $wp_followme[textcolor]; ?>&amp;iconbgcolor=<?php global $wp_followme; echo $wp_followme[iconbgcolor]; ?>" />
			<param name="bgcolor" value="#<?php global $wp_followme; echo $wp_followme[color]; ?>" />
			<!--[if !IE]>-->
			<object type="application/x-shockwave-flash" data="<?php echo wp_followme_url('flash/wp_followme.swf'); ?>" width="32" height="160">
				<param name="allowfullscreen" value="false" />
				<param name="allowscriptaccess" value="always" />
				<param name="flashvars" value="twit_icon=<?php global $wp_followme; echo $wp_followme['icon'];?>&amp;turl=<?php echo $wp_followme['twitter'] ?>&amp;twitmsg=<?php global $wp_followme; echo $wp_followme['followmsg'] ?>&amp;twitmsgcolor=<?php global $wp_followme; echo $wp_followme[textcolor]; ?>&amp;iconbgcolor=<?php global $wp_followme; echo $wp_followme[iconbgcolor]; ?>" />
				<param name="bgcolor" value="#<?php global $wp_followme; echo $wp_followme[color]; ?>" />
				<!--<![endif]-->
				<div class="getflash">
					<a rel="nofollow" href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>
					<?php global $wp_followme; if($wp_followme[powered_by] == '1') { ?>Plugin by wpburn.com <a href="http://wpburn.com">wordpress themes</a><?php } ?>
				</div><!--[if !IE]>-->
			</object> <!--<![endif]-->
		</object>
	</div>
</div>

<?php
}


add_action( 'wp_footer', 'show_followme' );

function wp_followme_settings() {
	// Add a new submenu under Options:
	add_options_page('WP FollowMe', 'WP FollowMe', 9, basename(__FILE__), 'wp_followme_settings_page');
}

function wp_followme_admin_head() {
	?>

<?php
}

add_action('admin_head', 'wp_followme_admin_head');

function wp_followme_settings_page() {
	require_once(ABSPATH.'/wp-admin/includes/plugin-install.php');

	?>
<script src="<?php echo wp_followme_url('js/jscolor.js'); ?>" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/jquery.effects.core.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){

		var t1 = $('#iconbgall').val();
		var t2 = $('#iconbgi').val();

		if(t1 == t2){
			$('#iconbgc').attr("checked") == false;
		}else{
			$('#iconbgc').attr("checked") == true;
		}


		$('#iconbgc').click(function(){

			if ($('#iconbgc').attr("checked") == true) {
				var tt = $('#iconbgall').val();

				$('#iconbgi').val(tt);
				$('#iconbgi').attr("disable", true);

			}
		});
		$('.twit_icon').click(function(){
			var icurl = $(this).attr("src");
			$('#iconurl').val(icurl);
			$('.twit_icon').css("border", "none");
			$(this).css("border", "2px dotted #ABABAB");

		});

		var icnsrc = $("#iconurl").val();
		$('.twit_icon[src="' +icnsrc+ '"]').css("border", "2px dotted #ABABAB");

	});
</script>
<div class="wrap">
	<h2>WP FollowMe</h2>

	<form id="followme_options" method="post" action="options.php">
		<div id="poststuff" class="metabox-holder has-right-sidebar">

			<div style="float:left;width:60%;">
				<?php
				settings_fields('wp-followme-group');
				$wp_followme = get_option('wp_followme_options');
				?>
				<h2>Settings</h2>

				<div class="postbox" style="line-height: 1.4em;">
					<h3 style="cursor:pointer;"><span>WP FollowMe Options</span></h3>
					<div>
						<table class="form-table">


							<tr valign="top" class="alternate">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[twitter]" style="font-weight:bold;">Your Twitter URL</label></th>
								<td>
									<input autocomplete="off" type="text" name="wp_followme_options[twitter]" value="<?php echo $wp_followme[twitter]; ?>" class="regular-text code" /> <br />Example: https://twitter.com/wpburn<br />
									<?php
									if (isset($wp_followme[twitter]))
									{
										if (!$wp_followme[twitter])
											echo '<span style="color:red;">Error:</span> <strong>Twitter URL cannot be blank</strong>';
										elseif (substr($wp_followme[twitter], 0, 4) != "http")
											echo '<span style="color:red;">Error:</span> <strong>Twitter URL must begin with <em>http</em></strong>';
									}
									?>
								</td>
							</tr>


							<tr valign="top">
								<th scope="row" style="width:20%;">
									Like what we're doing?
								</th>
								<td>
									<strong>Help us back by enabling the “Powered by” info on your website!</strong><br />
									<input id="poweredby" type="checkbox" name="wp_followme_options[powered_by]" value="1" <?php if ($wp_followme[powered_by] == '1' || !isset($wp_followme[twitter])) echo 'checked="checked"'; ?>/> Add the “Powered by” info
									<br />
								</td>
							</tr>


							<tr valign="top" class="alternate">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[followmsg]">Follow message</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[followmsg]" value="<?php echo $wp_followme[followmsg]; ?>" class="regular-text code" /></td>
							</tr>

							<tr valign="top">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[color]">Background color</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[color]" value="<?php echo $wp_followme[color]; ?>" id="iconbgall" class="color regular-text code" /></td>
							</tr>

							<tr valign="top">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[iconbgcolor]">Icon background color</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[iconbgcolor]" value="<?php echo $wp_followme[iconbgcolor]; ?>" id="iconbgi" class="color regular-text code" />
									<br />
									<input id="iconbgc" type="checkbox" name="iconbgcolor" value="iconbgcolor" <?php if ( $wp_followme[iconbgcolor] == $wp_followme[color] ){ echo 'checked="checked"'; } ?>/> Use same color as the badge background color
								</td>
							</tr>

							<tr valign="top" class="alternate">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[textcolor]">Text color</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[textcolor]" value="<?php echo $wp_followme[textcolor]; ?>" class="color regular-text code" /></td>
							</tr>

							<tr valign="top" class="alternate">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[bordercolor]">Badge border color</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[bordercolor]" value="<?php echo $wp_followme[bordercolor]; ?>" class="color regular-text code" /></td>
							</tr>

							<tr valign="top">
								<th scope="row" style="width:20%;"><label for="wp_followme_options[icon]">Icon URL</label></th>
								<td><input autocomplete="off" type="text" name="wp_followme_options[icon]" id="iconurl" value="<?php echo $wp_followme[icon]; ?>" class="regular-text code" /></td>
							</tr>

							<tr valign="top">
								<th scope="row" style="width:20%;">Choose icon</th>
								<td>
									<table width="300" border="0" cellspacing="5" cellpadding="5">
										<tr>
											<td><img src="<?php echo wp_followme_url('icons/1.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/2.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/3.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/4.png'); ?>" class="twit_icon" /></td>
										</tr>
										<tr>
											<td><img src="<?php echo wp_followme_url('icons/5.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/6.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/7.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/8.png'); ?>" class="twit_icon" /></td>
										</tr>
										<tr>
											<td><img src="<?php echo wp_followme_url('icons/9.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/10.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/11.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/12.png'); ?>" class="twit_icon" /></td>
										</tr>
										<tr>
											<td><img src="<?php echo wp_followme_url('icons/13.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/14.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/15.png'); ?>" class="twit_icon" /></td>
											<td><img src="<?php echo wp_followme_url('icons/16.png'); ?>" class="twit_icon" /></td>
										</tr>

									</table>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row" style="width:20%;"> </th>
								<td>To choose an icon, click on it. Currently used icon has a selection border.<br />
									To use your own icon, put its URL into the <em>Icon URL</em> field and save the changes.<br />
									Also, if you have designed a custom icon and you want it to be available on next version of this plugin, post a comment to the <a href="http://wpburn.com/wordpress-plugins/wp-followme-plugin" target="_blank">WP FollowMe plugin page</a>.<br />
									Recommended icon size is 32x32 px. If you use a bigger icon, it will be automatically resized but the quality can be affected.
								</td>
							</tr>

						</table>
					</div>
				</div>

				<h2>Positioning</h2>
				<table class="form-table">

					<tr valign="top">
						<th scope="row"><label for="wp_followme_options[align]">Alignment</label></th>
						<td><select name="wp_followme_options[align]">
							<option value="left" <?php if ($wp_followme['align'] == "left"){ echo "selected";}?> >Left</option>
							<option value="right" <?php if ($wp_followme['align'] == "right"){ echo "selected";}?> >Right</option>
						</select></td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="wp_followme_options[top]">Distance From Top</label></th>
						<td><input type="text" name="wp_followme_options[top]" class="small-text" value="<?php echo $wp_followme['top']; ?>" />&nbsp;px</td>
					</tr>


				</table>


				<script type="text/javascript">
					$(function() {
						$("#button_save_settings").click(function() {
							if(!$('#poweredby').is(':checked'))
								$("#div_submit_confirm").effect("drop", {mode: "show", direction: "left"}, 100, function(){
									$('html, body').animate({
																	   scrollTop: $(document).height()-$(window).height()},
																	   100,
																	   "easeOutQuint"
																	);
								});
							else
								$("#followme_options").submit();
							return false;
						});



						$("#submit_consider_yes").click(function() {
							$("#poweredby").attr("checked", "checked");
							$("#followme_options").submit();
							return false;
						});
					});
				</script>

				<div class="submit">
					<input type="button" class="button-primary" value="<?php _e('Save Changes') ?>"  id="button_save_settings" />

					<div style="display:none; background-color:#FFFFE0; border-color:#E6DB55;
						border-radius:3px 3px 3px 3px; border-style:solid; border-width:1px;
						padding:2px 10px 2px 10px; margin:10px 0 0 0;" id="div_submit_confirm">

						<p style="font-weight:bold;">Help us grow and improve our product!</p>
						<p>Please enable the “Powered by” info on your website. Showing that your website uses our software allows us
						to get more valuable feedback, which we use to flesh out our product and make it into something really great.</p>
						<p style="font-weight:bold; color:red;">Would you like to enable the “Powered by” info?</p>
						<p>
							<input type="button" class="button-primary" value="<?php _e('Yes, I want to contribute');?>" id="submit_consider_yes" />
							<input type="submit" class="button-secondary" value="<?php _e('No, I don\'t want to contribute');?>" />
						</p>
					</div>
				</div>

			</div>
	</form>

	<div id="side-info-column" class="inner-sidebar">
		<div class="postbox">
			<h3 class="hndle"><span>WP FollowMe</span></h3>
			<div class="inside">
				<ul>
					<li><a href="http://wpburn.com/wordpress-plugins/wp-followme-plugin" title="WP FollowMe plugin page" target="_blank">Plugin Homepage</a></li>
					<li><a href="http://wpburn.com" title="Visit wpBurn.com Website" target="_blank">wpBurn.com Website</a></li>
					<li><a href="http://wpburn.com/wordpress-themes" title="wpBurn Blue Wordpress Theme" target="_blank">Wordpress themes</a></li>
					<li><a href="http://wordpress.org/tags/wp-followme?forum_id=10" title="Support for WP FollowMe plugin" target="_blank">Support for this plugin</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="side-info-column" class="inner-sidebar">
		<div class="postbox">
			<h3 class="hndle"><span>Wordpress</span></h3>
			<div class="inside">
				<ul>
					<li><a href="http://wordpress.org" title="Wordpress homepage" target="_blank">Wordpress homepage</a></li>
					<li><a href="http://wordpress.org/extend/plugins/browse/popular" title="Most popular Wordpress plugins" target="_blank">Most popular Wordpress plugins</a></li>
					<li><a href="http://codex.wordpress.org/Main_Page" title="Wordpress Help" target="_blank">Wordpress Codex</a></li>
				</ul>
			</div>
		</div>
	</div>

</div> <!--end of poststuff -->


</div> <!--end of float wrap -->
<?php
}
// adding admin menus
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'wp_followme_settings');
	add_action('admin_init', 'register_wp_followme_settings');
}
function register_wp_followme_settings() { // whitelist options
	register_setting('wp-followme-group', 'wp_followme_options');
}
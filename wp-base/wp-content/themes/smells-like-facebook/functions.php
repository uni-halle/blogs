<?php
$sidebars = (get_option('slf_sidebarad') == 1) ? 2 : 1;
register_sidebars($sidebars);

add_filter('the_excerpt', 'excerpt_filter');
function excerpt_filter($ex) {
	return str_replace('<br />', '', $ex);
}

add_filter('comment_text', 'slf_comment_text', 29);
function slf_comment_text($comment) {
	if(basename($_SERVER['SCRIPT_FILENAME']) == "wp-comments-post.php") {
		return $comment;
	} else {
		echo str_replace("<br>", "<br/>", nl2br($comment));
	}
}

function trim_text($text) {
	if ( '' != $text ) {
		$text = strip_tags($text);
		$excerpt_length = 20;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

add_action('init', 'slf_init');
function slf_init() {
	global $user_ID;
	get_currentuserinfo();
	
	add_option('slf_sidebarad', 0);
	add_option('slf_ajax', 1);
	add_option('slf_logo', "none");
	add_option('slf_logourl', '');
	add_option('slf_sidepictype', 1);
	add_option('slf_sidepicvalue', $user_ID);
	add_option('slf_showcats', 5);
	
}

if(!is_admin()) {
	add_action('init', 'slf_scripts');
	add_action('init', 'slf_sidebarad');
}

/* Javascript & AJAX stuffs */
function slf_scripts() {
	$use_ajax = (bool)get_option('slf_ajax');
	if($use_ajax) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('slf_autogrow', get_bloginfo('stylesheet_directory').'/jquery.autogrow.js');
		wp_enqueue_style('slf_ajaxcss', get_bloginfo('stylesheet_directory').'/ajax.css');
	}
	wp_enqueue_script('slf_js', get_bloginfo('stylesheet_directory').'/script.js');
}

function slf_sidebarad() {
	if(get_option('slf_sidebarad') == 1)
		wp_enqueue_style('slf_sidebarad', get_bloginfo('stylesheet_directory').'/sidebarad.css');
}

add_filter('next_posts_link_attributes', 'older_post_class');
function older_post_class() {
	return (is_archive() || is_home() || is_search()) ? "class='nextpost'" : "";
}

function slf_avatar() {
	global $wpdb;
	$slfsidepictype = get_option('slf_sidepictype');
	switch($slfsidepictype) {
		case "gravatar" :
			$uid = get_option('slf_sidepicvalue');
			$user = $wpdb->get_row("select user_email, display_name from $wpdb->users where ID = '$uid' limit 0,1");
			$img = get_avatar($user->user_email, '200');
		break;
		
		case "url":
			$url = get_option('slf_sidepicvalue');
			$img = "<img src='$url' alt='Avatar' />"; 
		break;
		
		case "none" :
		default:
			$img = "&nbsp;";
		break;
	}
	echo $img;
}

function slf_info() {
	global $wpdb;
	$slfsidepictype = get_option('slf_sidepictype');
	if($slfsidepictype == "gravatar") {
		$user = get_userdata(get_option('slf_sidepicvalue'));
		echo ($user->description != '') ? "<li class='user-desc'>$user->description</li>" : "";
	}
}

function slf_logo() {
	$slflogo = get_option('slf_logo');
	echo ($slflogo == 1) ? "<a href='".get_bloginfo('url')."'><img id='logo' src='".get_option('slf_logourl')."' alt='".get_bloginfo('name')."' /></a>" : "";
}

add_action('admin_menu', 'slf_add_theme_page');
function slf_add_theme_page() {
	if(isset($_POST['slfsave'])) {
		check_admin_referer('smells-like-facebook');
		
		update_option('slf_sidebarad', (int)($_POST['slfsidebar'] == 1));
		
		update_option('slf_ajax', (int)($_POST['slfajax'] == 1));
		
		$slflogo = ($_POST['slflogo'] == 1);
		$slflogourl = ($slflogo) ? $_POST['custslflogo'] : '';
		update_option('slf_logo', (int) $slflogo);
		update_option('slf_logourl', $slflogourl);
		
		switch($_POST['sidepic']) {
			case 0 :
				$sidepic['type'] = "none";
				$sidepic['value'] = "";
			break;
			case 1 :
				$sidepic['type'] = "gravatar";
				$sidepic['value'] = $_POST['sidegrav'];
			break;
			case 2 :
				$sidepic['type'] = "url";
				$sidepic['value'] = $_POST['custurl'];
			break;
			default :
				$sidepic['type'] = "gravatar";
				$sidepic['value'] = $wpdb->get_var("select ID from $wpdb->users where user_status = '0' limit 0,1");
			break;
		}
		update_option('slf_sidepictype', $sidepic['type']);
		update_option('slf_sidepicvalue', $sidepic['value']);
		
		$showcats = abs(intval($_POST['showcats']));
		update_option('slf_showcats', (($showcats == 0) ? 5 : $showcats));
		
		wp_redirect("themes.php?page=functions.php&saved=true");
	}
	add_theme_page(__('Smells Like Facebook options'), __('Smells Like Facebook options'), 'edit_themes', basename(__FILE__), 'slf_theme_page');
}

function slf_theme_page() {
	global $wpdb;
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>'; ?>
	<div class="wrap">
		<h2>Smells Like Facebook options</h2>
		<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
			<?php wp_nonce_field('smells-like-facebook'); ?>
			<table class="form-table">
				<tr>
					<?php $slfsidebar = get_option('slf_sidebarad'); ?>
					<th>Sidebar</th>
					<td>
						<label>
							<input type="checkbox" name="slfsidebar" <?php echo ($slfsidebar == 1) ? "checked='checked'" : ""; ?> value="1" />
							&nbsp;Enable right sidebar
						</label>
					</td>
				</tr>
				
				<tr>
					<?php $slfajax = get_option('slf_ajax'); ?>
					<th>Javascript & AJAX</th>
					<td>
						<label>
							<input type="checkbox" name="slfajax" <?php echo ($slfajax == 1) ? "checked='checked'" : ""; ?> value="1" />
							&nbsp;Enable Javascript & AJAX effect (using Wordpress jQuery)
						</label>
					</td>
				</tr>
				
				<tr>
					<?php $slflogo = get_option('slf_logo'); ?>
					<th>Site logo (which will be shown on the top left corner)</th>
					<td>
						<label><input type="radio" name="slflogo" <?php echo ($slflogo == 0) ? "checked='checked'" : ""; ?> value="0" /> Show no logo</label><br/><br/>
						<label><input type="radio" name="slflogo" <?php echo ($slflogo == 1) ? "checked='checked'" : ""; ?> value="1" /> Custom image URL: </label>
						<input name="custslflogo" value="<?php echo ($slflogo == 1) ? get_option('slf_logourl') : ""; ?>" /> (the image height should be 17 pixels with proporsional width)<br/><br/>
					</td>
				</tr>
				
				<tr>
					<th>Sidebar picture</th>
					<td>
						<?php $slfsidepic = get_option('slf_sidepictype'); ?>
						<label><input type="radio" name="sidepic" <?php echo ($slfsidepic == "none") ? "checked='checked'" : ""; ?> value="0" /> Show no images</label><br/><br/>
						
						<label><input type="radio" name="sidepic" <?php echo ($slfsidepic == "gravatar") ? "checked='checked'" : ""; ?> value="1" /> Show the <a href="http://gravatar.com" target="_blank">Gravatar</a> of </label>
						<select name="sidegrav">
						<?php
							$users = $wpdb->get_results("select * from $wpdb->users where user_status = '0'", ARRAY_A);
							foreach($users as $user) { ?>
								<option <?php echo (($slfsidepic == 1) && ($user['ID'] == get_option('slf_sidepicvalue'))) ? "selected='selected'" : ""; ?> value="<?php echo $user['ID']; ?>">
									<?php echo $user['display_name'].' ('.$user['user_email'].')' ?>
								</option>
						<?php }	?>
						</select> (the profile description of selected user will be displayed below the avatar)<br/><br/>
						
						<label><input type="radio" <?php echo ($slfsidepic == "url") ? "checked='checked'" : ""; ?> name="sidepic" value="2" /> Custom image URL: </label>
						<input name="custurl" value="<?php echo ($slfsidepic == "url") ? get_option('slf_sidepicvalue') : ""; ?>" /> (the image width should be 200 pixels)<br/><br/>
					</td>
				</tr>
				
				<tr>
					<th>Category tabs</th>
					<td>show <input name="showcats" value="<?php echo abs(intval(get_option('slf_showcats'))); ?>" size="2" /> category boxes</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" name="slfsave" value="&nbsp;&nbsp;<?php _e('Save'); ?>&nbsp;&nbsp;" />
			</p>
		</form>
		
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" style="border: none" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form> every donation is very helpful. Thanks!
	</div>
<?php
}
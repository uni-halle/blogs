<?php
/*
	Plugin Name: Instagram for Wordpress
	Plugin URI: http://wordpress.org/extend/plugins/instagram-for-wordpress/
	Description: Simple sidebar widget that shows Your latest 20 instagr.am pictures and picture embedder.
	Version: 0.4.8
	Author: jbenders
	Author URI: http://ink361.com/
*/

if(!defined('INSTAGRAM_PLUGIN_URL')) {
  define('INSTAGRAM_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
}

function wpinstagram_admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = plugins_url('wpinstagram-admin.css', __FILE__);
    wp_enqueue_style('wpinstagram-admin.css', $url);
}

add_action('admin_head', 'wpinstagram_admin_register_head');
add_shortcode('instagram', 'instagram_embed_shortcode');

function instagram_embed_shortcode($atts, $content = null){
	extract(shortcode_atts(array(
		'url' => '',
		'size' => 'middle',
		'addlink' => 'yes'
		), $atts));
	if(($url != '')&&(preg_match('/^http:\/\/instagr\.am\/p\/[a-zA-Z0-9-_]+\/$/', $url))):
		switch($size):
			case 'large':
				$maxwidth = 612;
				break;
			case 'small':
				$maxwidth = 150;
				break;
			case 'middle':
			default:
				$maxwidth = 306;
				break;
		endswitch;
		$response = wp_remote_get("http://instagr.am/api/v1/oembed/?url=".rawurlencode($url)."&maxwidth=".$maxwidth);
		if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200):
			$data = json_decode($response['body']);
			if($data->url):
				$html = '<img src="'.$data->url.'"'.($data->title!=''?' alt="'.$data->title.'"':'').' width="'.$maxwidth.'" height="'.$maxwidth.'" />';
				if($addlink == 'yes'):
					return '<a href="'.$url.'"'.($data->title!=''?' title="'.$data->title.'"':'').'>'.$html.'</a>';
				else:
					return $html;
				endif;
			endif;
		endif;
	endif;
	return null;
}
add_action('widgets_init', 'load_wpinstagram');
add_option('instagram-widget-client_id', null, false, false);
add_option('instagram-widget-client_secret', null, false, false);
add_option('instagram-widget-access_token', null, false, true);
add_option('instagram-widget-username', null, false, false);
add_option('instagram-widget-picture', null, false, false);
add_option('instagram-widget-fullname', null, false, false);
add_option('instagram-widget-cache', null, false, false);

function load_wpinstagram() {
	register_widget('WPInstagram_Widget');
}
function load_wpinstagram_footer(){
?>
<script>
	jQuery(document).ready(function($) {
		$("ul.wpinstagram").find("a").each(function(i, e) {
			e = $(e);
			e.attr('data-href', e.attr('href'));
			e.attr('href', e.attr('data-original'));
		});

		$("ul.wpinstagram").find("a").fancybox({
			"transitionIn":			"elastic",
			"transitionOut":		"elastic",
			"easingIn":				"easeOutBack",
			"easingOut":			"easeInBack",
			"titlePosition":		"over",
			"padding":				0,
			"hideOnContentClick":	"false",
			"type":					"image",
			titleFormat: 			function(x, y, z) {

				var html = '<div id="fancybox-title-over">';

				if (x && x.length > 0) {
					html += x + ' - ';
				}

				html += '<a href="http://ink361.com/">Instagram</a> web interface</div>';
				return html;
			}
		});

		jQuery('#fancybox-content').live('click', function(x) {

			var src = $(this).find('img').attr('src');
			var a = $("ul.wpinstagram").find('a[href="' + src + '"]').attr('data-user-url');
			
			document.getElementById('igTracker').src=$('ul.wpinstagram').find('a[href="' + src + '"]').attr('data-onclick');
			window.open(a, '_blank');
		})
	});
</script>
<?php
}
class WPInstagram_Widget extends WP_Widget {
	function WPInstagram_Widget(){
		if(in_array('fancybox-for-wordpress/fancybox.php',(array)get_option('active_plugins',array()))==false):
			$withfancybox = true;
		else:
			$withfancybox = false;
		endif;
		$widget_ops = array('description' => __('Displays latest instagrams.', 'wpinstagram'), 'withfancybox' => $withfancybox);
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'wpinstagram-widget');
		$this->WP_Widget('wpinstagram-widget', __('Instagram', 'wpinstagram'), $widget_ops, $control_ops);
		if(is_active_widget('','','wpinstagram-widget')&&!is_admin()):
			$this->wpinstagram_path = plugin_dir_url( __FILE__);
			wp_enqueue_script("jquery");
			wp_enqueue_script("jquery.easing", $this->wpinstagram_path."js/jquery.easing-1.3.pack.js", Array('jquery'), null);
			wp_enqueue_script("jquery.cycle", $this->wpinstagram_path."js/jquery.cycle.lite-1.5.min.js", Array('jquery'), null);
			wp_enqueue_style("wpinstagram", $this->wpinstagram_path."wpinstagram.css", Array(), '0.3.5');
			if($withfancybox):
				wp_enqueue_script("fancybox", $this->wpinstagram_path."js/jquery.fancybox-1.3.4.pack.js", Array('jquery'), null);
				wp_enqueue_style("fancybox-css", $this->wpinstagram_path."js/fancybox/jquery.fancybox-1.3.4.min.css?1", Array(), null);
				wp_enqueue_script("jquery.mousewhell", $this->wpinstagram_path."js/jquery.mousewheel-3.0.4.pack.js", Array('jquery'), null);
				add_action('wp_footer', 'load_wpinstagram_footer');
			endif;
		endif;
	}
	function widget($args, $instance) {
		extract( $args);
		$title = apply_filters('widget_title', $instance['title']);
		$cacheduration = (!intval($instance['cacheduration'])||$instance['cacheduration'] == "")?3600:$instance['cacheduration'];
		$cycletimeout = (!intval($instance['cycletimeout'])||$instance['cycletimeout'] == "")?4000:$instance['cycletimeout'];
		$instance['access_token'] = get_option('instagram-widget-access_token');
		$instance['client_id'] = get_option('instagram-widget-client_id');
		$instance['client_secret'] = get_option('instagram-widget-client_secret');


		if(isset($instance['access_token'])):
			$images = wp_cache_get($this->id, 'wpinstagram_cache');
			if(false == $images):
				$imageraw = get_option('instagram-widget-cache');
				
				if ($imageraw) {
					$imageraw = unserialize(base64_decode($imageraw));
					
					if (($imageraw['created'] + $cacheduration) > time()) {
						$images = $imageraw['data'];
					}
				}

				if (false == $images) {
					$images = $this->instagram_get_latest($instance);
					wp_cache_set($this->id, $images, 'wpinstagram_cache', $cacheduration);

					$tocache = array(
						'created' => time(),
						'data' => $images
					);
					
					update_option('instagram-widget-cache', base64_encode(serialize($tocache)));
				}
			endif;

			//var_dump($images);

			if(!empty($images)):
				echo $before_widget;
				if($title):
					echo $before_title.$title.$after_title;
				endif;
				if($instance['customsize'] != ""):
					$imagesize = intval($instance['customsize']);
					if($imagesize <= 150):
						$imagetype = "image_small";
					elseif($imagesize <= 306 && $imagesize > 150):
						$imagetype = "image_middle";
					elseif($imagesize > 306):
						$imagetype = "image_large";
					endif;
				else:
					switch($instance['size']):
						case 'large':
							$imagetype = "image_large";
							$imagesize = 612;
							break;
						case 'middle':
							$imagetype = "image_middle";
							$imagesize = 306;
							break;
						case 'small':
						default:
							$imagetype = "image_small";
							$imagesize = 150;
							break;
					endswitch;
				endif;
				$cls = 'wpinstagram';
				if ($instance['centered']):
					$cls .= ' centered';
				endif;
				echo '<ul class="'.$cls .'" style="width: '.$imagesize.'px; height: '.$imagesize.'px;">';
				foreach($images as $image):
					$imagesrc = $image[$imagetype];
					echo '<li><a href="http://ink361.com/p/'.$image['id'].'" data-user-url="http://ink361.com/#/photo/'.$image['id'].'" data-original="'.$image['image_large'].'" title="'.$image['title'].'" rel="'.$this->id.'" data-onclick="http://plugin.ink361.com/photo.html?id='.$image['id'].'">';
					echo '<img src="'.$imagesrc.'" alt="'.$image['title'].'" width="'.$imagesize.'" height="'.$imagesize.'" />';
					echo '</a></li>';
				endforeach;
				echo '</ul>';
?>
<script>
jQuery(document).ready(function($) {
	$("#<?php echo $this->id; ?> ul").cycle({fx: "fade", timeout: <?php echo $cycletimeout; ?>});
});
</script>
<?php				echo $after_widget;
			endif;
		endif;
	}
	function instagram_login($login, $pass){
		$redirect_uri = INSTAGRAM_PLUGIN_URL . '/authenticationhandler.php';
		return $redirect_uri;
	}

	function instagram_get_latest($instance){
		$images = array();
		if($instance['access_token'] != null):
			if(isset($instance['hashtag']) && trim($instance['hashtag']) != "" && preg_match("/[a-zA-Z0-9_\-]+/i", $instance['hashtag'])):
				$hashtag = $instance['hashtag'];
				if (substr($hashtag, 0, 1) == '#'):
					$hashtag = substr($hashtag, 1);
				endif;
				$apiurl = "https://api.instagram.com/v1/tags/".$hashtag."/media/recent?count=".$instance['count']."&access_token=".$instance['access_token'];
			else:
				$apiurl = "https://api.instagram.com/v1/users/self/media/recent?count=".$instance['count']."&access_token=".$instance['access_token'];
			endif;
			$response = wp_remote_get($apiurl,
				array(
					'sslverify' => apply_filters('https_local_ssl_verify', false)
				)
			);
			if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200):
				$data = json_decode($response['body']);
				if($data->meta->code == 200):
					foreach($data->data as $item):
						if(isset($instance['hashtag'], $item->caption->text)):
							$image_title = $item->user->username.': &quot;'.filter_var($item->caption->text, FILTER_SANITIZE_STRING).'&quot;';
						elseif(isset($instance['hashtag']) && !isset($item->caption->text)):
							$image_title = "instagram by ".$item->user->username;
						else:
							$image_title = filter_var($item->caption->text, FILTER_SANITIZE_STRING);
						endif;
						$images[] = array(
							"id" => $item->id,
							"title" => $image_title,
							"image_small" => $item->images->thumbnail->url,
							"image_middle" => $item->images->low_resolution->url,
							"image_large" => $item->images->standard_resolution->url
						);
					endforeach;
				endif;
			endif;
		endif;
		return $images;
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$loggedout = false;
		if(isset($new_instance['dologout']) && $new_instance['dologout'] == 1):
			$loggedout = true;
			$instance['access_token'] = null;
			$instance['login'] = null;
			delete_option('instagram-widget-access_token');
			wp_cache_delete($this->id, 'wpinstagram_cache');
		endif;
		if(isset($new_instance['client_id'], $new_instance['client_secret']) && !$loggedout && 
				trim($new_instance['client_id']) != "" && trim($new_instance['client_secret']) != ""):
			wp_cache_delete($this->id, 'wpinstagram_cache');
			$auth = $this->instagram_login($new_instance['client_id'], $new_instance['client_secret']);
		endif;
		if(($new_instance['count'] != $old_instance['count'])||($new_instance['hashtag'] != $instance['hashtag'])):
			wp_cache_delete($this->id, 'wpinstagram_cache');
		endif;
		if(preg_match("/[a-zA-Z0-9_\-]+/i", $new_instance['hashtag'])):
			$instance['hashtag'] = $new_instance['hashtag'];
		else:
			unset($instance['hashtag']);
		endif;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['size'] = strip_tags($new_instance['size']);
		$instance['customsize'] = strip_tags($new_instance['customsize']);
		$instance['cycletimeout'] = strip_tags($new_instance['cycletimeout']);
		$instance['cacheduration'] = strip_tags($new_instance['cacheduration']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['customsize'] = intval($instance['customsize'])?$instance['customsize']:"";
		$instance['count'] = intval($instance['count'])?$instance['count']:20;
		$instance['centered'] = intval($new_instance['centered'])==1?true:false;
		$instance['cycletimeout'] = intval($instance['cycletimeout'])?$instance['cycletimeout']:4000;
		$instance['cacheduration'] = intval($instance['cacheduration'])?$instance['cacheduration']:3600;
		$instance['client_id'] = strip_tags($new_instance['client_id']);
		$instance['client_secret'] = strip_tags($new_instance['client_secret']);
		$instance['loggedout'] = $loggedout;
		
		if (!$loggedout):
			$instance['access_token'] = get_option('instagram-widget-access_token');
		endif;

		if (isset($auth)):
			$instance['redirecturi'] = $auth;
		endif;

		update_option('instagram-widget-client_id', $instance['client_id']);
		update_option('instagram-widget-client_secret', $instance['client_secret']);
	
		return $instance;
	}

	function form($instance ) {
		$defaults = array(
			'title' => __('My instagrams', 'wpinstagram'),
			'size' => __('small', 'wpinstagram'),
			'customsize' => __('', 'wpinstagram'),
			'cycletimeout' => __('4000', 'wpinstagram'),
			'cacheduration' => __('3600', 'wpinstagram'),
			'client_id' => __('', 'wpinstagram'),
			'client_secret' => __('', 'wpinstagram'),
			'hashtag' => __('', 'wpinstagram'),
			'dologout' => __('0', 'wpinstagram'),
			'count' => __('20', 'wpinstagram'),
			'centered' => __('false', 'wpinstagram'),
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		$instance['access_token'] = get_option('instagram-widget-access_token');
		$hasaccesstoken = isset($instance['access_token']) && strlen($instance['access_token']) > 0;
		
		$client_id_error = false;
		$client_secret_error = false;

		$openedpopup = false;

		if (!$instance['loggedout'] && isset($instance['redirecturi']) && 
				!$hasaccesstoken && $_SERVER['REQUEST_METHOD'] == 'POST'):

			if (strlen($instance['client_id']) != 32 || preg_match('[^0-9a-f]', $instance['client_id'])):
				$client_id_error = true;
			endif;

			if (strlen($instance['client_secret']) != 32 || preg_match('[^0-9a-f]', $instance['client_secret'])):
				$client_secret_error = true;
			endif;
			$openedinstagrampopup = false;
			if (!$client_id_error && !$client_secret_error):
				$openedinstagrampopup = true;
			?><script type="text/javascript">
				auth_instagramforwordpress = function() {
					var url = 'https://api.instagram.com/oauth/authorize/' 
						+ '?redirect_uri=' + encodeURIComponent("<?php echo $instance['redirecturi']; ?>")
						+ '&response_type=code' 
						+ '&client_id=<?php echo $instance["client_id"]; ?>'
						+ '&display=touch';

					window.open(url, 'wp-instagram-authentication-' + Math.random(), 'height=500,width=600');
				}
				
				auth_instagramforwordpress();
			</script><?php

			endif;
		endif; 
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wpinstagram'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Picture size:', 'wpinstagram'); ?></label>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
				<option value="small"<?php echo ($instance['size']=='small'?' selected':''); ?>>small (150x150px)</option>
				<option value="middle"<?php echo ($instance['size']=='middle'?' selected':''); ?>>middle (306x306px)</option>
				<option value="large"<?php echo ($instance['size']=='large'?' selected':''); ?>>large (612x612px)</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('customsize'); ?>"><?php _e('.. or custom picture size:', 'wpinstagram'); ?>
				<input id="<?php echo $this->get_field_id('customsize'); ?>" name="<?php echo $this->get_field_name('customsize'); ?>" type="text" size="3" value="<?php echo $instance['customsize']; ?>" /> px
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('A number of latest photos to show (by default 20):', 'wpinstagram'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>" value="<?php echo $instance['count'];?>" size="6" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cacheduration'); ?>"><?php _e('Cache duration (in seconds, default 3600):', 'wpinstagram'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('cacheduration'); ?>" id="<?php echo $this->get_field_id('cacheduration'); ?>" value="<?php echo $instance['cacheduration'];?>" size="6" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cycletimeout'); ?>"><?php _e('Cycle timeout (in miliseconds, default 4000):', 'wpinstagram'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('cycletimeout'); ?>" id="<?php echo $this->get_field_id('cycletimeout'); ?>" value="<?php echo $instance['cycletimeout'];?>" size="6" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('centered'); ?>"><?php _e('Center widget:', 'wpinstagram'); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('centered'); ?>" id="<?php echo $this->get_field_id('centered'); ?>" <?php if ($instance['centered']) { echo 'checked'; } ?> value="1" size="6" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('client_id'); ?>"><?php _e('Instagram client_id:', 'wpinstagram'); ?></label>
			<input id="<?php echo $this->get_field_id('client_id'); ?>" name="<?php echo $this->get_field_name('client_id'); ?>" type="text" value="<?php echo $instance['client_id'];?>" class="widefat" />

			<?php if ($client_id_error): ?>
				<div class="error">
					Your client_id must be exactly 32 characters and 
					only contain the characters 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, a, b, c, d, e and/or f.
					You can find the client_id on 
					<a href="http://instagram.com/developer/clients/manage/" target="_blank">
						http://instagram.com/developer/clients/manage/
					</a> if you registered a client previously. If not, please follow the instructions below
				</div>
			<?php endif; ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('client_secret'); ?>"><?php _e('Client_secret:', 'wpinstagram'); ?></label>
			<input id="<?php echo $this->get_field_id('client_secret'); ?>" name="<?php echo $this->get_field_name('client_secret'); ?>" type="text" value="<?php echo $instance['client_secret'];?>" class="widefat" />

			<?php if ($client_secret_error): ?>
				<div class="error">
					Your client_secret must be exactly 32 characters and 
					only contain the characters 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, a, b, c, d, e and/or f.

					You can find the client_secret on 
					<a href="http://instagram.com/developer/clients/manage/" target="_blank">
						http://instagram.com/developer/clients/manage/
					</a> if you registered a client previously. If not, please follow the instructions below
				</div>
			<?php endif; ?>
		</p>
		<?php if(!isset($instance['access_token']) || strlen($instance['access_token']) == 0): ?>
		
		<?php if ($openedinstagrampopup): ?>
			<p>
				<strong>
					A popup has been opened but it might have been blocked by your browser. 
					If you did not see this popup which is required for authenticating you with instagram,
					<a href="javascript:auth_instagramforwordpress()">please press here</a>
				</strong>
			</p>
		<?php endif; ?>
		<p>
			Need help registering an instagram client? Follow the instructions below.
		</p>

		<div id="instagram-plugin-instructions">
			<h3>How to get your Instagram Client ID</h3>

			<ol>
				<li>
					Visit <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a>
					and click on Manage Clients (top right hand corner)
					<br>
					<br>
					<img src="<?php echo plugins_url('img/1.png', __FILE__); ?>" width="280">
				</li>

				<li>
					Click on the Register a New Client button (top right hand corner again)
					<br>
					<br>
					<img src="<?php echo plugins_url('img/2.png', __FILE__); ?>">
				</li>

				<li>
					Fill in the register new OAuth Client form with:

					<dl>
						<dt>Application name</dt>
						<dd>Name of your website</dd>
						
						<dt>Description</dt>
						<dd>Instagram wordpress plugin</dd>
						
						<dt>Website</dt>
						<dd>Your website url</dd>
						
						<dt>OAuth redirect_url</dt>
						<dd><textarea><?php echo INSTAGRAM_PLUGIN_URL . '/authenticationhandler.php'; ?></textarea></dd>

					</dl>

					<br>
					<br>
					<img src="<?php echo plugins_url('img/3.png', __FILE__); ?>">
				</li>
			</ol>
		</div>
		<?php if ($openedinstagrampopup): ?>
			<p>
				<strong>
					A popup has been opened but it might have been blocked by your browser. 
					If you did not see this popup which is required for authenticating you with instagram,
					<a href="javascript:auth_instagramforwordpress()">please press here</a>
				</strong>
			</p>
		<?php endif; ?>
		<?php else: ?>
		<p>
			<label for="<?php echo $this->get_field_id('access_token'); ?>"><?php _e('Access_token:', 'wpinstagram'); ?></label>
			<span><?php echo $instance['access_token'];?></span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('hashtag'); ?>"><?php _e('Show latest public instagrams with following hashtag (without "#"; if empty, will show your recent instagrams):', 'wpinstagram'); ?></label>
			<input id="<?php echo $this->get_field_id('hashtag'); ?>" name="<?php echo $this->get_field_name('hashtag'); ?>" type="text" value="<?php echo $instance['hashtag'];?>" class="widefat" />
		</p>
		<p>
			<input type="hidden" value="0" name="<?php echo $this->get_field_name('dologout'); ?>" id="<?php echo $this->get_field_id('dologout'); ?>" />
			<label for="<?php echo $this->get_field_id('logoutbutton'); ?>"><?php _e('Logged in as: ', 'wpinstagram'); echo $instance['login']; ?></label>
			<a id="<?php echo $this->get_field_id('logoutbutton'); ?>" class="button-secondary"><?php _e('Logout from Instagram', 'wpinstagram'); ?></a>
			<script>
				jQuery(document).ready(function($){
					$("#<?php echo $this->get_field_id('logoutbutton'); ?>").click(function(){
						$("#<?php echo $this->get_field_id('dologout'); ?>").val("1");
						$(this).parents("form").find("input[type=submit]").click();
						return false;
					});
				});
			</script>
		</p>
		<?php endif; ?>
	<?php
	}
}
?>

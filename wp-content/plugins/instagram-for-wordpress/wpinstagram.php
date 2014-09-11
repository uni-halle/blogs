<?php
/*
	Plugin Name: Instagram for Wordpress
	Plugin URI: http://wordpress.org/extend/plugins/instagram-for-wordpress/
	Description: Comprehensive Instagram sidebar widget with many options.
	Version: 1.0.6
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
add_action('widgets_init', 'load_wpinstagram');
add_option('instagram-widget-cache', null, false, false);
add_option('wpaccount', null, false, false);

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

                	$("ul.wpinstagram.live").find("a.mainI").fancybox({
        	                "transitionIn":                 "elastic",
	                        "transitionOut":                "elastic",
                        	"easingIn":                     "easeOutBack",
                	        "easingOut":                    "easeInBack",
        	                "titlePosition":                "over",   
	                        "padding":                              0,
                        	"hideOnContentClick":   "false",
                	        "type":                                 "image",   
        	                titleFormat:                    function(x, y, z) {
	
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
	                        var a = $("ul.wpinstagram.live").find('a.[href="' + src + '"]').attr('data-user-url');
                  		
                	        document.getElementById('igTracker').src=$('ul.wpinstagram').find('a[href="' + src + '"]').attr('data-onclick');
        	                window.open(a, '_blank');
	                })
        	});
	</script>
	<?php
}

class WPInstagram_Widget extends WP_Widget {
	function WPInstagram_Widget($args=array()){
		$width = '220';
		$height = '220';
	
		$widget_ops = array('description' => __('Displays Instagrams', 'wpinstagram'));		
		$control_ops = array('id_base' => 'wpinstagram-widget');
	
		$this->wpinstagram_path = plugin_dir_url( __FILE__);
		$this->WP_Widget('wpinstagram-widget', __('Instagram Widget', 'wpinstagram'), $widget_ops, $control_ops);
		
		$withfancybox = false;
		if(in_array('fancybox-for-wordpress/fancybox.php',(array)get_option($this->id . 'active_plugins',array()))==false) {
                        $withfancybox = true;
		}                

		if (is_active_widget('', '', 'wpinstagram-widget') && !is_admin()) {		
			wp_enqueue_script("jquery");
		        wp_enqueue_script("jquery.easing", $this->wpinstagram_path."js/jquery.easing-1.3.pack.js", Array('jquery'), null);
		        wp_enqueue_script("jquery.cycle", $this->wpinstagram_path."js/jquery.cycle.all.js", Array('jquery'), null);
			wp_enqueue_style('wpinstagram', $this->wpinstagram_path . 'wpinstagram.css', Array(), '0.5');		
			if ($withfancybox) {
				wp_enqueue_script("fancybox", $this->wpinstagram_path."js/jquery.fancybox-1.3.4.pack.js", Array('jquery'), null);
	                        wp_enqueue_style("fancybox-css", $this->wpinstagram_path."js/fancybox/jquery.fancybox-1.3.4.min.css?1", Array(), null);
	                        wp_enqueue_script("jquery.mousewhell", $this->wpinstagram_path."js/jquery.mousewheel-3.0.4.pack.js", Array('jquery'), null);
	                        add_action('wp_footer', 'load_wpinstagram_footer');
			}
		}
	}
		
	function widget($args, $instance) {
		extract($args);
		
		echo $before_widget;
		
		#determine our settings
		$settings = get_option($this->id . 'settings');
		$updated = (int) get_option($this->id . 'last_updated');
		
		#settings cache of 1 hour
		
		if (!$settings || !$updated || ($updated < (time() - 3600))) {
			#grab our settings
			$response = wp_remote_get("http://wordpress.ink361.com/fetch?widget=" . get_option($this->id . 'token'));
		
			if (is_wp_error($response) || !(($response['response']['code'] < 400 && $response['response']['code'] >= 200))) {
				#oh dear, better log something here
			} else {
				$data = json_decode($response['body'], true);
								
				if ($data['data'] && $data['data']['settings'] && $data['data']['settings']) {
					update_option($this->id . 'settings', $data['data']['settings']);
					update_option($this->id . 'last_updated', time());
					
					$settings = get_option($this->id . 'settings');
				}
			}			
		}
		
		if (!$settings['display']) {
			$settings['display'] = 'self';
		}
		
		if (!$settings['access_token']) {
			#its no use, display nothing
			return null;
		}
		
		#have we got a cache?
		$cached = json_decode(get_option($this->id . 'result_cache'), true);
		$cache_time = get_option($this->id . 'cache_time');
		$shown = false;
		
		if ($cached && $cache_time && $cache_time > (time() - 3600)) {
			$shown = $this->_display_results($cached, $settings, true);
		}

		if (!$shown) {
			#what display type
			if ($settings['display'] == 'self') {
				#our own pictures
				$this->_display_user('self', $settings);
			} else if ($settings['display'] == 'tag' && $settings['tag']) {
				#a single tag
				$this->_display_tag($settings['tag'], $settings);
			} else if ($settings['display'] == 'likes') {
				#our likes
				$this->_display_likes($settings);
			} else if ($settings['display'] == 'feed') {
				#our feed
				$this->_display_feed($settings);
			} else if ($settings['display'] == 'popular') {
				$this->_display_popular($settings);
			} else if ($settings['display'] == 'user' && $settings['user']) {
				#another users photos
				$this->_display_user($settings['user'], $settings);			
			} else if ($settings['display'] == 'tags') {
				#multiple tags
				$this->_display_tags($settings['tag1'], $settings['tag2'], $settings['tag3'], $settings['tag4'], $settings);
			} else {
				#no idea
			}
		}
		
		echo $after_widget;
	}

	function _display_popular($settings) {
		$images = array();

		if ($settings['access_token']) {
			$url = "https://api.instagram.com/v1/media/popular?count=50&access_token=" . $settings['access_token'];
			
			$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
			if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body'], true);
				if ($data['meta']['code'] == 200) {
					foreach ($data['data'] as $item) {
						if (isset($item['caption']['text'])) {
							$image_title = $item['user']['username'] . ': &quot;' . filter_var($item['caption']['text'], FILTER_SANITIZE_STRING) . '&quot;';
						} else if (!isset($item['caption']['text'])) {
							$image_title = "Instagram by " . $item['user']['username'];
						}
						
						$images[] = array(
							"id"		=> $item['id'],
							"title"		=> $image_title,
							"image_small"	=> $item['images']['thumbnail']['url'],
							"image_middle"	=> $item['images']['low_resolution']['url'],
							"image_large"	=> $item['images']['standard_resolution']['url'],
						);
					}
				}							
			}			
		}
		
		return $this->_display_results($images, $settings, false);
	}
		
	function _display_feed($settings) {
		$images = array();

		if ($settings['access_token']) {
			$url = "https://api.instagram.com/v1/users/self/feed?count=50&access_token=" . $settings['access_token'];
			
			$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
			if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body'], true);
				if ($data['meta']['code'] == 200) {
					foreach ($data['data'] as $item) {
						if (isset($item['caption']['text'])) {
							$image_title = $item['user']['username'] . ': &quot;' . filter_var($item['caption']['text'], FILTER_SANITIZE_STRING) . '&quot;';
						} else if (!isset($item['caption']['text'])) {
							$image_title = "Instagram by " . $item['user']['username'];
						}
						
						$images[] = array(
							"id"		=> $item['id'],
							"title"		=> $image_title,
							"image_small"	=> $item['images']['thumbnail']['url'],
							"image_middle"	=> $item['images']['low_resolution']['url'],
							"image_large"	=> $item['images']['standard_resolution']['url'],
						);
					}
				}							
			}			
		}
		
		return $this->_display_results($images, $settings, false);
	}
	
	function _display_likes($settings) {
		$images = array();
		
		if ($settings['access_token']) {
			$url = "https://api.instagram.com/v1/users/self/media/liked?count=50&access_token=" . $settings['access_token'];
			
			$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
			if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body'], true);
				if ($data['meta']['code'] == 200) {
					foreach ($data['data'] as $item) {
						if (isset($item['caption']['text'])) {
							$image_title = $item['user']['username'] . ': &quot;' . filter_var($item['caption']['text'], FILTER_SANITIZE_STRING) . '&quot;';
						} else if (!isset($item['caption']['text'])) {
							$image_title = "Instagram by " . $item['user']['username'];
						}
						
						$images[] = array(
							"id"		=> $item['id'],
							"title"		=> $image_title,
							"image_small"	=> $item['images']['thumbnail']['url'],
							"image_middle"	=> $item['images']['low_resolution']['url'],
							"image_large"	=> $item['images']['standard_resolution']['url'],
						);
					}
				}							
			}			
		}
		
		return $this->_display_results($images, $settings, false);
	}

	function _display_user($user, $settings) {
		$images = array();
		$user = str_replace("ig-", "", $user);
		
		if ($settings['access_token']) {
			$url = "https://api.instagram.com/v1/users/" . $user . "/media/recent?count=50&access_token=" . $settings['access_token'];
			
			$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
			if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body'], true);
				if ($data['meta']['code'] == 200) {
					foreach ($data['data'] as $item) {
						if (isset($item['caption']['text'])) {
							$image_title = $item['user']['username'] . ': &quot;' . filter_var($item['caption']['text'], FILTER_SANITIZE_STRING) . '&quot;';
						} else if (!isset($item['caption']['text'])) {
							$image_title = "Instagram by " . $item['user']['username'];
						}
						
						$images[] = array(
							"id"		=> $item['id'],
							"title"		=> $image_title,
							"image_small"	=> $item['images']['thumbnail']['url'],
							"image_middle"	=> $item['images']['low_resolution']['url'],
							"image_large"	=> $item['images']['standard_resolution']['url'],
						);
					}
				}							
			}
		}
		
		return $this->_display_results($images, $settings, false);
	}
	
	function _display_tags($tag1, $tag2, $tag3, $tag4, $settings) {
		$images = array();
		
		#BEHOLD MY EFFICIENT WAY OF GETTING MANY TAGS!
		if ($tag1 && $tag1 != '' && $tag1 != 'None') {
			$images += $this->_get_tagged_photos($tag1, $settings);
		}
		if ($tag2 && $tag2 != '' && $tag2 != 'None') {
			$images += $this->_get_tagged_photos($tag2, $settings);
		}
		if ($tag3 && $tag3 != '' && $tag3 != 'None') {
			$images += $this->_get_tagged_photos($tag3, $settings);
		}
		if ($tag4 && $tag4 != '' && $tag4 != 'None') {
			$images += $this->_get_tagged_photos($tag4, $settings);
		}
		
		#jumble them up
		shuffle($images);
		
		return $this->_display_results($images, $settings, false);
	}
	
	function _display_tag($tag, $settings) {
		
		return $this->_display_results($this->_get_tagged_photos($tag, $settings), $settings, false);
	}
	
	function _get_tagged_photos($tag, $settings) {
		#tidy up our tag
		$tag = str_replace("#", "", $tag);
		$images = array();
		
		if ($settings['access_token']) {
			$url = "https://api.instagram.com/v1/tags/" . $tag . "/media/recent?count=50&access_token=" . $settings['access_token'];
			
			$response = wp_remote_get($url, array('sslverify' => apply_filters('https_local_ssl_verify', false)));
			if (!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body'], true);
				if ($data['meta']['code'] == 200) {
					foreach ($data['data'] as $item) {
						if (isset($item['caption']['text'])) {
							$image_title = $item['user']['username'] . ': &quot;' . filter_var($item['caption']['text'], FILTER_SANITIZE_STRING) . '&quot;';
						} else if (!isset($item['caption']['text'])) {
							$image_title = "Instagram by " . $item['user']['username'];
						}
						
						$images[] = array(
							"id"		=> $item['id'],
							"title"		=> $image_title,
							"image_small"	=> $item['images']['thumbnail']['url'],
							"image_middle"	=> $item['images']['low_resolution']['url'],
							"image_large"	=> $item['images']['standard_resolution']['url'],
						);
					}
				}				
			}
		}	
		
		return $images;
	}
	
	function _display_results($images, $settings, $fromCache) {
		#now lets cache our images		
		if (!$fromCache) {
			update_option($this->id . 'result_cache', json_encode($images));
			update_option($this->id . 'cache_time', time());
		}
		
		if (!$settings['method'] || $settings['method'] == 'grid') {
			require(plugin_dir_path(__FILE__) . 'templates/grid.php');								
		} else if ($settings['method'] == 'grid-page') {
			require(plugin_dir_path(__FILE__) . 'templates/gridPage.php');										
		} else if ($settings['method'] == 'slideshow') {
			require(plugin_dir_path(__FILE__) . 'templates/slideshow.php');										
		}
		
		if (sizeof($images) > 0) {		
			return true;
		} else {
			return false;
		}
	}

	function update($new_instance, $old_instance){
		$instance = $new_instance;
		
		update_option($this->id . 'settings', null);
		update_option($this->id . 'result_cache', null);
	
		return $instance;
	}

	function form($instance) {
		#new version
		
		if (!array_key_exists('token', $instance) || !$instance['token']) {
			$instance['token'] = get_option($this->id . 'token');			
		}
		
		if (!array_key_exists('token', $instance) || !$instance['token']) {
			#configure our options - wordpress needs a way to save an instances values directly, it just doesn't work right now		
			add_option($this->id . 'token', null, false, false);
			add_option($this->id . 'account', null, false, false);
			add_option($this->id . 'setup', null, false, false);
			add_option($this->id . 'waiting', null, false, false);
			add_option($this->id . 'settings', null, false, false);
			add_option($this->id . 'last_updated', null, false, false);
			add_option($this->id . 'result_cache', null, false, false);
			add_option($this->id . 'cache_time', null, false, false);
			
			#issue with token, get token
			
			$account = get_option('wpaccount');
			$url = "http://wordpress.ink361.com/init";			
			if ($account) {
				$url .= "?account=" . $account;
			}			
			$response = wp_remote_get($url);
			
			if (is_wp_error($response) || !(($response['response']['code'] < 400 && $response['response']['code'] >= 200))) {
				require(plugin_dir_path(__FILE__) . 'templates/initError.php');
				return;	
			} else {
				#its all good. Thanks for making me write php
				$data = json_decode($response['body'], true);

				if ($data['meta'] && $data['meta']['code'] && $data['meta']['code'] == "200") {
					#nice, lets see if our token is in there
					if ($data['data'] && $data['data']['widget'] && $data['data']['account']) {
						#good, lets save that mother
						$instance['token'] = $data['data']['widget'];
						$instance['account'] = $data['data']['account'];
						
						update_option($this->id . 'token', $instance['token']);
						update_option('wpaccount', $instance['account']);
					} else {
						require(plugin_dir_path(__FILE__) . 'templates/initError.php');								
						return;	
					}				
				} else {
					require(plugin_dir_path(__FILE__) . 'templates/initError.php');				
					return;
				}
			}
		}
		
		if (!array_key_exists('setup', $instance) || !$instance['setup']) {
			$instance['setup'] = get_option($this->id . 'setup');
		}		
		if (!array_key_exists('waiting', $instance) || !$instance['waiting']) {
			$instance['waiting'] = get_option($this->id . 'waiting');
		}

		if (!array_key_exists('setup', $instance) || !$instance['setup']) {
			#fetch out widget settings to determine if its been setup
			$response = wp_remote_get("http://wordpress.ink361.com/fetch?widget=" . $instance['token']);
		
			if (is_wp_error($response) || !(($response['response']['code'] < 400 && $response['response']['code'] >= 200))) {
				require(plugin_dir_path(__FILE__) . 'templates/initError.php');
				return;
			} else {
				$data = json_decode($response['body'], true);
				if ($data['data'] && $data['data']['settings'] && $data['data']['settings']['setup']) {
					$instance['setup'] = true;
					update_option($this->id . 'setup', $instance['setup']);
				} else if ($data['data'] && $data['data']['settings'] && $data['data']['settings']['access_token']) {
					$instance['waiting'] = true;
					update_option($this->id . 'waiting', $instance['waiting']);			
				}
			}
		}
		
		wp_enqueue_script("jquery");
		wp_enqueue_script("lightbox", plugin_dir_url(__FILE__)."js/lightbox.js", Array('jquery'), null);
				
		if (!array_key_exists('setup', $instance) || !$instance['setup']) {				
			require(plugin_dir_path(__FILE__) . 'templates/setupButton.php');
		} else {
			require(plugin_dir_path(__FILE__) . 'templates/configureButton.php');
		}				
		
		return;
	}
}
?>

<?php
/*
Plugin Name: Ada WPMS Sitewide Feed
Plugin URI: http://1uthavi.adadaa.com/ada-wpmu-sitewide-feed-plugin/
Description: Creates four rss 2.0 feeds showing recent posts, comments, pages, and one combined [posts and pages] from all blogs.  This will skip the first comment and page of a blog; also will not include spam, mature and deleted blogs.
Author:  CAPitalZ
Author URI: http://1uthavi.adadaa.com/
Version: 0.5.2
License: GPL
*/

/*--------------------------------------------------------------------------------------------------
 Ver                Name                     Description
 ===================================================================================================
 0.5.2              CAPitalZ				Made it to work both in WPMU and WP 3.0
 0.5.1              Cyril AKNINE			Fixed many bug issues with WP 3.0
 0.5.0				CAPitalZ				Optimized, bug fixed WP Object Cache, added display of 
 											site logo, inclution of author avatars
 0.4.0				CAPitalZ				Optimized, bug fixed WP Object Cache, added an additional feed
 0.3.2              I.T. Damager			Original creator        
--------------------------------------------------------------------------------------------------*/

class wpmu_sitefeed {

	var $version = '0.5.2';
		
	function wpmu_sitefeed() {
		add_action('init', array(&$this, 'wpmu_sitefeed_init'));
	}
	//if (!function_exists('is_subdomain_install')) :
	function is_subdomain_install() {
		if ( defined('SUBDOMAIN_INSTALL') )
			return SUBDOMAIN_INSTALL;

		if ( defined('VHOST') && VHOST == 'yes' )
			return true;

		return false;
	}
	//endif;


	function wpmu_sitefeed_init() {
		$this->apply_settings();
		if ($this->cache) $this->cache = $this->check_cache();
		if ($this->trigger('fullfeed')) return $this->outputfeed('fullfeed');
		elseif ($this->trigger('posts')) return $this->outputfeed('posts');
		elseif ($this->trigger('comments')) return $this->outputfeed('comments');
		elseif ($this->trigger('pages')) return $this->outputfeed('pages');
		add_action('publish_post', array(&$this, 'expire_post_feeds'));
		add_action('delete_post', array(&$this, 'expire_post_feeds'));
		add_action('private_to_published', array(&$this, 'expire_post_feeds'));
		add_action('comment_post', array(&$this, 'expire_comments_feed'));
		add_action('delete_comment', array(&$this, 'expire_comments_feed'));
		add_action('trackback_post', array(&$this, 'expire_comments_feed'));
		add_action('wp_set_comment_status', array(&$this, 'expire_comments_feed'));
		add_action('wpmuadminedit', array(&$this, 'expire_feeds')); // in case the admin deletes a blog
		
		if ( defined('MULTISITE') )
			add_action('network_admin_menu', array(&$this, 'add_submenu'));
		else
			add_action('admin_menu', array(&$this, 'add_submenu'));
	}

	function trigger($type) {
		global $wpdb;	//global $blog_id doesn't work!
		if ($wpdb->blogid != $this->triggerblog) return false;
		
		if ($type == 'fullfeed') $url = $this->triggerurl;
		elseif ($type == 'posts') $url = $this->triggerurl.$this->postsurl;
		elseif ($type == 'comments') $url = $this->triggerurl.$this->commentsurl;
		elseif ($type == 'pages') $url = $this->triggerurl.$this->pagesurl;
		if( $this->is_subdomain_install() ) {
			return (substr($_SERVER['REQUEST_URI'], strlen($url)*-1) == $url) ? true : false;
		} else {
			if ($type == 'fullfeed' && $_GET['wpmu-feed'] == 'full-feed') return true;
			elseif ($type == 'posts' && $_GET['wpmu-feed'] == 'posts') return true;
			elseif ($type == 'comments' && $_GET['wpmu-feed'] == 'comments') return true;
			elseif ($type == 'pages' && $_GET['wpmu-feed'] == 'pages') return true;
			return false;
		}
	}

	function check_cache() {
		global $wp_object_cache;
		//return (is_object($wp_object_cache) && $wp_object_cache->cache_enabled == true) ? true : false;
		return (is_object($wp_object_cache)) ? true : false;
	}

	function cache_expire_time() {
		global $wp_object_cache;
		if(property_exists('wp_object_cache', 'expiration_time'))
			return ($wp_object_cache->expiration_time/60);
		else
			return (15);
	}

	function add_submenu() {
		if (!is_site_admin()) return false;
	
		if ( defined('MULTISITE') )
			add_submenu_page('settings.php', 'Ada Sitewide Feed Configuration', 'Ada Sitewide Feed', 10, 'wpmu_sitewide_feed', array(&$this,'config_page'));
		else
			add_submenu_page('wpmu-admin.php', 'Ada Sitewide Feed Configuration', 'Ada Sitewide Feed', 10, 'wpmu_sitewide_feed', array(&$this,'config_page'));
		

	}

	function save_settings() {
		global $wpdb, $wp_db_version, $updated, $configerror;
		$changed = false;
		
		check_admin_referer();
		// validate all input!
		if (preg_match('/^[0-9]+$/',$_POST['triggerblog']) && $_POST['triggerblog'] > 0) $triggerblog = intval($_POST['triggerblog']);
		else $configerror[] = 'Trigger blog must be a numeric blog ID. Default: 1';

		if (preg_match('/^\/[a-zA-Z0-9_\/\-]+\/$/',$_POST['triggerurl']) || ! $this->is_subdomain_install()) $triggerurl = $_POST['triggerurl'];
		else $configerror[] = 'Invalid trigger URL. Must be a relative path beginning with and ending with a "/". Default: /full-feed/';
		
		if (preg_match('/^[a-zA-Z0-9_\-]+\/$/',$_POST['postsurl']) || ! $this->is_subdomain_install()) $postsurl = $_POST['postsurl'];
		else $configerror[] = 'Invalid posts URL. Must be a relative path ending with a "/". Default: posts/';

		if (preg_match('/^[a-zA-Z0-9_\-]+\/$/',$_POST['commentsurl']) || ! $this->is_subdomain_install()) $commentsurl = $_POST['commentsurl'];
		else $configerror[] = 'Invalid comments URL. Must be a relative path ending with a "/". Default: comments/';

		if (preg_match('/^[a-zA-Z0-9_\-]+\/$/',$_POST['pagesurl']) || ! $this->is_subdomain_install()) $pagesurl = $_POST['pagesurl'];
		else $configerror[] = 'Invalid pages URL. Must be a relative path ending with a "/". Default: pages/';

		if (preg_match('/^[0-9]+$/',$_POST['feedcount']) && $_POST['feedcount'] > 0) $feedcount = intval($_POST['feedcount']);
		else $configerror[] = 'Post count must be a number greater than zero. Default: 20';

		$feedtitle = strip_tags($_POST['feedtitle']);

		$feeddesc = strip_tags($_POST['feeddesc']);

		$mincontentchars = intval($_POST['mincontentchars']);
		
		if (preg_match('/^[a-zA-Z0-9_\-:%\/\.]*$/',$_POST['siteimageurl'])) $siteimageurl = trim($_POST['siteimageurl']);
		else $configerror[] = 'Invalid image URL. Must be a full path. If you leave it blank, it will not be included.';
		
		if (!empty($_POST['showavatar'])) $showavatar = 1;
		else $showavatar = 0;

		if (!empty($_POST['showstats'])) $showstats = 1;
		else $showstats = 0;

		if (!empty($_POST['etag'])) $etag = 1;
		else $etag = 0;

		if (!empty($_POST['cache'])) $cache = 1;
		else $cache = 0;

		if (isset($_POST['excerpt']) && ($_POST['excerpt'] == 0 || $_POST['excerpt'] == 1)) $excerpt = intval($_POST['excerpt']);
		else $configerror[] = 'Use excerpts: Must be a one or zero. Default: 1';

		if (preg_match('/^[0-9]+$/',$_POST['expiretime']) && $_POST['expiretime'] >= 0) $expiretime = intval($_POST['expiretime']);
		elseif ($wp_db_version > 3513) $configerror[] = 'Expire time must be a number equal to or greater than zero. Default: 0 (expire only when needed)';
		else $configerror[] = 'Expire time must be a number equal to or greater than zero. Default: '.$this->cache_expire_time().' (expire to account for future dated posts)';

		if ($_POST['expiretime'] > $this->cache_expire_time())
			$configerror[] = 'Expire Minutes: Cannot exceed WP Object Cache expiration time of '.$this->cache_expire_time().' minutes.';

		if ($wpdb->blogid == $_POST['triggerblog'] && ($_POST['triggerurl'] == '/' || stristr($_POST['triggerurl'],'wp-admin')))
			$configerror[] = 'Doh! That combination of blog id and trigger url may have locked you out of your site!';

		if (is_array($configerror)) return $configerror;

		$settings = compact('triggerblog'
							, 'triggerurl'
							, 'commentsurl'
							, 'pagesurl'
							, 'postsurl'
							, 'feedtitle'
							, 'feeddesc'
							, 'feedcount'
							, 'excerpt'
							, 'mincontentchars'
							, 'siteimageurl'
							, 'showavatar'
							, 'showstats'
							, 'cache'
							, 'etag'
							, 'expiretime');
		foreach($settings as $setting => $value) {
			if ($this->$setting != $value) $changed = true;
		}
		
		if ($changed) {
			update_site_option('wpmu_sitefeed_settings', $settings);
			$this->expire_feeds();
			$this->apply_settings($settings);
			return $updated = true;
		}
	}

	function set_defaults() {
		global $wp_db_version;
		//, $current_site;
		// do not edit here - use the admin screen
		$this->feedcount = 20;
		$this->triggerblog = 1;
		if( $this->is_subdomain_install() ) {
			$this->triggerurl = '/full-feed/';
			$this->commentsurl = 'comments/';
			$this->pagesurl = 'pages/';
			$this->postsurl = 'posts/';
		} else {
			$this->triggerurl = '?wpmu-feed=full-feed';
			$this->postsurl = '?wpmu-feed=posts';
			$this->commentsurl = '?wpmu-feed=comments';
			$this->pagesurl = '?wpmu-feed=pages';
		}
		$this->mincontentchars = 25;
		$this->domainpath = get_blog_option($this->triggerblog, 'siteurl');
		$this->blogname = get_blog_option($this->triggerblog, 'blogname');
		$this->siteimageurl = untrailingslashit($this->domainpath) . '/favicon.ico';
		$this->showavatar = 1;
		$this->showstats = 1;
		$this->excerpt = 1;
		$this->cache = 1;
		$this->etag = 1;
		$this->feedtitle = $this->blogname .' Master Site Feed';
		$this->feeddesc = 'Shows all posts, comments, and pages from all blogs on this WordPress powered site';
		($wp_db_version > 3513) ? $this->expiretime = 0 : ($this->cache_expire_time() > 15) ? $this->expiretime = 15 : $this->expiretime = $this->cache_expire_time();
		//$this->expiretime = $this->cache_expire_time();
	}

	function apply_settings($settings = false) {
		if (!$settings) $settings = get_site_option('wpmu_sitefeed_settings');
		if (is_array($settings)) foreach($settings as $setting => $value) $this->$setting = $value;
		if (!isset($this->mincontentchars)) { $this->delete_settings(); $this->set_defaults(); }
//		else $this->set_defaults();
	}

	function delete_settings() {
		global $wpdb, $updated;
		$settings = get_site_option('wpmu_sitefeed_settings');
		if ($settings) {
			$wpdb->query("DELETE FROM $wpdb->sitemeta WHERE `meta_key` = 'wpmu_sitefeed_settings'");
			//if ($this->check_cache()) wp_cache_delete('wpmu_sitefeed_settings','site-options');
			if ($this->check_cache()) wp_cache_delete('wpmu_sitefeed_settings','site-options');
			$this->set_defaults();
			$this->expire_feeds();
			return $updated = true;
		}
	}
	
	function create_feedurl($type) {
		//global $wpdb;
		if ($type == 'fullfeed') $url = $this->triggerurl;
		elseif ($type == 'posts') $url = $this->postsurl;
		elseif ($type == 'comments') $url = $this->commentsurl;
		elseif ($type == 'pages') $url = $this->pagesurl;
		
		if(in_array($type, array('posts', 'comments', 'pages')) && $this->is_subdomain_install()) {
			$url = $this->triggerurl . $url;
		}
		
		$this->domainpath = get_blog_option($this->triggerblog, 'siteurl');
		$this->blogname = get_blog_option($this->triggerblog, 'blogname');
		if($this->domainpath == '') return 'Trigger blog ID was not found!';
		return untrailingslashit($this->domainpath).$url;
	}

	function create_testlink($type) {
		return '<a href="'.$this->create_feedurl($type).'" target="_blank">test link</a>';
	}

	function create_map($type) {
		global $wpdb;
		$excl_blogs = "50";
		$multiplier = 100; // new setting to dig deep for posts/comments until we workaround wpmu_update_blogs_date messing with timestamp
		
		$blogs = $wpdb->get_col("SELECT A.`blog_id`
								FROM ".$wpdb->base_prefix."blogs AS A 
								WHERE A.`public` = '1'
									AND A.`archived` = '0'
									AND A.`spam` = '0'
									AND A.`blog_id` NOT IN (" . $excl_blogs . ")
									AND A.`deleted` ='0' 
									AND A.`last_updated` != '0000-00-00 00:00:00'
									AND A.site_id = (SELECT distinct site_id FROM ".$wpdb->base_prefix."blogs WHERE blog_id = " . $this->triggerblog . ")
								ORDER BY A.`last_updated` DESC
								LIMIT ".$this->feedcount*$multiplier);
		
		if (!is_array($blogs)) return false; // New Site?
		
		foreach($blogs as $blogid) {
			if ($type == 'fullfeed') {
				$results = $wpdb->get_results("SELECT `ID`,`post_date_gmt` 
					FROM `".$wpdb->base_prefix.$blogid."_posts` 
					WHERE `post_status` = 'publish'
						AND `post_password` = ''
						AND `post_date_gmt` < '".gmdate("Y-m-d H:i:s")."'
						AND `ID` > 2 
						AND TRIM(`post_title`) != ''
						AND LENGTH(TRIM(`post_content`)) > ".$this->mincontentchars."
					ORDER BY `post_date_gmt` DESC
					LIMIT ".$this->feedcount);
			} elseif ($type == 'comments' ) {
				$results = $wpdb->get_results("SELECT comment_ID, comment_date_gmt
					FROM ".$wpdb->base_prefix.$blogid."_comments 
						LEFT JOIN ".$wpdb->base_prefix.$blogid."_posts
							ON ".$wpdb->base_prefix.$blogid."_comments.comment_post_id = ".$wpdb->base_prefix.$blogid."_posts.id 
					WHERE ".$wpdb->base_prefix.$blogid."_posts.post_status = 'publish' 
						AND ".$wpdb->base_prefix.$blogid."_posts.`post_password` = ''
						AND ".$wpdb->base_prefix.$blogid."_comments.comment_approved = '1'
						AND ".$wpdb->base_prefix.$blogid."_comments.comment_date_gmt < '" . gmdate("Y-m-d H:i:s") . "'
						AND ".$wpdb->base_prefix.$blogid."_comments.comment_ID > 1
						AND ".$wpdb->base_prefix.$blogid."_comments.comment_type = '' 
					ORDER BY comment_date_gmt DESC LIMIT " . $this->feedcount);
			} elseif ($type == 'posts') {
				$results = $wpdb->get_results("SELECT `ID`,`post_date_gmt` 
					FROM `".$wpdb->base_prefix.$blogid."_posts` 
					WHERE `post_status` = 'publish'
						AND (`post_type` = 'post' OR `post_type` = '')
						AND `post_date_gmt` < '".gmdate("Y-m-d H:i:s")."'
						AND `ID` > 1 
						AND TRIM(`post_title`) != ''
						AND LENGTH(TRIM(`post_content`)) > ".$this->mincontentchars."
					ORDER BY `post_date_gmt` DESC
					LIMIT ".$this->feedcount);
			} elseif ($type == 'pages') {
				//now even Pages can be password protected
				$results = $wpdb->get_results("SELECT `ID`,`post_date_gmt` 
					FROM `".$wpdb->base_prefix.$blogid."_posts` 
					WHERE ((`post_status` = 'publish' AND `post_type` = 'page') OR `post_status` = 'static' )
						AND `post_password` = ''
						AND `post_date_gmt` < '".gmdate("Y-m-d H:i:s")."'
						AND `ID` > 2 
						AND TRIM(`post_title`) != ''
						AND LENGTH(TRIM(`post_content`)) > ".$this->mincontentchars."
					ORDER BY `post_date_gmt` DESC
					LIMIT ".$this->feedcount);
			}
			if (is_array($results)) {
				foreach($results as $result) {
					if ($type == 'fullfeed' || $type == 'posts' || $type == 'pages') {
						$map[] = array($blogid,$result->ID,$result->post_date_gmt);
						$ID[] = $result->ID;
						$date_gmt[] = $result->post_date_gmt;
					} elseif ( $type == 'comments') {
						$map[] = array($blogid,$result->comment_ID,$result->comment_date_gmt);
						$ID[] = $result->comment_ID;
						$date_gmt[] = $result->comment_date_gmt;
					}
				}
			}
		}
		if (is_array($map)) {
			array_multisort($date_gmt, SORT_DESC, $ID, SORT_DESC, $map);
			
				return array_slice($map,0,$this->feedcount);
		}
	}

	function get_data($type) {
		global $wpdb;
		
		$map = $this->create_map($type);
		if (!is_array($map)) return false;
		foreach($map as $item) {
			if ($type == 'fullfeed' || $type == 'posts' || $type == 'pages') {
				//has to have SELECT * so global foreach($posts as $post) works
				$row = $wpdb->get_row("SELECT * FROM `".$wpdb->base_prefix.intval($item[0])."_posts` WHERE `ID` = '".intval($item[1])."'");
				if ($row->ID)
					{
						$row->blogid = intval($item[0]);
						$rows[] = $row;
					}
			} elseif ( $type == 'comments') {
				//has to have SELECT * so global foreach($comments as $comment) works
					$row = $wpdb->get_row("SELECT * FROM `".$wpdb->base_prefix.intval($item[0])."_comments` WHERE `comment_ID` = '".intval($item[1])."'");
					if ($row->comment_ID)
						{
							$row->blogid = intval($item[0]);
							$rows[] = $row;
						}
			}
		}
		if ($rows) return $rows;
	}

	function latest_time() {
		global $posts, $comments;
		return ($posts) ? $posts[0]->post_date_gmt : $comments[0]->comment_date_gmt;
	}

	function save_feed($name,$data) {
		/* no need to save the expire time manually.  Can set in expire time in wp_cache_set
		if ($this->cache) update_site_option($name.'_ts',time());
		return ($this->cache) ? wp_cache_set($name,$data,'site-options') : false;*/
		return ($this->cache) ? wp_cache_set($name,$data,'site-options',$this->expiretime*60) : false;
	}

	function fetch_feed($name) {
		/* no need to expire the feed manually. Expired cache will return false, when using wp_cache_get()
		if ($this->cache) {
			$expires = get_site_option($name.'_ts')+($this->expiretime*60);
			if ($expires <= time()) $this->expire_feed($name);
		}
		*/
		return ($this->cache) ? wp_cache_get($name,'site-options') : false;		
	}

	function expire_feed($name = 'wpmu_sitefeed_cache') {
		return ($this->check_cache()) ? wp_cache_delete($name,'site-options') : false;
	}

	function expire_comments_feed() {
		$this->expire_feed('wpmu_sitecomments_cache');
	}

	function expire_pages_feed() {
		return $this->expire_feed('wpmu_sitepages_cache');
	}

	function expire_post_feeds() {
		$this->expire_feed('wpmu_siteposts_cache');
		$this->expire_feed('wpmu_sitepages_cache');
	}

	function expire_feeds() {
		$this->expire_feed();	//to expire the full-feed
		$this->expire_comments_feed();
		//$this->expire_pages_feed();	//will also expire posts
		$this->expire_post_feeds();
	}

	function outputfeed($type) {
		$cached = false;
		if ($type == 'fullfeed') $name = 'wpmu_sitefeed_cache';
		elseif ($type == 'posts') $name = 'wpmu_siteposts_cache';
		elseif ($type == 'comments') $name = 'wpmu_sitecomments_cache';
		elseif ($type == 'pages') $name = 'wpmu_sitepages_cache';
		if ($this->cache) {
			$feed = $this->fetch_feed($name);
			if ($feed) {
				$cached = true;
			} else {
				$feed = $this->generate_feed($type);
				$saved = $this->save_feed($name,$feed);
			}
		} else {
			$feed = $this->generate_feed($type);
		}
		if ($this->showstats) {
			$feed .= "<!-- ".get_num_queries()." queries ".number_format(timer_stop(),3)." seconds.";
			if ($cached) $feed .= " (cached)";
			$feed .= " -->\r\n";
		}
		//preg_match('/<pubDate>(.*)<\/pubDate>/',$feed,$match);
		preg_match('/<lastBuildDate>(.*)<\/lastBuildDate>/',$feed,$match);
		$lastmodified = date("D, j M Y H:i:s", strtotime($match[1]))." GMT" ;
		$etag = md5($lastmodified);
		//header('Content-Type: text/xml; charset='.get_option('blog_charset'), true);
		header('Content-Type: ' . feed_content_type('rss-http') . '; charset='.get_option('blog_charset'), true);
		if ($this->etag && ((isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == '"' . $etag . '"') || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $lastmodified == $_SERVER['HTTP_IF_MODIFIED_SINCE']))) {
			header('HTTP/1.1 304 Not Modified');
			header('Cache-Control: private');
			header('ETag: "'.$etag.'"');
		} else {
			if ($this->etag) {
				header('Last-Modified: ' . $lastmodified);
				header('ETag: "'.$etag.'"');
			}
			echo $feed;
		}
		exit();
	}

/**
 * Retrieve the avatar for a user who provided a user ID or email address.
 *
 * @since 2.5
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return string <img> tag for the user's avatar
*/
function ada_get_avatar_url( $id_or_email, $size = '96', $default = '', $alt = false ) {
	if ( ! get_option('show_avatars') )
		return false;

	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );

	if ( !is_numeric($size) )
		$size = '96';

	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		if ( isset($id_or_email->comment_type) && '' != $id_or_email->comment_type && 'comment' != $id_or_email->comment_type )
			return false; // No avatar for pingbacks or trackbacks

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	if ( empty($default) ) {
		$avatar_default = get_option('avatar_default');
		if ( empty($avatar_default) )
			$default = 'mystery';
		else
			$default = $avatar_default;
	}

 	if ( is_ssl() )
		$host = 'https://secure.gravatar.com';
	else
		$host = 'http://www.gravatar.com';

	if ( 'mystery' == $default )
		$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
	elseif ( 'blank' == $default )
		$default = includes_url('images/blank.gif');
	elseif ( !empty($email) && 'gravatar_default' == $default )
		$default = '';
	elseif ( 'gravatar_default' == $default )
		$default = "$host/avatar/s={$size}";
	elseif ( empty($email) )
		$default = "$host/avatar/?d=$default&amp;s={$size}";
	elseif ( strpos($default, 'http://') === 0 )
		$default = add_query_arg( 's', $size, $default );

	if ( !empty($email) ) {
		$out = "$host/avatar/";
		$out .= md5( strtolower( $email ) );
		$out .= '?s='.$size;
		$out .= '&amp;d=' . urlencode( $default );

		$rating = get_option('avatar_rating');
		if ( !empty( $rating ) )
			$out .= "&amp;r={$rating}";

		$avatar_url = $out;
	} else {
		$avatar_url = $default;
	}

	return $avatar_url;
}

	function generate_feed($type) {
		global $posts, $comments, $post, $comment;
		//global $posts, $comments;	//, $post, $comment;	//doesn't work
		if ($type == 'fullfeed') { $posts = $this->get_data($type); $feedtitlefull =  $this->feedtitle . __(' Posts & Pages');}
		elseif ($type == 'posts') { $posts = $this->get_data($type); $feedtitlefull =  $this->feedtitle . __(' Posts');}
		elseif ($type == 'comments') { $comments = $this->get_data($type); $feedtitlefull =  $this->feedtitle . __(' Comments');}
		elseif ($type == 'pages') { $posts = $this->get_data($type); $feedtitlefull =  $this->feedtitle . __(' Pages');}
		ob_start();
		echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
		
		if($type == 'fullfeed' || $type == 'posts' || $type == 'pages'){
		?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:media="http://search.yahoo.com/mrss/"
    <?php do_action('rss2_ns'); ?>
>
<?php } else { //comments?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:media="http://search.yahoo.com/mrss/"
	<?php do_action('rss2_ns'); do_action('rss2_comments_ns'); ?>
	>
<?php } ?>
<channel>
	<title><?php echo apply_filters('the_title_rss', $feedtitlefull); ?></title>
    <atom:link href="<?php echo $this->create_feedurl($type); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url'); ?></link>
	<description><?php echo convert_chars(strip_tags($this->feeddesc)); ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', $this->latest_time(), false); ?></lastBuildDate>
	<?php //the_generator( 'rss2' ); ?>
	<language><?php echo get_option('rss_language'); ?></language>
    <?php if($this->siteimageurl) { ?>
    <image>
		<url><?php echo $this->siteimageurl; ?></url>
		<title><?php echo apply_filters('the_title_rss', $feedtitlefull); ?></title>
		<link><?php echo $this->domainpath; ?></link>
	</image>
    <?php } ?>

<?php if ($posts) {
	do_action('rss2_head');
	foreach ($posts as $post) { switch_to_blog($post->blogid); start_wp(); ?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php the_permalink_rss(); ?></link>
		<comments><?php comments_link(); ?></comments>
		<pubDate><?php echo get_post_time('D, d M Y H:i:s +0000', true); ?></pubDate>
		<dc:creator><?php the_author(); ?></dc:creator>
		<?php the_category_rss(); ?>
        
		<guid isPermaLink="false"><![CDATA[<?php the_guid(); ?>]]></guid>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
<?php if (!$this->excerpt) : ?>
		<content:encoded><![CDATA[<?php the_content(); ?>]]></content:encoded>
<?php endif; ?>
		<wfw:commentRss><?php echo get_post_comments_feed_link(); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
    <?php if($this->showavatar) { ?>
        <media:content url="<?php echo $this->ada_get_avatar_url($post->post_author); ?>" medium="image">
            <media:title type="html"><?php the_author() ?></media:title>
        </media:content>
    <?php } ?>
<?php rss_enclosure(); ?>
	<?php do_action('rss2_item'); ?>
	</item>
<?php //restore_current_blog();
		}
		restore_current_blog(); } ?>
<?php if ($comments) {
	do_action('commentsrss2_head');
	foreach ($comments as $comment) { switch_to_blog($comment->blogid); get_post_custom($comment->comment_post_ID); ?>
	<item>
		<title><?php
				$title = get_the_title($comment->comment_post_ID);
				$title = apply_filters('the_title_rss', $title);
				printf(ent2ncr(__('%1$s (%2$s)')), $title, get_comments_number($comment->comment_post_ID));
		?></title>
		<link><?php comment_link(); ?></link>
		<dc:creator><?php echo get_comment_author_rss(); ?></dc:creator>
		<pubDate><?php echo get_comment_time('D, d M Y H:i:s +0000', true); ?></pubDate>
		<guid isPermaLink="false"><?php comment_guid(); ?></guid>
		<description><![CDATA[<?php comment_text_rss(); ?>]]></description>
		<content:encoded><![CDATA[<?php comment_text(); ?>]]></content:encoded>
    <?php if($this->showavatar) { ?>
        <media:content url="<?php echo $this->ada_get_avatar_url($comment); ?>" medium="image">
            <media:title type="html"><?php the_author() ?></media:title>
        </media:content>
    <?php } ?>
<?php //} // close check for password
	do_action('commentrss2_item', $comment->comment_ID,$comment->comment_post_ID); ?>
	</item>
<?php //restore_current_blog();
		}
		restore_current_blog(); } ?>
</channel>
</rss>
<?php
		$feed = ob_get_contents();
		ob_end_clean();
		return $feed;
	}

	function config_page() {
		global $updated, $configerror;
		//get_currentuserinfo();
		if (!is_site_admin()) die(__('<p>You do not have permission to access this page.</p>'));
		if (isset($_POST['action']) && $_POST['action'] == 'update') {
			if (!isset($_POST['reset'])) {
				$this->save_settings();
			}
			else $this->delete_settings();
		}
		if ($updated) { ?>
<div id="message" class="updated fade"><p><?php _e('Options saved.') ?></p></div>
<?php	} elseif (is_array($configerror)) { ?>
<div class="error"><p><?php echo implode('<br />',$configerror); ?></p></div>
<?php	} ?>
<div class="wrap">
<h2>Sitewide Feed Options</h2>
<fieldset class="options"> 
<p>This plugin creates four (4) seperate RSS 2.0 feeds from posts, comments, pages, and one combined [posts &amp; pages] across all blogs on your WordPress powered site. (version: <?php echo $this->version; ?>) (<a href="http://1uthavi.adadaa.com/ada-wpmu-sitewide-feed-plugin/" target="_blank">Plugin Homepage</a>)</p>
<?php if (!$this->check_cache()) { ?>
<p style="color:#CC0000;font-weight:bold;">NOTE: Your WordPress is not using <a href="http://ryan.boren.me/2005/11/14/persistent-object-cache/" target="_blank">WP Object Cache</a>. Performance will be degraded and site load increased. Please use the object cache for maximum performance.</p>
<?php } elseif (!$this->cache) { ?>
<p style="color:#CC0000;font-weight:bold;">NOTE: You have disabled usage of the <a href="http://ryan.boren.me/2005/11/14/persistent-object-cache/" target="_blank">WP Object Cache</a> for this plugin. Performance will be degraded and site load increased. Please use the object cache for maximum performance.</p>
<?php } ?>
<form name="sitefeedform" action="" method="post">
<table width="100%" cellspacing="2" cellpadding="5" class="editform">
  <tr valign="top">
    <th scope="row"><label for="triggerblog"><?php _e('Trigger Blog ID:') ?></label>
    </th>
    <td><input name="triggerblog" type="text" id="triggerblog" value="<?php echo $this->triggerblog; ?>" size="3" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="triggerurl"><?php _e('Feed URL (relative path):') ?></label>
    </th>
    <td><input name="triggerurl" type="text" id="triggerurl" value="<?php echo $this->triggerurl; ?>" size="25" title='<?php _e("This is the combined feeds of Posts &amp; Pages. Must be a relative path beginning with and ending with a \"/\". Default: /full-feed/") ?>' /> 
    (<?php echo $this->create_testlink('fullfeed'); ?>)</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="postsurl"><?php _e('Posts Feed URL (appended to Feed URL):') ?></label>
    </th>
    <td><input name="postsurl" type="text" id="postsurl" value="<?php echo $this->postsurl; ?>" size="25" title='<?php _e("Must be a relative path ending with a \"/\". Default: posts/") ?>' /> 
    (<?php echo $this->create_testlink('posts'); ?>)</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="commentsurl"><?php _e('Comments Feed URL (appended to Feed URL):') ?></label>
    </th>
    <td><input name="commentsurl" type="text" id="commentsurl" value="<?php echo $this->commentsurl; ?>" size="25" title='<?php _e("Must be a relative path ending with a \"/\". Default: comments/") ?>' /> 
    (<?php echo $this->create_testlink('comments'); ?>)</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="pagesurl"><?php _e('Pages Feed URL (appended to Feed URL):') ?></label>
    </th>
    <td><input name="pagesurl" type="text" id="pagesurl" value="<?php echo $this->pagesurl; ?>" size="25" title='<?php _e("Must be a relative path ending with a \"/\". Default: pages/") ?>' /> 
    (<?php echo $this->create_testlink('pages'); ?>)</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="feedtitle"><?php _e('Feed Title:') ?></label>
    </th>
    <td><input name="feedtitle" type="text" id="feedtitle" value="<?php echo $this->feedtitle; ?>" size="60" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="feeddesc"><?php _e('Feed Description:') ?></label>
    </th>
    <td><input name="feeddesc" type="text" id="feeddesc" value="<?php echo $this->feeddesc; ?>" size="60" /></td>
  </tr>
  <tr valign="top">
    <th width="33%" scope="row"><label for="feedcount"><?php _e('Show the most recent:') ?></label></th>
    <td><input name="feedcount" type="text" id="feedcount" value="<?php echo $this->feedcount; ?>" size="3" /> <?php _e('posts') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"><?php _e('For each article, show:') ?>
    </th>
    <td><label>
      <input name="excerpt"  type="radio" value="0" <?php checked(0, $this->excerpt); ?>  />
      <?php _e('Full text') ?>
      </label>
        <br />
        <label>
        <input name="excerpt" type="radio" value="1" <?php checked(1, $this->excerpt); ?> />
        <?php _e('Summary') ?>
        </label>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="mincontentchars"><?php _e('Minimum number of chars in content:') ?></label>
    </th>
    <td><input name="mincontentchars" type="text" id="mincontentchars" value="<?php echo $this->mincontentchars; ?>" size="3" title='<?php _e("Minimum number of chars needed in the content before they show up in the feed. Default: 25") ?>' /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="siteimageurl"><?php _e('URL of site image:') ?></label>
    </th>
    <td><input name="siteimageurl" type="text" id="siteimageurl" value="<?php echo $this->siteimageurl; ?>" size="60" title='<?php _e("Enter the full URL for the image of your site.  If you leave it blank, it will not be included.") ?>' /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="showavatar"><?php _e('Show avatar:') ?></label>
    </th>
    <td><label>
      <input name="showavatar"  type="checkbox" id="showavatar" value="1" <?php checked(1, $this->showavatar); ?>  />
      </label>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="showstats"><?php _e('Append stats to feed:') ?></label>
    </th>
    <td><label>
      <input name="showstats"  type="checkbox" id="showstats" value="1" <?php checked(1, $this->showstats); ?>  />
      </label>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="etag"><?php _e('Use ETag header:') ?></label>
    </th>
    <td><label>
      <input name="etag"  type="checkbox" id="etag" value="1" <?php checked(1, $this->etag); ?>  />
      </label>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="cache"><?php _e('Use Object Cache:') ?></label>
    </th>
    <td><label>
      <input name="cache"  type="checkbox" id="cache" value="1" <?php checked(1, $this->cache); ?>  />
      </label>
    </td>
  </tr>
  <tr valign="top">
    <th width="33%" scope="row"><label for="expiretime"><?php _e('Expire feed from cache after:') ?></label></th>
    <td><input name="expiretime" type="text" id="expiretime" value="<?php echo $this->expiretime; ?>" size="3" title="Default: 0 (expire only when needed). Any greater value (expire to account for future dated posts)" /> 
    <?php echo _e('minutes') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="reset"><?php _e('Reset all settings to default:') ?></label>
    </th>
    <td><label>
      <input name="reset" type="checkbox" value="1" id="reset" />
      </label>
    </td>
  </tr>
</table>
<p class="submit">
<input type="hidden" name="action" value="update" /> 
<input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" /> 
</p>
</form>
</fieldset>
</div>
<?php
	}
}

//all your posts, comments, pages, and base are belong to us!
if (defined('ABSPATH')) $wpmu_sitefeed = new wpmu_sitefeed();
?>
<?php
/**
 * Abstracts Goole OAuth2 authentication hoohah.
 * Also handles G+ imports.
 */
class Wdgpo_GoogleAuth {

	const GPLUS_MAX_RESULTS = 5;
	const GPLUS_ATTACHMENTS_TPL = '<div class="wdgpo_gplus_attachments">%s</div>';
	const GPLUS_ATTACHMENTS_PHOTO_TPL = "<p class='wdgpo_gplus_attachment wdgpo_gplus_photo_attachment'><a class='wdgpo_gplus_photo_attachment_full_size' href='%s'><img src='%s' alt='%s' height='%d' width='%d' /></a></p>";
	const GPLUS_ATTACHMENTS_PHOTO_TPL_FULLSIZE = "<p class='wdgpo_gplus_attachment wdgpo_gplus_photo_attachment'><img src='%s' alt='%s' height='%d' width='%d' /></p>";
	const GPLUS_ATTACHMENTS_VIDEO_TPL = "<p class='wdgpo_gplus_attachment wdgpo_gplus_video_attachment'><a class='wdgpo_gplus_video_attachment_link' href='%s'><img src='%s' /></a></p>";
	const GPLUS_LINKED_ARTICLE_TPL = "<p class='wdgpo_gplus_attachment wdgpo_gplus_article_attachment'><a class='wdgpo_gplus_article_attachment_link' href='%s'>%s</a></p>";

	private static $_instance;

	private $_page_id;
	private $_profile_id;
	private $_limit;
	private $_client_id;
	private $_client_secret;
	private $_token;

	private $_data;
	private $_client;
	private $_plus;

	private $_logger = false;

	private function __clone () {}

	private function __construct () {
		if (!function_exists('curl_init')) return false;

		$this->_data = new Wdgpo_Options;
		$this->_page_id = $this->_data->get_option('gplus_page_id');
		$this->_profile_id = $this->_data->get_option('gplus_profile_id');
		$this->_limit = (int)$this->_data->get_option('gplus_import_limit');
		$this->_client_id = $this->_data->get_option('gplus_client_id');
		$this->_client_secret = $this->_data->get_option('gplus_client_secret');
		$this->_token = $this->_data->get_token('gplus_token');

		$log_level = $this->_data->get_option('log');
		if ($log_level) {
			$this->_logger = new Wdgpo_Logger($log_level);
		}

		$this->_load_dependencies();
		$this->_construct_client();
	}

	public static function init () {
		try {
			$me = self::get_instance();
			$me->authenticate();
			if ($me->_logger) $me->_logger->log(
				"Initialized and authenticated without exceptions",
				Wdgpo_Logger::LEVEL_DEBUG
			);
		} catch (Exception $e) {
			if ($me->_logger) $me->_logger->log(
				sprintf("Error initializing: %s", $e->getMessage()),
				Wdgpo_Logger::LEVEL_ERROR
			);
		}
	}

	public static function get_instance () {
		if (!isset(self::$_instance)) self::$_instance = new Wdgpo_GoogleAuth;
		return self::$_instance;
	}

	public function get_redirect_url () {
		return trailingslashit(home_url());
	}

	public function get_auth_url () {
		$url = $this->_client->getAccessToken() ? false : $this->_client->createAuthUrl();
		if ($this->_logger) $this->_logger->log(
			sprintf("Getting auth url: [%s]", $url ? $url : 'OK'),
			Wdgpo_Logger::LEVEL_DEBUG
		);
		return $url;
	}

	public function authenticate () {
		if (isset($_GET['code'])) {
			if ($this->_logger) $this->_logger->log(
				"Authenticating",
				Wdgpo_Logger::LEVEL_DEBUG
			);
			$this->_client->authenticate();
			if ($this->_logger) $this->_logger->log(
				"Storing auth token",
				Wdgpo_Logger::LEVEL_DEBUG
			);
			$this->_store_auth_token();
		}
	}

	public function reset_token () {
		if ($this->_logger) $this->_logger->log(
			"Resetting token",
			Wdgpo_Logger::LEVEL_DEBUG
		);
		$this->_token = null;
		$this->_data->set_token(null);
	}

	public function get_gplus_data ($test=false) {
		if ($this->_logger) $this->_logger->log(
			"Getting data",
			Wdgpo_Logger::LEVEL_INFO
		);
		$import_ids = $this->_data->get_option('gplus_import_ids');
		$import_ids = $import_ids ? array_filter($import_ids) : array();
		$import_ids[] = $this->_page_id;
		$import_ids[] = $this->_profile_id;

		$results = array();
		foreach ($import_ids as $id) {
			if (!$id) continue;
			$results[] = $this->get_gplus_feed($id, $test);
		}

		return $results;
	}

	public function get_gplus_feed ($gplus_id, $test=false) {
		if ($this->_logger) $this->_logger->log(
			"Getting individual feed: {$gplus_id}",
			Wdgpo_Logger::LEVEL_INFO
		);
		$limit = $test ? 1 : ($this->_limit ? $this->_limit : self::GPLUS_MAX_RESULTS);
		return $this->_plus->activities->list(array(
			'userId' => $gplus_id,
			'collection' => 'public',
			'maxResults' => $limit,
		));
	}

	/**
	 * Imports latest posts from registered G+ feeds into WP.
	 */
	public function import_gplus_data () {
		if ($this->_logger) $this->_logger->log(
			"Importing data",
			Wdgpo_Logger::LEVEL_INFO
		);
		$feeds = $this->get_gplus_data();
		$results = array();

		foreach ($feeds as $feed) {
			$results[] = $this->_import_gplus_feed($feed);
		}

		return $results;
	}

	/**
	 * Imports a single G+ feed.
	 */
	private function _import_gplus_feed ($data) {
		$imported_items = 0;
		$to_import = @$data['items'];
		$title = @$data['title'];
		$status = @$data['title'] ? 1 : 0;
		$to_import = $to_import ? $to_import : array();

		$options = array (
			'post_tags' => $this->_data->get_option('gplus_post_tags'),
			'post_category' => $this->_data->get_option('gplus_post_category'),
			'post_format' => $this->_data->get_option('gplus_post_format'),
			'format_to_hashtag' => $this->_data->get_option('gplus_formats_to_hashtags'),
			'post_author_login' => $this->_data->get_option('gplus_post_author'),
			'auto_tag' => $this->_data->get_option('gplus_auto_tag'),
			'auto_hashtag' => $this->_data->get_option('gplus_auto_hashtag'),
			'hashtags' => trim($this->_data->get_option('gplus_import_hashtags')),
		);

		foreach ($to_import as $item) {
			$imported_items += (int)$this->_import_gplus_item(@$item, $options);
		}

		return array(
			"title" => $title,
			"status" => $status,
			"items" => $imported_items,
		);
	}

	/**
	 * Binds G+ activity item to WP post.
	 * Skips import if the item has already been imported.
	 */
	private function _import_gplus_item ($item, $opts=array()) {
		if (!$item) return false;

		$feed_id = @$item['actor']['id'];
		$item_id = @$item['object']['url'];
		if (!$item_id) return false; // Invalid data, no point carrying on...

		if ($this->_is_imported($item_id)) return false; // Item already present.

		if (!$this->_is_linked_article($item)) {
			$title = @$item['title'];
			$content = @$item['object']['content'] . $this->_item_attachments_to_html(@$item['object']['attachments']);
		} else {
			$result = $this->_parse_linked_article_item($item);
			$title = $result['title'];
			$content = $result['content'];
		}

		// Check hashtags
		if (@$opts['hashtags'] && !$this->_has_hashtags($content, $opts['hashtags'])) return false; // We require a hashtag to import, but found none.

		$author = get_userdatabylogin(@$opts['post_author_login']);
		$author_id = @$author->ID ? $author->ID : false;
		if (!$author_id) {
			// No author, assign to admin
			global $blog_id;
			$author_id = get_user_id_from_string(get_blog_option($blog_id, 'admin_email'));
		}

		$detected_hashtags = (@$opts['auto_hashtag'] || @$opts['format_to_hashtag']) ? $this->_detect_hashtags($content) : array();

		$post = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_type' => ($this->_data->get_option('gplus_post_no_publish') ? 'wdgpo_imported_post' : 'post' ),
			'post_date' => date("Y-m-d h:i:s", strtotime($item['published'])),
			'post_status' => 'publish',
			'post_author' => $author_id,
		);
		if (@$opts['post_category']) {
			$post['post_category'] = array($opts['post_category']);
		}
		// Save post
		$post_id = wp_insert_post($post);

		// Update tags, formats and metas.
		if ($post_id) {
			update_post_meta($post_id, 'wdgpo_gplus_feed_id', $feed_id);
			update_post_meta($post_id, 'wdgpo_gplus_item_id', $item_id);
			update_post_meta($post_id, 'wdgpo_gplus_author', @$item['actor']);

			$tags = array();
			if (@$opts['post_tags']) $tags = $opts['post_tags'];
			if (@$opts['auto_tag']) $tags[] = preg_replace('~[^-_.a-zA-Z0-9 ]~', '-', @$item['actor']['displayName']);
			if (@$opts['auto_hashtag']) array_splice($tags, count($tags), 0, $detected_hashtags);
			if ($tags) wp_set_post_tags($post_id, $tags);

			if (@$opts['post_format'] && !@$opts['format_to_hashtag']) {
				set_post_format($post_id, $opts['post_format']);
			} else if (@$opts['format_to_hashtag']) {
				foreach ($opts['format_to_hashtag'] as $format => $hashtag_mappings) {
					if (!$hashtag_mappings) continue;
					if (!$this->_has_hashtags($content, $hashtag_mappings)) continue;
					set_post_format($post_id, $format);
					break;
				}
			} // else no post format to assign...
		}

		if (defined('BP_VERSION') && $this->_data->get_option('gplus_post_bp_activities')) {
			$args = array (
				'action' => $title,
				'content' => $content,
				'component' => 'wdgpo_activities',
				'type' => 'wdgpo_activity',
				'item_id' => $post_id,
				'secondary_item_id' => $post_id,
				//'hide_sitewide' => $this->data->get_option('bp_publish_activity_local'),
			);
			$res = bp_activity_add($args);
		}

		return $post_id ? 1 : 0;
	}

	/**
	 * Checks to see if we're dealing with a reshared article link.
	 */
	private function _is_linked_article ($item) {
		$attachments = @$item['object']['attachments'];
		$attachments = $attachments ? $attachments : array();
		if (!$attachments) return false;
		foreach ($attachments as $attachment) {
			if ('article' == @$attachment['objectType']) return true;
		}
		return false;
	}

	/**
	 * Checks if an item has already been imported.
	 */
	private function _is_imported ($gplus_id) {
		$posts = get_posts('meta_key=wdgpo_gplus_item_id&meta_value=' . $gplus_id);
		return $posts ? true : false;
	}

	/**
	 * Special case: imports linked article.
	 */
	private function _parse_linked_article_item ($item) {
		$ret = array(
			"title" => "",
			"content" => @$item['object']['content'],
		);
		$attachments = @$item['object']['attachments'];
		$attachments = $attachments ? $attachments : array();
		if (!$attachments) return $ret;

		$content = '';
		$url = '#';
		$unprocessed = array();
		foreach ($attachments as $attachment) {
			if ('article' == @$attachment['objectType']) {
				$ret['title'] = $attachment['displayName'];
				$content .= $attachment['content'];
				$url = $attachment['url'];
			} else $unprocessed[] = $attachment;
		}

		$content .= $this->_item_attachments_to_html($unprocessed);
		$ret['content'] .= sprintf(self::GPLUS_LINKED_ARTICLE_TPL, $url, $content);
		return $ret;
	}

	/**
	 * Converts attachments to HTML.
	 */
	private function _item_attachments_to_html ($attachments) {
		$ret = '';
		if (!$attachments) return $ret;
		foreach ($attachments as $attachment) {
			if ('photo' == @$attachment['objectType']) $ret .= $this->_photo_attachment_to_html($attachment);
			else if ('video' == @$attachment['objectType']) $ret .= $this->_video_attachment_to_html($attachment);
		}

		return sprintf(self::GPLUS_ATTACHMENTS_TPL, $ret);
	}

	/**
	 * Converts Photo attachments to HTML.
	 */
	private function _photo_attachment_to_html ($attachment) {
		if (!$attachment) return '';
		$fullsize = (@$attachment['fullImage']['width'] && !@$attachment['image']['width']);
		$width = @$attachment['image']['width'] ? $attachment['image']['width'] : @$attachment['fullImage']['width'];
		$height = @$attachment['image']['height'] ? $attachment['image']['height'] : @$attachment['fullImage']['height'];
		return $fullsize ?
			sprintf(
				self::GPLUS_ATTACHMENTS_PHOTO_TPL_FULLSIZE,
				@$attachment['fullImage']['url'],
				@$attachment['content'],
				$height,
				$width
			)
			:
			sprintf(
				self::GPLUS_ATTACHMENTS_PHOTO_TPL,
				@$attachment['fullImage']['url'],
				@$attachment['image']['url'],
				@$attachment['content'],
				$height,
				$width
			)
		;
	}

	/**
	 * Converts video attachment to HTML.
	 */
	private function _video_attachment_to_html ($attachment) {
		return sprintf(
			self::GPLUS_ATTACHMENTS_VIDEO_TPL,
			@$attachment['url'],
			@$attachment['image']['url']
		);
	}

	/**
	 * Checks string for hashtags.
	 */
	private function _has_hashtags ($str, $hashtags) {
		if (!is_array($hashtags)) $hashtags = $this->_hashtag_string_to_array($hashtags);
		$hashtags = $hashtags ? $hashtags : array();
		if (!$hashtags) return true; // No hashtags - so... it has to have nothing?

		foreach ($hashtags as $hashtag) {
			if (preg_match('~' . preg_quote($hashtag) . '~', $str)) return true;
		}
		// If we got here, we didn't detect any hashtags
		return false;
	}

	/**
	 * Helper for converting hahstag string to array of tags.
	 */
	private function _hashtag_string_to_array ($str) {
		$result = array();
		$tags = explode(',', $str);
		$tags = $tags ? $tags : array();
		foreach ($tags as $tag) {
			// Clean it up
			$tag = preg_replace('/[^0-9A-Za-z]/', '', trim($tag));
			if (!$tag) continue;

			$result[] = "#{$tag}";
		}
		return $result;
	}

	/**
	 * Parses a string for hashtags.
	 */
	private function _detect_hashtags ($str) {
		preg_match_all('/\s#[0-9a-z]+\b/i', $str, $matches);
		if (!$matches[0]) return array();
		$result = array();
		foreach ($matches[0] as $tag) {
			$tag = preg_replace('/[^0-9A-Za-z]/', '', trim($tag));
			if (!$tag) continue;
			$result[] = "#{$tag}";
		}
		return $result;
	}

	private function _store_auth_token () {
		$this->_token = $this->_client->getAccessToken();
		$this->_set_access_token();
		$this->_data->set_token($this->_token);
	}

	private function _set_access_token () {
		if ($this->_token) $this->_client->setAccessToken($this->_token);
	}

	private function _load_dependencies () {
		if (!class_exists('apiClient')) require_once(WDGPO_PLUGIN_BASE_DIR . '/lib/external/google/apiClient.php');
		if (!class_exists('apiPlusService')) require_once(WDGPO_PLUGIN_BASE_DIR . '/lib/external/google/contrib/apiPlusService.php');
		if (!class_exists('apiClient') || !class_exists('apiPlusService')) {
			if ($this->_logger) $this->_logger->log(
				"Missing dependencies",
				Wdgpo_Logger::LEVEL_ERROR
			);
		} else {
			if ($this->_logger) $this->_logger->log(
				"Successfully loaded dependencies",
				Wdgpo_Logger::LEVEL_DEBUG
			);
		}
	}

	private function _construct_client () {
		$this->_client = new apiClient();
		$this->_client->setClientId($this->_client_id);
		$this->_client->setClientSecret($this->_client_secret);
		$this->_client->setRedirectUri($this->get_redirect_url());

		$this->_set_access_token();

		$this->_plus = new apiPlusService($this->_client);
	}

}
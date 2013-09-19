<?php
/**
 * Handles shortcode creation and replacement.
 */
class Wdgpo_Codec {

	var $shortcodes = array(
		'plusone' => 'wdgpo_plusone',
		'author' => 'wdgpo_author',
		'gplus_page' => 'wdgpo_gplus_page',
		'gplus_activities' => 'wdgpo_activities',
	);

	var $data;
	function Wdgpo_Codec () { $this->__construct(); }

	function __construct () {
		$this->data = new Wdgpo_Options;
	}

	function _check_display_restrictions ($post_id) {
		if (!$post_id) return false;

		$type = get_post_type($post_id);
		if (!$type) return false;

		$skip_types = $this->data->get_option('skip_post_types');
		if (!is_array($skip_types)) return true; // No restrictions, we're good

		return (!in_array($type, $skip_types));
	}

	function process_gplus_activities_code ($args, $content='') {
		$args = shortcode_atts(array(
			'ids' => false,
			'show_faces' => false,
			'template' => false,
			'orderby' => false,
			'order' => false,
			'limit' => false,
			'buffering' => false,
		), $args);

		$ids = array();
		if ($args['ids']) {
			$tmp = explode(',', $args['ids']);
			foreach($tmp as $id) {
				if (!trim($id)) continue;
				$ids[] = trim($id);
			}
		}
		$limit = $args['limit'] ? (int)$args['limit'] : -1;
		$orderby = $args['orderby'] ? $args['orderby'] : 'date';
		$order = $args['order'] ? $args['order'] : 'DESC';

		$meta_query = array();
		foreach ($ids as $id) {
			$meta_query[] = array(
				'key' => 'wdgpo_gplus_feed_id',
				'value' => $id,
			);
		}
		if (count($ids) > 1) $meta_query['relation'] = 'OR';
		$qargs = array(
			'post_type' => array('post', 'wdgpo_imported_post'),
			'posts_per_page=' => $limit,
			'orderby' => $orderby,
			'order' => $order,
		);
		if ($meta_query) $qargs['meta_query'] = $meta_query;
		else $qargs['meta_key'] = 'wdgpo_gplus_feed_id';

		$query = new WP_Query($qargs);
		$activities = $query->posts;

		$ret = '';
		if ($args['buffering']) {
			ob_start();
			if (!$args['template']) get_template_part('wdgpo-activities', $args['template']);
			else include(WDGPO_PLUGIN_BASE_DIR . '/lib/forms/activities.php');
			$ret = ob_get_contents();
			ob_end_clean();
			return "{$content} {$ret}";
		} else {
			echo apply_filters('the_content', $content);
			if ($args['template']) get_template_part('wdgpo-activities', $args['template']);
			else include(WDGPO_PLUGIN_BASE_DIR . '/lib/forms/activities.php');
			return $ret;
		}
	}

	function process_author_code ($args) {
		global $post;
		$args = shortcode_atts(array(
			'user_id' => $post->post_author,
		), $args);
		$user_id = $args['user_id'];
		$profile = get_user_meta($user_id, 'wdgpo_gplus', true);
		if (!$profile) return false;
		
		$user = get_userdata($user_id);
		$profile = preg_match('/\?rel=author/i', $profile) ? esc_url($profile) : esc_url("{$profile}?rel=author");
		return '<div class="wdgpo_author">' .
			"<a href='{$profile}'>" .
				'<img src="https://ssl.gstatic.com/images/icons/gplus-16.png" /> ' .
				sprintf(__('%s on Google+', 'wdgpo'), $user->display_name) .
			'</a>' .
		'</div>';
	}
	
	function process_plusone_code ($args) {
		$post_id = get_the_ID();
		if (!$this->_check_display_restrictions($post_id)) return '';

		$args = shortcode_atts(array(
			'appearance' => false,
			'show_count' => false,
		), $args);

		$size = $args['appearance'] ? $args['appearance'] : $this->data->get_option('appearance');
		$url = get_permalink();
		$show_count = $args['show_count'] ? ('yes' == $args['show_count']) : $this->data->get_option('show_count');
		$count = $show_count ? 'true' : 'false';
		$count_class = ('true' == $count) ? 'count' : 'nocount';

		$callback = $this->data->get_option('analytics_integration') ? "callback='wdgpo_plusone_click'" : '';

		$ret = "<div class='wdgpo wdgpo_{$size}_{$count_class}'><g:plusone size='{$size}' count='{$count}' href='{$url}' {$callback}></g:plusone></div>";
		return $ret;
	}

	function process_gplus_page_code ($args) {
		$args = shortcode_atts(array(
			'appearance' => false,
			'float' => false,
		), $args);
		$appearance = $args['appearance'] ? $args['appearance'] : 'medium_icon';
		$float = in_array($args['float'], array('left', 'right')) ? "style='float:{$args['float']};'" : '';

		$data = new Wdgpo_Options;
		$page_id = $data->get_option('gplus_page_id');
		if (!$page_id) return '';

		$tpl = '<a href="https://plus.google.com/%s/?prsrc=3" style="text-decoration: none;"><img src="https://ssl.gstatic.com/images/icons/gplus-%d.png" width="%d" height="%d" style="border: 0;"></img></a>';
		$tpl = "<div class='wdgpo wdgpo_gplus_page wdgpo_gplus_page_{$appearance}' {$float}>{$tpl}</div>";
		$ret = '';
		switch ($appearance) {
			case "small_icon":
				$ret = sprintf($tpl, $page_id, 16, 16, 16);
				break;
			case "medium_icon":
				$ret = sprintf($tpl, $page_id, 32, 32, 32);
				break;
			case "large_icon":
				$ret = sprintf($tpl, $page_id, 64, 64, 64);
				break;
		}

		return $ret;
	}

	function get_code ($key, $attr=false) {
		return '[' . $this->shortcodes[$key] . ']';
	}

	/**
	 * Registers shortcode handlers.
	 */
	function register () {
		foreach ($this->shortcodes as $key=>$shortcode) {
			add_shortcode($shortcode, array($this, "process_{$key}_code"));
		}
	}
}
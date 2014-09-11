<?php
/**
 * Shows Google+ page box
 */
class Wdgpo_Gplus_WidgetActivities extends WP_Widget {

	function Wdgpo_Gplus_WidgetActivities () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Shows your imported Google+ Activities', 'wdgpo'));
		parent::WP_Widget(__CLASS__, 'Google+ Imported Activities', $widget_ops);
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
		$feed_id = @$instance['feed_id'];
		$posts_limit = @$instance['posts_limit'];

		// Set defaults
		// ...
		$opts = new Wdgpo_Options;
		$gplus_feeds = $opts->get_option('gplus_import_ids');
		$gplus_feeds = $gplus_feeds ? array_filter($gplus_feeds) : array();
		$gplus_feeds[] = $opts->get_option('gplus_page_id');
		$gplus_feeds[] = $opts->get_option('gplus_profile_id');
		$posts_limit = $posts_limit ? $posts_limit : 10;

		$html = '<p>';
		$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title:', 'wdgpo') . '</label>';
		$html .= '<input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" class="widefat" value="' . $title . '"/>';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('feed_id') . '">' . __('Show items for this feed:', 'wdgpo') . '</label> ';
		$html .= '<select name="' . $this->get_field_name('feed_id') . '" id="' . $this->get_field_id('feed_id') . '">';
		foreach ($gplus_feeds as $key=>$feed) {
			$selected = ($feed == $feed_id) ? 'selected="selected"' : '';
			$html .= "<option value='{$feed}' {$selected}>{$feed}&nbsp;</option>";
		}
		$html .= '</select>';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('posts_limit') . '">' . __('Show this many imported posts:', 'wdgpo') . '</label> ';
		$html .= '<select name="' . $this->get_field_name('posts_limit') . '" id="' . $this->get_field_id('posts_limit') . '">';
		for ($i=1; $i<21; $i++) {
			$selected = ($i == $posts_limit) ? 'selected="selected"' : '';
			$html .= "<option value='{$i}' {$selected}>{$i}</option>";
		}
		$html .= '</select>';
		$html .= '</p>';

		echo $html;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['feed_id'] = strip_tags($new_instance['feed_id']);
		$instance['posts_limit'] = (int)$new_instance['posts_limit'];

		return $instance;
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		$feed_id = $instance['feed_id'];
		$posts_limit = (int)$instance['posts_limit'];

		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;

		$this->_show_posts($feed_id, $posts_limit);

		echo $after_widget;
	}

	function _show_posts ($feed_id, $limit) {
		$query = new WP_Query(array(
			'post_type' => array('post', 'wdgpo_imported_post'),
			'meta_key' => 'wdgpo_gplus_feed_id',
			'meta_value' => $feed_id,
			'posts_per_page' => (int)$limit,
		));
		echo "<ul class='wdgpo_gplus_posts'>";
		foreach ($query->posts as $post) {
			$url = ('wdgpo_imported_post' == $post->post_type) ? get_post_meta($post->ID, 'wdgpo_gplus_item_id', true) : get_permalink($post->ID);
			echo "<li>";
			echo '<a class="wdgpo_gplus_post_title" href="' . esc_url($url) . '">' . esc_html($post->post_title) . '</a>';
			echo "</li>";
		}
		echo "</ul>";
	}
}
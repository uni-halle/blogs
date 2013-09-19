<?php
/**
 * Shows Google+ page box
 */
class Wdgpo_Gplus_WidgetPage extends WP_Widget {

	function Wdgpo_Gplus_WidgetPage () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Shows your Google+ Page', 'wdgpo'));
		parent::WP_Widget(__CLASS__, 'Google+ Page Widget', $widget_ops);
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
		$gplus_appearance = esc_attr($instance['appearance']);
		$float = esc_attr($instance['float']);
		$text = $instance['text'];
		$text_position = $instance['text_position'];
		$show_posts = $instance['show_posts'];
		$posts_position = $instance['posts_position'];
		$posts_limit = $instance['posts_limit'];


		// Set defaults
		// ...
		$gplus_appearances = array (
			'small_icon' => array (
				"title" => __('Small icon: %s', 'wdgpo'),
				"icon" => "https://ssl.gstatic.com/images/icons/gplus-16.png",
			),
			'medium_icon' => array (
				"title" => __('Medium icon: %s', 'wdgpo'),
				"icon" => "https://ssl.gstatic.com/images/icons/gplus-32.png",
			),
			'large_icon' => array (
				"title" => __('Large icon: %s', 'wdgpo'),
				"icon" => "https://ssl.gstatic.com/images/icons/gplus-64.png",
			),
		);
		$gplus_appearance = $gplus_appearance ? $gplus_appearance : 'medium_icon';
		$floats = array (
			'' => __('None', 'wdgpo'),
			'left' => __('Left', 'wdgpo'),
			'right' => __('Right', 'wdgpo'),
		);
		$positions = array (
			'before' => __('before', 'wdgpo'),
			'after' => __('after', 'wdgpo'),
		);
		$posts_limit = $posts_limit ? $posts_limit : 10;

		$html = '<p>';
		$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title:', 'wdgpo') . '</label>';
		$html .= '<input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" class="widefat" value="' . $title . '"/>';
		$html .= '</p>';

		$data = new Wdgpo_Options;
		if (!$data->get_option('gplus_page_id')) {
			echo $html;
			echo '<p><small><em>' . __("If you'd like to add your Google+ badge, you need to set up your Google+ page ID in plugin settings.", 'wdgpo') . '</em></small></p>';
			return;
		}

		$html .= '<p>';
		foreach ($gplus_appearances as $key=>$info) {
			$checked = ($key == $gplus_appearance) ? "checked='checked'" : '';
			$icon = $info['icon'] ? sprintf('<br /><img src="%s" />', $info['icon']) : '';
			$label = sprintf($info['title'], $icon);

			$html .= "<input type='radio' name='" . $this->get_field_name('appearance') . "' value='{$key}' {$checked} id='" . $this->get_field_id('appearance') . "-{$key}' />";
			$html .= "&nbsp;";
			$html .= "<label for='" . $this->get_field_id('appearance') . "-{$key}'>{$label}</label></br />";
		}
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('float') . '">' . __('Badge float', 'wdgpo') . ':</label> ';
		$html .= '<select name="' . $this->get_field_name('float') . '" id="' . $this->get_field_id('float') . '">';
		foreach ($floats as $fkey=>$fval) {
			$selected = ($fkey == $float) ? 'selected="selected"' : '';
			$html .= "<option value='{$fkey}' {$selected}>{$fval}&nbsp;</option>";
		}
		$html .= '</select>';
		$html .= '</p>';

		$html .= '<label for="' . $this->get_field_id('text') . '">' . __('My text', 'wdgpo') . ':</label> ';
		$html .= '<textarea name="' . $this->get_field_name('text') . '" id="' . $this->get_field_id('text') . '" class="widefat" rows="4">' . $text . '</textarea>';

		$sel = '<select name="' . $this->get_field_name('text_position') . '" id="' . $this->get_field_id('text_position') . '">';
		foreach ($positions as $tkey=>$tval) {
			$selected = ($tkey == $text_position) ? 'selected="selected"' : '';
			$sel .= "<option value='{$tkey}' {$selected}>{$tval}&nbsp;</option>";
		}
		$sel .= '</select>';

		$html .= '<p>';
		$html .= sprintf(__('My text will come %s my badge.', 'wdgpo'), $sel);
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('show_posts') . '">' . __('Show my imported posts:', 'wdgpo') . '</label> ';
		$html .= '<input type="checkbox" name="' . $this->get_field_name('show_posts') . '" id="' . $this->get_field_id('show_posts') . '" value="1" ' . ($show_posts ? 'checked="checked"' : '') . ' />';
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

		$sel = '<select name="' . $this->get_field_name('posts_position') . '" id="' . $this->get_field_id('posts_position') . '">';
		foreach ($positions as $tkey=>$tval) {
			$selected = ($tkey == $posts_position) ? 'selected="selected"' : '';
			$sel .= "<option value='{$tkey}' {$selected}>{$tval}&nbsp;</option>";
		}
		$sel .= '</select>';

		$html .= '<p>';
		$html .= sprintf(__('My posts will come %s my badge and text.', 'wdgpo'), $sel);
		$html .= '</p>';

		echo $html;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['appearance'] = strip_tags($new_instance['appearance']);
		$instance['float'] = strip_tags($new_instance['float']);
		$instance['text_position'] = strip_tags($new_instance['text_position']);
		$instance['text'] = current_user_can('unfiltered_html') ? $new_instance['text'] : wp_filter_post_kses($new_instance['text']);
		$instance['show_posts'] = strip_tags($new_instance['show_posts']);
		$instance['posts_position'] = strip_tags($new_instance['posts_position']);
		$instance['posts_limit'] = strip_tags($new_instance['posts_limit']);

		return $instance;
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		$appearance = $instance['appearance'];
		$appearance = $appearance ? $appearance : 'medium_icon';

		$float = esc_attr($instance['float']);
		$text = $instance['text'];
		$text_position = $instance['text_position'];

		$show_posts = $instance['show_posts'];
		$posts_position = $instance['posts_position'];
		$posts_limit = (int)$instance['posts_limit'];

		$codec = new Wdgpo_Codec;
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;

		$args = array(
			'appearance' => $appearance,
		);
		if ($float) $args['float'] = $float;

		if ($show_posts && 'before' == $posts_position) $this->_show_posts($posts_limit);
		if ($text && 'before' == $text_position) echo $text;
		echo $codec->process_gplus_page_code($args);
		if ($text && 'after' == $text_position) echo $text;
		if ($float) {
			echo "<div style='clear:{$float}'></div>";
		}
		if ($show_posts && 'after' == $posts_position) $this->_show_posts($posts_limit);

		echo $after_widget;
	}

	function _show_posts ($limit) {
		$feed_id = Wdgpo_Options::get_option('gplus_page_id');
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
<?php

class AWPCP_Search_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(false, __('AWPCP Search Ads', 'AWPCP'));
	}

	/**
	 * @since  3.0-beta
	 */
	protected function ads_sel($by, $field, $search_field) {
		global $wpdb;

		$conditions[] = 'disabled = 0';
		$conditions[] = '(flagged IS NULL OR flagged = 0)';

		$query = 'SELECT DISTINCT ' . $field . ' FROM ' . AWPCP_TABLE_ADS . ' ';
		$query.= 'WHERE ' . join(' AND ', $conditions);
		$query.= 'ORDER BY ' . $field . ' ';

		$results = $wpdb->get_results($query);

		$options = array();
		foreach ($results as $result) {
			$options[$result->$field] = $result->$field;
		}

		$selected = stripslashes_deep(awpcp_post_param($search_field, null));

		echo $this->select($options, $by, $search_field, $selected) . '<br/>';
	}

	/**
	 * @since 3.0-beta
	 */
	protected function defaults() {
		return array(
			'title' => '',
			'subtitle' => '',
			'show_keyword' => 1,
			'show_by' => 1,
			'show_city' => 1,
			'show_state' => 1,
			'show_country' => 1,
			'show_category' => 1,
		);
	}

	// /**
	//  * @since 3.0-beta
	//  */
	// protected function get_ad_categories() {
	// 	global $wpdb;

	// 	$conditions[] = 'disabled = 0';
	// 	$conditions[] = '(flagged IS NULL OR flagged = 0)';

	// 	$query = 'SELECT DISTINCT ad_category_id AS id, category_name AS name FROM ' . AWPCP_TABLE_ADS . ' ';
	// 	$query.= 'LEFT JOIN ' . AWPCP_TABLE_CATEGORIES . ' ON (category_id = ad_category_id)';
	// 	$query.= 'WHERE ' . join(' AND ', $conditions);
	// 	$query.= 'ORDER BY category_name';

	// 	$results = $wpdb->get_results($query);

	// 	$categories = array();
	// 	foreach ($results as $result) {
	// 		$categories[$result->id] = $result->name;
	// 	}

	// 	return $categories;
	// }

	/**
	 * @since 3.0-beta
	 */
	public function select($options, $label, $name, $selected=null) {
		$id = 'awpcp-search-' . sanitize_title($label);

		$html = sprintf('<label for="%s">%s</label><br>', $id, $label);
		$html .= sprintf('<select id="%s" name="%s">', $id, $name);
		if (is_array($options)) {
			$html .= sprintf('<option value="">%s</option>', __('Select Option', 'AWPCP'));
			foreach ($options as $value => $option) {
				$_value = esc_attr($value);
				if ($value == $selected) {
					$html .= sprintf('<option checked="checked" value="%s">%s</option>', $_value, $option);
				} else {
					$html .= sprintf('<option value="%s">%s</option>', $_value, $option);
				}
			}
		} else {
			$html .= sprintf('<option value="">%s</option>', __('Select Option', 'AWPCP'));
			$html .= $options;
		}
		$html .= '</select>';

		return $html;
	}

	function widget($args, $instance) {
		extract($args);

		$instance = wp_parse_args($instance, $this->defaults());

		$title = $instance['title'].'<br/><span class="widgetstitle">'.$instance['subtitle'].'</span>';

		echo $before_widget . $before_title . $title . $after_title;
		echo '<div align="center"><form method=\'post\' action="'.url_searchads().'"><input type="hidden" name="a" value="dosearch"/>';

		$keywordphrase = stripslashes_deep(awpcp_post_param('keywordphrase'));

		if ($instance['show_keyword'] == 1) {
			echo '<label for="awpcp-search-keywordphrase">' . __('Search by keyword', "AWPCP") . '</label><br/>';
			echo '<input id="awpcp-search-keywordphrase" type="text" name="keywordphrase" value="' . esc_attr($keywordphrase) . '"><br/>';
		}
		if ($instance['show_by'] == 1) {
			$this->ads_sel(__('Find ads by', "AWPCP"), 'ad_contact_name', 'searchname');
		}
		if ($instance['show_city'] == 1) {
			$this->ads_sel(__('Search by City', "AWPCP"), 'ad_city', 'searchcity');
		}
		if ($instance['show_state'] == 1) {
			$this->ads_sel(__('Search by State', "AWPCP"), 'ad_state', 'searchstate');
		}
		if ($instance['show_country'] == 1) {
			$this->ads_sel(__('Search by Country', "AWPCP"), 'ad_country', 'searchcountry');
		}

		if ($instance['show_category'] == 1) {
			$label = __('Search by Category', "AWPCP");
			$name = 'searchcategory';
			$selected = stripslashes_deep(awpcp_post_param($name, null));
			echo $this->select(get_categorynameidall($selected), $label, $name, $selected) . '<br/>';
		}

		echo '<br/><input class="button" type="submit" value="Search"></form></div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['subtitle'] = strip_tags($new_instance['subtitle']);
		$instance['show_keyword'] = (strip_tags($new_instance['show_keyword']) == 1 ? 1 : 0);
		$instance['show_by'] = (strip_tags($new_instance['show_by']) == 1 ? 1 : 0);
		$instance['show_city'] = (strip_tags($new_instance['show_city']) == 1 ? 1 : 0);
		$instance['show_state'] = (strip_tags($new_instance['show_state']) == 1 ? 1 : 0);
		$instance['show_country'] = (strip_tags($new_instance['show_country']) == 1 ? 1 : 0);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, $this->defaults());

		$title = strip_tags($instance['title']);
		$subtitle = strip_tags($instance['subtitle']);
		$show_keyword = strip_tags($instance['show_keyword']);
		$show_by = strip_tags($instance['show_by']);
		$show_city = strip_tags($instance['show_city']);
		$show_state = strip_tags($instance['show_state']);
		$show_country = strip_tags($instance['show_country']);

		include(AWPCP_DIR . '/frontend/templates/widget-search-form.tpl.php');
	}
}

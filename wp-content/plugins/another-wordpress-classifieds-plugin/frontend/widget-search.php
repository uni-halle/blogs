<?php

class AWPCP_Search_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(false, __('AWPCP Search Ads', 'AWPCP'));
	}

	/**
	 * @since 3.0
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

    /**
     * @since 3.0.2
     */
    private function render_find_by_contact_name_field() {
        global $wpdb;

        $query = 'SELECT DISTINCT ad_contact_name FROM ' . AWPCP_TABLE_ADS . ' ';
        $query.= 'WHERE disabled = 0 AND (flagged IS NULL OR flagged = 0)';
        $query.= 'ORDER BY ad_contact_name ASC';

        $names = $wpdb->get_col( $query );

        if ( empty( $names ) ) {
            $options = array();
        } else {
            $options = array_combine( $names, $names );
        }

        $selected = stripslashes_deep( awpcp_post_param( 'searchname', null ) );

        return $this->select( $options, __('Find ads by Contact Name', "AWPCP"), 'searchname', $selected, __( 'All Contact Names', 'AWPCP' ) );
    }

    /**
     * @since 3.0.2
     */
    private function render_region_fields( $instance ) {
        if ( isset( $_POST['regions'][0] ) ) {
            $regions = array( stripslashes_deep( $_POST['regions'][0] ) );
        } else {
            $regions = array();
        }

        $options = array(
            'showTextField' => false,
            'showExistingRegionsOnly' => true,
            'maxRegions' => 1,
        );

        $selector = new AWPCP_MultipleRegionSelector( $regions, $options );
        echo $selector->render( 'search', array(), array() );
    }

    /**
     * @since 3.0.2
     */
    private function render_region_field( $label, $options, $name ) {
        if ( isset( $_POST['regions'][0][ $name ] ) ) {
            $selected = stripslashes_deep( $_POST['regions'][0][ $name ] );
        } else {
            $selected = null;
        }

        return $this->select( $options, $label, "regions[0][$name]", $selected );
    }

	/**
	 * @since 3.0
	 */
	public function select($options, $label, $name, $selected=null, $default=null) {
		$id = 'awpcp-search-' . sanitize_title($label);
        $default = is_null( $default ) ? __('Select Option', 'AWPCP') : $default;

		$html = sprintf('<label for="%s">%s</label>', $id, $label);
		$html .= sprintf('<select id="%s" name="%s">', $id, $name);
		if (is_array($options)) {
			$html .= sprintf( '<option value="">%s</option>', $default );
			foreach ($options as $value => $option) {
				$_value = esc_attr($value);
				if ($value == $selected) {
					$html .= sprintf('<option selected="selected" value="%s">%s</option>', $_value, $option);
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

		if ( !empty( $instance['subtitle'] ) ) {
			$title = $instance['title'] . '<br/><span class="widgetstitle">' . $instance['subtitle'] . '</span>';
		} else {
			$title = $instance['title'] . '</span>';
		}

        echo '<div class="awpcp-search-listings-widget">';
		echo $before_widget . $before_title . $title . $after_title;
		echo '<div align="center"><form method=\'post\' action="'.url_searchads().'">';
        echo '<input type="hidden" name="a" value="dosearch"/>';

		$keywordphrase = stripslashes_deep(awpcp_post_param('keywordphrase'));

		if ($instance['show_keyword'] == 1) {
			echo '<label for="awpcp-search-keywordphrase">' . __('Search by keyword', "AWPCP") . '</label>';
			echo '<input id="awpcp-search-keywordphrase" type="text" name="keywordphrase" value="' . esc_attr($keywordphrase) . '">';
		}
		if ($instance['show_by'] == 1) {
			echo $this->render_find_by_contact_name_field();
		}

		echo $this->render_region_fields( $instance );

		if ($instance['show_category'] == 1) {
			$label = __('Search by Category', "AWPCP");
			$name = 'searchcategory';
			$selected = stripslashes_deep(awpcp_post_param($name, null));

			$dropdown = new AWPCP_CategoriesDropdown();
			echo $dropdown->render( array(
                'context' => 'search',
                'selected' => $selected,
                'required' => false,
                'name' => $name,
                'label' => $label,
            ) );
		}

		echo '<div class="submit"><input class="button" type="submit" value="Search"></div>';
        echo '</form></div>';
        echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		$instance['show_keyword'] = absint( $new_instance['show_keyword'] );
		$instance['show_by'] = absint( $new_instance['show_by'] );
		$instance['show_city'] = absint( $new_instance['show_city'] );
		$instance['show_state'] = absint( $new_instance['show_state'] );
		$instance['show_country'] = absint( $new_instance['show_country'] );
		$instance['show_category'] = absint( $new_instance['show_category'] );
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, $this->defaults());

		$title = strip_tags( $instance['title'] );
		$subtitle = strip_tags( $instance['subtitle'] );
		$show_keyword = absint( $instance['show_keyword'] );
		$show_by = absint( $instance['show_by'] );
		$show_city = absint( $instance['show_city'] );
		$show_state = absint( $instance['show_state'] );
		$show_country = absint( $instance['show_country'] );
		$show_category = absint( $instance['show_category'] );

		include(AWPCP_DIR . '/frontend/templates/widget-search-form.tpl.php');
	}
}

<?php

/**
 * Create HTML list of categories.
 *
 * @uses Walker
 */
class AVHEC_Walker_Category extends Walker {
	/**
	 * @see   Walker::$db_fields
	 * @since 2.1.0
	 * @todo  Decouple this
	 * @var array
	 */
	public $db_fields = array('parent' => 'parent', 'id' => 'term_id');
	/**
	 * @see   Walker::$tree_type
	 * @since 2.1.0
	 * @var string
	 */
	public $tree_type = 'category';

	/**
	 * @see      Walker::end_el()
	 * @since    2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $object
	 * @param int    $depth  Depth of category. Not used.
	 * @param array  $args   Only uses 'list' for whether should append to output.
	 */
	public function end_el(&$output, $object, $depth = 0, $args = array()) {
		if ('list' != $args['style']) {
			return;
		}

		$output .= '</li>' . "\n";
	}

	/**
	 * @see   Walker::end_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of category. Used for tab indentation.
	 * @param array  $args   Will only append content if style argument value is 'list'.
	 */
	public function end_lvl(&$output, $depth = 0, $args = array()) {
		if ('list' != $args['style']) {
			return;
		}

		$indent = str_repeat("\t", $depth);
		$output .= $indent . '</ul>' . "\n";
	}

	/**
	 * @see   Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int    $depth    Depth of category in reference to parents.
	 * @param array  $args
	 * @param int    $current_object_id
	 */
	public function start_el(&$output, $category, $depth = 0, $args = array(), $current_object_id = 0) {
		$cat_name = apply_filters('list_cats', esc_attr($category->name), $category);
		// Don't generate an element if the category name is empty.
		if ( ! $cat_name) {
			return;
		}

		$link = '<div class="avhec-widget-line"><a href="' . get_category_link($category->term_id) . '" ';
		if ($args['use_desc_for_title'] && ! empty($category->description)) {
			/**
			 * Filter the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' .
			         esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) .
			         '"';
		} else {
			$link .= 'title="' . sprintf(__('View all posts filed under %s'), $cat_name) . '"';
		}
		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( ! empty($args['feed_image']) || ! empty($args['feed'])) {
			$link .= '<div class="avhec-widget-rss"> ';

			if (empty($args['feed_image'])) {
				$link .= '(';
			}

			$link .= '<a href="' . get_category_feed_link($category->term_id, $args['feed_type']) . '"';

			if (empty($args['feed'])) {
				$alt = ' alt="' . sprintf(__('Feed for all posts filed under %s'), $cat_name) . '"';
			} else {
				$alt  = ' alt="' . $args['feed'] . '"';
				$name = $args['feed'];
				$link .= ' title="';
				$link .= empty($args['title']) ? $args['feed'] : $args['title'];
				$link .= '"';
			}

			$link .= '>';

			if (empty($args['feed_image'])) {
				$link .= $name;
			} else {
				$link .= '<img src="' . $args['feed_image'] . '"' . $alt . '" />';
			}
			$link .= '</a>';

			if (empty($args['feed_image'])) {
				$link .= ')';
			}

			$link .= '</div>';
		}

		if ( ! empty($args['show_count'])) {
			$link .= '<div class="avhec-widget-count"> (' . number_format_i18n($category->count) . ')</div>';
		}

		if ( ! empty($args['$show_date'])) {
			$link .= ' ' . gmdate('Y-m-d', $category->last_update_timestamp);
		}

		if ('list' == $args['style']) {
			// When on a single post get the post's category. This ensures that that category will be given the CSS style of "current category".
			if (is_single()) {
				$post_cats                = get_the_category();
				$args['current_category'] = $post_cats[0]->term_id;
			}

			$output .= "\t" . '<li';
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty($args['current_category'])) {
				$_current_category = get_term($args['current_category'], $category->taxonomy);
				if ($category->term_id == $args['current_category']) {
					$css_classes[] = 'current-cat';
				} elseif ($category->term_id == $_current_category->parent) {
					$css_classes[] = 'current-cat-parent';
				}
			}

			/**
			 * Filter the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 * @see   wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));

			$output .= ' class="' . $css_classes . '"';
			$output .= '>' . $link . '</div>' . "\n";
		} else {
			$output .= "\t" . $link . '</div><br />' . "\n";
		}
	}

	/**
	 * @see   Walker::start_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of category. Used for tab indentation.
	 * @param array  $args   Will only append content if style argument value is 'list'.
	 */
	public function start_lvl(&$output, $depth = 0, $args = array()) {
		if ('list' != $args['style']) {
			return;
		}

		$indent = str_repeat("\t", $depth);
		$output .= $indent . '<ul class="children">' . "\n";
	}
}

class AVH_EC_Core {
	public $comment;
	public $db_options_core;
	public $db_options_tax_meta;
	public $default_options;
	public $default_options_category_group;
	public $default_options_general;
	public $default_options_sp_category_group;
	public $info;
	public $options;
	public $version;

	/**
	 * AVH_EC_Core constructor.
	 */
	public function __construct() {
		$this->version             = '3.10.0-dev.1';
		$this->comment             = '<!-- AVH Extended Categories version ' .
		                             $this->version .
		                             ' | http://blog.avirtualhome.com/wordpress-plugins/ -->';
		$this->db_options_core     = 'avhec';
		$this->db_options_tax_meta = 'avhec-tax_meta';

		AVH_EC_Singleton::getInstance('AVH_EC_Category_Group');

		add_action('init', array($this, 'handleInitializePlugin'), 10);
	}

	public function applyOrderFilter($orderby, $args) {
		switch ($args['orderby']) {
			case 'avhec_manualorder':
				$new_orderby = 't.avhec_term_order';
				break;
			case 'avhec_3rdparty_mycategoryorder':
				$new_orderby = 't.term_order';
				break;
			default:
				$new_orderby = $orderby;
				break;
		}

		return $new_orderby;
	}

	/**
	 * Display or retrieve the HTML dropdown list of categories.
	 *
	 * The 'hierarchical' argument, which is disabled by default, will override the
	 * depth argument, unless it is true. When the argument is false, it will
	 * display all of the categories. When it is enabled it will use the value in
	 * the 'depth' argument.
	 *
	 * @since 2.1.0
	 * @since 4.2.0 Introduced the `value_field` argument.
	 *
	 * @param string|array $args              {
	 *                                        Optional. Array or string of arguments to generate a categories drop-down
	 *                                        element.
	 *
	 * @type string        $show_option_all   Text to display for showing all categories. Default empty.
	 * @type string        $show_option_none  Text to display for showing no categories. Default empty.
	 * @type string        $option_none_value Value to use when no category is selected. Default empty.
	 * @type string        $orderby           Which column to use for ordering categories. See get_terms() for a list
	 *                                           of accepted values. Default 'id' (term_id).
	 * @type string        $order             Whether to order terms in ascending or descending order. Accepts 'ASC'
	 *                                           or 'DESC'. Default 'ASC'.
	 * @type bool          $pad_counts        See get_terms() for an argument description. Default false.
	 * @type bool|int      $show_count        Whether to include post counts. Accepts 0, 1, or their bool equivalents.
	 *                                           Default 0.
	 * @type bool|int      $hide_empty        Whether to hide categories that don't have any posts. Accepts 0, 1, or
	 *                                           their bool equivalents. Default 1.
	 * @type int           $child_of          Term ID to retrieve child terms of. See get_terms(). Default 0.
	 * @type array|string  $exclude           Array or comma/space-separated string of term ids to exclude.
	 *                                           If `$include` is non-empty, `$exclude` is ignored. Default empty
	 *                                           array.
	 * @type bool|int      $echo              Whether to echo or return the generated markup. Accepts 0, 1, or their
	 *                                           bool equivalents. Default 1.
	 * @type bool|int      $hierarchical      Whether to traverse the taxonomy hierarchy. Accepts 0, 1, or their bool
	 *                                           equivalents. Default 0.
	 * @type int           $depth             Maximum depth. Default 0.
	 * @type int           $tab_index         Tab index for the select element. Default 0 (no tabindex).
	 * @type string        $name              Value for the 'name' attribute of the select element. Default 'cat'.
	 * @type string        $id                Value for the 'id' attribute of the select element. Defaults to the value
	 *                                           of `$name`.
	 * @type string        $class             Value for the 'class' attribute of the select element. Default
	 *       'postform'.
	 * @type int|string    $selected          Value of the option that should be selected. Default 0.
	 * @type string        $value_field       Term field that should be used to populate the 'value' attribute
	 *                                           of the option elements. Accepts any valid term field: 'term_id',
	 *                                           'name',
	 *                                           'slug', 'term_group', 'term_taxonomy_id', 'taxonomy', 'description',
	 *                                           'parent', 'count'. Default 'term_id'.
	 * @type string|array  $taxonomy          Name of the category or categories to retrieve. Default 'category'.
	 * @type bool          $hide_if_empty     True to skip generating markup if no categories are found.
	 *                                           Default false (create select element even if no categories are found).
	 * }
	 * @return string HTML content only if 'echo' argument is 0.
	 */
	public function avh_wp_dropdown_categories($args = '') {
		$mywalker = new AVH_Walker_CategoryDropdown();

		// @format_off
		$defaults = array(
			'show_option_all'   => '',
			'show_option_none'  => '',
			'orderby'           => 'id',
			'order'             => 'ASC',
			'show_last_update'  => 0,
			'show_count'        => 0,
			'hide_empty'        => 1,
			'child_of'          => 0,
			'exclude'           => '',
			'echo'              => 1,
			'selected'          => 0,
			'hierarchical'      => 0,
			'name'              => 'cat',
			'id'                => '',
			'class'             => 'postform',
			'depth'             => 0,
			'tab_index'         => 0,
			'taxonomy'          => 'category',
			'walker'            => $mywalker,
			'hide_if_empty'     => false,
			'option_none_value' => - 1,
			'value_field'       => 'term_id',
		);
		// @format_on
		$defaults['selected'] = (is_category()) ? get_query_var('cat') : 0;

		$r                 = wp_parse_args($args, $defaults);
		$option_none_value = $r['option_none_value'];

		if ( ! isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical']) {
			$r['pad_counts'] = true;
		}

		$r['include_last_update_time'] = $r['show_last_update'];
		$tab_index                     = $r['tab_index'];

		$tab_index_attribute = '';
		if ((int) $tab_index > 0) {
			$tab_index_attribute = ' tabindex="' . $tab_index . '"';
		}

		// Avoid clashes with the 'name' param of get_terms().
		$get_terms_args = $r;
		unset($get_terms_args['name']);
		$categories = get_terms($r['taxonomy'], $get_terms_args);

		$name  = esc_attr($r['name']);
		$class = esc_attr($r['class']);
		$id    = $r['id'] ? esc_attr($r['id']) : $name;

		if ( ! $r['hide_if_empty'] || ! empty($categories)) {
			$output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
		} else {
			$output = '';
		}

		if (empty($categories) && ! $r['hide_if_empty'] && ! empty($r['show_option_none'])) {
			$show_option_none = apply_filters('list_cats', $r['show_option_none']);
			$output .= "\t<option value='-1' selected='selected'>$show_option_none</option>\n";
		}
		if ( ! empty($categories)) {

			if ($r['show_option_all']) {
				$show_option_all = apply_filters('list_cats', $$r['show_option_all']);
				$selected        = ('0' === strval($r['selected'])) ? " selected='selected'" : '';
				$output .= "\t" . '<option value="0"' . $selected . '>' . $show_option_all . '</option>' . "\n";
			}

			if ($r['show_option_none']) {
				$show_option_none = apply_filters('list_cats', $r['show_option_none']);
				$selected         = selected($option_none_value, $r['selected'], false);
				$output .= "\t" .
				           '<option value="' .
				           esc_attr($option_none_value) .
				           '"' .
				           $selected .
				           '>' .
				           $show_option_none .
				           '</option>' .
				           "\n";
			}

			if ($r['hierarchical']) {
				$depth = $r['depth']; // Walk the full depth.
			} else {
				$depth = - 1; // Flat
			}
			$output .= walk_category_dropdown_tree($categories, $depth, $r);
		}
		if ( ! $r['hide_if_empty'] || ! empty($categories)) {
			$output .= "</select>\n";
		}

		$output = apply_filters('wp_dropdown_cats', $output, $r);

		if ($r['echo']) {
			echo $output;
		}

		return $output;
	}

	/**
	 * Display or retrieve the HTML list of categories.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Introduced the `hide_title_if_empty` and `separator` arguments. The `current_category` argument was
	 *        modified to optionally accept an array of values.
	 *
	 * @param string|array $args                {
	 *                                          Array of optional arguments.
	 *
	 * @type string        $show_option_all     Text to display for showing all categories. Default empty string.
	 * @type string        $show_option_none    Text to display for the 'no categories' option.
	 *                                               Default 'No categories'.
	 * @type string        $orderby             The column to use for ordering categories. Default 'ID'.
	 * @type string        $order               Which direction to order categories. Accepts 'ASC' or 'DESC'.
	 *                                               Default 'ASC'.
	 * @type bool|int      $show_count          Whether to show how many posts are in the category. Default 0.
	 * @type bool|int      $hide_empty          Whether to hide categories that don't have any posts attached to them.
	 *                                               Default 1.
	 * @type bool|int      $use_desc_for_title  Whether to use the category description as the title attribute.
	 *                                               Default 1.
	 * @type string        $feed                Text to use for the feed link. Default 'Feed for all posts filed
	 *                                               under [cat name]'.
	 * @type string        $feed_type           Feed type. Used to build feed link. See {@link get_term_feed_link()}.
	 *                                               Default empty string (default feed).
	 * @type string        $feed_image          URL of an image to use for the feed link. Default empty string.
	 * @type int           $child_of            Term ID to retrieve child terms of. See {@link get_terms()}. Default 0.
	 * @type array|string  $exclude             Array or comma/space-separated string of term IDs to exclude.
	 *                                               If `$hierarchical` is true, descendants of `$exclude` terms will
	 *                                               also be excluded; see `$exclude_tree`. See {@link get_terms()}.
	 *                                               Default empty string.
	 * @type array|string  $exclude_tree        Array or comma/space-separated string of term IDs to exclude, along
	 *                                               with their descendants. See {@link get_terms()}. Default empty
	 *                                               string.
	 * @type bool|int      $echo                True to echo markup, false to return it. Default 1.
	 * @type int|array     $current_category    ID of category, or array of IDs of categories, that should get the
	 *                                               'current-cat' class. Default 0.
	 * @type bool          $hierarchical        Whether to include terms that have non-empty descendants.
	 *                                               See {@link get_terms()}. Default true.
	 * @type string        $title_li            Text to use for the list title `<li>` element. Pass an empty string
	 *                                               to disable. Default 'Categories'.
	 * @type bool          $hide_title_if_empty Whether to hide the `$title_li` element if there are no terms in
	 *                                               the list. Default false (title will always be shown).
	 * @type int           $depth               Category depth. Used for tab indentation. Default 0.
	 * @type string        $taxonomy            Taxonomy name. Default 'category'.
	 * @type string        $separator           Separator between links. Default '<br />'.
	 * }
	 * @return false|string HTML content only if 'echo' argument is 0.
	 */
	public function avh_wp_list_categories($args = '') {
		$mywalker = new AVHEC_Walker_Category();
		$defaults = array(
			'child_of'            => 0,
			'current_category'    => 0,
			'depth'               => 0,
			'echo'                => 1,
			'exclude'             => '',
			'exclude_tree'        => '',
			'feed'                => '',
			'feed_image'          => '',
			'feed_type'           => '',
			'hide_empty'          => 1,
			'hide_title_if_empty' => false,
			'hierarchical'        => true,
			'order'               => 'ASC',
			'orderby'             => 'name',
			'separator'           => '<br />',
			'show_count'          => 0,
			'show_last_update'    => 0,
			'show_option_all'     => '',
			'show_option_none'    => __('No categories'),
			'style'               => 'list',
			'taxonomy'            => 'category',
			'title_li'            => __('Categories'),
			'use_desc_for_title'  => 1,
			'walker'              => $mywalker
		);

		$r = wp_parse_args($args, $defaults);

		if ( ! isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical']) {
			$r['pad_counts'] = true;
		}

		if (isset($r['show_date'])) {
			$r['include_last_update_time'] = $r['show_date'];
		}

		if (true == $r['hierarchical']) {
			$exclude_tree = array();

			if ($r['exclude_tree']) {
				$exclude_tree = array_merge($exclude_tree, wp_parse_id_list($r['exclude_tree']));
			}

			if ($r['exclude']) {
				$exclude_tree = array_merge($exclude_tree, wp_parse_id_list($r['exclude']));
			}

			$r['exclude_tree'] = $exclude_tree;
			$r['exclude']      = '';
		}

		if ( ! isset($r['class'])) {
			$r['class'] = ('category' == $r['taxonomy']) ? 'categories' : $r['taxonomy'];
		}

		if ( ! taxonomy_exists($r['taxonomy'])) {
			return false;
		}

		$show_option_all  = $r['show_option_all'];
		$show_option_none = $r['show_option_none'];

		$categories = get_categories($r);

		$output = '';
		if ($r['title_li'] && 'list' == $r['style'] && ( ! empty($categories) || ! $r['hide_title_if_empty'])) {
			$output = '<li class="' . esc_attr($r['class']) . '">' . $r['title_li'] . '<ul>';
		}

		if (empty($categories)) {
			if ( ! empty($show_option_none)) {
				if ('list' == $r['style']) {
					$output .= '<li class="cat-item-none">' . __("No categories") . '</li>';
				} else {
					$output .= $show_option_none;
				}
			}
		} else {
			if ( ! empty($show_option_all)) {

				$posts_page      = '';
				$taxonomy_object = get_taxonomy($r['taxonomy']);

				if ( ! in_array('post', $taxonomy_object->object_type) &&
				     ! in_array('page', $taxonomy_object->object_type)
				) {
					foreach ($taxonomy_object->object_type as $object_type) {
						$_object_type = get_post_type_object($object_type);

						// Grab the first one.
						if ( ! empty($_object_type->has_archive)) {
							$posts_page = get_post_type_archive_link($object_type);
							break;
						}
					}
				}
				// Fallback for the 'All' link is the posts page.
				if ( ! $posts_page) {
					if ('page' == get_option('show_on_front') && get_option('page_for_posts')) {
						$posts_page = get_permalink(get_option('page_for_posts'));
					} else {
						$posts_page = home_url('/');
					}
				}

				$posts_page = esc_url($posts_page);
				if ('list' == $r['style']) {
					$output .= '<li class="cat-item-all"><a href="' .
					           $posts_page .
					           '">' .
					           $show_option_all .
					           '</a></li>';
				} else {
					$output .= '<a href="' . $posts_page . '">' . $show_option_all . '</a>';
				}
			}
			if (empty($r['current_category']) && (is_category() || is_tax() || is_tag())) {
				$current_term_object = get_queried_object();
				if ($current_term_object && $r['taxonomy'] === $current_term_object->taxonomy) {
					$r['current_category'] = get_queried_object_id();
				}
			}

			if ($r['hierarchical']) {
				$depth = $r['depth'];
			} else {
				$depth = - 1; // Flat.
			}

			$output .= walk_category_tree($categories, $depth, $r);
		}

		if ($r['title_li'] && 'list' == $r['style']) {
			$output .= '</ul></li>';
		}

		$html = apply_filters('wp_list_categories', $output, $args);

		if ($r['echo']) {
			echo $html;
		} else {
			return $html;
		}

		return;
	}

	/**
	 * Checks if running version is newer and do upgrades if necessary
	 *
	 * @since 1.2.3
	 *
	 * @param string       $db_version
	 */
	public function doUpdateOptions($db_version) {
		$options = $this->getOptions();

		// Add none existing sections and/or elements to the options
		foreach ($this->default_options as $section => $default_data) {
			if ( ! array_key_exists($section, $options)) {
				$options[ $section ] = $default_data;
				continue;
			}
			foreach ($default_data as $element => $default_value) {
				if ( ! array_key_exists($element, $options[ $section ])) {
					$options[ $section ][ $element ] = $default_value;
				}
			}
		}

		// Remove none existing sections and/or elements from the options
		foreach ($options as $section => $data) {
			if ( ! array_key_exists($section, $this->default_options)) {
				unset($options[ $section ]);
				continue;
			}
			foreach ($data as $element => $value) {
				if ( ! array_key_exists($element, $this->default_options[ $section ])) {
					unset($options[ $section ][ $element ]);
				}
			}
		}
		/**
		 * Update the options to the latests versions
		 */
		$options['general']['version']   = $this->version;
		$options['general']['dbversion'] = $db_version;
		$this->saveOptions($options);
	}

	/**
	 * Get the base directory of a directory structure
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function getBaseDirectory($directory) {
		// place each directory into array and get the last element
		$directory_array = explode('/', $directory);
		// get highest or top level in array of directory strings
		$public_base = end($directory_array);

		return $public_base;
	}

	public function getCategories() {
		static $_categories = null;
		if (null === $_categories) {
			$_categories = get_categories('get=all');
		}

		return $_categories;
	}

	public function getCategoriesId($categories) {
		static $_categories_id = null;
		if (null == $_categories_id) {
			foreach ($categories as $key => $category) {
				$_categories_id[ $category->term_id ] = $key;
			}
		}

		return $_categories_id;
	}

	/**
	 * *******************************
	 * *
	 * Methods for variable: options *
	 * *
	 * ******************************
	 */

	/**
	 * Get the value for an option element.
	 * If there's no option is set on the Admin page, return the default value.
	 *
	 * @param string $key
	 * @param string $option
	 *
	 * @return mixed
	 */
	public function getOptionElement($option, $key) {
		if ($this->options[ $option ][ $key ]) {
			$return = $this->options[ $option ][ $key ]; // From Admin Page
		} else {
			$return = $this->default_options[ $option ][ $key ]; // Default
		}

		return ($return);
	}

	/**
	 * return array
	 */
	public function getOptions() {
		return ($this->options);
	}

	/**
	 * @param array $options
	 */
	public function setOptions($options) {
		$this->options = $options;
	}

	public function handleInitializePlugin() {
		global $wpdb;

		/** @var AVH_EC_Category_Group $catgrp */
		$catgrp     = &AVH_EC_Singleton::getInstance('AVH_EC_Category_Group');
		$db_version = 4;

		$info['siteurl']      = get_option('siteurl');
		$info['plugin_dir']   = AVHEC_PLUGIN_DIR;
		$info['graphics_url'] = AVHEC_PLUGIN_URL . '/images';

		// Set class property for info
		$this->info = array(
			'home'         => get_option('home'),
			'siteurl'      => $info['siteurl'],
			'plugin_dir'   => $info['plugin_dir'],
			'js_dir'       => $info['plugin_dir'] . '/js',
			'graphics_url' => $info['graphics_url']
		);

		// Set the default options
		$this->default_options_general = array(
			'version'                          => $this->version,
			'dbversion'                        => $db_version,
			'alternative_name_select_category' => ''
		);

		// Set the default category group options
		$no_group_id                          = $catgrp->getTermIDBy('slug', 'none');
		$home_group_id                        = $catgrp->getTermIDBy('slug', 'home');
		$default_group_id                     = $catgrp->getTermIDBy('slug', 'all');
		$this->default_options_category_group = array(
			'no_group'      => $no_group_id,
			'home_group'    => $home_group_id,
			'default_group' => $default_group_id
		);

		$this->default_options_sp_category_group = array(
			'home_group'     => $home_group_id,
			'category_group' => $default_group_id,
			'day_group'      => $default_group_id,
			'month_group'    => $default_group_id,
			'year_group'     => $default_group_id,
			'author_group'   => $default_group_id,
			'search_group'   => $default_group_id
		);

		$this->default_options = array(
			'general'       => $this->default_options_general,
			'cat_group'     => $this->default_options_category_group,
			'widget_titles' => array(),
			'sp_cat_group'  => $this->default_options_sp_category_group
		);

		/**
		 * Set the options for the program
		 */
		$this->loadOptions();

		// Check if we have to do updates
		if (( ! isset($this->options['general']['dbversion'])) ||
		    $this->options['general']['dbversion'] < $db_version
		) {
			$this->doUpdateOptions($db_version);
		}

		$db = new AVH_DB();
		if ( ! $db->field_exists('avhec_term_order', $wpdb->terms)) {
			$wpdb->query("ALTER TABLE $wpdb->terms ADD `avhec_term_order` INT( 4 ) null DEFAULT '0'");
		}

		$this->handleTextdomain();
		add_filter('get_terms_orderby', array($this, 'applyOrderFilter'), 10, 2);
	}

	/**
	 * Loads the i18n
	 */
	public function handleTextdomain() {
		load_plugin_textdomain('avh-ec', false, AVHEC_RELATIVE_PLUGIN_DIR . '/lang');
	}

	/**
	 * Used in forms to set the checked option.
	 *
	 * @param mixed      $checked
	 * @param mixed_type $current
	 *
	 * @return string
	 * @since 2.0
	 */
	public function isChecked($checked, $current) {
		if ($checked == $current) {
			return (' checked="checked"');
		}

		return ('');
	}

	/**
	 * Used in forms to set the SELECTED option
	 *
	 * @param string $current
	 * @param string $field
	 *
	 * @return string
	 */
	public function isSelected($current, $field) {
		if ($current == $field) {
			return (' SELECTED');
		}

		return ('');
	}

	/**
	 * Retrieves the plugin options from the WordPress options table and assigns to class variable.
	 * If the options do not exists, like a new installation, the options are set to the default value.
	 *
	 * @return none
	 */
	public function loadOptions() {
		$options = get_option($this->db_options_core);
		if (false === $options) { // New installation
			$this->resetToDefaultOptions();
		} else {
			$this->setOptions($options);
		}
	}

	/**
	 * Reset to default options and save in DB
	 */
	public function resetToDefaultOptions() {
		$this->options = $this->default_options;
		$this->saveOptions($this->default_options);
	}

	/**
	 * Save all current options and set the options
	 *
	 * @param array $options
	 */
	public function saveOptions($options) {
		update_option($this->db_options_core, $options);
		wp_cache_flush(); // Delete cache
		$this->setOptions($options);
	}
}

/**
 * Create HTML dropdown list of Categories.
 *
 * @uses Walker
 */
class AVH_Walker_CategoryDropdown extends Walker_CategoryDropdown {
	public function walk($elements, $max_depth) {
		$args   = array_slice(func_get_args(), 2);
		$output = '';

		if ($max_depth < - 1) {
			return $output;
		}

		if (empty($elements)) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];

		// flat display
		if (- 1 == $max_depth) {
			$empty_array = array();
			foreach ($elements as $e) {
				$this->display_element($e, $empty_array, 1, 0, $args, $output);
			}

			return $output;
		}

		/*
		 * need to display in hierarchical order seperate elements into two buckets: top level and children elements children_elements is two dimensional array, eg. children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		foreach ($elements as $e) {
			if (0 == $e->$parent_field) {
				$top_level_elements[] = $e;
			} else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		/*
		 * when none of the elements is top level assume the first one must be root of the sub elements
		 */
		if (empty($top_level_elements)) {

			$first = array_slice($elements, 0, 1);
			$root  = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ($elements as $e) {
				if ($root->$parent_field == $e->$parent_field) {
					$top_level_elements[] = $e;
				} else {
					$children_elements[ $e->$parent_field ][] = $e;
				}
			}
		}

		foreach ($top_level_elements as $e) {
			$this->display_element($e, $children_elements, $max_depth, 0, $args, $output);
		}

		/*
		 * if we are displaying all levels, and remaining children_elements is not empty, then we got orphans, which should be displayed regardless
		 */
		if ((0 == $max_depth) && count($children_elements) > 0) {
			$empty_array = array();
			foreach ($children_elements as $orphans) {
				foreach ($orphans as $op) {
					$this->display_element($op, $empty_array, 1, 0, $args, $output);
				}
			}
		}

		return $output;
	}
}

<?php

/**
 * Created by PhpStorm.
 * User: benohead
 * Date: 09.05.14
 * Time: 13:55
 */
class WPCTC_Widget extends WP_Widget
{
    /**
     *
     */
    function __construct()
    {
        parent::__construct(
            'wpctc_widget',
            __('Category Tag Cloud', 'wpctc_widget_domain'),
            array('description' => __('WP Category Tag Cloud', 'wpctc_widget_domain'),)
        );
    }

    /**
     * @param $cat_id
     *
     * @return array
     */
    private function get_child_categories($cat_id)
    {
        $category_list = array();

        $args = array(
            'type' => 'post',
            'child_of' => $cat_id,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'hierarchical' => 1,
            'taxonomy' => 'category',
        );
        $child_categories = get_categories($args);

        if (!empty ($child_categories)) {
            foreach ($child_categories as $child_category) {
                $category_list[] = $child_category->term_id;
            }
        }
        return $category_list;
    }

    /**
     * @param $categories
     *
     * @return array
     */
    private function get_child_categories_list($categories)
    {
        $category_list = array();
        foreach ($categories as $cat_id) {
            if (!empty($cat_id)) {
                $category_list[] = $cat_id;
                $category_list = array_merge($category_list, $this->get_child_categories($cat_id));
            }
        }

        return $category_list;
    }

    /**
     * @param $options settings for this plugin
     * @return array
     */
    private function get_exclude_array($exclude)
    {
        $exclude_array = array();

        foreach (explode(',', $exclude) as $id) {
            if ($id != "") {
                array_push($exclude_array, $id);
            }
        }
        return $exclude_array;
    }

    /**
     * Converts any array to an array of ints by converting each element in the array to an int.
     *
     * @param $array_in array to be converted
     * @return array converted array
     */
    private function to_int_array($array_in)
    {
        $array_out = array();

        if (isset($array_in) && is_array($array_in)) {
            foreach ($array_in as $id) {
                array_push($array_out, intval($id));
            }
        }
        return $array_out;
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        global $wpdb;

        if (isset(WPCTC_Settings::get_instance()->settings['general'])
            && isset(WPCTC_Settings::get_instance()->settings['general']['do-not-load-scripts'])
            && WPCTC_Settings::get_instance()->settings['general']['do-not-load-scripts'] == 1) {
            wp_enqueue_style( $GLOBALS['wpctc']::PREFIX . 'wpctc');
            wp_enqueue_script( $GLOBALS['wpctc']::PREFIX . 'jquery-tagcanvas');
            wp_enqueue_script( $GLOBALS['wpctc']::PREFIX . 'wpctc-tagcanvas');
            wp_enqueue_script( $GLOBALS['wpctc']::PREFIX . 'jquery-style');
            wp_enqueue_script( $GLOBALS['wpctc']::PREFIX . 'wp-category-tag-cloud');
        }

        $md5 = md5(print_r(array_merge($args, $instance), true));
        $cache_id = 'wp_ctc_cache_' . $args['widget_id'];

        if (isset($instance['cache']) && $instance['cache'] === "1") {
            $current_time = time();
            $wp_ctc_cache = get_option($cache_id, array("", 0, ""));
            if ($wp_ctc_cache[1] > $current_time && $wp_ctc_cache[2] == $md5) {
                echo $wp_ctc_cache[0];
                return;
            }
        }

        ob_start();

        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if (isset($instance['child_categories']) && $instance['child_categories'] === "1" && isset($instance['category_id']) && count($instance['category_id']) > 0) {
            $instance['category_id'] = $this->get_child_categories_list($instance['category_id']);
        }

        $custom_taxonomies = get_taxonomies(array('public' => true, '_builtin' => false), 'objects', 'and');

        $from = "";
        $where = "";
        if ($custom_taxonomies) {
            foreach ($custom_taxonomies as $custom_taxonomy) {
                if (isset($instance[$custom_taxonomy->name . '_id']) && count($instance[$custom_taxonomy->name . '_id']) > 0) {
                    $from = $from . " INNER JOIN $wpdb->term_relationships as tr_" . $custom_taxonomy->name . " ON posts.ID = tr_" . $custom_taxonomy->name . ".object_ID "
                        . " INNER JOIN $wpdb->term_taxonomy as tt_" . $custom_taxonomy->name . " ON tr_" . $custom_taxonomy->name . ".term_taxonomy_id = tt_" . $custom_taxonomy->name . ".term_taxonomy_id ";
                    $where = $where . " AND tt_" . $custom_taxonomy->name . ".taxonomy = '" . $custom_taxonomy->name . "'" .
                        " AND tt_" . $custom_taxonomy->name . ".term_id IN (" . str_replace(",,", ",", implode(",", $instance[$custom_taxonomy->name . '_id'])) . ") ";
                }
            }
        }

        if (isset($instance['category_id']) && count($instance['category_id']) > 0) {
            $from = $from . " INNER JOIN $wpdb->term_relationships as tr1 ON posts.ID = tr1.object_ID
				INNER JOIN $wpdb->term_taxonomy as tt1 ON tr1.term_taxonomy_id = tt1.term_taxonomy_id ";
            $where = $where . " AND tt1.taxonomy = 'category' AND tt1.term_id IN (" . str_replace(",,", ",", implode(",", $instance['category_id'])) . ") ";
        }
        if (isset($instance['tag_id']) && count($instance['tag_id']) > 0) {
            $from = $from . " INNER JOIN $wpdb->term_relationships as tr3 ON posts.ID = tr3.object_ID
				INNER JOIN $wpdb->term_taxonomy as tt3 ON tr3.term_taxonomy_id = tt3.term_taxonomy_id ";
            $where = $where . " AND tt3.taxonomy = 'post_tag' AND tt3.term_id IN (" . str_replace(",,", ",", implode(",", $instance['tag_id'])) . ") ";
        }
        if (isset($instance['post_type']) && count($instance['post_type']) > 0) {
            $where = $where . " AND posts.post_type IN ('" . str_replace(",,", ",", implode("','", $instance['post_type'])) . "') ";
        }
        if (isset($instance['author']) && count($instance['author']) > 0) {
            $where = $where . " AND posts.post_author IN ('" . str_replace(",,", ",", implode("','", $instance['author'])) . "') ";
        }

        if (isset($instance['post_age']) && (is_int($instance['post_age']) || ctype_digit($instance['post_age'])) && intval($instance['post_age']) > 0) {
            $where = $where . " AND posts.post_date > '".date('Y-m-d', strtotime('-' . intval($instance['post_age']) . ' days'))."'";
        }

        if (isset($instance['post_count']) && (is_int($instance['post_count']) || ctype_digit($instance['post_count'])) && intval($instance['post_count']) > 0) {
            $where = $where . " GROUP BY tag_id HAVING count(distinct posts.ID) >= ".intval($instance['post_count']);
        }

        $tags = $wpdb->get_results
        (" SELECT DISTINCT tt2.term_id AS tag_id
			FROM $wpdb->posts as posts " .
            "	INNER JOIN $wpdb->term_relationships as tr2 ON posts.ID = tr2.object_ID
				INNER JOIN $wpdb->term_taxonomy as tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id " .
            $from .
            " WHERE posts.post_status = 'publish'
                AND tt2.taxonomy = '" . $instance['taxonomy'] . "' " .
            $where);

        $exclude = array();

        if (!empty($instance['exclude'])) {
            $exclude = $this->to_int_array($this->get_exclude_array($instance['exclude']));
        }

        $includeTags = '';
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                if ($instance['taxonomy'] == 'post_tag' && count($instance['tag_id']) > 0 && !in_array($tag->tag_id, $instance['tag_id'])) {
                    continue;
                }
                if (isset($instance[$instance['taxonomy'] + '_id'])
                    && count($instance[$instance['taxonomy'] + '_id']) > 0
                    && !in_array($tag->tag_id, $instance[$instance['taxonomy'] + '_id'])
                ) {
                    continue;
                }
                if (!in_array($tag->tag_id, $exclude)) {
                    $includeTags = $tag->tag_id . ',' . $includeTags;
                }
            }
        }

        $cloud_args = array(
            'smallest' => $instance['format'] == 'price' ? '100' : $instance['smallest'],
            'largest' => $instance['format'] == 'price' ? '100' : $instance['largest'],
            'unit' => '%',
            'number' => $instance['number'],
            'format' => $instance['format'] == 'price' ? 'flat' : $instance['format'] == 'bars' ? 'list' : $instance['format'] == 'rounded' ? 'list' : $instance['format'],
            'orderby' => $instance['order_by'],
            'order' => $instance['order'],
            'include' => null,
            'link' => 'view',
            'taxonomy' => $instance['taxonomy'],
            'echo' => $instance['format'] != 'array',
        );
        if (strlen($includeTags > 0)) {
            $cloud_args['include'] = $includeTags;
        }
        ?>
    <div
        id="<?php echo $args['widget_id']; ?>-tagcloud"
        class='wpctc-<?php echo $args['widget_id']; ?> <?php echo ($instance['format'] == 'price') ? "wpctc-tag-links" : ""; ?> <?php echo ($instance['format'] == 'bars') ? "wpctc-bars" : ""; ?> <?php echo ($instance['format'] == 'rounded') ? "wpctc-rounded" : ""; ?> <?php echo (isset($instance['opacity']) && $instance['opacity'] === "1") ? "wpctc-opacity" : ""; ?> <?php echo (isset($instance['tilt']) && $instance['tilt'] === "1") ? "wpctc-tilt" : ""; ?> <?php echo (isset($instance['colorize']) && $instance['colorize'] === "1") ? "wpctc-colorize" : ""; ?> wpctc-<?php echo($instance['format']); ?>'>
        <?php
        if ($instance['format'] == 'array') {
            $tags = wp_tag_cloud($cloud_args);
            ?>
            <canvas id="<?php echo $args['widget_id']; ?>_canvas" class="tagcloud-canvas"
                    data-tagcloud-color="<?php echo $instance['color']; ?>"
                    data-tagcloud-bordercolor="<?php echo !empty($instance['border']) ? $instance['border'] : "#ffff99"; ?>"
                    data-cloud-font=<?php echo !empty($instance['font']) ? '"'.$instance['font'].'"' : "null"; ?>
                    data-cloud-radiusx=<?php echo !empty($instance['radiusx']) ? '"'.$instance['radiusx'].'"' : "1"; ?>
                    data-cloud-radiusy=<?php echo !empty($instance['radiusy']) ? '"'.$instance['radiusy'].'"' : "1"; ?>
                    data-cloud-radiusz=<?php echo !empty($instance['radiusz']) ? '"'.$instance['radiusz'].'"' : "1"; ?>
                    data-cloud-zoom=<?php echo $instance['zoom']; ?>
                <?php echo isset($instance['width']) && is_numeric($instance['width']) && intval($instance['width']) != 0 ? " width='".$instance['width']."'" : ''; ?>
                <?php echo isset($instance['height']) && is_numeric($instance['height']) && intval($instance['height']) != 0 ? " height='".$instance['height']."'" : ''; ?>
                >
            </canvas>
            </div>
            <div id="<?php echo $args['widget_id']; ?>_canvas_tags">
            <ul>
                <?php foreach ($tags as $tag) { ?>
                    <li><?php echo($tag); ?></li>
                <?php } ?>
            </ul>
        <?php
        } elseif ($instance['format'] == 'bars') {
            ?>
            <ul class='wp-tag-cloud'>
                <?php
                $terms = get_terms($instance['taxonomy'], $cloud_args);
                $max = 1;
                foreach ($terms as $value) {
                    $term = (array)$value;
                    if ($max < $term['count']) {
                        $max = $term['count'];
                    }
                }
                foreach ($terms as $value) {
                    $term = (array)$value;
                    $width = 100 / $max * $term['count'];
                    $this_term = get_term_by('slug', $term['slug'], $instance['taxonomy']);
                    $style = 'width:' . $width . '%;';
                    if (isset($instance['background']) && !empty($instance['background'])) {
                        $style .= 'background-color: ' . $instance['background'] . ';';
                    }
                    if (isset($instance['border']) && !empty($instance['border'])) {
                        $style .= 'border-color: ' . $instance['border'] . ';';
                    }
                    ?>
                    <li style="<?= $style; ?>">
                        <a href="<?= print_r(get_term_link(intval($this_term->term_id), $this_term->taxonomy), true); ?>"><?= $term['name']; ?>
                            (<?= $term['count']; ?>)</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        <?php
        } else {
            wp_tag_cloud($cloud_args);
        }

        ?>
        </div>
        <?php
        if (isset($instance['color']) && !empty($instance['color'])) {
            ?>
            <style type="text/css">
                <?php echo ".wpctc-".$args['widget_id']; ?>
                a {
                    color: <?php echo $instance['color']; ?> !important;
                }

                <?php echo ".wpctc-tag-links.wpctc-".$args['widget_id']; ?>
                a:after {
                    background-color: <?php echo $instance['color']; ?> !important;
                }
            </style>
        <?php
        }
        if (($instance['format'] == 'rounded' || $instance['format'] == 'price') && isset($instance['background']) && !empty($instance['background'])) {
            ?>
            <style type="text/css">
                <?php echo ".wpctc-".$args['widget_id']; ?>
                a {
                    background-color: <?php echo $instance['background']; ?> !important;
                }

                <?php echo ".wpctc-tag-links.wpctc-".$args['widget_id']; ?>
                a:before {
                    border-right-color: <?php echo $instance['background']; ?> !important;
                }
            </style>
        <?php
        }
        if ($instance['format'] == 'rounded' && isset($instance['border']) && !empty($instance['border'])) {
            ?>
            <style type="text/css">
                <?php echo ".wpctc-".$args['widget_id']; ?>
                a {
                    border-color: <?php echo $instance['border']; ?> !important;
                }
            </style>
        <?php
        }
        echo $args['after_widget'];

        $output = ob_get_clean();

        if (isset($instance['nofollow']) && $instance['nofollow'] === "1") {
            $output = str_replace('<a href=', '<a rel="nofollow" href=', $output);
        }

        if (isset($instance['cache']) && $instance['cache'] === "1") {
            $timeout = isset($instance['timeout']) && is_numeric($instance['timeout']) ? $instance['timeout'] : 60;
            update_option($cache_id, array($output, $current_time + $timeout, $md5));
        }

        echo $output;
    }

    /**
     * @param array $instance
     */
    public function form($instance)
    {
        $title = (!empty($instance['title'])) ? strip_tags($instance['title']) : '';
        $exclude = (!empty($instance['exclude'])) ? strip_tags($instance['exclude']) : '';
        $font = (!empty($instance['font'])) ? strip_tags($instance['font']) : '';
        $category_id = isset($instance['category_id']) ? $instance['category_id'] : array();
        $child_categories = isset($instance['child_categories']) ? $instance['child_categories'] : "0";
        $opacity = isset($instance['opacity']) ? $instance['opacity'] : "0";
        $tilt = isset($instance['tilt']) ? $instance['tilt'] : "0";
        $colorize = isset($instance['colorize']) ? $instance['colorize'] : "0";
        $cache = isset($instance['cache']) ? $instance['cache'] : "0";
        $nofollow = isset($instance['nofollow']) ? $instance['nofollow'] : "0";
        $post_type = isset($instance['post_type']) ? $instance['post_type'] : array();
        $author = isset($instance['author']) ? $instance['author'] : array();
        $tag_id = isset($instance['tag_id']) ? $instance['tag_id'] : array();
        $order_by = isset($instance['order_by']) && strlen($instance['order_by']) > 0 ? $instance['order_by'] : 'name';
        $order = isset($instance['order']) && strlen($instance['order']) > 0 ? $instance['order'] : 'ASC';
        $format = isset($instance['format']) && strlen($instance['format']) > 0 ? $instance['format'] : 'flat';
        $number = isset($instance['number']) && (is_int($instance['number']) || ctype_digit($instance['number'])) ? $instance['number'] : 0;
        $post_age = isset($instance['post_age']) && (is_int($instance['post_age']) || ctype_digit($instance['post_age'])) ? $instance['post_age'] : 0;
        $post_count = isset($instance['post_count']) && (is_int($instance['post_count']) || ctype_digit($instance['post_count'])) ? $instance['post_count'] : 0;
        $taxonomy = isset($instance['taxonomy']) && strlen($instance['taxonomy']) > 0 ? $instance['taxonomy'] : 'post_tag';
        $width = isset($instance['width']) && is_numeric($instance['width']) ? $instance['width'] : 0;
        $height = isset($instance['height']) && is_numeric($instance['height']) ? $instance['height'] : 0;
        $radiusx = isset($instance['radiusx']) ? $instance['radiusx'] : '1';
        $radiusy = isset($instance['radiusy']) ? $instance['radiusy'] : '1';
        $radiusz = isset($instance['radiusz']) ? $instance['radiusz'] : '1';
        $zoom = isset($instance['zoom']) && is_numeric($instance['zoom']) ? $instance['zoom'] : 1;
        $timeout = isset($instance['timeout']) && is_numeric($instance['timeout']) ? $instance['timeout'] : 60;
        $smallest = isset($instance['smallest']) && (is_int($instance['smallest']) || ctype_digit($instance['smallest'])) ? $instance['smallest'] : 75;
        $largest = isset($instance['largest']) && (is_int($instance['largest']) || ctype_digit($instance['largest'])) ? $instance['largest'] : 200;
        $color = (!empty($instance['color'])) ? strip_tags($instance['color']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            $color = '';
        }
        $background = (!empty($instance['background'])) ? strip_tags($instance['background']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $background)) {
            $background = '';
        }
        $border = (!empty($instance['border'])) ? strip_tags($instance['border']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $border)) {
            $border = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Display:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('taxonomy'); ?>"
                    name="<?php echo $this->get_field_name('taxonomy'); ?>" class="widefat">
                <?php
                $taxonomies = get_taxonomies('', 'objects');
                foreach ($taxonomies as $field) {
                    ?>
                    <option
                        value="<?php echo($field->name); ?>" <?php selected($field->name, $taxonomy); ?>><?php echo $field->label; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>"
                   name="<?php echo $this->get_field_name('exclude'); ?>" type="text"
                   value="<?php echo esc_attr($exclude); ?>"/>
            <small><em><?php _e('comma separated list of term IDs'); ?></em></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Max displayed items:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="text"
                   value="<?php echo esc_attr($number); ?>"/>
            <small><em><?php _e('0 means display all'); ?></em></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_age'); ?>"><?php _e('Max post age in days:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('post_age'); ?>"
                   name="<?php echo $this->get_field_name('post_age'); ?>" type="text"
                   value="<?php echo esc_attr($post_age); ?>"/>
            <small><em><?php _e('0 means display all'); ?></em></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Min number of posts:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>"
                   name="<?php echo $this->get_field_name('post_count'); ?>" type="text"
                   value="<?php echo esc_attr($post_count); ?>"/>
            <small><em><?php _e('0 returns the same as 1'); ?></em></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Post authors:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('author'); ?>"
                    name="<?php echo $this->get_field_name('author'); ?>[]" size=3 multiple="multiple"
                    class="widefat"
                    style="height: auto;">
                <?php
                $allUsers = get_users('orderby=post_count&order=DESC');
                $authors = array();
                // Remove subscribers from the list as they won't write any articles
                foreach ($allUsers as $currentUser) {
                    if (!in_array('subscriber', $currentUser->roles)) {
                        $authors[] = $currentUser;
                    }
                }
                if ($authors) {
                    foreach ($authors as $aut) {
                        $label = esc_html($aut->display_name);
                        ?>
                        <option value="<?php echo($aut->ID); ?>"
                        <?php
                        if (in_array($aut->ID, $author)) {
                            echo("selected='selected'");
                        }
                       echo ">$label</option>";
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post types:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('post_type'); ?>"
                    name="<?php echo $this->get_field_name('post_type'); ?>[]" size=3 multiple="multiple"
                    class="widefat"
                    style="height: auto;">
                <?php
                $post_types = get_post_types('', 'objects');
                if ($post_types) {
                    foreach ($post_types as $name => $type) {
                        $label = esc_html($type->label);
                        ?>
                        <option value="<?php echo($name); ?>"
                        <?php
                        if (in_array($name, $post_type)) {
                            echo("selected='selected'");
                        }
                       echo ">$label</option>";
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category_id'); ?>"><?php _e('Categories:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('category_id'); ?>"
                    name="<?php echo $this->get_field_name('category_id'); ?>[]" size=3 multiple="multiple"
                    class="widefat"
                    style="height: auto;">
                <?php
                $categories = get_categories(array('hide_empty' => 0));

                if ($categories) {
                    foreach ($categories as $category) {
                        $category->name = esc_html($category->name);
                        ?>
                        <option value="<?php echo($category->term_id); ?>"
                        <?php
                        if (in_array($category->term_id, $category_id)) {
                            echo("selected='selected'");
                        }
                       echo ">$category->name</option>";
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('child_categories'); ?>"
                   name="<?php echo $this->get_field_name('child_categories'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($child_categories, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('child_categories'); ?>"><?php _e('Include children'); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tag_id'); ?>"><?php _e('Tags:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('tag_id'); ?>"
                    name="<?php echo $this->get_field_name('tag_id'); ?>[]" size=3 multiple="multiple"
                    class="widefat"
                    style="height: auto;">
                <?php
                $tags = get_tags(array('hide_empty' => 0));

                if ($tags) {
                    foreach ($tags as $tag) {
                        $tag->name = esc_html($tag->name);
                        ?>
                        <option value="<?php echo($tag->term_id); ?>"
                        <?php
                        if (in_array($tag->term_id, $tag_id)) {
                            echo("selected='selected'");
                        }
                       echo ">$tag->name</option>";
                    }
                }
                ?>
            </select>
        </p>
        <?php
        $custom_taxonomies = get_taxonomies(array('public' => true, '_builtin' => false), 'objects', 'and');
        if ($custom_taxonomies) {
            foreach ($custom_taxonomies as $custom_taxonomy) {
                $term_id = isset($instance[$custom_taxonomy->name . '_id']) ? $instance[$custom_taxonomy->name . '_id'] : array();

                ?>
                <p>
                    <label
                        for="<?php echo $this->get_field_id($custom_taxonomy->name . '_id'); ?>"><?php echo($custom_taxonomy->label . ':'); ?></label><br/>
                    <select id="<?php echo $this->get_field_id($custom_taxonomy->name . '_id'); ?>"
                            name="<?php echo $this->get_field_name($custom_taxonomy->name . '_id'); ?>[]" size=3
                            multiple="multiple"
                            class="widefat"
                            style="height: auto;">
                        <?php
                        $terms = get_terms($custom_taxonomy->name, array('hide_empty' => 0));

                        if ($terms) {
                            foreach ($terms as $term) {
                                $term->name = esc_html($term->name);
                                ?>
                                <option value="<?php echo($term->term_id); ?>"
                                <?php
                                if (in_array($term->term_id, $term_id)) {
                                    echo("selected='selected'");
                                }
                               echo ">$term->name</option>";
                            }
                        }
                        ?>
                    </select>
                </p>
            <?php
            }
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('order_by'); ?>"><?php _e('Order By:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('order_by'); ?>"
                    name="<?php echo $this->get_field_name('order_by'); ?>" class="widefat">
                <?php
                $taxonomies = array('name' => __('Name'), 'count' => __('Count'));
                foreach ($taxonomies as $field_id => $field_name) {
                    ?>
                    <option
                        value="<?php echo($field_id); ?>" <?php selected($field_id, $order_by); ?>><?php echo $field_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('order'); ?>"
                    name="<?php echo $this->get_field_name('order'); ?>" class="widefat">
                <?php
                $taxonomies = array('ASC' => __('Ascending'),
                    'DESC' => __('Descending'),
                    'RAND' => __('Random')
                );
                foreach ($taxonomies as $field_id => $field_name) {
                    ?>
                    <option
                        value="<?php echo($field_id); ?>" <?php selected($field_id, $order); ?>><?php echo $field_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:'); ?></label><br/>
            <select id="<?php echo $this->get_field_id('format'); ?>"
                    name="<?php echo $this->get_field_name('format'); ?>" class="widefat cloud-type-selector">
                <?php
                $taxonomies = array(
                    'flat' => __('Separated by whitespace'),
                    'price' => __('Price tags'),
                    'bars' => __('Bars'),
                    'rounded' => __('Rounded corners'),
                    'list' => __('UL with a class of wp-tag-cloud'),
                    'array' => __('3D HTML5 Cloud')
                );
                foreach ($taxonomies as $field_id => $field_name) {
                    ?>
                    <option
                        value="<?php echo($field_id); ?>" <?php selected($field_id, $format); ?>><?php echo $field_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>"
                   name="<?php echo $this->get_field_name('width'); ?>" type="text"
                   value="<?php echo esc_attr($width); ?>"/>
            <small><em><?php _e('width of the containing canvas in pixels'); ?></em></small>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>"
                   name="<?php echo $this->get_field_name('height'); ?>" type="text"
                   value="<?php echo esc_attr($height); ?>"/>
            <small><em><?php _e('height of the containing canvas in pixels'); ?></em></small>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('radiusx'); ?>"><?php _e('Radius X:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('radiusx'); ?>"
                   name="<?php echo $this->get_field_name('radiusx'); ?>" type="text"
                   value="<?php echo esc_attr($radiusx); ?>"/>
            <small><em><?php _e('Initial size of cloud from centre to sides'); ?></em></small>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('radiusy'); ?>"><?php _e('Radius Y:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('radiusy'); ?>"
                   name="<?php echo $this->get_field_name('radiusy'); ?>" type="text"
                   value="<?php echo esc_attr($radiusy); ?>"/>
            <small><em><?php _e('Initial size of cloud from centre to top and bottom'); ?></em></small>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('radiusz'); ?>"><?php _e('Radius Z:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('radiusz'); ?>"
                   name="<?php echo $this->get_field_name('radiusz'); ?>" type="text"
                   value="<?php echo esc_attr($radiusz); ?>"/>
            <small><em><?php _e('Initial size of cloud from centre to front and back'); ?></em></small>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Initial zoom factor:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>"
                   name="<?php echo $this->get_field_name('zoom'); ?>" type="text"
                   value="<?php echo esc_attr($zoom); ?>"/>
        </p>
        <p class="canvas-config">
            <label
                for="<?php echo $this->get_field_id('font'); ?>"><?php _e('Font family:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('font'); ?>"
                   name="<?php echo $this->get_field_name('font'); ?>" type="text"
                   value="<?php echo esc_attr($font); ?>"/>
        </p>
        <p class="cloud-non-price">
            <label
                for="<?php echo $this->get_field_id('smallest'); ?>"><?php _e('Size of the smallest item (in %):'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('smallest'); ?>"
                   name="<?php echo $this->get_field_name('smallest'); ?>" type="text"
                   value="<?php echo esc_attr($smallest); ?>"/>
        </p>
        <p class="cloud-non-price">
            <label
                for="<?php echo $this->get_field_id('largest'); ?>"><?php _e('Size of the largest item (in %):'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('largest'); ?>"
                   name="<?php echo $this->get_field_name('largest'); ?>" type="text"
                   value="<?php echo esc_attr($largest); ?>"/>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('cache'); ?>"
                   name="<?php echo $this->get_field_name('cache'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($cache, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('cache'); ?>"><?php _e('Cache cloud'); ?></label>
            <label
                for="<?php echo $this->get_field_id('timeout'); ?>"><?php _e('for'); ?></label>
            <input size="6" id="<?php echo $this->get_field_id('timeout'); ?>"
                   name="<?php echo $this->get_field_name('timeout'); ?>" type="text"
                   value="<?php echo esc_attr($timeout); ?>"/>
            <?php _e('seconds'); ?>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('opacity'); ?>"
                   name="<?php echo $this->get_field_name('opacity'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($opacity, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('opacity'); ?>"><?php _e('Adapt opacity'); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('tilt'); ?>"
                   name="<?php echo $this->get_field_name('tilt'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($tilt, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('tilt'); ?>"><?php _e('Tilt terms'); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('nofollow'); ?>"
                   name="<?php echo $this->get_field_name('nofollow'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($nofollow, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('nofollow'); ?>"><?php _e('No-follow links'); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('colorize'); ?>"
                   name="<?php echo $this->get_field_name('colorize'); ?>" type="checkbox"
                   class="widefat"
                   style="height: auto;"
                   value="1"
                <?php echo checked($colorize, "1"); ?>>
            <label
                for="<?php echo $this->get_field_id('colorize'); ?>"><?php _e('Random color'); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Font color:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('color'); ?>"
                   name="<?php echo $this->get_field_name('color'); ?>" type="text"
                   value="<?php echo esc_attr($color); ?>"/>
            <small><em><?php _e('Leave empty to use the default theme color.'); ?></em></small>
            <span class="wpctc-color-picker" rel="<?php echo $this->get_field_id('color'); ?>"></span>
        </p>
        <p class="bars-config">
            <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background color:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>"
                   name="<?php echo $this->get_field_name('background'); ?>" type="text"
                   value="<?php echo esc_attr($background); ?>"/>
            <small><em><?php _e('Leave empty to use the default theme background color.'); ?></em></small>
            <span class="wpctc-color-picker" rel="<?php echo $this->get_field_id('background'); ?>"></span>
        </p>
        <p class="bars-config border-color">
            <label for="<?php echo $this->get_field_id('border'); ?>"><?php _e('Border color:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('border'); ?>"
                   name="<?php echo $this->get_field_name('border'); ?>" type="text"
                   value="<?php echo esc_attr($border); ?>"/>
            <small><em><?php _e('Leave empty to use the default theme border color.'); ?></em></small>
            <span class="wpctc-color-picker" rel="<?php echo $this->get_field_id('border'); ?>"></span>
        </p>
    <?php
    }

// Updating widget replacing old instances with new
    /**
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : __('', 'wpctc_widget_domain');
        $instance['exclude'] = (!empty($new_instance['exclude'])) ? strip_tags($new_instance['exclude']) : '';
        $instance['font'] = (!empty($new_instance['font'])) ? strip_tags($new_instance['font']) : '';
        $custom_taxonomies = get_taxonomies(array('public' => true, '_builtin' => false), 'objects', 'and');
        if ($custom_taxonomies) {
            foreach ($custom_taxonomies as $custom_taxonomy) {
                $instance[$custom_taxonomy->name . '_id'] = isset($new_instance[$custom_taxonomy->name . '_id']) ? $new_instance[$custom_taxonomy->name . '_id'] : array();
            }
        }
        $instance['post_type'] = isset($new_instance['post_type']) ? $new_instance['post_type'] : array();
        $instance['author'] = isset($new_instance['author']) ? $new_instance['author'] : array();
        $instance['category_id'] = isset($new_instance['category_id']) ? $new_instance['category_id'] : array();
        $instance['child_categories'] = isset($new_instance['child_categories']) ? $new_instance['child_categories'] : "0";
        $instance['opacity'] = isset($new_instance['opacity']) ? $new_instance['opacity'] : "0";
        $instance['tilt'] = isset($new_instance['tilt']) ? $new_instance['tilt'] : "0";
        $instance['colorize'] = isset($new_instance['colorize']) ? $new_instance['colorize'] : "0";
        $instance['nofollow'] = isset($new_instance['nofollow']) ? $new_instance['nofollow'] : "0";
        $instance['cache'] = isset($new_instance['cache']) ? $new_instance['cache'] : "0";
        $instance['tag_id'] = isset($new_instance['tag_id']) ? $new_instance['tag_id'] : array();
        $instance['order_by'] = isset($new_instance['order_by']) && strlen($new_instance['order_by']) > 0 ? $new_instance['order_by'] : 'name';
        $instance['order'] = isset($new_instance['order']) && strlen($new_instance['order']) > 0 ? $new_instance['order'] : 'ASC';
        $instance['format'] = isset($new_instance['format']) && strlen($new_instance['format']) > 0 ? $new_instance['format'] : 'flat';
        $instance['number'] = isset($new_instance['number']) && (is_int($new_instance['number']) || ctype_digit($new_instance['number'])) ? $new_instance['number'] : 0;
        $instance['post_age'] = isset($new_instance['post_age']) && (is_int($new_instance['post_age']) || ctype_digit($new_instance['post_age'])) ? $new_instance['post_age'] : 0;
        $instance['post_count'] = isset($new_instance['post_count']) && (is_int($new_instance['post_count']) || ctype_digit($new_instance['post_count'])) ? $new_instance['post_count'] : 0;
        $instance['taxonomy'] = isset($new_instance['taxonomy']) && strlen($new_instance['taxonomy']) > 0 ? $new_instance['taxonomy'] : 'post_tag';
        $instance['width'] = isset($new_instance['width']) && is_numeric($new_instance['width']) ? $new_instance['width'] : 0;
        $instance['height'] = isset($new_instance['height']) && is_numeric($new_instance['height']) ? $new_instance['height'] : 0;
        $instance['radiusx'] = isset($new_instance['radiusx']) ? $new_instance['radiusx'] : '1';
        $instance['radiusy'] = isset($new_instance['radiusy']) ? $new_instance['radiusy'] : '1';
        $instance['radiusz'] = isset($new_instance['radiusz']) ? $new_instance['radiusz'] : '1';
        $instance['zoom'] = isset($new_instance['zoom']) && is_numeric($new_instance['zoom']) ? $new_instance['zoom'] : 1;
        $instance['timeout'] = isset($new_instance['timeout']) && is_numeric($new_instance['timeout']) ? $new_instance['timeout'] : 60;
        $instance['smallest'] = isset($new_instance['smallest']) && (is_int($new_instance['smallest']) || ctype_digit($new_instance['smallest'])) ? $new_instance['smallest'] : 75;
        $instance['largest'] = isset($new_instance['largest']) && (is_int($new_instance['largest']) || ctype_digit($new_instance['largest'])) ? $new_instance['largest'] : 200;
        $color = (!empty($new_instance['color'])) ? strip_tags($new_instance['color']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            $color = '';
        }
        $instance['color'] = $color;
        $background = (!empty($new_instance['background'])) ? strip_tags($new_instance['background']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $background)) {
            $background = '';
        }
        $instance['background'] = $background;
        $border = (!empty($new_instance['border'])) ? strip_tags($new_instance['border']) : '';
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $border)) {
            $border = '';
        }
        $instance['border'] = $border;

        if (isset(WPCTC_Settings::get_instance()->settings)
            && isset(WPCTC_Settings::get_instance()->settings['general'])
            && isset(WPCTC_Settings::get_instance()->settings['general']['clear-cache-on-save'])
            && WPCTC_Settings::get_instance()->settings['general']['clear-cache-on-save'] == 1) {
            WordPress_Category_Tag_Cloud::clear_caching_plugins();
        }

        return $instance;
    }
} // Class wpctc_widget ends here

// Register and load the widget
/**
 *
 */
function wpctc_load_widget()
{
    register_widget('wpctc_widget');
}

add_action('widgets_init', 'wpctc_load_widget');
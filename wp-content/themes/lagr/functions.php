<?php

// breadcrumb
function nav_breadcrumb() {
    $delimiter = '&gt;';
    $home = 'Startseite';
    $before = '<span class="current">';
    $after = '</span>';
    $ls = 'Deutschdidaktik 2.0'; // Lehrstuhl einbringen
    $ls_link = home_url() . "?cat=6";

    if (!is_home() && !is_front_page() || is_paged()) {

        echo '<div id="breadcrumb" class="left">';

        global $post;
        $homeLink = get_bloginfo('url');
        echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_page()) {
            echo '<a href="' . $ls_link . '">' . $ls . '</a> ' . $delimiter . ' ' . get_the_title();
        } elseif (is_category()) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0)
                echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            echo $before . single_cat_title('', false) . $after;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $before . get_the_title() . $after;
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_page() && !$post->post_parent) {
            echo $before . get_the_title() . $after;
        } elseif (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb)
                echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_search()) {
            echo $before . 'Ergebnisse f&uuml;r Ihre Suche nach "' . get_search_query() . '"' . $after;
        } elseif (is_tag()) {
            echo $before . 'Beiträge mit dem Schlagwort "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Beiträge veröffentlicht von ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Fehler 404' . $after;
        }

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ' (';
            echo ': ' . __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ')';
        }

        echo '</div>';
    }
}

function fb_filter_child_cats($query) {

    $cat = get_term_by('name', $query->query_vars['category_name'], 'category');
    $child_cats = (array) get_term_children($cat->term_id, 'category');

    if (!$query->is_admin)
        $query->set('category__not_in', array_merge($child_cats));

    return $query;
}

add_filter('pre_get_posts', 'fb_filter_child_cats');

/*

  if ( !function_exists('fb_filter_child_cats') ) {
  function fb_filter_child_cats( $cats= '' ) {
  global $wp_query, $wpdb;

  if ( is_category() ) {

  // get children ID's
  if ( $excludes = get_categories( "child_of=" . $wp_query->get('cat') ) ) {

  // set array with ID's
  foreach ( $excludes as $key => $value ) {
  $exclude[] = $value->cat_ID;
  }
  }

  // remove child cats
  if ( isset($exclude) && is_array($exclude) ) {
  $cats .= " AND " . $wpdb->prefix . "term_taxonomy.term_id NOT IN (" . implode(",", $exclude) . ") ";
  }
  }

  return $cats;
  }

  if ( !is_admin() ) {
  add_filter( 'posts_where', 'fb_filter_child_cats' );
  }
  }
 */
register_sidebar();

function sc_archive() {
    return wp_get_archives();
}

add_shortcode('archives', 'sc_archive');

if ( function_exists('register_nav_menus') ) {
    register_nav_menus(array(
        'header-nav' => __( 'Kopfzeilen-Navigation' ),
		'left-nav' => __( 'Sidebar-Navigation' )
    ));
}

?>

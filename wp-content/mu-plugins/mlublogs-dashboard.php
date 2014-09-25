<?php
/**
 * Plugin Name: * mluBlogs Dashboard
 * Author: Blogs@MLU Team
 * Author URI: mailto:blogs@uni-halle.de
 * Version: 1.1
 * Description: modifiziert das Standard WP-Dashboard: * Entfernt Dashboard-Widgets: Plugins, Primary und Secundary Feed. * Fügt die Feeds Blogportal und Blogs-News hinzu. * Setzt die Caching-Dauer für gerenderte Feeds auf 30 Minuten</li></ul>
 */

add_action('wp_dashboard_setup', 'my_dashboard_widgets');
function my_dashboard_widgets() {
     global $wp_meta_boxes;
     unset(
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']
     );
     wp_add_dashboard_widget( 'dashboard_blogsmlu-news', 'blogs@mlu News' , 'mlu_dashboard_blogs_news' );
     wp_add_dashboard_widget( 'dashboard_blogportal', 'Neues im Blogportal' , 'mlu_dashboard_blogportal' );


}
function mlu_dashboard_blogs_news () {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'https://blogs.urz.uni-halle.de/feed/',
          'items' => 3,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 1
     ));
     echo '</div>';
}
                                                                                                                                                                                                              
function mlu_dashboard_blogportal () {
     echo '<div class="rss-widget">';
     echo '<p>Beiträge aus dem <a href="http://blogportal.urz.uni-halle.de/" title="Zum Blogportal der MLU" target="_blank">Blogportal</a> der MLU.</p>';
     wp_widget_rss_output(array(
          'url' => 'http://feeds.feedburner.com/mlublogs_rss?format=xml',
          'items' => 14,
          'show_summary' => 0,
          'show_author' => 0,
          'show_date' => 1
     ));
     echo '</div>';
}


// feed-cache-lifetime
add_filter( 'wp_feed_cache_transient_lifetime', function() {return 1800;} );

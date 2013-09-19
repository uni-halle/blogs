<?php 
    
// Remove image dimentions from woo_get_image images
update_option('woo_force_all',false);
update_option('woo_force_single',false);

// YoastPosts
    
if (!class_exists('YoastPosts')) {
	class YoastPosts {

		// Class initialization
		function YoastPosts() {
			if (isset($_GET['show_yoast_widget'])) {
				if ($_GET['show_yoast_widget'] == "true") {
					update_option( 'show_yoast_widget', 'noshow' );
				} else {
					update_option( 'show_yoast_widget', 'show' );
				}
			} 
		
			// Add the widget to the dashboard
			add_action( 'wp_dashboard_setup', array(&$this, 'register_widget') );
			add_filter( 'wp_dashboard_widgets', array(&$this, 'add_widget') );
		}

		// Register this widget -- we use a hook/function to make the widget a dashboard-only widget
		function register_widget() {
			wp_register_sidebar_widget( 'yoast_posts', __( 'Yoast - Tweaking Websites', 'yoast-posts' ), array(&$this, 'widget'), array( 'all_link' => 'http://yoast.com/', 'feed_link' => 'http://yoast.com/feed/', 'edit_link' => 'options.php' ) );
		}

		// Modifies the array of dashboard widgets and adds this plugin's
		function add_widget( $widgets ) {
			global $wp_registered_widgets;
			if ( !isset($wp_registered_widgets['yoast_posts']) ) return $widgets;
			array_splice( $widgets, 2, 0, 'yoast_posts' );
			return $widgets;
		}

		function widget($args = array()) {
			$show = get_option('show_yoast_widget');
			if ($show != 'noshow') {
				if (is_array($args))
					extract( $args, EXTR_SKIP );
				echo $before_widget.$before_title.$widget_name.$after_title;
				echo '<a href="http://yoast.com/"><img style="margin: 0 0 5px 5px;" src="http://yoast.com/images/yoast-logo-rss.png" align="right" alt="Yoast"/></a>';
				include_once(ABSPATH . WPINC . '/rss.php');
				$rss = fetch_rss('http://feeds.feedburner.com/joostdevalk');
				$items = array_slice($rss->items, 0, 2);
				?>
				<?php if (empty($items)) echo '<li>No items</li>';
				else
				foreach ( $items as $item ) : ?>
				<a style="font-size: 14px; font-weight:bold;" href='<?php echo $item['link']; ?>' title='<?php echo $item['title']; ?>'><?php echo $item['title']; ?></a><br/> 
				<p style="font-size: 10px; color: #aaa;"><?php echo date('j F Y',strtotime($item['pubdate'])); ?></p>
				<p><?php echo substr($item['summary'],0,strpos($item['summary'], "This is a post from")); ?></p>
				<?php endforeach;
				echo $after_widget;
			}
		}
	}

	// Start this plugin once all other plugins are fully loaded
	add_action( 'plugins_loaded', create_function( '', 'global $YoastPosts; $YoastPosts = new YoastPosts();' ) );
}

/*
Plugin Name:  Yoast Breadcrumbs
Plugin URI:   http://yoast.com/wordpress/breadcrumbs/
Description:  Outputs a fully customizable breadcrumb path.
Version:      0.7.2
Author:       Joost de Valk
Author URI:   http://yoast.com/

Copyright (C) 2008, Joost de Valk
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Joost de Valk or Yoast nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

// Load some defaults
$opt 						= array();
$opt['home'] 				= "Home";
$opt['blog'] 				= "Blog";
$opt['sep'] 				= "&raquo;";
$opt['prefix']				= "You are here:";
$opt['boldlast'] 			= true;
$opt['nofollowhome'] 		= false;
$opt['singleparent'] 		= 0;
$opt['singlecatprefix']		= true;
$opt['archiveprefix'] 		= "Archives for";
$opt['searchprefix'] 		= "Search for";
add_option("yoast_breadcrumbs",$opt);

if ( ! class_exists( 'YoastBreadcrumbs_Admin' ) ) {

	class YoastBreadcrumbs_Admin {

		function add_config_page() {
			global $wpdb;
			if ( function_exists('add_submenu_page') ) {
				add_options_page('Yoast Breadcrumbs Configuration', 'Breadcrumbs', 10, basename(__FILE__),array('YoastBreadcrumbs_Admin','config_page'));
				add_filter( 'plugin_action_links', array( 'YoastBreadcrumbs_Admin', 'filter_plugin_actions'), 10, 2 );
				add_filter( 'ozh_adminmenu_icon', array( 'YoastBreadcrumbs_Admin', 'add_ozh_adminmenu_icon' ) );
			}
		}

		function add_ozh_adminmenu_icon( $hook ) {
			static $breadcrumbsicon;
			if (!$breadcrumbsicon) {
				$breadcrumbsicon = WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/script_link.png';
			}
			if ($hook == 'yoast-breadcrumbs.php') return $breadcrumbsicon;
			return $hook;
		}

		function filter_plugin_actions( $links, $file ){
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
			
			if ( $file == $this_plugin ){
				$settings_link = '<a href="options-general.php?page=yoast-breadcrumbs.php">' . __('Settings') . '</a>';
				array_unshift( $links, $settings_link ); // before other links
			}
			return $links;
		}

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Yoast Breadcrumbs options.'));
				check_admin_referer('yoast-breadcrumbs-updatesettings');
				
				foreach (array('home', 'blog', 'sep', 'singleparent', 'prefix', 'archiveprefix', 'searchprefix', 'breadcrumbprefix', 'breadcrumbsuffix') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = htmlentities(html_entity_decode($_POST[$option_name]));
					}
				}

				foreach (array('boldlast', 'nofollowhome', 'singlecatprefix') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = true;
					} else {
						$opt[$option_name] = false;
					}
				}
				
				update_option('yoast_breadcrumbs', $opt);
			}
			
			$opt  = get_option('yoast_breadcrumbs');
			?>
			<div class="wrap">
				<h2>Yoast Breadcrumbs Configuration</h2>
				<form action="" method="post" id="yoastbreadcrumbs-conf">
					<table class="form-table">
						<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('yoast-breadcrumbs-updatesettings'); } ?>
						<tr>
							<th scope="row" valign="top"><label for="sep">Separator between breadcrumbs:</label></th>
							<td><input type="text" name="sep" id="sep" value="<?php echo htmlentities($opt['sep']); ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="home">Anchor text for the Homepage:</label></th>
							<td><input type="text" name="home" id="home" value="<?php echo $opt['home']; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="blog">Anchor text for the Blog:</label></th>
							<td><input type="text" name="blog" id="blog" value="<?php echo $opt['blog']; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="prefix">Prefix for the breadcrumb path:</label></th>
							<td><input type="text" name="prefix" id="prefix" value="<?php echo $opt['prefix']; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="archiveprefix">Prefix for Archive breadcrumbs:</label></th>
							<td><input type="text" name="archiveprefix" id="archiveprefix" value="<?php echo $opt['archiveprefix']; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="searchprefix">Prefix for Search Page breadcrumbs:</label></th>
							<td><input type="text" name="searchprefix" id="searchprefix" value="<?php echo $opt['searchprefix']; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="singlecatprefix">Show category in post breadcrumbs? <small style="font-weight: normal;">(Shows the category inbetween Home and the blogpost)</small></label></th>
							<td><input type="checkbox" id="singlecatprefix" name="singlecatprefix" <?php if ($opt['singlecatprefix']) { echo 'checked="checked"'; } ?>/></td>
						</tr>						
						<tr>
							<th scope="row" valign="top"><label for="singleparent">Show Parent Page for Blog posts?</label> <small style="font-weight: normal;">(Adds another page inbetween Home and the blogpost)</small></th>
							<td><?php wp_dropdown_pages("depth=0&name=singleparent&show_option_none=-- None --&selected=".$opt['singleparent']); ?></td>
						</tr>						
						<tr>
							<th scope="row" valign="top"><label for="boldlast">Bold the last page in the breadcrumb?</label></th>
							<td><input type="checkbox" id="boldlast" name="boldlast" <?php if ($opt['boldlast']) { echo 'checked="checked"'; } ?>/></td>
						</tr>						
						<tr>
							<th scope="row" valign="top"><label for="nofollowhome">Nofollow the link to the home page?</label></th>
							<td><input type="checkbox" id="nofollowhome" name="nofollowhome" <?php if ($opt['nofollowhome']) { echo 'checked="checked"'; } ?>/></td>
						</tr>						
					</table>
					<br/>
					<span class="submit" style="border: 0;"><input type="submit" name="submit" value="Save Settings" /></span>
				</form>
			</div>
<?php		}
	}
}

function yoast_breadcrumb($prefix = '', $suffix = '', $display = true) {
	global $wp_query, $post;
	
	$opt = get_option("yoast_breadcrumbs");

	function bold_or_not($input) {
		$opt = get_option("yoast_breadcrumbs");
		if ($opt['boldlast']) {
			return '<strong>'.$input.'</strong>';
		} else {
			return $input;
		}
	}

	// Copied and adapted from WP source
	function yoast_get_category_parents($id, $link = FALSE, $separator = '/', $nicename = FALSE){
		$chain = '';
		$parent = &get_category($id);
		if ( is_wp_error( $parent ) )
		   return $parent;

		if ( $nicename )
		   $name = $parent->slug;
		else
		   $name = $parent->cat_name;

		if ( $parent->parent && ($parent->parent != $parent->term_id) )
		   $chain .= get_category_parents($parent->parent, true, $separator, $nicename);

		$chain .= bold_or_not($name);
		return $chain;
	}
	
	$nofollow = ' ';
	if ($opt['nofollowhome']) {
		$nofollow = ' rel="nofollow" ';
	}
	
	$on_front = get_option('show_on_front');
	
	if ($on_front == "page") {
		$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'">'.$opt['home'].'</a>';
		$bloglink = $homelink.' '.$opt['sep'].' <a href="'.get_permalink(get_option('page_for_posts')).'">'.$opt['blog'].'</a>';
	} else {
		$homelink = '<a'.$nofollow.'href="'.get_bloginfo('url').'">'.$opt['home'].'</a>';
		$bloglink = $homelink;
	}
		
	if ( ($on_front == "page" && is_front_page()) || ($on_front == "posts" && is_home()) ) {
		$output = bold_or_not($opt['home']);
	} elseif ( $on_front == "page" && is_home() ) {
		$output = $homelink.' '.$opt['sep'].' '.bold_or_not($opt['blog']);
	} elseif ( !is_page() ) {
		$output = $bloglink.' '.$opt['sep'].' ';
		if ( ( is_single() || is_category() || is_tag() || is_date() || is_author() ) && $opt['singleparent'] != 0) {
			$output .= '<a href="'.get_permalink($opt['singleparent']).'">'.get_the_title($opt['singleparent']).'</a> '.$opt['sep'].' ';
		} 
		if (is_single() && $opt['singlecatprefix']) {
			$cats = get_the_category();
			$cat = $cats[0];
			$output .= get_category_parents($cat->term_id, true, " ".$opt['sep']." ");
		}
		if ( is_category() ) {
			$cat = intval( get_query_var('cat') );
			$output .= yoast_get_category_parents($cat, false, " ".$opt['sep']." ");
		} elseif ( is_tag() ) {
			$output .= bold_or_not($opt['archiveprefix']." ".single_cat_title('',false));
		} elseif (is_date()) { 
			$output .= bold_or_not($opt['archiveprefix']." ".single_month_title(' ',false));
		} elseif (is_author()) { 
			$user = get_userdatabylogin($wp_query->query_vars['author_name']);
			$output .= bold_or_not($opt['archiveprefix']." ".$user->display_name);
		} elseif (is_search()) {
			$output .= bold_or_not($opt['searchprefix'].' "'.get_search_query().'"');
		} else {
			$output .= bold_or_not(get_the_title());
		}
	} else {
		$post = $wp_query->get_queried_object();

		// If this is a top level Page, it's simple to output the breadcrumb
		if ( 0 == $post->post_parent ) {
			$output = $homelink." ".$opt['sep']." ".bold_or_not(get_the_title());
		} else {
			if (isset($post->ancestors)) {
				if (is_array($post->ancestors))
					$ancestors = array_values($post->ancestors);
				else 
					$ancestors = array($post->ancestors);				
			} else {
				$ancestors = array($post->post_parent);
			}

			// Reverse the order so it's oldest to newest
			$ancestors = array_reverse($ancestors);

			// Add the current Page to the ancestors list (as we need it's title too)
			$ancestors[] = $post->ID;

			$links = array();			
			foreach ( $ancestors as $ancestor ) {
				$tmp  = array();
				$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
				$tmp['url'] 	= get_permalink($ancestor);
				$tmp['cur'] = false;
				if ($ancestor == $post->ID) {
					$tmp['cur'] = true;
				}
				$links[] = $tmp;
			}

			$output = $homelink;
			foreach ( $links as $link ) {
				$output .= ' '.$opt['sep'].' ';
				if (!$link['cur']) {
					$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>';
				} else {
					$output .= bold_or_not($link['title']);
				}
			}
		}
	}
	if ($opt['prefix'] != "") {
		$output = $opt['prefix']." ".$output;
	}
	if ($display) {
		echo $prefix.$output.$suffix;
	} else {
		return $prefix.$output.$suffix;
	}
}

add_action('admin_menu', array('YoastBreadcrumbs_Admin','add_config_page'));
    
    
?>
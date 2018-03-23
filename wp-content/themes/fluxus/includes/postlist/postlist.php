<?php
//activate scripts
function postlist_enqueue() {
	wp_enqueue_script('postlist', get_template_directory_uri().'/includes/postlist/postlist.js', array('jquery'), 'r3', false);
}
add_action('admin_init', 'postlist_enqueue');



function postlist() {
	add_meta_box( "postlist", "Liste anhängen", "postlist_form", "page", "normal", "default", "array( 'id' => '_postlist')" );
}
add_action( 'admin_init', 'postlist' );



function postlist_get_post_types(){
	$post_types = get_post_types(array("public" => "true"));
	unset($post_types["page"]);
	unset($post_types["attachment"]);
	return $post_types;
}
function postlist_get_post_type_taxonomies($post_type){
	$post_type_taxonomies = get_object_taxonomies( $post_type, "object" );
	unset($post_type_taxonomies["post_tag"]);
	unset($post_type_taxonomies["post_format"]);
	return $post_type_taxonomies;
}



function postlist_form($post, $args){
	global $post, $wp_locale;
	$settings = get_post_meta( $post->ID, "_postlist", true );

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'postlist_nonce' );

	// metabox-html
	foreach(postlist_get_post_types() as $key => $post_type_slug){
		$post_type_object = get_post_type_object($post_type_slug);
		echo "<p>";
		echo "<input type=checkbox name='" . $post_type_slug . "_show' ";
		if(!empty($settings[$post_type_slug]["show"])) { echo "checked='checked'"; }
		echo ">";
		echo " <strong>" . $post_type_object->labels->name . "</strong>";
		$post_type_taxonomies = postlist_get_post_type_taxonomies($post_type_slug);
		foreach($post_type_taxonomies as $post_type_taxonomy_slug => $post_type_taxonomy_object){
			$taxonomy_terms = get_terms( $post_type_taxonomy_slug, array('hide_empty' => false) );
			if(count($taxonomy_terms) > 0){
				echo " <select name='" . $post_type_slug . "_" . $post_type_taxonomy_slug . "'>";
				echo "<option value=''>Alle " . $post_type_taxonomy_object->labels->name . "</option>";
				echo "<option value=''>--</option>";
				foreach($taxonomy_terms as $key => $taxonomy_term_object){
					echo "<option value='" . $taxonomy_term_object->slug . "' ";
					if(!empty($settings[$post_type_slug]["taxonomies"]) && !empty($settings[$post_type_slug]["taxonomies"][$post_type_taxonomy_slug]) && $settings[$post_type_slug]["taxonomies"][$post_type_taxonomy_slug] == $taxonomy_term_object->slug) { echo "selected"; }
					echo ">" . $taxonomy_term_object->name . "</option>";
				}
				echo "</select>";
			}
		}

		// special case event from plugin fluxus-events
		if ( $post_type_slug == "event" && is_plugin_active( 'fluxus-events/fluxus-events.php' ) ) {
			echo " <select name='timespan'>";
			echo "<option value='future'";
			if(!empty($settings["event"]["timespan"]) && $settings["event"]["timespan"] == "future") { echo "selected"; }
			echo ">künftige Termine</option>";
			echo "<option value='current'";
			if(!empty($settings["event"]["timespan"]) && $settings["event"]["timespan"] == "current") { echo "selected"; }
			echo ">aktuelle Termine</option>";
			echo "<option value='past'";
			if(!empty($settings["event"]["timespan"]) && $settings["event"]["timespan"] == "past") { echo "selected"; }
			echo ">vergangene Termine</option>";
			echo "<option value='currentfuture'";
			if(!empty($settings["event"]["timespan"]) && $settings["event"]["timespan"] == "currentfuture") { echo "selected"; }
			echo ">aktuelle und künftige Termine</option>";
			echo "</select>";
		}
		echo "</p>";
	}
}



function postlist_save_meta( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( !isset( $_POST['postlist_nonce'] ) ) { return; }
	if ( !wp_verify_nonce( $_POST['postlist_nonce'], plugin_basename( __FILE__ ) ) ) { return; }
	if ( !current_user_can( 'edit_post', $post->ID ) ) { return; }
	$input = array();
	foreach(postlist_get_post_types() as $key => $post_type_slug){
		if(!empty($_POST[$post_type_slug . '_show'])){
			$input[$post_type_slug]["show"] = true;
		} else {
			unset($input[$post_type_slug]);
		}
		$post_type_taxonomies = postlist_get_post_type_taxonomies($post_type_slug);
		foreach($post_type_taxonomies as $post_type_taxonomy_slug => $post_type_taxonomy_object){
			if(!empty($_POST[$post_type_slug . '_show']) && !empty($_POST[$post_type_slug . '_' . $post_type_taxonomy_slug])){
				$input[$post_type_slug]["taxonomies"][$post_type_taxonomy_slug] = $_POST[$post_type_slug . '_' . $post_type_taxonomy_slug];
			}
		}

		// special case event from fluxus-events
		if ( $post_type_slug == "event" && !empty($_POST['event_show']) && is_plugin_active( 'fluxus-events/fluxus-events.php' ) ) {
			$input["event"]["timespan"] = $_POST["timespan"];
		}
	}
	if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if ( get_post_meta( $post->ID, "_postlist", FALSE ) ) { // If the custom field already has a value
		update_post_meta( $post->ID, "_postlist", $input );
	} else { // If the custom field doesn't have a value
		add_post_meta( $post->ID, "_postlist", $input );
	}
	if(empty($input)){
		delete_post_meta( $post->ID, "_postlist", $input );
	}
}
add_action( 'save_post', 'postlist_save_meta', 1, 2 );



/* get args for custom-query */
function get_postlist_query_args(){
	global $post;

	$settings = get_post_meta( $post->ID, "_postlist", true );
	
	if(!empty($settings)){
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$post_types = array();
		$taxonomies = array();
	
		$args = array(
					'paged' => $paged,
					'post_type' => array()
				);
	
		foreach($settings as $post_type => $post_type_object){
			if(!empty($post_type_object['show'])){
				$args['post_type'][] = $post_type;
			}
			if(!empty($post_type_object["taxonomies"])){
				$args['tax_query'] = array('relation' => 'AND');
				foreach($post_type_object["taxonomies"] as $taxonomy => $term){
					$args['tax_query'][] = array(
								'taxonomy' => $taxonomy,
								'terms' => array( $term ),
								'field' => 'slug'
							);
				}
			}
			if($post_type == "event" && count($settings) == 1){
				if($post_type_object["timespan"] == "future") {
					$args['meta_key'] = '_start_time';
					$args['meta_value'] = current_time( 'mysql' );
					$args['meta_compare'] = '>=';
					$args['orderby'] = 'meta_value';
					$args['order'] = 'ASC';
				}
				if($post_type_object["timespan"] == "past") {
					$args['meta_key'] = '_start_time';
					$args['orderby'] = 'meta_value';
					$args['order'] = 'DESC';
					$args['meta_query'] = array(
						'relation' => 'OR',
						array(
							'key' => '_end_time',
							'value' => current_time( 'mysql' ),
							'compare' => '<='
						),
						array(
							'relation' => 'AND',
							array(
								'key' => '_start_time',
								'value' => current_time( 'mysql' ),
								'compare' => '<='
							),
							array(
								'key' => '_end_time',
								'value' => '',
								'compare' => 'NOT EXISTS'
							)
						)
					);
				}
				if($post_type_object["timespan"] == "current") {
					$args['meta_query']["relation"] = "AND";
					$args['meta_query'][0]['key'] = '_start_time';
					$args['meta_query'][0]['value'] = current_time( 'mysql' );
					$args['meta_query'][0]['compare'] = '<=';
					$args['meta_query'][1]['key'] = '_end_time';
					$args['meta_query'][1]['value'] = current_time( 'mysql' );
					$args['meta_query'][1]['compare'] = '>=';
					$args['meta_key'] = '_start_time';
					$args['orderby'] = 'meta_value';
					$args['order'] = 'ASC';
				}
				if($post_type_object["timespan"] == "currentfuture") {
					$args['meta_query']["relation"] = "OR";
					$args['meta_query'][0]['key'] = '_end_time';
					$args['meta_query'][0]['value'] = current_time( 'mysql' );
					$args['meta_query'][0]['compare'] = '>=';
					$args['meta_query'][1]['key'] = '_start_time';
					$args['meta_query'][1]['value'] = current_time( 'mysql' );
					$args['meta_query'][1]['compare'] = '>=';
					$args['meta_key'] = '_start_time';
					$args['orderby'] = 'meta_value';
					$args['order'] = 'ASC';
				}
			}
		};
	
		return $args;
	}
}





// posts-options-page
add_action('admin_menu', 'create_post_options');
function create_post_options() {
	add_submenu_page(
		'edit.php', 
		'Optionen Beiträge', 
		'Optionen Beiträge', 
		'manage_options',
		'postsoptions', 
		'create_posts_options_page'
		);
	add_action( 'admin_init', 'register_postlist_plugin_settings' );
}
function register_postlist_plugin_settings() {
	register_setting( 'posts-settings-group', 'homefor_post' );
}
function create_posts_options_page() {
	echo "<div class='wrap'>";
	echo "<h2>Optionen Beiträge</h2>";
	echo "<form method='post' action='options.php'>";
	$pages = get_pages( array('sort_column' => 'menu_order') );
	settings_fields( 'posts-settings-group' );
	do_settings_sections( 'posts-settings-group' );
	echo "<p>";
	echo "<strong>Welche Seite soll als aktuell markiert werden bei der Einzelansicht eines Beitrags?</strong><br />";
	echo "Normalerweise die Seite oder Kategorie, welche die Beiträge auflistet.";
	echo "</p>"; 
	echo "<select name='homefor_post'>";
	echo "<option value=''>-</option>";
	foreach( $pages as $key => $page) {
		echo "<option value='" . $page->ID . "' ";
		if(get_option('homefor_post') == $page->ID) { echo " selected='selected' "; }
		echo ">";
		$ancestors = get_ancestors( $page->ID, 'page' );
		foreach( $ancestors as $key => $ancestor ) {
			echo "&nbsp;&nbsp;&nbsp;";
		}
		echo $page->post_title . "</option>";
	}
	echo "</select>";
	submit_button();
	echo "</form>";
	echo "</div>";
}





//add current_page_item-class im menu on post-single
function current_class_posts($classes, $page) {
	global $post;

	if(is_single() && get_post_type($post) == 'post') {
		$currentpage = get_page(get_option('homefor_post'));
		if($currentpage) {
			if($page->ID == get_option('homefor_post')) { $classes[] = 'current_page_item'; }
			$ancestors = $currentpage->ancestors;
			foreach($ancestors as $key => $ancestor) {
				if($page->ID == $ancestor) { $classes[] = 'current_page_item'; }
			}
		}
	}
	return $classes;
}
add_filter( 'page_css_class', 'current_class_posts', 10, 3 );
add_filter( 'nav_menu_css_class', 'current_class_posts', 10, 3 );


?>
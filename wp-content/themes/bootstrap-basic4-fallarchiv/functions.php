<?php
    /**
     * Theme Name child theme functions and definitions    
     */

    /*—————————————————————————————————————————*/
    /* Include the parent theme style.css
    /*—————————————————————————————————————————*/

    add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' ,100);
    function theme_enqueue_styles() {
		wp_dequeue_style('bootstrap-basic4-wp-main');
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'mein-style', get_stylesheet_uri() );
    }
     
     add_action('wp_enqueue_scripts','theme_enqueue_scripts');     
     function theme_enqueue_scripts() {
	     wp_enqueue_script('jquery');
	     wp_enqueue_script('meine-js', get_stylesheet_directory_uri().'/js/functions.js');
     }
    
    function cpt_interpretation() {
		$labels = array(
			'name' => 'Interpretationen',
			'singular_name' => 'Interpretation',
			);
		$rewrite = array(
			'slug' => 'interpretation',
			'with_front' => true,
			'pages' => true,
			'feeds' => true,
			);
		$args = array(
			'labels' => $labels,
			'supports' => array( 'title', 'editor', 'comments'),
			'public' => true,
			'show_ui' => true,
			'can_export' => false,
			'has_archive' => true,
			'rewrite' => $rewrite,
			);

		register_post_type( 'interpretation', $args );
		}
		
		register_taxonomy('auswertungsmethode','interpretation',array(
        	'hierarchical' => true,
			'label' => 'Auswertungsmethode',
			'query_var' => true,
			'rewrite' => true
			)
		);

	add_action( 'init', 'cpt_interpretation', 0 );
	
	add_action( 'init', 'build_taxonomies', 0 );

function build_taxonomies() {
    register_taxonomy('thema','post',array(
        	'hierarchical' => true,
			'label' => 'Themenschwerpunkt',
			'query_var' => true,
			'rewrite' => true
			)
		);
	register_taxonomy('schulform','post',array(
        	'hierarchical' => true,
			'label' => 'Schulform',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('schultraeger','post',array(
        	'hierarchical' => true,
			'label' => 'Schulträger',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('klasse','post',array(
        	'hierarchical' => true,
			'label' => 'Klassenstufe',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('fach','post',array(
        	'hierarchical' => true,
			'label' => 'Fach',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('sozialform','post',array(
        	'hierarchical' => true,
			'label' => 'Sozialform',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('werhandelt-sch','post',array(
        	'hierarchical' => true,
			'label' => 'wer handelt sch',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('werhandelt-kj','post',array(
        	'hierarchical' => true,
			'label' => 'wer handelt KJ',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);	
	register_taxonomy('werhandelt-eb','post',array(
        	'hierarchical' => true,
			'label' => 'wer handelt EB',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('handlungsfeld-au','post',array(
        	'hierarchical' => true,
			'label' => 'Handlungsfeld AU',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('handlungsfeld-kj','post',array(
        	'hierarchical' => true,
			'label' => 'Handlungsfeld KJ',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('handlungsfeld-eb','post',array(
        	'hierarchical' => true,
			'label' => 'Handlungsfeld EB',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('einrichtung','post',array(
        	'hierarchical' => true,
			'label' => 'Einrichtung',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('erhebungskontext','post',array(
        	'hierarchical' => true,
			'label' => 'Erhebungskontext',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('erhebungsmethode','post',array(
        	'hierarchical' => true,
			'label' => 'Erhebungsmethode',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('auswertungsmethode','post',array(
        	'hierarchical' => true,
			'label' => 'Auswertungsmethode',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('format','post',array(
        	'hierarchical' => true,
			'label' => 'Format',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
	register_taxonomy('status','post',array(
        	'hierarchical' => true,
			'label' => 'Status',
			'query_var' => true,
			'rewrite' => true,
			'meta_box_cb' => false
			)
		);
   	}

/*** entfernt überflüssige Beitrags-Optionen  ***/


function remove_stuff () {
  remove_theme_support ('post-formats');
  remove_theme_support ('post-thumbnails');
}
add_action( 'init','remove_stuff' );


/*
function remove_custom_meta(){
  remove_meta_box ('postcustom','post','normal');
}
add_action('admin_menu','remove_custom_meta');
*/



/**
 * Extend WordPress search to include custom fields
 *
 */

/**
 * Join posts and postmeta tables
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    
    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;
   
    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

add_shortcode('test', 'interpretation');
function interpretation(){
	$args = array (
			'post_type' => 'interpretation',
			'post_status' => 'publish',
			);
	$the_query = new WP_Query ($args);
									
	$string = '';									
	if ( $the_query->have_posts()){
			$string .= '<ol>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post ();
				$string .= '<li>';
				$string .= '<a href=" ';
				$string .= get_permalink ();
				$string .= '">' . get_the_title();
				$string .= '</a></li>';
				}
			$string .= '</ol>';
	}							
	wp_reset_postdata();
	return $string;
}

add_shortcode('feld', 'mein_feld');
function mein_feld(){
	$args = array (
			'post_type' => 'post',
			'post_status' => 'publish',
			'meta_key' => 'hat_interpretation',
			'meta_value' => '1',
			'posts_per_page' => 50
			);
	$the_query = new WP_Query ($args);
									
	$string = '';									
	if ( $the_query->have_posts()){
			$string .= '<ol>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post ();
				$string .= '<li>';
				$string .= '<a href=" ';
				$string .= get_permalink ();
				$string .= '">' . get_the_title();
				$string .= '</a></li>';
				}
			$string .= '</ol>';
	}							
	wp_reset_postdata();
	return $string;
}




/*
zeigt nur Fälle mit Interpretation an
muss an "Häkchen" geknüpft werden


add_action( 'pre_get_posts', 'nur_mit_i');
function nur_mit_i ($query ){
	if ( ! $query->is_main_query() || ! $query->is_home ())
	  return;
	
	$query->set( 'post_type', 'post');
	$query->set( 'meta_key', 'hat_interpretation');
	$query->set( 'meta_value', '1');
	}
*/

	
/*
	$posts = get_posts(array(
		'post_type' => 'post',
		'meta_key' => 'hat_interpretation',
		'meta_value' => '1'
		));

		if($posts)
		{
			echo '<ul>';

				foreach($posts as $post)
					{
						echo '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></li>';
					}
			echo '</ul>';
		}
*/


/*
$original_query = $wp_query;
$tagstr = '' + the_field('usertag');
$wp_query = null;
$wp_query = new WP_Query( "tag=$tagstr");
*/

/*
$original_query = $wp_query;
$wp_query = null;
$wp_query = new WP_Query( 'tag=erhebungsmethode');
*/

?>
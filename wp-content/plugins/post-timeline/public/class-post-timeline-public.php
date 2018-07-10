<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Post_TIMELINE
 * @subpackage Post_TIMELINE/public
 * @author     agilelogix <support@agilelogix.com>
 */
class Post_TIMELINE_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		
		/*
		error_reporting(E_ALL);
  		ini_set('display_errors',1);
  		*/
  		
		$this->defaults = array(
							"ptl-fonts" => array(array('family'=>'lato', 'variants' => '')),
							"ptl-type" => "date",
							"ptl-fav-icon" => "fa-heart",
							"ptl-post-icon" => "1",
							"ptl-sort" => "ASC",
							"ptl-post-type" => "post-timeline",
							"ptl-date-format" => "M j, Y",
							"ptl-post-length" => "350",
							"ptl-fonts" => array(array("family" => "")),
							"ptl-bg-color" => "#ffffff",
							"ptl-post-bg-color" => "",
							"ptl-post-head-color" => "#FFFFFF",
							"ptl-post-desc-color" => "#FFFFFF",
							"ptl-head-font-size" =>array("12","px"),
							"ptl-desc-font-size"=> array("14","px"),
							"ptl-post-bg" => "off",
							"ptl-post-load" => "1",
							"ptl-post-per-page" => "10",
							"ptl-anim-delay" => "",
							"ptl-anim-type" => "fadeInUp",
							"ptl-nav-color" => "#4285F4",
							"ptl-custom-css" => "",
							"ptl-post-size" => "",
							"ptl-nav-type" => "style-2",
							/**/
							"ptl-bg-status" => "on",
							"ptl-nav-status" => "on",
							"ptl-anim-status" => "on",
							"ptl-rm-status" => "on",
							"ptl-css" => null
							);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name.'-reset', POST_TIMELINE_URL_PATH . 'public/css/post-timeline.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-animate', POST_TIMELINE_URL_PATH . 'public/css/animate.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-bootstrap', POST_TIMELINE_URL_PATH . 'public/css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-bootstrap-theme', POST_TIMELINE_URL_PATH . 'public/css/bootstrap-theme.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-fontawsom', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), $this->version, 'all' );

		//test
		//wp_enqueue_style( $this->plugin_name.'-test', '/holytrinity.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		$cache  = true;
		$bottom = true;

		//wp_enqueue_script( $this->plugin_name.'-bootstrap', POST_TIMELINE_URL_PATH . 'public/js/bootstrap.min.js', array('jquery'), $this->version, $bottom );
			wp_enqueue_script( $this->plugin_name.'-masonry', POST_TIMELINE_URL_PATH . 'public/js/mp.mansory.js', array('jquery'), $this->version, $bottom );
			wp_enqueue_script( $this->plugin_name.'-anim', POST_TIMELINE_URL_PATH . 'public/js/ptl-anim.js', array('jquery'), $this->version, $bottom );
			wp_enqueue_script( $this->plugin_name.'-scroll', POST_TIMELINE_URL_PATH . 'public/js/smooth-scroll.js', array('jquery'), $this->version, $bottom );
			wp_enqueue_script( $this->plugin_name.'-slick', POST_TIMELINE_URL_PATH . 'public/js/slick.js', array('jquery'), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-timeline', POST_TIMELINE_URL_PATH . 'public/js/post-timeline.js', array('jquery'), $this->version, $bottom );

		wp_localize_script( $this->plugin_name.'-timeline', 'PTL_REMOTE', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' )
		));
	}

	/**
	 * Register individual shortcode [post_timeline]
	 *
	 * @since    0.0.1
	 */
  	public function add_shortcodes() {
  		

  		add_shortcode( 'post-timeline', array( $this, 'timeline_shortcode' ) );
  	}

	/**
	 * Callback function for individual timeline shortcode [post-timeline]
	 *
	 * @since    0.0.1
	 */
  	public function timeline_shortcode( $atts ) {

  		//call the helper
  		require_once POST_TIMELINE_PLUGIN_PATH.'includes/helper.php';
  		
		$template 		= '1';
		$this->template = 'post-timeline-public-'.$template.'.php';
		$this->temp_id  = $template;
		
		//1,11,10,12
  		wp_enqueue_style( $this->plugin_name.'-timeline', POST_TIMELINE_URL_PATH . 'public/css/post-timeline-public-'.$template.'.css', array(), $this->version, 'all' );


  		//If empty
  		if(!$atts) {

  			$atts = array();
  		}
		
		$atts['template'] = '1';


		//Get the title Post
		$top_post = (isset($atts['header']) && $atts['header'])?get_post($atts['header']):null;

		$paged = 1;

		$ptl_settings = get_option('post_timeline_global_settings');

		


		//Extend with defaults
		if(!$ptl_settings) {
			$ptl_settings = $this->defaults;
		}
		else
			$ptl_settings = array_merge($this->defaults,$ptl_settings);


		$ptl_settings = shortcode_atts( $ptl_settings, $atts );

		//add the missing attributes into settings
		$ptl_settings = array_merge($ptl_settings,$atts);


		// /ptl-post-load
		//If horiz, no limit
		if(strpos($ptl_settings['template'],'-h') == true) {
			
			$ptl_settings['ptl-post-per-page'] = -1;
		}

		$per_page = $ptl_settings['ptl-post-per-page'];

		//For Class
		$ptl_settings['class'] = isset($atts['class'])?$atts['class']:null;

		//load fonts
		if(isset($ptl_settings['ptl-fonts']) && isset($ptl_settings['ptl-fonts'][0])) {
			
			$font = $ptl_settings['ptl-fonts'][0];
			$font_name = ucwords($font['family']);
			
			$font_vars = '';

			if(isset($font['variants'])) {
				
				$font_vars = implode(',', $font['variants']);
			}

			if($font_vars) {
				$font_vars = ':'.$font_vars;
			}

			if($font_name) {

				$font_url = 'https://fonts.googleapis.com/css?family='.$font_name.$font_vars;

	  			wp_enqueue_style( $this->plugin_name.'-fonts', $font_url, array(), '', '' );
  			}

  			$ptl_settings['fonts'] = $font_name;
		}


		
		//Restrict category
		$category_id = isset($atts['category_id'])?$atts['category_id']:null;

		//0 for Load All
		if($ptl_settings['ptl-post-load'] == '0') {

			$per_page = -1;
		}

		$sort_order = ($ptl_settings['ptl-sort'] == '1' || $ptl_settings['ptl-sort'] == 'ASC')?'ASC':'DESC';

		$query_args = array (
			'post_type' 	 => $ptl_settings['ptl-post-type'],
			'post_status' 	 => 'publish',
			'posts_per_page' => $per_page,
			'paged' => $paged,
			'page'  => $paged,
			/*
			'orderby'   => 'ID',
			'order' => ($ptl_settings['ptl-sort'] == '0')?'ASC':'DESC'
			*/
			//order
			'meta_key'  => 'ptl-post-date',
			'orderby'   => 'meta_value',
			'order' 	=> $sort_order
		);



		//remove header post
		if($top_post) {

			$query_args['post__not_in'] = array($top_post->ID);
			$ptl_settings['top_id'] 	= $top_post->ID;
		}

		//Filter by Category
		if($category_id)  {
			
			$query_args['cat'] =  $category_id; //verified
		}



		//Sort By TAG
		if($ptl_settings['ptl-type'] == 'tag') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
		
		    $query_args['meta_query'] = array(
		        array(
		            'relation' => 'AND',
		            'tag_clause' 	=> array(
		                'key'    	=> 'ptl-post-tag',
		                'type' 		=> 'NUMERIC',
		                'compare' 	=> 'EXISTS'
		            )
		        )
		    );

		    $query_args['orderby'] = array(
		        'tag_clause'      	=> $sort_order
		    );
		}
		//Sort by Custom Order
		else if($ptl_settings['ptl-type'] == 'custom_order') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
		
		    $query_args['meta_query'] = array(
		        array(
		            'relation' => 'AND',
		            'order_clause' => array(
		                'key'  		=> 'ptl-post-order',
		                'type' 		=> 'NUMERIC',
		                'compare' 	=> 'EXISTS'
		            )
		        )
		    );

		    $query_args['orderby'] = array(
		        'order_clause'     	=> $sort_order
		    );
		}

		//Unset things if not post-timeline
		if($ptl_settings['ptl-post-type'] != 'post-timeline') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
			unset($query_args['meta_query']);
		}


		//dd($query_args);


		//dummy data
		if(isset($atts['admin'])) {
  			
  			$query_args['posts_per_page'] = '10';
  			$the_query = new WP_Query( $query_args );
			$posts 	   = $the_query->posts;
  		}
  		else {

			$the_query = new WP_Query( $query_args );
			$posts 	   = $the_query->posts;	
  		}




  
  		//echo $the_query->request;die;

		/*Assign Dates*/
		/*foreach($posts as $p) {

			update_post_meta($p->ID,'ptl-post-date', $this->rand_date());
		}
		die;*/

		/*
		foreach($posts as $p) {
			$p->POST_DATE = get_post_meta($p->ID,'ptl-post-date');
		}
		dd($posts);
		*/

		$pagination = null;

		//$ptl_settings['ptl-post-load'] = '0';


		/*if($ptl_settings['ptl-post-load'] == '0')
			$pagination = ptl_pagination($the_query->max_num_pages,"",$paged);*/
		if($ptl_settings['ptl-post-load'] == '1')
			$pagination = $this->load_more($the_query);

		//reset it
		wp_reset_postdata();



		//ID is valid, show timeline.
		return $this->output_timeline( array('ptl_posts'=> $posts, 'paginate' => $pagination, 'top_post' => $top_post),$ptl_settings );
  	}


  	/*Load By Ajax*/
  	public function ajax_load_posts() {


  		$paged      = ( isset($_GET['paged']) ) ? $_GET['paged'] : 1;
  		$template   = 1;
  		$paginate   = ( isset($_GET['paginate']) ) ? $_GET['paginate'] : 0;


  		$atts = array(
  			'template' => $template,
  			'paginate' => $paginate
  		);

		$template 		= ($atts['template'] !== null)?$atts['template']:'0';
		$this->template = 'post-timeline-public-'.$template.'.php';
		$this->temp_id  = $template;
		
		//*Default Rules and variables*//
		
		$ptl_settings = array_merge($this->defaults,$_GET);

		//as ajax
		$ptl_settings['ajax'] = true;

		//$ptl_settings = shortcode_atts( $ptl_settings, $atts );

		$per_page = $ptl_settings['ptl-post-per-page'];


		//0 for Load All
		if($ptl_settings['ptl-post-load'] == '0') {

			$per_page = -1;
		}


		$sort_order = ($ptl_settings['ptl-sort'] == '1' || $ptl_settings['ptl-sort'] == 'ASC')?'ASC':'DESC';

		$query_args = array (
			'post_type' 	 => $ptl_settings['ptl-post-type'],
			'post_status' 	 => 'publish',
			'posts_per_page' => $per_page,
			'paged' => $paged,
			'page'  => $paged,
			/*
			'orderby'   => 'ID',
			'order' => ($ptl_settings['ptl-sort'] == '0')?'ASC':'DESC'
			*/
			//order
			'meta_key' => 'ptl-post-date',
			'orderby'   => 'meta_value',
			'order' => $sort_order
		);

		//Sort By TAG
		if($ptl_settings['ptl-type'] == 'tag') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
		
		    $query_args['meta_query'] = array(
		        array(
		            'relation' => 'AND',
		            'tag_clause' 	=> array(
		                'key'    	=> 'ptl-post-tag',
		                'type' 		=> 'NUMERIC',
		                'compare' 	=> 'EXISTS'
		            )
		        )
		    );

		    $query_args['orderby'] = array(
		        'tag_clause'      	=> $sort_order
		    );
		}
		//Sort by Custom Order
		else if($ptl_settings['ptl-type'] == 'custom_order') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
		
		    $query_args['meta_query'] = array(
		        array(
		            'relation' => 'AND',
		            'order_clause' => array(
		                'key'  		=> 'ptl-post-order',
		                'type' 		=> 'NUMERIC',
		                'compare' 	=> 'EXISTS'
		            )
		        )
		    );

		    $query_args['orderby'] = array(
		        'order_clause'     	=> $sort_order
		    );
		}

		//Unset things if not post-timeline
		if($ptl_settings['ptl-post-type'] != 'post-timeline') {

			unset($query_args['orderby']);
			unset($query_args['meta_key']);
			unset($query_args['order']);
			unset($query_args['meta_query']);
		}

		//filter header post
		if($ptl_settings['top_id']) {

			$query_args['post__not_in'] = array($ptl_settings['top_id']);
		}

		//Restrict category
		$category_id = isset($ptl_settings['category_id'])?$ptl_settings['category_id']:null;

		//Filter by Category
		if($category_id)  {
			
			$query_args['cat'] =  $category_id; //verified
		}
		

		/*Ajax Query*/
		$the_query = new WP_Query( $query_args );
		$posts 	   = $the_query->posts;


		/*
		foreach($posts as $p) {
			$p->POST_DATE = get_post_meta($p->ID,'ptl-post-date');
		}
		
		dd($posts);
		*/
		
		//$the_query->max_num_pages = 12;
		$pagination = null;


		/*if($ptl_settings['ptl-post-load'] == '0')
			$pagination = ptl_pagination($the_query->max_num_pages,"",$paged);*/
		if($ptl_settings['ptl-post-load'] == '1')
			$pagination = $this->load_more($the_query);

		//reset it
		wp_reset_postdata();

		//ID is valid, show timeline.
		return $this->output_timeline(array('ajax' => 1,'ptl_posts'=> $posts, 'paginate' => $pagination, 'top_post' => null),$ptl_settings );
  	}


  	private function load_more($wp_query) {

  		if($wp_query->max_num_pages <= 1)
  			return '';


		$html = '<div class="plt-load-more">';
		$html .= '  <button class="ptl-more-btn' . ($wp_query->max_num_pages == 1 ? ' disabled' : '') . '" data-href="' . $this->get_the_url() . '" data-page="' . ( get_query_var('paged') ? get_query_var('paged') : '1' ) . '" data-max-pages="' . $wp_query->max_num_pages . '">';
		$html .= '    <span class="ptl-load-more">'.__( 'Load More', 'post-timeline').'</span><img src="'.POST_TIMELINE_URL_PATH.'public/img/ring.gif">' . "\n";
		$html .= '  </button>' . "\n";
		$html .= '</div>' . "\n";
		return $html;
  	}

  	private function get_the_url() {

		return ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/*Insert the Post*/
	function Generate_Featured_Image( $image_url, $post_id  ){
	    
	    $upload_dir = wp_upload_dir();
	    $image_data = file_get_contents($image_url);
	    $filename = basename($image_url);
	    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
	    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
	    file_put_contents($file, $image_data);

	    $wp_filetype = wp_check_filetype($filename, null );
	    $attachment = array(
	        'post_mime_type' => $wp_filetype['type'],
	        'post_title' => sanitize_file_name($filename),
	        'post_content' => '',
	        'post_status' => 'inherit'
	    );
	    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	    require_once(ABSPATH . 'wp-admin/includes/image.php');
	    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
	    $res2= set_post_thumbnail( $post_id, $attach_id );
	}

	private function rand_date() {

		$datestart = strtotime('2005-12-10');//you can change it to your timestamp;
		$dateend = strtotime('2015-12-31');//you can change it to your timestamp;

		$daystep = 86400;

		$datebetween = abs(($dateend - $datestart) / $daystep);

		$randomday = rand(0, $datebetween);


		return date("Y-m-d", $datestart + ($randomday * $daystep));
	}


	private function add_posts_test() {


		$years 		  = array(2000,2001,2003,2005,2007,2009,2010,2011,2012,2013,2015,2016,2017);
		$colors_codes = array("#6dff80","#86ffef","#FF7473","#0199A0","#c59cf1","#fba9ec","#8eefac","#43bff1","#ffbd38");

		for($i = 21; $i < 40; $i++) {

			// Gather post data.
			$my_post = array(
			    'post_title'    => 'A Custom Post '.$i,
			    'post_content'  => "It is a long established {$i} fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
			    'post_type'     => 'post-timeline',
			    'post_status'   => 'publish',
			    'post_author'   => 1,
			    'post_category' => array( 6 )
			);
			 

			$year = $years[rand(0,12)];

			// Insert the post into the database.
			$postId = wp_insert_post( $my_post );

			add_post_meta( $postId, 'ptl-post-tag', rand(3,5), true );
			add_post_meta( $postId, 'ptl-post-color', $colors_codes[rand(0,8)], true );
			add_post_meta( $postId, 'ptl-img-txt-pos', '0', true );
			//add_post_meta( $postId, 'ptl-post-type', '0', true );
			add_post_meta( $postId, 'ptl-post-date', $this->rand_date(), true );
			add_post_meta( $postId, 'ptl-post-order', '1', true );
			add_post_meta( $postId, 'ptl-fav-icon', 'fa fa-heart', true );
			
			//Insert the Meta and Attachment
			$this->Generate_Featured_Image("http://design.localhost.com/image2.jpg",$postId);
		}
	}

	/**
	 * Output a timeline
	 *
	 * @since    0.0.1
	 */
	public function output_timeline( $ptl_response,$all_configs ) {

		/*$args = array(
			'post_parent' => $parent_post->ID,
			'post_type'   => 'post-timeline', 
			'numberposts' => -1,
			'post_status' => 'publish'
		);
		*/
		
		
		//$this->add_posts_test();die('g');

		$prev_index  = ( isset($_GET['last']) ) ? $_GET['last'] : null;
		$ptl_admin   = (isset($all_configs['admin']) && $all_configs['admin'])?true:false;

		if($prev_index && !is_nan($prev_index)) {

			$prev_index++;
		}


		//get the parent
		$parent_post = null;

		if($ptl_response['top_post']) {
			
			$parent_post = $ptl_response['top_post'];
			$parent_post->custom = get_post_custom($parent_post->ID);
		}
		
		/*
		$timeline_posts 	 = get_children( $args );
		*/

		$timeline_posts = $ptl_response['ptl_posts'];


		//All the configs showing here
		$all_configs['template'] = $this->temp_id;

		$template_wrapper = '';
		//if horizontal
		if(in_array($this->temp_id, array('0-h','1-h','2-h','3-h','4-h','5-h','6-h','7-h','8-h'))) {

			$template_wrapper = '-horz';
		}

		/*
		the_post_thumbnail_url( 'thumbnail' );       // Thumbnail (default 150px x 150px max)
		wp_get_attachment_image_url( 'medium' );          // Medium resolution (default 300px x 300px max)
		the_post_thumbnail_url( 'large' );           // Large resolution (default 640px x 640px max)
		the_post_thumbnail_url( 'full' );  
		*/

		//apply format
		$format = $all_configs['ptl-date-format'];


		//switch to bool
		if($all_configs['ptl-post-icon'] == 'on') {
			$all_configs['ptl-post-icon'] = '1';
		}



		foreach($timeline_posts as $child) {

			$child->custom 		= get_post_custom($child->ID);

			//set icon
			if($all_configs['ptl-post-icon'] == '1') {

				$child->icon = isset($child->custom['ptl-fav-icon'][0])?('fa '.$child->custom['ptl-fav-icon'][0]):'fa '.$all_configs['ptl-fav-icon'];
			} 
			else
				$child->icon = '';	
			

			$date  = isset($child->custom['ptl-post-date'][0])?$child->custom['ptl-post-date'][0]:$child->post_date;
			//$format 			= isset($child->custom['ptl-date-format'][0])?$child->custom['ptl-date-format'][0]:null;
				
			//Set Text Position
			if(!isset($child->custom['ptl-img-txt-pos'][0]))
				$child->custom['ptl-img-txt-pos'] = array(0);


			if(!isset($child->custom['ptl-tag-line'][0]))
				$child->custom['ptl-tag-line'] = array(null);

			

			//Add the Tag
			if(!isset($child->custom['ptl-post-tag'][0]))
				$child->custom['ptl-post-tag'] = array(0);
			
			$child->tag_id = $child->custom['ptl-post-tag'][0];

			
			//$date = null;
			if(!$date) {

				$date = $child->post_date;
			}

			$child->event_date  = $date;
			
			if($date) {

				$child->date_str = date_i18n($format,strtotime($child->custom['ptl-post-date'][0]));
			}
			else
				$child->date_str = '';
		}


		//For Admin Timelines
		if(isset($all_configs['admin']) && $all_configs['admin'] == '1') {

			$timeline_posts = json_decode(file_get_contents(POST_TIMELINE_PLUGIN_PATH.'timeline.json'),TRUE);
			$ptl_response['paginate'] = null;
		}

		//file_put_contents('timeline.json', json_encode($timeline_posts));
		//$timeline_posts = json_decode(file_get_contents('timeline.json'),TRUE);

		foreach($timeline_posts as &$p) {
			$p = (object)$p;
		}


		//the range of dates
		$date_range = array('','');
		$tag_list   = array();


		$by_date = ($all_configs['ptl-type'] == 'date')?true:false;

		//for admin
		if($ptl_admin) {

			$tag_list = array('14'=> 'Asia','15'=> 'Africa', '16' => 'Australia', '17' => 'North America' , '19' => 'Europe'  );
			$all_configs['ptl-nav-type'] = 'style-2';
		}
		//Set Date Range
		else if($all_configs['ptl-type'] == 'date') {

			//Sort By Year :: disable sorting by date
			//usort($timeline_posts, array($this, "cmp_year"));

			//DATE RANGE
			if(isset($timeline_posts[0]) && $timeline_posts[0]->event_date) {

				$_comp = explode('-', $timeline_posts[0]->event_date);
				$date_range[0] = $_comp[0];
			}

			$last_post = ($timeline_posts)?$timeline_posts[count($timeline_posts) - 1]:null;
			if(isset($last_post) && $last_post->event_date) {

				$_comp = explode('-', $last_post->event_date);
				$date_range[1] = $_comp[0];
			}	

			//parent post
			if($parent_post && !empty($date_range)) {

				$parent_post->time_range = $date_range[0].' - '.$date_range[1];
			}

			//GET Year
			$tag_list = $this->get_year_list($timeline_posts);
		}
		//by Tag
		else {

			//Sort By Tags :: disable sorting by Tag
			//usort($timeline_posts, array($this, "cmp_tag"));

			//GET TAGS
			$tag_list = $this->get_tag_list($timeline_posts);

		}

		//Set the variables used
		$color_nav 	  = $all_configs['ptl-nav-color'];
		$ptl_URL 	  = site_url()."/".$all_configs['ptl-post-type']."/";
		$tmpl_class   = 'ptl-tmpl-'.$this->temp_id;
		$color_nav    = $all_configs['ptl-nav-color'];
		$bg_color     = $all_configs['ptl-bg-color'];
		$nav_class    = $all_configs['ptl-nav-type'];
		$head_color   = $all_configs['ptl-post-head-color'];
		$desc_color   = $all_configs['ptl-post-desc-color'];
		$post_bg      = $all_configs['ptl-post-bg-color'];
		

		//reset things
		if(isset($all_configs['ptl-bg-status']) && $all_configs['ptl-bg-status'] == 'off') {
			$all_configs['ptl-bg-color'] = '';
		}

		//ptl-anim-type,ptl-css
		$all_configs['ptl-nav-status']  = (isset($all_configs['ptl-nav-status']) && $all_configs['ptl-nav-status'] == '0')?false:true;
		$all_configs['ptl-anim-status'] = (isset($all_configs['ptl-anim-status']) && $all_configs['ptl-anim-status'] == '0')?false:true;
		$all_configs['ptl-rm-status']   = (isset($all_configs['ptl-rm-status']) && $all_configs['ptl-rm-status'] == '0')?false:true;
		$all_configs['ptl-post-length'] = intval($all_configs['ptl-post-length']);
		
		//dd($all_configs['ptl-rm-status']);

		if($all_configs['ptl-post-length'] <= 0 || $all_configs['ptl-post-length'] > 1000)
			$all_configs['ptl-post-length'] = 350; 
		
		//single side class
		if(!isset($all_configs['class'])) {

			$all_configs['class'] = '';
		}

		/*hack change var*/
		$child_posts = $timeline_posts;
		$uniqid 	 = uniqid();
		

		$template_file = $this->template;


		//Template File

		if($template_file) {

			if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
	            $template_file = $theme_file;
	        }
	        else {
	            $template_file = plugin_dir_path( __FILE__ ) . 'partials/' . $template_file;
	        }
		}

		/////////////////////////////////
		//$all_configs['ajax'] = $ptl_response['ajax'] = true;

		
		ob_start();

		//IS AJAX
		if(isset($ptl_response['ajax']))
			include $template_file;
		else
			include( plugin_dir_path( __FILE__ ) . '/partials/post-timeline-wrapper'.$template_wrapper.'.php' );
		
		$output = ob_get_contents();
		ob_end_clean();

		if(isset($ptl_response['ajax'])) { 
			header('Content-Type: application/json');
			echo json_encode(array('html' => $output,'range' => $date_range,'tags' => $tag_list));die;
		}

		//if admin
		if(isset($all_configs['admin']) && $all_configs['admin'] == '1')
			include POST_TIMELINE_PLUGIN_PATH.'admin/partials/demo-bottom.php';
		else
			wp_localize_script( $this->plugin_name.'-timeline', ('ptl_config_'.$uniqid), $all_configs);

		
		return $output;
	}


	private function get_year_list($timeline_posts) {


		$tag_list = array();
		foreach($timeline_posts as $child) {

			$date = $child->event_date;
			
			if($date) {
				$date_comp = explode('-', $date);
				$child->date_comp = $date_comp;

				//capture the year
				if(count($date_comp) >= 1) {
						
					if(!in_array($date_comp[0], $tag_list))
					$tag_list[] = $date_comp[0];
				}
			}
		}

		//make it key value pair
		$temp_list = array();

		foreach($tag_list as $y) {
			
			$temp_list[$y] = $y;
		}

		return $temp_list;
		
	}

	private function get_tag_list($timeline_posts) {


		$tag_list = array();
		foreach($timeline_posts as $child) {

			$tag = $child->custom['ptl-post-tag'];
				
			// Missing Tags
			if(!$tag[0]) {

				$child->custom['ptl-post-tag'] = $tag = array(0);
			}

			if($tag) {
				
				$child->date_comp = $tag;

				//capture the Tag
				if(!in_array($tag[0], $tag_list))
					$tag_list[] = $tag[0];
			}
		}

		//make it key value pair
		$temp_list = array();

		foreach($tag_list as $l) {
			
			$term = null;

			//Empty Terms
			if($l == 0) {

				$term = new \stdclass();
				$term->term_id = 0;
				$term->name    = 'None';
			}
			else {

				$term = get_term($l);

				//Term not found
				if(!$term) {

					$term = new \stdclass();
					$term->term_id = 0;
					$term->name    = 'None';
				}
			}

			if($term) {

				$temp_list[$term->term_id] = $term->name;				
			}
		}

		return $temp_list;
		
	}


	private function cmp_year($a, $b){
    	
    	return strcmp($a->event_date, $b->event_date);
	}

	private function cmp_tag($a, $b){
    	
    	return strcmp($a->tag_id, $b->tag_id);
	}

	private function debug($data) {

		echo '<pre>';
		print_r($data);
		echo '</pre>';
		die;
	}


  	/**
	 * Include in loop so they can be displayed among regular posts.
	 *
	 * @since    0.0.1
	 */
	function add_timelines_to_loop( $query ) {

		global $pagenow;

		if( $pagenow == 'edit.php' ) {
			
			return;
		}

		// Querying specific page (not set as home/posts page) or attachment
		if( !$query->is_home() ) {
			
			if( $query->is_page() || $query->is_attachment() ) {
				return;
			}
		}

		// Querying a specific taxonomy
		if( !is_null( $query->tax_query ) ) {
			
			$tax_queries = $query->tax_query->queries;
			$timeline_taxonomies = get_object_taxonomies( 'post-timeline' );

			if( is_array($tax_queries) ) {
			
				foreach( $tax_queries as $tax_query ) {
			
					  	if( isset( $tax_query['taxonomy'] ) && $tax_query['taxonomy'] !== '' && !in_array( $tax_query['taxonomy'], $timeline_taxonomies ) ) {
				
						  	return;
					  	}
					}
				}
			}

		$post_type = $query->get( 'post_type' );

		if( $post_type == '' || $post_type == 'post' ) {
			$post_type = array( 'post','post-timeline' );
		}
		else if( is_array($post_type) ) {
			
			if( in_array('post', $post_type) && !in_array('post-timeline', $post_type) ) {
				
				$post_type[] = 'post-timeline';
			}
		}

		$post_type = apply_filters( 'post_timeline_query_posts', $post_type, $query );

		$query->set( 'post_type', $post_type );

		return;

	}

  	/**
	 * Adds custom CSS from global settings page to site header.
	 *
	 * @since    0.0.1
	 */
	public function output_header_css() {

		$settings = get_option( 'post_timeline_global_settings' );

		if( isset($settings['ptl-custom-css']) && $settings['ptl-custom-css'] ) {
			
			$css = '<style type="text/css">' . $settings['ptl-custom-css'] . '</style>';
			echo $css;
		}

	}

}

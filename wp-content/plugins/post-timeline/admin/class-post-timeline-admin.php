<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Post_TIMELINE
 * @subpackage Post_TIMELINE/admin
 * @author     agilelogix <support@agilelogix.com>
 */
class Post_TIMELINE_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		/*
		if ( !session_id() ) {
		    session_start();
		}
		*/

		add_action('admin_menu', array(&$this,'ptl_menu'));
		
		//add_action('admin_notices', array(&$this,'my_admin_notice'));		
		//add_action('save_post', array(&$this,'validate_text_input'));
		//add_filter('ot_validate_setting', array(&$this,'validate_text_input'), 10, 3);

		add_filter('manage_edit-post-timeline_columns',array(&$this,'timeline_columns'));
		add_action( 'manage_post-timeline_posts_custom_column' , array(&$this,'timeline_column_details'), 10, 2 );

		add_action( 'add_meta_boxes', array( &$this, 'pro_meta_boxes' ) );
		

	}


	function pro_meta_boxes() {
	    
	    add_meta_box('ban-post-timeline-pro',__( 'Pro Version Post Timeline','post-timeline'),'post_timeline_pro_version','post-timeline','side','low');
	}


	function ptl_menu() {

		
	  	//Load Styles
	  	/* load WP colorpicker */
	    wp_enqueue_style( 'wp-color-picker' );
	    
	    /* load admin styles */
	    wp_enqueue_style( 'ot-admin-css', OT_URL . 'assets/css/ot-admin.css', false, OT_VERSION );

		/* load WP colorpicker */
	    wp_enqueue_script( 'wp-color-picker' );

	  	add_submenu_page( '/edit.php?post_type=post-timeline', 'Templates', 'Templates (Pro)', 'delete_posts', 'timeline-templates', array($this,'admin_template_page'));
	  	

	}

	function validate_text_input($post_id) {
		
		global $errors;

		if($_POST['ptl-post-date'] == '') {

			set_transient( 'post-timeline-err', 'Please add Post Date for the Timeline', 30 );

			return false;
		}

	    return true;
	}


	function my_admin_notice() {

		if ( $error = get_transient( "post-timeline-err" ) ) { ?>
		    <div class="error">
		        <p><?php echo $error; ?></p>
		    </div><?php

		    delete_transient("post-timeline-err");
		}
		
	}

	/**
	 * Register Timeline custom post type
	 *
	 * @since    0.0.1
	 */
	public function register_post_timeline() {


	    $labels = array(
	      'name'               => _x( 'Post Timelines', 'post type general name', 'post-timeline' ),
	      'singular_name'      => _x( 'Post Timeline', 'post type singular name', 'post-timeline' ),
	      'menu_name'          => _x( 'Post Timelines', 'admin menu', 'post-timeline' ),
	      'name_admin_bar'     => _x( 'Post Timeline', 'add new on admin bar', 'post-timeline' ),
	      'add_new'            => _x( 'Add New', 'timeline', 'post-timeline' ),
	      'add_new_item'       => __( 'Add New Timeline Post', 'post-timeline' ),
	      'new_item'           => __( 'New Timeline', 'post-timeline' ),
	      'edit_item'          => __( 'Edit Timeline', 'post-timeline' ),
	      'view_item'          => __( 'View Timeline', 'post-timeline' ),
	      'all_items'          => __( 'All Timelines', 'post-timeline' ),
	      'search_items'       => __( 'Search Timelines', 'post-timeline' ),
	      'parent_item_colon'  => __( 'Parent Timelines:', 'post-timeline' ),
	      'not_found'          => __( 'No timelines found.', 'post-timeline' ),
	      'not_found_in_trash' => __( 'No timelines found in Trash.', 'post-timeline' ),
	      "parent"  => __( 'Parent Timeline', 'post-timeline' ),
	    );

	    $args = array(
	      'labels'            => $labels,
	      'public'            => true,
	      'publicly_queryable'=> true,
	      'show_ui'           => true,
	      'show_in_menu'      => true,
	      'query_var'         => true,
	      'rewrite'           => array( 'slug' => 'post-timeline' ),
	      'capability_type'   => 'post',
	      'has_archive'       => true,
	      'hierarchical'      => true,
	      'taxonomies'		  => array('topics', 'category','post_tag' ),
	      'menu_position'     => 5,
	      'menu_icon'         => 'dashicons-portfolio',
	      'supports'          => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes'),
	    );

	  	register_post_type( 'post-timeline', $args );

	  
	  	add_filter( 'template_include', array($this,'include_template_function'), 1 );

	}

	/*Multiple Template Page*/
	function admin_template_page() {

		$ptl_templates =  array(
			array('id' => '0',   'template_name' => 'Template 0', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-0.png', 'href' => 'timeline-template-0'),
			array('id' => '1', 	 'template_name' => 'Template 1', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-1.png', 'href' => 'timeline-template-1'),
			array('id' => '2',   'template_name' => 'Template 2', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-2.png', 'href' => 'timeline-template-2'),
			array('id' => '3',   'template_name' => 'Template 3', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-3.png', 'href' => 'timeline-template-3'),
			array('id' => '4',   'template_name' => 'Template 4', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-4.png', 'href' => 'timeline-template-4'),
			array('id' => '5',   'template_name' => 'Template 5', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-5.png', 'href' => 'timeline-template-5'),
			array('id' => '6',   'template_name' => 'Template 6', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-6.png', 'href' => 'timeline-template-6'),
			array('id' => '7',   'template_name' => 'Template 7', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-7.png', 'href' => 'timeline-template-7'),
			array('id' => '8',   'template_name' => 'Template 8', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-8.png', 'href' => 'timeline-template-8'),
			
			array('id' => '1', 	 'template_name' => 'Template 1 One Side', 'class'=> 'one-side-left', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-1.png', 'href'=> 'timeline-template-single-side-1'),
			array('id' => '2',   'template_name' => 'Template 2 One Side', 'class'=> 'one-side-left', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-2.png', 'href'=> 'timeline-template-single-side-2'),
			array('id' => '5',   'template_name' => 'Template 5 One Side', 'class'=> 'one-side-left', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-5.png', 'href'=> 'timeline-template-single-side-5'),
			array('id' => '6',   'template_name' => 'Template 6 One Side',  'class'=> 'one-side-left', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-6.png', 'href'=> 'timeline-template-single-side-6'),
			array('id' => '8',   'template_name' => 'Template 8 One Side', 'class' => 'one-side-left', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-8.png', 'href'=> 'timeline-template-single-side-8'),
			
			array('id' => '0-h', 'template_name' => 'Template 0 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-0h.png',  'href' => 'timeline-template-horizontal-0'),
			array('id' => '1-h', 'template_name' => 'Template 1 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-1h.png', 'href' => 'timeline-template-horizontal-1'),
			array('id' => '2-h', 'template_name' => 'Template 2 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-2h.png', 'href' => 'timeline-template-horizontal-2'),
			array('id' => '3-h', 'template_name' => 'Template 3 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-3h.png', 'href' => 'timeline-template-horizontal-3'),
			array('id' => '4-h', 'template_name' => 'Template 4 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-4h.png', 'href' => 'timeline-template-horizontal-4'),
			array('id' => '5-h', 'template_name' => 'Template 5 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-5h.png', 'href' => 'timeline-template-horizontal-5'),
			array('id' => '6-h', 'template_name' => 'Template 6 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-6h.png', 'href' => 'timeline-template-horizontal-6'),
			array('id' => '7-h', 'template_name' => 'Template 7 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-7h.png', 'href' => 'timeline-template-horizontal-7'),
			array('id' => '8-h', 'template_name' => 'Template 8 Slider', 'image' => POST_TIMELINE_URL_PATH . 'admin/images/banners/template-9h.png', 'href' => 'timeline-template-horizontal-8')
		);
		include POST_TIMELINE_PLUGIN_PATH.'admin/partials/multiple-templates.php';
	}


	function include_template_function( $template_path ) {
	 	

	    if ( get_post_type() == 'post-timeline' ) {

	        if ( is_single() ) {
	            // checks if the file exists in the theme first,
	            // otherwise serve the file from the plugin
	            if ( $theme_file = locate_template( array ( 'single-post-timeline.php' ) ) ) {
	                $template_path = $theme_file;
	            } else {
	                $template_path = POST_TIMELINE_PLUGIN_PATH . 'public/partials/post-timeline-page.php';
	            }
	        }
	    }
	    return $template_path;
	}

	/**
	 * Initialize the timeline settings meta box.
	 *
	 * @since    0.0.1
	 */
	function add_post_timeline_meta_box() {

		$settings = get_option( 'post_timeline_global_settings' );


	    $timeline_meta_box = array(
	      'id'        => 'post_timeline_timeline_metabox',
	      'title'     => __( 'Post Timeline Details', 'post-timeline' ),
	      'pages'     => array( 'post-timeline' ),
	      'context'   => 'normal',
	      'priority'  => 'high',
	      'fields'    => array(
	      	array(

	        	'id'          => 'ptl-fav-icon',
			    'label'       => __( 'Post Icon', 'post-timeline' ),
			    'desc'        => __( 'Select Font Awesome Icon', 'post-timeline' ),
			    'type'        => 'icon-picker',
			    'class'		  => 'validate[required]',
			    'value'		  => '0'
	        ),
	        array(

	        	'id'          => 'ptl-post-date',
			    'label'       => __( 'Post Date', 'post-timeline' ),
			    'desc'        => __( 'Select the Date/Month/Year of Timeline', 'post-timeline' ),
			    'type'        => 'date-time-picker',
			    'class'		  => 'validate[required]',
			    'value'		  => '0'
	        ),
	        array(
		        'id'          => 'ptl-post-tag',
		        'label'       => __( 'Post Tag', 'post-timeline' ),
		        'desc'        => __( 'Add Tags for Navigation. Without Tags, Tag based Navigation will not appear', 'post-timeline' ),
		        'type'        => 'tag_select'
	        ),
	        /*array(
		        'id'          => 'ptl-post-type',
		        'label'       => __( 'Post Type', 'post-timeline' ),
		        'desc'        => __( 'Select the Type of Post to Display.', 'post-timeline' ),
		        'type'        => 'select',
	          	'choices'     => array(
			        array(
			            'label'     => __( 'Image', 'post-timeline' ),
			            'value'     => '0'
			        ),
		            array(
		              'label'     => __( 'Video', 'post-timeline' ),
		              'value'     => '1'
		            )
		        )
	        ),*/
	        array(
		        'id'          => 'ptl-post-gallery',
		        'label'       => __( 'Slider Gallery', 'post-timeline' ),
		        'desc'        => __( 'Upload All Images of Slider.', 'post-timeline' ),
		        'type'        => 'slider'
	        ),
	        /*array(
		        'id'          => 'ptl-post-vidlink',
		        'label'       => __( 'Video Link URL', 'post-timeline' ),
		        'desc'        => __( 'Youtube, Dailymotion, Vimeo.', 'post-timeline' ),
		        'type'        => 'text'
	        ),*/
	        array(
		        'id'          => 'ptl-post-link',
		        'label'       => __( 'Page URL', 'post-timeline' ),
		        'desc'        => __( 'For Separate Page URL(Optional).', 'post-timeline' ),
		        'type'        => 'text'
	        ),
	        array(
		        'id'          => 'ptl-img-txt-pos',
		        'label'       => __( 'Position', 'post-timeline' ),
		        'desc'        => __( 'Select the Position of Text and Image.', 'post-timeline' ),
		        'type'        => 'select',
	          	'choices'     => array(
			        array(
			            'label'     => __( 'Image Top', 'post-timeline' ),
			            'value'     => '0'
			        ),
		            array(
		              'label'     => __( 'Text Top', 'post-timeline' ),
		              'value'     => '1'
		            )
		        )
	        ),
	        array(
		        'id'          => 'ptl-tag-line',
		        'label'       => __( 'Sub Heading or Tag line', 'post-timeline' ),
		        'class'		  => '',
		        'desc'        => __( 'Tag Line (Optional)', 'post-timeline' ),
		        'type'        => 'text',
	        ),
	        array(
		        'id'          => 'ptl-post-order',
		        'label'       => __( 'Post Order (Number Only)', 'post-timeline' ),
		        'class'		  => 'validate[required,custom[integer]]',
		        //'class'		  => 'validate[custom[integer]]',
		        'desc'        => __( 'Custom Ordering of Posts, default "1".', 'post-timeline' ),
		        'type'        => 'text',
			    'std' 		  => '1'
	        ),
	       	array(
		        'id'          => 'ptl-post-color',
		        'label'       => __( 'Post Color', 'post-timeline' ),
		        'desc'        => __( 'Color of Post (Default Color will be used if not selected).', 'post-timeline' ),
		        'type'        => 'colorpicker'
	        ),
	      )
	    );


	    ot_register_meta_box( $timeline_meta_box );
	    add_action('admin_enqueue_scripts', array($this,'add_js_to_page')); 
	}


	function add_js_to_page() {

		if(is_admin())
			wp_enqueue_style( 'p_timeline_bootstrap', POST_TIMELINE_URL_PATH . 'admin/css/bootstrap.min.css', array(), $this->version, 'all' );
		else
			wp_enqueue_style( 'p_timeline_bootstrap', POST_TIMELINE_URL_PATH . 'public/css/bootstrap.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'font-awesome',  'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'p_timeline_base', POST_TIMELINE_URL_PATH . 'public/css/post-timeline.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( 'p_timeline_lib', POST_TIMELINE_URL_PATH . 'admin/css/ptl_libs.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'p_timeline_css', POST_TIMELINE_URL_PATH . 'admin/css/timeline_admin.css', array(), $this->version, 'all' );

  		wp_enqueue_script('p_timeline_libs', POST_TIMELINE_URL_PATH.'admin/js/fontawesome-iconpicker.min.js', array('jquery'));
  		wp_enqueue_script('p_timeline_script', POST_TIMELINE_URL_PATH.'admin/js/timeline_script.js', array('jquery'));
  		wp_enqueue_script( 'p_timeline_script_timeline', POST_TIMELINE_URL_PATH . 'public/js/post-timeline.js', array('jquery'), $this->version, false );
		wp_localize_script( $this->plugin_name.'-script', 'PTL_REMOTE', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' )
		));
	}

	function timeline_columns($gallery_columns) {		

		return array(
				"cb"  			=>  '<input type="checkbox" />',
				"title"  		=>  _x('Timeline Title', 'post-timeline'),
				"icon"  		=>  __('Icon'),
				"images"  		=>  __('Timeline Image'),
				"event_date"  	=>  __('Event Date'),
				"date"  		=>  _x('Published', 'post-timeline'),
				"content"  		=>  _x('Timeline Content', 'post-timeline')
			);
	}



	function timeline_column_details( $_column_name, $_post_id ) {
		

		switch ( $_column_name ) {
			
			case "event_date":

				
				$timeline_date = get_post_meta( $_post_id, 'ptl-post-date', true );
				

				if($timeline_date ) {

					$timeline_date = date_format(date_create($timeline_date)," M - d - Y");
				}

				echo $timeline_date;
				
				break;

			case "images":

				$post_image_id = get_post_thumbnail_id(get_the_ID());
				
				if ($post_image_id) {
					
					/*$_p = get_post($post_image_id);
					echo ptl_get_image($_p);*/
					$thumbnail = wp_get_attachment_image_src( $post_image_id, array(150,150), false);
					if ($thumbnail) (string)$thumbnail = $thumbnail[0];
					echo '<img src="'.$thumbnail.'" alt="" />';
				}
			  	break;

			case "icon":

				$post_icon  = get_post_meta(get_the_ID(),'ptl-fav-icon');
				$post_color = get_post_meta(get_the_ID(),'ptl-post-color');

				$post_icon  = (is_array($post_icon) && isset($post_icon[0]))?$post_icon[0]:'';
				$post_color = (is_array($post_color) && isset($post_color[0]))?$post_color[0]:'';

				
				echo '<span class="ptl-ico" style="background:'.$post_color.'" ><span class="fa '.$post_icon.'"></span></span>';
			  	break;

			case "content":
				echo  $content = get_the_excerpt();
				break;
		  }
	}

	/**
	 * Filter the required "title" field for list-item option types.
	 *
	 * @since    0.0.1
	 */
  	function filter_post_list_item_title_label( $label, $id ) {


	    return $label;

  	}

	//TODO Next two functions are terribad

	/**
	 * Filter the OptionTree header logo link
	 *
	 * @since    0.0.1
	 */
  	function filter_header_logo_link() {

		$screen = get_current_screen();
		if( $screen->id == 'page_post_timeline-settings' ) {
			return '';
		} else {
			return '<a href="https://wordpress.org/plugins/post-timeline/" target="_blank">Post Timeline</a>';
		}

  	}

	/**
	 * Filter the OptionTree header version text
	 *
	 * @since    0.0.1
	 */
	function filter_header_version_text() {

		$screen = get_current_screen();
		if( $screen->id == 'page_post_timeline-settings' ) {
			return '<a href="https://wordpress.org/plugins/post-timeline" target="_blank">' . $this->plugin_name . ' - v' . $this->version . '</a>';
		} else {
			return 'POST Timeline';
		}

	}


	/**
	 * OptionTree options framework for generating plugin settings page & metaboxes.
	 *
	 * Only needs to load if no other theme/plugin already loaded it.
	 *
	 * @since 0.0.1
	 */
	function include_optiontree() {

		if ( ! class_exists( 'OT_Loader' ) ) {
    	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/option-tree/ot-loader.php';

			/* TODO - probably shouldn't be doing this here */
			add_filter( 'ot_show_pages', '__return_false' );
			add_filter( 'ot_use_theme_options', '__return_false' );
		}

	}

	
	/**
	 * Registers a new global timeline settings page.
	 *
	 * @since    0.0.1
	 */
	public function register_post_timeline_settings_page() {

		// Only execute in admin & if OT is installed
	  	if ( is_admin() && function_exists( 'ot_register_settings' ) ) {

		    // Register the page
	    	ot_register_settings(
	        array(
	      		array(
	            'id'              => 'post_timeline_global_settings',
	            'pages'           => array(
	              array(
		              'id'              => 'post-timeline-settings',
		              'parent_slug'     => 'edit.php?post_type=post-timeline',
		              'page_title'      => __( 'Post Timeline - Global Settings', 'post-timeline' ),
		              'menu_title'      => __( 'Settings', 'post-timeline' ),
		              'capability'      => 'edit_theme_options',
		              'menu_slug'       => 'post-timeline-settings',
		              'icon_url'        => null,
		              'position'        => null,
		              'updated_message' => __( 'Settings updated', 'post-timeline' ),
		              'reset_message'   => __( 'Settings reset', 'post-timeline' ),
		              'button_text'     => __( 'Save changes', 'post-timeline' ),
		              'show_buttons'    => true,
		              'screen_icon'     => 'options-general',
		              'contextual_help' => null,
		              'sections'        => array(
		              		array(
		                  		'id'          => 'post-timeline-general',
		                  		'title'       => __( '<i class="fa fa-cog"></i>Basic', 'post-timeline' ),
		                	),
		                	array(
		                  		'id'          => 'post-timeline-styles',
		                  		'title'       => __( '<i class="fa fa-cog"></i>Styles', 'post-timeline' ),
		                	),
		                	array(
		                  		'id'          => 'post-timeline-navigation',
		                  		'title'       => __( '<i class="fa fa-cog"></i>Navigation', 'post-timeline' ),
		                	),
		                	array(
		                  		'id'          => 'post-timeline-animation',
		                  		'title'       => __( '<i class="fa fa-cog"></i>Animation', 'post-timeline' ),
		                	),
		                	/*array(
		                  		'id'          => 'post-timeline-dates',
		                  		'title'       => __( 'Dates', 'post-timeline' ),
		                	),*/
		                	array(
		                  		'id'          => 'post-timeline-custom',
		                  		'title'       => __( '<i class="fa fa-cog"></i>Custom', 'post-timeline' ),
		                	)
		              	),
	                	'settings'        => array(
	                		/*Settings*/
	                		array(

					        	'id'          => 'ptl-fav-icon',
							    'label'       => __( 'Default Post Icon', 'post-timeline' ),
							    'desc'        => __( 'Select Font Awesome Icon', 'post-timeline' ),
							    'type'        => 'icon-picker',
							    'class'		  => 'validate[required]',
							    'section'   => 'post-timeline-general',
							    'value'		  => ''
					        ),
					        array(
						        'id'          => 'ptl-post-icon',
						        'label'       => __( 'Post Icon', 'post-timeline' ),
						        'desc'        => __( 'Enable/Disable Icon', 'post-timeline' ),
						        'section'     => 'post-timeline-general',
						        'type'        => 'on-off'
					        ),
					        array(
						        'id'          => 'ptl-type',
						        'label'       => __( 'Timeline Type', 'post-timeline' ),
						        'desc'        => __( 'For "Tag Based", Post must be assigned to their tags, else nothing will appear. Custom Ordering Works by the Order defined in each timeline Post.', 'post-timeline' ),
						        'section'     => 'post-timeline-general',
						        'type'      => 'radio',
						        'choices'	=> array(
								    array(
								        'value'   => 'tag',
								        'label'   => __( 'Tag Based', 'post-timeline' )
								    ),
								    array(
								        'value'   => 'date',
								        'label'   => __( 'Date Based', 'post-timeline' )
								    ),
								    array(
								        'value'   => 'custom_order',
								        'label'   => __( 'Custom Order', 'post-timeline' )
								    )
								)
					        ),
					        array(
								'id'        => 'ptl-sort',
								'label'     => __( 'Sorting Order', 'post-timeline' ),
								'desc'      => __( 'Ascending (Default) or Descending', 'post-timeline' ),
								'type'      => 'radio',
								'section'   => 'post-timeline-general',
								'choices'	=> array(
								    array(
								        'value'   => 'ASC',
								        'label'   => __( 'Ascending', 'post-timeline' )
								    ),
								    array(
								        'value'   => 'DESC',
								        'label'   => __( 'Descending', 'post-timeline' )
								    )
								)
							),
							array(
						        'id'          => 'ptl-post-size',
						        'label'       => __( 'Post Size', 'post-timeline' ),
						        'desc'        => __( 'Select the Post Size.', 'post-timeline' ),
						        'type'        => 'select',
						        'section'   => 'post-timeline-general',
					          	'choices'     => array(
							        array(
							            'label'     => __( 'Default', 'post-timeline' ),
							            'value'     => ''
							        ),
						            array(
						              'label'     => __( 'Medium', 'post-timeline' ),
						              'value'     => 'size-md'
						            ),
						            array(
						              'label'     => __( 'Small', 'post-timeline' ),
						              'value'     => 'size-sm'
						            )
						        )
					        ),
					        array(
						        'id'          => 'ptl-date-format',
						        'label'       => __( 'Post Date', 'post-timeline' ),
						        'desc'        => __( 'Select the Date format to appear.', 'post-timeline' ),
						        'type'        => 'select',
						        'section'   => 'post-timeline-general',
					          	'choices'     => array(
							        array(
							            'label'     => __( 'Feb 22, 2018', 'post-timeline' ),
							            'value'     => 'M j, Y'
							        ),
							        array(
							            'label'     => __( 'March 10, 2001', 'post-timeline' ),
							            'value'     => 'F j, Y'
							        ),
						            array(
						              'label'     => __( '03/10/2017', 'post-timeline' ),
						              'value'     => 'm/d/Y'
						            ),
						            array(
						              'label'     => __( '05-16-18', 'post-timeline' ),
						              'value'     => 'j-m-y'
						            ),
						            array(
						              'label'     => __( '05-16-18', 'post-timeline' ),
						              'value'     => 'j-m-y'
						            ),
						            array(
						              'label'     => __( 'Sat Mar 10 17:16:18', 'post-timeline' ),
						              'value'     => 'D M j G:i:s'
						            ),
						            array(
						              'label'     => __( '2001-03-10', 'post-timeline' ),
						              'value'     => 'Y-m-d'
						            )
						        )
					        ),
					        array(
						        'id'          => 'ptl-post-length',
						        'label'       => __( 'Post Content Length', 'post-timeline' ),
						        'desc'        => __( 'Length of Post By Character Count', 'post-timeline' ),
						        'section'     => 'post-timeline-general',
						        'value'       => '350',
						        'type'        => 'text'
					        ),
					        /*Styles*/
					        array(
						        'id'          => 'ptl-fonts',
						        'label'       => __( 'Fonts', 'post-timeline' ),
						        'desc'        => __( 'Default Font will be used (Optional).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'google-fonts'
					        ),
					        array(
						        'id'          => 'ptl-bg-color',
						        'label'       => __( 'Container Background', 'post-timeline' ),
						        'desc'        => __( 'Background Color of Container (Empty for Default).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'colorpicker'
					        ),
					        array(
						        'id'          => 'ptl-post-bg-color',
						        'label'       => __( 'Post Background', 'post-timeline' ),
						        'desc'        => __( 'Background Color of Post (Empty for Default).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'colorpicker'
					        ),
					        array(
						        'id'          => 'ptl-post-head-color',
						        'label'       => __( 'Heading Font Color', 'post-timeline' ),
						        'desc'        => __( 'Font Color Heading (Optional).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'colorpicker'
					        ),
					        array(
						        'id'          => 'ptl-post-desc-color',
						        'label'       => __( 'Description Font Color', 'post-timeline' ),
						        'class'		  => 'validate[required]',
						        'desc'        => __( 'Font Color of Post Description (Optional).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'colorpicker',
					        ),
					        /*array(
						        'id'          => 'ptl-head-font-size',
						        'label'       => __( 'Heading Font Size', 'post-timeline' ),
						        'class'		  => 'validate[required]',
						        'desc'        => __( 'Default is Recommended, (Optional).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'Measurement'
					        ),
					        array(
						        'id'          => 'ptl-desc-font-size',
						        'label'       => __( 'Description Font Size', 'post-timeline' ),
						        'class'		  => 'validate[required]',
						        'desc'        => __( 'Default is Recommended, (Optional).', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'Measurement'
					        ),*/
					        array(
						        'id'          => 'ptl-bg-status',
						        'label'       => __( 'Container Background Color', 'post-timeline' ),
						        'class'		  => 'validate[required]',
						        'desc'        => __( 'Set Background', 'post-timeline' ),
						        'section'     => 'post-timeline-styles',
						        'type'        => 'on-off'
					        ),
					        /*Navigation*/
					        array(
								'id'        => 'ptl-nav-type',
								'label'     => __( 'Navigation Styles', 'post-timeline' ),
								'desc'      => __( 'Default will be used if empty (Optional)', 'post-timeline' ),
								'type'      => 'radio_image',
								'section'   => 'post-timeline-navigation',
								'choices'	=> array(
								      array(
								        'value'   => 'style-1',
								        'label'   => __( 'Style 1', 'post-timeline' ),
								        'src'     => POST_TIMELINE_URL_PATH . '/admin/images/layout/nav-1.png'
								      ),
								      array(
								        'value'   => 'style-2',
								        'label'   => __( 'Style 2', 'post-timeline' ),
								        'src'     => POST_TIMELINE_URL_PATH . '/admin/images/layout/nav-2.png'
								      ),
								      array(
								        'value'   => 'style-3',
								        'label'   => __( 'Style 3', 'post-timeline' ),
								        'src'     => POST_TIMELINE_URL_PATH . '/admin/images/layout/nav-3.png'
								      ),
								      array(
								        'value'   => 'style-4',
								        'label'   => __( 'Style 4', 'post-timeline' ),
								        'src'     => POST_TIMELINE_URL_PATH . '/admin/images/layout/nav-4.png'
								      ),
								      array(
								        'value'   => 'style-5',
								        'label'   => __( 'Style 5', 'post-timeline' ),
								        'src'     => POST_TIMELINE_URL_PATH . '/admin/images/layout/nav-5.png'
								      )
								)
							),
							array(
						        'id'          => 'ptl-nav-color',
						        'label'       => __( 'Navigation Color', 'post-timeline' ),
						        'desc'        => __( 'Color of Side Navigation (Empty for Default).', 'post-timeline' ),
						        'section'     => 'post-timeline-navigation',
						        'type'        => 'colorpicker'
					        ),
					       	array(
						        'id'          => 'ptl-post-load',
						        'label'       => __( 'More Post Load Method', 'post-timeline' ),
						        'desc'        => __( 'Select between (Load More - Ajax and ALL)', 'post-timeline' ),
						        'type'        => 'radio',
						        'section'   => 'post-timeline-navigation',
					          	'choices'     => array(
						            array(
						              'label'     => __( 'ALL', 'post-timeline' ),
						              'value'     => '0'
						            ),
						            array(
							            'label'     => __( 'Load More', 'post-timeline' ),
							            'value'     => '1'
							        )
						        )
					        ),
					  		array(
						        'id'          => 'ptl-post-per-page',
						        'label'       => __( 'Post Per Page', 'post-timeline' ),
						        'desc'        => __( 'No of Post in Each Page', 'post-timeline' ),
						        'section'     => 'post-timeline-navigation',
						        'type'        => 'text'
					        ),
					        array(
						        'id'          => 'ptl-nav-status',
						        'label'       => __( 'Navigation Enabled', 'post-timeline' ),
						        'class'		  => '',
						        'desc'        => __( '', 'post-timeline' ),
						        'section'     => 'post-timeline-navigation',
						        'type'        => 'on-off'
					        ),
					        array(
						        'id'          => 'ptl-rm-status',
						        'label'       => __( 'Read more Enabled', 'post-timeline' ),
						        'class'		  => '',
						        'desc'        => __( '', 'post-timeline' ),
						        'section'     => 'post-timeline-navigation',
						        'type'        => 'on-off'
					        ),

					        /*Animations*/
					        array(
						        'id'          => 'ptl-anim-status',
						        'label'       => __( 'Animation Enabled', 'post-timeline' ),
						        'class'		  => '',
						        'desc'        => __( '', 'post-timeline' ),
						        'section'     => 'post-timeline-animation',
						        'type'        => 'on-off'
					        ),
					        array(
						        'id'          => 'ptl-anim-type',
						        'label'       => __( 'Animation Type', 'post-timeline' ),
						        'desc'        => __( '(Optional)', 'post-timeline' ),
						        'type'        => 'select',
						        'section'   => 'post-timeline-animation',
					          	'choices'     => array(
							        array(
							            'label'     => __( 'Fade', 'post-timeline' ),
							            'value'     => 'fadeIn'
							        ),
						            array(
						              'label'     => __( 'Slide Up', 'post-timeline' ),
						              'value'     => 'fadeInUp'
						            ),
						            array(
						              'label'     => __( 'fadeInLeft/Right', 'post-timeline' ),
						              'value'     => 'fadeInLeft'
						            )
						        )
					        ),
					        array(
						        'id'          => 'ptl-anim-delay',
						        'label'       => __( 'Delay Period', 'post-timeline' ),
						        'class'		  => '',
						        'desc'        => __( 'Delay in Milli-Seconds (Optional)', 'post-timeline' ),
						        'section'     => 'post-timeline-navigation',
						        'type'        => 'text',
					        ),
					        /*Other*/
							array(
								'id'        => 'ptl-custom-css',
								'label'     => __( 'Custom CSS', 'post-timeline' ),
								'desc'      => __( 'Add your css for the timeline colors', 'post-timeline' ),
								'type'      => 'css',
								'section'   => 'post-timeline-custom',
							),
							/*Typo*/
							array(
								'id'        => 'ptl-css',
								'label'     => __( 'Custom CSS', 'post-timeline' ),
								'desc'      => __( 'Add your css for the timeline colors', 'post-timeline' ),
								'type'      => 'css',
								'section'   => 'post-timeline-type',
							)
	                	)
	              	)
	            )
	          )
	        
	        ));

		}
	}

}

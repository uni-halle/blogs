<?php

/** 
 * PRESENTATION PAGE COLUMNS 
 */

// Counting the PP column widgets
global $tempera_column_counter;
$tempera_column_counter = 0;

class ColumnsWidget extends WP_Widget
{ 	
  var $temperas; // theme options read in the constructor
  
  public function __construct() { 
    $widget_ops = array('classname' => 'ColumnsWidget', 'description' => 'Add columns in the presentation page' );
	$control_ops = array('width' => 350, 'height' => 350); // making widget window larger
	parent::__construct('columns_widget', 'Cryout Column', $widget_ops, $control_ops);
	$this->temperas = tempera_get_theme_options(); // reading theme options
  } // construct()
  
  public function ColumnsWidget(){
	self::__construct();	  
  } // PHP4 constructor

  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'image' => '', 'title' => '' , 'text' => '',  'link' => '',  'blank' => '' ) );
    $image = $instance['image'];
	$title = $instance['title'];
	$text = $instance['text'];
	$link = $instance['link'];
	$blank = $instance['blank'];?>
	<div>
		<p><label for="<?php echo $this->get_field_id('image'); ?>">Image: <input class="widefat slideimages" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_url($image); ?>" /></label><a class="upload_image_button button" href="#">Select / Upload Image</a></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('text'); ?>">Text: <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" ><?php echo esc_attr($text); ?></textarea></label></p>
		<p><label for="<?php echo $this->get_field_id('link'); ?>">Link: <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_url($link); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('blank'); ?>">Open in new Window: <input id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>" type="checkbox" <?php checked($blank, 1); ?> value="1" /></label></p>
	</div> <?php  
  } // form()

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['image'] = $new_instance['image'];
	$instance['title'] = $new_instance['title'];
	$instance['text'] = $new_instance['text'];
	$instance['link'] = $new_instance['link'];
	$instance['blank'] = $new_instance['blank'];
    return $instance;
  } // update()
  
  function widget($args, $instance) { 
	$tempera_nrcolumns = $this->temperas['tempera_nrcolumns']; // getting the number of columns setting
	global $tempera_column_counter; // global counter for incrementing col count
	$blank="";
	if($instance['blank']) $blank="target='_blank'";
	
	if($instance['image']) : 
		$tempera_column_counter++; // incrementing counter only if widget has image
		$counter = $tempera_column_counter; 
		$coldata = array(
			'colno' => (($counter%$tempera_nrcolumns)?$counter%$tempera_nrcolumns:$tempera_nrcolumns),
			'counter' => $counter,
			'image' => esc_url($instance['image']),
			'link' => esc_url($instance['link']),
			'blank' => ($instance['blank']?'target="_blank"':''),
			'title' =>  $instance['title'],
			'text' => $instance['text'],
			'readmore' => $this->temperas['tempera_columnreadmore'],  // read more setting
		);		
		tempera_singlecolumn_output($coldata);	
	endif; 
  } // widget()
  
} // ColumnsWidget class

add_action( 'widgets_init', create_function('', 'return register_widget("ColumnsWidget");') );

function tempera_widget_scripts() {
	// For the WP uploader
    if(function_exists('wp_enqueue_media')) {
         wp_enqueue_media();
      }
      else {
         wp_enqueue_script('media-upload');
         wp_enqueue_script('thickbox');
         wp_enqueue_style('thickbox');
      }
	wp_register_script('tempera-admin-widgets', get_template_directory_uri().'/admin/js/widgets.js', NULL, _CRYOUT_THEME_VERSION);
	wp_enqueue_script('tempera-admin-widgets'); 
}

add_action ('admin_print_scripts-widgets.php','tempera_widget_scripts');

/**
 * presentation page column output
 */
if ( ! function_exists('tempera_singlecolumn_output') ):
function tempera_singlecolumn_output($data){
	extract($data);
	?>
		<div class="pp-column column<?php echo $colno; ?>">
			<?php if (!empty($image)) {	?>
				<a href="<?php echo $link; ?>" <?php echo $blank; ?> class="clickable-column">
					<?php if (!empty($title)) { echo "<h3 class='column-header-image'>".$title."</h3>"; } ?>
				</a>

				<div class="column-image">
					<div class="column-image-inside">  </div>
						<a href="<?php echo $link; ?>" <?php echo $blank; ?> class="clickable-column">
							<img src="<?php echo esc_url($image) ?>" alt="<?php echo (!empty($title)?wp_kses($title,array()):''); ?>" />
						</a>
					
					<?php if (!empty($text)) { ?>		
						<div class="column-text">
							<?php echo $text; ?>							
						</div>
					<?php } ?>
					<?php if ( !empty($readmore) && !empty($link) ) { ?>
						<div class="columnmore">
							<a href="<?php echo $link; ?>" <?php echo $blank; ?>><?php echo $readmore ?> <i class="column-arrow"></i> </a>
						</div>
					<?php } ?>
				</div><!--column-image-->
			<?php } ?>
		</div><!-- column -->
	<?php
} // tempera_singlecolumn_output()
endif;

?>
<?php
/*
Plugin Name: Colorful Text Widgets
Plugin URI: http://iniyan.in/plugins/colorful-text-widget/
Description: Colorful text widget 
Author: iniyan
Version: 2.0.3
Author URI: http://iniyan.in
*/

//** Adding stylsheet **//
 add_action( 'wp_enqueue_scripts', 'ctw_add_stylesheet' );
    /**
     * Add stylesheet to the page
     */
    function ctw_add_stylesheet() {
        wp_enqueue_style( 'ctw-style', plugins_url('/css/colorful-text-widget-style.css', __FILE__) );
    }

 
    class colorful_text_widget extends WP_Widget {
        function colorful_text_widget() {
            parent::WP_Widget('colorful_text_widget', $name = 'Colorful Text Widget');
        }
 
        function widget($args, $instance) {
            extract($args);
            $title          = apply_filters('widget_title', $instance['title']);
            //$widgetstyle    = apply_filters('widget_title', $instance['widgetstyle']);
            $text           = $instance['text'];
      			$background     = $instance['background'];
      			$titlecolor     = $instance['titlecolor'];
    		    $textcolor      = $instance['textcolor'];
            $borderradius   = substr($instance['borderradius'], 0, -2);
 
            echo    "<div style ='background-color: $background; border-radius: $borderradius"."px;"."' class='ctw'>"."\n";
            echo    $before_widget;
            echo    "<h2 style ='color: $titlecolor;'>" .$title."</h2>";
            echo    "<p  style ='color: $textcolor;'>"  .$text. "</p>";
            echo    $after_widget;
            echo   '</div>'."\n";
        }
 
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']          = strip_tags($new_instance['title']);
            //$instance['widgetstyle']    = strip_tags($new_instance['widgetstyle']);
            $instance['text']           = $new_instance['text'];
      			$instance['background']     = strip_tags($new_instance['background']);
      			$instance['titlecolor']     = strip_tags($new_instance['titlecolor']);
      			$instance['textcolor']      = strip_tags($new_instance['textcolor']);
            $instance['borderradius']   = strip_tags($new_instance['borderradius']);
          
			
            return $instance;
        }
 
        function form($instance) {
      			$background        = esc_attr($instance['background']);
      			$titlecolor        = esc_attr($instance['titlecolor']);
      			$textcolor         = esc_attr($instance['textcolor']);
            $borderradius      = esc_attr($instance['borderradius']);
            if($instance) {
                $title          = esc_attr($instance['title']);
                //$widgetstyle    = esc_attr($instance['widgetstyle']);
                $text           = esc_attr($instance['text']);
					
            } else {
                $title          = __('', 'text_domain');
                //$widgetstyle    = __('', 'text_domain');
                $text           = __('', 'text_domain');
            } ?>
		<script type="text/javascript">
			//<![CDATA[
				jQuery(document).ready(function()
				{
					// colorpicker field
					jQuery('.cw-color-picker').each(function(){
					   var $this = jQuery(this),
					   id = $this.attr('rel');
					   $this.farbtastic('#' + id).hide();
					  jQuery('.cpr, .cpr-tile').click(function(){jQuery('.cw-color-picker').slideDown('slow')});	
					   
					   // jQuery('.cpr').bind('click', function(){jQuery('.cw-color-picker').slideToggle('slow')});	
					});
				});
			//]]>   
		  </script>		
 
           <?php  echo '<p><label for="'.$this->get_field_id('title').'">'._e('Title:').'</label>';
            echo '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></p>';

            echo '<p><label for="'.$this->get_field_id('text').'">'._e('Text:').'</label>';
            echo '<textarea class="widefat" id="'.$this->get_field_id('text').'" name="'.$this->get_field_name('text').'" rows="10">'.$text.'</textarea></p>';?>
            <p>
          	<label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background Color:'); ?></label> 
          	<input class="widefat cpr" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" 
            type="text" 
          	value="<?php if($background) { echo $background; } else { echo '#fff'; } ?>" />
          	<div class="cw-color-picker" rel="<?php echo $this->get_field_id('background'); ?>"></div>
          	</p>
            
            <p>
            <label for="<?php echo $this->get_field_id('titlecolor'); ?>"><?php _e('Title Color:'); ?></label> 
          	<input class="widefat cpr" id="<?php echo $this->get_field_id('titlecolor'); ?>" name="<?php echo $this->get_field_name('titlecolor'); ?>" 
            type="text" value="<?php if($titlecolor) { echo $titlecolor; } else { echo '#fff'; } ?>" />
          	<div class="cw-color-picker" rel="<?php echo $this->get_field_id('titlecolor'); ?>"></div>
          	</p>
            
            <p>
              <label for="<?php echo $this->get_field_id('textcolor'); ?>"><?php _e('Text Color:'); ?></label> 
            	<input class="widefat cpr" id="<?php echo $this->get_field_id('textcolor'); ?>" name="<?php echo $this->get_field_name('textcolor'); ?>" 
              type="text" value="<?php if($textcolor) { echo $textcolor; } else { echo '#fff'; } ?>" />
            	<div class="cw-color-picker" rel="<?php echo $this->get_field_id('textcolor'); ?>"></div>
          	</p>

            <p>
              <label for="<?php echo $this->get_field_id( 'borderradius' ); ?>"><?php _e( 'Border Radius(in px):' ); ?>
              </label>
              <input class="widefat" id="<?php echo $this->get_field_id( 'borderradius' ); ?>" 
              name="<?php echo $this->get_field_name( 'borderradius' ); ?>" type="text" 
              value="<?php echo esc_attr( $borderradius ); ?>" />
            </p>

             
          	<?php }
}
 
    add_action('widgets_init', create_function('', 'return register_widget("colorful_text_widget");'));
	
	function colorful_text_widget_script() {
	  wp_enqueue_script('farbtastic');
	}
	function colorful_text_widget_style() {
		wp_enqueue_style('farbtastic');	
	}
	add_action('admin_print_scripts-widgets.php', 'colorful_text_widget_script');
    add_action('admin_print_styles-widgets.php', 'colorful_text_widget_style');
	
?>

<?php

$widgets_style = array('Default' => 'Default','Block' => 'Block Style', 'Post' => 'Post Style', 'Simple' => 'Simple Text');

if(is_admin())
{
    add_action('sidebar_admin_setup', 'art_widget_process_control');
}


function art_widget_expand_control($id)
{
  global $wp_registered_widget_controls;
  if (strpos($id, 'art_vmenu')!== false) return;
  $controls = &$wp_registered_widget_controls[$id];
  $controls['params'][0]['id']=$id;
  $controls['callback_redirect']=$controls['callback'];
  $controls['callback']='widget_cache_extra_control';
}

function art_widget_process_control()
{
  global $wp_registered_widget_controls;
  if ('post' == strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST['widget-id']) )
  {
    $id = $_POST['widget-id'];
    $id_disp = 'widget-style';
    if (isset($_POST[$id_disp])){
      art_set_widget_style($id, $_POST[$id_disp]);
    }
    art_widget_expand_control($id);
    return;
  } 
  foreach ( $wp_registered_widget_controls as $id => $widget )
  {
    art_widget_expand_control($id);
  }
}


function art_get_widget_style_key($id)
{
  return 'art_widget'.$id.'_style';
}

function art_get_widget_style($id, $style = null)
{
  global $widgets_style;

  $key =  art_get_widget_style_key($id);
  $result = get_option($key);
  if (!$result || !array_key_exists($result, $widgets_style))  
  {
    $result = 'Default';
  }
  if ($style != null) {
    if($style == 'Default') {
      $style == 'Block';
    }
    if($result == 'Default') {
      $result = $style;
    }
  }
  return $result;
}

function art_set_widget_view($id, $style)
{
  global $widgets_style;
  if (!array_key_exists($style, $widgets_style)){
    $style = 'Default';
  }
  $key =  art_get_widget_style_key($id);
  update_option($key, $style);
}


function widget_cache_extra_control()
{
  global $wp_registered_widget_controls, $widgets_style;
  $params = func_get_args();
  $id = $params[0]['id'];
  $id_disp = 'widget-view';
  $val = art_get_widget_style($id);
  $callback=$wp_registered_widget_controls[$id]['callback_redirect'];
	if (is_callable($callback))
	{
		call_user_func_array($callback, $params);
	}
?>	
<p>
  <label for="<?php echo $id_disp; ?>"><?php _e('Style:', THEME_NS) ?></label>
  <select class="widefat" id="<?php echo $id_disp; ?>" name="<?php echo $id_disp; ?>">
<?php
  foreach ( $widgets_style as $key => $option ) {
    $selected = ($val == $key ? ' selected="selected"' : '');
    echo '<option'. $selected .' value="'. $key .'">'. __($option, THEME_NS) .'</option>';
  }
?>
  </select>
</p>
<?php
}

function art_get_vmenu($id='', $class='' , $theme_location = 'secondary-menu', $title = 'Vertical Menu')
{
	$caption = __(art_option('vmenu.source'), THEME_NS);
	if (function_exists('wp_nav_menu') && has_nav_menu( $theme_location ) ) {
    $caption = __($title, THEME_NS);
	} 
  $content = '<ul class="art-vmenu">' . art_get_menu_auto($theme_location, art_option('vmenu.source'), art_option('vmenu.showSubitems')) . '</ul>';
  return art_get_block($caption, $content, $id, $class, 'vmenu');
}



function widget_verticalmenu($args) {
	extract($args);
  $id = art_get_widget_id($before_widget);
  $class = art_get_widget_class($before_widget);
  echo art_get_vmenu($id, $class) . $after_widget;
}

if (class_exists('WP_Widget')){
    class VMenuWidget extends WP_Widget {

        function VMenuWidget() {
            $widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget.', THEME_NS) );
            parent::WP_Widget( 'art_vmenu', __('Vertical Menu', THEME_NS), $widget_ops );
        }

        function widget($args, $instance) {
            extract($args);
            $depth = (!art_option('vmenu.showSubitems') ? 1 : 0);
            $id = art_get_widget_id($before_widget);
            $class = art_get_widget_class($before_widget);
            $caption = $instance['title'];
            $content = '<ul class="art-vmenu">' . art_get_menu($instance['source'], $depth, wp_get_nav_menu_object( $instance['nav_menu'] )) . '</ul>';
            echo art_get_block($caption, $content, $id, $class, 'vmenu') .$after_widget;
        }

        function update( $new_instance, $old_instance ) {
            $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
            $instance['source'] = $new_instance['source'];
            $instance['nav_menu'] = (int) $new_instance['nav_menu'];
            return $instance;
        }

        function form( $instance ) {
            $title = isset( $instance['title'] ) ? $instance['title'] : '';
            $source = isset( $instance['source'] ) ? $instance['source'] : '';
            $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

            // Get menus
            $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
            $sources = array('Pages', 'Categories', 'Custom Menu');
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NS) ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('source'); ?>"><?php _e('Source:', THEME_NS) ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>" onchange="var s = jQuery('.p-<?php echo $this->get_field_id('nav_menu');?>'); if (this.value == 'Custom Menu') s.show(); else s.hide();">
            <?php
                
                foreach ( $sources as $s ) {
                    $selected = ($source == $s ? ' selected="selected"' : '');
                    echo '<option'. $selected .' value="'. $s .'">'. __($s, THEME_NS) .'</option>';
                }
            ?>
                </select>
            </p>
            <p class="p-<?php echo $this->get_field_id('nav_menu'); ?>" <?php if ($source !== 'Custom Menu') echo ' style="display:none"'?>>
            
            <?php // If no menus exists, direct the user to go and create some.
                if ( !$menus ) {
                    ?>
                    <p class="p-<?php echo $this->get_field_id('nav_menu'); ?>" <?php if ($source !== 'Custom Menu') echo ' style="display:none"'?>>
                    <?php
                    printf( __('No menus have been created yet. <a href="%s">Create some</a>.', THEME_NS), admin_url('nav-menus.php') );
                    ?>
                    </p>
                    <?php
                } else { ?>
            
                <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:', THEME_NS); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
            <?php 
                }
                foreach ( $menus as $menu ) {
                    $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                    echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
                }
            ?>
                </select>
            </p>
            <?php
        }
    }

}
// init
function artWidgetsInit(){
    if (WP_VERSION < 3.0) {
        if ( function_exists('register_sidebar_widget') && art_option('vmenu.showVmenu')) {
            register_sidebar_widget(array('Vertical Menu', 'widgets'), 'widget_verticalmenu');
        }
    } else {
        if (class_exists('VMenuWidget') && art_option('vmenu.showVmenu')) {
            register_widget('VMenuWidget');
        }
    }
}

add_action('widgets_init', 'artWidgetsInit');
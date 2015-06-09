<?php
// hook this addon in
add_filter( 'Widget_Wrangler_Admin_Addons', 'ww_clone_admin_addon' );

//
function ww_clone_admin_addon($addons){
  $addons['Clone'] = new WW_Clone_Admin();
  return $addons;
}

/*
 *
 */
class WW_Clone_Admin  {
  public $page_hook;

  function __construct(){
    add_action( 'admin_menu', array( $this, 'wp_admin_menu' ) );
  }

  /**
   * Implements action 'admin_menu'
   */
  function wp_admin_menu(){
    $page_title = 'Copy WP Widget';

    add_submenu_page($this->ww->admin->parent_slug, $page_title, $page_title, $this->ww->admin->capability, 'clone', array( $this, '_menu_router' ));
  }

  /*
   * Handles settings pages
   *   - all settings pages submit to this execute_action
   */
  function _menu_router(){
    if (isset($_GET['ww_action'])){
      switch($_GET['ww_action']){
        case 'insert':
          // create new cloned widget
          $new_post_id = $this->_clone_insert($_POST);
          // goto new widget page
          wp_redirect(get_bloginfo('wpurl').'/wp-admin/post.php?post='.$new_post_id.'&action=edit');
          break;
      }
      exit;
    }
    else {
      // TODO - move to file
      add_action( "admin_print_footer_scripts", array( $this, 'wp_admin_print_footer_scripts' ) );
      add_action( "admin_head", array( $this->ww->admin, '_admin_css' ) );
      
      $this->_clone_form();
    }
  }

  /*
   * Inserts a cloned WP widget as a WW widget
   */
  function _clone_insert($posted)
  {
    global $wpdb,$wp_widget_factory;
    
    //Start our outputs
    $this_class_name = '';
    $instance = array();
    
    if(isset($posted[$posted['ww-keyname']])){
      $this_class_name = $posted['ww-classname'];
      foreach($posted[$posted['ww-keyname']] as $i => $settings){
        foreach($settings as $key => $value){
          $instance[$key] = $value;
        }
      }
    }

    $user = wp_get_current_user();
    
    $wp_widget = new $this_class_name;
    $instance = $wp_widget->update($instance, array());  
    
    // prep new widget info for saving
    $new_widget = array();
    $new_widget['post_author']    = $user->ID;
    $new_widget['post_title']     = ($instance['title']) ? $instance['title'] : "Clone of ".$this_class_name;
    $new_widget['post_excerpt']   = __('Cloned from', 'widgetwrangler') .' '. $this_class_name;
    $new_widget['comment_status'] = 'closed';
    $new_widget['ping_status']    = 'closed';
    $new_widget['post_status']    = 'draft';
    $new_widget['post_type']      = 'widget';
    // Herb contributed fix for problem cloning
    $new_widget['post_content']   = '';
    $new_widget['to_ping']        = '';
    $new_widget['pinged']         = '';
    $new_widget['post_content_filtered'] = '';
    
    // insert new widget into db
    $new_post_id = wp_insert_post($new_widget);
    $instance['ID'] = $new_post_id;
    $instance['hide_title'] = '';
    
    // post as meta values
    add_post_meta($new_post_id,'ww-widget-type', 'clone');
    add_post_meta($new_post_id,'ww-clone-classname', $this_class_name);
    add_post_meta($new_post_id,'ww-clone-instance', $instance);
    
    return $new_post_id;
  }
  
  /*
   * Display widgets available for cloning.
   */
  function _clone_form()
  {
    global $wp_widget_factory;
    $total_widgets = count($wp_widget_factory->widgets);
    $half = round($total_widgets/2);
    $i = 0; 
    ?>
    <div class='wrap'>
      <h2><?php _e("Copy a Widget", 'widgetwrangler'); ?></h2>
      <p><?php _e("Here you can copy (instantiate) an existing WordPress widget into Widget Wrangler.", 'widgetwrangler'); ?></p>
      <p><?php _e("Click on the title of the widget you would like to copy, fill in the widget's form according to your needs and click 'Create'. This will create an instance of the chosen widget in Widget Wrangler.", 'widgetwrangler'); ?></p>
      <ul class='ww-clone-widgets'>
      <?php
        foreach ($wp_widget_factory->widgets as $classname => $widget)
        {
          $posted_array_key = "widget-".$widget->id_base;

          // break into 2 columns
          if ($i == $half)
          { ?>
            </ul><ul class='ww-clone-widgets'>
            <?php
          }
          
          ob_start();
            $wp_widget = new $classname;
            $wp_widget->form(array());
          $new_class_form = ob_get_clean();
          ?>
            <li class="ww-widgets-holder-wrap">
              <div class='widget widgets-holder-wrap closed'>
                <div class='widget-top'>
                  <div class='widget-title-action sidebar-name'>
                    <div class='widget-action sidebar-name-arrow handlediv'></div>
                  </div>
                  <div class="widget-title">
                    <h4><?php print $widget->name; ?></h4>
                  </div>
                </div>
                <div class='widget-inside'>            
                  <form action='edit.php?post_type=widget&page=clone&ww_action=insert&noheader=true' method='post'>
                    <input type='hidden' name='ww-classname' value='<?php print $classname; ?>' />
                    <input type='hidden' name='ww-keyname' value='<?php print $posted_array_key; ?>' />
                    <?php print $new_class_form; ?>
                    <input class='ww-clone-submit button button-primary button-large' type='submit' value='Create' />
                  </form>
                </div>
              </div>
            </li>
          <?php
          $i++;
        }
      ?>
      </ul>
    </div>
    <?php
  }

  //
  function wp_admin_print_footer_scripts()
  { ?>
    <script type="text/javascript">
      jQuery(document).ready(function(){
        // open and close widget menu
        jQuery('.widget-top').click(function(){
          jQuery(this).closest('.widgets-holder-wrap').find('.widget-inside').slideToggle('fast');
        });
      });
    </script>
    <?php
  }
    
}
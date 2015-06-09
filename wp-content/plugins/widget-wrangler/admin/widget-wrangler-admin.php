<?php
/*
 * Widget Wrangler Admin Panel and related functions
 *

filters
  - Widget_Wrangler_Admin_Addons

actions
  - widget_wrangler_form_meta  (doesn't get replaced on ajax)
  - widget_wrangler_form_top
  - widget_wrangler_form_bottom
  
*/

/**
 * Class Widget_Wrangler_Admin
 */
class Widget_Wrangler_Admin {
  public $addons = array();
  public $parent_slug = 'edit.php?post_type=widget';
  public $capability = 'manage_options';
  public $editing_post_id = FALSE;
  
  function __construct(){
    include_once WW_PLUGIN_DIR.'/admin/admin-clone.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-presets.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-corrals.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-sidebars.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-settings.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-shortcode-tinymce.php';
    include_once WW_PLUGIN_DIR.'/admin/admin-taxonomies.php';
    include_once WW_PLUGIN_DIR.'/admin/sortable.php';
    
    global $widget_wrangler;
    $this->ww = $widget_wrangler;
    
    // get all our admin addons
    $this->_gather_addons();

    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );

    // this is the best hook i could find that ensures we're editing a post
    // and the global $post object is available
    add_action( 'add_meta_boxes', array( $this->ww, 'find_all_page_widgets' ), -99 );
  }
  
  //
  function _gather_addons(){
    global $widget_wrangler;
    
    // get all addons
    $addons = apply_filters( 'Widget_Wrangler_Admin_Addons', $this->addons );
    
    // give access to the ww object
    if ( ! empty( $addons ) ){
      foreach ($addons as $addon_name => $addon){
        $addon->ww = $widget_wrangler;
      }
    }

    $this->addons = $addons;
  }
  
  // WordPress hook 'admin_init'
  function wp_admin_init(){
    add_action( 'widget_wrangler_form_meta' , array( $this, 'ww_form_meta' ) );
          
    // Add metabox to enabled post_types
    if (!empty($this->ww->settings['post_types'])){
      foreach($this->ww->settings['post_types'] as $enabled_post_type){
        add_meta_box('ww_admin_meta_box', __('<img src="'.WW_PLUGIN_URL.'/admin/images/lasso-small-black.png" />Widget Wrangler'), array( $this, '_sortable_widgets_meta_box'), $enabled_post_type, 'normal', 'high');
        // Add some CSS to the admin header on the widget wrangler pages, and edit pages
        if ($this->_is_editing_enabled_post_type()){
          $this->init_sortable_widgets();
        }
        
        if (isset($_POST['post_type']) && $_POST['post_type'] == $enabled_post_type){
          add_action( 'save_post', array( $this, '_save_post_widgets' ) );   // admin/sortable-widgets-metabox.inc
        }
      }
    }
  }
   
  // add js and css for sortable widgets
  function init_sortable_widgets(){
    add_action( 'admin_enqueue_scripts', array( $this, '_sortable_widgets_js' ) );
    add_action( 'admin_head', array( $this, '_admin_css' ) );
  }

  //
  function _array_merge_recursive($default, $new){
    foreach ($default as $k => $v){
      if (isset($new[$k])) {
        if (is_array($v)){
          $default[$k] = $this->_array_merge_recursive($default[$k], $new[$k]);
        }
        else {
          $default[$k] = $new[$k];
        }
      }
    }
    return $default;
  }
  
  //
  // Generic admin form output
  //
  function _form($form = array(), $content = ''){
    $default_form = array(
      'title' => 'Form Title',
      'description' => 'Form Description',
      'submit_button' => array(
        'attributes' => array(
          'value' => 'Save Settings',
          'class' => 'button button-primary button-large',
          ),
        'location' => 'top',
      ),
      'attributes' => array(
        'class' => 'ww-form',
        'action' => '',
        'method' => 'post'
        ),
      );
    
    $form = $this->_array_merge_recursive($default_form, $form);
    $form_attributes = '';
    $submit_button_attributes = '';
    
    // make form attributes attributes
    foreach ($form['attributes'] as $name => $value){
      $form_attributes.= " {$name}='{$value}'";
    }
    
    // make submit button element attributes
    foreach ($form['submit_button']['attributes'] as $name => $value){
      $submit_button_attributes.= " {$name}='{$value}'";
    }
    
    $form['attributes']['output'] = $form_attributes;
    $form['submit_button']['attributes']['output'] = $submit_button_attributes;
    
    ob_start();
    ?>
      <a id="widget-wrangler"></a><br />
      <div class="wrap">
        <form <?php print $form['attributes']['output']; ?>>
          <div class="ww-admin-top">
            
            <?php if ($form['submit_button']['location'] == "top") { ?>
              <p class="ww-top-right-save">
                <input type="submit" <?php print $form['submit_button']['attributes']['output']; ?> />
              </p>
            <?php } ?>
            
            <h2 class="ww-admin-title"><?php print $form['title']; ?></h2>
            <div class="ww-clear-gone">&nbsp;</div>
            <p class="description"><?php print $form['description']; ?></p>
          </div>
          <div>
            <?php print $content; ?>
          </div>
          <div class="ww-clear-gone">&nbsp;</div>
          
          <?php if ($form['submit_button']['location'] == "bottom") { ?>
            <p class="ww-top-right-save">
              <input type="submit" <?php print $form['submit_button']['attributes']['output']; ?> />
            </p>
          <?php } ?>
        
        </form>
      </div>
    <?php
    return ob_get_clean();
  }

  //
  // generic admin page output
  //
  function _page($page = array(), $content = ''){
    $default_page = array(
      'title' => 'Page Title',
      'description' => '',
      );
    $page = array_merge($default_page, $page);
    ob_start();
    ?>
      <div class="wrap">
        <div class="ww-admin-top">
          <h2><?php printf( __('%s', 'widgetwrangler'), $page['title']); ?></h2>
          <p class="description"><?php printf( __('%s', 'widgetwrangler'), $page['description']); ?></p>
        </div>
        <div>
          <?php print $content; ?>
        </div>
        <div class="ww-clear-gone">&nbsp;</div>
      </div>
    <?php
    return ob_get_clean();
  }  
  
  //
  // Take data from $_POST submit and convert in to serialized array as string
  //
  function _serialize_widgets($submitted_widget_data){
    // OK, we're authenticated:
    // we need to find and save the data
    $all_widgets = $this->ww->get_all_widgets(array('publish', 'draft'));
    $active_widgets = array();

    if ( ! empty( $submitted_widget_data ) ) {
      foreach ( $submitted_widget_data as $key => $details ) {
        // get rid of any hashes
        if ( isset( $all_widgets[ $details['id'] ] ) && isset( $details['weight'] ) && isset( $details['sidebar'] ) ) {
          // if something was submitted without a weight, make it neutral
          if ( $details['weight'] < 1 ) {
            $details['weight'] = $key;
          }

          $active_widgets[ $details['sidebar'] ][] = array(
            'id'     => $details['id'],
            'weight' => $details['weight'],
          );
        }
      }
    }

    return serialize($active_widgets);
  }
  
  /* -------------------------------------------- Sortable Widgets --------------------------*/
  
  /*
   * Provide Widget Wrangler selection when editing a page
   */
  function _sortable_widgets_meta_box($post = NULL){
	$sortable = new WW_Admin_Sortable();
	print $sortable->box_wrapper( $this->ww->page_widgets );
  }
    
  //
  function ww_form_meta(){
    // add the post_id hidden input when editing an enabled post
    // for ajax handling
    if ($this->editing_post_id)
    { ?>
      <input value="<?php print $this->editing_post_id; ?>" type="hidden" id="ww_ajax_context_id" /> 
      <?php
    }
  }

  /*
   * Hook into saving a page
   * Save the post meta for this post
   */
  function _save_post_widgets($post_id)
  {
    // skip quick edit
    if (isset($_REQUEST['_inline_edit'])) { return; }
    
    // don't know what is being saved if not a post_type, so we do nothing
    if (!isset($_POST['post_type'])){
      return $post_id;
    }
    
    // Ensure this is an enabled post_type and user can edit it
    $settings = $this->ww->settings;  
    if (!in_array($_POST['post_type'], $settings['post_types']) || !current_user_can('edit_post', $post_id)){
      return $post_id;
    }
    
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( ! isset( $_POST['ww-sortable-list-box'] ) || ! wp_verify_nonce( $_POST['ww-sortable-list-box'], 'widget-wrangler-sortable-list-box-save' ) ) {
      return $post_id;
    }
    
    // If this is an auto save routine, our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
      return $post_id;
    }

    // OK, we're authenticated:
    // we need to find and save the data
    $widgets = $this->_serialize_widgets($_POST['ww-data']['widgets']);

    // allow other plugins to modify the widgets or save other things
    $widgets = apply_filters('widget_wrangler_save_widgets_alter', $widgets);
    
    $new_preset_id = $this->ww->presets->new_preset_id;
    
    if ($widgets){
      update_post_meta( $post_id, 'ww_post_widgets', $widgets);
    }
    
    if ($new_preset_id !== FALSE){
      update_post_meta( $post_id, 'ww_post_preset_id', (int) $new_preset_id);
    }
  }

  /* ==================================== HELPER FUNCTIONS ================================ */
  
  //
  function _is_editing_enabled_post_type(){
    if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') && isset($_REQUEST['post'])) {
      $current_post_type = get_post_type($_REQUEST['post']);
        
      if (in_array($current_post_type, $this->ww->settings['post_types'])){
        $this->editing_post_id = $_REQUEST['post'];
        return TRUE;
      }
    }
    return FALSE;
  }

  
  //
  // Helper function for making sidebar slugs
  //
  function _make_slug($string){
    return stripcslashes(preg_replace('/[\s_\'\"]/','_', strtolower(strip_tags($string))));
  }
  
  // usort callback by 'weight' property or key
  function _sort_by_weight($a, $b){
    $a = (object) $a;
    $b = (object) $b;
    if ($a->weight == $b->weight) return 0;
    return ($a->weight > $b->weight) ? 1 : -1;
  }

  //
  // recursive array search
  // 
  function _array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
  {
    if( !is_array($haystack) ) {
      return false;
    }
    foreach( $haystack as $key => $val ) {
      if( is_array($val) && $subPath = $this->_array_searchRecursive($needle, $val, $strict, $path) ) {
          $path = array_merge($path, array($key), $subPath);
          return $path;
      } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
          $path[] = $key;
          return $path;
      }
    }
    return false;
  }
  
  //
  // Javascript drag and drop for sorting
  //
  function _sortable_widgets_js(){
    wp_enqueue_script('ww-sortable-widgets',
                    WW_PLUGIN_URL.'admin/js/sortable-widgets.js',
                    array('jquery-ui-core', 'jquery-ui-sortable', 'wp-util'),
                    WW_SCRIPT_VERSION,
                    true);
    $data = $this->_json_data();
    wp_localize_script( 'ww-sortable-widgets', 'WidgetWrangler', array('l10n_print_after' => 'WidgetWrangler = '.$data.';') );	
  }
  
  //
  // Javascript for editing a widget
  // 
  function _editing_widget_js(){
    wp_enqueue_script('ww-editing-widget',
                    WW_PLUGIN_URL.'admin/js/editing-widget.js',
                    array('jquery'),
                    WW_SCRIPT_VERSION,
                    true);
  }
  
  //
  // Add css to admin interface
  //
  function _admin_css(){
    print '<link rel="stylesheet" type="text/css" href="'.WW_PLUGIN_URL.'/admin/css/admin.css" />';
  }

  //
  // json data for ww admin
  //
  function _json_data() {
    $WidgetWrangler = array();
    $WidgetWrangler['data'] = array(
      'ajaxURL' => admin_url( 'admin-ajax.php' ),
      'allWidgets' => $this->ww->get_all_widgets(),
    );
    return json_encode($WidgetWrangler);
  }
  
  // cleanup previously serialized widgets
  function _cleanup_serialized_widgets($existing_widgets){
    // problem with over serialized options
    $existing_widgets = maybe_unserialize($existing_widgets);
    $existing_widgets = maybe_unserialize($existing_widgets);
    
    if (isset($existing_widgets['disabled'])){
      unset($existing_widgets['disabled']);
    }
    foreach ($existing_widgets as $corral_slug => $corral_widgets){
      foreach ($corral_widgets as $i => $widget){
        if (isset($widget['name'])){
          unset($existing_widgets[$corral_slug][$i]['name']);
        }
      }
    }
    return $existing_widgets;
  }
}

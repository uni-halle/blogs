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

/*
 *
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
    
    //include_once WW_PLUGIN_DIR.'/includes/EDD_SL_Plugin_Updater.php';
    
    global $widget_wrangler;
    $this->ww = $widget_wrangler;
    
    // get all our admin addons
    $this->_gather_addons();
    
    // initialize the wp hooks for this and addons
    $this->add_hooks();
  }
  
  //
  function _gather_addons(){
    global $widget_wrangler;
    
    // get all addons
    $addons = apply_filters( 'Widget_Wrangler_Admin_Addons', $this->addons );
    
    // give access to the ww object
    foreach ($addons as $addon_name => $addon){
      $addon->ww = $widget_wrangler;
    }
    
    $this->addons = $addons;
  }
    
  //
  // add common wp hooks for addons if they implement them with certain method names
  //  - wp_{hook_name}
  // 
  function add_hooks(){
    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );
    
    // this is the best hook i could find that ensures we're editing a post
    // and the global $post object is available
    add_action( 'add_meta_boxes', array( $this->ww, 'find_all_page_widgets' ), -99 );
    
    // auto-add common wp hooks
    foreach ($this->addons as $addon){
      // wp hook name => addon method name
      $auto_hooks = array(
        'init' => 'wp_init',
        'admin_init' => 'wp_admin_init',
        'admin_menu' => 'wp_admin_menu',
      );
      
      foreach ($auto_hooks as $wp_hook => $addon_method){
        if (method_exists( $addon, $addon_method ) ) {
          add_action( $wp_hook, array( $addon, $addon_method ) );
        }
      }
    }  
  }
  
  // WordPress hook 'admin_init'
  function wp_admin_init(){
    //$this->init_updater();
    
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
  function init_updater(){
    // setup the updater
    $ww_edd_updater = new EDD_SL_Plugin_Updater( WW_PRO_URL, __FILE__, array( 
        'version' 	=> WW_VERSION,
        'license' 	=> trim( get_option( 'ww_pro_license_key' ) ), 
        'item_name' => WW_PRO_NAME, 
        'author' 	=> 'Jonathan Daggerhart'
      )
    );
    
    $folder = basename( WW_PLUGIN_DIR );
    $file = basename( WW_PLUGIN_FILE );
    $update_message_hook = "in_plugin_update_message-{$folder}/{$file}";
    add_action( $update_message_hook, array( $this, '_update_message' ), 10, 2 );
  }
  
  //
  function _update_message(){
    // show an html change log, or something
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
    
    $i = 1;
    // loop through all widgets looking for those submitted
    foreach($all_widgets as $key => $widget){
      if ( isset( $submitted_widget_data[ $widget->ID ] ) ) {
        $weight = $submitted_widget_data[$widget->ID]["weight"];

        $sidebar_slug = $submitted_widget_data[$widget->ID]["sidebar"];

        // if something was submitted without a weight, make it neutral
        if ($weight < 1) {
          $weight = $i;
        }

        // add it to the active widgets list
        if ($sidebar_slug && ( $sidebar_slug != 'disabled' )) {
          $active_widgets[$sidebar_slug][] = array(
            'id'     => $widget->ID,
            'weight' => $weight,
          );
        }
      }
      $i++;
    }
    
    // what we have
    return serialize($active_widgets);
  }
  
  /* -------------------------------------------- Sortable Widgets --------------------------*/
  
  /*
   * Provide Widget Wrangler selection when editing a page
   */
  function _sortable_widgets_meta_box($post = NULL){
    $active_widgets = (!empty($this->ww->page_widgets)) ? maybe_unserialize($this->ww->page_widgets) : array();
    print $this->theme_sortable_widgets($active_widgets);
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
  
  //
  function theme_sortable_widgets($active_widgets){
    // meta_box interior
    ?>
      <div id="widget-wrangler-form-meta">
        <?php do_action('widget_wrangler_form_meta'); ?>
        <input value='true' type='hidden' name='widget-wrangler-edit' />
        <input type='hidden' name='ww_noncename' id='ww_noncename' value='<?php print wp_create_nonce( plugin_basename(__FILE__) ) ; ?>' />
      </div>
      
      <div id="widget-wrangler-form-wrapper">
        <div id='widget-wrangler-form' class='new-admin-panel'>
          <div class='outer'>
            
            <div id="widget_wrangler_form_top">
              <?php do_action('widget_wrangler_form_top'); ?>
            </div>
            
            <div id='ww-post-edit-message'>* <?php _e("Widget changes will not be updated until you save.", 'widgetwrangler'); ?>"</div>
            
            <?php print $this->theme_sortable_sidebars($active_widgets); ?>
            
            <div id="widget_wrangler_form_bottom">
              <?php do_action('widget_wrangler_form_bottom'); ?>
            </div>
            
          </div>
        </div>
      </div>
    <?php
  }

  /*
   * Theme the output for editing widgets on a page
   */
  function theme_sortable_sidebars($active_widgets)
  {
    $sorted_widgets = $this->get_sortable_widgets($active_widgets);
    ob_start();
    
    // loop through sidebars and add active widgets to list
    foreach($this->ww->corrals as $corral_slug => $corral_name)
    {
      $no_widgets_style = '';
      // open the list
      ?>
      <div id="ww-corral-<?php print $corral_slug; ?>-wrapper" class="ww-sortable-corral-wrapper">
        <h4 class="ww-sortable-widgets-corral-title"><?php print $corral_name; ?></h4>
        <ul name='<?php print $corral_slug; ?>' id='ww-corral-<?php print $corral_slug; ?>-items' class='inner ww-sortable' width='100%'>
          <?php
            if (isset($sorted_widgets['active'][$corral_slug]) &&
                is_array($sorted_widgets['active'][$corral_slug]))
            {
              // hide the 'no widgets' list item if widgets exist
              $no_widgets_style = "style='display: none;'";
              
              print implode('', $sorted_widgets['active'][$corral_slug]);
            }
          ?>
          <li class='ww-no-widgets' <?php print $no_widgets_style; ?>><?php _e("No Widgets in this corral.", 'widgetwrangler'); ?></li>
        </ul>
      </div>
      <?php
    }
  
    // disabled list
    ?>
    <div id="ww-corral-disabled-wrapper" class="ww-sortable-corral-wrapper">
      <h4 class="ww-sortable-widgets-corral-title">Disabled</h4>
      <ul name='disabled' id='ww-disabled-items' class='inner ww-sortable' width='100%'>
        <?php
          $no_widgets_style = '';
          // loop through and add disabled widgets to list
          if (!empty($sorted_widgets['disabled'])){
            // hide the 'no widgets' list item if widgets exist
            $no_widgets_style = "style='display: none;'";
            
            print implode('', $sorted_widgets['disabled']);
          }
        ?>
        <li class='ww-no-widgets' <?php print $no_widgets_style; ?>><?php _e("No disabled Widgets", 'widgetwrangler'); ?></li>
      </ul>
    </div>
    <?php
  
    return ob_get_clean();
  }
  
  /*
   * Put all widgets into an array separating active widgets from disabled widgets
   *  with active widgets in a sidebar
   *
   * like so,
   * $sorted_widgets['active'][$sidebar_slug][$weight] = $this->theme_single_sortable_widget($widget, $sidebar_slug, $weight);
   */
  function get_sortable_widgets($active_widgets)
  {
    $all_widgets = $this->ww->get_all_widgets(array('publish', 'draft'));
    $sidebars = $this->ww->corrals;
    $sorted_widgets = array('active' => array(), 'disabled' => array());
  
    // loop through $all_widgets, so we can know which widgets are disabled
    $i = 0;
    foreach($all_widgets as $widget){
      // hide widget from wrangler if applicable
      if (!empty($widget->hide_from_wrangler)){
        continue;
      }
      
      // fix widgets with no title
      if ($widget->post_title == ""){
        $widget->post_title = sprintf( __('(no title) - Slug: %1$s - ID: %2$s', 'widgetwrangler'), $widget->post_name, $widget->ID );
      }
      
      $keys = $this->_array_searchRecursive($widget->ID, $active_widgets);
      
      // setup initial info
      $corral_slug = $keys[0];
      $active_widget_index = (isset($keys[1])) ? $keys[1] : NULL;
      
      // get sidebar_slug for this widget, default to disabled
      if ($corral_slug == '' || (!array_key_exists($corral_slug, $sidebars))){
        $corral_slug = "disabled";
      }
      
      // get weight, default to $i
      if (($corral_slug != 'disabled') &&
          !is_null($active_widget_index) &&
          isset($active_widgets[$corral_slug][$active_widget_index]['weight']))
      {
        $weight = $active_widgets[$corral_slug][$active_widget_index]['weight'];
      }
      else {
        $weight = $i;
      }
      
      // place into sorted array
      if ($corral_slug == 'disabled'){
        $sorted_widgets['disabled'][] = $this->theme_single_sortable_widget($widget, $corral_slug, $weight);
      }
      else{
        $sorted_widgets['active'][$corral_slug][$weight] = $this->theme_single_sortable_widget($widget, $corral_slug, $weight);
      }
  
      $i++;
    }
    
    // sort each sidebar's widgets
    foreach($sorted_widgets['active'] as $corral_slug => $unsorted_widgets){
      if ($sorted_widgets['active'][$corral_slug]){
        ksort($sorted_widgets['active'][$corral_slug]);
      }
    }
    
    return $sorted_widgets;
  }
  
  /*
   * Single wortable widget form
   */
  function theme_single_sortable_widget($widget, $corral_slug, $weight)
  {        
    ob_start();
    ?>
      <li class='ww-item <?php print $corral_slug; ?> nojs' width='100%'>
        <input  name='ww-data[widgets][<?php print $widget->ID; ?>][weight]' type='text' class='ww-widget-weight'  size='2' value='<?php print $weight; ?>' />
        <input  name='ww-data[widgets][<?php print $widget->ID; ?>][id]' type='hidden' class='ww-widget-id' value='<?php print $widget->ID; ?>' />
        <select name='ww-data[widgets][<?php print $widget->ID; ?>][sidebar]'>
          <option value='disabled'>Disabled</option>
          <?php
          foreach($this->ww->corrals as $this_corral_slug => $corral_name){
            $selected = ($this_corral_slug == $corral_slug) ? "selected='selected'" : '';
            ?>
            <option name='<?php print $this_corral_slug; ?>' value='<?php print $this_corral_slug; ?>' <?php print $selected; ?>><?php print $corral_name; ?></option>
            <?php
          }
          ?>
        </select>
        <?php print $widget->post_title; ?> <?php print (($widget->post_status == 'draft') ? '- <em>('.__('draft', 'widgetwrangler').')</em>': ''); ?> <?php print (($widget->display_logic_enabled) ? '- <em>('.__('display logic', 'widgetwrangler').')</em>': ''); ?>
      </li>
    <?php
    return ob_get_clean();
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
    if ( isset($_POST['ww_noncename']) && !wp_verify_nonce( $_POST['ww_noncename'], plugin_basename(__FILE__) )) {
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
                    array('jquery-ui-core', 'jquery-ui-sortable'),
                    false,
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
                    false,
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

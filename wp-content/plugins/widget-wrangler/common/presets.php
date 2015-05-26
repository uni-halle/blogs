<?php

/*
 * Presets are a mechanism for using pre-configured groups of widgets throughout
 *  a WordPress site.
 *
 * Preset varieties are different types of presets.
 *
 * New WordPress filters
 *  - widget_wrangler_preset_varieties
 */
class WW_Presets {
  public $preset_varieties = array();
  public $current_preset_id = 0;
  
  //
  function __construct(){
    // wp hooks
    add_action( 'init', array( $this, 'wp_init' ) );
    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );
              
    // ww hooks
    // add default preset_varieties to the filter
    add_filter('widget_wrangler_preset_varieties', array( $this, 'ww_default_preset_varieties' ));
    // early, so it will prevent the singular filter
    // presets override the ww_posts_widgets
    add_filter( 'widget_wrangler_find_all_page_widgets', array( $this, 'ww_find_standard_preset_widgets' ), 0 );
    // last, to ensure that nothing else was found
    add_filter( 'widget_wrangler_find_all_page_widgets', array( $this, 'ww_find_core_preset_widgets' ), 999 );
  }

  /*
   * WordPress hook - init
   *  Gather and process the preset varieties
   */
  function wp_init(){
    // gather and process preset_varieties
    $preset_varieties = apply_filters('widget_wrangler_preset_varieties', $this->preset_varieties);
    
    foreach($preset_varieties as $slug => $preset_variety){
      // set filter's type as a value if not provided by filter
      if(!isset($preset_variety['slug'])){
        $preset_varieties[$slug]['slug'] = $slug;
      }
      // maintain the hook's key
      $preset_varieties[$slug]['hook_key'] = $slug;
    }
    $this->preset_varieties = $preset_varieties;
  }
  
  /*
   * WordPress hook admin_init
   *  Ensure default presets are installed
   */
  function wp_admin_init(){
    // make sure our core presets are installed
    if (!$this->get_core_preset('default')){
      $this->ww->_handle_extras_table();
      $this->_install_core_presets();
    }
  }

  /*
   * All Widget Preset types provided by default
   *
   * @param (array) - existing preset varieties
   * 
   * @return (array) default preset varieties
   */  
  function ww_default_preset_varieties($preset_varieties){
    $preset_varieties['core'] = array(
      'title' => 'Core',
      'description' => 'Widget Wrangler provided presets',
    );
    $preset_varieties['standard'] = array(
      'title' => 'Standard',
      'description' => 'Custom arbitrary widget groupings',
    );
    return $preset_varieties;
  }
  
  /*
   * Handle determining the widgets for a single post using a preset
   *
   * @param (mixed) - array if widgets already found, null if not
   *
   * @return (mixed) - array if widgets found, null if not
   */
  function ww_find_standard_preset_widgets($widgets){
    if (is_null($widgets) && (is_singular() || is_admin())) {
      global $post;
      if (isset($post) && $post_preset_id = get_post_meta($post->ID, 'ww_post_preset_id', TRUE)){
        if ($post_preset = $this->get_preset($post_preset_id)){
          $this->current_preset_id = $post_preset_id;
          $widgets = $post_preset->widgets;
        }
      }
    }
    
    return $widgets;
  }
  
  /*
   * Handle determining the widgets for non-post routes using a preset
   *
   * @param (mixed) - array if widgets already found, null if not
   *
   * @return (mixed) - array if widgets found, null if not
   */
  function ww_find_core_preset_widgets($widgets){
    // only take over with core widgets if no other widgets have been found
    if (is_null($widgets)){
      $found_widgets = FALSE;
      
      if(is_home() && $preset = $this->get_core_preset('postspage')){
        $found_widgets = $preset->widgets;
      }
      
      else if ($preset = $this->get_core_preset('default')) {
        $found_widgets = $preset->widgets;
      }
      
      if ($found_widgets){
        $widgets = $found_widgets;
        $this->current_preset_id = $preset->id;
      }
    }
    return $widgets;
  }
  
  /*
   * Get all Presets
   *
   * @return array All widget presets found in the DB
   */
  function get_all_presets(){
    $where = array(
      'type' => 'preset',
    );
    return $this->ww->_extras_get($where, 'all');
  }
    
  /*
   * Get a single Preset
   *
   * @param int $preset_id the id for the Preset
   * @return array the Preset details
   */
  function get_preset($preset_id){
    $where = array(
      'id' => $preset_id,
      'type' => 'preset',
    );
    
    return $this->ww->_extras_get($where);
  }  
  
  /*
   * Get a preset of the 'core' variety using it's key
   *
   * @param (string) - key for the core preset
   *
   * @return (mixed) - as Widget_Wrangler::_extras_get
   */
  function get_core_preset($preset_key){
    $where = array(
      'type' => 'preset',
      'variety' => 'core',
      'extra_key' => $preset_key,
    );
    
    return $this->ww->_extras_get($where);
  }  
  
  /*
   * Look for and insert legacy default and postpage widgets as presets
   */
  function _install_core_presets(){
    $data = array(
      'type' => 'preset',
      'variety' => 'core',
      'extra_key' => '',
      'data' => '',
      'widgets' => serialize(array()),
    );
    
    $where = array(
      'type' => 'preset',
      'variety' => 'core',
      'extra_key' => 'default',
    );
    
    // default widgets
    if (!$row = $this->ww->_extras_get($where)) {
      $data['extra_key'] = $where['extra_key'];
      $data['data'] = serialize(array('name' => 'Default'));
      
      $existing_widgets = $existing_widgets = $this->ww->admin->_cleanup_serialized_widgets(get_option('ww_default_widgets', array()));
      $data['widgets'] = serialize($existing_widgets);
      
      $this->ww->_extras_insert($data);
    }
  
    // postspage widgets
    $where['extra_key'] = 'postspage';
    if (!$row = $this->ww->_extras_get($where)) {
      $data['extra_key'] = $where['extra_key'];
      $data['data'] = serialize(array('name' => 'Posts Page'));

      $existing_widgets = $this->ww->admin->_cleanup_serialized_widgets(get_option('ww_postspage_widgets', array()));
      $data['widgets'] = serialize($existing_widgets);
      
      $this->ww->_extras_insert($data);
    }
  }
}

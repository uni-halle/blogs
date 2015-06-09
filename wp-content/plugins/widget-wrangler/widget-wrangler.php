<?php
/*
Plugin Name: Widget Wrangler
Plugin URI: http://www.wranglerplugins.com
Description: Widget Wrangler gives the WordPress admin a clean interface for managing widgets on a page by page basis. It also provides widgets as a post type, the ability to clone existing WordPress widgets, and granular control over widget templates.
Author: Jonathan Daggerhart
Version: 2.2.2
Author URI: http://daggerhart.com
Text Domain: widgetwrangler
Domain Path: /languages
License: GPL2
*/
/*  Copyright 2010  Jonathan Daggerhart  (email : jonathan@daggerhart.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// versioning for now
define('WW_VERSION', 2);
define('WW_SCRIPT_VERSION', '2.2.2');
define('WW_PRO_NAME', 'Widget Wrangler Pro' );
define('WW_PRO_URL', 'http://wranglerplugins.com' ); 

define('WW_PLUGIN_FILE', __FILE__);
define('WW_PLUGIN_DIR', dirname(WW_PLUGIN_FILE));
define('WW_PLUGIN_URL', plugin_dir_url(WW_PLUGIN_FILE));

// put our table details somewhere useful
global $wpdb;
$wpdb->ww_extras_table = $wpdb->prefix."ww_extras";

// leave this in the global space so anything can use it
$widget_wrangler = new Widget_Wrangler();

/*
 * The Widget_Wrangler object is the master of all things widget wrangler.
 *   It handles database interactions, loading addons, and execution of output.
 *   Does NOT build the output.  See Widget_Wrangler_Display (common/display.php)
 *   
 * New WordPress filters included
 *  - Widget_Wrangler_Addons
 *  - widget_wrangler_find_all_page_widgets
 *  - widget-wrangler-set-page-context
 */
class Widget_Wrangler {
  // addons
  var $addons = array();

  // global storage for found page widgets
  // defaults to NULL so that we know if widgets were found
  public $page_widgets = NULL;
    
  // context for current page being viewed on front end
  var $page_context = NULL;

  // theme compatiblity is a global option
  var $theme_compat = 0;
  
  // all corrals
  var $corrals = array();
  
  // licensing
  var $license_status = FALSE;
  
  // altered sidebars
  var $altered_sidebars = array();
  
  // ww
  var $settings = array();
  var $default_settings = array(
        'exclude_from_search' => 1,
        'theme_compat' => 1,
        'capabilities' => 'simple',
        'advanced_capability' => '',
        'post_types' => array(
          'page' => 'page',
          'post' => 'post',
        ),
        'taxonomies' => array(),
        'override_elements' => array(
          'div', 'h2', 'h3', 'aside', 'strong', 'span', 
        ),
        'legacy_template_suggestions' => 0,
      );

  /*
   * Construct the widget wrangler object.
   *  - add dependencies
   *  - load settings
   *  - assign hooks
   */
  function __construct(){
    add_action('wp_loaded', array($this, 'wp_loaded'), 999);

    // core
    include_once WW_PLUGIN_DIR.'/common/template-wrangler.inc';
    include_once WW_PLUGIN_DIR.'/common/presets.php';
    include_once WW_PLUGIN_DIR.'/common/display.php';
    include_once WW_PLUGIN_DIR.'/common/wp-posttype-widget.php';
    
    // addons
    include_once WW_PLUGIN_DIR.'/common/taxonomies.php';
    
    // initialize core
    $this->_get_settings();
    $this->_get_corrals();
    $this->_check_license();
    
    // early wp hooks
    register_activation_hook(WW_PLUGIN_FILE, array( $this, 'register_activation_hook' ));
    add_action( 'widgets_init', array( $this, 'wp_widgets_init' ) );
    
    // let all plugins load before gathering addons
    add_action( 'plugins_loaded' , array( $this, 'wp_plugins_loaded' ) );
    
    // widget wrangler hooks
    add_action( 'wp', array( $this, 'find_all_page_widgets' ), 10000 );
    // singular page widget detection
    add_filter( 'widget_wrangler_find_all_page_widgets', array( $this, '_find_singular_page_widgets' ), 10 );
  }

  /*
   * WordPress hook widgets_init
   * 
   *  - Register the corral and widget WP_Widget(s) 
   */
  function wp_widgets_init(){
    include_once WW_PLUGIN_DIR.'/common/wp-widget-ww-corral.php';
    include_once WW_PLUGIN_DIR.'/common/wp-widget-ww-widget.php';
    register_widget( 'WidgetWrangler_Corral_Widget' );
    register_widget( 'WidgetWrangler_Widget_Widget' );
  }

  /*
   * WordPress hook plugins_loaded
   * 
   *  - load all Widget Wrangler Addons
   *  - load core WW display and preset functionality
   */
  function wp_plugins_loaded(){
    load_plugin_textdomain( 'widgetwrangler', FALSE, basename( WW_PLUGIN_DIR ) . '/languages/' );

    $this->_gather_addons();
    $this->display = new Widget_Wrangler_Display();
    $this->display->ww = $this;
    $this->presets = new WW_Presets();
    $this->presets->ww = $this;
    // init the post type
    $widget_post_type = new WW_Widget_PostType();
    
    // initialize admin stuff
    if (is_admin()){
      include_once WW_PLUGIN_DIR.'/admin/widget-wrangler-admin.php';
      $this->admin = new Widget_Wrangler_Admin();
      
      // make sure we're updated
      $this->register_activation_hook();
    }  
  }
  
  /*
   * WordPress wp_loaded hook
   *
   *  - Handle altered sidebar definitions
   */
  function wp_loaded(){
    if ( !is_admin()) {
      global $wp_registered_sidebars;
      $wp_registered_sidebars = $this->get_altered_sidebars();
    }
  }
    
  /*
   * Apply the filter so all addons can help find the appropriate page_widgets
   */
  function find_all_page_widgets(){
    $this->_set_page_context();
    // gather page widgets by allowing anything to look for them
    $this->page_widgets = apply_filters('widget_wrangler_find_all_page_widgets', $this->page_widgets);
  }

  /*
   * Alter WP Sidebars
   *
   * @param (bool) - Force sidebar alterations
   *
   * @return (array) - Array of sidebar definitions
   */
  function get_altered_sidebars($force_alter = false){
    global $wp_registered_sidebars;
    $ww_alter_sidebars = get_option('ww_alter_sidebars', array());
    $combined = array();
    
    // altered sidebars
    foreach ($wp_registered_sidebars as $slug => $sidebar){
      
      // use original
      if ($force_alter || isset($ww_alter_sidebars[$slug]['ww_alter'])){
        $combined[$slug] = $wp_registered_sidebars[$slug];
        if ( isset($ww_alter_sidebars[$slug]) && is_array($ww_alter_sidebars[$slug]) ){
          foreach ($ww_alter_sidebars[$slug] as $k => $v){
            if (isset($v)) {
              $combined[$slug][$k] = $v;
            }
          }
        }
      }
      else {
        $combined[$slug] = $wp_registered_sidebars[$slug];
      }
      $combined[$slug]['ww_created'] = FALSE;
    }
    
    $this->altered_sidebars = $combined;
    
    return $combined;
  }
  
  /*
   * Detect if the current page being viewed is wrangling own widgets
   *
   * @param (array) - $widgets found by the system
   *
   * @return (array) - widgets array
   */
  function _find_singular_page_widgets($widgets){
    // don't replace any widgets already found
    if (is_null($widgets) && (is_singular() || is_admin())) {
      global $post;
      // single page widgets wrangling on their own
      if (isset($post) && $widgets_string = get_post_meta($post->ID,'ww_post_widgets', TRUE)) {
        $widgets = unserialize( $widgets_string );
      }
    }
    return $widgets;
  }
    
  /*
   * Get WW Addons and apply common actions/filters for them
   */
  function _gather_addons(){
    // get all addons
    $addons = apply_filters( 'Widget_Wrangler_Addons', $this->addons );
    
    // give access to the ww object
    foreach ($addons as $addon_name => $addon){
      $addon->ww = $this;
    }
    
    $this->addons = $addons;
  }
  
  /*
   * Get ww_settings and merge defaults in where appropriate
   *
   * @return (array) - Widget Wrangler settings
   */
  function _get_settings(){
    $settings = get_option("ww_settings", array());
    if (empty($settings)){
      // set default settings
      $settings = $this->default_settings;
      update_option("ww_settings", $settings);
    }
    
    // can't trust some functions not to over-serialize
    $settings = maybe_unserialize($settings);
    
    // merge in the default settings where missing
    foreach ($this->default_settings as $k => $v){
      if (!isset($settings[$k])){
        $settings[$k] = $this->default_settings[$k];
      }
    }
    
    // issue with problematic settings save
    if (empty($settings['override_elements'])){
      $settings['override_elements'] = $this->default_settings['override_elements'];
    }
    
    $this->settings = $settings;
    return $this->settings;
  }
  
  /*
   * Get all corrals and store them in the ww object
   *
   * @return (array) - Widget Wrangler corrals
   */
  function _get_corrals(){
    $corrals = get_option('ww_sidebars', array());
    $this->corrals = maybe_unserialize($corrals);
    return $this->corrals;
  }
  
  /*
   * Returns all published widgets
   * 
   * @return array of all widget objects
   */
  function get_all_widgets($post_status = array('publish'))
  {
    if (!is_array($post_status)) { $post_status = array($post_status); }
    $status = implode("','", $post_status);
    
    global $wpdb;
    $query = "SELECT `ID` FROM ".$wpdb->prefix."posts
              WHERE
                `post_type` = 'widget' AND
                `post_status` IN ('$status')";
    $results = $wpdb->get_results($query);
    
    $widgets = array();
    $i=0;
    $total = count($results);
    while($i < $total){
      $widgets[$results[$i]->ID] = $this->get_single_widget($results[$i]->ID);
      $i++;
    }
    return $widgets;
  }
  
  /*
   * Retrieve and return a single widget by its ID
   * 
   * @return widget object
   */
  function get_single_widget($post_id, $widget_status = false)
  {
    if (empty($post_id)) { return false; }
    
    global $wpdb;
    
    $status = $widget_status ? "`post_status` = '".$widget_status."' AND" : "";
    
    $query = "SELECT
                `ID`,`post_name`,`post_title`,`post_content`,`post_status`
              FROM
                `".$wpdb->prefix."posts`
              WHERE
                `post_type` = 'widget' AND
                ".$status."
                `ID` = ".$post_id." LIMIT 1";
    if ($widget = $wpdb->get_row($query)) {
  
      // do this first so the following get_post_meta queries are pulled from cache
      $widget->widget_meta  = get_post_meta($widget->ID);
      
      $widget->adv_enabled  = get_post_meta($widget->ID,'ww-adv-enabled',TRUE);
      $widget->adv_template = get_post_meta($widget->ID,'ww-adv-template',TRUE);
      $widget->parse        = get_post_meta($widget->ID,'ww-parse', TRUE);
      $widget->wpautop      = get_post_meta($widget->ID,'ww-wpautop', TRUE);
      $widget->widget_type  = get_post_meta($widget->ID,'ww-widget-type', TRUE);
      if (empty($widget->widget_type)){
        $widget->widget_type = "standard";
      }
      
      // output related variables
      $widget->display_logic_enabled  = get_post_meta($widget->ID,'ww-display-logic-enabled',TRUE);
      $widget->display_logic  = get_post_meta($widget->ID,'ww-display-logic',TRUE);
      $widget->wp_widget_args = array('before_widget' => '', 'before_title' => '', 'after_title' => '', 'after_widget' => '');
      $widget->hide_title = get_post_meta($widget->ID,'ww-hide-title', TRUE);
      $widget->hide_from_wrangler = get_post_meta($widget->ID,'ww-hide-from-wrangler', TRUE);
      $widget->override_output_html = get_post_meta($widget->ID,'ww-override-output-html', TRUE);
      $widget->html = array(
        'wrapper_element' => get_post_meta($widget->ID,'ww-html-wrapper-element', TRUE),
        'wrapper_id'      => get_post_meta($widget->ID,'ww-html-wrapper-id', TRUE),
        'wrapper_classes' => get_post_meta($widget->ID,'ww-html-wrapper-classes', TRUE),
        'title_element'   => get_post_meta($widget->ID,'ww-html-title-element', TRUE),
        'title_classes'   => get_post_meta($widget->ID,'ww-html-title-classes', TRUE),
        'content_element' => get_post_meta($widget->ID,'ww-html-content-element', TRUE),
        'content_classes' => get_post_meta($widget->ID,'ww-html-content-classes', TRUE),
      );
      
      $widget->custom_template_suggestion = get_post_meta($widget->ID,'ww-custom-template-suggestion', TRUE);
      
      // clones
      $widget->clone_classname = get_post_meta($widget->ID,'ww-clone-classname', TRUE);
      $widget->clone_instance = get_post_meta($widget->ID,'ww-clone-instance', TRUE);
      
      $widget->in_preview = FALSE;
      
      return $widget;
    }
    return false;
  }

  /*
   * Simple check
   *
   * @return (bool) - Whether or not the license is valid
   */
  function _check_license() {    
    $status = get_option('ww_pro_license_status');
    $this->license_status = (isset($status->license) && $status->license === "valid") ? TRUE : FALSE;
    return $this->license_status;
  }
  
  /*
   * Get data from the ww_extras table
   *
   * @params $where (array)
   *          - key value pairs for extras database table
   *
   * @params $limit (int | string)
   *          - number of items to return
   *          - can be 'all' to set no limit
   *
   * @return (single object | array of objects | false)
   *          - if $limit === 1, return a single object
   *          - else, return an array of objects
   */
  function _extras_get($where, $limit = 1){
    global $wpdb;
    
    // where `type` = '%s' AND `variety` => '%s' AND `key` = '%s'
    $where_string = '';
    $limit_string = '';
    
    $i = 1;
    foreach ($where as $k => $v){
      $where_string.= "`$k` = '%s'";
      if ($i < count($where)){
        $where_string.= " AND ";
      }
      $i++;
    }
    
    if ($limit != 'all'){
      $limit_string = "LIMIT ".$limit;
    }
    
    $sql = "SELECT * FROM ".$wpdb->ww_extras_table." WHERE $where_string ORDER BY `id` ASC $limit_string";
    
    if ($extras = $wpdb->get_results($wpdb->prepare($sql, array_values($where)))){
      
      // unserialize
      foreach($extras as $i => $extra){
        $extras[$i]->data = unserialize($extra->data);
        $extras[$i]->widgets = unserialize($extra->widgets);
      }
      
      if ((int) $limit === 1){
        // return single row of data as object
        return array_pop($extras);
      }
      // return all data as array of objects
      return $extras;
    }
    // failure
    return false;
  }
  
  /*
   * Wrapper for wpdb->insert
   *  - ensure data is serialized
   *
   * @param (array) - ww_extra data that needs to be inserted into the db.
   *
   * @return (mixed) - as $wpdb->insert.  false on failure
   */ 
  function _extras_insert($data){
    global $wpdb;
    
    // make sure this is not an object
    $data = (array) $data;
    
    // handle data types
    if (isset($data['data']) && is_array($data['data'])){
      $data['data'] = serialize($data['data']);
    }
    if (isset($data['widgets']) && is_array($data['widgets'])){
      $data['widgets'] = serialize($data['widgets']);
    }
    
    return $wpdb->insert( $wpdb->ww_extras_table , $data );
  }
  
  /*
   * Wrapper for wpdb->update
   *  - ensure data is serialized
   *
   * @param (array) - $data according to table structure
   * @param (array) - $where according to table structure
   *
   * @return (mixed) - as $wpdb->update, number of rows if successful, false on failure
   */ 
  function _extras_update($data, $where){
    global $wpdb;
    
    // make sure this is not an object
    $data = (array) $data;
    
    // handle data types
    if (isset($data['data']) && is_array($data['data'])){
      $data['data'] = serialize($data['data']);
    }
    if (isset($data['widgets']) && is_array($data['widgets'])){
      $data['widgets'] = serialize($data['widgets']);
    }
    
    return $wpdb->update($wpdb->ww_extras_table, $data, $where);
  }
  
  /*
   * Wrapper for wpdb->delete
   *
   * @param (array) - $where according to table structure
   *
   * @return (mixed) - as $wpdb->delete, number of rows if successful, false on failure
   */ 
  function _extras_delete($where){
    global $wpdb;
    return $wpdb->delete($wpdb->ww_extras_table, $where);
  }
  
  /*
   *  Wrapper function to handle the dynamics of capability checking
   */ 
  function _current_user_can_edit($post_id){
    if ($post_type = get_post_type($post_id)){
      $post_types = get_post_types(array('public' => true, '_builtin' => true), 'objects', 'and');
      $post_types+= get_post_types(array('public' => true, '_builtin' => false), 'objects', 'and');
      if (isset($post_types[$post_type]) && isset($post_types[$post_type]->capability_type)){
        $this_capability_type = $post_types[$post_type]->capability_type;
        return current_user_can('edit_'.$this_capability_type, $post_id);
      }
    }
    return FALSE;
  }
    
  /*
   * Gather some often-needed data
   *   - Provide some useful information to help determine the current screen
   */ 
  function _set_page_context(){
    $context = false;
    
    if (is_singular()){
      global $post;
      if (isset($post->ID)){
        $context['id'] = $post->ID;
        $context['context'] = 'post';
        $context['object'] = $post;
      }
    }
    else if ((is_tax() || is_category() || is_tag()) &&
             $term = get_queried_object())
    {
      $context['id'] = $term->term_id;
      $context['context'] = 'term';
      $context['object'] = $term;
    }
    
    $context = apply_filters('widget-wrangler-set-page-context', $context);
    
    if ($context){
      $this->page_context = $context;
    }
  }
    
  /*
   * Helper function for sorting arrays of items
   */
  function _sort_by_weight($a, $b){
    $a = (object) $a;
    $b = (object) $b;
    if ($a->weight == $b->weight) return 0;
    return ($a->weight > $b->weight) ? 1 : -1;
  }
  
  /*
   * Activation
   * @TODO - refactor to "Check Upgrade / version"
   */
  function register_activation_hook(){
    // upgrade, if an old version exists
    if ($old_version = get_option('ww_version', FALSE)){
      if ((float) $old_version < WW_VERSION){
        
        // upgrade from 1x to 2x
        if ((float) $old_version < 2){
          $this->_upgrade_from_1x_to_2x();
        }
        
        $this->_upgrade_core();
      }
    }
    // otherwise, install
    else {
      $this->_install_core();
      add_option('ww_version', WW_VERSION);
    }
  }
  
  /*
   * First install
   */
  function _install_core(){
    if (!get_option('ww_settings', false)){
      add_option('ww_settings', $this->default_settings);
    }
  }
  
  /*
   * Upgrade very-core stuff
   */ 
  function _upgrade_core(){
    // upgrade
    update_option('ww_version', WW_VERSION);
    // refresh settings
    $this->_get_settings();
  }
  
  /*
   * Big upgrade with a lot of 1-time changes
   */
  function _upgrade_from_1x_to_2x(){
    // check to make sure array options aren't over serialized
    $options = array('ww_default_widgets', 'ww_postspage_widgets', 'ww_settings', 'ww_sidebars');
    foreach ($options as $option){
      if ($v = get_option($option)){
        $v = maybe_unserialize($v);
        update_option($option, $v);
      }
    }
    
    $settings = get_option('ww_settings', array());
          
    // help with over serialization in previous versions
    $settings = maybe_unserialize($settings);
    
    // add new default settings
    foreach ($this->default_settings as $key => $value ){
      if ($key != "theme_compat" && !isset($settings[$key])){
        $settings[$key] = $value;
      }
    }
    
    if (isset($settings['advanced'])){
      $settings['advanced_capability'] = $settings['advanced'];
      unset($settings['advanced']);
    }
    
    // enable legacy template suggestions
    $settings['legacy_template_suggestions'] = 1;
    update_option('ww_settings', $settings);
    
    // save the previous main version number for later use
    if (!get_option('ww_previous_main_version', '')){
      add_option('ww_previous_main_version', 1, '', 'no');
    } else {
      update_option('ww_previous_main_version', 1);
    }    
  }
  
  /*
   * Create widget presets table
   */ 
  function _handle_extras_table(){
    global $wpdb;
    $sql = "CREATE TABLE " . $wpdb->ww_extras_table . " (
`id` mediumint(11) NOT NULL AUTO_INCREMENT,
`type` varchar(32) NOT NULL,
`variety` varchar(32) DEFAULT NULL,
`extra_key` varchar(32) DEFAULT NULL,
`data` text NOT NULL,
`widgets` text NOT NULL,
UNIQUE KEY id (id)
);";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $t = dbDelta($sql);
  }
  
  /*
   * Simple debugging function
   *
   * @param (mixed) - $data to output
   * @param (bool) - $return the output
   *
   * @return (void|string) - depending on $return parameter
   */ 
  function __d($v, $return = false){
    $d ='<pre>'.htmlentities(print_r($v,1)).'</pre>';
    if (!$return) {
      print $d;
    } else {
      return $d;
    }
  }  
}

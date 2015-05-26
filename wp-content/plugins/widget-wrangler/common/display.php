<?php

/*
 * The Widget_Wrangler_Display object handles output of
 *  - corrals
 *  - widgets
 *  - shortcodes
 *
 * New Wordpress filters included 
 *  - widget-wrangler-display-widget-output-alter
 *  - widget-wrangler-display-corral-output-alter
 */
class Widget_Wrangler_Display {
  
  var $theme_compat = 0;
  
  // keep up with what corral is being executed
  var $doing_corral = false;
  var $doing_corral_slug = '';
  var $doing_corral_wp_widget_args = array();
  
  // keep up with what wp_sidebar is being executed
  var $dynamic_sidebar_index = '';
  var $dynamic_sidebar_args = array();
  var $dynamic_sidebar_has_widgets = FALSE;
    
  /*
   * Constructor for Display object
   * 
   *  - load backwards compatibility functions
   *  - prepare display object for WordPress
   */
  function __construct(){
    include_once WW_PLUGIN_DIR.'/common/backwards-compat-functions.inc';
    $this->add_hooks();
  }
  
  /*
   * Assign WordPress hooks for display object
   */
  function add_hooks(){
    
    // shortcodes
    add_shortcode( 'ww_widget', array( $this, 'single_widget_shortcode' ) );
    add_shortcode( 'ww_corral', array( $this, 'single_corral_shortcode' ) );
    
    // template wrangler hook
    add_filter( 'tw_templates', array( $this, '_tw_templates' ) );
    add_filter( 'tw_pre_process_template', array( $this, '_tw_pre_process_template' ) );
     
    // wordpress sidebar hooks
    add_action( 'dynamic_sidebar_before', array( $this, 'dynamic_sidebar_before'), -10, 2 );
    add_action( 'dynamic_sidebar_after', array( $this, 'dynamic_sidebar_after'), 10, 2 );
    
    add_action( 'wp', array( $this, 'wp_loaded'));
  }
  
  /*
   * WordPress hook wp
   *  - need theme compatibility check to happen a little later
   */ 
  function wp_loaded(){
    $this->theme_compat = (isset($this->ww->settings['theme_compat']) && $this->ww->settings['theme_compat']) ? 1 : 0;
  }
  
  /*
   * Get wp sidebar details
   */ 
  function dynamic_sidebar_before($index, $has_widgets){
    $this->dynamic_sidebar_index = $index;
    $this->dynamic_sidebar_has_widgets = $has_widgets;
    
    global $wp_registered_sidebars;
    if (isset($wp_registered_sidebars[$index])) {
      $this->dynamic_sidebar_args = $wp_registered_sidebars[$index];
    }
  }
  
  // reset the sidebar details
  function dynamic_sidebar_after($index, $has_widgets){
    $this->dynamic_sidebar_index = '';
    $this->dynamic_sidebar_has_widgets = FALSE;
    $this->dynamic_sidebar_args = array();
  }
  
  /*
   * Template wrangler hook
   *  - add template suggestions for widgets
   */
  function _tw_templates($templates)
  {
    // template applied by files
    $templates['ww_widget'] = array(
      // string of single template suggestion
      // or array of multiple template suggestions
      'files' => array(
          // use argument keys as replacable patterns with the argument's value
          // order is important as the order here is the order inwhich templates are searched for
          // 2.x
          'widget--corral_[corral_slug]--[widget_id].php',
          'widget--corral_[corral_slug]--[post_name].php',
          'widget--[widget_id].php', 
          'widget--[post_name].php', 
          'widget--corral_[corral_slug]--type_[widget_type].php',
          'widget--type_[widget_type].php',
          'widget--corral_[corral_slug].php',
          'widget.php'
      ),
  
      // location of the default template if nothing is found
      'default_path' => WW_PLUGIN_DIR.'/templates',
  
      // optional arguments to be passed to the themeing function
      'arguments' => array(
          // must be key => value pairs
          'widget' => NULL,
          'widget_id' => 0,
          'corral_slug' => '',
          'widget_type' => '',
          'post_name' => '',
      ),
    );
  /*	
    $templates['ww_corral'] = array(
      'files' => array(
        'corral-[corral_id].php',
        'corral.php'
      ),
      'default_path' => dirname(__FILE__).'/templates',
      'arguments' => array(
          'corral_id' => 0,
      ),
    );
  */
  
    if ($this->ww->settings['legacy_template_suggestions']){
      // remove the default widget.php
      array_pop($templates['ww_widget']['files']);
      // add the legacy template suggestion patterns
      $templates['ww_widget']['files'][] = 'widget-[widget_type]-[widget_id].php';
      $templates['ww_widget']['files'][] = 'widget-[widget_id].php';
      $templates['ww_widget']['files'][] = 'widget-[post_name].php';
      $templates['ww_widget']['files'][] = 'widget-[widget_type].php';
      $templates['ww_widget']['files'][] = 'widget.php';
    }
    
    return $templates;
  }
  
  /*
   * Allow for custom template names by preprocessing template wrangler suggestions
   *
   * @param (array) - template wrangler single-template array
   */ 
  function _tw_pre_process_template($tw_template){
    if ($tw_template['name'] == "ww_widget" && !empty($tw_template['arguments']['widget']->custom_template_suggestion)){
      // make this suggestion the first
      array_unshift($tw_template['files'], 'widget-[custom_template_suggestion].php');
      $tw_template['arguments']['custom_template_suggestion'] = $tw_template['arguments']['widget']->custom_template_suggestion;
    }
    return $tw_template;
  }
  
  /*
   * Output a corral
   *
   * @param (string) - slug for the corral to output
   * @param (array) - wp_widget_args are the WordPress sidebar definitions for wrapping a widget
   *
   * @return (void) - Output is sent to the screen immediately
   */
  function dynamic_corral($corral_slug = 'default', $wp_widget_args = array('before_widget' => '', 'before_title' => '', 'after_title' => '', 'after_widget' => ''))
  {
    $corral_html = '';
    
    // only if page_widgets were found
    if (!is_null($corral_slug) &&
        $this->ww->page_widgets &&
        isset($this->ww->page_widgets[$corral_slug]))
    {
      $this->doing_corral = TRUE;
      $this->doing_corral_slug = $corral_slug;
      $this->doing_corral_wp_widget_args = $wp_widget_args;
      
      // ensure widgets are sorted correctly
      usort($this->ww->page_widgets[$corral_slug], array( $this->ww, '_sort_by_weight') );
      
      $i = 0;
      $total = count($this->ww->page_widgets[$corral_slug]);
      while($i < $total) {
        if($widget = $this->ww->get_single_widget($this->ww->page_widgets[$corral_slug][$i]['id'], 'publish'))
        {
          // include theme compatibility data
          $widget->wp_widget_args = $this->doing_corral_wp_widget_args;
          $widget->current_weight = $this->ww->page_widgets[$corral_slug][$i]['weight'];
          
          // Theme compatiblity
          if (!$widget->override_output_html &&
              $this->theme_compat)
          {
            $widget = $this->_replace_wp_widget_args($widget);
          }
          
          $widget_html = $this->theme_single_widget($widget);
          $widget_html = apply_filters('widget-wrangler-display-widget-output-alter', $widget_html, $widget, $corral_slug);
          $corral_html.= $widget_html;
          
        }
        $i++;
      }
      
      $corral_html = apply_filters('widget-wrangler-display-corral-output-alter', $corral_html, $corral_slug);
      
      $this->doing_corral = FALSE;
      $this->doing_corral_slug = '';
      $this->doing_corral_wp_widget_args = array();
    }
      
    print $corral_html;
  }
  
  /*
   * Replace the classes and IDs of a widget displayed within a sidebar
   *
   * @param (object) - Widget Wrangler widget object
   *
   * @return (object) - Same WW widget, but modified if necessary
   */
  function _replace_wp_widget_args($widget){
    // wrapper_classes
    $replace = !empty($widget->html['wrapper_classes']) ? $widget->html['wrapper_classes']: 'ww_widget-'.$widget->post_name.' ww_widget-'.$widget->ID;
    $widget->wp_widget_args['before_widget'] = str_replace('widget-wrangler-widget-classname', $replace, $widget->wp_widget_args['before_widget']);
    return $widget;
  }
  
  /*
   * Apply templating and parsing to a single widget
   *
   * @param (object) - Widget Wrangler widget object (standard class)
   * 
   * @return (string) - themed widget for output or templating
   */
  function theme_single_widget($widget){
    if (empty($widget)) { return ''; }
    
    if ($widget->display_logic_enabled){
      //$show = TRUE;
      ob_start();
        // expecting direct conditional statemnt
        if (stripos($widget->display_logic, 'return') === FALSE){
          $show = (eval('return '.$widget->display_logic.';'));
        }
        else {
          $show = eval($widget->display_logic);
        }
      // 
      $ob = ob_get_clean();
      if (!$show && !$widget->in_preview){
        return '';
      }
    }
    
    $default_html = array(
      'wrapper_element' => 'div',
      'wrapper_id'      => 'widget-'.$widget->ID,
      'wrapper_classes' => 'widget',
      'title_element'   => 'h3',
      'title_classes'   => '',
      'content_element' => 'div',
      'content_classes' => 'content',
    );
    
    //
    foreach ($default_html as $k => $v){
      // allow nulls to bypass so we can unset element tags
      if (empty($widget->html[$k]) && !is_null($widget->html[$k])){
        $widget->html[$k] = $default_html[$k];
      }
      
      // allow for _none_
      if ($widget->html[$k] == '_none_'){
        $widget->html[$k] = NULL;
      }
    }
    
    // clone
    if ($widget->widget_type == "clone"){
      $widget->clone_instance['post_name'] = $widget->post_name;
      $widget->clone_instance['ww_widget'] = $widget;
      $themed = $this->_the_widget($widget->clone_classname, $widget->clone_instance);
    }
    // standard
    else{
      // maybe they don't want auto p ?
      if ($widget->wpautop == "on"){
        $widget->post_content = wpautop($widget->post_content);
      }
    
      // apply shortcode
      $widget->post_content = do_shortcode($widget->post_content);
    
      // see if this should use advanced parsing
      if($widget->adv_enabled){
        $themed = $this->_adv_parse_widget($widget);
      } else {
        $themed = $this->template_widget($widget);
      }
    }
    
    return $themed;
  }
  
  /*
   * Look for possible custom templates, then default to widget.php
   *
   * @param (object) - Widget Wrangler widget object
   *
   * @return (string) - templated widget
   */
  function template_widget($widget)
  {

    // prepare template wrangler arguments
    $args = array(
      'widget' => $widget, // needed in final template
      'widget_id' => $widget->ID,
      'widget_type' => $widget->widget_type,
      'post_name' => $widget->post_name,
    );
    
    if ($this->doing_corral && !empty($this->doing_corral_slug)){
      $args['corral_slug'] = $this->doing_corral_slug;
    }
    
    $args += $widget->html;
    
    // preview bypasses the theme compatibility assignment
    //if (!isset($this->theme_compat)){
    //  $this->theme_compat = 0;
    //}
    
    $output = '';
    
    // if overriding the template html (Pro), go ahead and template the widget
    if ($widget->override_output_html){
      $output = theme('ww_widget', $args);
    }
    else {
      // if theme_compat is enabled, or widget is hiding the title
      // remove post title from templating
      if ($this->theme_compat || $widget->hide_title){
        $widget->hidden_title = $widget->post_title;
        $widget->post_title = NULL;
      }

      // apply templating
      $output = theme('ww_widget', $args);

      // handle final theme compat issues if the widget is in a corral,
      if ($this->doing_corral && $this->theme_compat)
      {
        $theme_compat =  $widget->wp_widget_args['before_widget'];
       
        // title can also be NULL with clones
        if ($widget->hidden_title && !$widget->hide_title) {
          $theme_compat.= $widget->wp_widget_args['before_title'] .
                            $widget->hidden_title .
                          $widget->wp_widget_args['after_title'];
        }
       
        $theme_compat.= $output . $widget->wp_widget_args['after_widget'];
        $output = $theme_compat;
        
        // give the post title back now that we're done
        $widget->post_title = $widget->hidden_title;
      }
    }

    return $output;  
  }
  
  /*
   * Handle the advanced parsing for a widget, including adv templating
   *
   * @param (object) - Widget Wrangler widget object
   * 
   * @return advanced parsed widget
   */
  function _adv_parse_widget($widget){
    // make $post and $page available
    global $post;
    $page = $post;
    
    // find and replace title and content tokens
    // this should happen after eval() to prevent code-like content from attempting to execute
    // use str_replace to avoid $<digits> issue with preg_replace
    // replace \$ with $ for backwards compat w/ users who have added their own backslashes
    $search = array('{{title}}','{{content}}', '\$');
    $replace = array($widget->post_title, $widget->post_content, '$');
    
    // handle advanced templating
    if($widget->adv_template)
    {
      $returned_array = eval('?>'.$widget->parse);
      
      if (is_array($returned_array)){
        // only change values if passed into returned array
        if (isset($returned_array['title'])) {
          // tokens
          $returned_array['title'] = str_replace($search, $replace, $returned_array['title']);
          $widget->post_title = $returned_array['title'];
        }
        if (isset($returned_array['content'])) {
          // tokens
          $returned_array['content'] = str_replace($search, $replace, $returned_array['content']);
          $widget->post_content = $returned_array['content'];
        }
        $output = $this->template_widget($widget);
      }
      else {
        $output = "<!-- Error:  This widget did not return an array. -->";
      }
    }
    else{
      // execute adv parsing area - no advanced templating
      ob_start();
        // add some context to all parsed areas
        eval('$instance["ww_widget"] = $widget; ?>'.$widget->parse);
      $output = ob_get_clean();
      
      // theme compatibility
      // adv parse w/o templating doesn't have separate title
      if (!$widget->override_output_html &&
          $this->theme_compat)
      {
        $output = $widget->wp_widget_args['before_widget'].$output.$widget->wp_widget_args['after_widget'];
      }
      // tokens
      $output = str_replace($search, $replace, $output);
      
      // fix for recent post widget not resetting the query
      $post = $page;
    }
        
    return $output;
  }
  
  /**
   * Handles output for cloned widgets
   * 
   * Taken from wp-includes/widgets.php, adjusted for my needs
   *
   * @param (string) -  $wp_widget_class the widget's PHP class name (see default-widgets.php)
   * @param (array) - $instance the widget's instance settings
   * @return (string) - themed widget
   **/
  function _the_widget($wp_widget_class, $instance = array())
  {
    // load widget from widget factory ?
    global $wp_widget_factory;
    $wp_widget = $wp_widget_factory->widgets[$wp_widget_class];

    // get as much ww widget data as possible 
    $ww_widget = (isset($instance['ww_widget'])) ? $instance['ww_widget'] : $this->ww->get_single_widget($instance['ID']);

    if (!isset($instance['hide_title'])){
      $instance['hide_title'] = 0;
    }

    if ( !is_a($wp_widget, 'WP_Widget') ){
      return '<!-- widget clone is not a WP_Widget -->';
    }

    $explode_target = '[eXpl0de--WW_ID-'.$instance['ID'].']';
    
    // args for spliting title from content
    $args = array('before_widget'=>'','after_widget'=>'','before_title'=>'','after_title'=> $explode_target);
  
    // output to variable for replacements
    ob_start();
      $wp_widget->widget($args, $instance);
    $temp = ob_get_clean();

    // get title and content separate
    $array = explode($explode_target, $temp);

    // prep object for template
    if (count($array) > 1) {
      // we have a title
      $ww_widget->post_title    = ($array[0]) ? $array[0]: $instance['title'];
      $ww_widget->post_content  = $array[1];
    }
    else {
      // no title
      $ww_widget->post_content = $array[0];
    }
  
    if (isset($instance['hide_title']) && $instance['hide_title']){
      $ww_widget->post_title = NULL;
    }
  
    $themed_widget = $this->template_widget($ww_widget);

    // template with WW template
    return $themed_widget;
  }
  
  /*
   * Single widget shortcode
   *
   * @param (array) - attributes passed into the shortcode
   *
   * @return (string) - output of templated widget
   */
  function single_widget_shortcode($atts){
    $args = shortcode_atts(array('id' => NULL, 'slug' => NULL), $atts);
    
    // allow slug
    if ($args['slug']){
      global $wpdb;
      if ($id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_name = '%s' AND post_status = 'publish' LIMIT 1", $args['slug']))){
        $args['id'] = $id;
      }
    }
    
    if ($widget = $this->ww->get_single_widget($args['id'], 'publish')){
      if (!empty($this->doing_corral_wp_widget_args)){
        $widget->wp_widget_args = $this->doing_corral_wp_widget_args;
      }
      return $this->theme_single_widget($widget);
    }
    return '';
  }
  
  /*
   * Single corral shortcode
   *
   * @param (array) - attributes passed into the shortcode
   *
   * @return (string) - output of corral with widgets
   */
  function single_corral_shortcode($atts){
    $args = shortcode_atts(array('slug' => NULL), $atts);
    
    // allow slug
    if ($args['slug']){
      ob_start();
        $this->dynamic_corral($args['slug']);
      return ob_get_clean();
    }
  }

  /*
   * Create a reusable map of corrals to sidebars
   *
   * @return (array) - map of which WW corrals exist in what WP sidebars
   */
  function corrals_to_wpsidebars_map(){
    global $wp_registered_sidebars;
    $wpsidebars_widgets = get_option( 'sidebars_widgets' );
    $widget_ww_sidebar = get_option('widget_widget-wrangler-sidebar');
    
    $corrals = array();
    
    foreach ($wp_registered_sidebars as $wpsidebar_id => $wpsidebar_details){
      $wpwidgets_in_sidebar = $wpsidebars_widgets[$wpsidebar_id];
      foreach ($wpwidgets_in_sidebar as $i => $wpwidget_id){
        $widget_key = str_replace('widget-wrangler-sidebar-', '', $wpwidget_id);
        if (isset($widget_ww_sidebar[$widget_key])){
          $widget_instance = $widget_ww_sidebar[$widget_key];
          $corrals[$widget_instance['sidebar']] = $wpsidebar_id;
        }
      }
    }
    
    return $corrals;  
  }  
}


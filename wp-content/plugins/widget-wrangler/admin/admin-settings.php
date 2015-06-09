<?php
// hook this addon in
add_filter( 'Widget_Wrangler_Admin_Addons', 'ww_settings_admin_addon' );

//
function ww_settings_admin_addon($addons){
  $addons['Settings'] = new WW_Settings_Admin();
  return $addons;
}

/*/
// example setting tab
add_filter('ww_settings_form_tabs', 'example_settings_tab');

function example_settings_tab($tabs){
  $tabs['settings'] = array(
    'title' => 'General',
    'description' => 'Setup how Widget Wrangler works with other WordPress content.',
    'form_action' => array( $this, '_settings_general_form' ),
    );
  return $tabs;
}

// example setting item
add_filter('ww_settings_form_items', 'example_settings_items');

function example_settings_items($settings){
  $settings['setting_key'] = array(
    // (required) - setting title
    'title' => 'Setting Title',
    // (required) - during form_action, field names should be prefixed with 'settings'
    'form_action' => array( $this, '_my_form_method' ),
    // (required) - handle execution
    'execute_action' => array( $this, '_my_submit_method' ),
    
    // * optional 
    // (optional) - defaults to 'settings'
    'tab' => 'settings/some_tab',
    // (optional)
    'description' => '',
    // (optional) - defaults to false
    'require_license' => false,
    // (optional) - something like 0 or array() when needed, defaults to empty string
    'empty_value' => '',
    // (optional) - these are keys of values you plan to save to the ww->settings array
    //   while techinically optional, if your form has more than 1 field, this will need
    //   to be setup completely
    //  - defaults to array($setting_key)
    'value_keys' => array(
      'setting_array_key_must_be_unique',
      'another_setting_key',
      ),
    // (optional) - which $_GET['ww_action'] to execute on - defaults to 'save'
    'execute_key' => 'save',
    
    // * created during processing
    // (processed) - rendered html of the form item
    'form' => ''
    // (processed) - value exists here if key exists in $_POST
    'submitted_values' => array(
      'another_setting_key' => '',
    ),
    // (processed) - settings' form values - value exists here as they exist in ww->settings array
    //    not related at all to ww->default_settings
    'form_values' => array(
      'another_setting_key' => '',
    ),
  );
  return $settings;
}

 
//*/


/*
 *
 */
class WW_Settings_Admin  {
  public $urlbase = 'edit.php?post_type=widget&page=';
  public $page_hook;
  public $settings_form_tabs = array();
  public $settings_form_items = array();
  public $current_settings_form_tab = array();
  
  // hook into the settings form items and tabs
  function __construct(){
    add_filter('ww_settings_form_items', array( $this, '_default_settings_form_items' ) );
    add_filter('ww_settings_form_tabs', array( $this, '_default_settings_form_tabs' ) );
    add_action( 'init', array( $this, 'wp_init' ) );
    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );
    add_action( 'admin_menu', array( $this, 'wp_admin_menu' ) );
  }

  /**
   * Implements action 'init'
   */
  function wp_init(){
    $this->_preprocess_settings_form_items();
    $this->_preprocess_settings_form_tabs();
  }

  /**
   * Implements action 'admin_init'
   */
  function wp_admin_init(){
    if ( isset($_GET['post_type']) && 'widget' == $_GET['post_type'] &&
         isset($_GET['page']) && strpos($_GET['page'], 'settings') !== FALSE )
    {
      $this->_process_settings_form_items();
      //$this->_process_settings_form_tabs();
    }
  }

  /**
   * Implements action 'admin_menu'
   */
  function wp_admin_menu(){
    $page_title = 'Settings';
    $this->page_hook = add_submenu_page($this->ww->admin->parent_slug, $page_title, $page_title, $this->ww->admin->capability, 'settings', array( $this, '_menu_router' ));
    
    foreach( $this->settings_form_tabs as $menu_slug => $tab){
      $title = $tab['title'].' '.$page_title;
      $this->tab_pages[$menu_slug]['page_hook'] = add_submenu_page(null, $title, $title, $this->ww->admin->capability, $menu_slug, array( $this, '_menu_router' ) );
      
      add_action( "admin_head", array( $this->ww->admin, '_admin_css' ) );
    }
  }
  
  /*
   * Handles settings pages
   *   - all settings pages submit to this execute_action
   */
  function _menu_router(){
    if (isset($_GET['ww_action'])){
      do_action('ww_settings_form_items_execute_'.$_GET['ww_action']);
      wp_redirect($_SERVER['HTTP_REFERER']);
      exit;
    }
    else if (isset($_GET['page']) && isset($this->settings_form_tabs[$_GET['page']])) {
      $this->current_settings_form_tab = $this->settings_form_tabs[$_GET['page']];
      do_action('ww_settings_form_tab_'.$this->current_settings_form_tab['safe_tab_key']);
    }
  }

  // -------------------------- Setting tabs and setting items ---------------------------
  
  //
  // Settings sub-pages
  //
  function _default_settings_form_tabs($tabs){
    $tabs['settings'] = array(
      'title' => __('General', 'widgetwrangler'),
      'description' => __('Setup how Widget Wrangler works with other WordPress content.', 'widgetwrangler'),
      'form_action' => array( $this, '_settings_general_form' ),
      );
    $tabs['settings/widget'] = array(
      'title' => __('Post Type', 'widgetwrangler'),
      'description' => __('Post type settings control the widget post_type registered by this plugin.', 'widgetwrangler'),
      'form_action' => array( $this, '_settings_general_form' ),
      );
    $tabs['settings/tools'] = array(
      'title' => __('Tools', 'widgetwrangler'),
      'description' => __('Actions that will modify Widget Wrangler data.', 'widgetwrangler'),
      'form_action' => array( $this, '_settings_tools_form' ),
      );
    $tabs['settings/license'] = array(
      'title' => __('Pro License', 'widgetwrangler'),
      'description' => __('Widget Wrangler Pro provides new features for site developers that can drastically increase efficiency in widget management for complex sites or needs.', 'widgetwrangler'),
      'form_action' => array( $this, '_settings_tools_form' ),
      );
    return $tabs;
  }
  
  //
  //
  //
  function _default_settings_form_items($settings){
    $settings['post_types'] = array(
      'title' => __('Post Types', 'widgetwrangler'),
      'description' => __('Select which post types can control widgets individually.', 'widgetwrangler'),
      'empty_value' => array(),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['taxonomies'] = array(
      'title' => __('Taxonomies', 'widgetwrangler'),
      'description' => __('Select which taxonomies can control widgets individually.', 'widgetwrangler'),
      'empty_value' => $this->ww->settings['taxonomies'],
      'require_license' => true,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['theme_compat'] = array(
      'title' => __('Theme Compatibility', 'widgetwrangler'),
      'empty_value' => 0,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['shortcode_tinymce'] = array(
      'title' => __('tinyMCE Shortcode Button', 'widgetwrangler'),
      'empty_value' => 0,
      'require_license' => true,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['override_elements'] = array(
      'title' => __('HTML Override Elements', 'widgetwrangler'),
      'description' => __('Allowed elements for override a widget\'s html output.  Place one element per line.', 'widgetwrangler'),
      'empty_value' => $this->ww->settings['override_elements'],
      'require_license' => true,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    
    if (get_option('ww_previous_main_version', '')){
      $extra_desc = __("This version of Widget Wrangler has been upgraded from 1.x. If you have created templates with the previous version, you should leave this checked.", 'widgetwrangler');
    } else {
      $extra_desc = __("This version of Widget Wrangler was not upgraded from 1.x, you should not need this setting.", 'widgetwrangler');
    }
    
    $settings['legacy_template_suggestions'] = array(
      'title' => __('Legacy Template Suggestions', 'widgetwrangler'),
      'description' => $extra_desc,
      'empty_value' => 0,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    
    // widget settings
    $settings['capabilities'] = array(
      'title' => __('Capabilities', 'widgetwrangler'),
      'tab' => 'settings/widget',
      'value_keys' => array('capabilities', 'advanced_capability'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['exclude_from_search'] = array(
      'title' => __('Exclude from search', 'widgetwrangler'),
      'tab' => 'settings/widget',
      'empty_value' => 0,
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    
    /*
    $settings['widget_advanced'] = array(
      'title' => __('Advanced', 'widgetwrangler'),
      'tab' => 'settings/widget',
      'require_license' => true,
      'description' => __('Only change these if you know what you\'re doing.', 'widgetwrangler'),
      'value_keys' => array('rewrite_slug', 'query_var'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    */
    
    // tools
    $settings['theme_setup'] = array(
      'title' => __('Setup Theme', 'widgetwrangler'),
      'tab' => 'settings/tools',
      'execute_key' => 'theme_setup',
      'description' => __('If you click this button, Widget Wrangler will create a Corral for each WordPress sidebar you have, and place a Widget Wrangler Corral Widget into each WordPress Sidebar.', 'widgetwrangler'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['mass_reset'] = array(
      'title' =>__('Mass Reset', 'widgetwrangler'),
      'tab' => 'settings/tools',
      'execute_key' => 'reset',
      'description' => __('If you click this button, all pages will lose their assigned widget settings and will fall back on the default preset.', 'widgetwrangler'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    $settings['settings_reset'] = array(
      'title' => __('Reset settings to default', 'widgetwrangler'),
      'tab' => 'settings/tools',
      'execute_key' => 'reset_settings',
      'description' => __('If you click this button, Widget Wrangler settings will be reset to their default state.  This will not affect Corral or Widget data.', 'widgetwrangler'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    
    // license
    $settings['license_key'] = array(
      'title' => __('License Key', 'widgetwrangler'),
      'tab' => 'settings/license',
      'execute_key' => 'license',
      'description' => __('Enter your license key below.', 'widgetwrangler'),
      'form_action' => array( $this, '_default_settings_form_action' ),
      'execute_action' => array( $this, '_default_settings_execute_action' ),
      );
    return $settings;
  }
  
  //
  //
  //
  function _preprocess_settings_form_tabs(){
    $settings_form_tabs = apply_filters('ww_settings_form_tabs', array());
    
    foreach ($settings_form_tabs as $tab_key => $tab){
      $settings_form_tabs[$tab_key]['tab_key'] = $tab_key;
      $settings_form_tabs[$tab_key]['safe_tab_key'] = sanitize_key($tab_key);
      $settings_form_tabs[$tab_key]['tab_url'] = $this->urlbase . $tab_key;
      add_action('ww_settings_form_tab_'.$settings_form_tabs[$tab_key]['safe_tab_key'], $settings_form_tabs[$tab_key]['form_action']);
      
      // get the setting items associated with this tab
      foreach ($this->settings_form_items as $setting_key => $setting){
        if ($setting['tab'] == $tab_key){
          $settings_form_tabs[$tab_key]['items'][$setting_key] = $setting;
        }
      }
    }
    if (isset($_GET['page']) && isset($settings_form_tabs[$_GET['page']])){
      $this->current_settings_form_tab = $settings_form_tabs[$_GET['page']];
    }
    $this->settings_form_tabs = $settings_form_tabs;
  }
  
  // for later
  function _process_settings_form_tabs(){}
  
  //
  // Gather, preprocess and process settings_form_items
  //
  function _preprocess_settings_form_items(){
    $settings = apply_filters('ww_settings_form_items', array());
    
    foreach ($settings as $setting_key => $setting){
      // self awareness
      $setting['setting_key'] = $setting_key;
      
      // default setting values
      if (!isset($setting['tab'])) $setting['tab'] = 'settings';
      if (!isset($setting['require_license'])) $setting['require_license'] = false;
      if (!isset($setting['execute_key'])) $setting['execute_key'] = 'save';
      if (!isset($setting['value_keys'])) $setting['value_keys'] = array($setting_key);
      if (!isset($setting['empty_value'])) $setting['empty_value'] = '';
      
      // get submitted values
      if (is_array($setting['value_keys'])){
        foreach ($setting['value_keys'] as $key)
        {
          // get submitted values from $_POST if they exist
          if (isset($_POST['settings'][$key])){
            $setting['submitted_values'][$key] = $_POST['settings'][$key];
          }
          
          // get default form values from settings array
          if (isset($this->ww->settings[$key])){
            $setting['form_values'][$key] = $this->ww->settings[$key];
          }
          // fall back to empty value if missing (or disabled)
          else {
            $setting['form_values'][$key] = $setting['empty_value'];
          }
        }
      }
      
      // add this setting to a dynamic filter for form, and execute.
      // form filter is unique to the setting item
      add_filter('ww_settings_form_items_'.$setting_key, $setting['form_action']);
      
      // execute action is dynamic based on the execute_key
      add_filter('ww_settings_form_items_execute_'.$setting['execute_key'], $setting['execute_action']);
      
      $settings[$setting_key] = $setting;
    }
    
    $this->settings_form_items = $settings;
  }

  function _process_settings_form_items(){
    $settings  = $this->settings_form_items;    
    
    // now that all the settings are preprocessed, build the forms
    foreach ($settings as $setting_key => $setting){
      ob_start();
        apply_filters('ww_settings_form_items_'.$setting_key, $setting);
      $settings[$setting_key]['form'] = ob_get_clean();
    }
    
    $this->settings_form_items = $settings;
  }
  // -------------------------- Form Related Actions ---------------------------
  //
  //
  //
  function _default_settings_execute_action(){
    switch($_GET['ww_action']){
      case 'save':
        $this->_save_settings();
        break;
      
      case 'reset':
        $this->_reset_widgets();
        break;
      
      case 'reset_settings':
        update_option('ww_settings', $this->ww->default_settings);
        break;
      
      case 'theme_setup':
        $this->_setup_theme();
        break;
      
      case 'license':
        $this->_handle_license();
        break;
    }
  }
  
  //
  //
  //
  function _default_settings_form_action($setting){
    $setting_key = $setting['setting_key'];
    
    switch($setting['setting_key'])
    { 
      case 'post_types':
        $post_types = get_post_types(array('public' => true, '_builtin' => false), 'names', 'and');
        $post_types['post'] = 'post';
        $post_types['page'] = 'page';
        unset($post_types['widget']);
        ksort($post_types);
        ?>
          <div class="ww-checkboxes">
            <?php
              // loop through post types
              foreach ($post_types as $post_type )
              {
                $checked = (in_array($post_type, $setting['form_values']['post_types'])) ? 'checked="checked"' : '';
                ?>
                <label class="ww-checkbox"><input type="checkbox" name="settings[<?php print $setting_key; ?>][<?php print $post_type; ?>]" value="<?php print $post_type; ?>" <?php print $checked; ?> /> - <?php print ucfirst($post_type); ?> </label>
                <?php
              }
            ?>
          </div>
        <?php
        break;
      
      // Taxonomies
      case 'taxonomies':
        $taxonomies = get_taxonomies(array(), 'objects');
          
        if (!isset($setting['form_values']['taxonomies'])){
          $setting['form_values']['taxonomies'] = array();
        }
        ?>
          <div class="ww-checkboxes">
            <?php
              // loop through taxonomies
              foreach ($taxonomies as $tax_name => $tax ){
                if ($tax->show_ui){
                  // taken from get_edit_term_link
                  // https://core.trac.wordpress.org/browser/tags/3.9.1/src/wp-includes/link-template.php#L894
                  $args = array(
                    'taxonomy' => $tax_name,
                  );
                  
                  $edit_link = add_query_arg( $args, admin_url( 'edit-tags.php' ) );
                  
                  $checked = (in_array($tax_name, $setting['form_values']['taxonomies'])) ? 'checked="checked"' : '';
                  ?>
                  <label class="ww-checkbox"><input type="checkbox" name="settings[<?php print $setting_key; ?>][<?php print $tax_name; ?>]" value="<?php print $tax_name; ?>" <?php print $checked; ?> /> - <?php print $tax->label; ?>
                    <?php if ($checked) { ?>- <a href="<?php print $edit_link;?>#widget-wrangler">edit widgets</a><?php } ?>
                  </label> 
                  <?php                    
                }
              }
            ?>
          </div>
        <?php
        break;
      
      case 'theme_compat':
        $checked = (!empty($setting['form_values']['theme_compat'])) ? "checked='checked'" : "";
        ?>
          <label class="ww-checkbox">
            <input name="settings[<?php print $setting_key; ?>]" type="checkbox" <?php print $checked; ?> value="1" /> - <?php _e('If checked, widgets will include WordPress sidebar settings for the registered sidebar.  ie, $before_widget, $before_title, $after_title, $after_widget. -- Additionally, enabling theme compatibility provides an administration page for managing the current theme\'s registered sidebar html.', 'widgetwrangler'); ?>
          </label>
        <?php
        break;
      
      case 'shortcode_tinymce':
        $checked = (!empty($setting['form_values']['shortcode_tinymce'])) ? "checked='checked'" : "";
        ?>
          <label class="ww-checkbox">
            <input name="settings[<?php print $setting_key; ?>]" type="checkbox" <?php print $checked; ?> value="1" /> - <?php _e('Enable tinyMCE shortcode button', 'widgetwrangler'); ?>
          </label>
        <?php
        break;
      
      case 'legacy_template_suggestions':
        $checked = (!empty($setting['form_values']['legacy_template_suggestions'])) ? "checked='checked'" : "";
        ?>
          <label class="ww-checkbox">
            <input name="settings[<?php print $setting_key; ?>]" type="checkbox" <?php print $checked; ?> value="1" /> - <?php _e('Enable template suggestions from Widget Wrangler 1.x', 'widgetwrangler'); ?>
          </label>
        <?php
        break;
      
      // widget settings
      case 'capabilities':
        $simple_checked = ($setting['form_values']['capabilities'] == 'simple') ? "checked" : ""; 
        $adv_checked = ($setting['form_values']['capabilities'] == 'advanced') ? "checked" : ""; 
        $advanced_capability = (!empty($setting['form_values']['advanced_capability'])) ? $setting['form_values']['advanced_capability'] : "";
        ?>
          <p> 
            <label>
              <input name="settings[capabilities]" type="radio" value="simple" <?php print $simple_checked; ?> />
              <strong><?php _e('Simple', 'widgetwrangler'); ?></strong>:  <?php _e('Widgets can be Created and Edited by anyone who can edit Posts.  Anyone who can edit a Page can change the Widgets displayed on that Page.', 'widgetwrangler'); ?>
            </label>
          </p>
          <hr />
          <p>
            <label>
              <input name="settings[capabilities]" type="radio" value="advanced" <?php print $adv_checked; ?> />
              <strong><?php _e('Advanced', 'widgetwrangler'); ?></strong>:  <?php _e('Change the capability_type for this post_type.', 'widgetwrangler'); ?>
            </label>
            <?php _e('This is primarily for incorporating third party permission systems. A simple use of this setting would be to change the Capability Type to \'page\'.  This would make it so that only users who can create and edit pages may create and edit widgets.', 'widgetwrangler'); ?>
          </p>
          <p>
            <label><input name="settings[advanced_capability]" type="text" size="20" value="<?php print $advanced_capability; ?>"/> <?php _e('Capability Type', 'widgetwrangler'); ?></label>
          </p>
        <?php
        break;

      case 'exclude_from_search':
        $checked = (!empty($setting['form_values']['exclude_from_search'])) ? "checked='checked'" : "";
        ?>
          <label class="ww-checkbox">
            <input name="settings[<?php print $setting_key; ?>]" type="checkbox" <?php print $checked; ?> value="1" /> - <?php _e('If checked, widgets will be excluded from search results.', 'widgetwrangler'); ?>
          </label>
        <?php
        break;
      
      case 'widget_advanced':
        ?>
          <p>
            <label>
              <?php _e('Rewrite slug', 'widgetwrangler'); ?>: <input name="settings[rewrite_slug]" type="text" value="<?php print $setting['form_values']['rewrite_slug']; ?>" />
            </label>
          </p>
          <p>
            <label>
              <?php _e('Query var', 'widgetwrangler'); ?>: <input name="settings[query_var]" type="text" value="<?php print $setting['form_values']['query_var']; ?>" />
            </label>
          </p>        
        <?php
        break;
      
      // tools
      case 'theme_setup':
        ?>
          <form action="<?php print $this->current_settings_form_tab['tab_url']; ?>&ww_action=theme_setup&noheader=true" method="post">
            <input class="button ww-setting-button-bad" type="submit" value="<?php _e('Setup Theme', 'widgetwrangler'); ?>" onclick="return confirm( '<?php _e('Are you sure you want to reset your WordPress sidebars and widget wrangler corrals?', 'widgetwrangler'); ?>' );" />
          </form>        
        <?php
        break;
      
      case 'mass_reset':
        ?>
          <form action="<?php print $this->current_settings_form_tab['tab_url']; ?>&ww_action=reset&noheader=true" method="post">
            <input class="button ww-setting-button-bad" type="submit" value="<?php _e('Reset All Widgets to Default', 'widgetwrangler'); ?>" onclick="return confirm( '<?php _e('Are you sure you want to Reset widget settings on all pages?', 'widgetwrangler'); ?>' );" />
          </form>        
        <?php
        break;
      
      case 'settings_reset':
        ?>
          <form action="<?php print $this->current_settings_form_tab['tab_url']; ?>&ww_action=reset_settings&noheader=true" method="post">
            <input class="button ww-setting-button-bad" type="submit" value="<?php _e('Reset Settings', 'widgetwrangler'); ?>" onclick="return confirm( '<?php _e('Are you sure you want to Reset Settings?', 'widgetwrangler'); ?>' );" />
          </form>
        <?php
        break;
      
      // license
      case 'license_key':    
        $license 	= get_option( 'ww_pro_license_key' );
        $status 	= get_option( 'ww_pro_license_status' );
        $valid = $this->ww->license_status ? true : false;
        $status_indicator = ( $valid ) ? "<small style='color: green;'>".__('active', 'widgetwrangler') . "</small>": "<small style='color: red;'>".__('inactive', 'widgetwrangler') . "</small>";
        
        $action_title = __("Activate License", 'widgetwrangler');
        $button_name = "ww_pro_license_activate";
        if( $valid ){
          $action_title = __("Deactivate License", 'widgetwrangler');
          $button_name = "ww_pro_license_deactivate";
        }
        ?>
          <form method="post" action="<?php print $this->current_settings_form_tab['tab_url']; ?>&ww_action=license&noheader=true">  
              <p>
                <input id="ww_pro_license_key" name="ww_pro_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                <?php print $status_indicator; ?>
              </p>
              <p>
                <input type="submit" class="button-secondary" name="<?php print $button_name; ?>" value="<?php print $action_title; ?>"/>
              </p>
          </form>
          
          <h2><?php _e('About Widget Wrangler Pro', 'widgetwrangler'); ?></h2>
          <p>
            <?php _e('Widget Wrangler Pro offers new features for a whole new level of control over your widgets.  Manage the widgets on archive (category) pages, create as many presets as you want, and many more!'); ?>
            <a target="_blank" href="http://wranglerplugins.com/plugins/widget-wrangler/#compare"><?php _e('View Pro Features', 'widgetwrangler'); ?></a>
          </p>
        <?php
        break;
      
      case 'override_elements':
        $rows = count($setting['form_values']['override_elements']) + 1;
        if ($rows < 5){
          $rows = 5;
        }
        ?>
          <textarea name="settings[override_elements]" cols="16" rows="<?php print $rows; ?>"><?php print implode("\n", $setting['form_values']['override_elements']); ?></textarea>
        <?php
        break;
    }
  }

  //
  // General settings forms use the ww->admin->_form  wrapper
  //
  function _settings_general_form(){
    $form = array(
      'title' => sprintf( __('Widget Wrangler %s Settings', 'widgetwrangler'), $this->current_settings_form_tab['title']),
      'description' => $this->current_settings_form_tab['description'],
      'attributes' => array(
        'action' => $this->current_settings_form_tab['tab_url'].'&ww_action=save&noheader=true',
        ),
      );
    
    print $this->ww->admin->_form($form, $this->_settings_form_content());
  }
  
  //
  // Tools settings forms include special button and stuff that don't fit into a generic form
  //  - uses ww->admin->_page  wrapper
  //
  function _settings_tools_form(){
    $page = array(
      'title' => sprintf( __('Widget Wrangler %s', 'widgetwrangler'), $this->current_settings_form_tab['title']),
      'description' => $this->current_settings_form_tab['description'],
      );
    
    print $this->ww->admin->_page($page, $this->_settings_form_content());
  }
  
  //
  // Gather the form items and tools for this tab
  //
  function _settings_form_content()
  {
    ob_start();
    ?>
      <div class="ww-admin-tab-links">
        <ul class="ww-admin-tab-list">
          <li class="ww-admin-tab-list-title"><h2 class="ww-setting-title">Settings</h2></li>
          <?php
            foreach($this->settings_form_tabs as $tab_key => $tab)
            {
              $li_class = ($tab_key == $this->current_settings_form_tab['tab_key']) ? "active" : "in-active";
              ?>
                <li class="<?php print $li_class; ?>"><a href="<?php print $tab['tab_url']; ?>"><?php print $tab['title']; ?></a></li>
              <?php
            }
          ?>
        </ul>        
      </div>
      
      <div class="ww-admin-tab">
        <?php
          foreach ($this->settings_form_items as $setting_key => $setting){
            // only settings on this tab
            if ($setting['tab'] == $this->current_settings_form_tab['tab_key']){
              // skip settings that require a license if license invalid
              if ($setting['require_license'] && !$this->ww->license_status){
                continue;
              }
              
              $this->_theme_settings_form_item($setting);
            }
          }
        ?>
      </div>    
    <?php
    return ob_get_clean();
  }

  //
  // Individual settings form item html template
  //  - happens within an output buffer
  //
  function _theme_settings_form_item($setting)
  { ?>
      <div class="postbox">
        <h2 class="ww-setting-title"><?php print $setting['title']; ?></h2>
        <div class="ww-setting-content">
          <?php if (isset($setting['description'])) { ?>
            <p class="description"><?php print $setting['description']; ?></p>
          <?php } ?>
          <div class='ww-setting-form-item'>
            <?php print $setting['form']; ?>
          </div>
          <div class="ww-clear-gone">&nbsp;</div>
        </div>
      </div>
    <?php
  }

  // -------------------------- Form Execute Actions ---------------------------
  
  //
  // Reset all pages to use the default widget settings
  //
  function _reset_widgets(){
    global $wpdb;
    $query = "DELETE FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = 'ww_post_widgets' OR `meta_key` = 'ww_post_preset_id'";
    $wpdb->query($query);
  }
  
  //
  // Save the Widget Wrangler Settings page
  //
  function _save_settings(){
    $settings = $this->ww->settings;
    
    // loop through all settings_items looking for submitted values
    foreach ($this->current_settings_form_tab['items'] as $setting_key => $setting){
      // if these values were submitted with values, store them in the settings array
      foreach ($setting['value_keys'] as $value_key){
        // default to empty
        $value = $setting['empty_value'];
        
        if (isset($setting['submitted_values'][$value_key])){
          $value = $setting['submitted_values'][$value_key];
        }
        
        // override elements
        if ($setting_key == "override_elements" && is_string($value)){
          $value = explode("\n", $value);
        }
        
        $settings[$value_key] = $value;
      }
    }
    
    // save
    update_option('ww_settings', $settings);
    $this->ww->settings = $settings;    
  }
    
  //
  // check license
  //
  function _handle_license() {
    // run a quick security check 
    //if( ! check_admin_referer( 'ww_nonce', 'ww_nonce' ) ) 	{
      //return; // get out if we didn't click the Activate button
    //}
    
    if ( isset( $_POST['ww_pro_license_activate'] ) ) {
      $new = trim($_POST['ww_pro_license_key']);
      $old = trim( get_option( 'ww_pro_license_key' ) );
      
      if($old != $new ) {
        update_option( 'ww_pro_license_key', $new);
        delete_option( 'ww_pro_license_status' ); // new license has been entered, so must reactivate
      }
      
      // retrieve the license from the database
      $license = get_option( 'ww_pro_license_key' );
        
      // data to send in our API request
      $api_params = array( 
        'edd_action'=> 'activate_license', 
        'license' 	=> $license, 
        'item_name' => urlencode( WW_PRO_NAME ) // the name of our product in EDD
      );
      
      // Call the custom API.
      $response = wp_remote_get( add_query_arg( $api_params, WW_PRO_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
  
      // make sure the response came back okay
      if ( is_wp_error( $response ) ){
        return false;
      }
  
      // decode the license data
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );
      
      // $license_data->license will be either "valid" or "invalid"
      update_option( 'ww_pro_license_status', $license_data );
    }
  
    // listen for our activate button to be clicked
    else if( isset( $_POST['ww_pro_license_deactivate'] ) )
    {
      // retrieve the license from the database
      $license = get_option( 'ww_pro_license_key' );
        
      // data to send in our API request
      $api_params = array( 
        'edd_action'=> 'deactivate_license', 
        'license' 	=> $license, 
        'item_name' => urlencode( WW_PRO_NAME ) // the name of our product in EDD
      );
      
      // Call the custom API.
      $response = wp_remote_get( add_query_arg( $api_params, WW_PRO_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
  
      // make sure the response came back okay
      if ( is_wp_error( $response ) ){
        return false;
      }
  
      // decode the license data
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );
      
      // $license_data->license will be either "deactivated" or "failed"
      if( $license_data->license == 'deactivated' ){
        delete_option( 'ww_pro_license_status' );
      }
    }
  }
  
  //
  // Empty wp sidebars,
  //  - create a corral for each wp sidebar,
  //  - place corral widget inside of each wp sidebar
  //
  function _setup_theme(){  
    global $wp_registered_sidebars;
    $sidebars_widgets = get_option( 'sidebars_widgets' );
    $widget_ww_sidebar = get_option('widget_widget-wrangler-sidebar');
    $corrals = $this->ww->corrals;
    
    // new options 
    $new_sidebars_widgets = array(
      'wp_inactive_widgets' => $sidebars_widgets['wp_inactive_widgets'],
      'array_version' => $sidebars_widgets['array_version'],
    );
    $new_widget_ww_sidebar = array('_multiwidget' => 1);
    
    $i = 0;
    foreach ($wp_registered_sidebars as $sidebar_id => $sidebar_details){
      $corral_slug = $this->ww->admin->_make_slug($sidebar_details['name']);
      
      // see if corral exists
      if (!isset($corrals[$corral_slug])){
        // make it
        $corrals[$corral_slug] = $sidebar_details['name'];
      }
      
      // assign a new corral widget instances
      $new_widget_ww_sidebar[$i] = array(
        'title' => '',
        'sidebar' => $corral_slug,
      );
      
      // assign new widget instance to sidebar
      $new_sidebars_widgets[$sidebar_id][$i] = 'widget-wrangler-sidebar-'.$i;
      
      $i++;
    }
    
    update_option('ww_sidebars', $corrals);
    update_option('sidebars_widgets', $new_sidebars_widgets);
    update_option('widget_widget-wrangler-sidebar', $new_widget_ww_sidebar);
  }

}
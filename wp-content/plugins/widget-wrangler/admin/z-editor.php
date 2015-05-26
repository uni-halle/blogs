<?php
/*
 *
filters
 - ww-preset-ajax-op
 
 */
// hook this addon in
add_filter( 'Widget_Wrangler_Addons', 'ww_z_editor_addon' );

//
function ww_z_editor_addon($addons){
  $addons['Z_Editor'] = new WW_Z_Editor();
  return $addons;
}

/*
 *
 */
class WW_Z_Editor  {
  var $capability = 'edit_posts';
  
  function wp_init(){
    if (current_user_can($this->capability)){
      add_action( 'wp_ajax_ww_z_editor_ajax', array( $this, '_z_editor_ajax' ) );
      add_action( 'wp_ajax_nopriv_ww_z_editor_ajax', array( $this, '_z_editor_ajax' ) );
      
      if (!is_admin()){
          add_action( 'wp_enqueue_scripts' , array( $this, '_z_editor_js' ) );
          add_action( 'wp_head', array( $this, '_z_editor_css' ) );
          
          // Hook into the 'wp_before_admin_bar_render' action
          add_action( 'wp_before_admin_bar_render', array( $this, '_z_editor_toolbar' ) );
          
          add_filter( 'widget-wrangler-display-widget-output-alter', array( $this, '_z_editor_wrap_widget_output'), 10, 4 );
          add_filter( 'widget-wrangler-display-corral-output-alter', array( $this, '_z_editor_wrap_corral_output'), 10, 3 );
      }   
    }
  }
  
  // Add Toolbar Menus
  function _z_editor_toolbar() {
    global $wp_admin_bar;
    
    if ($this->ww->page_context && $this->ww->page_widgets){
      
      $message = "Default widgets";
      $message_context = "";
      
      if ($this->ww->page_context['context'] == "post"){
        $post = $this->ww->page_context['object'];
        
        $post_types = get_post_types(array('public' => true, '_builtin' => false), 'names', 'and');
        $post_types['post'] = 'post';
        $post_types['page'] = 'page';
        
        $message = "Viewing ".ucfirst($post_types[$post->post_type])." ".$post->ID;
                    
        $meta = get_post_meta($post->ID);
        
        // look for preset
        if (isset($meta['ww_post_preset_id'][0]) &&
            (int)$meta['ww_post_preset_id'][0] > 0 &&
            $preset = $this->ww->presets->get_preset($meta['ww_post_preset_id'][0]))
        {
          $message_context = 'using preset '.$preset->data['name']; 
        }
        else if (isset($meta['ww_post_widgets'][0]) ) {
          $message_context = "wrangling it's own widgets"; 
        }
      }
      
      else if ($this->ww->page_context['context'] == 'term'){
        $term = $this->ww->page_context['object'];
        $message = 'Viewing term '.$term->name;
        
        $where = array(
          'type' => 'taxonomy',
          'variety' => 'term',
          'extra_key' => $term->term_id,
        );
        if ($term_data = $this->ww->_extras_get($where)){
          // look for presets
          if ($term_data->data['preset_id'] > 0){
            $preset = $this->ww->presets->get_preset($term_data->data['preset_id']);
            $message_context = "using preset ".$preset->data['name']." widgets";
          }
          // wrangling own widgets
          else {
            $message_context = "wrangling it's own widgets";
          }
        }
        // look for taxonomy override
        else {
          $where['variety'] = 'taxonomy';
          $where['extra_key'] = $term->taxonomy;
          if ($tax_data = $this->ww->_extras_get($where)){
            if (isset($tax_data->data['override_default'])){
              $tax = array_pop(get_taxonomies(array('name' => $term->taxonomy), 'objects'));
              $message_context = "using taxonomy ".$tax->label." widgets";
            }
          }
        }
      }
      
      $items = array(
        array(
          'id'     => 'widget-wrangler',
          'title'  => __( 'Widget Wrangler', 'text_domain' ),
        ),
        array(
          'id'     => 'widget-wrangler-context-message',
          'parent' => 'widget-wrangler',
          'title'  => $message.'<div class="message-context">( '.$message_context.' )</div>',
        ),
        array(
          'id'     => 'widget-wrangler-enable',
          'parent' => 'widget-wrangler',
          'title'  => __( 'Show Editor', 'text_domain' ),
        ),
        array(
          'id'     => 'widget-wrangler-disable',
          'parent' => 'widget-wrangler',
          'title'  => __( 'Hide Editor', 'text_domain' ),
        ),
        array(
          'id'     => 'widget-wrangler-save',
          'parent' => 'widget-wrangler',
          'title'  => __( 'Save Widgets', 'text_domain' ),
        )
      );
      
      foreach($items as $args){
        $wp_admin_bar->add_menu( $args );
      }
    }
  }
  
  /*
   * Add css to admin interface
   */
  function _z_editor_css(){
    print '<link rel="stylesheet" type="text/css" href="'.WW_PLUGIN_URL.'/admin/css/z_editor.css" />';
  }
  
  /*
   * Javascript drag and drop for sorting
   */
  function _z_editor_js(){
    wp_enqueue_script('ww-z-editor',
                    plugins_url('admin/js/z_editor.js', WW_PLUGIN_FILE ),
                    array('jquery-ui-core', 'jquery-ui-sortable'),
                    false,
                    true);
    $data = $this->_z_editor_json_data();
    wp_localize_script( 'ww-z-editor', 'WidgetWrangler', array('l10n_print_after' => 'WidgetWrangler = '.$data.';') );	
  }
  
  /*
   * Ajax callback handler
   */
  function _z_editor_ajax(){
    if (isset($_POST['z-editor-op']))
    {
      //
      // saving, look for and check all necessary data
      // 
      if ($_POST['z-editor-op'] === "save_z_editor_widgets" &&
          isset($_POST['context_id']) &&
          isset($_POST['context']) &&
          isset($_POST['z-editor-widgets']))
      {
        $submitted_widgets_data = json_decode(stripcslashes($_POST['z-editor-widgets']), true);
        
        foreach ($submitted_widgets_data as $corral_slug => $widgets){
          foreach ($widgets as $weight => $widget){
            if ($widget['removed']){
              unset($submitted_widgets_data[$corral_slug][$weight]);
            }
            else {
              unset($submitted_widgets_data[$corral_slug][$weight]['removed']);
            }
            unset($submitted_widgets_data[$corral_slug][$weight]['sidebar']);
          }
        }
          
        // saving post widgets
        if ($_POST['context'] == "post" &&
          $this->ww->_current_user_can_edit($_POST['context_id']))
        {
          
          if (update_post_meta( $_POST['context_id'], 'ww_post_widgets', serialize($submitted_widgets_data)) &&
              update_post_meta( $_POST['context_id'], 'ww_post_preset_id', 0))
          {
            print "success";
          }
          else {
            print "failure";
          }
        }
        
        // saving term widgets
        else if ($_POST['context'] == "term") {
          
          $where = array('type' => 'taxonomy', 'variety' => 'term', 'extra_key' => $_POST['context_id']);
          if ($term_data = $this->ww->_extras_get($where)){
            $term_data->widgets = $submitted_widgets_data;
            $term_data->data['preset_id'] = 0;
            
            if ($this->ww->_extras_update($term_data, $where)){
              print 'success';
            } else {
              print 'failure';
            }
          }
        }
      }
      
      //
      // adding new widget
      //
      else if ($_POST['z-editor-op'] == "add_z_editor_widget" &&
          isset($_POST['widget_id']) &&
          isset($_POST['corral_slug']))
      {
        // access control
        //if ($this->ww->_current_user_can_edit($_POST['context_id']))
        //{
          $widget_id = $_POST['widget_id'];
          $corral_slug = $_POST['corral_slug'];
          $wp_widget_args = array();
          
          if($widget = $this->ww->get_single_widget($widget_id))
          {
            // include theme compatibility data
            $widget->theme_compat = 0;
            $widget->wp_widget_args = array('before_widget' => '', 'before_title' => '', 'after_title' => '', 'after_widget' => '');
            $widget->current_weight = 0;  // js will fix
            
            if ($this->ww->settings['theme_compat']) {
              $widget->theme_compat = 1;
              
              // get wp widget args like it was in this sidebar
              $corrals_to_wpsidebar_map = $this->ww->display->corrals_to_wpsidebars_map();
              if (isset($corral_to_wpsidebar_map[$corral_slug]))
              {
                global $wp_registered_sidebars;
                
                if (isset($wp_registered_sidebars[$corral_to_wpsidebar_map[$corral_slug]])){
                  $wp_widget_args = $wp_registered_sidebars[$corral_to_wpsidebar_map[$corral_slug]];
                }
              }          
              
              $widget->wp_widget_args = array_merge($widget->wp_widget_args, $wp_widget_args);
          
              // handle output widget classes
              $search = 'widget-wrangler-widget-classname';
              $replace = 'ww_widget-'.$widget->post_name.' ww_widget-'.$widget->ID;
              $widget->wp_widget_args['before_widget'] = str_replace($search, $replace, $widget->wp_widget_args['before_widget']);          
            }
            
            $widget_html = $this->ww->display->theme_single_widget($widget);
            print $this->_z_editor_wrap_widget_output($widget_html, $widget, $corral_slug);
            exit;
          }
        //}
      }
    }
    else {
      print 'fail';
    }
    exit;
  }
  
  /*
   * data for ajax
   */
  function _z_editor_json_data(){
    $all_widgets = $this->ww->get_all_widgets();
    $all_widget_options = array();
    foreach($all_widgets as $widget){
      $all_widget_options[$widget->ID] = $widget->post_title;
    }
    $WidgetWrangler = array();
    $WidgetWrangler['data'] = array(
      'ajaxURL' => admin_url( 'admin-ajax.php' ),
      'allWidgetOptions' => $all_widget_options,
    );
    
    return json_encode($WidgetWrangler);
  }

  /*
   * Wrap corrals with data for z-editor
   */
  function _z_editor_wrap_corral_output($corral_html, $corral_slug){ 
    
    if (!$this->ww->page_context){
      // no context to work with, exit early
      return $corral_html;
    }
    
    $context = $this->ww->page_context;
    $all_widgets = $this->ww->get_all_widgets();
    
    ob_start();
    ?>
    <div id="ww-z-editor-corral-<?php print $corral_slug; ?>" class="ww-z-editor-corral ww-z-editor-disabled">
      <div class="ww-z-editor-corral-data">
        <div class="ww-z-editor-corral-message"></div>
        
        <div class="ww-z-editor-corral-actions">
          <div class="ww-z-editor-corral-action-add">
            Add Widget
          </div>
          <div class="ww-z-editor-corral-action-add-content">
            <select name="widget_id">
              <?php
                foreach ($all_widgets as $widget)
                { ?>
                  <option value="<?php print $widget->ID; ?>"><?php print $widget->post_title; ?></option>
                  <?php
                }
              ?>
            </select>
            <div class="ww-z-editor-corral-action-add-submit">Add</div>
            <input type="hidden" name="corral_slug" value="<?php print $corral_slug; ?>" />
            <input type="hidden" name="context_id" value="<?php print $context['id']; ?>" />
            <input type="hidden" name="context" value="<?php print $context['context']; ?>" />
          </div>
        </div>
        
        <div class="ww-z-editor-corral-title"><?php print $corral_slug; ?></div>
      </div>
      
      <div class="ww-z-editor-corral-html">
        <?php print $corral_html; ?>
        <div class="ww-no-widgets" style="display:none;">No widgets in this Corral</div>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
  
  /*
   * Wrap widgets in data for z-editor
   */
  function _z_editor_wrap_widget_output($widget_html, $widget, $corral_slug)
  {
    $all_corrals = $this->ww->corrals;
    $wpsidebar_id = (isset($widget->wp_widget_args['id'])) ? $widget->wp_widget_args['id'] : '';
    ob_start();
    ?><div id="ww-z-editor-widget-<?php print $widget->ID; ?>" class="ww-z-editor-widget ww-z-editor-disabled">
        <div class="ww-z-editor-widget-data">
          <a class="ww-z-editor-widget-button ww-z-editor-widget-remove" name="<?php print $widget->ID; ?>">Remove</a>
          <div class="ww-z-editor-widget-title">
            <?php print (isset($widget->hidden_title)) ? $widget->hidden_title : $widget->post_title; ?>
            <div class='ww-z-editor-widget-corral-select'>
              <small>Corral:</small>
              <select class="ww-corral-slug" name="ww-data[widgets][<?php print $widget->ID; ?>][sidebar]">
              <?php
                foreach ($all_corrals as $slug => $title)
                {
                  $selected = ($slug == $corral_slug) ? 'selected="selected"' : '';
                  ?>
                  <option value="<?php print $slug; ?>" <?php print $selected; ?>><?php print $title; ?></option>
                  <?php
                }
              ?>
              </select>            
            </div>
          </div>
          <input type="hidden" name="ww-data[widgets][<?php print $widget->ID; ?>][id]" value="<?php print $widget->ID; ?>" class="ww-widget-id" />
          <input type="hidden" name="ww-data[widgets][<?php print $widget->ID; ?>][weight]" value="<?php print $widget->current_weight; ?>" class="ww-widget-weight" />
          <input type="hidden" name="ww-data[widgets][<?php print $widget->ID; ?>][remove]" value="0" class="ww-widget-removed" />
          <input type="hidden" name="ww-data[widgets][<?php print $widget->ID; ?>][wpsidebar_id]" value="<?php print $wpsidebar_id; ?> " class="ww-widget-wpsidebar-id" />
        </div>
        <div class="ww-z-editor-widget-content"><?php print $widget_html; ?></div>
    </div>
    <?php
    return ob_get_clean();
  }
}
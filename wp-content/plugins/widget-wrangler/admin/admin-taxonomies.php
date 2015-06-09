<?php
// hook this addon in
add_filter( 'Widget_Wrangler_Admin_Addons', 'ww_taxonomies_admin_addon' );

//
function ww_taxonomies_admin_addon($addons){
  $addons['Taxonomies'] = new WW_Taxonomies_Admin();
  return $addons;
}

/*
 *
 */
class WW_Taxonomies_Admin {

  function __construct(){
    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );
    add_action( 'admin_menu', array( $this, 'wp_admin_menu' ) );
  }

  //
  function wp_admin_init(){
    // saving widgets 
    add_action( 'edited_term', array( $this, '_taxonomy_term_form_save' ) );
    
    // alter the form and add some ajax functionality
    add_filter( 'widget_wrangler_preset_ajax_op', array( $this, '_preset_ajax_op') );
    add_action( 'wp_ajax_ww_form_ajax', array( $this, 'ww_form_ajax' ) );
    add_action( 'widget_wrangler_form_meta' , array( $this, 'ww_form_meta' ) );
    
    if (isset($_GET['taxonomy'])){
      $taxonomy = $_GET['taxonomy'];
      
      // see if it's enabled for ww
      if (isset($this->ww->settings['taxonomies']) && isset($this->ww->settings['taxonomies'][$taxonomy]))
      {
        // add js and css
        $this->ww->admin->init_sortable_widgets();
          
        // editing a term
        if (isset($_GET['action']) && isset($_GET['tag_ID'])){
          // add our sortable widgets to term edit form
          add_action ( $taxonomy . '_edit_form_fields', array( $this, '_taxonomy_term_form'), 10, 2);
        }
        // taxonomy list table page
        else {
          add_action( "after-{$taxonomy}-table", array( $this, '_taxonomy_form' ) );
        }
      }
    }
  }
  
  //
  function wp_admin_menu(){
    // need a hook for saving taxonomy defaults
    add_submenu_page(null, 'title', 'title', $this->ww->admin->capability, 'ww_save_taxonomy_form', array( $this, '_menu_router' ) );
  }
  
  //
  function _menu_router(){
    if (isset($_GET['page']) && $_GET['page'] == 'ww_save_taxonomy_form' && isset($_POST['taxonomy'])){
      $this->_save_taxonomy_form();
      wp_redirect($_SERVER['HTTP_REFERER']);
    }
  }
  
  //
  function ww_form_meta()
  {
    if (isset($_GET['tag_ID']))
    { ?>
      <input value="<?php print $_GET['tag_ID']; ?>" type="hidden" id="ww_ajax_context_id" />
      <?php
    }
    else if (isset($_GET['taxonomy']))
    { ?>
      <input value="<?php print $_GET['taxonomy']; ?>" type="hidden" id="ww_ajax_context_id" />
      <?php
    }
  }
  
  //
  // widget form on taxonomy (term list) screen
  // 
  function _taxonomy_form( $taxonomy ) {
    
    if (isset($this->ww->settings['taxonomies'][$taxonomy]) &&
        $taxonomies = get_taxonomies(array('name' => $taxonomy), 'objects'))
    {
      $tax = array_pop($taxonomies);
      $override_default_checked = "";
      
      $where = array(
        'type' => 'taxonomy',
        'variety' => 'taxonomy',
        'extra_key' => $taxonomy,
      );
      
       // allow for presets
      if ($tax_data = $this->ww->_extras_get($where))
      {
        if ( isset( $tax_data->data['override_default'] ) ){
          $override_default_checked = 'checked="checked"';
        }
        
        if (isset($tax_data->data['preset_id']) && $tax_data->data['preset_id'] != 0) {
          $preset = $this->ww->presets->get_preset($tax_data->data['preset_id']);
          $widgets = $preset->widgets;
        }
        else {
          $widgets = $tax_data->widgets;
        }
      }
      else {
        $preset = $this->ww->presets->get_core_preset('default');
        $widgets = $preset->widgets;
      }
      
      $this->ww->page_widgets = $widgets;
      if (isset($preset)){
        $this->ww->presets->current_preset_id = $preset->id;
      }
      
      $form = array(
        'title' => __('Widget Wrangler', 'widgetwrangler'),
        'description' => __('Here you can override the default widgets for all terms in this taxonomy.', 'widgetwrangler'),
        'attributes' => array(
          'action' => $this->ww->admin->parent_slug.'&page=ww_save_taxonomy_form&noheader=true',
          ),
        'submit_button' => array(
          'attributes' => array(
            'value' => __('Save Widgets', 'widgetwrangler'),
            ),
          ),
        );
      
      $form_content = '';
      ob_start();
        ?>
        <input type="hidden" name="taxonomy" value="<?php print $taxonomy; ?>" />
        <div class="postbox">
          <div class="ww-setting-content">
            <p>
              <label><input type="checkbox" name="override_default" <?php print $override_default_checked; ?> /> - <?php _e('Enable these widgets as the default widgets for terms in this taxonomy.', 'widgetwrangler'); ?></label>
            </p>
            <?php $this->ww->admin->_sortable_widgets_meta_box(); ?>
          </div>
        </div>
        <?php
      $form_content = ob_get_clean();
      
      print $this->ww->admin->_form($form, $form_content );
    }
  }

  //
  // save taxonomy widget data
  //
  function _save_taxonomy_form(){
    if (isset($_POST['taxonomy'])) {
      $additional_data = array();
      if (isset($_POST['override_default'])){
        $additional_data['override_default'] = 1;
      }
      $this->_update_posted_taxonomy_widgets('taxonomy', $_POST['taxonomy'], $additional_data);
    }
  }
  
  //
  // widget form on taxonomy_term edit screen
  // 
  function _taxonomy_term_form( $tag, $taxonomy ) {
    $settings = $this->ww->settings;
    
    if (isset($settings['taxonomies'][$tag->taxonomy])){
      $where = array(
        'type' => 'taxonomy',
        'variety' => 'term',
        'extra_key' => $tag->term_id,
      );
      // allow for presets
      if ($term_data = $this->ww->_extras_get($where)){
        if (isset($term_data->data['preset_id']) && $term_data->data['preset_id'] != 0) {
          $preset = $this->ww->presets->get_preset($term_data->data['preset_id']);
          $widgets = $preset->widgets;
        }
        else {
          $widgets = $term_data->widgets;
        }
      }
      else {
        $preset = $this->ww->presets->get_core_preset('default');
        $widgets = $preset->widgets;
      }
      
      $this->ww->page_widgets = $widgets;
      if (isset($preset)){
        $this->ww->presets->current_preset_id = $preset->id;
      }
      
      ?>
      <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Widget Wrangler', 'widgetwrangler'); ?></label><br/><em><small>(<?php _e('for this term only', 'widgetwrangler'); ?>)</small></em></th>
        <td>
          <div class="postbox">
            <div class="ww-setting-content">
              <?php $this->ww->admin->_sortable_widgets_meta_box(); ?>
            </div>
          </div>
        </td>
      </tr>
      <?php
    }
  }
  
  //
  // save taxonomy_term widget data
  //
  function _taxonomy_term_form_save( $term_id ) {
    if (isset($_POST['taxonomy']) &&
        isset($_POST['tag_ID']) &&
        is_numeric($_POST['tag_ID']) && 
        isset($_POST['ww-data']['widgets']))
    {
      $this->_update_posted_taxonomy_widgets('term', $_POST['tag_ID']);
    }
  }
  
  //
  //
  //
  function _update_posted_taxonomy_widgets($variety, $extra_key, $additional_data = array()){
    //
    $widgets = $this->ww->admin->_serialize_widgets($_POST['ww-data']['widgets']);
    
    // let presets addon do it's stuff
    $widgets = apply_filters('widget_wrangler_save_widgets_alter', $widgets);
    
    // get the new preset id, if set
    $new_preset_id = ($this->ww->presets->new_preset_id !== FALSE) ? (int) $this->ww->presets->new_preset_id : 0;
    
    $where = array(
      'type' => 'taxonomy',
      'variety' => $variety,
      'extra_key' => $extra_key,
    );
    
    $values = array(
      'type' => 'taxonomy',
      'variety' => $variety,
      'extra_key' => $extra_key,
      'data' => array('preset_id' => $new_preset_id),
      'widgets' => $widgets,
    );
    
    if (!empty($additional_data)){
      $values['data'] += $additional_data;
    }
    
    // doesn't exist, create it before update
    if (!$this->ww->_extras_get($where)){
      $values['data'] = serialize($values['data']);
      $this->ww->_extras_insert($values);
    }
    
    if ($widgets) {
      // no preset, save widgets
      $values['widgets'] = $widgets;
      
      // force the 'zero' preset because these widgets are custom
      $values['data']['preset_id'] = 0;
    }
    
    if ($new_preset_id) {
      // don't save widgets because they are preset widgets
      unset($values['widgets']);
    }
    
    $values['data'] = serialize($values['data']);
    $this->ww->_extras_update($values, $where); 
  }

  // override the preset ajax op
  function _preset_ajax_op($op){
    if (isset($_GET['tag_ID']) || (isset($_POST['op']) && $_POST['op'] == 'replace_edit_taxonomy_term_widgets')) {
      $op = 'replace_edit_taxonomy_term_widgets';
    }
    else if (isset($_GET['taxonomy']) || (isset($_POST['op']) && $_POST['op'] == 'replace_edit_taxonomy_widgets')) {
      $op = 'replace_edit_taxonomy_widgets';
    }
    
    return $op;
  }
  
  //
  function ww_form_ajax(){
    if (isset($_POST['op'])) {
      if ($_POST['op'] == 'replace_edit_taxonomy_term_widgets'){
        // legit post ids only
        if (isset($_POST['context_id']) && is_numeric($_POST['context_id']) && $_POST['context_id'] > 0)
        {
          $tag_id = $_POST['context_id'];
          $preset_id = 0;
          
          if (isset($_POST['preset_id']) && is_numeric($_POST['preset_id'])){
            $preset_id = $_POST['preset_id'];
          }
          
          // if we changed to a preset, load those widgets
          if ($preset_id && $preset = $this->ww->presets->get_preset($preset_id)){
            $this->ww->presets->current_preset_id = $preset_id;
            $this->ww->page_widgets = $preset->widgets;
          }
          // else, attempt to load tag widgets
          else {
            $where = array(
              'type' => 'taxonomy',
              'variety' => 'term',
              'extra_key' => $tag_id,
            );
            
            if ($term_data = $this->ww->_extras_get($where)){
              $this->ww->page_widgets = $term_data->widgets;  
            }
            else {
              $this->ww->page_widgets = $this->ww->presets->get_core_preset('default')->widgets;
            }
          }
          ob_start();
            $this->ww->admin->_sortable_widgets_meta_box();
          $output = ob_get_clean();
  
          print $output;
        }
        exit;
      }
      else if ($_POST['op'] == 'replace_edit_taxonomy_widgets'){
        if (isset($_POST['context_id'])) {
          $taxonomy = $_POST['context_id'];
          $preset_id = 0;
          
          if (isset($_POST['preset_id']) && is_numeric($_POST['preset_id'])){
            $preset_id = $_POST['preset_id'];
          }
          
          // if we changed to a preset, load those widgets
          if ($preset_id && $preset = $this->ww->presets->get_preset($preset_id)){
            $this->ww->presets->current_preset_id = $preset_id;
            $this->ww->page_widgets = $preset->widgets;
          }
          // else, attempt to load tag widgets
          else {
            $where = array(
              'type' => 'taxonomy',
              'variety' => 'taxonomy',
              'extra_key' => $taxonomy,
            );
            
            if ($term_data = $this->ww->_extras_get($where)){
              $this->ww->page_widgets = $term_data->widgets;  
            }
            else {
              $this->ww->page_widgets = $this->ww->presets->get_core_preset('default')->widgets;
            }
          }
          ob_start();
            $this->ww->admin->_sortable_widgets_meta_box();
          $output = ob_get_clean();
  
          print $output;          
        }
        exit;
      }
    }
  }
    
}
<?php
/*
 *
filters
 - widget_wrangler_preset_ajax_op
 
 */
// hook this addon in
add_filter( 'Widget_Wrangler_Admin_Addons', 'ww_presets_admin_addon' );

//
function ww_presets_admin_addon($addons){
  $addons['Presets_Admin'] = new WW_Presets_Admin();
  return $addons;
}

/*
 *
 */
class WW_Presets_Admin  {
  public $page_hook;
  public $current_preset_id = 0;
  public $new_preset_id = FALSE;

  function __construct(){
    add_action( 'admin_init', array( $this, 'wp_admin_init' ) );
    add_action( 'admin_menu', array( $this, 'wp_admin_menu' ) );
  }

  /**
   * Implements action 'admin_init'
   */
  function wp_admin_init(){
    add_action( 'widget_wrangler_form_top', array( $this, 'ww_form_top' ));
    add_action( 'wp_ajax_ww_form_ajax', array( $this, 'ww_form_ajax' ) );
    add_action( 'widget_wrangler_save_widgets_alter', array( $this, 'ww_save_widgets_alter' ) );
    
    if ( isset($_GET['post_type']) && 'widget' == $_GET['post_type'] &&
         isset($_GET['page']) && $_GET['page'] == 'presets' )
    {
      $this->ww->admin->init_sortable_widgets();
    }
  }

  //
  function wp_admin_menu(){
    $page_title = 'Presets';

    $this->page_hook = add_submenu_page($this->ww->admin->parent_slug, $page_title, $page_title, $this->ww->admin->capability, 'presets', array( $this, '_menu_router' ));
  }

  //
  function ww_save_widgets_alter($widgets){
    // '0' handles itself
    $new_preset_id = (isset($_POST['ww-post-preset-id-new'])) ? (int)$_POST['ww-post-preset-id-new'] : 1;
    $new_preset_widgets = NULL;
    
    // attempt to load that new preset
    if ($new_preset_id && $new_preset = $this->ww->presets->get_preset($new_preset_id)){
      $new_preset_widgets = serialize($new_preset->widgets);
    }
    
    // if the widgets to be saved are not the same as the selected preset's widgets
    //   then the user loaded a preset, and changed some widgets
    if ($widgets != $new_preset_widgets){
      // force the 'zero' preset because these widgets are custom
      $new_preset_id = 0;      
    }
    
    // if new_preset_id is not zero, then a preset was selected and we don't want to save widgets
    if ($new_preset_id !== 0){
      $widgets = false;
    }
    // set the new preset id for other plugins or addons to use
    $this->ww->presets->new_preset_id = $new_preset_id;
    
    return $widgets;
  }

  /*
   * Handles settings pages
   *   - all settings pages submit to this execute_action
   */
  function _menu_router(){
    if (isset($_GET['ww_action']) && $_GET['ww_action'] == "handle_button" && isset($_POST['action'])){
      // get the button presses
      $keys = array_keys($_POST['action']);
      $button = array_pop($keys);
  
      switch ($button){
        // create new widget preset
        case 'create':
          $preset_id = $this->_create_preset();
          break;
        //*/
        // update an existing widget preset
        case 'update':
          $preset_id = $this->_update_preset();
          break;
        //
        case 'delete':
          $this->_delete_preset();
          break;
        //*/
      }
      wp_redirect(get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=widget&page=presets&preset_id='.$preset_id);  
    }
    else {
      $this->_presets_form();
    }
  }
  
  /*
   * Delete a Widget Preset
   */
  function _delete_preset(){
    global $wpdb;
    $table = $wpdb->ww_extras_table;
    
    // can't delete defaults
    if(isset($_POST['preset-id']) && isset($_POST['preset-variety']) && $_POST['preset-variety'] != 'core'){
      $preset_id = $_POST['preset-id'];
      $preset_variety = $_POST['preset-variety'];
    
      $sql = "DELETE FROM ".$table." WHERE `type` = 'preset' AND `variety` = '%s' AND `id` = '%s' LIMIT 1";
      $prepared = $wpdb->prepare($sql,$preset_variety, $preset_id);
      
      $wpdb->query($prepared);
    }
  }

  /*
   * Update a Widget Preset
   *
   * @return int Widget Preset id
   */
  function _update_preset(){
    if (isset($_POST['preset-id']) && isset($_POST['preset-variety']) && isset($_POST['data']) && isset($_POST['ww-data']['widgets'])) 
    {
      $submitted_widget_data = $_POST['ww-data']['widgets'];
      $active_widgets = $this->ww->admin->_serialize_widgets($submitted_widget_data);
      
      $preset_id = $_POST['preset-id'];
      $preset_variety = $_POST['preset-variety'];
      $preset_data = $_POST['data'];
      $preset_widgets = (!empty($active_widgets)) ? $active_widgets : serialize(array());    
      
      // update the ww_presets db
      $data = array(
        'data' => serialize($preset_data),
        'widgets' => $preset_widgets,
      );
      $where = array(
        'id' => $preset_id,
        'type' => 'preset',
      );
      
      // save the widgets
      $this->ww->_extras_update($data, $where);
      $this_preset_variety = $this->ww->preset_varieties[$preset_variety];

      return $preset_id;
    }
  }
  
  /*
   * Create a new Widget Preset
   *
   * @return int New Widget Preset id
   */
  function _create_preset() {
    if (isset($_POST['create']['variety'])) {
      switch($_POST['create']['variety']) {
        case 'standard':
          $data = array(
            'type' => 'preset',
            'variety' => $_POST['create']['variety'],
            'data' => serialize(array('name' => ucfirst($_POST['create']['variety']))),
            'widgets' => serialize(array()),
          );
          
          return $this->ww->_extras_insert($data);
          break;
      }
    }
  }
  
  //
  // Ajax form templates
  //
  function ww_form_ajax() {
    if (isset($_POST['op']) && $_POST['op'] == 'replace_edit_page_widgets'){
      // legit post ids only
      if (isset($_POST['context_id']) && is_numeric($_POST['context_id']) && $_POST['context_id'] > 0)
      {
        $post_id = $_POST['context_id'];
        $preset_id = 0;
        
        if (isset($_POST['preset_id']) && is_numeric($_POST['preset_id'])){
          $preset_id = $_POST['preset_id'];
        }
        
        // setup the admin editing_post_id variable 
        $this->ww->admin->editing_post_id = $post_id;
        
        // if we changed to a preset, load those widgets
        if ($preset_id && $preset = $this->ww->presets->get_preset($preset_id)){
          $this->ww->presets->current_preset_id = $preset_id;
          $this->ww->page_widgets = $preset->widgets;
        }
        // else, attempt to load page widgets
        else {
          $page_widgets = get_post_meta($post_id, 'ww_post_widgets', TRUE);
          $this->ww->page_widgets = ($page_widgets != '') ? maybe_unserialize($page_widgets) : array();
        }
        
        ob_start();
          $this->ww->admin->_sortable_widgets_meta_box(NULL);
        $output = ob_get_clean();

        print $output;
      }
      exit;
    }
  }
  
  // action for widget_wrangler_form_top
  function ww_form_top()
  {
    $preset_ajax_op = 'replace_edit_page_widgets';
    // allow other addons to manage their own ajax
    $preset_ajax_op = apply_filters('widget_wrangler_preset_ajax_op', $preset_ajax_op);
    
    $all_presets = $this->ww->presets->get_all_presets();
    $current_preset_id = $this->ww->presets->current_preset_id;
    $current_preset = NULL;
    $current_preset_message = "No preset selected. This page is wrangling widgets on its own.";
    
    // we have a preset to load
    if ($current_preset_id && $current_preset = $this->ww->presets->get_preset($current_preset_id)){
      $current_preset_message = "This page is currently using the <a href='".$this->ww->admin->parent_slug."&page=presets&preset_id=".$current_preset->id."'>".$current_preset->data['name']."</a>  Widgets.";                
    }
    
    ?>
    <div id='ww-post-preset'>
      <input type="hidden" name="widget_wrangler_preset_ajax_op" id="widget_wrangler_preset_ajax_op" value="<?php print $preset_ajax_op; ?>" />
      <span id="ww-post-preset-message"><?php print $current_preset_message; ?></span>
      <div class='ww-presets'>
        <span><?php _e("Widget Preset", 'widgetwrangler'); ?>:</span>
        
        <select id='ww_post_preset' name='ww-post-preset-id-new'>
          <option value='0'><?php _e("- No Preset -", 'widgetwrangler'); ?></option>
          <?php
            // create options
            foreach($all_presets as $preset)
            {
              $selected = ($preset->id == $current_preset_id) ? "selected='selected'" : '';
              ?>
              <option value='<?php print $preset->id; ?>' <?php print $selected;?>><?php print $preset->data['name']; ?></option>
              <?php
            }
          ?>
        </select>
        <span class="ajax-working spinner">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        
        <p class='description'>
          <span><?php _e("Select the Widget Preset you would like to control widgets on this page. To allow this page to control its own widgets, select '- No Preset -'. If you select a preset and then rearrange widgets, this page will convert itself to '- No Preset -' on save.", 'widgetwrangler'); ?></span>
        </p>
      </div>
    </div>        
      
    <?php
  }
  
  /*
   * Admin Manage presets form
   */
  function _presets_form() {
    // prepare preset data  
    $preset_id = 1;
    
    if(isset($_GET['preset_id'])) {
      if (is_numeric($_GET['preset_id']) && $_GET['preset_id'] > 1){
        $preset_id = $_GET['preset_id'];
      }
    }
    
    $all_presets     = $this->ww->presets->get_all_presets();
    $this_preset     = $this->ww->presets->get_preset($preset_id);
    $preset_variety  = $this->ww->presets->preset_varieties[$this_preset->variety];


	  // themes draggable widgets
	  $sortable = new WW_Admin_Sortable();

	  // need to remove the normal preset form_top. can't select preset for preset
	  remove_action( 'widget_wrangler_form_top', array( $this, 'ww_form_top' ));

	  ob_start();
    ?>
    <div class='wrap'>
      <form id="widget-wrangler-form" action='edit.php?post_type=widget&page=presets&ww_action=handle_button&noheader=true' method='post' name='widget-wrangler-form'>				
        <div class="ww-admin-top">
          <p class="ww-top-right-save"> 
            <input class='button button-primary button-large' name='action[update]' type='submit' value='Save Preset' />
            <input value='true' type='hidden' name='widget-wrangler-edit' />
            <input type='hidden' name='ww_noncename' id='ww_noncename' value='<?php print wp_create_nonce( plugin_basename(__FILE__) ); ?>' />
          </p>
          <h2><?php _e("Edit Preset", 'widgetwrangler'); ?> <em><?php print $this_preset->data['name']; ?></em></h2>
        </div>
  
        <div id="presets-wrap">	
          <div class="ww-admin-tab-links">
            <ul class="ww-admin-tab-list">
              <li class="ww-admin-tab-list-title"><h2 class="ww-setting-title">Presets</h2></li>
              <?php
                // show all presets, core first, then others
                if(is_array($all_presets)){
                  foreach($all_presets as $preset)
                  {
                    $classes = ($preset_id == $preset->id) ? 'active' : '';
                    ?>
                    <li class="<?php print $classes; ?>">
                      <a href="edit.php?post_type=widget&page=presets&preset_id=<?php print $preset->id; ?>"><?php print $preset->data['name']; ?><?php print ($preset->variety == 'core') ? ' <small>('.$preset->variety.')</small>' : ''; ?></a>
                    </li>
                    <?php
                  }
                }
              ?>
            </ul>
            
            <?php
              if ($this->ww->license_status)
              { ?>
                <div id="presets-add-new">
                  <h2 class="ww-setting-title">Create New</h2>
                  <div class="ww-setting-content">
                    <p>
                      <select name="create[variety]">
                      <?php
                        foreach($this->ww->presets->preset_varieties as $key => $pstype)
                        {
                          if ($key != 'core')
                          { ?>
                            <option value="<?php print $key; ?>"><?php print $pstype['title']; ?></option>
                            <?php
                          }
                        }
                      ?>
                      </select>
                    </p>
                    <p>
                      <input type="submit" name="action[create]" class="button button-primary button-large" value="<?php _e("Create New Preset", 'widgetwrangler'); ?>" />
                    </p>
                  </div>
                </div>
                <?php
              }
            ?>
          </div>
        
          <div class="ww-admin-tab postbox">		
            <?php
              // can't change the names of core presets
              if($preset_variety['slug'] == 'core')
              { ?>
                <h2 class="ww-setting-title"><?php print $this_preset->data['name']; ?></h2>
                <input type="hidden" name="data[name]" value="<?php print $this_preset->data['name']; ?>" />
                <?php
              }
              else
              { ?>			
                <div id="preset-data">
                  <div id="preset-name">
                    <div class="detail">
                      <label><?php _e("Name:", 'widgetwrangler'); ?></label>
                      <input size="40" type="text" name="data[name]" value="<?php print $this_preset->data['name']; ?>" />
                    </div>
                    <div class="detail">
                      <label><?php _e("Type:", 'widgetwrangler'); ?></label> <?php print $preset_variety['slug']; ?>
                    </div>         
                  </div>
                </div>
                <?php
              }
            ?>
            <input type="hidden" name="preset-id" value="<?php print $preset_id; ?>" />
            <input type="hidden" name="preset-variety" value="<?php print $preset_variety['slug']; ?>" /> 
            
            <div class="ww-admin-tab-inner">

			<div id="widget_wrangler_form_top">
				<?php
				// TODO, dry this action up
				do_action('widget_wrangler_form_top');
				?>
			</div>
			<div id='ww-post-edit-message'>* <?php _e("Widget changes will not be updated until you save.", 'widgetwrangler'); ?>"</div>

              <div id="preset-widgets">
                <?php print $sortable->theme_sortable_corrals( $this_preset->widgets ); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="ww-clear-gone">&nbsp;</div>
        <?php if($preset_variety['slug'] != 'core'){ ?>
          <input class='button' name='action[delete]' type='submit' value='<?php _e("Delete", 'widgetwrangler'); ?>' />
        <?php } ?>
      </form>
    </div>
    <?php
  }

}
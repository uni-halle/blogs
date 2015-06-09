<?php
// hook this addon in
add_filter( 'Widget_Wrangler_Admin_Addons', 'ww_corrals_admin_addon' );

//
function ww_corrals_admin_addon($addons){
  $addons['Corrals'] = new WW_Corrals_Admin();
  return $addons;
}

/*
 *
 */
class WW_Corrals_Admin  {
  public $page_hook;
  
  function __construct(){
    add_action( 'admin_menu', array( $this, 'wp_admin_menu' ) );
  }
  
  function wp_admin_menu(){
    $page_title = 'Corrals';

    $this->page_hook = add_submenu_page($this->ww->admin->parent_slug, $page_title, $page_title, $this->ww->admin->capability, 'corrals', array( $this, '_menu_router' ));
    add_action( "admin_print_scripts-".$this->page_hook, array( $this, '_corrals_form_js' ) );
    add_action( "admin_head", array( $this->ww->admin, '_admin_css' ) );
  }

  /*
   * Handles settings pages
   *   - all settings pages submit to this execute_action
   */
  function _menu_router(){
    if (isset($_GET['ww_action'])){
      switch($_GET['ww_action']){
        case 'insert':
          $new_corral_id = $this->_corrals_insert($_POST);
          break;
        case 'delete':
          $this->_corrals_delete($_POST);
          break;
        case 'update':
          $this->_corrals_update($_POST);
          break;
        case 'sort':
          $this->_corrals_sort($_POST);
          break;
      }
      
      wp_redirect($_SERVER['HTTP_REFERER']);
      exit;
    }
    else {
      $this->_corrals_form();
    }
  }
  
  /*
   * Handle sorting of sidebars
   */
  function _corrals_sort($posted = array())
  {
    $all_sidebars = $this->ww->corrals;
    $new_order_array = array();
    
    if (is_array($posted['weight'])){
      $i = 1;
      $total = count($posted['weight']);
      while($i <= $total){
        $new_order_array[$posted['weight'][$i]] = $all_sidebars[$posted['weight'][$i]];
        $i++;
      }
      
      update_option('ww_sidebars',$new_order_array);
    }
  }
  
  /*
   * Add a new sidebar
   */
  function _corrals_insert($posted = array())
  {
    $new_corral = strip_tags($posted['ww-new-corral']);
    $slug_name = $this->ww->admin->_make_slug($new_corral); 
    $corrals_array = get_option('ww_sidebars', array());
     
    // add new sidebar
    $corrals_array[$slug_name] = $new_corral;
    
    // save
    update_option('ww_sidebars',$corrals_array);
  }
  
  /*
   * Delete a sidebar
   */
  function _corrals_delete($posted = array())
  {
    $old_slug = $posted['ww-delete-slug'];
    $corrals_array = get_option('ww_sidebars', array());
    
    if (isset($corrals_array[$old_slug])){
      unset($corrals_array[$old_slug]);
    }
    
    update_option('ww_sidebars', $corrals_array);
  }
  
  /*
   * Update/Edit a sidebar
   */
  function _corrals_update($posted = array())
  {
    $update_corral = strip_tags($posted['ww-update-corral']);
    $update_slug = $this->ww->admin->_make_slug($posted['ww-update-slug']);
    $corrals_array = get_option('ww_sidebars', array());
    $old_slug = $posted['ww-update-old-slug'];
    
    if (isset($corrals_array[$old_slug])){
      // delete old one
      unset($corrals_array[$old_slug]);
      // add new one
      $corrals_array[$update_slug] = $update_corral;
    }
    
    update_option('ww_sidebars', $corrals_array);
  }
  
  /*
   * Build the form 
   */
  function _corrals_form()
  {
    $corrals = $this->ww->corrals;
    $sorting_items = '';
    ?>
    <div class='wrap'>
      <h2><?php _e("Widget Corrals", 'widgetwrangler'); ?></h2>
      <p>
        <?php _e("A Corral is an arbitrary group of widgets. WordPress and previous Widget Wrangler versions call them 'sidebars', but they are ultimately not limited by that terminology.", 'widgetwrangler'); ?>
      </p>
      <p>
        <?php _e("In general, you probably want at least one corral per theme sidebar.", 'widgetwrangler'); ?>
      </p>
    <div id='ww-corral-page'>
      <div class="ww-setting-column">
        
        <div class="postbox">
          <h2 class="ww-setting-title"><?php _e("Edit existing Corrals", 'widgetwrangler'); ?></h2>
          <div class="ww-setting-content">
          <div class='description' style='color:red;'>
            <?php _e("Warning! If you change a corral's slug, widgets currently assigned to that corral will need to be reassigned.", 'widgetwrangler'); ?>
          </div>
          <ul id='ww-corrals-list'>
          <?php
            //  no corrals
            if (!is_array($corrals))
            { ?>
              <li><?php _e("No Corrals defined", 'widgetwrangler'); ?></li>
              <?php
            }
            // corrals
            else {
              // loop through each sidebar and build edit form
              $i = 1;
              foreach($corrals as $slug => $corral)
              { ?><li class='ww-corral-item ww-widgets-holder-wrap'>
                    <div class='widget widgets-holder-wrap closed'>
                      <div class='widget-top'>
                        <div class='widget-title-action sidebar-name'>
                          <div class='widget-action sidebar-name-arrow handlediv'></div>
                        </div>
                        <h4><?php print $corral; ?> (<?php print $slug; ?>)</h4>
                      </div>
                      <div class='widget-inside'>
                        <form action='edit.php?post_type=widget&page=corrals&ww_action=update&noheader=true' method='post'>
                          <p class="ww-top-right-save">
                            <input class='ww-corral-update-submit button button-primary button-large' name='ww-update-submit' type='submit' value='<?php _e("Update", 'widgetwrangler'); ?>' />
                          </p>
                          <p>
                            <label><?php _e("Name", 'widgetwrangler'); ?>: </label>
                            <input class='ww-text' name='ww-update-corral' type='text' value='<?php print $corral; ?>' />
                          </p>
                          <p>
                            <label><?php _e("Slug", 'widgetwrangler'); ?>: </label>
                            <input class='ww-text' name='ww-update-slug' type='text' value='<?php print $slug; ?>' />
                          </p>
                          <input name='ww-update-old-slug' type='hidden' value='<?php print $slug; ?>' />
                        </form>
                        <hr />
                        <form class='ww-delete-corral' action='edit.php?post_type=widget&page=corrals&ww_action=delete&noheader=true' method='post'>
                          <input name='ww-delete-slug' type='hidden' value='<?php print $slug; ?>' />
                          <p>
                            <input class='ww-setting-button-bad button button-small ww-delete-submit' name='ww-delete-submit' type='submit' value='<?php _e("Delete", 'widgetwrangler'); ?>' onclick="return confirm('<?php _e('Are you sure you want to delete this corral?', 'widgetwrangler'); ?>');" />
                          </p>
                        </form>
                        <div class="ww-clear-gone">&nbsp;</div>
                      </div>
                    </div>
                  </li>
                  <?php
                // sortable list
                $sorting_items.= "<li class='ww-corral-sort-item'>
                                    <strong>".$corral." (".$slug.")</strong>
                                    <input type='hidden' class='ww-corral-weight' name='weight[".$i."]' value='".$slug."' />
                                  </li>";
                $i++;
              }
              
            }
          ?>
          </ul>
          </div>
        </div>
      </div>
      <div class="ww-setting-column">
        <div class="postbox">
          <h2 class="ww-setting-title"><?php _e("Create New Corral", 'widgetwrangler'); ?></h2>
          <div class="ww-setting-content">
            <form action='edit.php?post_type=widget&page=corrals&ww_action=insert&noheader=true' method='post'>
              <p>
                <?php _e("Corral Name", 'widgetwrangler'); ?>: <input name='ww-new-corral' type='text' value='' />
              </p>
              <input class='button button-primary button-large' type='submit' value='<?php _e("Create Corral", 'widgetwrangler'); ?>' />
            </form>
          </div>
        </div>
        
        <div class="postbox">
          <h2 class="ww-setting-title"><?php _e("Sort Corrals", 'widgetwrangler'); ?></h2>
          <div class="ww-setting-content">
            <form action='edit.php?post_type=widget&page=corrals&ww_action=sort&noheader=true' method='post'>
              <ul id='ww-corrals-sort-list'>
                <?php print $sorting_items; ?>
              </ul>
              <input class='ww-sidebar-sort-submit button button-primary button-large' type='submit' name='ww-sidebars-save' value='<?php _e("Save Order", 'widgetwrangler'); ?>' />
            </form>
          </div>
        </div>
      </div>
    </div>
    </div>
    <?php
  }

  //
  // Javascript for drag and drop sidebar sorting
  //
  function _corrals_form_js(){
    wp_enqueue_script('ww-corrals-js',
                      plugins_url('js/corrals.js', __FILE__ ),
                      array('jquery-ui-core', 'jquery-ui-sortable'),
                      false);
  }
    
}
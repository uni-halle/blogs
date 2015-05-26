<?php

/*
 *
 */
class WW_Widget_PostType {
  // public meta fields
  var $meta_box_fields = array(
        "ww-adv-enabled",
        "ww-parse",
        "ww-wpautop",
        "ww-adv-template",
        "ww-hide-title",
        "ww-hide-from-wrangler",
        "ww-custom-template-suggestion",
        'ww-override-output-html',
        'ww-html-wrapper-element',
        'ww-html-wrapper-id',
        'ww-html-wrapper-classes',
        'ww-html-title-element',
        'ww-html-title-classes',
        'ww-html-content-element',
        'ww-html-content-classes',
        'ww-preview-corral-slug',
        'ww-display-logic-enabled',
        'ww-display-logic',
      );
  var $capability_type;
  var $widget_type;
  var $post_id;
  var $widget_meta = array();
  
  //
  function __construct(){
    global $widget_wrangler;
    $this->ww = $widget_wrangler;
    
    add_action( 'init', array( $this, 'wp_init' ) );
  }
  
  /*
   * hook_init
   */
  function wp_init(){
    $settings = $this->ww->settings;
    $capability_type = ($settings['capabilities'] == "advanced" && isset($settings['advanced_capability'])) ? $settings['advanced_capability'] : "post";
    
    $supports = array(
      'title' => 'title',
      'excerpt' => 'excerpt',
      'editor' => 'editor',
      'custom-fields' => 'custom-fields',
      'thumbnail' => 'thumbnail'
    );
      
    // custom post type labels
		$labels = array(
      'name' => __('Widget Wrangler', 'widgetwrangler'),
      'all_items' => __('All Widgets'),
      'singular_name' => __('Widget', 'widgetwrangler'),
      'add_new' => __('Add New Widget', 'widgetwrangler'),
      'add_new_item' => __('Add New Widget', 'widgetwrangler'),
      'edit_item' => __('Edit Widget', 'widgetwrangler'),
      'new_item' => __('New Widget', 'widgetwrangler'),
      'view_item' => __('View Widget', 'widgetwrangler'),
      'search_items' => __('Search Widgets', 'widgetwrangler'),
      'not_found' =>  __('No widgets found', 'widgetwrangler'),
      'not_found_in_trash' => __('No widgets found in Trash', 'widgetwrangler'),
      'parent_item_colon' => '',
    );

    
    // Editing specific widget
		if (isset($_GET['post'])){
			$pid = $_GET['post'];
		}
		else if (isset($_POST['post_ID'])){
			$pid = $_POST['post_ID'];
		}
		// get the type
		if (isset($pid)){
			$post_type = get_post_type($pid);
		}
    
		// load some extra data if we are on a WW widget post_type
		if (isset($post_type) && $post_type == 'widget')
		{
      // Allow for insert post hook
      add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);
      
			// this widget has been saved before, we know $pid exists
			$this->post_id = $pid;
      $this->widget_meta = get_post_meta($pid);
      $widget_type = get_post_meta($pid, 'ww-widget-type', true);
			$this->widget_type = ($widget_type) ? $widget_type : "standard";

			// Clones do not need an editor
			if($this->widget_type == 'clone'){
				unset($supports['editor']);
			}
    }
    
    // Register the post_type
    register_post_type('widget', array(
      'labels' => $labels,
      'public' => true,
			//'publicly_queryable' => true,?
      'exclude_from_search' => (isset($settings['exclude_from_search']) && $settings['exclude_from_search'] == 0) ? false : true, 
      'show_in_menu' => true,
      'show_ui' => true, // UI in admin panel
      '_builtin' => false, // It's a custom post type, not built in
      '_edit_link' => 'post.php?post=%d',
      'capability_type' => $capability_type,
      'hierarchical' => false,
      'rewrite' => array("slug" => 'widget'), // Permalinks
      'query_var' => 'widget', // This goes to the WP_Query schema
      'supports' => $supports,
      'menu_icon' => WW_PLUGIN_URL.'/admin/images/lasso-menu.png'
    ));

    add_filter("manage_edit-widget_columns", array(&$this, "edit_columns"));
    add_action("manage_posts_custom_column", array(&$this, "custom_columns"));

    if (isset($_GET['post']) && 'widget' == get_post_type($_GET['post'])){
      // Admin interface init
      add_action("admin_init", array(&$this, "wp_admin_init"));
    }
  }
  
  /*
   * Add meta box to widget posts
   */
  function wp_admin_init()
  {
    add_action('admin_enqueue_scripts', array( $this->ww->admin, '_editing_widget_js' ));
    
    // Clone Instance
    if ($this->widget_type == "clone") {
      add_meta_box("ww-clone-instance", __("Widget Form", 'widgetwrangler'), array(&$this, "meta_box_clone_instance"), "widget", "normal", "high");
      add_meta_box("ww-display-logic", __("Display Logic", 'widgetwrangler'), array(&$this, "meta_box_display_logic"), "widget", "normal", "high");
    }
    else {
      // Custom meta boxes for the edit widget screen
      add_meta_box("ww-parse", __("Options", 'widgetwrangler'), array(&$this, "meta_box_parse"), "widget", "normal", "high");
      add_meta_box("ww-display-logic", __("Display Logic", 'widgetwrangler'), array(&$this, "meta_box_display_logic"), "widget", "normal", "high");
    }
    add_meta_box("ww-adv-help", __("Advanced Help", 'widgetwrangler'), array(&$this, "meta_box_advanced_help"), "widget", "normal", "high");
    add_meta_box("ww-widget-preview", __("Widget Preview", 'widgetwrangler'), array(&$this, "meta_box_widget_preview"), "widget", "side", "default");
  }
  
  /*
   * Custom columns for the main Widgets management page
   */
  function edit_columns($columns)
  {
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => __("Widget Title", 'widgetwrangler'),
      "ww_type" => __("Type", 'widgetwrangler'),
      "ww_description" => __("Description", 'widgetwrangler'),
      "ww_rewrite_output" => __("Rewrite Output", 'widgetwrangler'),
			"ww_shortcode" => __("Shortcode", 'widgetwrangler'),
    );

    return $columns;
  }
  /*
   * Handler for custom columns
   */
  function custom_columns($column)
  {
    global $post;
    
    switch ($column){
      case "ww_type":
        $widget_type = get_post_meta($post->ID, 'ww-widget-type', true);
        print ($widget_type) ? $widget_type : "standard";
        break;
      case "ww_description":
        the_excerpt();
        break;
      case "ww_rewrite_output":
        $rewrite = get_post_meta($post->ID, 'ww-adv-enabled', true);
        print ($rewrite) ? $rewrite : "&nbsp;";
        break;
			case "ww_shortcode":
				print "[ww_widget  id=".$post->ID."]<hr />[ww_widget slug=\"".$post->post_name."\"]";
				break;
    }
  }

  /*
   * When a post is inserted or updated
   * - should only be called when saving a widget post type
   */
  function wp_insert_post($post_id, $post = null)
  {
    //Check if this call results from another event, like the "Quick Edit" option
    // http://wordpress.org/support/topic/quickedit-deletes-code-in-advanced-parsing
		if (isset($_REQUEST['_inline_edit'])) { return; }
    
    // Loop through the public meta fields ($this->meta_box_fields) for $_POST data
    foreach ($this->meta_box_fields as $key){
      $value = @$_POST['ww-data'][$key];

      // If value is a string it should be unique
      if (!is_array($value))
      {
        // custom template suggestion filtering
        if ($key == "ww-custom-template-suggestion"){
          $value = sanitize_key($value);
        }
        
        // Update meta
        update_post_meta($post_id, $key, trim($value));
      }
      else {
        // If passed along as an array, we should remove all previous data
        delete_post_meta($post_id, $key);

        // Loop through the array adding new values to the post meta as different entries with the same name
        foreach ($value as $entry){
          add_post_meta($post_id, $key, trim($entry));
        }
      }
    }
    
    // update clone instance
    if ($this->widget_type == "clone"){
      $instance = $this->make_clone_instance($_POST);
      $old_instance = get_post_meta($post_id, 'ww-clone-instance', true);
      
      // let the widget update itself
      $classname = $_POST['ww-data']['clone']['clone-class'];
      if (class_exists($classname)) {
        $wp_widget = new $classname;
        $instance = $wp_widget->update($instance, $old_instance);
      }

      $instance['ID'] = $post_id;
      if (isset($_POST['ww-data']['clone']['hide_title'])){
        $instance['hide_title'] = $_POST['ww-data']['clone']['hide_title'];
      } else if (isset($instance['hide_title'])) {
        unset($instance['hide_title']);
      }
      
      delete_post_meta($post_id, 'ww-clone-instance');
      add_post_meta($post_id, 'ww-clone-instance', $instance);
    }
  }

  /*
   * Read the clone instance data from WW post type widget form
   */
  function make_clone_instance($posted){
    global $wp_widget_factory;
    if (isset($posted['ww-data']['clone'])) {
      $clone_class = $posted['ww-data']['clone']['clone-class'];
      $option_name = "widget-".$wp_widget_factory->widgets[$clone_class]->control_options['id_base'];
      $instance = array();
    
      // loop through instance values and create an instance array
      foreach($posted[$option_name] as $i => $settings){
        foreach($settings as $key => $value){
          $instance[$key] = $value;
        }
      }
      
      return $instance;
    }
    return false;
  }
	
  /*
	 * Clone Instance
	 */ 
  function meta_box_clone_instance()
  {
    // get widget factory and post data
    global $wp_widget_factory;

    $wp_widget_classname = get_post_meta($this->post_id,'ww-clone-classname', true);
    $wp_widget_instance = get_post_meta($this->post_id, 'ww-clone-instance', true);
    $ww_hide_from_wrangler = get_post_meta( $this->post_id, 'ww-hide-from-wrangler', true);
    
    if($wp_widget_classname)
    {
      // create instance form
      ob_start();
        $wp_widget = new $wp_widget_classname;
        $wp_widget->form($wp_widget_instance);
      $instance_form = ob_get_clean();
        
      $hide_title_checked = (isset($wp_widget_instance['hide_title'])) ? 'checked="checked"' : '';
      ?>
        <p>
          <label>
            <input type="checkbox" name="ww-data[clone][hide_title]" <?php print $hide_title_checked; ?> /> <?php _e("Hide the Widget's title on display", 'widgetwrangler'); ?>
          </label>
        </p>
        <p>
          <label>
            <input type="checkbox" name="ww-data[ww-hide-from-wrangler]" <?php checked($ww_hide_from_wrangler, 'on', 1); ?> /> <?php _e("Hide the Widget from the drag and drop Wrangler.", 'widgetwrangler'); ?>
            <br /><em><?php _e("This is useful for widgets that are only meant to be used as shortcodes.", 'widgetwrangler'); ?></em>
          </label>
        </p>
        <div class="ww-clone-form">
          <?php print $instance_form; ?>
        </div>
				<input type="hidden" name="ww-data[clone][clone-class]" value="<?php print $wp_widget_classname; ?>" />
        <input type="hidden" name="ww-data[clone][clone-instance]" value="Placeholder" />
      <?php
    }
  }
  
  /*
   * Widgetv preview box
   */ 
  function meta_box_widget_preview(){
    if ($this->post_id)
    {
      // queries in widgets can cause problems, even when executed correctly
      global $post;
      $tmp = clone $post;

      // buffer all of this in case of php errors
      ob_start();
        $widget = $this->ww->get_single_widget($this->post_id);
        $widget->in_preview = TRUE;
        $preview_corral_slug = get_post_meta($this->post_id, 'ww-preview-corral-slug', TRUE);
        
        // set some data so the preview is as accurate as possible
        if ($preview_corral_slug){
          global $wp_registered_sidebars;
          $corral_sidebar_map = $this->ww->display->corrals_to_wpsidebars_map();
          
          // get the sidebar widget args if context is set
          if (isset($corral_sidebar_map[$preview_corral_slug]) && isset($wp_registered_sidebars[$corral_sidebar_map[$preview_corral_slug]])){
            $this_sidebar = $wp_registered_sidebars[$corral_sidebar_map[$preview_corral_slug]];
            
            // get the id of the corral selected if it exists within the sidebar
            $sidebars_widgets = wp_get_sidebars_widgets();
            if (isset($sidebars_widgets[$this_sidebar['id']])){
              $corral_widgets = $sidebars_widgets[$this_sidebar['id']];
            }
            $widget->wp_widget_args = $this_sidebar;
            $widget->wp_widget_args['before_widget'] = sprintf($this_sidebar['before_widget'], $corral_widgets[0], 'widget-wrangler-widget-classname');
            // replace id and classes as done during output
            $widget = $this->ww->display->_replace_wp_widget_args($widget);
          }
          
          $this->ww->display->doing_corral = TRUE;
          $this->ww->display->doing_corral_slug = $preview_corral_slug;
        }
        print $this->ww->display->theme_single_widget($widget);
      $preview = ob_get_clean();
      
			$preview_balance = balanceTags($preview, true);
      ?>
        <div id="ww-preview">
          <label><strong><?php _e("Preview Corral Context", 'widgetwrangler'); ?>:</strong></label>
          <p><em><?php _e("This setting only affects the preview on this page, and helps provide accurate template suggestions.", 'widgetwrangler'); ?></em></p>
          <select id="ww-preview-corral" name="ww-data[ww-preview-corral-slug]" class="widefat" style="width:100%;">
            <option value='0'>- <?php _e("No Corral", 'widgetwrangler'); ?> -</option>
              <?php
                foreach($this->ww->corrals as $corral_slug => $corral)
                {
                  $selected = (isset($this->ww->display->doing_corral_slug) && $corral_slug == $this->ww->display->doing_corral_slug) ? 'selected="selected"': "";
                  ?>
                  <option <?php print $selected; ?> value="<?php print $corral_slug; ?>"><?php print $corral; ?></option>
                  <?php
                }
              ?>
          </select>
          <p><em><?php _e("This preview does not include your theme's CSS stylesheet, nor corral or sidebar styling.", 'widgetwrangler'); ?></em></p>
        </div>
        <hr />
        <?php	print $preview_balance; ?>
          
				<?php if ($preview != $preview_balance) { ?>
					<div style="border-top: 1px solid #bbb; margin-top: 12px; padding-top: 8px;">
						<span style="color: red; font-style: italic;"><?php _e("Your widget may contain some broken or malformed html.", 'widgetwrangler'); ?></span> <?php _e("Wordpress balanced the tags in this preview in an attempt to prevent the page from breaking, but it will not do so on normal widget display.", 'widgetwrangler'); ?>
					</div>
				<?php } ?>
        <hr />
        <div id="ww-preview-html-toggle"><?php _e("View Output HTML"); ?></div><pre id="ww-preview-html-content"><?php
          print htmlentities($preview); ?></pre>
      <?php

      // restore original post
      $post = $tmp;
    }
  }

  // helper function for override html element select 
  function _make_override_element_select($fields, $key)
  { ?>
      <select name="ww-data[<?php print $key; ?>]" class="ww-element-select">
        <option value="">- Use Default -</option>
        <option value="_none_">- None -</option>
        <?php
          $trimmed_value = trim($fields[$key]['value']);
          
          // make sure that if an element is later changed, we can still retain the current value
          if (!empty($trimmed_value) &&
              !in_array($fields[$key]['value'], $this->ww->settings['override_elements']))
          {
            $this->ww->settings['override_elements'][] = $fields[$key]['value'];
          }
          
          foreach ($this->ww->settings['override_elements'] as $element){
            $selected = ($fields[$key]['value'] == $element) ? 'selected="selected"': '';
            ?>
            <option value="<?php print $element; ?>" <?php print $selected; ?>><?php print strtoupper($element); ?></option>
            <?php
          }
        ?>
      </select>  
    <?php
  }
  
  // Admin post meta contents
  function meta_box_parse()
  {
    $fields = array();
    foreach ($this->meta_box_fields as $i => $key){
      // get values
      $fields[$key]['value'] = (isset($this->widget_meta[$key])) ? $this->widget_meta[$key][0] : NULL;
      // for checkboxes
      $fields[$key]['checked'] = ($fields[$key]['value']) ? 'checked="checked"' : '';
    }
    
    // ww-wpautop defaults to checked
    if (!isset($_GET['action']) && (isset($_GET['post_type']) && $_GET['post_type'] == 'widget')){
      $fields['ww-wpautop']['checked'] = 'checked="checked"';
    }
    
    ?><div id="ww-template">
        <div class="ww-widget-postid"><?php _e("Post ID"); ?><br/><span><?php print $this->post_id;?></span></div>
        <p>
          <label><input type="checkbox" name="ww-data[ww-wpautop]" <?php print $fields['ww-wpautop']['checked']; ?> /> <?php _e("Automatically add Paragraphs to this Widget's Content", 'widgetwrangler'); ?></label>
        </p>
        <p>
          <label><input type="checkbox" name="ww-data[ww-hide-title]" <?php print $fields['ww-hide-title']['checked']; ?> /> <?php _e("Hide this widget's title on output.", 'widgetwrangler'); ?></label>
        </p>
        <p>
          <label>
            <input type="checkbox" name="ww-data[ww-hide-from-wrangler]" <?php print $fields['ww-hide-from-wrangler']['checked']; ?> /> <?php _e("Hide the Widget from the drag and drop Wrangler.", 'widgetwrangler'); ?>
            <br /><em><?php _e("This is useful for widgets that are only meant to be used as shortcodes.", 'widgetwrangler'); ?></em>
          </label>
        </p>
        <hr />
        <div>
          <h4><?php _e("Advanced Parsing", 'widgetwrangler'); ?></h4>
          <div id="ww-advanced-field">
            <p>
              <label><input id="ww-adv-parse-toggle" type="checkbox" name="ww-data[ww-adv-enabled]" <?php print $fields['ww-adv-enabled']['checked']; ?> /> <?php _e("Enable Advanced Parsing", 'widgetwrangler'); ?></label>
            </p>
            <div id="ww-adv-parse-content">
              <p id="ww-advanced-template">
                <label><input id="ww-adv-template-toggle" type="checkbox" name="ww-data[ww-adv-template]" <?php print $fields['ww-adv-template']['checked']; ?> /> <?php _e("Template the Advanced Parsing Area", 'widgetwrangler'); ?></label> <em>(<?php _e("Do not use with Cloned Widgets.  Details below", 'widgetwrangler'); ?></em>
              </p>
              <div>
                <textarea name="ww-data[ww-parse]" cols="40" rows="16" style="width: 100%;"><?php print htmlentities($fields['ww-parse']['value']); ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <?php if ($this->ww->_check_license()) { ?>
          <hr />
          <h4><?php _e("Override HTML Output", 'widgetwrangler'); ?></h4>
          <p class="description"><?php _e("Alter the html output of a templated widget.  Doesn't apply to advanced parsing unless templating is selected.", 'widgetwrangler'); ?></p>
          <p>
            <label><input type="checkbox" id="ww-override-html-toggle" name="ww-data[ww-override-output-html]" value="1" <?php print $fields['ww-override-output-html']['checked']; ?> /> <?php _e("Override the HTML output of this widget with the values below.  This will take precendence over theme compatibility.", 'widgetwrangler'); ?></label>
          </p>
          <div id="ww-override-html-content" class="ww-override-html">
            <div>
              <label><?php _e("Wrapper Element", 'widgetwrangler'); ?></label>
              <p><?php $this->_make_override_element_select($fields, 'ww-html-wrapper-element'); ?></p>
            </div>
            <div>
              <label><?php _e("Wrapper ID", 'widgetwrangler'); ?></label>
              <p><input type="text" size="30" name="ww-data[ww-html-wrapper-id]" value="<?php print $fields['ww-html-wrapper-id']['value']; ?>"/></p>
            </div>
            <div>
              <label><?php _e("Wrapper Classes", 'widgetwrangler'); ?></label>
              <p class="description"><?php _e("Separate multiple classes with spaces.", 'widgetwrangler'); ?></p>
              <p><input type="text" size="30" name="ww-data[ww-html-wrapper-classes]" value="<?php print $fields['ww-html-wrapper-classes']['value']; ?>"/></p>
            </div>
            <div>
              <label><?php _e("Title Element", 'widgetwrangler'); ?></label>
              <p><?php $this->_make_override_element_select($fields, 'ww-html-title-element'); ?></p>
            </div>
            <div>
              <label><?php _e("Title Classes", 'widgetwrangler'); ?></label>
              <p class="description"><?php _e("Separate multiple classes with spaces.", 'widgetwrangler'); ?></p>
              <p><input type="text" size="30" name="ww-data[ww-html-title-classes]" value="<?php print $fields['ww-html-title-classes']['value']; ?>"/></p>
            </div>
            <div>
              <label><?php _e("Content Element", 'widgetwrangler'); ?></label>
              <p><?php $this->_make_override_element_select($fields, 'ww-html-content-element'); ?></p>
            </div>
            <div>
              <label><?php _e("Content Classes", 'widgetwrangler'); ?></label>
              <p class="description"><?php _e("Separate multiple classes with spaces.", 'widgetwrangler'); ?></p>
              <p><input type="text" size="30" name="ww-data[ww-html-content-classes]" value="<?php print $fields['ww-html-content-classes']['value']; ?>"/></p>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php
  }

  /*
   * Display Logic
   */
  function meta_box_display_logic()
  {
    $fields = array();
    foreach ($this->meta_box_fields as $i => $key){
      // get values
      $fields[$key]['value'] = (isset($this->widget_meta[$key])) ? $this->widget_meta[$key][0] : NULL;
      // for checkboxes
      $fields[$key]['checked'] = ($fields[$key]['value']) ? 'checked="checked"' : '';
    }
    ?>
    <div>
      <p>
        <label><input id="ww-display-logic-toggle" type="checkbox" name="ww-data[ww-display-logic-enabled]" <?php print $fields['ww-display-logic-enabled']['checked']; ?> /> <?php _e("Enable Display Logic"); ?></label>
      </p>
      <div id="ww-display-logic-content">
        <p class="description"><?php _e("Site-wide raw PHP logic for displaying this widget", 'widgetwrangler'); ?></p>
        <div>
          <textarea name="ww-data[ww-display-logic]" cols="40" rows="5" style="width: 100%;"><?php print htmlentities($fields['ww-display-logic']['value']); ?></textarea>
        </div>
      </div>
    </div>
    <?php
  }

	/*
	 * Advanced Help
	 */
	function meta_box_advanced_help()
	{
		if ($widget = $this->ww->get_single_widget($this->post_id)){
      
			$preview_corral_slug = get_post_meta($this->post_id, 'ww-preview-corral-slug', TRUE);
      if ($preview_corral_slug){
        $this->ww->display->doing_corral = TRUE;
        $this->ww->display->doing_corral_slug = $preview_corral_slug;
      }
      
      $args = array(
        'widget' => $widget, // needed in final template
        'widget_id' => $widget->ID,
        'post_name' => $widget->post_name,
        'widget_type' => $widget->widget_type,
        //'corral_id' => (isset($widget->widget_data) && isset($widget->widget_data['preview-corral-id'])) ? $widget->widget_data['preview-corral-id'] : 0,
        'tw_action'  => 'find_only',
      );
      
      if ($preview_corral_slug){
        $args['corral_slug'] = $preview_corral_slug;
      }
      
      $a = theme('ww_widget', $args);
    
      $suggestions = "";
      if (isset($a['suggestions'])){
        foreach($a['suggestions'] as $i => $suggestion) {
          // we can't detect corral here
          //$suggestion = str_replace("corral_0", "corral_[corral_id]", $suggestion);
          $class = (isset($a['found_suggestion']) && $suggestion == $a['found_suggestion']) ? "ww-template-suggestion found" : "ww-template-suggestion";
          $suggestions.= "<li class='$class'>".$suggestion."</li>";
        }
      }
    }
			?>
      <div class="adv-parse-description">
        <div id="ww-advanced-help">
          <div class="ww-advanced-help-description adv-parse-description">
            <?php
              //  only show adv parsing help on standard
              if ($this->widget_type != "clone"){ ?>
                <h4><?php _e("In the Advanced Parsing area you can", 'widgetwrangler'); ?>:</h4>
                <ul>
                  <li><?php _e("Use PHP tags ( &lt;?php and ?&gt; are required )", 'widgetwrangler'); ?></li>
                  <li><?php _e("Use {{title}} or \$widget->post_title to insert the widget's title", 'widgetwrangler'); ?></li>
                  <li><?php _e("Use {{content}} or \$widget->post_content to insert the widget's content", 'widgetwrangler'); ?></li>
                  <li><?php _e("Access the \$widget object for more widget data (see provided template for examples)", 'widgetwrangler'); ?></li>
                  <li><?php _e("Access the \$post object for data concerning the page being displayed (see provided template for examples)", 'widgetwrangler'); ?></li>
                </ul>
                <h4><?php _e("Templating Advanced Parsed Widgets", 'widgetwrangler'); ?></h4>
                <ul>
                  <li><?php _e("To template an advanced parsed widget you must return an associative array with a title and content string.", 'widgetwrangler'); ?></li>
                  <li><?php _e("Example", 'widgetwrangler'); ?>: <code>&lt;?php return array("title" => "The Widget's Title", "content" => "The Widget's Content"); ?&gt;</code></li>
                </ul>
                <?php
              }
            ?>
              <h4><?php _e("Display Logic", 'widgetwrangler'); ?></h4>
              <ul>
                <li><?php _e("Do NOT use &lt;?php and ?&gt; tags.  This field is for raw PHP.", 'widgetwrangler'); ?></li>
                <li><?php _e("Evaluate boolean true or false with php. Defaults to TRUE.", 'widgetwrangler'); ?></li>
                <li><?php _e("For simple logic, execute your conditions directly.  (For example, use Wordpress Conditional Tags such as 'is_search()' or 'is_404()' ).", 'widgetwrangler'); ?></li>
                <li><?php _e("For complex logic, return TRUE or FALSE as needed.", 'widgetwrangler'); ?></li>
              </ul>
            <?php
              if (isset($suggestions)) { ?>
              
                <?php if ($this->ww->_check_license()){ ?>
                  <h4><?php _e("Custom template suggestion", 'widgetwrangler'); ?></h4>
                  <ul class="ww-custom-template-suggestion">
                    <li><?php _e("Define a custom template name for this widget.", 'widgetwrangler'); ?></li>
                    <li><label>widget-<input type="text" maxlength="64" size="32" name="ww-data[ww-custom-template-suggestion]" value="<?php print $widget->custom_template_suggestion; ?>" />.php</label></li>
                    <li><?php _e("Lowercase alphanumeric characters, dashes and underscores are allowed.", 'widgetwrangler'); ?></li>
                    <li><?php _e("Uppercase characters will be converted to lowercase.", 'widgetwrangler'); ?></li>
                    <li><?php _e("If defined, the custom suggestion will take precedence.", 'widgetwrangler'); ?></li>
                  </ul>
                <?php } ?>
                
                <h4><?php _e("Template Suggestions", 'widgetwrangler'); ?></h4>
                <p class="description"><?php _e("Corral specific templates will not be detected here unless you set the 'Preview Corral Context' in the preview pane.", 'widgetwrangler'); ?></p>
                <ul><?php print $suggestions; ?></ul>
              <?php
                if (isset($a['found_path']) && $a['found_path'])
                { ?>
                    <h4><?php _e("Found template location", 'widgetwrangler'); ?></h4>
                    <div class='ww-found-template-location'><?php print str_replace(ABSPATH, "/", $a['found_path']); ?></div>
                  <?php
                }
                
                // provide some clone instance debugging
                if ($this->widget_type == "clone")
                {
                  $clone_instance = unserialize($this->widget_meta['ww-clone-instance'][0]);
                  ?>
                  <div>
                    <h4>WP_Widget $instance</h4>
                    <div><pre style="font-size: 0.9em;"><?php print htmlentities(print_r($clone_instance,1)); ?></pre></div>
                  </div>
                  <?php
                }
              }
            ?>
          </div>
        </div>
      </div>
		<?php
	}    
}
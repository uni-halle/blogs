<?php
/* 
Plugin Name: Font Resizer
Plugin URI: http://www.cubetech.ch/products/font-resizer
Description: Font Resizer with jQuery and Cookies
Author: cubetech.ch
Version: 1.3.2
Author URI: http://www.cubetech.ch/
*/

    # Add the options/actions to WordPress (if they doesn't exist)

    add_action('admin_menu', 'fontResizer_addAdminPage');
    add_option('fontResizer', 'body', '', 'yes');
    add_option('fontResizer_ownid', '', '', 'yes');
    add_option('fontResizer_ownelement', '', '', 'yes');
    add_option('fontResizer_resizeSteps', '1.6', '', 'no');
    add_option('fontResizer_cookieTime', '31', '', 'no');
    add_option('fontResizer_maxFontsize', '', '', 'yes');

    # Register an administration page

    function fontResizer_addAdminPage() {
        add_options_page('font-resizer Options', 'font-resizer', 'edit_pages', 'font-resizer', 'fontResizer_aMenu');
    }

    # Generates the administration menu

    function fontResizer_aMenu() {
	?>
	<div class="wrap">
	    <h2>font-resizer</h2>
	    <form method="post" action="options.php">
	    <?php wp_nonce_field('update-options'); ?>
	    <table class="form-table">
		<tr valign="top">
		    <th scope="row"><?php _e('Basic Settings', 'font-resizer'); ?></th>
		    <td>
			<label for="fr_div">
			    <input type="radio" name="fontResizer" value="body" <?php if(get_option('fontResizer')=="body") echo "checked"; ?> />
			    <?php _e('Default setting, resize whole content in body tag (&lt;body&gt;All content of your site&lt;/body&gt;)', 'font-resizer'); ?>
			</label><br />
			<label for="fr_div">
			    <input type="radio" name="fontResizer" value="innerbody" <?php if(get_option('fontResizer')=="innerbody") echo "checked"; ?> />
			    <?php _e('Use div with id innerbody (&lt;div id="innerbody"&gt;Resizable text&lt;/div&gt;)', 'font-resizer'); ?>
			</label><br />
			<label for="fr_div">
			    <input type="radio" name="fontResizer" value="ownid" <?php if(get_option('fontResizer')=="ownid") echo "checked"; ?> /> <input type="text" name="fontResizer_ownid" value="<?php echo get_option('fontResizer_ownid'); ?>" /><br />
			    <?php _e('Use your own div id (&lt;div id="yourid"&gt;Resizable text&lt;/div&gt;)', 'font-resizer'); ?>
			</label><br />
			<label for="fr_div">
			    <input type="radio" name="fontResizer" value="ownelement" <?php if(get_option('fontResizer')=="ownelement") echo "checked"; ?> /> <input type="text" name="fontResizer_ownelement" value="<?php echo get_option('fontResizer_ownelement'); ?>" /><br />
			    <?php _e('Use your own element (For example: for a span with class "bla" (&lt;span class="bla"&gt;Resizable text&lt;/span&gt;), enter the css definition, "span.bla" (without quotes))', 'font-resizer'); ?>
			</label><br />
		    </td>
		</tr>
		<tr valig="top">
		    <th scope="row"><?php _e('Resize Steps', 'font-resizer'); ?></th>
		    <td>
		        <label for="resizeSteps">
		            <input type="text" name="fontResizer_resizeSteps" value="<?php echo get_option('fontResizer_resizeSteps'); ?>" style="width: 3em"><b>px</b> 
		            <br /><?php _e('Set the resize steps in pixel (default: 1.6px)', 'font-resizer'); ?>
		        </label>
		    </td>
		</tr>
		<tr valig="top">
		    <th scope="row">Font Size</th>
		    <td>
		        <label for="cookieTime">
		            <input type="text" name="fontResizer_maxFontsize" value="<?php echo get_option('fontResizer_maxFontsize'); ?>" style="width: 3em"><b>px</b> 
		            <br /><?php _e('Set the maximum font size (default: no limit, 0 or empty means no limit)', 'font-resizer'); ?>
		        </label>
		    </td>
		</tr>
		<tr valig="top">
		    <th scope="row">Cookie Settings</th>
		    <td>
		        <label for="cookieTime">
		            <input type="text" name="fontResizer_cookieTime" value="<?php echo get_option('fontResizer_cookieTime'); ?>" style="width: 3em"> <b>days</b> 
		            <br /><?php _e('Set the cookie store time (default: 31 days)', 'font-resizer'); ?>
		        </label>
		    </td>
		</tr>
	    </table>
	    <input type="hidden" name="action" value="update" />
	    <input type="hidden" name="page_options" value="fontResizer,fontResizer_ownid,fontResizer_ownelement,fontResizer_resizeSteps,fontResizer_cookieTime,fontResizer_maxFontsize" />
	    <p class="submit">
	    	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	    </form>
	</div>
	<?php	
    }
    
    # Sort the dependencies

    function fontResizer_sortDependencys(){
    	$font_resizer_path = plugins_url( '/js/', __FILE__ );
        wp_register_script('fontResizer', $font_resizer_path.'jquery.fontsize.js');
        wp_register_script('fontResizerCookie', $font_resizer_path.'jquery.cookie.js');
        wp_register_script('fontResizerPlugin', $font_resizer_path.'main.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('fontResizerCookie');
        wp_enqueue_script('fontResizer');
        wp_enqueue_script('fontResizerPlugin');
    }
    
    # Generate the font-resizer text

    function fontResizer_place(){
		echo '<ul><li class="fontResizer" style="text-align: center; font-weight: bold;">';
		echo '<a class="fontResizer_minus" href="#" title="' . __('Decrease font size', 'font-resizer') . '" style="font-size: 0.7em;">A</a> ';
		echo '<a class="fontResizer_reset" href="#" title="' . __('Reset font size', 'font-resizer') . '">A</a> ';
		echo '<a class="fontResizer_add" href="#" title="' . __('Increase font size', 'font-resizer') . '" style="font-size: 1.2em;">A</a> ';
		echo '<input type="hidden" id="fontResizer_value" value="'.get_option('fontResizer').'" />';
		echo '<input type="hidden" id="fontResizer_ownid" value="'.get_option('fontResizer_ownid').'" />';
		echo '<input type="hidden" id="fontResizer_ownelement" value="'.get_option('fontResizer_ownelement').'" />';
		echo '<input type="hidden" id="fontResizer_resizeSteps" value="'.get_option('fontResizer_resizeSteps').'" />';
		echo '<input type="hidden" id="fontResizer_cookieTime" value="'.get_option('fontResizer_cookieTime').'" />';
		echo '<input type="hidden" id="fontResizer_maxFontsize" value="'.get_option('fontResizer_maxFontsize').'" />';
		echo '</li></ul>';
    }
	
	# Creating the widget

    function fontresizer_widget($args) {
        extract($args);
        fontResizer_place();
    }

    add_action('init', 'fontResizer_sortDependencys');
	
	# Register sidebar function
	
    wp_register_sidebar_widget('fontresizer_widget', 'Font Resizer','fontresizer_widget');

    # Register uninstall function

    register_uninstall_hook(__FILE__, 'fontResizer_uninstaller');
    
    # This function deletes the options when you uninstall the plugin

    function fontResizer_uninstaller() {
    	delete_option('fontResizer');
    	delete_option('fontResizer_ownid');
    	delete_option('fontResizer_ownelement');
    	delete_option('fontResizer_resizeSteps');
    }

?>

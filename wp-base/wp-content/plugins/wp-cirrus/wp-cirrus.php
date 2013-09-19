<?php
/*
	Plugin Name: WP-Cirrus
	Plugin URI: http://www.ga-ap.de/plugins/wp-cirrus/
	Description: A 3d javascript tagcloud
	Version: 0.6.11
	Author: Christian Kramer & Hendrik Thole
	Author URI: http://www.ga-ap.de
	
	Copyright 2010 - 2013, Christian Kramer & Hendrik Thole
	
	This file is part of WP-Cirrus Plugin.
	
	WP-Cirrus is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
    
	WP-Cirrus is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
    
	You should have received a copy of the GNU General Public License
	along with WP-Cirrus. If not, see <http://www.gnu.org/licenses/>.


*/

define("WP_CIRR_VERSION", "0.6.11");

function wpcirrusShortCodeHandler($atts=NULL){
	wpcirrusInit(true, $atts);
}

function wpcirrusWidgetHandler(){
	
	function wpcirrusWidgetInit($args){
		extract($args);
		$options = get_option('wpcirrus-widget');
		
		echo $before_widget . $before_title . $options['title'] . $after_title;
		
		wpcirrusInit(false, $args);
		
		echo $after_widget;
		
	}
	
	function wpcirrusWidgetOptions(){
		
		$errors;
		
		if ( $_POST['wpcirrus-options-submit'] ) {
			$options_pre = array();
			$options_pre['title'] = strip_tags(stripslashes($_POST["wpcirrus-title"]));
			$options_pre['width'] = strip_tags(stripslashes($_POST["wpcirrus-width"]));
			$options_pre['height'] = strip_tags(stripslashes($_POST["wpcirrus-height"]));
			$options_pre['refreshrate'] = strip_tags(stripslashes($_POST["wpcirrus-refreshrate"]));
			$options_pre['radius'] = strip_tags(stripslashes($_POST["wpcirrus-radius"]));
			$options_pre['number'] = strip_tags(stripslashes($_POST['wpcirrus-number']));
			$options_pre['mode'] = strip_tags(stripslashes($_POST['wpcirrus-mode']));
			$options_pre['smallest'] = strip_tags(stripslashes($_POST['wpcirrus-smallest']));
			$options_pre['biggest'] = strip_tags(stripslashes($_POST['wpcirrus-biggest']));
			$options_pre['orderBy'] = strip_tags(stripslashes($_POST['wpcirrus-orderBy']));
			$options_pre['sortOrder'] = strip_tags(stripslashes($_POST['wpcirrus-sortOrder']));
			$options_pre['fontcolor'] = strip_tags(stripslashes($_POST['wpcirrus-fontcolor']));
			$options_pre['backgroundcolor'] = strip_tags(stripslashes($_POST['wpcirrus-backgroundcolor']));
						
			$errors = wpcirrusValidateOptions($options_pre);

			if(empty($errors)){
				update_option('wpcirrus-widget', $options_pre);
			}
		}
		
		$options = get_option('wpcirrus-widget');
		
		?>
		<?php
		$styleError = "";
		if ($errors){
			while(!empty($errors)){
				echo "<div id='errorMessage' class='error fade'><p>".array_pop($errors)."</p></div>";
			}
			$styleError = "background-color: rgb(255, 235, 232); border-color: rgb(204, 0, 0); border-style: solid; border-width: 1px;";
			
		}
		?>
		<div id="error" style="<?php echo $styleError?>">
		<h3>Cloud settings</h3>
		<p>
			<label for="wpcirrus-title"><?php _e('Title:'); ?> <input class="widefat" id="wpcirrus-title" name="wpcirrus-title" type="text" value="<?php echo $options['title'];?>" /></label>
			<small></small>
		</p>
		<p>
			<label for="wpcirrus-height"><?php _e('Height:'); ?> <input class="widefat" id="wpcirrus-height" name="wpcirrus-height" type="text" value="<?php echo $options['height'];?>" /></label>
			<small>in px</small>
		</p>
		<p>
			<label for="wpcirrus-width"><?php _e('Width:'); ?> <input class="widefat" id="wpcirrus-width" name="wpcirrus-width" type="text" value="<?php echo $options['width'];?>" /></label>
			<small>in px</small>
		</p>
		<p>
			<label for="wpcirrus-refreshrate"><?php _e('Refreshrate:'); ?><input class="widefat" id="wpcirrus-refreshrate" name="wpcirrus-refreshrate" type="text" value="<?php echo $options['refreshrate'];?>" /></label>
			<small>in ms, 40 = 25fps, 0 = default (30ms)</small>
		</p>
		<p>
			<label for="wpcirrus-radius"><?php _e('Radius:'); ?> <input class="widefat" id="wpcirrus-radius" name="wpcirrus-radius" type="text" value="<?php echo $options['radius'];?>" /></label>
			<small>in px, 0 = will be automatically determined</small>
		</p>
		<h3>Item settings</h3>
		<p>
			Type of cloud items:<br/>
			<label><input type="radio" class="radio" name="wpcirrus-mode" id="tags" value="post_tag"<?php if ($options['mode'] == 'post_tag'){?>checked<?php } ?>>Tags</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-mode" id="categories" value="category"<?php if ($options['mode'] == 'category'){?>checked<?php } ?>>Categories</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-mode" id="both" value="both"<?php if ($options['mode'] == 'both'){?>checked<?php } ?>>Both</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-mode" id="pages" value="pages"<?php if ($options['mode'] == 'pages'){?>checked<?php } ?>>Pages</label><br/>
			<small>Choose if you want to see categories or tags in cloud</small>
		</p>
		<p>
			Item sorting:<br/>
			<label><input type="radio" class="radio" name="wpcirrus-orderBy" value="name"<?php if ($options['orderBy'] == 'name'){?>checked<?php } ?>>Name</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-orderBy" value="count"<?php if ($options['orderBy'] == 'count'){?>checked<?php } ?>>Count</label><br/>
			<small>Choose which order the items should have</small>
		</p>
		<p>
			Sort order of items:<br/>
			<label><input type="radio" class="radio" name="wpcirrus-sortOrder" value="ASC"<?php if ($options['sortOrder'] == 'ASC'){?>checked<?php } ?>>Ascending</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-sortOrder" value="DESC"<?php if ($options['sortOrder'] == 'DESC'){?>checked<?php } ?>>Descending</label><br/>
			<label><input type="radio" class="radio" name="wpcirrus-sortOrder" value="RAND"<?php if ($options['sortOrder'] == 'RAND'){?>checked<?php } ?>>Random</label><br/>
			<small>Choose which sort order the items should have</small>
		</p>
		<p>
			<label for="wpcirrus-smallest"><?php _e('Smallest:')?><input class="widefat" id="wpcirrus-smallest" name="wpcirrus-smallest" value="<?php echo $options['smallest'];?>" type="text" /></label>
			<small>The size of the smallest item means the size of the fewest used item.</small>
		</p>
		<p>
			<label for="wpcirrus-biggest"><?php _e('Biggest:')?><input class="widefat" id="wpcirrus-biggest" name="wpcirrus-biggest" value="<?php echo $options['biggest'];?>" type="text" /></label>
			<small>The size of the biggest item means the size of the most used item.</small>
		</p>
		<p>
			<label for="wpcirrus-fontcolor"><?php _e('Font color:')?><input class="widefat" id="wpcirrus-fontcolor" name="wpcirrus-fontcolor" value="<?php echo $options['fontcolor'];?>" type="text" /></label>
			<small>in valid hex (i.e. #000000 means black). If you want to use the template color's just enter nothing here (default).</small>
		</p>
		<p>
			<label for="wpcirrus-backgroundcolor"><?php _e('Background color:')?><input class="widefat" id="wpcirrus-backgroundcolor" name="wpcirrus-backgroundcolor" value="<?php echo $options['backgroundcolor'];?>" type="text" /></label>
			<small>in valid hex (i.e. #000000 means black). If you want to use the template color's just enter nothing here (default).</small>
		</p>		
		<p>
			<label for="wpcirrus-args">Number of items<input class="widefat" id="wpcirrus-number" name="wpcirrus-number" type="text" value="<?php echo $options['number'];?>" /></label>
			<small></small>
		</p>
		
		<input type="hidden" id="wpcirrus-options-submit" name="wpcirrus-options-submit" value="true" />
		</div>

	<?php
	}
	
	wp_register_sidebar_widget('wpcirrus','WP Cirrus', 'wpcirrusWidgetInit');
	wp_register_widget_control('wpcirrus','WP Cirrus', 'wpcirrusWidgetOptions' );
}

function wpcirrusAdminMenuHandler(){
	function wpcirrusOptionsHandler(){
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}		
		
		
		$errors;
		
		if($_POST['wpcirrus-options-submit']){
			$options_pre = array();
			$options_pre['height'] = strip_tags(stripslashes($_POST['height']));
			$options_pre['width'] = strip_tags(stripslashes($_POST['width']));
			$options_pre['refreshrate'] = strip_tags(stripslashes($_POST['refreshrate']));
			$options_pre['radius'] = strip_tags(stripslashes($_POST['radius']));
			$options_pre['number'] = strip_tags(stripslashes($_POST['number']));
			$options_pre['mode'] = strip_tags(stripslashes($_POST['mode']));
			$options_pre['smallest'] = strip_tags(stripslashes($_POST['smallest']));
			$options_pre['biggest'] = strip_tags(stripslashes($_POST['biggest']));
			$options_pre['orderBy'] = strip_tags(stripslashes($_POST['orderBy']));
			$options_pre['sortOrder'] = strip_tags(stripslashes($_POST['sortOrder']));
			$options_pre['fontcolor'] = strip_tags(stripslashes($_POST['fontcolor']));
			$options_pre['backgroundcolor'] = strip_tags(stripslashes($_POST['backgroundcolor']));
			
			$errors = wpcirrusValidateOptions($options_pre);

			if(empty($errors)){
				update_option('wpcirrus-options', $options_pre);
			}
		}
		
		$options = get_option('wpcirrus-options');
	
		?>
		
		    <div class="wrap">
		      <h2>WP Cirrus settings</h2>
			<form method="post">
				<h3>Cloud settings</h3>

				<table class="form-table">
					
					<tr valign="top">
						<th scope="row"><label for="args">Height of the Tagcloud window</label></th>
						<td>
							<input name="height" value="<?php echo $options['height'];?>" type="text" />
							<span class="description">in px</span>
						</td>
						
					</tr>		    
					 <tr valign="top">
						<th scope="row"><label for="args">Width of the Tagcloud window</label></th>
						<td>
							<input name="width" value="<?php echo $options['width'];?>" type="text" />
							<span class="description">in px</span>
						</td>
					</tr>	
					<tr valign="top">
						<th scope="row"><label for="args">Refreshrate of the Tagcloud</label></th>
						<td>
							<input name="refreshrate" value="<?php echo $options['refreshrate'];?>" type="text" />
							<span class="description">in ms, 40 = 25fps, 0 = default (30ms)</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="args">Radius of the Tagcloud</label></th>
						<td>
							<input name="radius" value="<?php echo $options['radius'];?>" type="text" />
							<span class="description">in px, 0 = will be automatically determined</span>
						</td>
					</tr>
				</table>
				<br/>
				<h3>Item settings</h3>
				<?php
				if ($errors){
					while(!empty($errors)){
						echo "<div id='errorMessage' class='error fade'><p>".array_pop($errors)."</p></div>";
					}
				
				}
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="args">Type of cloud items</label></th>
						<td>
							<fieldset>
							<label title="Tags"><input type="radio" class="radio" name="mode" value="post_tag"<?php if ($options['mode'] == 'post_tag'){?>checked<?php } ?>>Tags</label><br/>
							<label title="Categories"><input type="radio" class="radio" name="mode" value="category"<?php if ($options['mode'] == 'category'){?>checked<?php } ?>>Categories</label><br/>
							<label title="Both"><input type="radio" class="radio" name="mode" value="both"<?php if ($options['mode'] == 'both'){?>checked<?php } ?>>Both</label><br/>
							<label title="Pages"><input type="radio" class="radio" name="mode" value="pages"<?php if ($options['mode'] == 'pages'){?>checked<?php } ?>>Pages</label><br/>
							</fieldset>
							<span class="description">Choose if you want to see categories or tags in cloud</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="args">Item sorting</label></th>
						<td>
							<fieldset>
							<label title="name"><input type="radio" class="radio" name="orderBy" value="name"<?php if ($options['orderBy'] == 'name'){?>checked<?php } ?>>Name</label><br/>
							<label title="count"><input type="radio" class="radio" name="orderBy" value="count"<?php if ($options['orderBy'] == 'count'){?>checked<?php } ?>>Count</label><br/>
							</fieldset>
							<span class="description">Choose which order the items should have</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="args">Sort order of items</label></th>
						<td>
							<fieldset>
							<label title="ASC"><input type="radio" class="radio" name="sortOrder" value="ASC"<?php if ($options['sortOrder'] == 'ASC'){?>checked<?php } ?>>Ascending</label><br/>
							<label title="DESC"><input type="radio" class="radio" name="sortOrder" value="DESC"<?php if ($options['sortOrder'] == 'DESC'){?>checked<?php } ?>>Descending</label><br/>
							<label title="RAND"><input type="radio" class="radio" name="sortOrder" value="RAND"<?php if ($options['sortOrder'] == 'RAND'){?>checked<?php } ?>>Random</label><br/>
							</fieldset>
							<span class="description">Choose which sort order the items should have</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="smallest">Size of smallest item</label></th>
						<td>
							<input name="smallest" value="<?php echo $options['smallest'];?>" type="text" />
							<span class="description">The size of the smallest item means the size of the fewest used item</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="biggest">Size of biggest item</label></th>
						<td>
							<input name="biggest" value="<?php echo $options['biggest'];?>" type="text" />
							<span class="description">The size of the biggest item means the size of the most used item</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="number">Number of items</label></th>
						<td>
							<input name="number" value="<?php echo $options['number'];?>" type="text" />
							<span class="description"></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="fontcolor">Font color</label></th>
						<td>
							<input name="fontcolor" value="<?php echo $options['fontcolor'];?>" type="text" />
							<span class="description">in valid hex (i.e. #000000 means black). If you want to use the template color's just enter nothing here (default).</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="backgroundcolor">Background color</label></th>
						<td>
							<input name="backgroundcolor" value="<?php echo $options['backgroundcolor'];?>" type="text" />
							<span class="description">in valid hex (i.e. #000000 means black). If you want to use the template color's just enter nothing here (default).</span>
						</td>
					</tr>					
				</table>
				<br/>
				<input type="submit" name="Submit" class="button-primary" value="<?php echo _e('Save Changes')?>"/>
				<input type="hidden" value="true" name="wpcirrus-options-submit">
			</form>
		    </div>
		   
<?php
	}

	add_options_page('WP Cirrus settings', 'WP Cirrus', 'manage_options', __FILE__, 'wpcirrusOptionsHandler');
}



function wpcirrusInit( $shortcode=false, $args){
	$tagBoxName = "";
	
	if(!$shortcode){
		$options = get_option('wpcirrus-widget');
		$tagBoxName = "cirrusCloudWidget";
	}
	if($shortcode){
		$options = get_option('wpcirrus-options');
		$tagBoxName = "cirrusCloudTagBox";
	}
	
	?>
	<script type="text/javascript">
		var wpcirrusRadius<?php echo $tagBoxName?> = <?php echo $options['radius'];?>;
		var wpcirrusRefreshrate<?php echo $tagBoxName?> = <?php echo $options['refreshrate'];?>;
		<?php
		if(!empty($options['fontcolor'])){
			echo "var wpcirrusFontColor".$tagBoxName." = '" . $options['fontcolor'] . "';";
		} else {
			echo "var wpcirrusFontColor".$tagBoxName.";";
		}
		if(!empty($options['backgroundcolor'])){
			echo "var wpcirrusBackgroundColor".$tagBoxName." = '" . $options['backgroundcolor'] . "';";
		} else {
			echo "var wpcirrusBackgroundColor".$tagBoxName.";";
		}
		?>
	</script>
	<div style="position: relative; height: <?php echo $options['height'];?>px; width: <?php echo $options['width'];?>px;"  id="<?php echo $tagBoxName?>">
		<?php
		 if($options['mode'] == 'both'){
			echo wp_tag_cloud($options['args'] = $options['args'] . "&taxonomy=post_tag&number=" . $options['number'] . "&orderby=" . $options['orderBy'] . "&order=" . $options['sortOrder'] . "&smallest=" . $options['smallest']  . "&largest=" . $options['biggest']);
			echo " " . wp_tag_cloud($options['args'] = $options['args'] . "&taxonomy=category&number=" . $options['number'] . "&orderby=" . $options['orderBy'] . "&order=" . $options['sortOrder'] . "&smallest=" . $options['smallest']  . "&largest=" . $options['biggest']);
		} else if($options['mode'] == 'pages'){
			echo wp_list_pages("echo=0&title_li=");
		} else {
			echo wp_tag_cloud($options['args'] = $options['args'] . "&taxonomy=". $options['mode'] ."&number=" . $options['number'] . "&orderby=" . $options['orderBy'] . "&order=" . $options['sortOrder'] . "&smallest=" . $options['smallest']  . "&largest=" . $options['biggest']);
		}
		?>
	</div>
	<?php
	
	
}


function wpcirrusLoadCSS(){
	$url = WP_PLUGIN_URL . '/wp-cirrus/cirrusCloud.css';
	$style = WP_PLUGIN_DIR . '/wp-cirrus/cirrusCloud.css';
	if ( file_exists($style) ) {
		wp_register_style('wpcirrus-cloudStyle', $url, array(), WP_CIRR_VERSION);
		wp_enqueue_style( 'wpcirrus-cloudStyle');
	}
}

function wpcirrusLoadScript(){
	$url = WP_PLUGIN_URL . '/wp-cirrus/wp_cirrus_gwt/wp_cirrus_gwt.nocache.js';
	$script = WP_PLUGIN_DIR . '/wp-cirrus/wp_cirrus_gwt/wp_cirrus_gwt.nocache.js';
	if ( file_exists($script) ) {
		wp_register_script('wpcirrus-cloudScript', $url, array(), WP_CIRR_VERSION);
		wp_enqueue_script('wpcirrus-cloudScript');
	}
}

function wpcirrusInstall(){
	$options['height'] = '500';
	$options['width'] = '500';
	$options['radius'] = '0';
	$options['refreshrate'] = '0';
	$options['mode'] = 'post_tag';
	$options['number'] = '20';
	$options['order'] = 'name';
	$options['smallest'] = '10';
	$options['biggest'] = '20';
	$options['orderBy'] = 'name';
	$options['sortOrder'] = 'ASC';
	$options['args'] = 'unit=pt';
	$options['fontcolor'] = '';
	$options['backgroundcolor'] = '';
	add_option('wpcirrus-options', $options);
	$options['height'] = '160';
	$options['width'] = '160';
	$options['radius'] = '0';
	$options['refreshrate'] = '0';
	$options['mode'] = 'post_tag';
	$options['number'] = '20';
	$options['order'] = 'name';
	$options['smallest'] = '10';
	$options['biggest'] = '20';
	$options['orderBy'] = 'name';
	$options['sortOrder'] = 'ASC';
	$options['args'] = 'unit=pt';
	$options['fontcolor'] = '';
	$options['backgroundcolor'] = '';
	add_option('wpcirrus-widget', $options);
}

function wpcirrusUninstall(){
	delete_option('wpcirrus-options');
	delete_option('wpcirrus-widget');
	wp_deregister_script('wpcirrus-cloudScript');
	wp_deregister_style('wpcirrus-cloudStyle');
}

function wpcirrusPluginAction($links, $file){
	if ($file == plugin_basename(__FILE__))
            $links[] = '<a href="' . admin_url("options-general.php?page=wp-cirrus/wp-cirrus.php") . '">'. __('Settings') .'</a>';
        return $links;
	
}

function wpcirrusValidateOptions($inputs){
	$fontColorErrorMessage = "The font color must be a correct hex value (i.e. #000000)";
	$backgroundColorErrorMessage = "The background color must be a correct hex value (i.e. #000000)";
	
	$errors = array();
	
	if (!empty($inputs['fontcolor'])){
		if (!preg_match('/^#[\da-fA-F]{6}$/', $inputs['fontcolor'])){
			array_push($errors, $fontColorErrorMessage);
		}
	}
	
	if (!empty($inputs['backgroundcolor'])){
		if (!preg_match('/^#[\da-fA-F]{6}$/', $inputs['backgroundcolor'])){
			array_push($errors, $backgroundColorErrorMessage);
		}
	}
	
	return $errors;
}

// create initial values and delete them on deactivation
register_activation_hook( __FILE__, 'wpcirrusInstall' );
register_deactivation_hook( __FILE__, 'wpcirrusUninstall' );

// add settings link
add_filter('plugin_action_links', 'wpcirrusPluginAction', 10, 2);

// register js and css file
add_action('init', 'wpcirrusLoadScript');
add_action('wp_print_styles', 'wpcirrusLoadCSS');

// check if shortcode is used
add_shortcode('WP-CIRRUS', 'wpcirrusShortCodeHandler');
add_shortcode('wp-cirrus', 'wpcirrusShortCodeHandler');

// register widget
add_action('widgets_init', 'wpcirrusWidgetHandler');

// register menu
add_action('admin_menu', 'wpcirrusAdminMenuHandler');



?>
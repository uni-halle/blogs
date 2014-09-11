<?php
/*
Plugin Name: Let It Snow!
Plugin URI: http://aentan.com/work/let-it-snow/
Description: Snow on your Wordpress Blog based on the DHTML Snowstorm script by <cite><a href="http://www.schillmania.com/projects/snowstorm/" title="DHTML Snowstorm">Scott Schiller</a>.</cite>
Version: 3.0
Author: Aen Tan
Author URI: http://aentan.com/
*/
function snow_options() {
	add_menu_page('Let It Snow!', 'Let It Snow!', 8, basename(__FILE__), 'snow_options_page');
	add_submenu_page(basename(__FILE__), 'Settings', 'Settings', 8, basename(__FILE__), 'snow_options_page');
}
?>
<?php function snow_options_page() { ?>

<div class="wrap">
    
    <div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Let It Snow!</h2>
    
    <p>Like Let It Snow?, you should follow me on Twitter <a href="http://twitter.com/Aen"><em>here</em></a></p>

    <form method="post" action="options.php">

	    <?php
	        // New way of setting the fields, for WP 2.7 and newer
	        if(function_exists('settings_fields')){
	            settings_fields('snow-options');
	        } else {
	            wp_nonce_field('update-options');
	            ?>
	            <input type="hidden" name="action" value="update" />
	            <input type="hidden" name="page_options" value="sflakesMax,sflakesMaxActive,svMaxX,svMaxY,ssnowStick,sfollowMouse" />
	            <?php
	        }
	    ?>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="animationInterval">Animation rate</label></th>
				<td>
					<input type="text" name="animationInterval" id="animationInterval" value="<?php echo get_option('animationInterval'); ?>" size="5" />
					<span class="description">Lesser (e.g. 20) = fast + smooth, but high CPU use. More (e.g. 50) = more conservative, but slower. Default is 33.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="flakeBottom">Limit flakes at the bottom</label></th>
				<td>
					<input type="text" name="flakeBottom" id="flakeBottom" value="<?php echo get_option('flakeBottom'); ?>" size="5" />
					<span class="description">Limits the "floor" (pixels) of the snow. If unspecified, snow will "stick" to the bottom of the browser window and persists through browser resize/scrolling.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="flakesMax">Maximum number of flakes</label></th>
				<td>
					<input type="text" name="flakesMax" id="flakesMax" value="<?php echo get_option('flakesMax'); ?>" size="5" />
					<span class="description">Sets the maximum number of snowflakes that can exist on the screen at any given time.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="flakesMaxActive">Maximum number of active flakes</label></th>
				<td>
					<input type="text" name="flakesMaxActive" id="flakesMaxActive" value="<?php echo get_option('flakesMaxActive'); ?>" size="5" />
					<span class="description">Sets the limit of "falling" snowflakes (ie. moving on the screen, thus considered to be active.)</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="followMouse">Follow mouse?</label></th>
				<td>
					<select name="followMouse" id="followMouse">
                		<option <?php if (get_option('followMouse') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('followMouse') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">Allows snow to move dynamically with the "wind", relative to the mouse's X (left/right) coordinates.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="freezeOnBlur">Freeze on blur?</label></th>
				<td>
					<select name="freezeOnBlur" id="freezeOnBlur">
                		<option <?php if (get_option('freezeOnBlur') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('freezeOnBlur') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">Stops the snow effect when the browser window goes out of focus, eg., user is in another tab. Saves CPU, nicer to user.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="snowColor">Snow color</label></th>
				<td>
					<input type="text" name="snowColor" id="snowColor" value="<?php echo get_option('snowColor'); ?>" size="7" />
					<span class="description">Don't eat (or use?) yellow snow.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="snowCharacter">Snow character</label></th>
				<td>
					<input type="text" name="snowCharacter" id="snowCharacter" value="<?php echo get_option('snowCharacter'); ?>" size="1" />
					<span class="description">&amp;bull; (&bull;) = bullet. &amp;middot; entity (&middot;) is not used as it's square on some systems etc. Changing this may result in cropping of the character and may require flakeWidth/flakeHeight changes, so be careful.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="snowStick">Sticky snow?</label></th>
				<td>
					<select name="snowStick" id="snowStick">
                		<option <?php if (get_option('snowStick') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('snowStick') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">Allows the snow to "stick" to the bottom of the window. When off, snow will never sit at the bottom.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="targetElement">Target element</label></th>
				<td>
					<input type="text" name="targetElement" id="targetElement" value="<?php echo get_option('targetElement'); ?>" size="10" />
					<span class="description">Element which snow will be appended to (default: document body) - can be an element ID string eg. 'myDiv', or a DOM node reference.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="useMeltEffect">Use melt effect?</label></th>
				<td>
					<select name="useMeltEffect" id="useMeltEffect">
                		<option <?php if (get_option('useMeltEffect') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('useMeltEffect') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">When recycling fallen snow (or rarely, when falling), have it "melt" and fade out if browser supports it.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="useTwinkleEffect">Use twinkle effect?</label></th>
				<td>
					<select name="useTwinkleEffect" id="useTwinkleEffect">
                		<option <?php if (get_option('useTwinkleEffect') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('useTwinkleEffect') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">Allow snow to randomly "flicker" in and out of view while falling.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="usePositionFixed">Use fixed position?</label></th>
				<td>
					<select name="usePositionFixed" id="usePositionFixed">
                		<option <?php if (get_option('usePositionFixed') == 'true') echo 'selected="selected"'; ?> value="true">Yes</option>
                		<option <?php if (get_option('usePositionFixed') == 'false') echo 'selected="selected"'; ?> value="false">No</option> 
                	</select>
                	<span class="description">Yes = snow not affected by window scroll. may increase CPU load, disabled by default - if enabled, used only where supported.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="vMaxX">Snowfall maximum speed (horizontal)</label></th>
				<td>
					<input type="text" name="vMaxX" id="vMaxX" value="<?php echo get_option('vMaxX'); ?>" size="5" />
					<span class="description">Defines maximum X velocity for the storm; a random value in this range is selected for each snowflake.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="vMaxY">Snowfall maximum speed (vertical)</label></th>
				<td>
					<input type="text" name="vMaxY" id="vMaxY" value="<?php echo get_option('vMaxY'); ?>" size="5" />
					<span class="description">Defines maximum Y velocity for the storm; a random value in this range is selected for each snowflake.</span>
				</td>
			</tr>
		</table>
		<p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" class="button-primary" />
        </p>

    </form>
    
    <p>Like Let It Snow?, you should follow me on Twitter <a href="http://twitter.com/Aen"><em>here</em></a></p>

</div>
<?php } ?>
<?php
// On access of the admin page, register these variables (required for WP 2.7 & newer)
function snow_init(){
    if(function_exists('register_setting')){
    	register_setting('snow-options', 'animationInterval');
    	register_setting('snow-options', 'flakeBottom');
    	register_setting('snow-options', 'flakesMax');
    	register_setting('snow-options', 'flakesMaxActive');
    	register_setting('snow-options', 'followMouse');
    	register_setting('snow-options', 'freezeOnBlur');
    	register_setting('snow-options', 'snowColor');
    	register_setting('snow-options', 'snowCharacter');
    	register_setting('snow-options', 'snowStick');
    	register_setting('snow-options', 'targetElement');
    	register_setting('snow-options', 'useMeltEffect');
    	register_setting('snow-options', 'useTwinkleEffect');
    	register_setting('snow-options', 'usePositionFixed');
    	register_setting('snow-options', 'vMaxX');
    	register_setting('snow-options', 'vMaxY');
    }
}

// Only all the admin options if the user is an admin
if(is_admin()){
    add_action('admin_menu', 'snow_options');
    add_action('admin_init', 'snow_init');
}

//Set the default options when the plugin is activated
function snow_activate(){
	add_option('animationInterval', 33);
    add_option('flakeBottom', 'null');
    add_option('flakesMax', 128);
    add_option('flakesMaxActive', 64);  
    add_option('followMouse', true);
    add_option('freezeOnBlur', true);  
    add_option('snowColor', '#fff');
    add_option('snowCharacter', '&bull;');
    add_option('snowStick', true);
    add_option('targetElement', 'null');
    add_option('useMeltEffect', true);
    add_option('useTwinkleEffect', true);
    add_option('usePositionFixed', false);
    add_option('vMaxX', 8);
    add_option('vMaxY', 5);
}

register_activation_hook( __FILE__, 'snow_activate' );

function let_it_snow() {
	// Path for snow images
	$snowPath = get_option('siteurl').'/wp-content/plugins/let-it-snow/';
	
	$snowJS = '<script type="text/javascript" src="'.$snowPath.'script/snowstorm-min.js"></script>'."\n";

	$snowJS .=	'<script type="text/javascript">
sitePath = "'.$snowPath.'";
snowStorm.animationInterval = '.get_option('animationInterval').';
snowStorm.flakeBottom = '.get_option('flakeBottom').';
snowStorm.flakesMax = '.get_option('flakesMax').';
snowStorm.flakesMaxActive = '.get_option('flakesMaxActive').';
snowStorm.followMouse = '.get_option('followMouse').';
snowStorm.freezeOnBlur = '.get_option('freezeOnBlur').';
snowStorm.snowColor = "'.get_option('snowColor').'";
snowStorm.snowCharacter = "'.get_option('snowCharacter').'";
snowStorm.snowStick = '.get_option('snowStick').';
snowStorm.targetElement = '.get_option('targetElement').';
snowStorm.useMeltEffect = '.get_option('useMeltEffect').';
snowStorm.useTwinkleEffect = '.get_option('useTwinkleEffect').';
snowStorm.usePositionFixed = '.get_option('usePositionFixed').';
snowStorm.vMaxX = '.get_option('vMaxX').';
snowStorm.vMaxY = '.get_option('vMaxY').';
</script>'."\n";
	
	print($snowJS);
}
add_action('wp_head', 'let_it_snow');
?>
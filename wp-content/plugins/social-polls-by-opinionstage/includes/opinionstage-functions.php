<?php

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

/* --- Wordpress Hooks Implementations --- */

/**
 * Initialize the plugin 
 */
function opinionstage_init() {
	opinionstage_initialize_data();
}

/**
 * Initialiaze the data options
 */
function opinionstage_initialize_data() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['version'] = OPINIONSTAGE_WIDGET_VERSION;
	
	// For backward compatibility
	if (!isset($os_options['sidebar_placement_active'])) {
		$os_options['sidebar_placement_active'] = 'false';
	}
	
	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
}

/**
 * Remove the plugin data
 */
function opinionstage_uninstall() {
    delete_option(OPINIONSTAGE_OPTIONS_KEY);
}

/**
 * Check if the requested plugin is already available
 */
function opinionstage_check_plugin_available($plugin_key) {
	$other_widget = (array) get_option($plugin_key); // Check the key of the other plugin

	// Check if OpinionStage plugin already installed.
	return (isset($other_widget['uid']) || 
		    isset($other_widget['email']));
}
/** 
 * Notify about other OpinionStage plugin already available
 */ 
function opinionstage_other_plugin_installed_warning() {
	echo "<div id='opinionstage-warning' class='error'><p><B>".__("Opinion Stage Plugin is already installed")."</B>".__(', please remove "<B>Popup for Interactive Content by Opinion Stage</B>" and use the available "<B>Poll & Quiz tools by Opinion Stage</B>" plugin')."</p></div>";
}

/**
 * Add the flyout embed code to the page header
 */
function opinionstage_add_flyout() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	
	if (!empty($os_options['fly_id']) && $os_options['fly_out_active'] == 'true' && !is_admin() ) {
		// Will be added to the head of the page
		?>
		 <script type="text/javascript">//<![CDATA[
			window.AutoEngageSettings = {
			  id : '<?php echo $os_options['fly_id']; ?>'
			};
			(function(d, s, id){
			var js,
				fjs = d.getElementsByTagName(s)[0],
				r = Math.floor(new Date().getTime() / 1000000);
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id; js.async=1;
			js.src = '<?php echo OPINIONSTAGE_SERVER_BASE; ?>' + '/assets/autoengage.js?' + r;
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'os-jssdk'));
			
		//]]></script>
		
		<?php
	}
}

?>

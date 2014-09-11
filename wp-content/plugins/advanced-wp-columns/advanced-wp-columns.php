<?php

/*
  Plugin Name: Advanced WP Columns
  Description: Advanced columns manager plugin which provides you to set up your content in the multiple columns using simple user interface, without any short codes. Very simple to use, and maintain.
  Version: 2.0.6
  Author: Vladica Savic
  Author URI: https://twitter.com/vsavic
 */

class AdvancedWPColumns {

    function __construct() {
        add_action('init', array(&$this, 'init'));
		add_action('admin_menu', array(&$this, 'advanced_wp_columns_add_options_page'));
		
		register_activation_hook(__FILE__, array(&$this, 'drysc_add_defaults'));
        add_action('admin_init', array(&$this, 'drysc_init'));		
    }	
	
    function init() {		
		$options = get_option('drysc_options');
		
		if (is_admin()) {
			wp_enqueue_script('advanced_wp_columns_handle', plugins_url('assets/js/plugins/settings.js', __FILE__));
		
			wp_localize_script('advanced_wp_columns_handle', 'AWP_Columns', array(
				'url' =>  plugin_dir_url( __FILE__ ),
				'fullWidth' => (isset($options['fullWidth'])) ? $options['fullWidth'] : "960",
				'columnStructure' => (isset($options['columnStructure'])) ? $options['columnStructure'] : "0",
				'responsiveSupport' => (isset($options['responsiveSupport'])) ? $options['responsiveSupport'] : "on",
				'containerClass' => (isset($options['containerClass'])) ? $options['containerClass'] : "",
				'columnsClass' => (isset($options['columnsClass'])) ? $options['columnsClass'] : "",
				'gutterClass' => (isset($options['gutterClass'])) ? $options['gutterClass'] : ""
			));
			
            wp_enqueue_style("dry_awp_admin_style", plugins_url('assets/css/admin.css', __FILE__));
			add_editor_style(plugins_url('assets/css/awp-editor.css', __FILE__));
			
			add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), array(&$this, 'advanced_wp_columns_settings_link') );
        }else{
			if((isset($options['responsiveSupport']) && $options['responsiveSupport'] == 'on') && (isset($options['wpautopDisabled']) && $options['wpautopDisabled'] == 'yes')){
					remove_filter ('the_content', 'wpautop');
			}
			add_action( 'wp_enqueue_scripts', array(&$this, 'get_wp_columns_styles') );
		}

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array(&$this, 'register_dry_plugins'));
            add_filter('mce_buttons_2', array(&$this, 'register_dry_btns'));
        }
    }
	
    function register_dry_btns($buttons) {
        array_push($buttons, 'advanced_wp_columns');

        return $buttons;
    }
	
	function drysc_add_defaults() {
			$arr = array(	
				"fullWidth" => "960",
				"columnStructure" => "0",
				"responsiveSupport" => "on",
				"smallBreakPoint" => "1024",
				"smallBreakPointContentWidth" => "80%",
				"wpautopDisabled" => "no",
				"containerClass" => "",
				"columnsClass" => "",
				"gutterClass" => ""				
			);
			update_option( 'drysc_options', $arr );
	}

    function register_dry_plugins($plgs) {
        $plgs['advanced_wp_columns'] = plugins_url('assets/js/plugins/columns.js', __FILE__);
        
        return $plgs;
    }

    function drysc_init() {
        register_setting('drysc_plugin_options', 'drysc_options');		
	}        
	
	function advanced_wp_columns_add_options_page(){
		add_options_page(__('Advanced WP Columns','awp_columns'), __('Advanced WP Columns','awp_columns'), 'manage_options', __FILE__, array(&$this, 'render_options_form'));
	}
	
	function render_options_form(){
		$options = get_option('drysc_options');
		
		$outputHTML  = '<div class="wrap">';
		$outputHTML .= '	<div id="icon-options-general" class="icon32"></div>';
		$outputHTML .= '	<h2>Advanced WP Columns</h2>';
		$outputHTML .= '	<h4>Main options related to the default settings and columns theming.</h4>';
		$outputHTML .= '	<form class="awp-columns" method="post" action="options.php">';
		
		echo($outputHTML);
		
		settings_fields('drysc_plugin_options');
		
		$outputHTML = '';
		
		$outputHTML .= '		<table>';
		$outputHTML .= '			<tbody>';
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="fullWidth">Container width</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="fullWidth" name="drysc_options[fullWidth]" value="'.$options["fullWidth"].'"/>';
		$outputHTML .= '						<p class="description">Full width of all of your columns, optimal is 960</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';		
		$outputHTML .= '				<tr>';	
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="columnStructure">Initial structure</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<select id="columnStructure" name="drysc_options[columnStructure]">';
		$outputHTML .= '							<option value="0"'.(( '0' == $options['columnStructure'] ) ? 'selected="selected"' : '').'>Empty</option>';
		$outputHTML .= '							<option value="1-1"'.(( '1-1' == $options['columnStructure'] ) ? 'selected="selected"' : '').'>Two Columns</option>';
		$outputHTML .= '							<option value="1-1-1"'.(( '1-1-1' == $options['columnStructure'] ) ? 'selected="selected"' : '').'>Three Columns</option>';
		$outputHTML .= '							<option value="1-1-1-1"'.(( '1-1-1-1' == $options['columnStructure'] ) ? 'selected="selected"' : '').'>Four Columns</option>';
		$outputHTML .= '						</select>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';
		$outputHTML .= '				<tr>';	
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						Responsive layout';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<label for="responsiveSupport">';
		$outputHTML .= '							<input type="checkbox" id="responsiveSupport" name="drysc_options[responsiveSupport]" value="on" '.((isset($options['responsiveSupport']) && 'on' == $options['responsiveSupport'] ) ? 'checked="checked"' : '').'/>';
		$outputHTML .= '							Enabled';
		$outputHTML .= '						</label>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';	
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="smallBreakPoint">Resolution breaking point</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="smallBreakPoint" name="drysc_options[smallBreakPoint]" value="'.((!isset($options["smallBreakPoint"]) || $options["smallBreakPoint"] == "") ? "1024" : $options["smallBreakPoint"]).'"/>';
		$outputHTML .= '						<p class="description">Show one column per row when screen width<br /> is smaller than specified, optimal is 1024</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';	
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="smallBreakPointContentWidth">Column per row occupation</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="smallBreakPointContentWidth" name="drysc_options[smallBreakPointContentWidth]" value="'.((!isset($options["smallBreakPointContentWidth"]) || $options["smallBreakPointContentWidth"] == "") ? "80%" : $options["smallBreakPointContentWidth"]).'"/>';
		$outputHTML .= '						<p class="description">Define how much space in percentage will column occupy <br /> in row after resolution break when is single, optimal is 80%</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';	
		$outputHTML .= '				<tr>';	
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						Empty paragraphs formating';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<label for="wpautopDisabled">';
		$outputHTML .= '							<input type="checkbox" id="wpautopDisabled" name="drysc_options[wpautopDisabled]" value="yes" '.(( isset($options['wpautopDisabled']) && 'yes' == $options['wpautopDisabled'] ) ? 'checked="checked"' : '').'/>';
		$outputHTML .= '							Disable on small resolutions';
		$outputHTML .= '						</label>';
		$outputHTML .= '						<span>(<a href="http://codex.wordpress.org/Function_Reference/wpautop" target="_blank">follow this link for more details</a>)</span>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';	
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="containerClass">Container class</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="containerClass" name="drysc_options[containerClass]" value="'.$options["containerClass"].'"/>';
		$outputHTML .= '						<p class="description">CSS class which you want to apply to columns container</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';	
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="columnsClass">Column class</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="columnsClass" name="drysc_options[columnsClass]" value="'.$options["columnsClass"].'"/>';
		$outputHTML .= '						<p class="description">CSS class which you want to apply to all columns</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';		
		$outputHTML .= '				<tr valign="top">';
		$outputHTML .= '					<th scope="row">';
		$outputHTML .= '						<label for="gutterClass">Gutter class</label>';
		$outputHTML .= '					</th>';
		$outputHTML .= '					<td>';
		$outputHTML .= '						<input type="text" id="gutterClass" name="drysc_options[gutterClass]" value="'.$options["gutterClass"].'"/>';
		$outputHTML .= '						<p class="description">CSS class which you want to apply to all gutters</p>';
		$outputHTML .= '					</td>';
		$outputHTML .= '				</tr>';
		$outputHTML .= '			</tbody>';
		$outputHTML .= '		</table>';
		$outputHTML .= '		<a href="http://www.wpcolumns.com/#np-user_guide" target="_blank">If you need help or more details about this plugin configuration please follow this link.</a>';
		$outputHTML .= '		<p class="submit">';
		$outputHTML .= '			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">';
		$outputHTML .= '		</p>';
		$outputHTML .= '	</form>';
		$outputHTML .= '</div>';
		
		echo $outputHTML;
		
		wp_enqueue_style("awp_options_form_style", plugins_url('assets/css/awp-forms.css', __FILE__));
		wp_enqueue_script('awp_options', plugins_url('assets/js/plugins/awp-options.js', __FILE__));
	}	
	
	function get_wp_columns_styles() {
		$options = get_option('drysc_options');
		
		wp_enqueue_style("dry_awp_theme_style", plugins_url('assets/css/awp-columns.css', __FILE__));
		if($options['responsiveSupport'] == 'on'){
			$smallBreakPoint = preg_replace("/[^0-9]/","",$options["smallBreakPoint"]);
			$smallBreakPointContentWidth = preg_replace("/[^0-9]/","",$options["smallBreakPointContentWidth"]);
			
			$marginSpace = floor(((100 - $smallBreakPointContentWidth)/2) * 100)/100;
			
			$awp_css  = '';
			$awp_css .= '@media screen and (max-width: '.preg_replace("/[^0-9]/","",$smallBreakPoint).'px) {';
			$awp_css .= '	.csColumn {';
			$awp_css .= '		clear: both !important;';
			$awp_css .= '		float: none !important;';
			$awp_css .= '		text-align: center !important;';
			$awp_css .= '		margin-left:  '.$marginSpace.'% !important;';
			$awp_css .= '		margin-right: '.$marginSpace.'% !important;';
			$awp_css .= '		width: '.$smallBreakPointContentWidth.'% !important;';
			$awp_css .= '	}';
			$awp_css .= '	.csColumnGap {';
			$awp_css .= '		display: none !important;';
			$awp_css .= '	}';
			$awp_css .= '}';
			
			wp_add_inline_style( 'dry_awp_theme_style', $awp_css );
		}
	}	
	
	function advanced_wp_columns_settings_link( $links ) {
		$isSet=apply_filters('ebs_custom_option',false);
		if (!$isSet) {
			$settings_link = '<a href="options-general.php?page=advanced-wp-columns/advanced-wp-columns.php">Settings</a>';
			array_push( $links, $settings_link );
		}
		return $links;
	}
}

new AdvancedWPColumns();
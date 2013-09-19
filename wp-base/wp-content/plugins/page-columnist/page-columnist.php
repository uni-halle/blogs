<?php
/***************************************************************************************************************************************************
Plugin Name:  Page Columnist
Plugin URI: http://www.code-styling.de/english/development/wordpress-plugin-page-columnist-en
Description:  A simple way to get single posts and static pages content arranged in column layout, supports also overview page behavior.
Author: Heiko Rabe
Author URI: http://www.code-styling.de/
Version: 1.7.3
***************************************************************************************************************************************************
 License:
 =======
 Copyright 2009 Heiko Rabe  (email : info@code-styling.de)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************************************************************************************/

//avoid direct calls to this file, because now WP core and framework has been used
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

//WordPress definitions
if ( !defined('WP_CONTENT_URL') )
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if ( !defined('WP_PLUGIN_URL') ) 
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if ( !defined('WP_PLUGIN_DIR') ) 
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
if ( !defined('PLUGINDIR') )
	define( 'PLUGINDIR', 'wp-content/plugins' ); // Relative to ABSPATH.  For back compat.
	
if ( !defined('WP_LANG_DIR') )
	define('WP_LANG_DIR', WP_CONTENT_DIR . '/languages');

//safety patch for changed activation procedure in WP 3.0
//without this test it won't be activatable at less than 3.0	
if (!function_exists('cspc_on_activate_plugin'))	{

function cspc_on_activate_plugin() {
	global $page_columnist_plug, $wpdb;
	//self deactivation in error cases
	if (is_object($page_columnist_plug)) {
		$version_error = $page_columnist_plug->_get_version_errors();
		if (is_array($version_error) && count($version_error) != 0) {
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", 'active_plugins' ) );
			if ( is_object( $row ) ) {
				$current = maybe_unserialize( $row->option_value );
				if (array_search($page_columnist_plug->basename, $current) !== false) {		
					array_splice($current, array_search($page_columnist_plug->basename, $current), 1 );
					update_option('active_plugins', $current);
				}
			}
			exit();
		}
	}
	if (!get_option('cspc_page_columnist'))
		update_option('cspc_page_columnist', get_option('cspc_page_columnist', $page_columnist_plug->defaults));
}		
function cspc_on_deactivate_plugin() {
	//currently empty
}
register_activation_hook(plugin_basename(__FILE__), 'cspc_on_activate_plugin');	
register_deactivation_hook(plugin_basename(__FILE__), 'cspc_on_deactivate_plugin');

	
class Page_columnist_page_transition {

	function Page_columnist_page_transition(&$plugin, $page_id = false) {
		$this->plugin 		= $plugin; //reference to owner plugin
		$this->transition 	= $this->plugin->page_default_trans;
		$this->data			= array();
		$this->data_keys	= array('spacing', 'columns', 'overflow', 'multiposts');
		$this->padding		= '<span style="font-style:italic;color:#f00">'.__('* content missing', $this->plugin->textdomain).'</span>';
		if ($page_id) $this->load($page_id);
		else $this->page_id = 0;
	}

	function load($page_id) {
		$this->page_id		= ($page_id ? $page_id : 0);		
		$this->transition 	= $this->plugin->page_default_trans;
		$this->data			= array();
		$res = explode('|', get_post_meta($this->page_id, '_cspc-page-transitions', true));	
		$num = count($res);
		if ($num > 0) {
			if (in_array($res[0], array_keys($this->plugin->page_transitions))) {
				$this->transition = $res[0];
				if ($num > 1) {
					$this->data = unserialize($res[1]);
				}
			}
		}
	}
	
	function save() {
		if ($this->page_id > 0) {
			$value = $this->transition.'|'.serialize($this->data);
			update_post_meta($this->page_id, '_cspc-page-transitions', $value);
		}
	}
	
	function update_and_save() {
		if (isset($_POST['cspc-page-transition']) && in_array($_POST['cspc-page-transition'], array_keys($this->plugin->page_transitions))) {
			$this->transition = $_POST['cspc-page-transition'];
		}
		if (isset($_POST['cspc-count-columns'])) {
			$this->data['columns'] = (int)$_POST['cspc-count-columns'];
		}
		if (isset($_POST['cspc-overflow-paging'])) {
			$this->data['overflow'] = $_POST['cspc-overflow-paging'];
			if($this->data['overflow'] != 'hidden' && $this->data['overflow'] != 'virtual') {
				$this->data['overflow'] = 'hidden';
			}
		}	
		if (isset($_POST['cspc-multiposts-paging']) && in_array($_POST['cspc-multiposts-paging'], $this->plugin->multiposts_style)) {
			$this->data['multiposts'] = $_POST['cspc-multiposts-paging'];
		}
		if (!isset($this->data['distribution']) || !is_array($this->data['distribution'])) $this->data['distribution'] = array();
		$this->save();
	}
	
	
	function spacing() {
		return (isset($this->data['spacing']) ? (float)$this->data['spacing'] : (float)$this->plugin->options->spacing);
	}
	
	function columns() {
		return (isset($this->data['columns']) ? (int)$this->data['columns'] : 2);
	}
	
	function overflow() {
		return (isset($this->data['overflow']) ? $this->data['overflow'] : 'hidden');
	}
	
	function multiposts() {
		return (isset($this->data['multiposts']) ? $this->data['multiposts'] : 'same');
	}
	
	function execute(&$pages) {
		return call_user_func(array(&$this, '_exec_'.str_replace('-','_',$this->transition)), $pages);
	}

	function _padding_pages($needed, &$pages) {
		$res = array();
		while(count($pages) % $needed != 0) $pages[] = $this->padding; //padding count of sub pages
		if ($this->overflow() == 'hidden') {
			$res[] = array_slice($pages, 0, $needed);
		}else{
			$res = array_chunk($pages, $needed);
		}
		return $res;
	}

	function _get_distribution($num, $remaining) {
		$res = array();
		if (!is_array($this->data['distribution']) || !isset($this->data['distribution'][$num])) {
			$perc = $remaining / $num;
			for ($i=0; $i<$num; $i++) $res[] = $perc;
		}
		else{
			$sum = 0.0;
			for ($i=0; $i<$num; $i++) $sum += (float)$this->data['distribution'][$num][$i];
			for ($i=0; $i<$num; $i++) {
				$res[]= ($remaining * ((float)$this->data['distribution'][$num][$i] / $sum));
			}
		}
		return $res;
	}
	
	function _columnize_pages($num, $pages) {
		$res = '';
		$base = 100.0;
		$spacing = $this->spacing();
		$remaining = ($base - ($num - 1) * $spacing);
		$dist = $this->_get_distribution($num, $remaining);
		$lorr = 'left';
		global $wp_locale;
		if ($wp_locale->text_direction == 'rtl') {
			$lorr = 'right'; //make it work at RTL languages
		}
		for ($i=0; $i<$num; $i++) {
			$page = $pages[$i];
			$perc = $dist[$i];
			if ($this->plugin->is_MSIE()) {
				//IE is not able to calculate column sizes well, several 100% sums leads to get the last column display wrapped under the first
				//in several cases (mostly where a periodic fraction of 1/3 or 1/6 occures) , IE seems to reach by internal rounding errors more than 100% of available width
				$margin_left_i = ($i==0 ? 0 : $spacing * 0.66);
			}
			else{
				$margin_left_i = ($i==0 ? 0 : $spacing);
			}
			$extend = $this->plugin->is_page_preview() ? ' data="'.$perc.'"' : '';
			$res .= "<div id=\"cspc-column-$i\" class=\"cspc-column\" style=\"display:inline-block;float:$lorr;margin-$lorr:$margin_left_i%;width:$perc%;overflow:hidden;\"$extend>";
			if (has_filter('the_content', 'wpautop') && !preg_match('/^\s*<div/', $page)) $res .= '<p>';
			$res .= $page;
			if (has_filter('the_content', 'wpautop') && !preg_match('/<\/p>\s*$/', $page)) $res .= '</p>';
			$res .= '</div>';
		}
		return $res;		
	}
	
	function _columnize_fullsize($page, $clearleft = false) {
		if (empty($page)) return '';
		$res = ($clearleft ? '<div id="cspc-footer" style="clear:left;">' : '<div id="cspc-header">');
		if (has_filter('the_content', 'wpautop') && !preg_match('/^\s*<div/', $page)) $res .= '<p>';
		$res .= $page;
		if (has_filter('the_content', 'wpautop') &&!preg_match('/<\/p>\s*$/', $page)) $res .= '</p>';
		$res .= '</div>';
		if ($clearleft) $res .='<div style="clear:both; height:0;"> </div>';
		return $res;
	}
	
	function _exec_cspc_trans_wordpress(&$pages) {
		return $pages;
	}

	function _exec_cspc_trans_ordinary(&$pages) {
		$res = "<div id=\"$this->transition-wrap\" class=\"cspc-wrapper\">";
		$res .= "\n\n";
		$res .= implode("\n\n", $pages);
		$res .='</div>';
		return array($res);
	}
	
	function _exec_cspc_trans_columns(&$pages) {
		$work = $this->_padding_pages($this->columns(), $pages);	
		$out = array();
		for ($i=0; $i<count($work); $i++) {
			$num = count($work[$i]);
			$res = "<div id=\"$this->transition-wrap\" class=\"cspc-wrapper\">";
			$res .= '<div id="cspc-content" style="clear:left;">';
			$res .= $this->_columnize_pages($num, $work[$i]);
			$res .= '<div style="clear:left;"></div></div>';
			$res .= '</div>';
			$out[] = $res;
		}
		return $out;
	}
	
	function _exec_cspc_trans_header(&$pages) {
		$work = $this->_padding_pages($this->columns() + 1, $pages);	
		$out = array();
		for ($i=0; $i<count($work); $i++) {
			$top = array_shift($work[$i]);
			$num = count($work[$i]);
			$res = "<div id=\"$this->transition-wrap\" class=\"cspc-wrapper\">";
			$res .= $this->_columnize_fullsize($top);
			$res .= '<div id="cspc-content" style="clear:left;">';
			$res .= $this->_columnize_pages($num, $work[$i]);
			$res .= '<div style="clear:left;"></div></div>';
			$res .= '</div>';
			$out[] = $res;
		}
		return $out;
	}
	
	function _exec_cspc_trans_footer(&$pages) {
		$work = $this->_padding_pages($this->columns() + 1, $pages);	
		$out = array();
		for ($i=0; $i<count($work); $i++) {
			$last = end($work[$i]);
			$num = count($work[$i]) -1;
			$work[$i] = ($num > 0 ? array_slice($work[$i],0,$num) : array());
			$res = "<div id=\"$this->transition-wrap\" class=\"cspc-wrapper\">";
			$res .= '<div id="cspc-content" style="clear:left;">';
			$res .= $this->_columnize_pages($num, $work[$i]);
			$res .= '<div style="clear:left;"></div></div>';
			$res .= $this->_columnize_fullsize($last, true);
			$res .= '</div>';
			$out[] = $res;
		}
		return $out;
	}
	
	function _exec_cspc_trans_interior(&$pages) {
		$work = $this->_padding_pages($this->columns() + 2, $pages);	
		$out = array();
		for ($i=0; $i<count($work); $i++) {
			$top = array_shift($work[$i]);
			$num = count($work[$i]) -1;
			$last = ($num > 0 ? end($work[$i]) : '');
			$work[$i] = ($num > 0 ? array_slice($work[$i],0,$num) : array());
			$res = "<div id=\"$this->transition-wrap\" class=\"cspc-wrapper\">";
			$res .= $this->_columnize_fullsize($top);
			$res .= '<div id="cspc-content" style="clear:left;">';
			$res .= $this->_columnize_pages($num, $work[$i]);
			$res .= '<div style="clear:left;"></div></div>';
			$res .= $this->_columnize_fullsize($last, true);
			$res .= '</div>';
			$out[] = $res;
		}
		return $out;
	}
}


//page_columnist Plugin class	
class Plugin_page_columnist {
	
	//initializes the whole plugin and enables the activation/deactivation handling
	function Plugin_page_columnist() {
		global $wp_version;

		$this->l10n_ready 							= false;
		
		//own plugin data
		$this->basename 							= plugin_basename(__FILE__);
		$this->url									= WP_PLUGIN_URL.'/'.dirname($this->basename);
		$this->textdomain 							= basename($this->basename);
		$this->textdomain							= substr($this->textdomain, 0, strrpos($this->textdomain, '.'));
		$this->versions								= new stdClass;
		$this->versions->required					= array( 'php' => '4.4.2', 'wp' => '2.7');
		$this->versions->found						= array( 'php' => phpversion(), 'wp' => $wp_version);
		$this->versions->above_27					= !version_compare($this->versions->found['wp'], '2.8alpha', '<');
		$this->do_resample_page						= false;
		$this->multiposts_style						= array('flat', 'same', 'wp');

		$this->page_default_trans 					= 'cspc-trans-wordpress';
		
		$this->defaults								= new stdClass;
		$this->defaults->spacing					= 3.0;
		$this->defaults->preview_assistent			= false;
		
		$this->options								= get_option('cspc_page_columnist', $this->defaults);
		$stored = get_object_vars($this->options);
		$defaults = get_object_vars($this->defaults);
		foreach($defaults as $key => $value) {
			if (!isset($stored[$key])) $this->options->$key = $value;
		}
									
		add_action('init', 							array(&$this, 'on_init'));
		add_action('admin_init',					array(&$this, 'on_admin_init'));
		add_action('wp_head',						array(&$this, 'on_wp_head'));
		add_action('wp_footer',						array(&$this, 'on_wp_footer'));
		add_action('admin_head', 					array(&$this, 'on_admin_head'));
		add_action('edit_page_form',				array(&$this, 'on_extend_html_editor'));
		add_action('edit_form_advanced',			array(&$this, 'on_extend_html_editor'));
		add_action('manage_pages_custom_column',	array(&$this, 'on_manage_custom_column'), 10, 2);
		add_action('manage_posts_custom_column',	array(&$this, 'on_manage_custom_column'), 10, 2);
		add_action('wp_insert_post', 				array(&$this, 'on_wp_insert_post'), 10, 2);
		add_action('loop_start', 					array(&$this, 'on_loop_start'), 0 );
		add_action('the_post', 						array(&$this, 'on_the_post'), 0 );
		add_action('template_redirect',				array(&$this, 'on_template_redirect'));
		add_filter('mce_buttons',					array(&$this, 'on_filter_mce_buttons'));

		$this->_display_version_errors();
		
		//TODO:  future development should be able to deal with removing the ugly wpautop algorithm
		//remove_filter('the_content', 'wpautop');
	}

	function _get_version_errors() {
		$res = array();
		foreach($this->versions->required as $key => $value) {
			if (!version_compare($this->versions->found[$key], $value, '>=')) {
				$res[strtoupper($key).' Version'] = array('required' => $value, 'found' =>  $this->versions->found[$key]);
			}
		}
		return $res;
	}
	
	function _display_version_errors() {
		if (isset($_GET['action']) && isset($_GET['plugin']) && ($_GET['action'] == 'error_scrape') && ($_GET['plugin'] == $this->basename )) {
			$this->setup_translation();
			$version_error = $this->_get_version_errors();
			if (count($version_error) != 0) {
				echo "<table>";
				echo "<tr style=\"font-size: 12px;\"><td><strong style=\"border-bottom: 1px solid #000;\">".__('Plugin can not be activated.', $this->textdomain)."</strong></td><td> | ".__('required', $this->textdomain)."</td><td> | ".__('actual', $this->textdomain)."</td></tr>";			
				foreach($version_error as $key => $value) {
					echo "<tr style=\"font-size: 12px;\"><td>$key</td><td align=\"center\"> &gt;= <strong>".$value['required']."</strong></td><td align=\"center\"><span style=\"color:#f00;\">".$value['found']."</span></td></tr>";
				}
				echo "</table>";
			}
		}
	}	
	
	//load and setup the necessary translation files
	function setup_translation() {
		if (!$this->l10n_ready) {
			$abs_rel_path = str_replace(ABSPATH, '', WP_PLUGIN_DIR.'/'.dirname($this->basename));
			$plugin_rel_path = dirname($this->basename);		
			load_plugin_textdomain($this->textdomain, $abs_rel_path, $plugin_rel_path);
			$this->l10n_ready = true;
		}
	}
	
	//is the browser of current user IE ?
	function is_MSIE() {
		return preg_match('/msie/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/opera/i', $_SERVER['HTTP_USER_AGENT']);
	}
	
	//do we show a frontend page in preview mode ?
	function is_page_preview() {
		$id = (isset($_GET['preview_id']) ? (int)$_GET['preview_id'] : 0);
		if ($id == 0 && isset($_GET['post_id'])) $id = (int)$_GET['post_id'];
		if ($id == 0 && isset($_GET['page_id'])) $id = (int)$_GET['page_id'];
		if ($id == 0 && isset($_GET['p'])) $id = (int)$_GET['p'];
		$preview = (isset($_GET['preview']) ? $_GET['preview'] : '');
		if ($id > 0 && $preview == 'true' && $this->options->preview_assistent) {
			global $wpdb;
			$type = $wpdb->get_results("SELECT post_type FROM $wpdb->posts WHERE ID=$id");
			if (count($type) && ($type[0]->post_type == 'page' || $type[0]->post_type == 'post')){
				switch($type[0]->post_type) {
					case 'post':
						return current_user_can('edit_post', $id);
						break;
					case 'page':
						return current_user_can('edit_page', $id);
						break;
					default:
						return false;
						break;
				}
			}
		}
		return false;
	}

	//detects if we a currently render the posts or pages edit overview table page
	function is_page_overview() {
		global $pagenow;
		return (is_admin() && ($pagenow == 'edit-pages.php' || $pagenow == 'edit.php'));
	}

	//detects if we a currently render the posts or pages editor page
	function is_page_editor() {
		global $pagenow;
		return (is_admin() && (
			($pagenow == 'page.php') || ($pagenow == 'page-new.php')
			||
			($pagenow == 'post.php') || ($pagenow == 'post-new.php')
			)
		);
	}
	
	//gets called by WordPress action "init" after WordPress core is up and running
	function on_init() {		
		//ensures the correct matching translation file gets loaded
		$this->setup_translation();				
		
		$this->page_transitions = array(
			'cspc-trans-wordpress' 		=> array('img-pos' =>   0, 'text' => __('WordPress - Next Page (default)', $this->textdomain), 'default' => true),
			'cspc-trans-ordinary'		=> array('img-pos' => -16, 'text' => __('Ordinary Plain Page', $this->textdomain)),
			'cspc-trans-columns'		=> array('img-pos' => -32, 'text' => __('Every Sub Page as Column', $this->textdomain)),
			'cspc-trans-header'			=> array('img-pos' => -48, 'text' => __('First Sub Page as Header', $this->textdomain)),
			'cspc-trans-footer'			=> array('img-pos' => -64, 'text' => __('Last Sub Page as Footer', $this->textdomain)),
			'cspc-trans-interior'		=> array('img-pos' => -80, 'text' => __('Interior as Columns', $this->textdomain)),
		);
				
		if ($this->is_page_preview() && !defined('DOING_AJAX')) {
			wp_enqueue_script('jquery-spin', $this->url.'/jquery.spin.js', array('jquery'));
			wp_enqueue_script('cspc_page_columnist_assistance', $this->url.'/page-columnist-assistance.js', array('jquery', 'jquery-ui-draggable', 'jquery-spin'));
			wp_enqueue_style('cspc_page_columnist_assistance', $this->url.'/page-columnist-assistance.css');
		}		

		if(defined('DOING_AJAX')) {
			add_action('wp_ajax_cspc_save_changes', array(&$this, 'on_ajax_save_changes'));
		}
	}
	
	//gets called by WordPress action "admin_init" to be able to setup correctly in backend mode
	function on_admin_init() {

		if ($this->is_page_overview()) {
			add_filter('manage_edit-pages_columns', array(&$this,'on_filter_manage_columns'));
			add_filter('manage_posts_columns', array(&$this,'on_filter_manage_columns'));
		}
		
		if ($this->is_page_editor()) {
			add_meta_box('cspc-page-transitions', __('Page Columnist', $this->textdomain) , array(&$this, 'on_print_metabox_content_cspc_page_transitions'), 'page', 'side', 'core');
			add_meta_box('cspc-page-transitions', __('Page Columnist', $this->textdomain) , array(&$this, 'on_print_metabox_content_cspc_page_transitions'), 'post', 'side', 'core');
			wp_enqueue_script('jquery-spin', $this->url.'/jquery.spin.js', array('jquery'));		
		}		
	}
	
	//gets called by action "wp_head" to configure the preview assistance
	function on_wp_head() {
		if ($this->is_page_preview()) {
			$id = (isset($_GET['preview_id']) ? (int)$_GET['preview_id'] : 0);
			if ($id == 0 && isset($_GET['post_id'])) $id = (int)$_GET['post_id'];
			if ($id == 0 && isset($_GET['page_id'])) $id = (int)$_GET['page_id'];
			if ($id == 0 && isset($_GET['p'])) $id = (int)$_GET['p'];
			$pt = new Page_columnist_page_transition($this, $id);			
		?>
		<script type="text/javascript">
		var cspc_page_columnist_l10n = {
			adminUrl: '<?php echo admin_url(); ?>',
			pageId: <?php echo $id; ?>,
			imageBasePath: '<?php echo $this->url.'/img/'; ?>'
		};
		</script>
		<?php }
	}
	
	//gets called by WordPress action "wp_footer" to print in preview mode the assistance
	function on_wp_footer() {
		if ($this->is_page_preview()) { 
			$id = (isset($_GET['preview_id']) ? (int)$_GET['preview_id'] : 0);
			if ($id == 0 && isset($_GET['post_id'])) $id = (int)$_GET['post_id'];
			if ($id == 0 && isset($_GET['page_id'])) $id = (int)$_GET['page_id'];
			if ($id == 0 && isset($_GET['p'])) $id = (int)$_GET['p'];
			$pt = new Page_columnist_page_transition($this, $id);
		?>
		<div id="cspc-assist-bar">
			<div id="cspc-assist-bar-content"> 
				<div class="cspc-section">
					<strong style="font-size: 18px;"><i><a href="http://www.code-styling.de" target="_blank">CodeStyling Project &copy 2009</a></i></strong>
				</div>
				<div class="cspc-section">
					<input autocomplete="off" id="cspc-columns-sizing" name="cspc-columns-sizing" type="checkbox"><label for="cspc-columns-sizing"><?php _e('enable column resizing', $this->textdomain); ?></label>
				</div>
				<div class="cspc-section">
					<input autocomplete="off" id="cspc-col-spacing" type="text" value="<?php echo (float)$pt->spacing(); ?>" readonly="readonly" style="width:20px;background-color:#fff;color:#000;"/>
					<label>&nbsp;&nbsp;<?php _e('% spacing', $this->textdomain); ?></label>
					&nbsp;
					<input autocomplete="off" id="cspc-default-spacing" name="cspc-default-spacing" type="checkbox"><label for="cspc-default-spacing"><?php _e('default spacing', $this->textdomain); ?></label></input>
					&nbsp;
					<a id="cspc-save-changes" class="cspc-button" href="javascript:void(0);"><?php _e('save changes', $this->textdomain); ?></a>
				</div>
				<div style="clear:left;"> </div>
			</div>
			<div id="cspc-assist-bar-expander"><?php _e('Page Columnist &bull; Assistance', $this->textdomain); ?></div>
		</div>
		<?php }
	}
		
	//gets called by WordPress action "admin_head"
	function on_admin_head() {
		if ($this->is_page_overview() || $this->is_page_editor()) :	?>
			<style type="text/css">
				.cspc-page-table td { font-size: 11px; vertical-align: middle; }
				#cspc-page-definition td { font-size: 11px; vertical-align: middle; }
				#cspc-page-transition-col { width: 56px; } 
				.cspc-page-transition-row { width: 30px; height: 16px; padding-left:16px; }
				.cspc-page-transition-box { padding: 0px 3px 3px 18px; line-height: 18px; white-space: nowrap; }
				*:first-child + html #cspc-page-transitions input { margin-top: -3px; margin-left: 0xp;} /* IE fix */
				*:first-child + html #cspc-page-transitions p { margin: 3px; }
				#cspc-default-column-spacing { font-size:12px;width:26px;background-color:#fff;color:#000;height:19px; padding: 1px 0px 2px 1px; line-height:15px;} /* border: solid 1px #000; line-height:15px;}*/
				*:first-child + html #cspc-default-column-spacing, html:first-child>b\ody #cspc-default-column-spacing  { height: 15px; } /* IE and Opera */
				<?php foreach($this->page_transitions as $key => $val) : ?>
				.<?php echo $key; ?> { background: transparent url(<?php echo $this->url.'/states.png'; ?>) no-repeat left <?php echo $val['img-pos']; ?>px; }
				<?php endforeach; ?>
			</style>
			<?php if ($this->is_page_editor()) : ?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#cspc-default-column-spacing').spin({max:10,min:0,imageBasePath: '<?php echo $this->url.'/img/'; ?>', interval: 0.5 });
					});
				</script>
			<?php endif; ?>
		<?php endif;
	}

	//insert the nextpage button to TinyMCE button bar if it's not already there
	function on_filter_mce_buttons($buttons) {
		if ($this->is_page_editor()) {
			if (!in_array('wp_page', $buttons)) {
				if (!in_array('wp_more', $buttons)) {
					$last = array_pop($buttons);
					$buttons[] = "wp_page";
					$buttons[] = $last;
				}else{
					$txt = implode('|', $buttons);
					$txt = str_replace('wp_more|','wp_more|wp_page|', $txt);
					$buttons = explode('|', $txt);
				}
			}
		}
		return $buttons;		
	}

	//insert nextpage button to HTML editor, if not already present there
	function on_extend_html_editor() {
		if ($this->is_page_editor()) : ?>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready(function() {
				if (!jQuery("#ed_next").length) {
					content = '<input id="ed_next" class="ed_button" type="button" value="<?php _e('nextpage',$this->textdomain); ?>" title="<?php _e('insert Page break', $this->textdomain); ?>" onclick="edInsertContent(edCanvas, \'<!--nextpage-->\');" accesskey="t" />';
					jQuery("#ed_toolbar").append(content);
				}
			});
			//]]>
		</script>
		<?php endif;
	}
	
	//append a custom column at edit posts/pages overview
	function on_filter_manage_columns($columns) {
		$columns['cspc-page-transition-col'] = __('Cols', $this->textdomain);
		return $columns;
	}
	
	//add content to the new edit posts / pages overview column
	function on_manage_custom_column($column_name, $id) {
		if ($column_name == 'cspc-page-transition-col') {
			$pt = new Page_columnist_page_transition($this, $id);
			if ($pt->transition != 'cspc-trans-wordpress' && $pt->transition != 'cspc-trans-ordinary') {
				echo "<div class=\"cspc-page-transition-row $pt->transition\">&nbsp; (".$pt->columns().")</div>";
			}
			else{
				echo "<div class=\"cspc-page-transition-row $pt->transition\">&nbsp; (-)</div>";
			}
		}
	}
	
	//save the page transition mode setting of page/post, if something has been saved
	function on_wp_insert_post($post_ID, $post) {	
		$my_id 		= ((isset($_POST['wp-preview']) && $_POST['wp-preview'] == 'dopreview') ? $_POST['post_ID'] : $post_ID);	
		$my_type 	= ((isset($_POST['wp-preview']) && $_POST['wp-preview'] == 'dopreview') ? $_POST['post_type'] : $post->post_type);

		switch($my_type) {
			case 'post':
				if(!current_user_can('edit_post', $my_id)) return;
				break;
			case 'page':
				if (!current_user_can('edit_page', $my_id)) return;
				break;
			default:
				return;
				break;
		}
		
		if (($my_type == 'page') || ($my_type == 'post')) { 
		
			$pt = new Page_columnist_page_transition($this, $my_id);
			$pt->update_and_save();
						
			$this->options->preview_assistent = (isset($_POST['cspc-preview-assistent']) ? (bool)$_POST['cspc-preview-assistent'] : $this->defaults->preview_assistent);
			$this->options->spacing = (isset($_POST['cspc-default-column-spacing']) ? (float)$_POST['cspc-default-column-spacing'] : $this->defaults->spacing);
			update_option('cspc_page_columnist', $this->options);
		}
	}
	
	//save the changes the user made at preview assistent 
	function on_ajax_save_changes() {	
		$page_id = (int)$_POST['page_id'];
		$spacing = (float)$_POST['spacing'];
		$default_spacing = ($_POST['default_spacing'] == 'true' ? true : false);

		global $wpdb;
		$type = $wpdb->get_results("SELECT post_type FROM $wpdb->posts WHERE ID=$page_id");
		if (count($type) && ($type[0]->post_type == 'page' || $type[0]->post_type == 'post')){
			$checked = false;
			switch($type[0]->post_type) {
				case 'post':
					$checked = current_user_can('edit_post', $page_id);
					break;
				case 'page':
					$checked = current_user_can('edit_page', $page_id);
					break;
				default:
					$checked = false;
					break;
			}
			if ($checked) {
				$pt = new Page_columnist_page_transition($this, $page_id);
				$pt->data['spacing'] = $spacing;
				if (!is_array($pt->data['distribution'])) $pt->data['distribution'] = array();
				$pt->data['distribution'][$pt->columns()] = explode('|',$_POST['distribution']);
				$pt->save();				
				if ($default_spacing) {
					$this->options->spacing = $spacing;
					update_option('cspc_page_columnist', $this->options);
				}
				exit();
			}
		}
		header('Status: 404 Not Found');
		header('HTTP/1.1 404 Not Found');
		_e('You do not have the permission to edit this page.', $this->textdomain);
		exit();
	}
	
	
	//ouput the content of new one sidebar box at page/post editing
	function on_print_metabox_content_cspc_page_transitions($data) {
		$pt = new Page_columnist_page_transition($this, $data->ID); ?>
		<div style="margin-bottom:5px; border-bottom: dotted 1px #999; padding: 5px 0px;vertical-align:top;">
			<table class="cspc-page-table">
				<tr>
				 <td><input id="cspc-default-column-spacing" name="cspc-default-column-spacing" type="text" value="<?php echo $this->options->spacing; ?>" readonly="readonly" autocomplete="off"/></td>
				 <td>&nbsp;<label for="cspc-default-column-spacing"><?php _e('% column default spacing', $this->textdomain); ?></label><td>
				</tr>
			</table>
		</div>
		<div>
			<table class="cspc-page-table">
			<?php
			foreach($this->page_transitions as $type => $val) : ?>
				<tr><td>
					<input id="<?php echo $type; ?>" type="radio" name="cspc-page-transition" value="<?php echo $type; ?>" <?php if($type == $pt->transition) echo 'checked="checked"'; ?>/>
				</td>
				<td>
					<label for="<?php echo $type; ?>" class="cspc-page-transition-box <?php echo $type; ?>"><?php echo $val['text']; ?></label>
				</td></tr>
			<?php endforeach; ?>
			</table>
		</div>
		<table id="cspc-page-definition" style="margin-top:5px; border-top: dotted 1px #999; padding: 5px 0px;table-layout:fixed;" width="100%" cellspacing="5px">
			<tr>
				<td width="30%"><?php _e('spacing:', $this->textdomain); ?></td><td width="70%"><strong><?php echo $pt->spacing(); ?> %</strong></td>
			</tr>
			<tr>
				<td><?php _e('columns:', $this->textdomain); ?></td>
				<td>
					<?php for($i=2;$i<7; $i++) { ?>
					<input id="cspc-count-columns-<?php echo $i; ?>" name="cspc-count-columns" type="radio" value="<?php echo $i; ?>" autocomplete="off" <?php if ($pt->columns() == $i) echo 'checked="checked" '; ?>><?php echo $i; ?></input>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;"><?php _e('overflow:', $this->textdomain); ?></td>
				<td>
					<input id="cspc-overflow-hidden" name="cspc-overflow-paging" type="radio" value="hidden" <?php if ($pt->overflow() == 'hidden') echo 'checked="checked"'; ?> autocomplete="off"><?php _e('hide too much columns', $this->textdomain); ?></input><br/>
					<input id="cspc-overflow-virtual" name="cspc-overflow-paging" type="radio" value="virtual" <?php if ($pt->overflow() == 'virtual') echo 'checked="checked"'; ?> autocomplete="off"/><?php _e('generate virtual pages', $this->textdomain); ?></input>
				<td>
			</tr>
			<?php if ($this->versions->above_27) : ?>
			<tr>
				<td style="vertical-align:top;"><?php _e('at overview pages:', $this->textdomain); ?></td>
				<td>
					<input id="cspc-multiposts-same" name="cspc-multiposts-paging" type="radio" value="same" <?php if ($pt->multiposts() == 'same') echo 'checked="checked"'; ?> autocomplete="off"><?php _e('same as single pages', $this->textdomain); ?></input><br/>
					<input id="cspc-multiposts-wp" name="cspc-multiposts-paging" type="radio" value="wp" <?php if ($pt->multiposts() == 'wp') echo 'checked="checked"'; ?> autocomplete="off"/><?php _e('pagination', $this->textdomain); ?></input><br/>
					<input id="cspc-multiposts-flat" name="cspc-multiposts-paging" type="radio" value="flat" <?php if ($pt->multiposts() == 'flat') echo 'checked="checked"'; ?> autocomplete="off"/><?php _e('render flat single content', $this->textdomain); ?></input>
				<td>
			</tr>
			<?php endif; ?>
		</table>
		<div style="margin-top:5px; border-top: dotted 1px #999; padding: 5px 0px;">
			<table class="cspc-page-table">
				<tr>
					<td><input id="cspc-preview-assistent" name="cspc-preview-assistent" type="checkbox" <?php if($this->options->preview_assistent) echo 'checked="checked"'; ?>/></td>
					<td><label for="cspc-preview-assistent">&nbsp;<?php _e('enable Assistance at Preview', $this->textdomain); ?></label></td>			
				</tr>
			</table>
		</div>
		<?php		
	}
	
	//restructure the current page to meet the user configured column layout
	function resample_page_content() {
		global $post, $id, $page, $pages, $multipage, $numpages;
	
		$pt = new Page_columnist_page_transition($this, $post->ID);
		//user can change overview pages to support also columnization, default is off
		if (!is_single()) {
			switch($pt->multiposts()) {
				case 'wp': 
					$pt->transition = $this->page_default_trans;
					break;
				case 'flat':
					$pt->transition = 'cspc-trans-ordinary';
					break;
			}
		}
		if($pt->transition == $this->page_default_trans) return;
		$pages = $pt->execute($pages);
					
		if (count($pages) > 1) {
			$multipage = 1;
			$numpages = count($pages);				
		}
		else{
			$multipage = 0;
			$numpages = 1;
		}

/*			
		//TODO: future versions should check ugly invalid <p> based at shortcode api if possible
		if (has_filter('the_content', 'wpautop')) {
			preg_match('/'.get_shortcode_regex().'(\s\s|)/', $pages[0],$hits);
			var_dump($hits);
		}
*/		
	}
	
	//beginning WP 2.8 the order of how hooks been called have been change
	//that's why the handling of resampling the page if different for 2.7 and 2.8, this have to be respected
	function on_the_post() {
		if ($this->versions->above_27) {
			$this->resample_page_content();
		}
	}
	
	//beginning WP 2.8 the order of how hooks been called have been change
	//that's why the handling of resampling the page if different for 2.7 and 2.8, this have to be respected
	function on_loop_start() {
		if ($this->versions->above_27 == false) {
			if (is_page() || is_single()) {
				$this->resample_page_content();
			}
		}
	}
	
	//if we are in none standard page mode, ensure a redirect to main page slug, if sub-page has been requested directly and doesn't support virtual paging
	function on_template_redirect(){
		global $post, $page;
		if ($post->post_type == 'page' || $post->post_type == 'post') {
			$pt = new Page_columnist_page_transition($this, $post->ID);
			if (($pt->transition != $this->page_default_trans) && $page && $pt->overflow() == 'hidden') {
				wp_redirect(get_permalink($post->ID));
				exit();
			}
		}
	}
	
}

//nothing more remains to do as to create an instance of the plugin itself :-)
global $page_columnist_plug; 
$page_columnist_plug = new Plugin_page_columnist();

}// if !function_exists('cspc_on_activate_plugin'))	{
?>
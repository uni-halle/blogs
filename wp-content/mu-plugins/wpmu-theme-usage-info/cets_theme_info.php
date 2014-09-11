<?php
/*
Plugin Name: WPMU Theme Info
Plugin URI: http://wordpress.org/extend/plugins/wpmu-theme-usage-info/
Description: WordPress plugin for letting network admins easily see what themes are actively used on the network
Version: 1.9
Author: Kevin Graeme, <a href="http://deannaschneider.wordpress.com/" target="_target">Deanna Schneider</a> & <a href="http://www.jasonlemahieu.com/" target="_target">Jason Lemahieu</a>
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
Text Domain: cets-theme-info
Domain Path: /languages
      
	WPMU Theme Info

	Copyright (C) 2009 - 2013 Board of Regents of the University of Wisconsin System
	Cooperative Extension Technology Services
	University of Wisconsin-Extension

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( !class_exists('cets_Theme_Info') ) {
	
	class cets_Theme_Info {
		
		const ID		= 'cets-theme-info';
		const VERSION	= '1.9';

		function __construct() {            
			
			add_filter( 'theme_action_links', array( &$this, 'action_links'), 9, 3);
			add_action( 'switch_theme', array( &$this, 'on_switch_theme'));			

			if ( is_network_admin() ) {
				add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts'));
				add_action( 'network_admin_menu', array( &$this, 'theme_info_add_page'));
				add_filter( 'plugin_row_meta', array( $this, 'set_plugin_meta' ), 10, 2 );
			}

			load_plugin_textdomain( 'cets-theme-info', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			if ( in_array( basename($_SERVER['PHP_SELF']), array('themes.php') ))  {
				// run the function to generate the theme blog list (this runs whenever the theme page reloads, but only regenerates the list if it's more than an hour old or not set yet)
				$gen_time = get_site_option('cets_theme_info_data_freshness');

				if ((time() - $gen_time) > 3600 || strlen($gen_time) == 0)
					$this->generate_theme_blog_list();
			}
		}

		/**
		* PHP 4 constructor
		*/
		function cets_Theme_Info() {
			cets_Theme_Info::__construct();
		}

		function maybe_update() {
			
			$this_version = self::VERSION;
			$plugin_version = get_site_option('cets_theme_info_version', 0);

			if ( version_compare( $plugin_version, $this_version, '<') ) {
				add_site_option('cets_theme_info_data_freshness', 1);
				update_site_option('cets_theme_info_data_freshness', 2);
			}

			if ( $plugin_version == 0 ) {
				add_site_option('cets_theme_info_version', $this_version);
			} else {
				update_site_option('cets_theme_info_version', $this_version);
			}
		}

		function version_supported() {
			global $wp_version;
			$supported_minimum = "3.4";

			if (version_compare($wp_version, $supported_minimum, '<')) {
				return false;
			} else {
				return true;
			}
		}

		function generate_theme_blog_list() {
			
			global $wpdb, $current_site, $wp_version;

			if (!$this->version_supported())
				return;

			//require_once('admin.php');
			$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
			$blogthemes = array();
			$processedthemes = array();
			if ($blogs) {
				foreach ($blogs as $blog) {
					switch_to_blog( $blog->blog_id );
					$cto = wp_get_theme();
					$ct = $cto->stylesheet;

					if( constant( 'VHOST' ) == 'yes' ) {
						$blogurl = $blog->domain;
					} else {
						$blogurl =  trailingslashit( $blog->domain . $blog->path );
					}

					if (array_key_exists($ct, $processedthemes) == false) {
						//echo ("<p> Blogid = " .  $blog->blog_id . " & current theme = " . $ct . "</p>");
						$blogthemes[$ct][0] = array('blogid' => $blog->blog_id, /*'path' => $path, 'domain' => $domain,*/ 'name' => get_bloginfo('name'), 'blogurl' => $blogurl);	
						$processedthemes[$ct] = true;
					} else {
						//get the size of the current array of blogs
						$count = sizeof($blogthemes[$ct]);
						//echo ("<p> Blogid = " .  $blog->blog_id . " & current theme = " . $ct . "</p>");
						$blogthemes["$ct"][$count] = array('blogid' => $blog->blog_id,/* 'path' => $path, 'domain' => $domain,*/ 'name' => get_bloginfo('name'), 'blogurl' => $blogurl);
					}			
					restore_current_blog();
				}
			}
			// Set the site option to hold all this
			update_site_option( 'cets_theme_info_data', $blogthemes);
			update_site_option( 'cets_theme_info_data_freshness', time());
		}

		// $actions       = apply_filters( 'theme_action_links', $actions, $theme );
		function action_links($actions, $theme){
			if (!$this->version_supported())
				return $actions;

			if (!is_object($theme))
				$theme = wp_get_theme($theme);

			// Get the toggle to see if users can view this information
			$allow = get_site_option('cets_theme_info_allow');

			// if it's not the site admin and users aren't allowed to be in here, just get out.
			if ($allow != 1 && !is_super_admin())
				return $actions;

			//get the list of blogs for this theme
			$data = get_site_option('cets_theme_info_data');

			if ( isset($data[$theme->stylesheet]) ) {
				$blogs = $data[$theme->stylesheet];
			} else {
				$blogs = array();
			}

			// get the first param of the actions var and add some more stuff before it	
			//$start = $actions[0];
			$name = str_replace(" ", "_", $theme['Name']);
			$text = "<div class='cets-theme-info'>" . __( 'Used on', 'cets-theme-info') . " ";
			if (sizeOf($blogs) > 0) {
				$text .='<a href="#TB_inline?height=155&width=300&inlineId='. $name . '" class="thickbox" title="' . __( 'Sites that use this theme', 'cets-theme-info') . '">';
			}
			$text .= sizeOf($blogs) . " " . __( 'site', 'cets-theme-info');
			if (sizeOf($blogs) != 1) {$text .= 's';}
			if (sizeOf($blogs)> 0 ) {
				$text .= '</a>';
			}
			$text .=('. ');
			if(sizeOf($blogs) > 0){
				$text .= '<div id="' . $name . '" style="display: none"><div>';

				// loop through the list of blogs and display their titles
				$text .=( __( 'Activated on the following sites:', 'cets-theme-info') . " <ul>");
				foreach ($blogs as $blog){
					$text .= '<li><a href="http://' . $blog['blogurl'] . '" target="new">' . $blog['name'] . '</a></li>';
				}
				$text .= "</li>";
				$text .= "</div></div>";
			} 
			$text .='</div>';

			array_push( $actions, $text );
			return $actions;
		}

		// Create a function to add a menu item for site admins
		function theme_info_add_page() {
			// Add a submenu
			if ( is_network_admin() )
				$this->page = add_submenu_page(
					'themes.php',
					__( 'Theme Usage Info', 'cets-theme-info'),
					__( 'Theme Usage Info', 'cets-theme-info'),
					'manage_network',
					'wpmu-theme-info',
					array( &$this, 'theme_info_page')
				);

			add_action("load-$this->page", array( &$this, 'help_tabs'));
		}

		function help_tabs() {
			$screen = get_current_screen();
			$screen->add_help_tab( array(
				'id'        => 'cets_theme_info_about',
				'title'     => __('About', 'cets-theme-info'),
				'callback'  => array( &$this, 'about_tab')
			));       
		}

		function about_tab() { ?>
			<style>.tab-about li { list-style: none; }</style>
			<h1>WPMU Theme Info</h1>
			<p>
				<a href="http://wordpress.org/extend/plugins/wpmu-theme-usage-info/" target="_blank">WordPress.org</a> | 
				<a href="https://github.com/wp-repository/wpmu-theme-usage-info" target="_blank">GitHub Repository</a> | 
				<a href="https://github.com/wp-repository/wpmu-theme-usage-info/issues" target="_blank">Issue Tracker</a>
			</p>
			<ul class="tab-about">
				<li><b><?php _e( 'Development', 'cets-theme-info'); ?>:</b>
					<ul>
						<li>Kevin Graeme | <a href="http://profiles.wordpress.org/kgraeme/" target="_blank">kgraeme@WP.org</a></li>
						<li><a href="http://deannaschneider.wordpress.com/" target="_blank">Deanna Schneider</a> | <a href="http://profiles.wordpress.org/deannas/" target="_blank">deannas@WP.org</a></li>
						<li><a href="http://www.jasonlemahieu.com/" target="_blank">Jason Lemahieu</a> | <a href="http://profiles.wordpress.org/MadtownLems/" target="_blank">MadtownLems@WP.org</a></li>
					</ul>
				</li>
				<li><b>WordPress:</b>
					<ul>
						<li><?php printf( __( 'Requires at least: %s', 'cets-theme-info'), '3.4'); ?></li>
						<li><?php printf( __( 'Tested up to: %s', 'cets-theme-info'), '3.5.1'); ?></li>
					</ul>
				</li>
				<li><b><?php _e( 'Languages', 'cets-theme-info'); ?>:</b>
					<ul>
						<li>English (development), German</li>
						<li><?php printf( __( 'Help to translate at %s', 'cets-theme-info'), '<a href="https://translate.foe-services.de/projects/cets-theme-info" target="_blank">Translate > WPMU Theme Info</a>'); ?></li>
					</ul>
				</li>
				<li><b><?php _e( 'License', 'cets-theme-info'); ?>:</b></li>
				<li>
					<p>Copyright 2009-2013 Board of Regents of the University of Wisconsin System<br />
					Cooperative Extension Technology Services<br />
					University of Wisconsin-Extension</p>
				</li>
			</ul>
		<?php 
		}

		// Create a function to actually display stuff on theme usage
		function theme_info_page( $active_tab = '' ) {            
			$this->maybe_update();

			if (!$this->version_supported()) {
				echo "<div class='wrap'><h2>" . printf( __( 'Theme Usage Information %s This plugin requires at least WordPress version 3.4 - Please upgrade to stay safe and secure.', 'cets-theme-info'), '</h2>') . "</div>";
				return;
			}
			//Handle updates
			if ( isset($_POST['action']) && $_POST['action'] == 'update' ) {
				update_site_option('cets_theme_info_allow', $_POST['usage_flag']);
			?>
				<div id="message" class="updated fade"><p><?php _e( 'Settings saved.') ?></p></div>
		   <?php
			}

			// get the usage setting
			$usage_flag = get_site_option('cets_theme_info_allow');

			// if it's not set, set it to zero (the default)
			if (strlen($usage_flag) == 0) {
				$usage_flag = 0;
				update_site_option('cets_theme_info_allow', 0);
			}

			// Get the time when the theme list was last generated
			$gen_time = get_site_option('cets_theme_info_data_freshness');

			if ( (time() - $gen_time) > 3600 )
				// if older than an hour, regenerate, just to be safe
				$this->generate_theme_blog_list();	

			$allowed_themes = WP_Theme::get_allowed_on_network();

			// returns an array of Theme Objects
			$themes = wp_get_themes();

			$list = get_site_option('cets_theme_info_data');
			ksort($list);

			// figure out what themes have not been used at all
			$unused_themes = array();
			foreach ( $themes as $theme ) {
				if (!array_key_exists($theme->stylesheet, $list)) {
					//if (!array_key_exists($theme['Name'], $list))
					array_push($unused_themes, $theme);

				}
			}
			?>
			<style type="text/css">
				.tab-body {
					padding: 10px;
					border-style: solid;
					border-width: 0 1px 1px 1px;
					border-color: #CCCCCC;
				}
				.bloglist {
					display:none;
				}
				.pc_settings_heading {
					text-align: center; 
					border-right:  3px solid black;
					border-left: 3px solid black;
				}
				.pc_settings_left {
					border-left: 3px solid black;
				}
				.pc_settings_right {
					border-right: 3px solid black;
				}
				.widefat tr:hover td {
					background-color: #DDD;
				}
			</style>
			<div class="wrap">
				<?php screen_icon( 'themes' ); ?>
				<h2><?php _e( 'Theme Usage Information', 'cets-theme-info'); ?></h2>

				<?php
				if ( isset($_GET['tab']) ) {
					$active_tab = $_GET['tab'];
				} else if ( $active_tab == 'settings' ) {
					$active_tab = 'settings';
				} else {
					$active_tab = 'themes';
				} 
				?>

				<h2 class="nav-tab-wrapper">
					<a href="?page=wpmu-theme-info.php&tab=themes" class="nav-tab <?php echo $active_tab == 'themes' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Themes', 'cets-theme-info'); ?></a>
					<a href="?page=wpmu-theme-info.php&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'cets-theme-info'); ?></a>
				</h2>

				<?php if ($active_tab == 'settings') { ?>
					<div class="tab-body">
						<h2><?php _e('Manage User Access', 'cets-theme-info'); ?></h2>
						<p><?php _e('Users can see usage information for themes in Appearance -> Themes. You can control user access to that information via this toggle.', 'cets-theme-info'); ?></p>
						<form name="themeinfoform" action="" method="post">
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><?php _e('Let Users View Theme Usage Information:', 'cets-theme-info'); ?> </th>
										<td>
											<label><input type="radio" name="usage_flag" value="1" <?php checked('1', $usage_flag) ?> /> <?php _e('Yes') ?></label><br/>
											<label><input type="radio" name="usage_flag" value="0" <?php checked('0', $usage_flag) ?> /> <?php _e('No') ?></label>
										</td>
									</tr>
								</tbody>
							</table>		
							<p>
								<input type="hidden" name="action" value="update" />
								<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
							</p>
						</form>
					</div>

					<?php } else { ?>

					<div class="tab-body">
						<table class="widefat" id="cets_active_themes">
							<thead>
								<tr>
									<th class="nocase"><?php _e( 'Used Themes', 'cets-theme-info'); ?></th>
									<th class="case" style="text-align: center !important;"><?php _e( 'Activated Sitewide', 'cets-theme-info'); ?></th>
									<th class="num"><?php _e( 'Total Sites', 'cets-theme-info'); ?></th>
									<th><?php _e( 'Site Titles', 'cets-theme-info'); ?></th>
								</tr>
							</thead>
							<tbody id="themes">
								<?php
								$counter = 0;
								foreach ( $list as $theme => $blogs ) {
									$theme_object = wp_get_theme($theme);
									$counter = $counter + 1;
									echo('<tr valign="top"><td>' .$theme_object->name .'</td><td class="num">');

									// get the array for this theme
									if ( isset($themes[$theme]) ) {
										$thisTheme = $themes[$theme];

										if (array_key_exists($thisTheme['Stylesheet'], $allowed_themes)) { 
											_e( 'Yes');
										} else {
											_e( 'No'); 
										}
									} else {
										_e( 'Theme Files Not Found!', 'cets-theme-info');
									}
									echo ('</td><td class="num">' . sizeOf($blogs) . '</td><td>');
									?>
									<a href="javascript:void(0)" onClick="jQuery('#bloglist_<?php echo $counter; ?>').toggle(400);"><?php _e( 'Show/Hide Sites', 'cets-theme-info'); ?></a>
									<?php
									echo ('<ul class="bloglist" id="bloglist_' . $counter  . '">');
									foreach ( $blogs as $key => $row ){
										$name[$key] = $row['name'];
										$blogurl[$key] = $row['blogurl'];
									}
									if (sizeOf($name) == sizeOf($blogs))
										array_multisort($name, SORT_ASC, $blogs);

									foreach ( $blogs as $blog ) {
										echo ('<li><a href="http://' . $blog['blogurl'] . '" target="new">' . $blog['name'] . '</a></li>');
									}

									echo ('</ul></td>');
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th class="nocase"><?php _e( 'Used Themes', 'cets-theme-info'); ?></th>
									<th class="case" style="text-align: center !important;"><?php _e( 'Activated Sitewide', 'cets-theme-info'); ?></th>
									<th class="num"><?php _e( 'Total Sites', 'cets-theme-info'); ?></th>
									<th><?php _e( 'Site Titles', 'cets-theme-info'); ?></th>
								</tr>
							</tfoot>
						</table>
						<p>&nbsp;</p>
						<table class="widefat">
							<thead>
								<tr>
									<th class="nocase"><?php _e( 'Unused Themes', 'cets-theme-info'); ?></th>
									<th class="case" style="text-align: center !important;"><?php _e( 'Activated Sitewide', 'cets-theme-info'); ?></th>
									<th class="num"><?php _e( 'Total Sites', 'cets-theme-info'); ?></th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody id="plugins">
							<?php
							asort($unused_themes);
							foreach($unused_themes as $theme) {
								echo ("<tr><td>" . $theme['Name'] . "</td><td class=\"num\">");
								if ( array_key_exists($theme['Stylesheet'], $allowed_themes) ) { 
									_e( 'Yes'); 
								}
								else {
									_e( 'No'); 
								}
								echo("</td><td class=\"num\">0</td><td>&nbsp;</td></tr>");
							}
							?>	
							</tbody>
							<tfoot>
								<tr>
									<th class="nocase"><?php _e( 'Unused Themes', 'cets-theme-info'); ?></th>
									<th class="case" style="text-align: center !important;"><?php _e( 'Activated Sitewide', 'cets-theme-info'); ?></th>
									<th class="num"><?php _e( 'Total Sites', 'cets-theme-info'); ?></th>
									<th>&nbsp;</th>
								</tr>
							</tfoot>
						</table>                        
					</div>
					<?php } ?>
			</div>
		<?php 
		}

		function on_switch_theme() {
			if (!$this->version_supported())
				return;

			$this->generate_theme_blog_list();
		}
		
		function load_scripts() {
			
			$screen = get_current_screen();
			
			// if ( $screen->id == $this->page . '-network' ) {
				wp_register_script( 'tablesort', plugins_url('js/tablesort-2.4.min.js', __FILE__), array(), '2.4', true);
				wp_enqueue_script( 'tablesort' );
			//}
			
		}
		
		function set_plugin_meta( $links, $file ) {
			
			if ( $file == plugin_basename( __FILE__ ) ) {
				return array_merge(
					$links,
					array( '<a href="https://github.com/wp-repository/wpmu-theme-usage-info" target="_blank">GitHub</a>' )
				);
			}
			
			return $links;
		}
		
	} // END class cets_Theme_Info

	$GLOBALS['cets_Theme_Info'] = new cets_Theme_Info();
	
}// END if class_exists

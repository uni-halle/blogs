<?php

/**
 * Module to check the modules that are needed
 *
 * @link       https://wordpress.org/plugins/fg-joomla-to-wordpress/
 * @since      2.0.0
 *
 * @package    FG_Joomla_to_WordPress
 * @subpackage FG_Joomla_to_WordPress/admin
 */

if ( !class_exists('FG_Joomla_to_WordPress_Modules_Check', false) ) {

	/**
	 * Class to check the modules that are needed
	 *
	 * @package    FG_Joomla_to_WordPress
	 * @subpackage FG_Joomla_to_WordPress/admin
	 * @author     Frédéric GILLES
	 */
	class FG_Joomla_to_WordPress_Modules_Check {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    2.0.0
		 * @param    object    $plugin       Admin plugin
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;

		}

		/**
		 * Check if some modules are needed
		 *
		 * @since    2.0.0
		 */
		public function check_modules() {
			$premium_url = 'http://www.fredericgilles.net/fg-joomla-to-wordpress/';
			$message_premium = __('Your Joomla database contains %s. You need the <a href="%s" target="_blank">Premium version</a> to import them.', 'fgj2wp');
			if ( defined('FGJ2WPP_LOADED') ) {
				// Message for the Premium version
				$message_addon = __('Your Joomla database contains %1$s. You need the <a href="%3$s" target="_blank">%4$s</a> to import them.', 'fgj2wp');
			} else {
				// Message for the free version
				$message_addon = __('Your Joomla database contains %1$s. You need the <a href="%2$s" target="_blank">Premium version</a> and the <a href="%3$s" target="_blank">%4$s</a> to import them.', 'fgj2wp');
			}
			$modules = array(
				array('users', 2,
					'fg-joomla-to-wordpress-premium/fg-joomla-to-wordpress-premium.php',
					sprintf($message_premium, __('several users', 'fgj2wp'), $premium_url)
				),
				array('tags', 1,
					'fg-joomla-to-wordpress-premium/fg-joomla-to-wordpress-premium.php',
					sprintf($message_premium, __('some tags', 'fgj2wp'), $premium_url)
				),
				array('k2_items', 10,
					'fg-joomla-to-wordpress-premium-k2-module/fgj2wp-k2.php',
					sprintf($message_addon, __('some K2 items', 'fgj2wp'), $premium_url, $premium_url . 'k2/', __('K2 add-on', 'fgj2wp'))
				),
				array('flexicontent_versions', 1,
					'fg-joomla-to-wordpress-premium-flexicontent-module/fgj2wp-flexicontent.php',
					sprintf($message_addon, __('some Flexicontent items', 'fgj2wp'), $premium_url, $premium_url . 'flexicontent/', __('Flexicontent add-on', 'fgj2wp'))
				),
				array('easyblog_post', 10,
					'fg-joomla-to-wordpress-premium-easyblog-module/fgj2wp-easyblog.php',
					sprintf($message_addon, __('some EasyBlog posts', 'fgj2wp'), $premium_url, $premium_url . 'easyblog/', __('EasyBlog add-on', 'fgj2wp'))
				),
				array('jf_content', 10,
					'fg-joomla-to-wordpress-premium-joomfish-module/fgj2wp-joomfish.php',
					sprintf($message_addon, __('some Joom!Fish translations', 'fgj2wp'), $premium_url, $premium_url . 'joomfish/', __('Joom!Fish add-on', 'fgj2wp'))
				),
				array('zoo_item', 10,
					'fg-joomla-to-wordpress-premium-zoo-module/fgj2wp-zoo.php',
					sprintf($message_addon, __('some Zoo items', 'fgj2wp'), $premium_url, $premium_url . 'zoo/', __('Zoo add-on', 'fgj2wp'))
				),
				array('sh404sef_urls', 10,
					'fg-joomla-to-wordpress-premium-sh404sef-module/fgj2wp-sh404sef.php',
					sprintf($message_addon, __('some sh404sef redirections', 'fgj2wp'), $premium_url, $premium_url . 'sh404sef/', __('sh404sef add-on', 'fgj2wp'))
				),
				array('redirection', 10,
					'fg-joomla-to-wordpress-premium-sh404sef-module/fgj2wp-sh404sef.php',
					sprintf($message_addon, __('some sh404sef redirections', 'fgj2wp'), $premium_url, $premium_url . 'sh404sef/', __('sh404sef add-on', 'fgj2wp'))
				),
				array('sefurls', 10,
					'fg-joomla-to-wordpress-premium-joomsef-module/fgj2wp-joomsef.php',
					sprintf($message_addon, __('some JoomSEF redirections', 'fgj2wp'), $premium_url, $premium_url . 'joomsef/', __('JoomSEF add-on', 'fgj2wp'))
				),
				array('opensef_sef', 10,
					'fg-joomla-to-wordpress-premium-opensef-module/fgj2wp-opensef.php',
					sprintf($message_addon, __('some OpenSEF redirections', 'fgj2wp'), $premium_url, $premium_url . 'opensef/', __('OpenSEF add-on', 'fgj2wp'))
				),
				array('jcomments', 10,
					'fg-joomla-to-wordpress-premium-jcomments-module/fgj2wp-jcomments.php',
					sprintf($message_addon, __('some JComments comments', 'fgj2wp'), $premium_url, $premium_url . 'jcomments/', __('JComments add-on', 'fgj2wp'))
				),
				array('jomcomment', 10,
					'fg-joomla-to-wordpress-premium-jomcomment-module/fgj2wp-jomcomment.php',
					sprintf($message_addon, __('some JomComment comments', 'fgj2wp'), $premium_url, $premium_url . 'jomcomment/', __('JomComment add-on', 'fgj2wp'))
				),
				array('kunena_version', 0,
					'fg-joomla-to-wordpress-premium-kunena-module/fgj2wp-kunena.php',
					sprintf($message_addon, __('a Kunena forum', 'fgj2wp'), $premium_url, $premium_url . 'kunena/', __('Kunena add-on', 'fgj2wp'))
				),
				array('fb_version', 0,
					'fg-joomla-to-wordpress-premium-kunena-module/fgj2wp-kunena.php',
					sprintf($message_addon, __('a Kunena forum', 'fgj2wp'), $premium_url, $premium_url . 'kunena/', __('Kunena add-on', 'fgj2wp'))
				),
				array('attachments', 10,
					'fg-joomla-to-wordpress-premium-attachments-module/fgj2wp-attachments.php',
					sprintf($message_addon, __('some attachments', 'fgj2wp'), $premium_url, $premium_url . 'attachments/', __('Attachments add-on', 'fgj2wp'))
				),
				array('tag_term', 1,
					'fg-joomla-to-wordpress-premium-joomlatags-module/fgj2wp-joomlatags.php',
					sprintf($message_addon, __('some Joomlatags tags', 'fgj2wp'), $premium_url, $premium_url . 'joomlatags/', __('Joomlatags add-on', 'fgj2wp'))
				),
				array('joomgallery', 2,
					'fg-joomla-to-wordpress-premium-joomgallery-module/fgj2wp-joomgallery.php',
					sprintf($message_addon, __('some JoomGallery galleries', 'fgj2wp'), $premium_url, $premium_url . 'joomgallery/', __('JoomGallery add-on', 'fgj2wp'))
				),
				array('phocagallery', 2,
					'fg-joomla-to-wordpress-premium-phocagallery-module/fgj2wp-phocagallery.php',
					sprintf($message_addon, __('some PhocaGallery galleries', 'fgj2wp'), $premium_url, $premium_url . 'phoca-gallery/', __('PhocaGallery add-on', 'fgj2wp'))
				),
				array('jevents_vevent', 0,
					'fg-joomla-to-wordpress-premium-jevents-module/fgj2wp-jevents.php',
					sprintf($message_addon, __('some JEvents events', 'fgj2wp'), $premium_url, $premium_url . 'jevents/', __('JEvents add-on', 'fgj2wp'))
				),
				array('contact_details', 1,
					'fg-joomla-to-wordpress-premium-contactmanager-module/fgj2wp-contactmanager.php',
					sprintf($message_addon, __('some contacts', 'fgj2wp'), $premium_url, $premium_url . 'contact-manager/', __('Contact Manager add-on', 'fgj2wp'))
				),
				array('docman', 0,
					'fg-joomla-to-wordpress-premium-docman-module/fgj2wp-docman.php',
					sprintf($message_addon, __('some Docman documents', 'fgj2wp'), $premium_url, $premium_url . 'docman/', __('Docman add-on', 'fgj2wp'))
				),
				array('virtuemart_products', 0,
					'fg-joomla-to-wordpress-premium-virtuemart-module/fgj2wp-virtuemart.php',
					sprintf($message_addon, __('a Virtuemart e-commerce solution', 'fgj2wp'), $premium_url, $premium_url . 'virtuemart/', __('Virtuemart add-on', 'fgj2wp'))
				),
				array('vm_product', 0,
					'fg-joomla-to-wordpress-premium-virtuemart-module/fgj2wp-virtuemart.php',
					sprintf($message_addon, __('a Virtuemart e-commerce solution', 'fgj2wp'), $premium_url, $premium_url . 'virtuemart/', __('Virtuemart add-on', 'fgj2wp'))
				),
				array('jreviews_content', 0,
					'fg-joomla-to-wordpress-premium-jreviews-module/fgj2wp-jreviews.php',
					sprintf($message_addon, __('some JReviews data', 'fgj2wp'), $premium_url, $premium_url . 'jreviews/', __('JReviews add-on', 'fgj2wp'))
				),
				array('mt_links', 0,
					'fg-joomla-to-wordpress-premium-mosetstree-module/fgj2wp-mosetstree.php',
					sprintf($message_addon, __('a Mosets Tree directory', 'fgj2wp'), $premium_url, $premium_url . 'mosetstree/', __('Mosets Tree add-on', 'fgj2wp'))
				),
				array('usergroups', 10,
					'fg-joomla-to-wordpress-premium-usergroups-module/fgj2wp-usergroups.php',
					sprintf($message_addon, __('user groups', 'fgj2wp'), $premium_url, $premium_url . 'usergroups/', __('User Groups add-on', 'fgj2wp'))
				),
			);
			foreach ( $modules as $module ) {
				list($table, $count, $plugin, $message) = $module;
					if ( !is_plugin_active($plugin) ) {
						if ( $this->count($table) > $count ) {
							$this->plugin->display_admin_error($message);
						}
					}
			}
			
			// Check if we need the WPML module
			if ( ($this->count_languages() > 2) && !is_plugin_active('fg-joomla-to-wordpress-premium-wpml-module/fgj2wp-wpml.php') ) {
				$message = sprintf($message_addon, __('several languages', 'fgj2wp'), $premium_url, $premium_url . 'wpml/', __('WPML add-on', 'fgj2wp'));
				$this->plugin->display_admin_error($message);
			}
		}

		/**
		 * Count the number of rows in the table
		 *
		 * @param string $table Table
		 * @return int Number of rows
		 */
		private function count($table) {
			$prefix = $this->plugin->plugin_options['prefix'];
			$sql = "SELECT COUNT(*) AS nb FROM ${prefix}${table}";
			return $this->count_sql($sql);
		}

		/**
		 * Count the number languages used (Joomla 2.5+)
		 *
		 * @return int Number of languages used
		 */
		private function count_languages() {
			$prefix = $this->plugin->plugin_options['prefix'];
			$sql = "SELECT COUNT(DISTINCT `language`) AS nb FROM ${prefix}content AS nb";
			return $this->count_sql($sql);
		}

		/**
		 * Execute the SQL request and return the nb value
		 *
		 * @param string $sql SQL request
		 * @return int Count
		 */
		private function count_sql($sql) {
			$count = 0;
			$result = $this->plugin->joomla_query($sql, false);
			if ( isset($result[0]['nb']) ) {
				$count = $result[0]['nb'];
			}
			return $count;
		}

	}
}

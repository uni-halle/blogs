<?php
/*
Plugin Name: Delicious XML Importer
Plugin URI: http://sillybean.net/code/wordpress/delicious/
Description: Import links or posts from Delicious bookmarks. Supports Tasty Links theme.
Author: Guillermo Moreno, Stephanie Leary
Author URI: http://sillybean.net/
Version: 0.4
Stable tag: 0.4
*/

if ( !defined('WP_LOAD_IMPORTERS') )
	return;

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( !class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require_once $class_wp_importer;
}

if ( class_exists( 'WP_Importer' ) ) {
class Delicious_Import extends WP_Importer {

	var $posts = array ();
	var $file;

	function header() {
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>'.__('Import Delicious XML', 'delicious-xml-importer').'</h2>';
	}

	function footer() {
		echo '</div>';
	}

	function unhtmlentities($string) { // From php.net for < 4.3 compat
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}

	function greet() {
		?>
		<div class="narrow">
		<p><?php _e('This importer allows you to import posts or links from your Delicious bookmarks.', 'delicious-xml-importer'); ?>
		<?php printf(__('<a href="%s">Visit this link to get an XML file of your bookmarks.</a> You will be asked to log in to your Delicious account.', 'delicious-xml-importer'), 'https://api.del.icio.us/v1/posts/all?meta=yes'); ?></p>
		<p><?php printf(__('<strong>Tip:</strong> Use the API\'s <a href="%s">arguments</a> to select which links you export. The exporter handles 1000 links at a time, so if you have more than that, you can use the date parameter to fetch the older ones. You can also use it to select only the links saved since your last import.', 'delicious-xml-importer'), 'http://www.delicious.com/help/api#posts_all') ?>
		
		<form enctype="multipart/form-data" method="post" action="admin.php?import=delicious&amp;step=1"><p>
		
		<label for="upload"><?php _e("Choose the XML file from your computer:", 'delicious-xml-importer'); ?></label>
		<input type="file" id="upload" name="import" size="25" />
		<input type="hidden" name="action" value="save" />
		<p><label for="bookmarksposts"><?php _e("Do you want your Delicious Bookmarks as", 'delicious-xml-importer'); ?> </label>
			<select name="bookmarksposts">
				<option value="post"><?php _e("Posts", 'delicious-xml-importer'); ?></option>
				<option value="links"><?php _e("Links", 'delicious-xml-importer'); ?></option>
				<?php
				$post_types = get_post_types(array('public'=>true,'_builtin'=>false), 'objects');
				foreach ($post_types as $type) {
					echo "<option value=\"" . esc_attr($type->name) . "\">" . esc_html($type->label) . "</option>";
				}				
				?>
			</select>?
		</p>
		
		<p><label for="categoriestags"><?php _e("If posts, do you want your Delicious Tags as ", 'delicious-xml-importer'); ?></label>
			<select name="categoriestags">
				<option><?php _e("Categories", 'delicious-xml-importer'); ?></option>
				<option><?php _e("Tags", 'delicious-xml-importer'); ?></option>
			</select>?
		</p>
		
		<p><label for="custom_field"><?php _e("If posts, do you want the links stored ", 'delicious-xml-importer'); ?></label>
			<select name="custom_field">
				<option value="0"><?php _e("in the post content", 'delicious-xml-importer'); ?></option>
				<option value="1"><?php _e("in a custom field", 'delicious-xml-importer'); ?></option>
			</select>?
			<br />&nbsp;&nbsp;&nbsp;<label for="custom_field_name">Custom field name:</label> <input type="text" name="custom_field_name" size="25" />
		</p>
		<p class="submit">
			<input type="submit" name="submit" class="button" value="<?php echo esc_attr(__('Submit', 'delicious-xml-importer')); ?>" />
		</p>
		<?php wp_nonce_field('delicious-import'); ?>
		</form>
		</div>
	<?php
	}

	function get_posts($post_type = 'post') {
		global $wpdb;
		set_magic_quotes_runtime(0);
		$datalines = file($this->file); // Read the file into an array
		$importdata = implode('', $datalines); // squish it
		$importdata = str_replace(array ("\r\n", "\r"), "\n", $importdata);
		$xml = simplexml_load_string($importdata);
		$index = 0;
		foreach($xml->post as $post) {
			$post_title = $post['description'];
			$post_date_gmt = $post['time'];
			$post_date_gmt = strtotime($post_date_gmt);
			$post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
			$post_date = get_date_from_gmt( $post_date_gmt );
			$category = $post['tag'];
			if ($category == 'empty')
				$category = '';
			$categories = explode(" ", $category);
			$cat_index = 0;
			foreach ($categories as $category) {
				$categories[$cat_index] = $wpdb->escape($this->unhtmlentities($category));
				$cat_index++;
			}
			$post_content = $post['extended'];
			if ($post_content == 'empty')
				$post_content = '';
			else {
				$post_content = $wpdb->escape($this->unhtmlentities(trim($post_content)));
				$post_content = preg_replace_callback('|<(/?[A-Z]+)|', create_function('$match', 'return "<" . strtolower($match[1]);'), $post_content);
				$post_content = str_replace('<br>', '<br />', $post_content);
				$post_content = str_replace('<hr>', '<hr />', $post_content);
			}

			$post_link = $post['href'];
			$post_link = $wpdb->escape($this->unhtmlentities(trim($post_link)));
			
			$post_author = 1;
			if ($post['shared'] == 'no') $post_status = 'private';
			else $post_status = 'publish';
			$this->posts[$index] = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'categories', 'post_link', 'post_type');
			$index++;
		}
	}
	
	function get_links() {
		global $wpdb;
		set_magic_quotes_runtime(0);
		$datalines = file($this->file); // Read the file into an array
		$importdata = implode('', $datalines); // squish it
		$importdata = str_replace(array ("\r\n", "\r"), "\n", $importdata);
		$xml = simplexml_load_string($importdata);
		$index = 0;
		foreach($xml->post as $post) {
			$link_name = $post['description'];
			$link_visible = 'Y';
			if ($post['shared'] == 'no') 
				$link_visible = 'N';
			$post_date_gmt = $post['time'];
			$post_date_gmt = strtotime($post_date_gmt);
			$post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
			$link_updated = get_date_from_gmt( $post_date_gmt );
			$category = $post['tag'];
			if ($category == 'empty')
				$category = '';
			$link_category = explode(" ", $category);
			$cat_index = 0;
			foreach ($link_category as $category) {
				$cat_name = $wpdb->escape($this->unhtmlentities($category));
				$slug = sanitize_title($cat_name);
				$link_id = term_exists($slug, 'link_category');
				if (!$link_id) {
					$link_id = wp_insert_term($cat_name, 'link_category');
				}
				elseif (is_object($link_id))
					$link_id = $link_id->term_id;
				if (!is_wp_error( $link_id ) ) {
					$link_category[$cat_index] = $link_id['term_id'];
					$cat_index++;
				}
			}
			$link_notes = $post['extended'];
			if ($link_notes == 'empty')
				$link_notes = '';
			else {
				$link_notes = $wpdb->escape($this->unhtmlentities(trim($link_notes)));
				$link_notes = preg_replace_callback('|<(/?[A-Z]+)|', create_function('$match', 'return "<" . strtolower($match[1]);'), $link_notes);
				$link_notes = str_replace('<br>', '<br />', $link_notes);
				$link_notes = str_replace('<hr>', '<hr />', $link_notes);
			}

			$link_url = $post['href'];
			$link_url = $wpdb->escape($this->unhtmlentities(trim($link_url)));

			$this->posts[$index] = compact('link_url', 'link_name', 'link_updated', 'link_notes', 'link_visible', 'link_category');
			$index++;
		}
	}

	function import_posts() {
		echo '<ol>';
		$categoriestags = $_POST['categoriestags'];
		$cat_id = $_POST['cat_id'];
		$postmeta = $_POST['custom_field'];
		$fieldname = $_POST['custom_field_name'];
		foreach ($this->posts as $post) {
			extract($post);
			echo "<li>".sprintf(__('Importing %s...', 'delicious-xml-importer'), $post_title);

			if ($post_id = post_exists($post_title, $post_content, $post_date)) {
				_e('Post already imported', 'delicious-xml-importer');
			} else {
				if (!$postmeta)
					$post_content = '<p class="delicious_post_link"><a href="'.$post_link.'">'.$post_title.'</a></p>'.$post_content;
					
				$post_id = wp_insert_post($post);
				
				if ( is_wp_error( $post_id ) )
					return $post_id;
				if (!$post_id) {
					_e('Couldn&#8217;t get post ID', 'delicious-xml-importer');
					return;
				}
				
				if (0 != count($categories)) {
					if ($categoriestags == 'Tags')
						wp_add_post_tags($post_id, $categories);
					else
						wp_create_categories($categories, $post_id);
				} 
				
				if ($postmeta)
					add_post_meta($post_id, $fieldname, $post_link, true);
				
				_e('Done!', 'delicious-xml-importer');
			}
			echo '</li>';
		}

		echo '</ol>';

	}
	
	function import_links() {
		echo '<ol>';

		foreach ($this->posts as $post) {
			echo "<li>".__('Importing ', 'delicious-xml-importer');

			extract($post);

			$link_id = wp_insert_link($post);
				if ( is_wp_error( $link_id ) )
					return $link_id;
				else 
					echo ' <em>'.$link_name.'... </em>';
					
				if (!$link_id) {
					_e('Couldn&#8217;t get link ID', 'delicious-xml-importer');
					return;
				}
				_e('Done!', 'delicious-xml-importer');
			echo '</li>';
		}

		echo '</ol>';

	}

	function import() {
		$file = wp_import_handle_upload();
		if ( isset($file['error']) ) {
			echo $file['error'];
			return;
		}

		$this->file = $file['file'];
		if ($_POST['bookmarksposts'] == 'links') {
			$this->get_links();
			$result = $this->import_links();
			$done = 'link-manager.php';
		}
		else {
			$this->get_posts($_POST['bookmarksposts']);
			$result = $this->import_posts();
			$done = 'edit.php?post_type='.$_POST['bookmarksposts'];
		}
		
		if ( is_wp_error( $result ) )
			return $result;
		wp_import_cleanup($file['id']);
		do_action('import_done', 'delicious');

		echo '<h3>';
		printf(__('All done. <a href="%s">Have fun!</a>', 'delicious-xml-importer'), $done);
		echo '</h3>';
	}

	function dispatch() {
		if (empty ($_GET['step']))
			$step = 0;
		else
			$step = (int) $_GET['step'];

		$this->header();

		switch ($step) {
			case 0 :
				$this->greet();
				break;
			case 1 :
				check_admin_referer('delicious-import');
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		}

		$this->footer();
	}

	function Delicious_Import() {
		// Nothing.
	}
}

} // class_exists( 'WP_Importer' )

$delicious_import = new Delicious_Import();

register_importer('delicious', __('Delicious', 'delicious-xml-importer'), __('Import posts from a Delicious XML export file.', 'delicious-xml-importer'), array ($delicious_import, 'dispatch'));

function delicious_xml_importer_init() {
    load_plugin_textdomain( 'delicious-xml-importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'delicious_xml_importer_init' );
?>
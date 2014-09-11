<?php
/**
 * Renders form elements for admin settings pages.
 */
class Wdgpo_AdminFormRenderer {
	function _get_option () {
		return WP_NETWORK_ADMIN ? get_site_option('wdgpo') : get_option('wdgpo');
	}

	function _create_checkbox ($name) {
		$opt = $this->_get_option();
		$value = @$opt[$name];
		return
			"<input type='radio' name='wdgpo[{$name}]' id='{$name}-yes' value='1' " . ((int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-yes'>" . __('Yes', 'wdgpo') . "</label>" .
			'&nbsp;' .
			"<input type='radio' name='wdgpo[{$name}]' id='{$name}-no' value='0' " . (!(int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-no'>" . __('No', 'wdgpo') . "</label>" .
		"";
	}

	function _create_radiobox ($name, $value) {
		$opt = $this->_get_option();
		$checked = (@$opt[$name] == $value) ? true : false;
		return "<input type='radio' name='wdgpo[{$name}]' id='{$name}-{$value}' value='{$value}' " . ($checked ? 'checked="checked" ' : '') . " /> ";
	}


	function create_appearance_box () {
		$appearances = array (
			'small' => __('Small: %s', 'wdgpo'),
			'medium' => __('Medium: %s', 'wdgpo'),
			'standard' => __('Standard: %s', 'wdgpo'),
			'tall' => __('Tall: %s', 'wdgpo'),
		);
		foreach ($appearances as $pos => $label) {
			$img = "<br /><img src='" . WDGPO_PLUGIN_URL . "/img/{$pos}.png' /> <img src='" . WDGPO_PLUGIN_URL . "/img/{$pos}-count.png' />";
			echo $this->_create_radiobox ('appearance', $pos);
			echo "<label for='appearance-{$pos}'>" . sprintf($label, $img) . "</label><br />";
		}
	}

	function create_show_count_box () {
		echo $this->_create_checkbox ('show_count');
	}

	function create_front_page_box () {
		echo $this->_create_checkbox ('front_page');
	}

	function create_position_box () {
		$positions = array (
			'top' => __('Before the post', 'wdgpo'),
			'bottom' => __('After the post', 'wdgpo'),
			'both' => __('Both before and after the post', 'wdgpo'),
			'manual' => __('Manually position the box using shortcode or widget', 'wdgpo'),
		);
		foreach ($positions as $pos => $label) {
			echo $this->_create_radiobox ('position', $pos);
			echo "<label for='position-{$pos}'>$label</label><br />";
		}
	}

	function create_language_box () {
		$languages = array(
			"" => "",
			"Arabic" => "ar",
			"Bulgarian" => "bg",
			"Catalan" => "ca",
			"Chinese (Simplified)" => "zh-CN",
			"Chinese (Traditional)" => "zh-TW",
			"Croatian" => "hr",
			"Czech" => "cs",
			"Danish" => "da",
			"Dutch" => "nl",
			"English (UK)" => "en-GB",
			"English (US)" => "en-US",
			"Estonian" => "et",
			"Filipino" => "fil",
			"Finnish" => "fi",
			"French" => "fr",
			"German" => "de",
			"Greek" => "el",
			"Hebrew" => "iw",
			"Hindi" => "hi",
			"Hungarian" => "hu",
			"Indonesian" => "id",
			"Italian" => "it",
			"Japanese" => "ja",
			"Korean" => "ko",
			"Latvian" => "lv",
			"Lithuanian" => "lt",
			"Malay" => "ms",
			"Norwegian" => "no",
			"Persian" => "fa",
			"Polish" => "pl",
			"Portuguese (Brazil)" => "pt-BR",
			"Portuguese (Portugal)" => "pt-PT",
			"Romanian" => "ro",
			"Russian" => "ru",
			"Serbian" => "sr",
			"Slovak" => "sk",
			"Slovenian" => "sl",
			"Spanish" => "es",
			"Spanish (Latin America)" => "es-419",
			"Swedish" => "sv",
			"Thai" => "th",
			"Turkish" => "tr",
			"Ukrainian" => "uk",
			"Vietnamese" => "vi",
		);
		$locale = get_locale();
		$locale_dash = str_replace('_', '-', $locale);
		$locale_first = substr($locale, 0, 2);
		$opt = $this->_get_option();
		echo "<select name='wdgpo[language]'>";
		foreach ($languages as $label => $lang) {
			if (@$opt['language']) $selected = ($lang == $opt['language']) ? 'selected="selected"' : '';
			else $selected = (!$opt && $lang == $locale || $lang == $locale_dash || $lang == $locale_first) ? 'selected="selected"' : '';
			echo "<option value='{$lang}' {$selected}>{$label}</option>";
		}
		echo "</select>";
	}

	function create_skip_post_types_box () {
		$post_types = get_post_types(array('public'=>true), 'objects');

		$opt = $this->_get_option();
		$skip_types = is_array(@$opt['skip_post_types']) ? @$opt['skip_post_types'] : array();

		foreach ($post_types as $tid=>$type) {
			$checked = in_array($tid, $skip_types) ? 'checked="checked"' : '';
			echo
				"<input type='hidden' name='wdgpo[skip_post_types][{$tid}]' value='0' />" . // Override for checkbox
				"<input {$checked} type='checkbox' name='wdgpo[skip_post_types][{$tid}]' id='skip_post_types-{$tid}' value='{$tid}' /> " .
				"<label for='skip_post_types-{$tid}'>{$type->label}</label>" .
			"<br />";
		}
		_e(
			'<p>Google +1 will <strong><em>not</em></strong> be shown for selected types.</p>',
			'wdgpo'
		);
	}

	function create_footer_render_box () {
		echo $this->_create_checkbox('footer_render');
		echo '<div><small>' . __('Using the WordPress defaults, the needed scripts will be added to your <code>head</code>. Use this option to load the scripts in your footer, after the bulk of your page has lareadt been loaded.', 'wdgpo') . '</small></div>';
		echo '<div><small>' . __('Note that this method is a bit less reliable then the default one, as it depends on your theme doing the right thing.', 'wdgpo') . '</small></div>';
	}

	function create_gplus_page_id_box () {
		$opt = $this->_get_option();
		$page_id = esc_attr(@$opt['gplus_page_id']);
		echo "<input type='text' name='wdgpo[gplus_page_id]' class='widefat' value='{$page_id}' />";
		echo '<div><small>' . __('Your Google+ page ID is the long number at the end of your page URL.', 'wdgpo') . '</small></div>';
	}

	function create_gplus_profile_id_box () {
		$opt = $this->_get_option();
		$gplus_profile_id = esc_attr(@$opt['gplus_profile_id']);
		echo "<input type='text' name='wdgpo[gplus_profile_id]' class='widefat' value='{$gplus_profile_id}' />";
		echo '<div><small>' . __('Your Google+ profile ID is the long number at the end of your page URL.', 'wdgpo') . '</small></div>';
	}

	function create_gplus_profile_fields_box () {
		echo '<label for="gplus_profile_fields-yes">' . __('Add Google+ profile field to user profile pages', 'wdgpo') . ':</label> ';
		echo $this->_create_checkbox('gplus_profile_fields');
		echo '<div><small>' . __('Enabling this option will add a new Google+ profile contact field for your users with posting privileges on their profile page.', 'wdgpo') . '</small></div>';
		echo '<label for="gplus_autorship_links-yes">' . __('Automatically add authorship links', 'wdgpo') . ':</label> ';
		echo $this->_create_checkbox('gplus_autorship_links');
		echo '<div><small>' . __('Enabling this option will automatically add Google+ authorship links to your posts.', 'wdgpo') . '</small></div>';
		echo '<div><small>' . __('Even if the option is turned off, you will be able to add the links yourself using a shortcode and/or template tag.', 'wdgpo') . '</small></div>';		
	}

	function create_import_check_box () {
		if (!function_exists('curl_init')) {
			echo '<div class="error below-h2"><p>' . __('You need CURL for import options to work', 'wdgpo') . '</p></div>';
		}
	}

	function create_gplus_app_settings_box () {
		if (!function_exists('curl_init')) return false;
		$opt = $this->_get_option();
		$page_id = esc_attr(@$opt['gplus_page_id']);
		$profile_id = esc_attr(@$opt['gplus_profile_id']);
		$client_id = esc_attr(@$opt['gplus_client_id']);
		$client_secret = esc_attr(@$opt['gplus_client_secret']);
		$auth = Wdgpo_GoogleAuth::get_instance();

		echo "<ol>";
		echo "<li><a href='https://code.google.com/apis/console/'>" . __('Create a project in Google API Console', 'wdgpo') . '</a></li>';
		echo '<li>' . __('Under &quot;Services&quot; tab, turn &quot;Google+ API&quot; to <b>ON</b>', 'wdgpo') . '</li>';
		echo '<li>' . sprintf(__("Under &quot;API Access&quot; click &quot;Create oAuth Client Access.&quot; Fill in your details and use this as your &quot;Authorized Redirect URIs&quot;: <code>%s</code>", 'wdgpo'), esc_url($auth->get_redirect_url())) . '</li>';
		echo '<li>' . __('Copy your <b>Client ID</b> and <b>Client secret</b> values and paste them in the fields below', 'wdgpo') . '</li>';
		echo '<li>' . __('Save your plugin settings and click the &quot;Authenticate&quot; button', 'wdgpo') . '</li>';
		echo "</ol>";

		echo
			'<label for="wdgpo-gplus_client_id">' . __('Client ID', 'wdgpo') . '</label>' .
			"<input type='text' id='wdgpo-gplus_client_id' name='wdgpo[gplus_client_id]' class='widefat' value='{$client_id}' />" .
		"</br>\n";
		echo
			'<label for="wdgpo-gplus_client_secret">' . __('Client secret', 'wdgpo') . '</label>' .
			"<input type='text' id='wdgpo-gplus_client_secret' name='wdgpo[gplus_client_secret]' class='widefat' value='{$client_secret}' />" .
		"</br>\n";

		$url = $auth->get_auth_url();
		if (!$page_id && !$profile_id) {
			echo '<p><b>' . __('You need to set up the page and/or profile ID fields to enable Google+ integration.', 'wdgpo') . '</b></p>';
		}
		if (!$client_id || !$client_secret) {
			echo '<p><b>' . __('You need to set up your app info to enable Google+ imports.', 'wdgpo') . '</b></p>';
		}
		if ($client_id && $client_secret && $url && ($page_id || $profile_id)) {
			echo "<input type='hidden' id='wdgpo-gplus_auth_url' value='" . esc_url($url) . "' />";
			echo "<p><input type='button' id='wdgpo-gplus_authenticate' value='" . esc_attr(__('Authenticate', 'wdgpo')) . "' /></p>\n";
		} else if ($client_id && $client_secret && ($page_id || $profile_id)) {
			echo "<p><input type='button' id='wdgpo-gplus_test_import' value='" . esc_attr(__('Test', 'wdgpo')) . "' /></p>\n";
			echo "<p><small><a href='#' id='wdgpo-gplus_deauthenticate'>" . esc_attr(__('Deauthenticate', 'wdgpo')) . "</a></small></p>\n";
		}
	}

	function create_gplus_import_settings_box () {
		if (!function_exists('curl_init')) return false;
		global $current_user;
		$opt = $this->_get_option();

		$post_category = @$opt['gplus_post_category'];

		$post_tags = @$opt['gplus_post_tags'];
		$post_tags = $post_tags ? $post_tags : array();

		$post_format = @$opt['gplus_post_format'];

		$import_ids = @$opt['gplus_import_ids'];
		$import_ids = $import_ids ? array_filter($import_ids) : array();

		$post_author = @$opt['gplus_post_author'];
		$post_author = $post_author ? $post_author : $current_user->user_login;

		$import_hashtags = @$opt['gplus_import_hashtags'];

		$import_limit = (int)@$opt['gplus_import_limit'];
		$import_limit = $import_limit ? $import_limit : Wdgpo_GoogleAuth::GPLUS_MAX_RESULTS;

		$formats_to_hashtags = @$opt['gplus_formats_to_hashtags'];
		$formats_to_hashtags = $formats_to_hashtags ? $formats_to_hashtags : array();

		// Multiple import
		if (!$import_ids) {
			echo '<p><a href="#multiple_import" id="wdgpo-multiple_import-action">' . __('Multiple import', 'wdgpo') . '</a></p>';
		}
		$style = $import_ids ? 'block' : 'none';
		echo '<div id="wdgpo-multiple_import" style="display:' . $style . '">';
		echo '<ul>';
		$import_ids[] = ''; // One for the empty item
		foreach ($import_ids as $idx=>$id) {
			$id = esc_attr($id);
			echo '<li>';
			echo "<label>" . __('Google+ ID:', 'wdgpo') . "</label> ";
			echo "<input type='text' size='64' name='wdgpo[gplus_import_ids][]' value='{$id}' />";
			echo ' <a href="#wdgpo-remove_item" class="wdgpo-multiple_import-remove_item">' . __('Remove', 'wdgpo') . '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '<input type="button" id="wdgpo-multi_import-add_one" value="' . esc_attr(__('Add Google+ ID to import', 'wdgpo')) . '" />';
		echo '</div>';
		echo '<div><small>' . __('In addition to your page, you can import posts from a number of other Google+ profiles/pages.', 'wdgpo')  . '</small></div>';
		echo '<div><small>' . __('Keep in mind that every additional import adds more to total processing time.', 'wdgpo')  . '</small></div>';

		// Publish posts
		echo "<br />";
		if (defined('BP_VERSION')) {
			// BuddyPress activities
			$bp_checked = @$opt['gplus_post_bp_activities'] ? 'checked="checked"' : '';
			$wp_checked = @$opt['gplus_post_no_publish'] ? '': 'checked="checked"';
			echo '<label for="">' . __('Import posts into BuddyPress as:', 'wdgpo') . '</label><br />' .
				'<input type="hidden" value="0" name="wdgpo[gplus_post_bp_activities]">' .
				'<input id="gplus_post_bp_activities" type="checkbox" value="1" name="wdgpo[gplus_post_bp_activities]" ' . $bp_checked . '>' .
				' <label for="gplus_post_bp_activities">' . __('Activities', 'wdgpo') . '</label>' .
				'<div><small>' . __('Select this option if you want import your Google+ activities as BuddyPress activities.', 'wdgpo')  . '</small></div>' .

				'<input type="hidden" value="1" name="wdgpo[gplus_post_no_publish]">' .
				'<input id="gplus_post_no_publish" type="checkbox" value="0" name="wdgpo[gplus_post_no_publish]" ' . $wp_checked . '>' .
				' <label for="gplus_post_no_publish">' . __('Posts', 'wdgpo') . '</label>' .
				'<div><small>' . __('Select this option if you want import your Google+ activities as posts on your blog.', 'wdgpo')  . '</small></div>' .

			'<div><small>' . __('Changing these settings will only affect newly imported posts.', 'wdgpo')  . '</small></div>' .
			"";
		} else {
			// Regular WordPress
			echo '<label for="">' . __('I want to display my imported posts in widgets and shortcode only:', 'wdgpo') . '</label><br />' .
				$this->_create_checkbox('gplus_post_no_publish') .
				'<div><small>' . __('Select this option if you want to show your imported posts in your widgets, but not actually post them on your blog.', 'wdgpo')  . '</small></div>' .
				'<div><small>' . __('Changing this setting will only affect newly imported posts.', 'wdgpo')  . '</small></div>' .
			"";
		}

		// Import limit
		echo "<br />";
		echo
			'<label for="wdgpo-gplus_import_limit">' . __('Limit import to this many posts:', 'wdgpo') . '</label> ' .
			"<input type='text' size='3' name='wdgpo[gplus_import_limit]' id='wdgpo-gplus_import_limit' value='{$import_limit}' />" .
			'<div><small>' . __('At most this many posts will be imported', 'wdgpo') . '</small></div>' .
			'<div><small>' . __("To prevent timeout issues and incomplete imports, you'll want to keep this number fairly low.", 'wdgpo') . '</small></div>' .
		"";

		// Import now
		if (@Wdgpo_Options::get_token()) {
			echo '<p><input type="button" id="wdgpo-gplus_import_now" value="' . esc_attr(__('Import now', 'wdgpo')) . '" /></p>';
		}

		// Author assignment
		echo "<h4>" . __('Author assignment', 'wgdpo') . "</h4>";
		echo
			'<label for="wdgpo-gplus_post_author">' . __('Assign imported posts to this user:', 'wdgpo') . '</label> ' .
			"<input type='text' size='32' name='wdgpo[gplus_post_author]' id='wdgpo-gplus_post_author' value='{$post_author}' />" .
		"<br />";
		$user = get_userdatabylogin($post_author);
		if (!$user->ID) {
			echo '<div class="error below-h2"><p>' . __('Invalid username!', 'wdgpo') . '</p></div>';
		}
		echo '<div><small>' . __('All your imported posts will be assigned to this user.', 'wdgpo')  . '</small></div>';


		// Categories
		echo "<h4>" . __('Category assignment', 'wgdpo') . "</h4>";
		$categories = get_categories(array('type'=>'post', 'taxonomy'=>'category', 'hierarchical'=>1, 'hide_empty'=>0));
		echo '<label for="wdgpo-gplus_post_category">' . __('Apply this category:', 'wdqs') . '</label> ';
		echo '<select name="wdgpo[gplus_post_category]" id="wdgpo-gplus_post_category">';
		echo "<option value=''>" . __('No category', 'wdgpo') . "</option>";
		foreach ($categories as $category) {
			$selected = ($category->term_id == $post_category) ? "selected='selected'" : '';
			$name = esc_html($category->name);
			if ($category->parent) $name = "&#8212;&nbsp;{$name}";
			echo "<option value='{$category->term_id}' {$selected}>{$name}</option>";
		}
		echo '</select>';
		echo '<div><small>' . __('This category will be automatically added to all your imported posts.', 'wdgpo')  . '</small></div>';

		// Tags
		echo "<h4>" . __('Tags assignment', 'wgdpo') . "</h4>";
		$tags = get_tags(array('hide_empty'=>0));
		echo '<label for="wdgpo-gplus_post_tags">' . __('Apply these tags:', 'wdqs') . '</label><br /> ';
		echo '<select style="height:6em" name="wdgpo[gplus_post_tags][]" multiple="multiple" size="3" id="wdgpo-gplus_post_tags">';
		foreach ($tags as $tag) {
			$selected = in_array($tag->slug, $post_tags) ? "selected='selected'" : '';
			$name = esc_html($tag->name);
			echo "<option value='{$tag->slug}' {$selected}>{$name}</option>";
		}
		echo '</select>';
		echo '<div><small>' . __('These tags will be automatically added to all your imported posts.', 'wdgpo')  . '</small></div>';

		echo '<label for="gplus_auto_tag-yes">' . __('Auto-create and apply tags from activity author name:', 'wdgpo') . '</label> ' .
			$this->_create_checkbox('gplus_auto_tag') .
		"<br />";
		echo '<div><small>' . __('All your imported posts will be auto-tagged according to activity author display name.', 'wdgpo')  . '</small></div>';

		echo '<label for="gplus_auto_hashtag-yes">' . __('Auto-create and apply tags from detected hashtags:', 'wdgpo') . '</label> ' .
			$this->_create_checkbox('gplus_auto_hashtag') .
		"<br />";
		echo '<div><small>' . __('All your imported posts will be auto-tagged according to detected hashtags.', 'wdgpo')  . '</small></div>';

		// Post formats
		echo "<h4>" . __('Post formats assignment', 'wgdpo') . "</h4>";
		$theme_formats = get_theme_support('post-formats');
		$theme_formats = is_array($theme_formats) ? $theme_formats[0] : array();
		array_unshift($theme_formats, '');
		echo '<label for="wdgpo-gplus_post_format">' . __('Apply this post format:', 'wdgpo') . '</label> ';
		if (!current_theme_supports('post-formats') || !$theme_formats) {
			_e('<p>Your theme does not support post formats</p>', 'wdgpo');
		} else {
			echo '<select name="wdgpo[gplus_post_format]" id="wdgpo-gplus_post_format">';
			foreach ($theme_formats as $format) {
				$selected = ($format == $post_format) ? "selected='selected'" : '';
				$name = esc_html(get_post_format_string($format));
				echo "<option value='{$format}' {$selected}>{$name}</option>";
			}
			echo '</select>';
			echo '<div><small>' . __('This format will be applied to all your imported posts.', 'wdgpo')  . '</small></div>';

			// Post Formats to Hashtags
			echo "<br />";
			echo "<ul>";
			foreach ($theme_formats as $format) {
				if (!$format) continue;
				$hashtags = esc_attr(@$formats_to_hashtags[$format]);
				$name = esc_html(get_post_format_string($format));
				echo '<li>';
				echo "<label for='wdgpo-map-{$format}-to-hashtags'>" . sprintf(__('Map <b>%s</b> post format to imported posts with these hashtags:', 'wdgpo'), $format) . '</label> ';
				echo "<input type='text' size='32' name='wdgpo[gplus_formats_to_hashtags][{$format}]' id='wdgpo-map-{$format}-to-hashtags' value='{$hashtags}' />";
				echo '</li>';
			}
			echo "</ul>";
			echo '<div><small>' . __('Separate multiple hashtags with commas, i.e. <code>#hashtag1,#hashtag2</code>', 'wdgpo') . '</small></div>';
			echo '<div><small>' . __('Any hashtag to post format mapping you set up here will take precedence over your assigned default post format.', 'wdgpo') . '</small></div>';
		}

		// Hashtags
		echo "<h4>" . __('Hashtags filter', 'wgdpo') . "</h4>";
		echo
			'<label for="wdgpo-gplus_import_hashtags">' . __('Import only posts with these hashtags:', 'wdgpo') . '</label><br />' .
			"<input type='text' class='widefat' name='wdgpo[gplus_import_hashtags]' id='wdgpo-gplus_import_hashtags' value='{$import_hashtags}' />" .
			'<div><small>' . __('Separate multiple hashtags with commas, i.e. <code>#hashtag1,#hashtag2</code>', 'wdgpo') . '</small></div>' .
			'<div><small>' . __('Leave this field empty to import all posts.', 'wdgpo') . '</small></div>' .
		"";

	}

	function create_enable_analytics_box () {
		echo $this->_create_checkbox('analytics_integration');
		echo '<div><small>' . __('Enabling this option will allow you to track your +1s in Google Analytics.', 'wdgpo') . '</small></div>';
		echo '<div><small>' . __('<b>Note:</b> your site will need to have Google Analytics already set up for this to work.', 'wdgpo') . '</small></div>';
	}

	function create_analytics_category_box () {
		$opt = $this->_get_option();
		$category = @$opt['analytics_category'] ? $opt['analytics_category'] : 'Google +1';
		$category = esc_attr($category);
		echo "<input type='text' name='wdgpo[analytics_category]' class='widefat' value='{$category}' />";
		echo '<div><small>' . __('Your +1 clicks will be added to this category.', 'wdgpo') . '</small></div>';
	}

	function create_log_level_box () {
		echo '' .
			$this->_create_radiobox('log', 0) .
			'<label for="log-0">' . __('Do not do any logging', 'wdgpo') . '</label>' .
		'<br />';
		echo '' .
			$this->_create_radiobox('log', Wdgpo_Logger::LEVEL_ERROR) .
			'<label for="log-' . Wdgpo_Logger::LEVEL_ERROR . '">' . __('Log only errors', 'wdgpo') . '</label>' .
		'<br />';
		echo '' .
			$this->_create_radiobox('log', Wdgpo_Logger::LEVEL_INFO) .
			'<label for="log-' . Wdgpo_Logger::LEVEL_INFO . '">' . __('Log info messages too', 'wdgpo') . '</label>' .
		'<br />';
		echo '' .
			$this->_create_radiobox('log', Wdgpo_Logger::LEVEL_DEBUG) .
			'<label for="log-' . Wdgpo_Logger::LEVEL_DEBUG . '">' . __('Debugging level', 'wdgpo') . '</label>' .
		'<br />';
	}

	function create_log_output_box () {
		$log = new Wdgpo_Logger;
		echo '<a href="#log" id="wdgpo-toggle_log" data-off_label="' . esc_attr('Hide log', 'wdgpo') . '">' . __('Show log', 'wdgpo') . '</a>';
		echo '&nbsp;|&nbsp;';
		echo '<a href="#clear-log" id="wdgpo-clear_log">' . __('Clear log', 'wdgpo') . '</a>';
		echo '<div id="wdgpo-log_container" style="display:none">' . $log->get_log_string() . '</div>';
	}

}
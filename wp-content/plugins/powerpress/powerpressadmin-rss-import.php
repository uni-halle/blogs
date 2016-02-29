<?php

// Load Importer API
require_once( ABSPATH . 'wp-admin/includes/import.php');

if ( !class_exists( 'WP_Importer' ) ) {
	if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-importer.php' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-importer.php' );
}

/**
 * PowerPress RSS Podcast Importer
 *
 * originally based on the rss importer, significantly modified specifically for podcasting
 */

/**
 * PowerPress RSS Podcast Importer
 *
 * Will process a Podcast RSS feed for importing posts into WordPress. 
 *
 */
if ( class_exists( 'WP_Importer' ) ) {
class PowerPress_RSS_Podcast_Import extends WP_Importer {

	var $m_content = '';
	var $m_item_pos = 0;
	var $m_item_inserted_count = 0;
	var $m_item_skipped_count = 0;

	function header() {
		echo '<div class="wrap">';
		screen_icon();
		
		if( !empty($_GET['import']) )
		{
			switch($_GET['import'] )
			{
				case 'powerpress-soundcloud-rss-podcast': echo '<h2>'.__('Import Podcast from SoundCloud', 'powerpress').'</h2>'; break;
				case 'powerpress-libsyn-rss-podcast': echo '<h2>'.__('Import Podcast from LibSyn', 'powerpress').'</h2>'; break;
				case 'powerpress-podbean-rss-podcast': echo '<h2>'.__('Import Podcast from PodBean', 'powerpress').'</h2>'; break;
				case 'powerpress-squarespace-rss-podcast': echo '<h2>'.__('Import Podcast from Squarespace', 'powerpress').'</h2>'; break;
				case 'powerpress-rss-podcast': 
				default: echo '<h2>'.__('Import Podcast RSS Feed', 'powerpress').'</h2>'; break;
			}
		}
		else
		{
			echo '<h2>'.__('Podcast RSS Import', 'powerpress').'</h2>';
		}
	}

	function footer() {
		echo '</div>';
	}

	function greet() {
?>
<div class="wrap">

<p><?php echo __('The following tool will import your podcast episodes to this website.', 'powerpress'); ?></p>

<form enctype="multipart/form-data" action="admin.php?import=<?php echo esc_attr($_GET['import']); ?>" method="post" name="blogroll">
<?php wp_nonce_field('import-powerpress-rss') ?>

<div style="width: 70%; margin: auto; height: 8em;">
<input type="hidden" name="step" value="1" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo wp_max_upload_size(); ?>" />
<div style="width: 48%;" class="alignleft">
<h3><label for="podcast_feed_url"><?php _e('Podcast Feed URL:', 'powerpress'); ?></label></h3>
<?php
	$placeholder = 'https://example.com/feed.xml';
	switch($_GET['import']) {
		case 'powerpress-soundcloud-rss-podcast': $placeholder = 'http://feeds.soundcloud.com/users/soundcloud:users:00000000/sounds.rss'; break;
		case 'powerpress-libsyn-rss-podcast': $placeholder = 'http://yourshow.libsyn.com/rss'; break;
		case 'powerpress-podbean-rss-podcast': $placeholder = 'http://yourshow.podbean.com/feed/'; break;
		case 'powerpress-squarespace-rss-podcast': $placeholder = 'http://example.com/podcast/?format=rss'; break;
	}
?>
<input type="text" name="podcast_feed_url" id="podcast_feed_url" size="50" class="code" style="width: 90%;" placeholder="<?php echo esc_attr($placeholder); ?>" />
</div>

<div style="width: 48%;" class="alignleft">
<h3><label for="podcast_feed_file"><?php _e('Or choose from your local disk:', 'powerpress'); ?></label></h3>
<input id="podcast_feed_file" name="podcast_feed_file" type="file" />
</div>

</div>

<p><?php echo sprintf(__('Importing your feed does not migrate your media files. Please use the %s tool to migrate your media once your feed is imported.', 'powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>'); ?></p>


<div class="submit">
<div>
	<label><input type="checkbox" name="import_itunes_image" value="1" checked> <?php echo __('Download and save iTunes image', 'powerpress'); ?></label>
</div>
<div>
<?php 
	//$Settings = get_option('powerpress_general');
	/*
	if( !empty($Settings['blubrry_auth']) ) { ?>
	<label><input type="checkbox" name="migrate_to_blubrry" value="1" checked /> <?php echo __('Migrate Media files to Blubrry Podcast Hosting account', 'powerpress'); ?></label>
<?php } else { ?>
	<label><input type="checkbox" name="migrate_to_blubrry" value="1" /> <?php echo __('Migrate Media files to Blubrry Podcast Hosting account', 'powerpress'); ?></label> <a title="<?php echo esc_attr(__('Blubrry Podcast Hosting', 'powerpress')); ?>" href="<?php echo admin_url('admin.php'); ?>?action=powerpress-jquery-hosting&amp;KeepThis=true&amp;TB_iframe=true&amp;modal=false&amp;width=800&amp;height=400" target="_blank" class="thickbox"><?php echo __('Learn More', 'powerpress'); ?></a>
<?php }
	*/
?>
</div>
<div id="powerpress-import-advanced-options-off">
	<p><a href="#" onclick="document.getElementById('powerpress-import-advanced-options-off').style.display='none'; document.getElementById('powerpress-import-advanced-options-on').style.display='block';  return false;"><?php echo __('Advanced options', 'powerprses'); ?></a>
	</p>
</div>
<div id="powerpress-import-advanced-options-on" style="display: none;">
	<div>
		<label><input type="checkbox" name="import_overwrite_program_info" value="1"> <?php echo __('Overwrite podcast program information', 'powerpress'); ?></label>
	</div>
	<div>
		<label><input type="checkbox" name="import_strict" value="1" > <?php echo __('Allow duplicate episode titles and dates', 'powerpress'); ?></label>
	</div>
	<div>
		<label><input type="checkbox" name="import_blog_posts" value="1" > <?php echo __('Include blog posts', 'powerpress'); ?></label>
	</div>
	<div>
		<label for="import_item_limit"><?php echo __('Item limit', 'powerpress'); ?></label> <input type="text" name="import_item_limit" id="import_item_limit" class="small-text" value="" /> (<?php echo __('leave blank for no limit', 'powerpress'); ?>)
	</div>
</div>
<?php submit_button( __('Import Podcast', 'powerpress' ) ); ?>
</div>
</form>
</div>
<?php
	return;
		echo '<div class="narrow">';
		
		echo '<h2>'.__('Import saved Feed', 'powerpress') .'</h2>';
		wp_import_upload_form("admin.php?import=rss-podcast&amp;step=1");
		echo '</div>';
	}

	function _normalize_tag( $matches ) {
		return '<' . strtolower( $matches[1] );
	}
	
	function import_program_info($channel, $overwrite=false, $download_itunes_image=false) {
		$Feed = get_option('powerpress_feed_podcast' );
		if( empty($Feed) )
			$Feed = get_option('powerpress_feed');
		
		$NewSettings = array();
		
		$matches = array();
		$program_title = false;
		if( preg_match('|<title>(.*?)</title>|is', $channel, $matches) ) {
			$program_title = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['title']) )
				$NewSettings['title'] = $program_title;
		}
		
		// language
		$language = false;
		if( preg_match('|<language>(.*?)</language>|is', $channel, $matches) ) {
			$language = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['rss_language']) )
				$NewSettings['rss_language'] = $language;
		}
		
		// copyright
		$copyright = false;
		if( preg_match('|<copyright>(.*?)</copyright>|is', $channel, $matches) ) {
			$copyright = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['copyright']) )
				$NewSettings['copyright'] = $copyright;
		}
		
		// description
		$description = false;
		if( preg_match('|<description>(.*?)</description>|is', $channel, $matches) ) {
			$description = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['description']) )
				$NewSettings['description'] = $description;
		}
		
		// itunes:subtitle
		$itunes_subtitle = false;
		if( preg_match('|<itunes:subtitle>(.*?)</itunes:subtitle>|is', $channel, $matches) ) {
			$itunes_subtitle = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_subtitle']) )
				$NewSettings['itunes_subtitle'] = $itunes_subtitle;
		}
		
		// itunes:summary
		$itunes_summary = false;
		if( preg_match('|<itunes:summary>(.*?)</itunes:summary>|is', $channel, $matches) ) {
			$itunes_summary = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_summary']) )
				$NewSettings['itunes_summary'] = $itunes_summary;
		}
		
		// itunes:email
		$itunes_email = false;
		if( preg_match('|<itunes:email>(.*?)</itunes:email>|is', $channel, $matches) ) {
			$itunes_email = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['email']) )
				$NewSettings['email'] = $itunes_email;
		}
		
		// itunes:author
		$itunes_talent_name = false;
		if( preg_match('|<itunes:author>(.*?)</itunes:author>|is', $channel, $matches) ) {
			$itunes_talent_name = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_talent_name']) )
				$NewSettings['itunes_talent_name'] = $itunes_talent_name;
		}
		
		// itunes:explicit
		if( preg_match('|<itunes:explicit>(.*?)</itunes:explicit>|is', $channel, $explicit) )
		{
			$explicit_array = array('yes'=>1, 'clean'=>2); // No need to save 'no'
			$value = strtolower( trim( $explicit[1] ) );
			if( !empty($explicit_array[ $value ]) )
			{
				if( $overwrite || empty($Feed['itunes_explicit']) ) {
					$NewSettings['itunes_explicit'] = $explicit_array[ $value ];
				}
			}
		}

		// itunes:image
		$itunes_image = '';
		if( preg_match('/<itunes:image.*href="(.*?)".*(\/>|>.*<\/itunes:image>)/is', $channel, $image) )
		{
			$itunes_image = html_entity_decode( trim( $image[1] ) ); // Now we need to download and save the image locally...
			
			// download the image then save it locally...
			if( $download_itunes_image ) {
				
				$upload_path = false;
				$upload_url = false;
				$UploadArray = wp_upload_dir();
				if( false === $UploadArray['error'] )
				{
					$upload_path =  $UploadArray['basedir'].'/powerpress/';
					$upload_url =  $UploadArray['baseurl'].'/powerpress/';
					$filename = str_replace(" ", "_", basename($itunes_image) );
					
					if( file_exists($upload_path . $filename ) )
					{
						$filenameParts = pathinfo($filename);
						if( !empty($filenameParts['extension']) ) {
							do {
								$filename_no_ext = substr($filenameParts['basename'], 0, (strlen($filenameParts['extension'])+1) * -1 );
								$filename = sprintf('%s-%03d.%s', $filename_no_ext, rand(0, 999), $filenameParts['extension'] );
							} while( file_exists($upload_path . $filename ) );
						}
					}
					
					$options = array();
					$options['user-agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
					if( !empty($_GET['import']) && $_GET['import'] == 'powerpress-squarespace-rss-podcast' )
						$options['user-agent'] = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
					$options['timeout'] = 10;
					
					$image_data = '';
					$response = wp_safe_remote_get($itunes_image, $options);
					if ( !is_wp_error( $response ) ) {
						$image_data = wp_remote_retrieve_body( $response );
					}
		
					if( !empty($image_data) ) {
						file_put_contents($upload_path.$filename, $image_data);
						$NewSettings['itunes_image'] = $upload_url . $filename;
						$NewSettings['rss2_image'] = $itunes_image;
					}
				}
			} else if( $overwrite || empty($Feed['itunes_image']) ) {
				$NewSettings['itunes_image'] = $itunes_image;
				$NewSettings['rss2_image'] = $itunes_image;
			}
		}
		
			
		if( preg_match('|<itunes:author>(.*?)</itunes:author>|is', $channel, $matches) ) {
			$itunes_talent_name = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_talent_name']) )
				$NewSettings['itunes_talent_name'] = $itunes_talent_name;
		}
		
		// itunes:category (up to 3)
		$itunes_categories  = false;
		if( preg_match_all('|<itunes:category.*text="(.*?)"|is', $channel, $matches) ) {
			$pos = 1;
			$itunes_categories = $matches[1];
			$Categories = powerpress_itunes_categories();
			$Categories = array_map('strtolower', $Categories);
			$cats_by_title = array_flip( $Categories );
			
			$FoundCategories = array();
			while( list($index,$category) = each($itunes_categories) )
			{
				$category = str_replace('&amp;', '&', $category);
				$category = strtolower($category);
				if( !empty($cats_by_title[ $category ] ) )
					$FoundCategories[] = $cats_by_title[ $category ];
			}
			
			// Now walk trouigh found categories and stack them correctly...
			// this logic rebuilds the categorires in the correct order no matter what method the service stacked them
			$FinalCats = array(1=>'', 2=>'', 3=>'');
			$last_category_index = 1;
			while( list($index,$cat_id) = each($FoundCategories) )
			{
				if( !empty($FinalCats[$last_category_index]) ) // Do we need to increment to the next category position
				{
					if( intval(substr($FinalCats[$last_category_index], 3)) > 0 )
					{
						$last_category_index++;
					}
					else if( intval(substr($FinalCats[$last_category_index],0, 2)) != intval(substr($cat_id,0, 2)) )
					{
						$last_category_index++;
					}
					// else we can overwrite this category with subcategory
				}
				
				if( $last_category_index > 3 )
					break; // We are at the max cats available...
				
				$FinalCats[ $last_category_index ] = $cat_id;
			}
			
			while( list($field_no, $cat_id) = each($FinalCats) ) {
				if( empty( $cat_id) )
					continue;
				$field = sprintf('itunes_cat_%d', $field_no);
				
				if( $overwrite || empty($Feed[ $field  ]) ) {
					$NewSettings[ $field ] = $cat_id;
				}
			}
		}
		
		if( !empty($NewSettings) )
		{
			// Save here..
			if( get_option('powerpress_feed_podcast') ) { // If the settings were moved to the podcast channels feature...
				powerpress_save_settings($NewSettings, 'powerpress_feed_podcast' ); // save a copy here if that is the case.
			} else {
				powerpress_save_settings($NewSettings, 'powerpress_feed' );
			}
			
			echo '<hr />';
			echo '<p><strong>'. __('Program information imported', 'powerpress') .'</strong></p>';
			echo '<ul class="ul-disc">';
			while( list($field,$value) = each($NewSettings) )
			{
				if( $field == 'rss2_image' )
					continue;
				
				echo '<li>';
				switch( $field )
				{
					case 'title': echo __('Feed Title (Show Title)', 'powerpress'); break;
					case 'rss_language': echo __(' Feed Language', 'powerpress'); break;
					case 'description': echo __('Feed Description', 'powerpress'); break;
					case 'copyright': echo __('Copyright', 'powerpress'); break;
					case 'itunes_talent_name': echo __('iTunes Author Name', 'powerpress'); break;
					case 'itunes_summary': echo __('iTunes Program Summary', 'powerpress'); break;
					case 'itunes_subtitle': echo __('iTunes Program Subtitle', 'powerpress'); break;
					case 'itunes_image': echo __('iTunes Image', 'powerpress'); break;
					case 'itunes_explicit': echo __('iTunes Explicit', 'powerpress'); break;
					case 'email': echo __('iTunes Email', 'powerpress'); break;
					case 'itunes_cat_1': echo __('iTunes Category', 'powerpress'); break;
					case 'itunes_cat_2': echo __('iTunes Category 2', 'powerpress'); break;
					case 'itunes_cat_3': echo __('iTunes Category 3', 'powerpress'); break;
					default: echo $field  . ': '.htmlspecialchars($value); break;
				}
				echo '</li>';
			}
			echo '</ul>';
		}
	}
	
	function import_item($post, $import_strict=false, $import_blog_posts=false) {	
		global $wpdb;
		$this->m_item_pos++;
		
		$matches = array();
		$post_title = false;
		if( !preg_match('|<title>(.*?)</title>|is', $post, $matches) ) {
			echo  sprintf(__('Empty episode title for item %d', 'powerpress'), $this->m_item_pos);
			$this->m_item_skipped_count++;
			return false;
		}
		$post_title = $this->_sanatize_tag_value($matches[1]);
			
		// Look for an enclosure, if not found skip it...
		$enclosure_data = false;
		if( !preg_match('|<enclosure(.*?)/>|is', $post, $enclosure_data) ) {
			echo sprintf(__('No Media found for item %d', 'powerpress'), $this->m_item_pos);
			if( empty($import_blog_posts) ) {
				$this->m_item_skipped_count++;
				return false;
			}
			
			echo ' - ';
		}
		if( !empty($enclosure_data[1]) ) {
			$enclosure = $this->_parse_enclosure( '<enclosure '.$enclosure_data[1].' />', $post );
			if( empty($enclosure) ) {
				if( empty($import_blog_posts) ) {
					echo sprintf(__('No Media found for item %d', 'powerpress'), $this->m_item_pos);
					$this->m_item_skipped_count++;
					return false;
				}
			}
		}
		
		// GUID has to be last, as we will use the media URL as the guid as a last resort
		$guid = false;
		if( preg_match('|<guid.*?>(.*?)</guid>|is', $post, $matches) )
			$guid = $this->_sanatize_tag_value( $matches[1] );
		else if( !empty($enclosure['url']) )
			$guid = $enclosure['url'];
			
		$post_date_gmt = false;
		if( preg_match('|<pubdate>(.*?)</pubdate>|is', $post, $matches) ) {
			$post_date_gmt = strtotime($matches[1]);
		} else {
			// if we don't already have something from pubDate
			if( preg_match('|<dc:date>(.*?)</dc:date>|is', $post, $matches) )
			{
				$post_date_gmt = preg_replace('|([-+])([0-9]+):([0-9]+)$|', '\1\2\3', $matches[1]);
				$post_date_gmt = str_replace('T', ' ', $post_date_gmt);
				$post_date_gmt = strtotime($post_date_gmt);
			}
		}

		$post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
		$post_date = get_date_from_gmt( $post_date_gmt );
		
		// Before we go any further, lets see if we have imported this one already...
		if( $import_strict && !empty($guid) )
			$exists = $this->_post_exists_strict($guid);
		else
			$exists = $this->_post_exists($post_title, $guid, $post_date);
		
		if( !empty($exists) )
		{
			echo sprintf(__('<i>%s</i> already imported.', 'powerpress'), htmlspecialchars($post_title) );
			$this->m_item_skipped_count++;
			return false;
		}
		
		// Okay awesome, lets dig through the rest...
		$categories = array();
		if( preg_match_all('|<category>(.*?)</category>|is', $post, $matches) )
			$categories = $matches[1];

		if ( empty($categories) ) {
			if( preg_match_all('|<dc:subject>(.*?)</dc:subject>|is', $post, $matches) )
				$categories = $matches[1];
		}
		
		$cat_index = 0;
		foreach ($categories as $category) {
			$categories[$cat_index] = $this->_sanatize_tag_value( $category );
			$cat_index++;
		}
		
		$post_content = '';
		if( preg_match('|<content:encoded>(.*?)</content:encoded>|is', $post, $matches) )
			$post_content = $this->_sanatize_tag_value( $matches[1] );

		if ( empty($post_content) ) {
			// This is for feeds that put content in description
			if( preg_match('|<description>(.*?)</description>|is', $post, $matches) )
				$post_content = $this->_sanatize_tag_value( $matches[1] );
		}

		// Clean up content
		$post_content = preg_replace_callback('|<(/?[A-Z]+)|', array( &$this, '_normalize_tag' ), $post_content);
		$post_content = str_replace('<br>', '<br />', $post_content);
		$post_content = str_replace('<hr>', '<hr />', $post_content);

		$post_author = 1;
		$post_status = 'publish';
		
		// Save this episode to the database...
		$post_to_save = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'guid', 'categories', 'enclosure');
		$this->m_item_inserted_count++;
		echo '<span style="color: green; font-weight: bold;">';
		echo sprintf(__('<i>%s</i> imported.', 'powerpress'), htmlspecialchars($post_title) );
		echo '</span>';
		return $this->_import_post_to_db($post_to_save);
	}
	
	function _sanatize_tag_value($value)
	{
		if( !is_string($value) )
			return '';
		
		$value = trim($value);
		if( preg_match('/^<!\[CDATA\[(.*)\]\]>$/is', $value, $matches) ) {
			$value = $matches[1];
		} else {
			$value = html_entity_decode($value);
		}
		
		return $value;
	}

	function import_episodes($import_strict=false, $import_blog_posts=false, $import_item_limit=0) {
		global $wpdb;
		@set_time_limit(60*15); // Give it 15 minutes
		$this->m_item_pos = 0;
		
		$item_count = substr_count( $this->m_content, '<item>');
		$item_count += substr_count( $this->m_content, '<ITEM>');
		
		echo '<hr />';
		echo '<p><strong>';
		echo __('Importing Episodes...', 'powerpress');
		echo '</strong></p>';
		
		echo '<p>';
		echo sprintf( __('Items Found: %d', 'powerpress'), $item_count);
		echo '</p>';
		@flush();

		echo '<ol>';
		
		$item_start_pos = 0;
		$item_start_pos = mb_stripos($this->m_content, '<item>', $item_start_pos);
		$item_end_pos = mb_stripos($this->m_content, '</item>', $item_start_pos);
		
		$count = 0;
		while( $item_start_pos !== false && $item_end_pos !== false ) // If one were to return false, we stap!
		{
			// check item limit at the beginning of each iteration
			if( $import_item_limit > 0 && $this->m_item_pos >= $import_item_limit ) {
				break; // Item limit reached, stop!
			}
			
			echo '<li>';
			$new_start = $item_start_pos + mb_strlen('<item>');
			$item_content = mb_substr($this->m_content, $new_start, $item_end_pos - $new_start);
			$this->import_item($item_content, $import_strict, $import_blog_posts);
			echo '</li>';
			
			// Extra stop just in case...
			if( $count > 3000 )
				break;
				
			if( $count % 50 == 0 )
				@flush();
			
			$item_start_pos = mb_stripos($this->m_content, '<item>', $item_end_pos); // Find the next item
			$item_end_pos = mb_stripos($this->m_content, '</item>', $item_start_pos);
		}
		echo '</ol>';
	}

	function import() {
?>	
<div class="wrap">
<h3><?php _e('Importing Podcast', 'powerpress') ?></h3>
<?php
		
		$result = false;
		if ( empty($_POST['podcast_feed_url']) ) {
			?><p><?php _e('From Uploaded file...', 'powerpress'); ?></p><?php
			$result = $this->_import_handle_upload();
		}
		else
		{
			?><p><?php _e('From URL', 'powerpress'); ?> <?php echo esc_html($_POST['podcast_feed_url']); ?></p><?php
			$result = $this->_import_handle_url();
		}
		
		if( $result == false )
			return;
		$import_strict = (!empty($_POST['import_strict'])?true:false);
		$import_blog_posts = (!empty($_POST['import_blog_posts'])?true:false);
		$import_item_limit  = (!empty($_POST['import_item_limit'])?intval($_POST['import_item_limit']):0);
		
		if( preg_match('/^(.*)<item>/is', $this->m_content, $matches) )
		{
			$overwrite_program_info = (!empty($_POST['import_overwrite_program_info'])?true:false);
			$import_itunes_image = (!empty($_POST['import_itunes_image'])?true:false);
			$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image);
		}
		
		$this->import_episodes($import_strict, $import_blog_posts, $import_item_limit);
		
		$migrated_to_blubrry = false;
		if( !empty($_POST['migrate_to_blubrry'])  && !empty($GLOBALS['pp_migrate_media_urls']) ) {
			require_once( POWERPRESS_ABSPATH .'/powerpressadmin-migrate.php');
			$migrated_to_blubrry = true;
			
			$update_option = true;
			$QueuedFiles = get_option('powerpress_migrate_queued');
			if( !is_array($QueuedFiles) ) {
				$QueuedFiles = array();
				$update_option = false;
			}
			
			$add_urls = '';
			while( list($meta_id, $url) = each($GLOBALS['pp_migrate_media_urls']) )
			{
				if( !empty($add_urls) )
					$add_urls .= "\n";
				$add_urls .= $url;
			}
			
			$UpdateResults = powepress_admin_migrate_add_urls($add_urls);
			if( !empty($UpdateResults) )
			{
				// Queued ok...
				if( $update_option )
					update_option('powerpress_migrate_queued', $QueuedFiles);
				else
					add_option('powerpress_migrate_queued', $QueuedFiles, '', 'no');
			}
		}
		
		powerpress_page_message_print();

		echo '<h3>';
		echo __('Import Completed!', 'powerpress');
		echo '</h3>';
		echo '<p>'. sprintf(__('Items Skipped: %d', 'powerpress'), $this->m_item_skipped_count).'</p>';
		echo '<p>'. sprintf(__('Items Inserted: %d', 'powerpress'), $this->m_item_inserted_count).'</p>';
		echo '';
		if( $migrated_to_blubrry ) {
			echo '<p>'. sprintf(__('Visit %s to monitor the migration process.','powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>' ). '</p>';
		} else {
			echo '<p>'. sprintf(__('You may now migrate your media manually or use the %s tool.','powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>' ). '</p>';
		}
	}

	function dispatch() {
		
		$step = 0;
		if( !empty($_POST['step']) )
			$step = intval($_POST['step']);
		else if( !empty($_GET['step']) )
			$step = intval($_GET['step']);
			
		
		// Drop back down a step if not setup for hosting...
		if( !empty($_POST['migrate_to_blubrry']) ) {
			$Settings = get_option('powerpress_general');
			if( empty($Settings['blubrry_auth']) ) {
				echo '<div class="notice is-dismissible updated"><p>'. sprintf(__('You must have a blubrry Podcast Hosting account to continue.', 'powerpress')) .' '. '<a href="http://create.blubrry.com/resources/podcast-media-hosting/" target="_blank">'. __('Learn More', 'powerpress') .'</a>'. '</p></div>';
				$step = 0; // Drop back a step
			}
		}
		
		$this->header();

		switch ($step) {
			case 0 :
				$this->greet();
				break;
			case 1 :
				check_admin_referer('import-powerpress-rss');
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		}

		$this->footer();
	}
	
	function _post_exists_strict($guid)
	{
	global $wpdb;

		$post_guid = wp_unslash( sanitize_post_field( 'guid', $guid, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
		$args = array();

		if ( !empty ( $post_guid ) ) {
			$query .= 'AND guid = %s';
			$args[] = $post_guid;
		}

		if ( !empty ( $args ) )
			return intval( $wpdb->get_var( $wpdb->prepare($query, $args) ) );

		return 0;
	}
	
	function _post_exists($title, $guid = '', $date = '') {
		global $wpdb;

		$post_title = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
		$post_guid = wp_unslash( sanitize_post_field( 'guid', $guid, 0, 'db' ) );
		$post_date = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
		$args = array();

		if ( !empty ( $post_date ) ) {
			$query .= ' AND post_date = %s';
			$args[] = $post_date;
		}

		if ( !empty ( $post_title ) ) {
			$query .= ' AND post_title = %s';
			$args[] = $post_title;
		}

		if ( !empty ( $post_guid ) ) {
			$query .= 'AND guid = %s';
			$args[] = $post_guid;
		}

		if ( !empty ( $args ) )
			return intval( $wpdb->get_var( $wpdb->prepare($query, $args) ) );

		return 0;
	}
	
	function _import_post_to_db($post)
	{
		extract($post);
		$post_id = wp_insert_post($post);
		if ( is_wp_error( $post_id ) )
			return $post_id;
		if (!$post_id) {
			_e('Couldn&#8217;t get post ID', 'powerpress');
			return false;
		}

		if (0 != count($categories))
			wp_create_categories($categories, $post_id);
					
		if( !empty($enclosure['url']) )
		{
			$encstring = $enclosure['url'] . "\n" . $enclosure['length'] . "\n" . $enclosure['type'];
			$serialize = array();
			if( !empty($enclosure['duration']) && function_exists('powerpress_raw_duration') )
				$serialize['duration'] = powerpress_raw_duration($enclosure['duration']);
			if( !empty($enclosure['keywords']) )
				$serialize['keywords'] = $enclosure['keywords'];
			if( !empty($enclosure['summary']) )
				$serialize['summary'] = $enclosure['summary'];
			if( !empty($enclosure['subtitle']) )
				$serialize['subtitle'] = $enclosure['subtitle'];
			if( !empty($enclosure['author']) )
				$serialize['author'] = $enclosure['author'];
			if( !empty($enclosure['itunes_image']) )
				$serialize['itunes_image'] = $enclosure['itunes_image'];
			if( !empty($enclosure['block']) )
				$serialize['block'] = $enclosure['block'];
			if( !empty($enclosure['cc']) )
				$serialize['cc'] = $enclosure['cc'];
			if( !empty($enclosure['order']) )
				$serialize['order'] = $enclosure['order'];
			if( !empty($enclosure['explicit']) )
				$serialize['explicit'] = $enclosure['explicit'];
			
			if( !empty($serialize) )
				$encstring .= "\n". serialize( $serialize );
			$meta_id = add_post_meta($post_id, 'enclosure', $encstring, true);
			if( $meta_id ) {
				if( empty($GLOBALS['pp_migrate_media_urls']) )
					$GLOBALS['pp_migrate_media_urls'] = array();
				$GLOBALS['pp_migrate_media_urls'][ $meta_id ] = $enclosure['url'];
			}
		}
		return true;
	}
	
	function _parse_enclosure($string, $post)
	{
		global $wpdb;
		$p = xml_parser_create();
		xml_parse_into_struct($p, $string, $vals, $index);
		xml_parser_free($p);

		if( !empty($vals[0]['attributes']['URL']) )
		{
			$enclosure = array('url'=>trim($vals[0]['attributes']['URL']),'length'=>1, 'type'=>'');
			if(  !empty($vals[0]['attributes']['LENGTH']) )
				$enclosure['length'] = trim($vals[0]['attributes']['LENGTH']);
			if(  !empty($vals[0]['attributes']['TYPE']) )
				$enclosure['type'] = trim($vals[0]['attributes']['TYPE']);
			if( empty($enclosure['type']) )
				$enclosure['type'] = powerpress_get_contenttype($enclosure['url']);
			$matches = array();
			if( preg_match('|<itunes:duration>(.*?)</itunes:duration>|is', $post, $matches) )
			{
				$enclosure['duration'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			// keywords No longer supported by iTunes:
			if( preg_match('|<itunes:keywords>(.*?)</itunes:keywords>|is', $post, $matches) )
			{
				$enclosure['keywords'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:summary>(.*?)</itunes:summary>|is', $post, $matches) )
			{
				$enclosure['summary'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:subtitle>(.*?)</itunes:subtitle>|is', $post, $matches) )
			{
				$enclosure['subtitle'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:author>(.*?)</itunes:author>|is', $post, $matches) )
			{
				$enclosure['author'] = $this->_sanatize_tag_value(  $matches[1] );
			}
			
			if( preg_match('|<itunes:block>(.*?)</itunes:block>|is', $post, $matches) )
			{
				$value = strtolower(trim( $matches[1] ));
				if( $value == 'yes' )
					$enclosure['block'] = 1;
			}
			
			// <itunes:image href="http://example.com/podcasts/everything/AllAboutEverything.jpg" />
			if( preg_match('/<itunes:image[^h]*href="(.*?)".*(\/>|>.*<\/itunes:image>)/is', $post, $matches) )
			{
				$enclosure['itunes_image'] = html_entity_decode( trim( $matches[1] ) );
			}
			
			if( preg_match('|<itunes:isClosedCaptioned>(.*?)</itunes:isClosedCaptioned>|is', $post, $matches) )
			{
				$value = strtolower(trim( $matches[1] ));
				if( $value == 'yes' )
					$enclosure['cc'] = 1;
			}
			
			if( preg_match('|<itunes:order>(.*?)</itunes:order>|is', $post, $matches) )
			{
				$value = trim( $matches[1] );
				if( !empty($value) )
					$enclosure['order'] = intval($value);
			}
			
			if( preg_match('|<itunes:explicit>(.*?)</itunes:explicit>|is', $post, $matches) )
			{
				$explicit_array = array('yes'=>1, 'clean'=>2); // No need to save 'no'
				$value = strtolower( trim( $matches[1] ) );
				if( !empty($explicit_array[ $value ]) )
					$enclosure['explicit'] = $explicit_array[ $value ];
			}
				
			return $enclosure;
		}
		
		return '';
	}
	
	function _import_handle_url() {
		
		if( empty($_POST['podcast_feed_url']) ) {
			echo '<p>'.	__( 'URL is empty.', 'powerpress' ) .'<p>';
			return false;
		}
		
		$options = array();
		$options['user-agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
		if( !empty($_GET['import']) && $_GET['import'] == 'powerpress-squarespace-rss-podcast' )
			$options['user-agent'] = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
		$options['timeout'] = 10;
		
		$response = wp_safe_remote_get($_POST['podcast_feed_url'], $options);
		if ( is_wp_error( $response ) ) {
			echo '<p>'.	$response->get_error_message() .'<p>';
			return false;
		}

		$this->m_content = wp_remote_retrieve_body( $response );
		return true;
	}
	
	function _import_handle_upload() {
		if ( ! isset( $_FILES['podcast_feed_file'] )  || empty($_FILES['podcast_feed_file']['tmp_name']) ) {
			echo '<p>'.	__( 'Upload failed.', 'powerpress' ).'<p>';
			return false;
		}
		
		$this->m_content = file_get_contents($_FILES['podcast_feed_file']['tmp_name']);
		return true;
	}
} // end PowerPress_RSS_Podcast_Import class

	$powerpress_rss_podcast_import = new PowerPress_RSS_Podcast_Import();

	register_importer('powerpress-soundcloud-rss-podcast', __('Podcast from SoundCloud', 'powerpress'), __('Import episdoes from a SoundCloud podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-libsyn-rss-podcast', __('Podcast from LibSyn', 'powerpress'), __('Import episdoes from a LibSyn podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-podbean-rss-podcast', __('Podcast from PodBean ', 'powerpress'), __('Import episdoes from a PodBean podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-squarespace-rss-podcast', __('Podcast from Squarespace', 'powerpress'), __('Import episdoes from a Squarespace podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-rss-podcast', __('Podcast RSS Feed', 'powerpress'), __('Import episdoes from a RSS podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	
}; // end if WP_Importer exists

// eof
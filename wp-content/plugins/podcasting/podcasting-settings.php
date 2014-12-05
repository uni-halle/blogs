<?php

/**
 * Podcasting Settings Interface
 * @author The Software Group 
 * @author Spiral Web Consulting
 **/
class PodcastingSettings
{
	
	/**
	 * Initialize the Podcasting settings interface
	 **/
	function PodcastingSettings()
	{
		# Add Podcasting's settings
		add_action('admin_init', array($this, 'addPodcastingSettings'));
		
		# Add Podcasting's settings page
		add_action('admin_menu', array($this, 'addPodcastingSettingsPage'));
	}
	
	/**
	 * Add the settings page to the admin menu
	 */
	function addPodcastingSettingsPage() {
		# Add the options page
		add_options_page('Podcasting Settings', 'Podcasting', 'manage_options', basename(__FILE__), array($this, 'addSettings'));
	}
	
	/**
	 * Adds the settings page for Podcasting
	 */
	function addPodcastingSettings() {
    
    
        // If it's the first time through add the default format
        if ( is_admin() && (get_option( 'Activated_Plugin' ) == 'podcasting_plugin') ) {
    
            delete_option( 'Activated_Plugin' );
            
            // make sure the default format does not already exist
            $term = term_exists(TSG_DEAULT_FORMAT, 'podcast_format');
            if( $term == 0 || $term == null  )
            {
                /* do stuff once right after activation */
            	// Update taxonomy
            	$args = array('slug' => TSG_DEAULT_FORMAT );
                wp_insert_term("Default", 'podcast_format', $args);
                
            	// Update explicit
            	$pod_explicits["default"] = "no";
            	update_option(TSG_DEAULT_FORMAT, serialize($pod_explicits));
            }
        }            
    
		# Register Podcasting's settings
		if ( function_exists('register_setting') ) {
			register_setting('podcasting', 'pod_title', '');
			register_setting('podcasting', 'pod_tagline', '');
			register_setting('podcasting', 'pod_disable_enclose', '');
            
            register_setting('podcasting', 'pod_cat', '');
            
            //iTunes Options                        
			register_setting('podcasting', 'pod_itunes_summary', '');
			register_setting('podcasting', 'pod_itunes_author', '');
			register_setting('podcasting', 'pod_itunes_image', '');
			register_setting('podcasting', 'pod_itunes_cat1', '');
			register_setting('podcasting', 'pod_itunes_cat2', '');
			register_setting('podcasting', 'pod_itunes_cat3', '');
			register_setting('podcasting', 'pod_itunes_keywords', '');
			register_setting('podcasting', 'pod_itunes_explicit', '');
			register_setting('podcasting', 'pod_itunes_ownername', '');
			register_setting('podcasting', 'pod_itunes_owneremail', '');
			register_setting('podcasting', 'pod_itunes_new-feed-url', '');
            			
			register_setting('podcasting', 'pod_formats', '');
            
            //General Player Options
            register_setting('podcasting', 'pod_use_native_player', '');
            register_setting('podcasting', 'pod_player_use_video', '');
			register_setting('podcasting', 'pod_player_location');
			register_setting('podcasting', 'pod_player_text_above', '');
			register_setting('podcasting', 'pod_player_text_before', '');
			register_setting('podcasting', 'pod_player_text_below', '');
			register_setting('podcasting', 'pod_player_text_link', '');
                        
            //Flash Player Options
			register_setting('podcasting', 'pod_player_flashvars', '');
			register_setting('podcasting', 'pod_player_width', '');
			register_setting('podcasting', 'pod_player_height', '');
            
            //Flash Video Player Options
			register_setting('podcasting', 'pod_video_flashvars', '');
            
            //Flash Audio Player Options
            register_setting('podcasting', 'pod_audio_width', '');
                        
            // Native Player Options
            register_setting('podcasting', 'pod_player_audiovars', '');
            register_setting('podcasting', 'pod_player_videovars', '');
                     
			register_setting('podcasting', 'pod_accept_fail', '');
		}
       
	}
	
	
	/**
	 * Displays Podcasting's settings
	 */
	function addSettings() {
		// Check for delete
		if ( isset($_POST['term_ids']) ) {
			$term_ids = explode(',', $_POST['term_ids']);
			foreach ($term_ids as $term_id) {
				if ( isset($_POST["delete_pod_format_$term_id"]) ) {
					$_POST['Submit'] = 'Update';
				}
			}
		}

		// Store options if postback	
		if ( isset($_POST['Submit']) ) {
			// Prevent attacks
			if ( wp_verify_nonce($_POST['podcasting-nonce-key'], 'podcasting') ) {

				// Update the podcast options
				update_option('pod_title', $_POST['pod_title']);
				update_option('pod_tagline', $_POST['pod_tagline']);
				update_option('pod_disable_enclose', $_POST['pod_disable_enclose']);
                update_option('pod_cat', $_POST['pod_cat']);

				// Update the iTunes options
				update_option('pod_itunes_summary', $_POST['pod_itunes_summary']);
				update_option('pod_itunes_author', $_POST['pod_itunes_author']);
                if($_POST['pod_itunes_image'] == '')
                {
                    update_option('pod_itunes_image', $_POST['pod_itunes_image']);
                }
                else
                {
                    update_option('pod_itunes_image', podcasting_urlencode($_POST['pod_itunes_image']));
                }
				
				update_option('pod_itunes_cat1', $_POST['pod_itunes_cat1']);
				update_option('pod_itunes_cat2', $_POST['pod_itunes_cat2']);
				update_option('pod_itunes_cat3', $_POST['pod_itunes_cat3']);
				update_option('pod_itunes_keywords', $_POST['pod_itunes_keywords']);
				update_option('pod_itunes_explicit', $_POST['pod_itunes_explicit']);
				update_option('pod_itunes_ownername', $_POST['pod_itunes_ownername']);
				update_option('pod_itunes_owneremail', $_POST['pod_itunes_owneremail']);
				update_option('pod_itunes_new-feed-url', $_POST['pod_itunes_new-feed-url']);
				update_option('rss_language', $_POST['rss_language']);

				// Update the general player options
				update_option('pod_player_location', $_POST['pod_player_location']);
				update_option('pod_player_text_above', $_POST['pod_player_text_above']);
				update_option('pod_player_text_before', $_POST['pod_player_text_before']);
				update_option('pod_player_text_below', $_POST['pod_player_text_below']);
				update_option('pod_player_text_link', $_POST['pod_player_text_link']);
				update_option('pod_use_native_player', $_POST['pod_use_native_player']);
				

				// Update the audio player options
				update_option('pod_player_flashvars', $_POST['pod_player_flashvars']);
                update_option('pod_player_audiovars', $_POST['pod_player_audiovars']);
				update_option('pod_audio_width', $_POST['pod_audio_width']);
				update_option('pod_player_use_video', $_POST['pod_player_use_video']);

				// Update the video player options
//				update_option('pod_video_flashvars', $_POST['pod_video_flashvars']);
                update_option('pod_player_videovars', $_POST['pod_player_videovars']);
				update_option('pod_player_width', $_POST['pod_player_width']);
				update_option('pod_player_height', $_POST['pod_player_height']);
				
				// Update the advance options
				update_option('pod_accept_fail', $_POST['pod_accept_fail']);

				// Add a new format
				if ( '' != $_POST['pod_format_new_name'] ) {
					$args = ( '' != $_POST['pod_format_new_slug'] ) ? array('slug' => $_POST['pod_format_new_slug']) : '';
					$format = wp_insert_term($_POST['pod_format_new_name'], 'podcast_format', $args);
                    // Need to handle error here:
                    // object(WP_Error)#311 (2) { ["errors"]=> array(1) { ["term_exists"]=> array(1) { [0]=> string(54) "A term with the name and slug provided already exists." } } ["error_data"]=> array(1) { ["term_exists"]=> string(1) "2" } }object(WP_Error)#311 (2) { ["errors"]=> array(1) { ["term_exists"]=> array(1) { [0]=> string(54) "A term with the name and slug provided already exists." } } ["error_data"]=> array(1) { ["term_exists"]=> string(1) "2" } }
					$format = get_term($format['term_id'], 'podcast_format');

					$pod_explicits = unserialize(get_option('pod_formats'));
					$pod_explicits[$format->slug] = $_POST['pod_format_new_explicit'];						
					update_option('pod_formats', serialize($pod_explicits));
				}

				// Update formats
				if ( isset($_POST['term_ids']) ) {		
					foreach ( $term_ids as $term_id ) {
						$term_id = (int) $term_id;
						$format = get_term($term_id, 'podcast_format');

						if ( isset($_POST["delete_pod_format_$term_id"]) )
                        {
							wp_delete_term($term_id, 'podcast_format');
                        }
                        else
                        {
    						// Update taxonomy
    						$args = array( 'name' => $_POST["pod_format_name_$term_id"], 'slug' => $_POST["pod_format_slug_$term_id"] );
    						wp_update_term($term_id, 'podcast_format', $args);
    
    						// Update explicit
    						$pod_explicits[$_POST["pod_format_slug_$term_id"]] = $_POST["pod_format_explicit_$term_id"];
    						update_option('pod_formats', serialize($pod_explicits));
                        }
					}
				}

				// Give an updated message
				echo "<div class='updated fade'><p><strong>Podcasting settings saved.</strong></p></div>";
			}

			// Clear used variables
			unset($term_ids);
		}

		// iTunes category options
		$pod_itunes_cats = array(
			'Arts', 'Arts||Design', 'Arts||Fashion &amp; Beauty', 'Arts||Food', 'Arts||Literature', 'Arts||Performing Arts', 'Arts||Visual Arts',
			'Business', 'Business||Business News', 'Business||Careers', 'Business||Investing', 'Business||Management &amp; Marketing', 'Business||Shopping',
			'Comedy',
			'Education', 'Education||Education Technology', 'Education||Higher Education', 'Education||K-12', 'Education||Language Courses', 'Education||Training',
			'Games &amp; Hobbies', 'Games &amp; Hobbies||Automotive', 'Games &amp; Hobbies||Aviation', 'Games &amp; Hobbies||Hobbies', 'Games &amp; Hobbies||Other Games', 'Games &amp; Hobbies||Video Games',
			'Government &amp; Organizations', 'Government &amp; Organizations||Local', 'Government &amp; Organizations||National', 'Government &amp; Organizations||Non-Profit', 'Government &amp; Organizations||Regional',
			'Health', 'Health||Alternative Health', 'Health||Fitness &amp; Nutrition', 'Health||Self-Help', 'Health||Sexuality',
			'Kids &amp; Family',
			'Music',
			'News &amp; Politics',
			'Religion &amp; Spirituality', 'Religion &amp; Spirituality||Buddhism', 'Religion &amp; Spirituality||Christianity', 'Religion &amp; Spirituality||Hinduism', 'Religion &amp; Spirituality||Islam', 'Religion &amp; Spirituality||Judaism', 'Religion &amp; Spirituality||Other', 'Religion &amp; Spirituality||Spirituality',
			'Science &amp; Medicine', 'Science &amp; Medicine||Medicine', 'Science &amp; Medicine||Natural Sciences', 'Science &amp; Medicine||Social Sciences',
			'Society &amp; Culture', 'Society &amp; Culture||History', 'Society &amp; Culture||Personal Journals', 'Society &amp; Culture||Philosophy', 'Society &amp; Culture||Places &amp Travel',
			'Sports &amp; Recreation', 'Sports &amp; Recreation||Amateur', 'Sports &amp; Recreation||College &amp; High School', 'Sports &amp; Recreation||Outdoor', 'Sports &amp; Recreation||Professional',
			'Technology', 'Technology||Gadgets', 'Technology||Tech News', 'Technology||Podcasting', 'Technology||Software How-To',
			'TV &amp; Film'
			);

		$pod_formats = get_terms('podcast_format', 'get=all');

        
		?>

		<div class="wrap">
			
<h2>Podcasting Settings</h2>
			
		<form method="post" action="options-general.php?page=podcasting-settings.php">
		<?php $this->nonceField(); ?>
            <strong>Questions about using this plugin?  <a target="_blank" href="http://podcastingplugin.com/">Contact us</a> for help.</strong>
			<p>
            <em>
				
                Visit <a target="_blank" href="http://podcastingplugin.com/">PodcastingPlugin.com</a> for usage information and project news.
                The Podcasting plugin is brought to you by The Software Group.
			</em></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label>Podcast feed address (URL):</label>
					</th>
					<td>
						<p style="margin: 7px 0;"><strong>
						<?php 
                            global $wp_rewrite;
                            
                            echo get_option('home');

                            if ($wp_rewrite->using_permalinks())
                            {
                                echo '/feed/podcast/';
                            }
                            else
                            {
                                echo '?feed=podcast';
                            }
                            
						
						?>
						</strong></p>
                       
                        
                        </span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label>Advanced Category Podcast feed address (URL):</label>
					</th>
					<td>
						<p style="margin: 7px 0;"><strong>
							<?php 
                            global $wp_rewrite;
                            $pod_cat = get_option("pod_cat");
                            echo get_option('home');
                            echo '/';
                            if ($wp_rewrite->using_permalinks())
                            {
                                if(get_option( 'category_base' ) != '')
                                {
                                    echo get_option( 'category_base' );                               
                                }
                                else
                                {
                                    echo 'category';
                                }
                                
                                echo '/';
                                
                                wp_dropdown_categories('name=pod_cat&id=id&selected=' . $pod_cat ) ;
                                echo '/feed/podcast/';
                            }
                            else
                            {
                                echo "?cat=";
                                
                                $category_ids = get_all_category_ids();
                                echo'<select name="pod_cat" id="id">';
                                // For each value of the array assign variable name "city"

                                foreach($category_ids as $category_id)
                                {
                                    echo'<option value="' . $category_id;
                                    if($pod_cat = $category_id)
                                        echo " selected";
                                    echo '">'.$category_id.'</option>';
                                }
                                echo'</select>';
                                echo '&feed=podcast';
                            }
                            
						
						?>
						</strong></p>
                        <br />
                        See format urls below if you've created formats.<br />
                        <span class="setting-description">
                        
                        <br />
                        Most people create a category named 'podcasting' and tag podcasts with that category
                        to create a podcast only feed. More information can be found <a href="http://docs.podcastingplugin.com/?p=233">
                        on the podcast plugin help site.</a>
                        
                        </span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_title">Title:</label>
					</th>
					<td>
						<input type="text" style="width: 95%" name="pod_title" id="pod_title" value="<?php echo stripslashes(get_option('pod_title')); ?>" />
						<br /><span class="setting-description">If your podcast's title is different than your blog's title, change the title here.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_tagline">Podcast tagline:</label>
					</th>
					<td>
						<input type="text" style="width: 95%" name="pod_tagline" id="pod_tagline" value="<?php echo ent2ncr(htmlspecialchars(stripslashes(get_option('pod_tagline')))); ?>" />
						<br /><span class="setting-description">If your podcast's tagline is different than your blog's tagline, change the tagline here.</span>
					</td>
				</tr>
			</table>

			<h3>iTunes Specifics</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_itunes_summary">Summary:</label>
					</th>
					<td>
						<textarea cols="40" rows="4" style="width: 95%" name="pod_itunes_summary" id="pod_itunes_summary"><?php echo stripslashes(get_option('pod_itunes_summary')); ?></textarea>
						<br /><span class="setting-description">A detailed description of your podcast. iTunes allows up to 4,000 characters and the tagline will be used if no summary is entered.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_author">Author:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_itunes_author" id="pod_itunes_author" value="<?php echo stripslashes(get_option('pod_itunes_author')); ?>" />
						<br /><span class="setting-description">The default author of your podcast.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_image">Podcast Art (URL):</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_itunes_image" id="pod_itunes_image" value="<?php echo rawurldecode(stripslashes(get_option('pod_itunes_image'))); ?>" />
						<br /><span class="setting-description">An image which represents your podcast. iTunes uses this image on your podcast directory page and a smaller version in searches. iTunes prefers square .jpg images that are at least 300 x 300 pixels, but any jpg or png will work.</span>
					</td>
				</tr>
				<?php for ($i = 1; $i <= 3; $i++) {
				$pod_cat_option = 'pod_itunes_cat' . $i;
				$pod_cat_label = ( 1 == $i ) ? 'Primary Category' : 'Category ' . $i;
				$pod_cat_summary = ( 1 == $i ) ? 'The category which most fits your podcast. The primary category is used in Top Podcasts lists and directory pages which include podcast art.' : 'An optional additional category which is only used on directory pages without podcast art.';
				?>
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo $pod_cat_option; ?>"><?php echo $pod_cat_label; ?>:</label>
					</th>
					<td>
						<select name="<?php echo $pod_cat_option; ?>" id="<?php echo $pod_cat_option; ?>">
							<option value=""></option>
							<?php foreach ( $pod_itunes_cats as $pod_itunes_cat ) {
								// Deal with subcategories
								$pod_category = explode("||", $pod_itunes_cat);
								$pod_category_display = ( $pod_category[1] ) ? '&nbsp;&nbsp;&nbsp;' . $pod_category[1] : $pod_category[0];
								// If selected category
								$pod_selected = ( $pod_itunes_cat == htmlspecialchars(stripslashes(get_option($pod_cat_option))) ) ? ' selected="selected"' : '';

								echo '<option value="' . $pod_itunes_cat . '"' . $pod_selected . '>' . $pod_category_display . '</option>';
							} ?>
						</select>
						<br /><span class="setting-description"><?php echo $pod_cat_summary; ?></span>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_keywords">Keywords:</label>
					</th>
					<td>
						<input type="text" style="width: 95%" name="pod_itunes_keywords" id="pod_itunes_keywords" value="<?php echo stripslashes(get_option('pod_itunes_keywords')); ?>" />
						<br /><span class="setting-description">Up to 12 comma-separated words which iTunes uses for search placement.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_explicit">Explicit:</label>
					</th>
					<td>
						<select name="pod_itunes_explicit" id="pod_itunes_explicit">
							<option value="">No</option>
							<option value="yes"<?php echo ( 'yes' == get_option('pod_itunes_explicit') ) ? ' selected="selected"' : ''; ?>>Yes</option>
							<option value="clean"<?php echo ( 'clean' == get_option('pod_itunes_explicit') ) ? ' selected="selected"' : ''; ?>>Clean</option>
						</select>
						<br /><span class="setting-description">Notifies readers your podcast contains explicit material. Select clean if your podcast removed any explicit content. Note: iTunes requires all explicit podcast to mark them-self as one. Failure to do so can result in removal from the iTunes podcast directory.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_ownername">Owner Name:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_itunes_ownername" id="pod_itunes_ownername" value="<?php echo stripslashes(get_option('pod_itunes_ownername')); ?>" />
						<br /><span class="setting-description">Your podcast's owner's name. The owner name will not be publicly displayed and is used only by iTunes in the event they need to contact your podcast.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_owneremail">Owner E-mail Address:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_itunes_owneremail" id="pod_itunes_owneremail" value="<?php echo stripslashes(get_option('pod_itunes_owneremail')); ?>" />
						<br /><span class="setting-description">Your podcast's owner's e-mail address. The owner e-mail address will not be publicly displayed and is used only by iTunes in the event they need to contact your podcast.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="rss_language">Feed language:</label>
					</th>
					<td>
						<input type="text" size="40" name="rss_language" id="rss_language" value="<?php echo stripslashes(get_option('rss_language')); ?>" />
						<br /><span class="setting-description">The language of your feed. This value needs changing for international users looking to set this information in iTunes.</span>
					</td>
				</tr>
			</table>

<h3>General Player Options</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_location">Player location:</label>
					</th>
					<td>
						<select name="pod_player_location" id="pod_player_location">
							<option value="">Manual</option>
							<option value="top"<?php echo ( 'top' == get_option('pod_player_location') ) ? ' selected="selected"' : ''; ?>>Before Content</option>
							<option value="bottom"<?php echo ( 'bottom' == get_option('pod_player_location') ) ? ' selected="selected"' : ''; ?>>After Content</option>
						</select>
						<br /><span class="setting-description">Automatically insert the audio player or video player. Any players manually inserted will override this setting, so players can still be manually placed on a per-post basis.</span>
					</td>
				</tr>			
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_text_above">Text Above the Player:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_text_above" id="pod_player_text_above" value="<?php echo htmlentities(stripslashes(get_option('pod_player_text_above'))); ?>" />
						<br /><span class="setting-description">Text that will appear above the player.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_text_before">Text Before the Player</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_text_before" id="pod_player_text_before" value="<?php echo htmlentities(stripslashes(get_option('pod_player_text_before'))); ?>" />
						<br /><span class="setting-description">That that will appear on the line of the player, immediately before it. This text will not display for video players.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_player_text_below">Text Below the Player:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_text_below" id="pod_player_text_below" value="<?php echo htmlentities(stripslashes(get_option('pod_player_text_below'))); ?>" />
						<br /><span class="setting-description">Text that will appear below the player.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_text_link">Download Link Text</label>
					</th>
					<td>
						<select name="pod_player_text_link" id="pod_player_text_link">
							<?php $text_links = array('none', 'above', 'before', 'below');
							$text_link_option = get_option('pod_player_text_link');
							foreach ($text_links as $text_link) {
								$selected = ($text_link == $text_link_option) ? ' selected="selected"' : '';
								echo '<option value="' . $text_link . '"' . $selected . '>' . ucfirst($text_link) . '</option>';
							} ?>
						</select>
						<br /><span class="setting-description">Select the block of text that will link to the podcast file.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_use_native_player">Use HTML5 Players</label>
					</th>
					<td>
						<select name="pod_use_native_player" id="pod_use_native_player">
							<?php $text_links = array('yes', 'no');
							$text_link_option = get_option('pod_use_native_player');
							foreach ($text_links as $text_link) {
								$selected = ($text_link == $text_link_option) ? ' selected="selected"' : '';
								echo '<option value="' . $text_link . '"' . $selected . '>' . ucfirst($text_link) . '</option>';
							} ?>
						</select>
						<br />
                        <span class="setting-description">
                            Use native HTML5 enabled WordPress media players.
                        </span>
					</td>
                </td>                
			</table>

<h3>Audio Player Options</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="pod_audio_width">Player Width:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_audio_width" id="pod_audio_width" value="<?php echo get_option('pod_audio_width'); ?>" />
						<br /><span class="setting-description">The default width in pixels of the audio player.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_player_flashvars">Player Flashvars:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_flashvars" id="pod_player_flashvars" value="<?php echo stripslashes(get_option('pod_player_flashvars')); ?>" />
						<br /><span class="setting-description">Optional <a href="http://wpaudioplayer.com/standalone">WordPress Audio Player flashvars</a> that will apply on a global basis. Enter the flashvars like so: <code>autostart: 'yes', bg: 'e5e5e5'</code>. Additional flashvars can be appended on a per file basis by adding a flashvars=&quot;x&quot; parameter to the [podcast] tag.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_player_audiovars">Audio Player Vars:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_audiovars" id="pod_player_audiovars" value="<?php echo stripslashes(get_option('pod_player_audiovars')); ?>" />
						<br /><span class="setting-description">
                        The 'src' is already set. You can add <a target="_blank" href="https://codex.wordpress.org/Audio_Shortcode">other options</a> here.
                        </span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_use_video">Use Video Player:</label>
					</th>
					<td>
						<select name="pod_player_use_video" id="pod_player_use_video">
							<?php $text_links = array('no', 'yes');
							$text_link_option = get_option('pod_player_use_video');
							foreach ($text_links as $text_link) {
								$selected = ($text_link == $text_link_option) ? ' selected="selected"' : '';
								echo '<option value="' . $text_link . '"' . $selected . '>' . ucfirst($text_link) . '</option>';
							} ?>
						</select>
						<br /><span class="setting-description">Selecting this option will use the video player instead of the audio player for audio files.</span>
					</td>
				</tr>

			</table>

<h3>Video Player Options</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_width">Default Player Width:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_width" id="pod_player_width" value="<?php echo get_option('pod_player_width'); ?>" />
						<br /><span class="setting-description">The default width in pixels of the video player. This can be changed on a per video basis by adding a width=&quot;x&quot; parameter to the [podcast] tag.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_player_height">Default Player Height:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_height" id="pod_player_height" value="<?php echo get_option('pod_player_height'); ?>" />
						<br /><span class="setting-description">The default height in pixels of the video player. This can be changed on a per video basis by adding a height=&quot;y&quot; parameter to the [podcast] tag.</span>
					</td>
				</tr>
				<!--
                <tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_video_flashvars">Player Flashvars:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_video_flashvars" id="pod_video_flashvars" value="<?php //echo stripslashes(get_option('pod_video_flashvars')); ?>" />
						<br /><span class="setting-description">Optional <a href="http://code.longtailvideo.com/trac/wiki/FlashVars">JW FLV Player flashvars</a> that will apply on a global basis. Enter the flashvars like so: <code>autostart: 'true', bufferlength: 4</code>. Additional flashvars can be appended on a per video basis by adding a flashvars=&quot;x&quot; parameter to the [podcast] tag.</span>
					</td>
				</tr>
                -->
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_player_videovars">Video Player Vars:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_player_videovars" id="pod_player_videovars" value="<?php echo stripslashes(get_option('pod_player_videovars')); ?>" />
						<br /><span class="setting-description">
                        The 'src', 'height' and 'width' are already set. You can add <a target="_blank" href="https://codex.wordpress.org/Video_Shortcode">other options</a> here.
                        </span>
					</td>
				</tr>

			</table>
			
<h3>Advanced Options</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="pod_disable_enclose">Disable auto-enclose:</label>
					</th>
					<td>
						<select name="pod_disable_enclose" id="pod_disable_enclose">
							<option value="">No</option>
							<option value="yes"<?php echo ( 'yes' == get_option('pod_disable_enclose') ) ? ' selected="selected"' : ''; ?>>Yes</option>
						</select>
						<br /><span class="setting-description">Enabling this option will prevent WordPress from automatically enclosing file URLs in the content of your posts. This is helpful if you're trying to keep certain files from appearing in your Podcasting feed.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_accept_fail">Bypass error check:</label>
					</th>
					<td>
						<select name="pod_accept_fail" id="pod_accept_fail">
							<option value="no">No</option>
							<option value="yes"<?php echo ( 'yes' == get_option('pod_accept_fail') ) ? ' selected="selected"' : ''; ?>>Yes</option>
						</select>
						<br /><span class="setting-description"><strong>WARNING:</strong> Enabling this option will disable the 404 check and file size check that occurs when adding a new podcast episode. When enabled, any new file added through Podcasting will report itself as being 1MB in size. This may cause issues with some Podcatchers that rely on accurate enclosure information. Only enable this option if you're experiencing constant HTTP connection errors (resulting in 404s) and are absolutely sure the file exists on the server. <strong>Use this option at your own risk.</strong></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_itunes_new-feed-url">New Podcast Location(URL):</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_itunes_new-feed-url" id="pod_new-feed-url" value="<?php echo rawurldecode(stripslashes(get_option('pod_itunes_new-feed-url'))); ?>" />
						<br /><span class="setting-description">
            <strong>This field is usually kept blank.</strong>
            Add a URL here only if you need to move your podcast feed to a new URL.
            This option is used to inform iTunes of a new feed URL location. 
            Enter the full URL of your new podcast feed location.</span>
					</td>
				</tr>				
			</table>

			<p class="submit">
				<?php if ( function_exists('settings_fields') ) settings_fields('podcasting'); ?>
				<input type="submit" name="Submit" value="Save Changes" />
			</p>
            <?php tsg_podcasting_var_dump($pod_formats); ?> 

				<br />
<h3>Media Format Feed Definitions</h3>
        <p>The Podcasting Plugin allows your podcast to create feeds for multiple
        media formats. For example you might want to distribute an audio feed with mp3 files
        and a video feed with mp4 files. In this case you could set up for format or each or use the
        default format for audio and create a new mp4 format for your video feed.<p/>
				<?php 
                $term_ids = '';
                $term_count = 0;
                foreach ($pod_formats as $pod_format) 
                {
					if ( $term_count > 0 ) 
                        $term_ids .= ','; 
                    $term_count++;
					$term_ids .= $pod_format->term_id;     
                    
                    // build the feed URL for this format
                    $readonly = '';  
                
                    $appender = "&";
                    if ($wp_rewrite->using_permalinks())
                    {
                        $appender = "?";
                    }
                 
                    $format_option = "format=$pod_format->slug"; 
                    
                    // allow for 'no format needed' if default   
    				if ($pod_format->slug == TSG_DEAULT_FORMAT) 
                    {
                        $readonly = 'readonly="readonly"';
                        $format_option = '';
                        $appender = "";
                        
                    }    
                    
    

                    ?>

					<table cellpadding="3" class="pod_format">
						<tr>
							<td class="pod-title">Format Feed</td>
							<td colspan="3">
								<input type="text" name="pod_format_feed" class="pod_format_feed" value="<?php
								global $wp_rewrite;
                                /*
                                echo get_option('home');
                                echo '/';
                                echo get_option( 'category_base' );
                                echo '/';
                                $pod_cat = get_option("pod_cat");
                                echo get_the_category_by_ID( $pod_cat );                      
                                */
								echo $this->getCategoryFeedURL($pod_cat);
                                echo $appender;
                                echo $format_option;
								 ?>" readonly="readonly" />
							</td>
						</tr>
						<tr>
							<td class="pod-title">Format Name</td>
							<td><input type="text" name="pod_format_name_<?php echo $pod_format->term_id; ?>" class="pod_format_name" value="<?php echo $pod_format->name?>" <?php echo $readonly; ?>/>					
							<td class="pod-title">Format Slug</td>
							<td><input type="text" name="pod_format_slug_<?php echo $pod_format->term_id; ?>" class="pod_format_slug" value="<?php echo $pod_format->slug ?>" <?php echo $readonly; ?>/></td>					
						</tr>
                        <tr>
							<td class="pod-title">Explicit</td>
							<td><select name="pod_format_explicit_<?php echo $pod_format->term_id; ?>" class="pod_format_explicit">
								<?php $explicits = array('', 'no', 'yes', 'clean');
								$format_explicit = unserialize(get_option('pod_formats'));
								foreach ($explicits as $explicit) {
									$selected = ($explicit == $format_explicit[$pod_format->slug]) ? ' selected="selected"' : '';
									echo '<option value="' . $explicit . '"' . $selected . '>' . ucfirst($explicit) . '</option>';
								} ?>
							</select></td>	
                            <?php
                            if ( 'default-format' == $pod_format->slug )
                            {
                            ?>				
							<td colspan="2" class="pod-update">
								<input name="Submit" type="submit" class="button-secondary" value="Update" />                            
                           </td>
                            <?php
                            }
                            else
                            {
                            ?>
							<td class="pod-delete">
								<input name="delete_pod_format_<?php echo $pod_format->term_id; ?>" type="submit" class="button-secondary" value="Delete" onclick="return deleteSomething( 'podcast_format', <?php echo $pod_format->term_id; ?>, 'You are about to delete a podcast format. All episodes currently assigned to this format will become assigned to no format.\n\'OK\' to delete, \'Cancel\' to stop.' );" /> 
                            </td> 
							<td class="pod-update">
								<input name="Submit" type="submit" class="button-secondary" value="Update" />                            
                            </td>                            
                            <?php
                            }
                            ?>


                        </tr>
					</table>
					<input name="term_ids" type="hidden" value="<?php echo $term_ids; ?>" />
    		<?php  } ?>
		

			<br />

<h3>Add a New Media Format Feed Definition</h3>
<p>If you need an addition media format feed definition you can add it here.</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row" style="width: 200px;">
						<label for="pod_format_new_name">Format name:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_format_new_name" id="pod_format_name" value="" />
						<br /><span class="setting-description">The display name of your new new format.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_format_new_slug">Format slug:</label>
					</th>
					<td>
						<input type="text" size="40" name="pod_format_new_slug" id="pod_format_new_slug" value="" />
						<br /><span class="setting-description">If you leave this field blank, a slug will automatically be generated for you.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pod_format_new_explicit">Explicit:</label>
					</th>
					<td>
						<select name="pod_format_new_explicit" id="pod_format_new_explicit">
							<option value=""></option>
							<option value="no">No</option>
							<option value="yes">Yes</option>
							<option value="clean">Clean</option>
						</select>
						<br /><span class="setting-description">The explicit setting for this format. If you leave this field blank, your global podcast explicit setting will be used.</span>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" name="Submit" value="Add Format" />
			</p>
		</div>
		</form>

		<?php
	}
	
	/**
	 * The nonce field
	 */
	function nonceField() {
		echo "<input type='hidden' name='podcasting-nonce-key' value='" . wp_create_nonce('podcasting') . "' />";
	}

	/**
	 * Category functions
	 */
         
    function getCategoryFeedURL($cat_id)
    {
        global $wp_rewrite;
        
        $ret = $cat_id;


        $ret = get_category_link( $cat_id );
        

        if($wp_rewrite->using_permalinks())
        {        
            $ret .= 'feed/podcast/';
        }
        else
        {
            $ret .= '&feed=podcast';
        }        
       
        return $ret;
    }
    
    function getCategoryURLFirst()
    {
        $ret = get_option('home');
        $ret .= '/';
        if ($wp_rewrite->using_permalinks())
        {
            if(get_option( 'category_base' ) != '')
            {
                $ret .= get_option( 'category_base' );
                $ret .= '/';
            }
        }
        else
        {
            $ret .= "?cat=";
        }
        
        return $ret;
	}
}

# Start the Podcasting settings interface
$podcasting_settings = new PodcastingSettings();

?>

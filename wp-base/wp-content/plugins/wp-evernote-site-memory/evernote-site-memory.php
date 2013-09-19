<?php
/*
Plugin Name: WP Evernote Site Memory
Plugin URI: http://www.slocumstudio.com
Version: 2.0.2
Author: <a href="http://www.slocumdesignstudio.com">Slocum Design Studio</a> - <a href="http://www.jonathandesrosiers.com/">Jonathan Desrosiers</a>
Description: WP Evernote Site Memory integrates Evernote's site memory feature into your Wordpress blog, placing your fully customizable site memory button at the end of every page and post.

Copyright 2010 Slocum Design Studio

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

*/

if ( ! class_exists( 'EvernoteSiteMemoryPlugin' ) ) {

	class EvernoteSiteMemoryPlugin {

		private $options;
		private $defaults = array(
				'providerName' => '',
				'nameInTitle' => 1,
				'suggestedNotebook' => '',
				'contentID' => '',
				'affiliateCode' => '',
				'button' => 'article-clipper.png',
				'styling' => 'text',
				'signature' => '',
				'header' => '',
				'footer' => '',
				'js' => 'normal',
				'beforeOrAfter' => 'after'
			);

		function __construct() {
			$this->setup_options();

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'admin_init' ) );

			//Add the button to the content
			add_filter( 'the_content', array( $this, 'the_content' ) );
			add_filter( 'the_excerpt', array( $this, 'the_excerpt' ) );
		}

		function setup_options() {
			//Convert our old options to new options if we haven't done so already
			if ( ! $has_upgraded = get_option( 'wp_evernote_has_upgraded' ) ) {
				if ( $old_options = get_option( 'evernoteSiteMemoryAdminOptions' ) ) {
					$new_options = wp_parse_args( $old_options, $this->defaults );

					if ( $new_options['nameInTitle'] == 'yes' )
						$new_options['nameInTitle'] = 1;
					else
						$new_options['nameInTitle'] = 0;

					update_option( 'wp_evernote_options', $new_options );
					update_option( 'wp_evernote_has_upgraded', 1 );
					delete_option( 'evernoteSiteMemoryAdminOptions' );
				}
			}

			if ( $current_options = get_option( 'wp_evernote_options' ) ) {
				$this->options = wp_parse_args( $current_options, $this->defaults );
			} else {
				$this->options = $this->defaults;
				update_option( 'wp_evernote_options', $current_options );
			}
		}

		function admin_menu() {
			add_options_page( 'Evernote Site Memory for Wordpress', 'Site Memory', 'manage_options', 'wp-evernote-site-memory', array( $this, 'settings_page' ) );
		}

		function wp_enqueue_scripts() {
			if( $this->options['js'] == 'minified' )
				wp_enqueue_script( 'wp-evernote-site-memory', 'http://static.evernote.com/noteit.min.js', array(), '2.0', true );
			else
				wp_enqueue_script( 'wp-evernote-site-memory', 'http://static.evernote.com/noteit.js', array(), '2.0', true );
		}

		function wp_enqueue_styles() {
			wp_enqueue_style( 'wp-evernote-site-memory', plugins_url( 'css/styles.css', __FILE__ ), array(), '2.0' );
		}

		function admin_enqueue_scripts() {
			wp_enqueue_style( 'wp-evernote-site-memory-admin', plugins_url( 'css/admin.css', __FILE__ ), array(), '2.0' );
			wp_enqueue_style( 'wp-evernote-site-memory', plugins_url( 'css/styles.css', __FILE__ ), array(), '2.0' );
		}

		function admin_init() {

			if ( ! current_user_can( 'manage_options' ) )
				return;

			//Register setting
			register_setting( 'wp_evernote_options', 'wp_evernote_options', array( $this, 'wp_evernote_validate' ) );

			//Color Scheme Settings
			add_settings_section( 'wp_evernote_basic_section', 'Settings', array( $this, 'wp_evernote_basic_section_callback' ), 'wp-evernote-site-memory' );

				add_settings_field( 'wp_evernote_provider_name', 'Provider Name:', array( $this, 'wp_evernote_provider_name_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_provider_name_title', "Provider's Name In Title:", array( $this, 'wp_evernote_provider_name_title_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_suggested_notebook', "Suggested Notebook:", array( $this, 'wp_evernote_suggested_notebook_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_content_id', 'Content ID:', array( $this, 'wp_evernote_content_id_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_affiliate_code', 'Affiliate Code:', array( $this, 'wp_evernote_affiliate_code_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_button_style', 'Button Style:', array( $this, 'wp_evernote_button_style_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_button_position', 'Button Position:', array( $this, 'wp_evernote_button_postition_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_javascript_type', 'JavaScript Type:', array( $this, 'wp_evernote_javascript_type_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_styling_type', 'Clip Styling:', array( $this, 'wp_evernote_styling_type_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_signature', 'Signature:', array( $this, 'wp_evernote_signature_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_header', 'Note Header:', array( $this, 'wp_evernote_header_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
				add_settings_field( 'wp_evernote_footer', 'Note Footer:', array( $this, 'wp_evernote_footer_field' ), 'wp-evernote-site-memory', 'wp_evernote_basic_section', $this->options );
		}

		function wp_evernote_basic_section_callback() {
		?>
			<div class="postbox siteMemoryPostBox">
		    	<p>Evernote makes it easy to remember things big and small from your notable life using your computer, phone, and the web</p>
		    	<p>With a click, your visitors can save posts and pages into Evernote. You define what gets saved, where in Evernote the clip is placed, and how clips should be tagged. It's like giving your site a favorite function while hardly lifting a finger.</p>
		    	<p>
		    		<strong>
			    		Below are ways to customize the way your site is clipped into Evernote.<br />
			    		If a field is left blank, the default value will be used.<br />
			    		Default values are listed below each input.
		    		</strong>
		    	</p>
	    	</div>
	    	<?php
		}

		function wp_evernote_provider_name_field( $options ) {
		?>
			<input type="text" name="wp_evernote_options[providerName]" id="wp_evernote_options[providerName]" value="<?php echo esc_attr( stripslashes( $this->options['providerName'] ) ); ?>" class="regular-text" /><br />
			<span class="description">Enter provider's name. If left blank, "<strong><?php bloginfo( 'name' ); ?></strong>" will be used.</span>
			<?php
		}

		function wp_evernote_provider_name_title_field( $options ) {
			?>
			<fieldset>
				<label>
					<input name="wp_evernote_options[nameInTitle]" value="1" type="radio" <?php checked( $this->options['nameInTitle'] ); ?> /> Yes<br />
					<input name="wp_evernote_options[nameInTitle]" value="0" type="radio"<?php checked( ! $this->options['nameInTitle'] ); ?> /> No
				</label>
			</fieldset>
		 	<span class="description">
		 		Would you like the provider name to appear in the title of the note?<br />
		 		Yes will read: "<strong>Post/Page Name on Provider's Name</strong>" if a name is provided above, or "<strong>Post/Page Name on <?php bloginfo('name' ); ?></strong>" if no name is provided above.<br />
		 		No will read: "<strong>Post/Page name</strong>"</span>
		 	<?php
		}

		function wp_evernote_suggested_notebook_field( $options ) {
			?>
				<input type="text" name="wp_evernote_options[suggestedNotebook]" value="<?php echo esc_attr( stripslashes( $this->options['suggestedNotebook'] ) ); ?>" class="regular-text" /><br />
   				<span class="description">Enter the suggested notebook. If left blank, Evernote will choose one for you based on your site's content.</span>
		 	<?php
		}

		function wp_evernote_content_id_field( $options ) {
			?>
				<input type="text" name="wp_evernote_options[contentID]" value="<?php echo esc_attr( stripslashes( $this->options['contentID'] ) ); ?>" class="regular-text" /><br />
				<span class="description">The ID of the HTML element containing the content to be clipped, for example, a &lt;div&gt;. If left blank, "<strong>post-ID</strong>" for that post will be used.</span>
		 	<?php
		}

		function wp_evernote_affiliate_code_field( $options ) {
		?>
			<input type="text" name="wp_evernote_options[affiliateCode]" value="<?php echo esc_attr( stripslashes( $this->options['affiliateCode'] ) ); ?>" class="regular-text" /><br />
			<span class="description">The Evernote Affiliate Program is designed to reward developers and publishers for referring new users to Evernote.<br />
			For more information, please visit <a href="http://www.evernote.com/about/affiliate/?utm_source=<?php $bName = get_bloginfo('name' ); echo urlencode( $bName ); ?>&utm_medium=siteMemoryWordpressPlugin&utm_campaign=adminPanel" target="_blank">Evernote's website.</a></span>
		 	<?php
		}

		function wp_evernote_button_style_field( $options ) {
			?>
				<fieldset>
				<?php
					$imageNames = array( 'article-clipper.png', 'article-clipper-remember.png', 'article-clipper-es.png', 'article-clipper-fr.png', 'article-clipper-de.png', 'article-clipper-jp.png', 'article-clipper-rus.png', 'article-clipper-vert.png', 'site-mem-36.png', 'site-mem-32.png', 'site-mem-22.png', 'site-mem-16.png' );

					foreach( $imageNames as $img ) {
						if ( $img == 'article-clipper-vert.png' )
							echo "<br />";
						?>

						<label class="siteMemoryButton">
							<input name="wp_evernote_options[button]" value="<?php echo $img; ?>" type="radio" <?php checked( $this->options['button'], $img ); ?> />
							<img src="http://static.evernote.com/<?php echo $img; ?>" />
						</label>
						<?php
					}
				?>
				<br />

				<label class="siteMemoryButton">
					<input name="wp_evernote_options[button]" value="large.png" type="radio" <?php checked( $this->options['button'], 'large.png' ); ?> />
					<img src="<?php echo plugins_url( '/img/large.png', __FILE__ ); ?>" />
				</label>

				<label class="siteMemoryButton">
					<input name="wp_evernote_options[button]" value="medium.png" type="radio" <?php checked( $this->options['button'], 'medium.png' ); ?> />
					<img src="<?php echo plugins_url( '/img/medium.png', __FILE__ ); ?>" />
				</label>
					<br />
					<br />

					<label class="siteMemoryButton">
						<div class="evernoteSiteMemory">
							<input name="wp_evernote_options[button]" value="smallclip.png" type="radio"<?php if( $this->options['button'] == 'smallclip.png' ) { ?> checked<?php } ?> style="float: left; height: 79px; margin-right: 5px;" />
							<img src="<?php echo plugins_url( '/img/smallclip.png', __FILE__ ); ?>" style="padding: 0px !important;" />
							<p class="evernoteSiteMemoryDescription">
<strong>Evernote</strong> lets you save all the interesting things you see online into a single place. Access all those saved pages from your computer, phone or the web.  <a href="https://www.evernote.com/Registration.action" title="Sign up for Evernote" target="_blank">Sign up now</a> or <a href="https://www.evernote.com/about/learn_more/" title="Learn more about Evernote" target="_blank">learn more</a>. It's free!
</p><div class="evernoteSiteMemoryClear">&nbsp;</div>
						</div>
					</label>
			</fieldset>
			<?php
		}

		function wp_evernote_button_postition_field( $options ) {
			?>
				<fieldset>
					<label>
						<input name="wp_evernote_options[beforeAfter]" value="before" type="radio"<?php if( $this->options['beforeOrAfter'] == 'before' ) { ?> checked<?php } ?> /> Before<br />
						<input name="wp_evernote_options[beforeAfter]" value="after" type="radio"<?php if( $this->options['beforeOrAfter'] == 'after' ) { ?> checked<?php } ?> /> After
					</label>
				</fieldset>
			 	<span class="description">
			 		Would you like the button to appear before or after the post's content?
			 	</span>
			 	<?php
		}

		function wp_evernote_styling_type_field( $options ) {
			?>
				<select name="wp_evernote_options[styling]" id="styling">
					<option<?php if( $this->options['styling'] == 'full' ) { ?> selected="selected"<?php } ?> value="full">Full</option>
					<option<?php if( $this->options['styling'] == 'text' ) { ?> selected="selected"<?php } ?> value="text">Text</option>
					<option<?php if( $this->options['styling'] == 'none' ) { ?> selected="selected"<?php } ?> value="none">None</option>
				</select><br />
				<span class="description">Select the type of styling to use when clipping your site.he clip styling strategy that the clipper should use<br />
				Valid values are "<strong>none</strong>" to ignore page styles, "<strong>text</strong>" to only apply page styles to textual elements, and "<strong>full</strong>" to attempt to copy the full styling of the page.<br />
				The default value is "<strong>text</strong>", which we suggest using as it yields clips that display consistently across platforms.<br />
				"full" styling often looks good one one platform but fails to render well on others.<br />
				<strong>Note that there is currently no way to style clips made from IE.</strong></span>
			 	<?php
		}

		function wp_evernote_javascript_type_field( $options ) {
			?>
				<select name="wp_evernote_options[js]" id="js">
					<option<?php if( $this->options['js'] == 'normal' ) { ?> selected="selected"<?php } ?> value="normal">Normal</option>
					<option<?php if( $this->options['js'] == 'minified' ) { ?> selected="selected"<?php } ?> value="minified">Minified</option>
				</select><br />
				<span class="description">Site Memory is completely static until the user clicks on the button, so it won't slow down your page loads. Site Memory content is distributed via Amazon CloudFront, so the reader's browser doesn't even make a request to an actual Evernote server until they click on a Site Memory button.<br />
				If you are really concerned about saving every byte possible, load the minified JavaScript library.</span>
			<?php
		}

		function wp_evernote_signature_field( $options ) {
			?>
				<textarea name="wp_evernote_options[signature]" id="wp_evernote_options[signature]" rows="10" cols="50" class="large-text code"><?php echo stripslashes( $this->options['signature'] ); ?></textarea><br />
				<span class="description">A string containing a signature that will be appended to the main content of the clip, separated by a horizontal rule.<br />
				The following basic HTML tags are allowed: &lt;a&gt;, &lt;em&gt;, &lt;strong&gt;, &lt;img&gt;, &lt;p&gt;, &lt;br&gt;, &lt;div&gt;</span>
				<?php
		}

		function wp_evernote_header_field( $options ) {
			?>
				<textarea name="wp_evernote_options[header]" id="wp_evernote_options[header]" rows="10" cols="50" class="large-text code"><?php echo stripslashes( $this->options['header'] ); ?></textarea><br />
				<span class="description">A string containing a header that will be prepended to the main content.<br />
				The following basic HTML tags are allowed: &lt;a&gt;, &lt;em&gt;, &lt;strong&gt;, &lt;img&gt;, &lt;p&gt;, &lt;br&gt;, &lt;div&gt;</span>
				<?php
		}

		function wp_evernote_footer_field( $options ) {
			?>
				<textarea name="wp_evernote_options[footer]" id="wp_evernote_options[footer]" rows="10" cols="50" class="large-text code"><?php echo stripslashes( $this->options['footer'] ); ?></textarea><br />
				<span class="description">A string containing a footer that will be appended to the main content. Unlike signature, footer will not be separated by a horizontal rule.<br />
				The following basic HTML tags are allowed: &lt;a&gt;, &lt;em&gt;, &lt;strong&gt;, &lt;img&gt;, &lt;p&gt;, &lt;br&gt;, &lt;div&gt;</span>
				<br />
				<?php
		}

		function wp_evernote_validate( $input ) {
			$new_input = wp_parse_args( $input, $this->defaults );

			$new_input['providerName'] = htmlspecialchars( sanitize_text_field( $new_input['providerName'] ), ENT_QUOTES );
			$new_input['nameInTitle'] = sanitize_text_field( $new_input['nameInTitle'] );
			$new_input['suggestedNotebook'] = htmlspecialchars( sanitize_text_field( $new_input['suggestedNotebook'] ), ENT_QUOTES );
			$new_input['contentID'] = htmlspecialchars( sanitize_text_field( $new_input['contentID'] ), ENT_QUOTES );
			$new_input['affiliateCode'] = htmlspecialchars( sanitize_text_field( $new_input['affiliateCode'] ), ENT_QUOTES );
			$new_input['button'] = sanitize_text_field( $new_input['button'] );
			$new_input['styling'] = sanitize_text_field( $new_input['styling'] );
			$new_input['signature'] = htmlspecialchars( sanitize_text_field( $new_input['signature'] ), ENT_QUOTES );
			$new_input['header'] = htmlspecialchars( sanitize_text_field( $new_input['header'] ), ENT_QUOTES );
			$new_input['footer'] = htmlspecialchars( sanitize_text_field( $new_input['footer'] ), ENT_QUOTES );
			$new_input['js'] = sanitize_text_field( $new_input['js'] );
			$new_input['beforeOrAfter'] = sanitize_text_field( $new_input['beforeAfter'] );

			return $new_input;
		}

		function settings_page() {
		?>
			<div class="wrap">
				<div id="icon-evernote" class="icon32"><img src="<?php echo plugins_url( '/img/admin-icon.png', __FILE__ ); ?>" /></div>
				<h2>WP Evernote Site Memory</h2>
				<?php settings_errors(); ?>

				<form method="post" action="options.php">
					<?php
						settings_fields( 'wp_evernote_options' );

						do_settings_sections( 'wp-evernote-site-memory' );
						submit_button();
					?>
				</form>
			</div>
			<?php
		}

		//Add the Clip button code to the post's content
		function the_content( $content = '' ) {

			$options = $this->options;

			$countTags = 1;
			$theSuggestedTags = '';

			//Get the tags for the current post and create a string for use in the link
			$currentposttags = get_the_tags();

				if ( $currentposttags) {
					foreach( $currentposttags as $tag) {
						//if this is not the first tag, add a comma before the next tag
						if(! ( $countTags == 1) ) {
							$theSuggestedTags .= ",";
						}

						//Add the tag to the list
						$theSuggestedTags .= $tag->name;

						//increment
						$countTags++;
				}

				$theSuggestedTags = addslashes( $theSuggestedTags );
				$theSuggestedTags = str_replace( '&amp;', '&', $theSuggestedTags );
			}

			$currentTitle = get_the_title();

			$currentTitle = str_replace( array( '&#8220;', '&#8221;' ), '&quot;', $currentTitle);
			$currentTitle = str_replace('&#8217;', '&#039;', $currentTitle);
			$currentTitle = str_replace('&#038;', '&amp;', $currentTitle);

			//wrap in div
			$buttonCode = '<div class="evernoteSiteMemory">';

			//Add the first parameters to the link
			$buttonCode .= '<a href="javascript:" onclick="Evernote.doClip({title: \'' . htmlspecialchars( addslashes( htmlspecialchars_decode( $currentTitle, ENT_QUOTES ) ), ENT_QUOTES);

			//check whether to display the provider's name in the title of the note
			if( $this->options['nameInTitle'] == 'yes' ) {
				if( $this->options['providerName'] == '' )
					$buttonCode .= ' on ' . htmlspecialchars( addslashes( htmlspecialchars_decode( get_bloginfo(), ENT_QUOTES ) ), ENT_QUOTES ) . "',";
				else
					$buttonCode .= ' on ' . $this->options['providerName'] . "',";
			} else {
				$buttonCode .= "',";
			}

			//add the url
			$buttonCode .= "url: '" . get_permalink() . "',";

			//check for a contentID and add to link
			if( $this->options['contentID'] == '' ) {
				    $buttonCode .= "contentID: 'post-" . get_the_ID() . "',";
			} else {
				$buttonCode .= "contentID: '" . $this->options['contentID'] . "',";
			}

			//check for affiliate code and add if necessary
			if( ! ( $this->options['affiliateCode'] == '' ) ) {
				$buttonCode .= "code: '" . $this->options['affiliateCode'] . "',";
			}

			//Check for signature, and add if necessary
			if( !( $this->options['signature'] == '' ) ) {
				$buttonCode .= "signature: '" . $this->options['signature'] . "',";
			}

			//check for header and add if necessary
			if( ! ( $this->options['header'] == '' ) ) {
				$buttonCode .= "header: '" . $this->options['header'] . "',";
			}

			//check for footer and add if necessary
			if( ! ( $this->options['footer'] == '' ) ) {
				$buttonCode .= "footer: '" . $this->options['footer'] . "',";
			}

			//Check for custom suggested notebook
			if( $this->options['suggestedNotebook'] == '' ) {
				//    $buttonCode .= "suggestNotebook: '" . get_bloginfo('name' ) . " Notes',";
			} else {
				$buttonCode .= "suggestNotebook: '" . $this->options['suggestedNotebook'] . "',";
			}

			//add suggested tags
			$buttonCode .= "suggestTags: '" . htmlspecialchars( $theSuggestedTags, ENT_QUOTES ) . "',";

			//check for custom provider name
			if( $this->options['providerName'] == '' ) {
				$buttonCode .= "providerName: '" . htmlspecialchars( addslashes( htmlspecialchars_decode( get_bloginfo('name' ), ENT_QUOTES ) ), ENT_QUOTES ) . "',";
			} else {
				$buttonCode .= "providerName: '" . $this->options['providerName'] . "',";
			}

			//Add styling option
			$buttonCode .= "styling: '" . $this->options['styling'] . "'";

			//close link tag
			$buttonCode .= " });" . 'return false" class="evernoteSiteMemoryLink">';

			//detect image style
			if( $this->options['button'] == 'smallclip.png' ) {
				$buttonCode .= '<img src="' . plugins_url( '/img/' . stripslashes( $this->options['button'] ), __FILE__ ) . '" class="evernoteSiteMemoryButton" /></a>
				<p class="evernoteSiteMemoryDescription">
					<strong>Evernote</strong> lets you save all the interesting things you see online into a single place. Access all those saved pages from your computer, phone or the web.  <a href="https://www.evernote.com/Registration.action" title="Sign up for Evernote" target="_blank">Sign up now</a> or <a href="https://www.evernote.com/about/learn_more/" title="Learn more about Evernote" target="_blank">learn more</a>. It\'s free!
				</p>

				<div class="evernoteSiteMemoryClear">&nbsp;</div>
			</div>';
			}
			else if( $this->options['button'] == 'medium.png' || $this->options['button'] == 'large.png' ) {
				$buttonCode .= '<img src="' . plugins_url( '/img/' . stripslashes( $this->options['button'] ), __FILE__ ) . '" class="evernoteSiteMemoryButton" />
				</a>				<div class="evernoteSiteMemoryClear">&nbsp;</div></div>';
			} else {
				$buttonCode .= '<img src="http://static.evernote.com/' . stripslashes( $this->options['button'] ) . '" class="evernoteSiteMemoryButton" />
				</a>				<div class="evernoteSiteMemoryClear">&nbsp;</div>
</div>';
			}

			//Add button before or after content
			if( $this->options['beforeOrAfter'] == 'before' )
				$content = $buttonCode . $content;
			else
				$content .= $buttonCode;

			return $content;
		 }

		function the_excerpt( $excerpt = '' ) {

			if( $this->options['button'] == 'smallclip.png' && trim( get_the_excerpt() ) == '' ) {

				$cont = get_the_content();
				$excerpt = apply_filters( 'get_the_excerpt', $cont );
			}
			return $excerpt;
		}

	}
} //End Class EvernoteSiteMemoryPlugin


if ( class_exists( 'EvernoteSiteMemoryPlugin' ) )
	$siteMemory = new EvernoteSiteMemoryPlugin();
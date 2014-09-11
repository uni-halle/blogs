<?php
	if (!defined('WP_CONTENT_DIR')) die;

	/**
	* Function returns a row on the settings page. That's right, it's a function within a function.
	*/
	function tw_row_head($rh,$lfor=""){
		return ' 
					<th class="twhrow" scope="row" valign="top">
						<label for="'.$lfor.'">
							'.$rh.'
						</label>
					</th>';
	}	
	function tw_checkbox_input ($thisobj, $opname, $label){
		$str = '<input type="checkbox" value="1"';
		if ($thisobj->tw_get_option($opname) == '1') $str .= ' checked="checked"';
		$str .= ' name="'.$opname.'" id="'.$opname.'"/> <label for="'.$opname.'">'.__($label,$thisobj->txtdom).'</label>';
		return $str;
	}
	
	function tw_google_tracking($thisobj){
		if (class_exists('WP_Http')){
			if ($thisobj->tw_get_option('tw_url_shortener_google_token') !=''){
				echo '<p><a href="'.$thisobj->settingsuri.'&goauth=delete" class="button">'.__ ('Remove authorization data',$thisobj->txtdom).'</a></p>';
				?><input type="hidden" value="<?php echo $thisobj->tw_get_option('tw_url_shortener_google_token');?>" name="tw_url_shortener_google_token" /><?php
			} else {
				$gsparams = http_build_query(
					array(
						'next' => admin_url().$thisobj->settingsuri,
						'scope' => 'https://www.googleapis.com/auth/urlshortener',
						'session' => 1
					)
				);
				$google_auth_url = 'https://www.google.com/accounts/AuthSubRequest' . '?' . $gsparams;
				?><input type="button" class="button" value="Authorize goo.gl tracking" onclick="window.location = '<?php echo $google_auth_url;?>';"/><br /><?php
			}
		}
	}
		$msg['w3'] = __('W3 Total Cache page cache has been cleared',$this->txtdom);
		$msg['sc'] = __('WP Super Cache cleared',$this->txtdom);
		$msg['oauthok'] = __('Auto-Tweeting authorized. You can now <a href="#tw_auto_tweet_box">configure</a> Auto-Tweeting.',$this->txtdom);
		$msg['oauthdel'] = __('Auto-Tweeting authorization has been removed. Auto-Tweeting has been disabled.',$this->txtdom);
		$msg['goauthok'] = __('Goo.gl authorized.',$this->txtdom);
		$msg['goauthdel'] = __('Goo.gl authorization has been removed.',$this->txtdom);
		if (isset($_GET['op2'])) if (
			$_GET['op2']=='w3' || 
			$_GET['op2']=='goauthok' || 
			$_GET['op2']=='goauthdel' ||
			$_GET['op2']=='oauthok' ||
			$_GET['op2']=='oauthdel' ||
			$_GET['op2']=='sc') 
		{
			$updmsg = '<p><strong>' . $msg[$_GET['op2']] . '</strong></p>';
		} else {
			$updmsg = null;
		}
		if (isset($_GET['op1'])) if ($_GET['op1']=='1') echo '<div id="message" class="updated fade"><p><strong>' . __('Tweet Button settings have been saved',$this->txtdom) . '</strong></p>'.$updmsg.'</div>';
		if (isset($_GET['op2'])) if ($_GET['op2']=='oauthok' || $_GET['op2']=='oauthdel' || $_GET['op2']=='goauthok' || $_GET['op2']=='goauthdel') echo '<div id="message" class="updated fade">'.$updmsg.'</div>';
		if (isset($_GET['op3'])) if ($_GET['op3']=='fs') echo '<div id="message" class="updated fade"><p><strong>All cached shortlinks have been deleted.</strong></p></div>';
		if (isset($_GET['showdiag'])) if ($_GET['showdiag']=='1') echo '<div id="message" class="updated fade"><pre><strong>' . 'PHP version: ' . phpversion(). '<br />'.$this->pluginlabel.' configuration: ' .print_r($this->options,true) . '</strong></pre></div>';
		?>
		<form method="post" action="" name="twsettingsform" id="twsettingsform"><?php
			if (function_exists('settings_fields')){
				settings_fields('tw-options');
			} else {
				wp_nonce_field('update-options');
				$paramstr = '';
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="page_options" value="';
				foreach($this->options as $key => $option) {
					$paramstr .= $key.',';
				}
				echo substr($paramstr,0, -1);
				echo '" />';
			}
		?>
			<div>
				<table style="margin:0" class="form-table">
					<tr class="bt1pcs">
						<td class="twhdata" colspan="3"  style="background: #E3E3E3;border-top:1px solid #CCC;border-bottom:1px solid #CCC;">
							<p class="submit">
								<input style="width:160px" type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
							</p>				
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Display',$this->txtdom));?>
						<td class="twhdata">
							<div style="margin-right:20px;width:160px;" class="floatleft180">
								<label for="tw_where"><?php _e('Position',$this->txtdom); ?></label><br />						
								<select name="tw_where" class="w99c29c">
									<option <?php if ($this->tw_get_option('tw_where') == 'before') echo 'selected="selected"'; ?> value="before"><?php _e('Before',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_where') == 'after') echo 'selected="selected"'; ?> value="after"><?php _e('After',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter"><?php _e('Before and After',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_where') == 'shortcode') echo 'selected="selected"'; ?> value="shortcode"><?php _e('Shortcode',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_where') == 'manual') echo 'selected="selected"'; ?> value="manual"><?php _e('Manual',$this->txtdom);?></option>
								</select>
							</div>
							<div class="floatleft180">
								<label for="tw_align"><?php _e('Alignment',$this->txtdom); ?></label><br />						
								<select name="tw_align" class="w99c29c">
									<option <?php if ($this->tw_get_option('tw_align') == 'none') echo 'selected="selected"'; ?> value="none"><?php _e('None (Default)',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_align') == 'right') echo 'selected="selected"'; ?> value="right"><?php _e('Right',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_align') == 'left') echo 'selected="selected"'; ?> value="left"><?php _e('Left',$this->txtdom);?></option>
									<option <?php if ($this->tw_get_option('tw_align') == 'center') echo 'selected="selected"'; ?> value="center"><?php _e('Center',$this->txtdom);?></option>
								</select>
							</div>
							<p style="clear:both;"></p>
							<p style="margin:10px 0 0 0"><label><?php _e('Visibility',$this->txtdom);?></label></p>
							<div class="floatleft180">
								<?php echo tw_checkbox_input($this, 'tw_display_single','Posts'); ?>
							</div>
							<div class="floatleft100">
								<?php echo tw_checkbox_input($this, 'tw_display_page','Pages'); ?>

							</div>
							<div class="floatleft180" style="clear:left;">
								<?php echo tw_checkbox_input($this, 'tw_display_front','Front page (home)'); ?>
							</div>
							<div class="floatleft100">
								<?php echo tw_checkbox_input($this, 'tw_display_feed','RSS feeds'); ?>
							</div>
							<div class="floatleft180" style="clear:left;">
								<?php echo tw_checkbox_input($this, 'tw_display_archive','Archives'); ?>
							</div>
							<div class="floatleft100">
								<?php echo tw_checkbox_input($this, 'tw_display_search','Search'); ?>
							</div>
							<div class="floatleft180" style="clear:left;">
								<?php echo tw_checkbox_input($this, 'tw_display_excerpt','Excerpts'); ?>
							</div>
							<?php
							if (function_exists("bnc_wptouch_is_mobile")) {
							?>
							<div class="floatleft100">
								<?php echo tw_checkbox_input($this, 'tw_display_mobile','Mobile (WPTouch)'); ?>
							</div>
							<?php
							}
							?>
							<div class="floatleft180" style="clear:left;">
								<?php echo tw_checkbox_input($this, 'tw_ex_after_archive','No After in Archives'); ?>
							</div>
							<div class="floatleft180" style="clear:left;">
								<?php echo tw_checkbox_input($this, 'tw_ex_after_home','No After on Front page'); ?>
							</div>
							<p style="clear:both;"></p>
							<?php
							$selectedPT = $this->tw_get_option('tw_post_type_exclude');
							if (!is_array($selectedPT))$selectedPT=array();
							$args=array('public' => true, '_builtin' => false); 								
							$output = 'objects';
							$post_types=get_post_types($args,$output);
							if (!empty($post_types)){?>
							<p style="clear:both;margin:20px 0 0 0"><?php _e('You can also EXCLUDE the Tweet Button from custom post types by checking them here.',$this->txtdom);?></p><?php
								foreach ($post_types  as $name => $post_type ) {
									echo '<div class="floatleft180"><label><input type="checkbox" name="tw_post_type_exclude[]" '.(in_array($name,$selectedPT) ? 'checked="checked" ' : '').'value="'.$name.'" /> ' . $post_type->label .'</label></div>';
								}
							} ?>
							<p style="clear:both;"></p>
						</td>
						<td class="twhdata twhhlp">
							<p style="text-align:center;margin-bottom:20px;">
							<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8YYQTCLT37SEG" title="Donate via paypal">
								<img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" alt="PayPal - The safer, easier way to pay online!"/>
							</a>
							</p>							
							<p>
								<?php _e('Set your look-and-feel preferences.',$this->txtdom); ?>
							</p>
							<p>
								<?php _e('The <strong>"No After on Front page"</strong> option allows you to use "Before and After" placement and the "Front page" placement but exclude the button from the bottom of posts on the front page.',$this->txtdom); ?>
							</p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Tweet Button style',$this->txtdom));?>
						<td class="twhdata">
							<div style="float:left;margin-right:10px;">
							<input type="radio" value="vertical" class="floatleftm10" <?php if ($this->tw_get_option('tw_count') == 'vertical') echo 'checked="checked"'; ?> name="tw_count" id="tw_count_vertical" group="tw_count"/>
							<label for="tw_count_vertical" class="tweetbtnc" style="border-right: 1px solid #E3E3E3;width:70px;background:transparent url('<?php echo WP_PLUGIN_URL; ?>/wp-tweet-button/tweetv.png') no-repeat 0 24px"><?php _e('Vertical',$this->txtdom);?></label>
							</div>
							<div style="float:left;margin-right:10px;">
							<input type="radio" value="horizontal" class="floatleftm10" <?php if ($this->tw_get_option('tw_count') == 'horizontal') echo 'checked="checked"'; ?> name="tw_count" id="tw_count_horizontal" group="tw_count"/>
							<label for="tw_count_horizontal" class="tweetbtnc" style="border-right: 1px solid #E3E3E3;width:120px;background:transparent url('<?php echo WP_PLUGIN_URL; ?>/wp-tweet-button/tweeth.png') no-repeat  0 24px"><?php _e('Horizontal',$this->txtdom);?></label>
							</div>
							<div style="float:left;">
							<input type="radio" value="none" class="floatleftm10" <?php if ($this->tw_get_option('tw_count') == 'none') echo 'checked="checked"'; ?> name="tw_count" id="tw_count_none" group="tw_count" />
							<label for="tw_count_none" class="tweetbtnc" style="width:60px;background:transparent url('<?php $wpt_pluginpath = (empty($_SERVER['HTTPS'])) ? WP_PLUGIN_URL : str_replace("http://", "https://", WP_PLUGIN_URL); echo $wpt_pluginpath; ?>/wp-tweet-button/tweetn.png') no-repeat  0 24px"><?php _e('No count',$this->txtdom);?></label>
							</div>
						</td>
						<td class="twhdata twhhlp">
							<p>
							</p>
						</td>
					</tr>
					<tr class="bt1pcs">
					<?php 
					if (function_exists('get_user_meta')){
						echo tw_row_head(__('Default Twitter username',$this->txtdom),'tw_via');
					} else {
						echo tw_row_head(__('Twitter username',$this->txtdom),'tw_via');
					}
					?>
					<td class="twhdata">
							<input class="pad45 w99c29c" type="text" value="<?php echo $this->tw_get_option('tw_via'); ?>" name="tw_via" id="tw_via" /><br />
							<?php
								if (function_exists('get_user_meta')){
									echo tw_checkbox_input($this, 'tw_via_authors','Posts belong to this account. (Overrides author accounts)');
								} ?>
						</td>
						<td class="twhdata twhhlp">
							<?php
								if (function_exists('get_user_meta')){?>
								<p>
									<?php _e('Authors can configure their own Twitter accounts on their <a href="profile.php" title="your profile page">profile</a> page. Check the "Override author accounts" box to ignore these settings.',$this->txtdom); ?>
								</p>
								<?php
								} ?>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Referral format',$this->txtdom),'tw_text_format');?>
						<td class="twhdata">
							<input class="floatlm4" type="radio" value="VIA" <?php if ($this->tw_get_option('tw_text_format') == 'VIA') echo 'checked="checked"'; ?> name="tw_text_format" id="tw_text_format_via" group="tw_text_format"/>
							<label class="floatlmn2" style="width:160px;" for="tw_text_format_via"><?php _e('{text} {link} via {@user}',$this->txtdom);?></label>
							<input class="floatlm4" type="radio" value="RT" <?php if ($this->tw_get_option('tw_text_format') == 'RT') echo 'checked="checked"'; ?> name="tw_text_format" id="tw_text_format_rt" group="tw_text_format"/>
							<label class="floatlmn2" style="width:150px;" for="tw_text_format_rt"><?php _e('RT {@user} {text} {link}',$this->txtdom);?></label>
						</td>
						<td class="twhdata twhhlp">&nbsp;
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Default Tweet text',$this->txtdom),'tw_text');?>
						<td class="twhdata">
							<input class="" type="radio" value="entry_title" <?php if ($this->tw_get_option('tw_text') == 'entry_title') echo 'checked="checked"'; ?> name="tw_text" id="tw_text_entry_title" group="tw_text"/>
							<label class="" for="tw_text_entry_title"><?php _e('Entry title e.g., "Pictures Of My Cat"',$this->txtdom);?></label><br />
							<input class="" type="radio" value="page_title" <?php if ($this->tw_get_option('tw_text') == 'page_title') echo 'checked="checked"'; ?> name="tw_text" id="tw_text_page_title" group="tw_text"/>
							<label class="" for="tw_text_page_title"><?php _e('Page title e.g., "Pictures of my cat - My Wordpress Blog"',$this->txtdom);?></label><br />
							<input class="" type="radio" value="blog_title" <?php if ($this->tw_get_option('tw_text') == 'blog_title') echo 'checked="checked"'; ?> name="tw_text" id="tw_text_blog_title" group="tw_text"/>
							<label class="" for="tw_text_blog_title"><?php _e('Blog title e.g., "My Wordpress Blog"',$this->txtdom);?></label><br />
							<input class="" type="radio" value="custom_title" onclick="var fnow=document.getElementById('tw_text_custom');fnow.focus();" <?php if ($this->tw_get_option('tw_text') == 'custom_title') echo 'checked="checked"'; ?> name="tw_text" id="tw_text_custom_title" group="tw_text" />
							<label class="" for="tw_text_custom_title" onclick="var fnow=document.getElementById('tw_text_custom');fnow.focus();"><?php _e('Custom text',$this->txtdom);?></label><br />
							<textarea class="pad45" style="font-size: 11px;width:300px" type="text" name="tw_text_custom" id="tw_text_custom"><?php if ($this->tw_get_option('tw_text_custom')) echo stripslashes($this->tw_get_option('tw_text_custom')); ?></textarea>
						</td>
						<td class="twhdata twhhlp">
							<p><strong><?php _e('Tags for custom text:',$this->txtdom);?></strong></p>
							<p>
								<code>%POSTTITLE%</code> - <?php _e('The post\'s title.',$this->txtdom);?><br />
								<code>%POSTCONTENT%</code> - <?php _e('The post\'s content.',$this->txtdom);?><br />
								<code>%BLOGTITLE%</code> - <?php _e('The blog\'s name.',$this->txtdom);?>
								<code>%BLOGHASHTAGS%</code> - <?php _e('#Hash tags generated using blog tags.',$this->txtdom);?>
								<code>%BLOGHASHCATS%</code> - <?php _e('#Hash tags generated using blog categories.',$this->txtdom);?>
							</p>
							<p><?php _e('Setting a tweet text for specific posts will override this default selection.',$this->txtdom);?></p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Auto-Tweeting',$this->txtdom),'tw_auto_tweet_box');?>
						<td class="twhdata"><a href="#" id="tw_auto_tweet_box" name="tw_auto_tweet_box"></a>
							<?php
							if ($this->tw_get_option('tw_auto_tweet_token') == '' || $this->tw_get_option('tw_auto_tweet_token_secret') == ''){
								echo '<p>' . __ ('Before you can configure Auto-Tweeting you must first authorize an account. Once you have authorized an account, return here to configure Auto-Tweeting.',$this->txtdom).'</p>';
								echo '<p><a href="'.$this->wptbsrv.'?do=login&return_url='.urlencode(admin_url().$this->settingsuri).'" class="button">'.__ ('Authorize',$this->txtdom).'</a></p>';
							} else {
								echo '<p><a href="'.$this->wptbsrv.'?do=logout&return_url='.urlencode(admin_url().$this->settingsuri).'" class="button">'.__ ('Remove authorization data',$this->txtdom).'</a></p>';
								echo '<p>'. __ ('Auto-tweets will be posted by ',$this->txtdom).'<span class="dark"><strong>'.$this->tw_get_option('tw_auto_tweet_via').'</strong></span></p>';
								?>
								<input type="hidden" value="<?php echo $this->tw_get_option('tw_auto_tweet_via');?>" name="tw_auto_tweet_via" />
								<input type="hidden" value="<?php echo $this->tw_get_option('tw_auto_tweet_token');?>" name="tw_auto_tweet_token" />
								<input type="hidden" value="<?php echo $this->tw_get_option('tw_auto_tweet_token_secret');?>" name="tw_auto_tweet_token_secret" />
								<p><?php _e('When posts are:',$this->txtdom); ?><br />
									<?php echo tw_checkbox_input($this, 'tw_auto_tweet_posts','Created'); ?><br />
									<?php echo tw_checkbox_input($this, 'tw_auto_tweet_posts_onsave','Saved'); ?><br />
								</p>
								<p><?php _e('When pages are:',$this->txtdom); ?><br />
									<?php echo tw_checkbox_input($this, 'tw_auto_tweet_pages','Created'); ?><br />
									<?php echo tw_checkbox_input($this, 'tw_auto_tweet_pages_onsave','Saved'); ?><br />
								</p>
								<p>
									<?php echo tw_checkbox_input($this, 'tw_auto_long_tweet_mode','Long Tweet Mode'); ?><br />
									<?php _e('When enabled, automatically generated tweets will consist of the first part of your entry followed by a link. Use this option to override the "Default Tweet text" setting.',$this->txtdom);?>
								</p>
<?php 
							} ?>
							
						</td>
						<td class="twhdata twhhlp">
							<p>
								<?php _e ('Auto-Tweeting will allow your blog to send out a tweet when a post is published or saved. By default auto tweets are sent out only once but can be repeated from the post edit page.',$this->txtdom);?>
							</p>
							<p>
								<?php _e ('It is recommended to select a URL shortener before enabling Auto-Tweeting.',$this->txtdom);?>
							</p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Default recommended Twitter user',$this->txtdom),'tw_rec');?>
						<td class="twhdata">
							<input class="pad45 w99c29c" type="text" value="<?php echo $this->tw_get_option('tw_rec'); ?>" name="tw_rec" id="tw_rec" /><br />
							<label for="tw_rec_desc"><?php _e('Description',$this->txtdom);?></label><br />
							<input class="pad45 w99c29c" type="text" value="<?php echo $this->tw_get_option('tw_rec_desc'); ?>" name="tw_rec_desc" id="tw_rec_desc"/>
							<p><?php _e ('After the user tweets the entry, Twitter will allow the user to follow a recommended users. Set a default recommended user here. You can also recommend Twitter users individually in posts and pages using the Tweet Button options.',$this->txtdom);?></p>
							<?php echo tw_checkbox_input($this, 'tw_rec_authors','Use the author\'s Twitter account if configured.'); ?>
						</td>
						<td class="twhdata twhhlp">
							<p>
								<?php _e ('Twitter recommendations configured in posts override these default settings.',$this->txtdom);?>
							</p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Container style',$this->txtdom),'tw_style_c');?>
						<td class="twhdata">
							<input class="pad45 w99c29c" type="text" value="<?php echo htmlspecialchars($this->tw_get_option('tw_style_c')); ?>" name="tw_style_c" id="tw_style_c" />
						</td>
						<td class="twhdata twhhlp" rowspan="1">
							<p><?php _e ('Use the container style box to add additional CSS properties to the DIV surrounding the Tweet Button.',$this->txtdom);?>
							</p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Tweet Button language',$this->txtdom),'tw_lang');?>
						<td class="twhdata">
							<select id="tw_lang" name="tw_lang" style="" class="w99c29c">
								<option value="en" <?php if ($this->tw_get_option('tw_lang') == 'en') echo 'selected="selected"'; ?>><?php _e('English',$this->txtdom);?></option> 
								<option value="fr" <?php if ($this->tw_get_option('tw_lang') == 'fr') echo 'selected="selected"'; ?>><?php _e('French',$this->txtdom);?></option> 
								<option value="de" <?php if ($this->tw_get_option('tw_lang') == 'de') echo 'selected="selected"'; ?>><?php _e('German',$this->txtdom);?></option> 
								<option value="es" <?php if ($this->tw_get_option('tw_lang') == 'es') echo 'selected="selected"'; ?>><?php _e('Spanish',$this->txtdom);?></option> 
								<option value="ja" <?php if ($this->tw_get_option('tw_lang') == 'ja') echo 'selected="selected"'; ?>><?php _e('Japanese',$this->txtdom);?></option>
							</select> 
						</td>
						<td class="twhdata twhhlp" rowspan="2">
							<p><?php _e('This is the language that the button will render in on your website.',$this->txtdom);?></p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Rel metatag',$this->txtdom),'tw_rel_meta');?>
						<td class="twhdata">
							<input class="pad45 w99c29c" type="text" value="<?php echo htmlspecialchars($this->tw_get_option('tw_rel_meta')); ?>" name="tw_rel_meta" id="tw_rel_meta" />
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Tweet link text',$this->txtdom),'tw_btn_text');?>
						<td class="twhdata">
							<input class="pad45 w99c29c" type="text" value="<?php echo htmlspecialchars($this->tw_get_option('tw_btn_text')); ?>" name="tw_btn_text" id="tw_btn_text" />
						</td>
						<td class="twhdata twhhlp">
							<p><?php _e('The text of the link before it is made into a button by Javascript. Clearing this field will prevent non-javascript clients from seeing the tweet link.',$this->txtdom);?></p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('URL Shortener',$this->txtdom),'tw_url_shortener');?>
						<td class="twhdata" style="border-top:1px solid #fff;">
							<select id="tw_url_shortener" name="tw_url_shortener" style="" class="w99c29c">
								<?php
								$shrtnr = $this->tw_get_option('tw_url_shortener');
								foreach ($this->shortenerdata as $name=>$alias){
									if ($alias['enabled'] != 'false'){
										echo '<option '.(($shrtnr == $name) ? 'selected="selected" ':'').'value="'.$name.'">'. $alias['label'] .'</option>'."\r\n";
									}
								}
								?>
								<?php if (function_exists('permalink_to_twitter_link')){?><option <?php if ($shrtnr == 'tflp') echo 'selected="selected"'; ?> value="tflp">Twitter Friendly Links Plugin</option><?php } ?>
								<?php if (function_exists('wp_get_shortlink')){?><option <?php if ($shrtnr == 'wordpress') echo 'selected="selected"'; ?> value="wordpress">Wordpress 3 shortener function</option><?php } ?>
								<?php if (function_exists('wp_ozh_yourls_raw_url')){?><option <?php if ($shrtnr == 'yourls') echo 'selected="selected"'; ?> value="yourls">YOURLS Plugin</option><?php } ?>
							</select> <br />
								<p style="width:230px;margin-right:10px;float:left;"><strong><?php _e('Limitations',$this->txtdom);?></strong><br /><?php _e('If you select a shortener from the list, Twitter will still wrap the shortened URL with its own short URL. (t.co)',$this->txtdom);?></p>
								<p style="width:230px;float:left;"><strong><?php _e('Usernames and API Keys', $this->txtdom);?></strong><br /><?php _e ('Some URL shortening services may require(*) usernames and/or API key.',$this->txtdom);?></p>
								<br style="clear:both" />
								<input type="checkbox" value="1" name="tw_flush_cached_shortlinks" id="tw_flush_cached_shortlinks" />
								<label for="tw_flush_cached_shortlinks"><?php _e('Delete all previously saved shortlinks when I save.',$this->txtdom); ?></label><br />
								<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_no_https_shortlinks') == '1') echo 'checked="checked"'; ?> name="tw_no_https_shortlinks" id="tw_no_https_shortlinks" />
								<label for="tw_no_https_shortlinks"><?php _e('Do not shrink HTTPS URLs.',$this->txtdom); ?></label><br />
						</td>
						<td class="twhdata twhhlp" crowspan="<?php echo ($this->shortenerdata['tinyarrow']['enabled']!='false') ? '7' : '6';?>" valign="top" style="border-top:1px solid #fff;">
						</td>
					</tr>
<?php if (function_exists('wp_get_shortlink')){?>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Wordpress 3.0 shortener',$this->txtdom));?>
						<td class="twhdata">
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_strip_www') == '1') echo 'checked="checked"'; ?> name="tw_strip_www" id="tw_strip_www" />
							<label for="tw_strip_www"><?php _e('Remove the "www." prefix from my domain.',$this->txtdom); ?></label><br />
							<?php
							if (function_exists('stats_get_option')) {?>
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_no_wporg') == '1') echo 'checked="checked"'; ?> name="tw_no_wporg" id="tw_no_wporg" />
							<label for="tw_no_wporg"><?php _e('Disable the WP.org shortener.',$this->txtdom); ?></label><br />
							<?php } ?>
						</td>
						<td class="twhdata twhhlp" crowspan="<?php echo ($this->shortenerdata['tinyarrow']['enabled']!='false') ? '7' : '6';?>" valign="top" style="border-top:1px solid #fff;">
						</td>
					</tr>
<?php } ?>
					<?php
						foreach ($this->shortenerdata as $name=>$values){
							$labelinput ='';
							if ($values['enabled'] != 'false'){
								if (is_array($values['params'])){
									foreach ($values['params'] as $item=>$props){
										if (is_array($props)){
											if ($props['value'] != ''){
												$labelinput .= '
												<p>
												<label for="'.$props['value'].'">'.__($props['name'],$this->txtdom).'</label>'.(($props['important']==true) ? '*' : '').'<br />
												<input class="pad45 w99c29c" type="text" value="'.$this->tw_get_option($props['value']).'" name="'.$props['value'].'" id="'.$props['value'].'"/>
												</p>';
											}
										}
									}
								}
							}
							if ($labelinput !=''){
								echo '<tr class="bt1pcs">'.tw_row_head($values['label']).'<td class="twhdata">';
								echo $labelinput;
								if ($name=='google'){tw_google_tracking($this);}
								echo '</td><td class="twhdata twhhlp">&nbsp;</td></tr>';
							}
						}
					if ($this->shortenerdata['tinyarrow']['enabled'] != 'false'){?>
					<tr class="bt1pcs">
						<?php echo tw_row_head('TinyArro.ws');
						$tw_url_shortener_tinyarrow_host = $this->tw_get_option('tw_url_shortener_tinyarrow_host');
						?>
						<td>
							<!-- p>
								<label for="tw_url_shortener_tinyarrow_user"><?php _e('UserID',$this->txtdom);?></label>
								<input class="pad45 w99c29c" type="text" value="<?php echo $this->tw_get_option('tw_url_shortener_tinyarrow_user');?>" name="tw_url_shortener_tinyarrow_user" id="tw_url_shortener_tinyarrow_user"/>
							</p -->
							<p>
							<label for="tw_url_shortener_tinyarrow_host"><?php _e('Host',$this->txtdom);?></label>
							<select style="font-size:200%" id="tw_url_shortener_tinyarrow_host" name="tw_url_shortener_tinyarrow_host" style="" class="w99c29c"> 
							<?php
							foreach ($this->tinyarrow_hosts as $host=>$alias){
								echo '<option '. (($tw_url_shortener_tinyarrow_host == $host) ? 'selected="selected" ':'').'value="'. $host .'">'.$alias.'</option>'."\r\n";
							}
							?>
							</select>
						</td>
						<td class="twhdata twhhlp">
						</td>
					</tr>
					<?php } ?>
					<tr class="bt1pcs">
						<?php echo tw_row_head(__('Advanced',$this->txtdom),'tw_bwdata_attr');?>
						<td class="twhdata" style="border-top:1px solid #ccc;">							
							<label for="tw_hook_prio"><?php _e('Filter hook priority. <span class="warning">Only change this value if you know what you\'re doing!</span> (Default is 75)',$this->txtdom); ?></label><br />
							<select name="tw_hook_prio" id="tw_hook_prio" title="<?php echo $this->tw_get_option('tw_hook_prio');?>" class="w99c29c">
							<option value="75">75 (Default)</option>
							<option <?php echo ($this->tw_get_option('tw_hook_prio')=='101')?'selected="selected"':'';?> value="101">101</option>
							<option <?php echo ($this->tw_get_option('tw_hook_prio')=='501')?'selected="selected"':'';?> value="501">501</option>
							<option <?php echo ($this->tw_get_option('tw_hook_prio')=='1001')?'selected="selected"':'';?> value="1001">1001</option>
							</select>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									var ddl = document.getElementById( 'tw_hook_prio' );
									var theOption = new Option;
									var x;
									var i;
									for(i = 3; i < 74; i++) {
									var theOption = new Option;
										x = i + 1;
										if (x == ddl.title){theOption.selected=true;}
										theOption.text = x;
										theOption.value = x;
										ddl.options[i+1] = theOption;
									}
								});
							</script>
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_bwdata_attr') == '1') echo 'checked="checked"'; ?> name="tw_bwdata_attr" id="tw_bwdata_attr" />
							<label for="tw_bwdata_attr"><?php _e('Build Tweet Button with HTML5 data attributes.',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_urlencode') == '1') echo 'checked="checked"'; ?> name="tw_urlencode" id="tw_urlencode" />
							<label for="tw_urlencode"><?php _e('Check this box if you experience character encoding issues.',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_url_samecount') == '1') echo 'checked="checked"'; ?> name="tw_url_samecount" id="tw_url_samecount" />
							<label for="tw_url_samecount"><?php _e('Display the same tweet count across all shorteners.',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_use_rel_me') == '1') echo 'checked="checked"'; ?> name="tw_use_rel_me" id="tw_use_rel_me" />
							<label for="tw_use_rel_me"><?php _e('Add rel=&quot;me&quot; to &lt;head&gt;. (Overrides referral format to \'via {user}\')',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_add_rel_shortlink') == '1') echo 'checked="checked"'; ?> name="tw_add_rel_shortlink" id="tw_add_rel_shortlink" />
							<label for="tw_add_rel_shortlink"><?php _e('Add rel=&quot;shortlink&quot; to &lt;head&gt;',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_force_manual') == '1') echo 'checked="checked"'; ?> name="tw_force_manual" id="tw_force_manual" />
							<label for="tw_force_manual"><?php _e('Force manual placement.',$this->txtdom); ?></label><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_script_infooter') == '1') echo 'checked="checked"'; ?> name="tw_script_infooter" id="tw_script_infooter" />
							<label for="tw_script_infooter"><?php _e('Place script in footer.',$this->txtdom); ?></label><br />

							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_prev_content_dup') == '1') echo 'checked="checked"'; ?> name="tw_prev_content_dup" id="tw_prev_content_dup" />
							<label for="tw_prev_content_dup"><?php _e('Prevent button duplication in content.',$this->txtdom); ?></label><br />

							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_nostyle_feed') == '1') echo 'checked="checked"'; ?> name="tw_nostyle_feed" id="tw_nostyle_feed" />
							<label for="tw_nostyle_feed"><?php _e('No styles in feeds.',$this->txtdom); ?></label><br />
							<?php
									if (function_exists('w3tc_pgcache_flush')) {
										?>
												<input <?php if ($this->tw_get_option('tw_flush_cache') == '1') echo 'checked="checked"'; ?> type="checkbox" value="1" name="tw_flush_cache" id="tw_flush_cache" />
												<label for="tw_flush_cache"><?php _e('Clear the W3 Total Cache when I save.',$this->txtdom); ?></label>
												<br />
												<?php
										} else if (function_exists('wp_cache_clear_cache')) {
										?>
												<input type="checkbox" value="1" name="tw_flush_cache" id="tw_flush_cache" />
												<label <?php if ($this->tw_get_option('tw_flush_cache') == '1') echo 'checked="checked"'; ?> for="tw_flush_cache"><?php _e('Clear the WP Super Cache when I save.',$this->txtdom); ?></label>
												<br />
												<?php
										}
							?>
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_enable_ga_code') == '1') echo 'checked="checked"'; ?> name="tw_enable_ga_code" id="tw_enable_ga_code" />
							<label for="tw_enable_ga_code"><?php _e('Add Google Analytics campaign tracking code to links.',$this->txtdom); ?></label><br />
							<?php echo tw_checkbox_input($this, 'tw_ga_setallowanchor','Use "#" as my Analytics query delimiter.'); ?><br />
							<?php _e('You can use <code>%POSTID%</code> and <code>%POSTSLUG%</code> in this field but no spaces, no + and no domain names. Simply enter the URL parameters. Example:<br />
							<code>utm_source=twitter&utm_medium=twt&utm_campaign=%POSTSLUG%</code>',$this->txtdom); ?><br />
							<label for="tw_ga_code"><?php _e('Google Analytics tracking code:',$this->txtdom); ?></label><br />
							<input class="pad45 w99c29c" type="text" value="<?php echo htmlspecialchars($this->tw_get_option('tw_ga_code')); ?>" name="tw_ga_code" id="tw_ga_code" /><br />
							<input type="checkbox" value="1" <?php if ($this->tw_get_option('tw_debug') == '1') echo 'checked="checked"'; ?> name="tw_debug" id="tw_debug" />
							<label for="tw_debug"><?php _e('Debug mode (NOT INTENDED FOR LIVE ENVIRONMENTS).',$this->txtdom); ?></label><br />
						</td>
						<td style="border-top: 1px solid #fff;border-bottom: 1px solid #CCC;" class="twhdata twhhlp" valign="top" style="">
							<p><strong><?php _e('Filter hook priority',$this->txtdom); ?></strong></p>
							<p><?php _e('Hook priority allows you to change the order in which the button is placed in relation to other plugins. It should only be used if you\'re having trouble displaying the Tweet Button in a custom template. Use 7,8,9 or 10 to get your desired result. Changing this value may break the formatting of the button.',$this->txtdom); ?></p>
						</td>
					</tr>
					<tr class="bt1pcs">
						<td class="twhdata" colspan="3" style="background:#E3E3E3;border-top:1px solid #CCC;border-bottom:1px solid #CCC;">
							<p class="submit">
								<input style="width:160px" type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
							</p>				
						</td>
					</tr>
				</table>
				<p style="clear:both;"></p>
			</div>
		</form>
		<div style="float:right">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><p><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="8YYQTCLT37SEG"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online."><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></p></form>
		</div>
		<p style="margin-left:20px;">
			<?php echo sprintf(__("<a href='%1\$s' class='button'>Show settings for diagnostics</a>"), $this->settingsuri.'&amp;showdiag=1');?>
			<?php echo sprintf(__("<a href='%1\$s' target='_blank' class='button'>Report a bug</a>"), 'http://0xtc.com/plugins/wp-tweet-button');?>
		</p>
		<p style="margin-left:20px;margin-top:20px;">
			<a href="http://twitter.com/share"
				data-url="http://wordpress.org/extend/plugins/wp-tweet-button/"
				data-text="Loving the <?php echo $this->pluginlabel; ?> plugin for #WordPress"
				data-lang="<?php echo $this->tw_get_option('tw_lang');?>"
				data-via=""
				data-related="TCorp:The Author of the <?php echo $this->pluginlabel; ?> plugin."
				data-count="horizontal"
				data-counturl="http://wordpress.org/extend/plugins/wp-tweet-button/"
				rel="nofollow"
				class="twitter-share-button">
			</a>
		</p>
		<p style="clear:both;"></p>
<?php
		if (!defined('WP_CONTENT_DIR')) die;
		
		$activepost = $this->tw_get_activepost();
		$tw_do_not_send_auto_tweet	= get_post_meta($activepost->ID,'_tw_do_not_send_auto_tweet',true);
		$tw_exclude_tweet_button 	= get_post_meta($activepost->ID,'_exclude_tweet_button',true);
		$tw_twitter_related_user 	= get_post_meta($activepost->ID,'_twitterrelated_user',true);
		$tw_twitter_related_desc 	= get_post_meta($activepost->ID,'_twitterrelated_desc',true);
		$tw_post_custom_text 		= get_post_meta($activepost->ID,'_twitterrelated_custom_text',true);
		
		?><script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#wpTweetButtonPostOp .head').click(function() {
				jQuery(this).next().slideToggle("slow");
				return false;
			}).next().hide();
		});		
		</script><div id="wpTweetButtonPostOp">
		<p class="head" style="margin:0 0 10px 5px"><a href="#"><?php _e('Tweet Button options',$this->txtdom);?></a></p>
		<div>
			<p style="margin:0;"><?php _e('If this post is related to a Twitter user, the Tweet Button can be enhanced by adding the Twitter account as a recommended user.',$this->txtdom);?></p>
			<div class="misc-pub-section">
				<label for="tw_twitter_related_user" style="width:80px;float:left;padding:5px 0"><?php _e('Username:',$this->txtdom);?></label>
				<input class="pad45" style="width:165px;text-align:left;" type="text" value="<?php echo $tw_twitter_related_user; ?>" name="tw_twitter_related_user" id="tw_twitter_related_user"/> <br />
				<label for="tw_twitter_related_desc" style="width:80px;float:left;padding:5px 0"><?php _e('Description:',$this->txtdom);?></label>
				<input class="pad45" style="width:165px;text-align:left;" type="text" value="<?php echo $tw_twitter_related_desc; ?>" name="tw_twitter_related_desc" id="tw_twitter_related_desc"/> 
			</div>
			<div class="misc-pub-section">
				<p style="margin:0;"><?php _e('Enter an optional custom tweet for this post.',$this->txtdom);?></p>
				<label for="tw_post_custom_text" style="width:80px;float:left;padding:5px 0"><?php _e('Tweet text:',$this->txtdom);?></label>
				<textarea class="pad45" style="width:165px;font-size: 11px;" name="tw_post_custom_text" id="tw_post_custom_text"/><?php echo $tw_post_custom_text; ?></textarea> 
			</div>
			<div class="misc-pub-section">
					<img src="<?php $wpt_pluginpath = (empty($_SERVER['HTTPS'])) ? WP_PLUGIN_URL : str_replace("http://", "https://", WP_PLUGIN_URL); echo $wpt_pluginpath; ?>/wp-tweet-button/tweetn.png" id="tw_tweet_button_image" style="float:right;margin:-3px 10px 0 10px"/>
					<input style="min-width:20px;" onclick="var twimgp=document.getElementById('tw_tweet_button_image');twimgp.style.display=(this.checked)?'none':'block';" type="checkbox" value="1" <?php if ($tw_exclude_tweet_button == '1') echo 'checked="checked"'; ?> name="tw_exclude_tweet_button" id="tw_exclude_tweet_button"/>
					<label for="tw_exclude_tweet_button"><?php _e('Disable Tweet Button',$this->txtdom);?></label><br />
					<input style="min-width:20px;" type="checkbox" value="1" name="tw_clear_short_cache_post" id="tw_clear_short_cache_post"/>
					<label for="tw_clear_short_cache_post"><?php _e('Clear shortlink cache',$this->txtdom);?></label><br />
					<?php
					if ($this->tw_get_option('tw_auto_tweet_token') != '' && $this->tw_get_option('tw_auto_tweet_token_secret') != ''){
						if (strstr(get_post_meta($activepost->ID, '_tw_autotweeted',true),':') != false){?>
							<input style="min-width:20px;" type="checkbox" value="1" name="tw_send_auto_tweet_again" id="tw_send_auto_tweet_again"/>
							<label for="tw_send_auto_tweet_again"><?php _e('Auto-tweet again',$this->txtdom);?></label><br />
							<?php
							$tdata = get_post_meta($activepost->ID, '_tw_autotweeted',true);
							$tdata = explode(':',$tdata );
							$tid = $tdata[0];
							$tname = $tdata[1];
							$turl = 'http://twitter.com/'.$tdata[1].'/status/'.$tdata[0];
							echo '<p><a href="'.$turl.'" target="_blank">'.__('View corresponding tweet',$this->txtdom).'</a></p>';
						} else { ?>
							<input <?php if ($tw_do_not_send_auto_tweet == '1') echo 'checked="checked"'; ?> style="min-width:20px;" type="checkbox" value="1" name="tw_do_not_send_auto_tweet" id="tw_do_not_send_auto_tweet"/>
							<label for="tw_do_not_send_auto_tweet"><?php _e('Do not Auto-Tweet',$this->txtdom);?></label><br />					
						<?php } 
					}
					?>
			</div>
		</div></div><p style="clear:both;"></p>
<?php
add_thickbox();

if (array_key_exists('socialwidget_global_notification', $_GET) && $_GET['socialwidget_global_notification'] == 0) {
        update_option('socialwidget_global_notification', 0);
}
?>
<div class="wrap">
        <?php settings_errors(); ?>
        <style type="text/css">
                #socialwidget_global_notification a.button:active {vertical-align:baseline;}
        </style>
        <div id="socialwidget_global_notification" style="border:3px solid #31964D;position:relative;background:#6AB07B;color:#ffffff;height:70px;margin:5px 0 15px;padding:1px 12px;">
                <p style="font-size:16px;line-height:40px;">
                        <?php _e('Tools to grow your Email List, Social Sharing and Analytics.'); ?> &nbsp;<a style="background-color: #6267BE;border-color: #3C3F76;" href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=sumome&TB_iframe=true&width=743&height=500'); ?>" class="thickbox button button-primary">Get SumoMe WordPress Plugin</a>
                </p>
        </div>
	<?php screen_icon(); ?>
	<h1>Social Media Widget</h1>
        <p>To add or edit a social media widget please <a href="<?php echo admin_url('widgets.php'); ?>">visit the widgets page</a>.</p>
        <h3>Other Plugins</h3>
        
        <p style="border:1px solid #CCCCCC;background:#FFFFFF;padding:8px;">
                <a href="<?php echo admin_url( 'plugin-install.php?tab=plugin-information&plugin=sumome&TB_iframe=true&width=743&height=500'); ?>" class="thickbox">SumoMe</a> - Tools to grow your Email List, Social Sharing and Analytics
                <br /><br />
                <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=google-analyticator&TB_iframe=true&width=743&height=500'); ?>" class="thickbox">Google Analyticator</a> - Easily view your Google Analytics and real-time statistics
                <br /><br />
                <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=social-share-boost&TB_iframe=true&width=743&height=500'); ?>" class="thickbox">Social Share Boost</a> - Boost Your Social Sharing by automatically adding various social share tools above or below the posts, page and excerpts.
        </p>

        <h3>Signup for a free 30 day course to DOUBLE YOUR EMAIL LIST</h3>
        <form method="post" class="af-form-wrapper" action="http://www.aweber.com/scripts/addlead.pl" target="_blank">
                <p>
                        <input placeholder="Type Your Email Address" class="email" name="email" autofocus style="width:200px;" />
                </p>
                <p>
                        <button class="button button-primary">Let me in!</button>
                </p>

                <input type="hidden" name="meta_web_form_id" value="1747290999" />
                <input type="hidden" name="meta_split_id" value="" />
                <input type="hidden" name="listname" value="awlist3626406" />
                <input type="hidden" name="redirect" value="http://email1k.sumome.com/tweet.html" id="redirect_19605a373ab8e7f77fc954424326ab1c" />
                <input type="hidden" name="meta_redirect_onlist" value="http://email1k.sumome.com/tweet.html" />
                <input type="hidden" name="meta_adtracking" value="social-media-widget" />
                <input type="hidden" name="meta_message" value="1" />
                <input type="hidden" name="meta_required" value="email" />
                <input type="hidden" name="meta_tooltip" value="" />
        </form>

        <!--<p style="border:1px solid #CCCCCC;background:#FFFFFF;padding:8px;">Check out more sharing tools with our <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=sumome&TB_iframe=true&width=743&height=500'); ?>" class="thickbox">SumoMe WordPress plugin</a></p>-->
        <p><i>If you find this plugin useful please <a href="https://wordpress.org/support/view/plugin-reviews/social-media-widget" target="_blank">leave us a review!</a></i></p>
</div>
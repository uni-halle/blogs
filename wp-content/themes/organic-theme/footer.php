<?php
/**
 * @package WordPress
 * @subpackage Organic_Theme
 */
?>

<?php get_sidebar(); ?>

<div class="clr"></div>
</div>

<div id="footer">
<div id="connect">
<h3>Connect</h3>
<ul>
<?php
// Build Organic Social Sites
$count = 0;
if (get_option('organic_facebook') != ""){ $count++; ?><li class="facebook-link"><a href="<?php echo get_option('organic_facebook'); ?>" target='_blank'>Facebook</a></li><?php }
if (get_option('organic_twitter') != ""){ $count++; ?><li class="twitter-link"><a href="<?php echo get_option('organic_twitter'); ?>" target='_blank'>Twitter</a></li><?php }
if (get_option('organic_friendfeed') != ""){ $count++; ?><li class="friend-link"><a href="<?php echo get_option('organic_friendfeed'); ?>" target='_blank'>FriendFeed</a></li><?php }
if (get_option('organic_flickr') != ""){ $count++; ?><li class="flickr-link"><a href="<?php echo get_option('organic_flickr'); ?>" target='_blank'>Flickr</a></li><?php }
if (get_option('organic_linkedin') != ""){ $count++; ?><li class="linkedin-link"><a href="<?php echo get_option('organic_linkedin'); ?>" target='_blank'>Linked In</a></li><?php }
if ((get_option('organic_lastfm') != "") AND ($count != 5)){ $count++; ?><li class="lastfm-link"><a href="<?php echo get_option('organic_lastfm'); ?>" target='_blank'>Last.fm</a></li><?php }
if ((get_option('organic_youtube') != "") AND ($count != 5)){ ?><li class="youtube-link"><a href="<?php echo get_option('organic_youtube'); ?>" target='_blank'>Youtube</a></li><?php }
?>
</ul>
</div>
<div id="foot-cloud">
<h3>Popular Tags</h3>
	<?php wp_tag_cloud( 'smallest=11&largest=20&unit=px&number=30&orderby=name&order=RAND' ); ?>
</div>
<div id="foot-menu">
<ul id="foot-nav">
<li><a href="<?php if (get_option('organic_feedburner') == "#") { bloginfo('rss2_url'); } else { echo get_option('organic_feedburner' );} ?>" rel="nofollow" target="_blank">Entries RSS</a></li>
<li><a href="<?php bloginfo('comments_rss2_url'); ?>" rel="nofollow" target="_blank">Comments RSS</a></li>
<?php if (get_option('organic_theme_admin') === "true") { // Organic hide admin ?>
<li><a href="<?php bloginfo("url"); ?>/wp-admin/" rel="nofollow">Admin Login</a></li>
<?php } ?>
<li><a href="http://validator.w3.org/check?uri=<?php bloginfo("url"); ?>" rel="nofollow" target="_blank">Valid XHTML</a></li>
<li><a href="http://www.wordpress.com" rel="nofollow" target="_blank">WordPress</a></li>
</ul>
</div>
<div class="clr"></div>
	
	<div class="footerlinks">
		<div class="allrights">&copy; <?php bloginfo('name');?> - All rights reserved</div>
		<div class="credit">
Organic Theme designed by<br />
<a href="http://www.theforge.co.za" title="Cape Town Web Designers">The Forge Web Creations</a></div>

	</div>

</div>
</div>

<?php wp_footer(); ?>
<?php echo get_option("organic_analytics"); ?>
</body>
</html>

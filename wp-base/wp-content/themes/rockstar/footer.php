		
		<div class="fix"></div>
		
	</div><!--wrapper-->

<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

<?php $twitter = get_option("widget_Twidget"); ?>
<?php if ( $twitter['account'] ) { ?>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $twitter['account']; ?>.json?callback=twitterCallback2&amp;count=<?php echo $twitter['show']; ?>"></script>
<?php } ?>

</body>
</html>
<div class="updated instagram_misc_message" style="padding: 0; margin: 0; border: none; background: none;">	
	<div class="instagram_activate instagram_review">
		<div class="instagram_bg">
			<div class="instagram_button instagram_review_button" onclick="displayInstagramReviewInstructions();">
				Review Instagram Widget
			</div>
			<div class="instagram_description">
				If you've got a spare 5 minutes, could you review our plugin please?						
			</div>
			<span class="instagram_hide_review">
				<a href="javascript:hideDisplayInstagramReview();">Never show this message again</a>						
			</span>	
		</div>
	</div>
</div>

<script>
	function hideDisplayInstagramReview() {
		var href = location.href;
		if (href.indexOf('?') == -1) {
			href += '?';
		}
		
		href += '&instagram_widget_disable_message=1';
		
		location.href = href;
	}
	
	function displayInstagramReviewInstructions() {
		window.open('https://wordpress.org/support/view/plugin-reviews/instagram-for-wordpress');
		
		hideDisplayInstagramReview();
	}	
</script>
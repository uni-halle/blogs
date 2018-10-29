<style type="text/css">
	.os-feedback-modal-wrapper {
		position: fixed;
	    top: 0;
	    width: 100vw;
	    height: 100vh;
	    z-index: 9999;
	    background: rgba(0,0,0,0.5);
	}
	.os-modal-inner {
	    background: #fff;
	    width: 500px;
	    margin: auto;
	    margin-top: calc( (100vh - 393px) / 2 );
	    padding: 20px;
	    position: relative;
	}
	.os-modal-content {
		margin-top: 25px;
		margin-bottom: 45px;
	}
	h3.os-modal-heading {
	    margin: 0px;
	    text-transform: uppercase;
	    font-weight: 700;
	    letter-spacing: 1px;
	}
	.os-form-field input[type=radio]{
		margin-right: 10px;
	    margin-top: 1px;
	}
	span.os-close-button {
	    position: absolute;
	    top: -10px;
	    right: -10px;
	    background: #f1f1f1;
	    border-radius: 20px;
	    cursor: pointer;
	}
	.os-skip-deactivate {
		float: right;
	}
	.os-form-field {
    	margin-bottom: 10px;
	}
	.os-other-reason, .os-other-plugin{
		display: block;
	    margin-left: 30px;
	    margin-top: 5px;
	    width: 75%;
	    line-height: 22px;
	}
	.os-modal-inner span.alert-error {
	    color: #f00;
	    font-weight: 600;
	}
</style>
<script type="text/javascript">
	// OS Modal JS here
	jQuery(document).ready(function($){
		
		// elements
		var elemModal 	= $('.os-feedback-modal-wrapper');
		var elemOpen 	= $('.plugins [data-slug="social-polls-by-opinionstage"] .deactivate');
		var elemClose 	= $('.os-close-button');
		var elemSkip 	= $('.os-skip-deactivate');
		var elemSend 	= $('.os-send-deactivate');
		var elemValue	= $('.os-feedback-modal-wrapper input[type=radio]');
		
		// handlers
		$(elemOpen).click(function(){
			elemModal.fadeIn();
			return false;
		});
		
		$(elemClose).click(function(){
			elemModal.fadeOut();
		});
		
		$(elemSend).click(function(){

			if( jQuery('input[name=reason]:checked', $(elemModal)).length > 0 ){
				elemModal.fadeOut();

				var reason = jQuery('input[name=reason]:checked', $(elemModal)).val();
				if(reason == 'I found a better plugin.'){
					reason = 'Found better plugin: ' + $('.os-other-plugin').val();
				}else if(reason == 'Other:'){
					reason = 'Other: ' + $('.os-other-reason').val();
				}

				$.ajax({
			    	url: '<?php echo OPINIONSTAGE_DEACTIVATE_FEEDBACK_API ?>',
			    	headers: {
				        'Accept':'application/vnd.api+json',
				        'Content-Type':'application/vnd.api+json',
				        'OSWP-Plugin-Version':'<?php echo OPINIONSTAGE_WIDGET_VERSION ?>',
				        'OSWP-Client-Token': '<?php echo opinionstage_user_access_token() ?>'
			    	},
			    	method: 'POST',
			    	dataType: 'json',
			    	data: JSON.stringify({ data: {type: 'disconnect', attributes: { reason: reason }} }),
			    	success: function(data){
			      		window.location = elemOpen.find('a').attr('href');
			    	},
			    	error: function(){
			      		window.location = elemOpen.find('a').attr('href');
			    	}
			  	});
			}else{
				// show error.
				$('span.alert-error').html('Please select one of the options.');
			}

		});

		$(elemSkip).click(function(){
			elemModal.fadeOut();
			window.location = elemOpen.find('a').attr('href');
		});

		$(elemValue).click(function(){
			$('span.alert-error').html('');
			$('input[type=text]', $(elemModal)).hide();
			$(this).parent().find('input[type=text]').show();
		});

	});
</script>

<div class="os-feedback-modal-wrapper" style="display: none;">
	<div class="os-modal-inner">
		<h3 class="os-modal-heading">Quick Feedback</h3>
		<div class="os-modal-content">
			<span class="alert-error"></span>

			<p><strong>If you have a moment, please share why you're deactivating?</strong></p>
			<div class="os-form-field">
				<input type="radio" name="reason" value="It is a temporary deactivation." id="label1">
				<label for="label1">It is a temporary deactivation.</label>
			</div>
			<div class="os-form-field">
				<input type="radio" name="reason" value="I couldn't get the plugin to work." id="label2">
				<label for="label2">I couldn't get the plugin to work.</label>
			</div>
			<div class="os-form-field">
				<input type="radio" name="reason" value="The plugin broke my website layout." id="label3">
				<label for="label3">The plugin broke my website layout.</label>
			</div>
			<div class="os-form-field">
				<input type="radio" name="reason" value="I found a better plugin." id="label4">
				<label for="label4">I found a better plugin.</label>
				<input type="text" name="other_plugin" class="os-other-plugin" placeholder="Please share the plugin name" style="display: none;">
			</div>
			<div class="os-form-field">
				<input type="radio" name="reason" value="I no longer need this plugin." id="label5">
				<label for="label5">I no longer need this plugin.</label>
			</div>
			<div class="os-form-field">
				<input type="radio" name="reason" value="Other:" id="label6">
				<label for="label6">Other:</label>
				<input type="text" name="other_reason" class="os-other-reason" placeholder="Please share your reason here" style="display: none;">
			</div>
		</div>
		<span class="os-close-button"><span class="dashicons dashicons-dismiss"></span></span>
		<button class="os-send-deactivate button button-primary">Send & Deactivate</button>
		<button class="os-skip-deactivate button">Skip & Deactivate</button>
	</div>
</div>
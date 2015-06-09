<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">	
	<div class="instagram_activate">
		<div class="instagram_bg">
			<div class="instagram_button" onclick="displayInstagramSQLInstructions();">
				MySQL Setup Instructions
			</div>
			<div class="instagram_description instagram_description_error">
				There was an error activating the <b>Instagram for Wordpress</b> plugin, you need to manually setup the MySQL tables required.
			</div>
		</div>
	</div>
</div>

<div id="instagram_sql_instructions" style="display: none;">
	<h2>MySQL Setup</h2>
	
	<div class="instagram_setup_instructions">		
		<p>
			Unfortunately the plugin was not able to automatically create the tables required for the plugin to work.
		</p>
		
		<p>
			<b>You will need to create these tables yourself with the following code:</b>	
		</p>
		
		<p class="code"><?php echo str_replace("\n", '<br>', $this->_getCreateTableSQL()); ?></p>
			
		<p>
			Alternatively, you can <a href="https://dev.mysql.com/doc/refman/5.1/en/grant.html" target="_blank">add the Alter and Create MySQL privileges</a> onto your MySQL user to let the plugin setup these tables automatically.
		</p>
	</div>
</div>

<script>
	function displayInstagramSQLInstructions() {
		lightbox({
        content : jQuery('#instagram_sql_instructions').html(),
        frameCls : '',
        closeCallback: function() {
          
        }      
      });
	}	
</script>

<style>
	/* CSS to hide any other messages */	
	.instagram_misc_message {
		display: none;
	}
</style>
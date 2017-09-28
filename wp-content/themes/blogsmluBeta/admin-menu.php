<?php 
	global $user_identity, $user_ID, $user_email, $user_login, $current_user;
	get_currentuserinfo();
	
	if ( !is_user_logged_in() ) { ?>
	
	<div id="front-admin">
				
		<form action="<?php echo site_url('/wp-login.php', 'login_post') ?>" method="post">
		
		    <p><label for="log"><?php _e('User ID','blogsmlu'); ?></label><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" /> </p>
		
		    <p><label for="pwd"><?php _e('Password','blogsmlu'); ?></label><input type="password" name="pwd" id="pwd" size="20" /></p>
			<p>
				<input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" />
		       	<label for="rememberme"><?php _e('Remember me','blogsmlu'); ?></label>
		       	<input type="submit" name="submit" class="submit" value="<?php _e('Login','blogsmlu'); ?>" />
		    </p>
		
		    <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
			<p><a href="<?php echo site_url('/wp-login.php?action=lostpassword', 'https'); ?>"><?php _e('Recover Password','blogsmlu'); ?></a></p>
		</form>
		
		<a id="login-link" class="loggedout" href="<?php echo site_url('/wp-login.php', 'https'); ?>">v <?php _e('Login','blogsmlu'); ?></a>

	</div>
	
<?php } ?>
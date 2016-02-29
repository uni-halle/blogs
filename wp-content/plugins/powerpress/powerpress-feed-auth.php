<?php
	// powerpress-feed-auth.php
	
	function powerpress_feed_auth($feed_slug)
	{
		// See if a filter exists to perform the authentication...
		$authenticated = apply_filters('powerpress_feed_auth', false, 'channel', $feed_slug);
		if( !$authenticated )
		{
			$FeedSettings = get_option('powerpress_feed_'.$feed_slug);
			
			/*
			 * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
			 * For this workaround to work, add this line to your .htaccess file:
			 * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
			 */
			// Workaround for HTTP Authentication with PHP running as CGI
			if ( !isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
				$ha = base64_decode( substr($_SERVER['HTTP_AUTHORIZATION'],6) ); // Chop off 'basic ' from the beginning of the value
				if( strstr($ha, ':') ) { // Colon found, lets split it for user:password
					list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $ha);
				}
				unset($ha);
			}
			
			if( !isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) )
				powerpress_feed_auth_basic( $FeedSettings['title'] );
				
			$user = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			
			$userObj = wp_authenticate($user, $password);
			
			if( !is_wp_error($userObj) )
			{
				// Check capability...
				if( $userObj->has_cap( $FeedSettings['premium'] ) )
					return; // Nice, let us continue...
				
				powerpress_feed_auth_basic( $FeedSettings['title'], __('Access Denied', 'powerpress') );
			}
			
			// user authenticated here
			powerpress_feed_auth_basic( $FeedSettings['title'], __('Authorization Failed', 'powerpress') );
		}
	}
	
	function powerpress_feed_auth_basic($realm_name, $error = false )
	{
		if( !$error )
			$error = __('Unauthorized', 'powerpress');
		header('HTTP/1.0 401 Unauthorized');
		header('Content-Type: text/html');
		header('WWW-Authenticate: Basic realm="'. str_replace('"', '', $realm_name).'"');
		
		echo '<!DOCTYPE html>'; // HTML5!
		echo "\n";
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="robots" content="noindex" />
	<title><?php echo htmlspecialchars($error); ?></title>
</head>
<body>
	<p><?php echo htmlspecialchars($error); ?></p>
</body>
</html>
<?php
		exit;
	}
	
	


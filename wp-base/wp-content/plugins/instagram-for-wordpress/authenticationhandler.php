<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

if(!defined('INSTAGRAM_PLUGIN_URL')) {
  define('INSTAGRAM_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
}

if (isset($_GET['code'])) {

	$client_id = get_option('instagram-widget-client_id');
	$client_secret = get_option('instagram-widget-client_secret');
 
	$response = wp_remote_post("https://api.instagram.com/oauth/access_token",
		array(
			'body' => array(
				'code' => $_GET['code'],
				'response_type' => 'authorization_code',
				'redirect_uri' => INSTAGRAM_PLUGIN_URL . '/authenticationhandler.php',
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'grant_type' => 'authorization_code',
			),
			'sslverify' => apply_filters('https_local_ssl_verify', false)
		)
	);

	$access_token = null;
	$username = null;
	$image = null;

	$success = false;
	$errormessage = null;
	$errortype = null;

	if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200):
		$auth = json_decode($response['body']);
		if(isset($auth->access_token)):
			$access_token = $auth->access_token;
			$user = $auth->user;
			
			update_option('instagram-widget-access_token', $access_token);
			update_option('instagram-widget-username', $user->username);
			update_option('instagram-widget-picture', $user->profile_picture);
			update_option('instagram-widget-fullname', $user->full_name);

			$success = true;
		endif;
        elseif(is_wp_error($response)):
                $error = $response->get_error_message();
                $errormessage = $error;
                $errortype = 'Wordpress Error';
	elseif($response['response']['code'] >= 400):
		$error = json_decode($response['body']);
		$errormessage = $error->error_message;
		$errortype = $error->error_type;
	endif;  

	if (!$access_token):
		delete_option('instagram-widget-access_token');
	endif;
}

?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body, html {
			font-family: arial, sans-serif;
			padding: 30px;

			text-align: center;
		}
	</style>
</head>
<body>
<?php if ($success): ?>
	<script type="text/javascript">
		opener.location.reload(true);
   		self.close();
	</script>
<?php else: ?>
	<h1>An error occured</h1>
	<p>
		Type: <?php echo $errortype; ?>
		<br>
		Message: <?php echo $errormessage; ?>
	</p>
	<p>Please make sure you entered the right client details</p>
<?php endif; ?>
</body>
</html>

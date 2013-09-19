<?php
	$message = _x('Congratulations. AWPCP has been successfully upgraded. You are seeing this message because your database needed to be upgraded in order to support the newest version. You can now access all features. <a href="%s">Click here to Continue</a>.', 'awpcp upgrade', 'AWPCP');
	echo sprintf($message, $url);
?>

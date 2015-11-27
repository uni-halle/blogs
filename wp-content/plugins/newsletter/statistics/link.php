<?php
global $wpdb;

if (!defined('ABSPATH')) {
    include '../../../../wp-load.php';
}
list($email_id, $user_id, $url, $anchor, $key) = explode(';', base64_decode($_GET['r']), 5);

if (!is_user_logged_in()) {
    if (empty($email_id) || empty($user_id) || empty($url)) {
        header("HTTP/1.0 404 Not Found");
        die();
    }
}

$parts = parse_url($url);
//die($url);
$verified = $parts['host'] == $_SERVER['HTTP_HOST'];
if (!$verified) {
    $options = NewsletterStatistics::instance()->options;
    $verified = $key == md5($email_id . ';' . $user_id . ';' . $url . ';' . $anchor . $options['key']);
}

// For feed by mail tests
if ($verified && empty($email_id) && is_user_logged_in()) {
    header('Location: ' . $url);
    die();
}

if ($verified) {
    $wpdb->insert(NEWSLETTER_STATS_TABLE, array(
        'email_id' => $email_id,
        'user_id' => $user_id,
        'url' => $url,
        //'anchor' => $anchor,
        'ip' => $_SERVER['REMOTE_ADDR']
            )
    );
    $user = Newsletter::instance()->get_user($user_id);
    if ($user) {
        setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
    }
    header('Location: ' . $url);
    die();
} else {
    header("HTTP/1.0 404 Not Found");
    //header('Location: ' . home_url());
    //die();
}
?><html>
    <head>
        <style>
            body {
                font-family: sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="max-width: 100%; width: 500px; margin: 40px auto; text-align: center">
            <p>The requested URL (<?php echo esc_html($url) ?>) has not been verified.</p>
            <p>You can follow it if you recognize it as a valid URL.</p>
        </div>
    </body>
</html>
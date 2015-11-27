<?php
global $wpdb;

if (!defined('ABSPATH')) {
    require_once '../../../../wp-load.php';
}
list($email_id, $user_id) = explode(';', base64_decode($_GET['r']), 2);

$wpdb->insert(NEWSLETTER_STATS_TABLE, array(
    'email_id' => $email_id,
    'user_id' => $user_id,
    'ip' => $_SERVER['REMOTE_ADDR']
        )
);

header('Content-Type: image/gif');
echo base64_decode('_R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
die();


<?php

add_action('admin_notices', 'contact_form_7_modules_promo_message');

function contact_form_7_modules_promo_message() {
	global $pagenow;

	if(!current_user_can('install_plugins')) { return; }

	$message = (int)get_option('cf7_modules_hide_promo_message');

	if(isset($_REQUEST['hide']) && $_REQUEST['hide'] == 'cf7_modules_promo_message') {
		$message = 3;
		update_option('cf7_modules_hide_promo_message', $message);
	}

	if($pagenow == 'admin.php' && isset($_REQUEST['page']) && $_REQUEST['page'] == 'wpcf7' && ($message !== 3 || isset($_REQUEST['show']) && $_REQUEST['show'] == 'cf7_modules_promo_message')) {
		$message = contact_form_7_modules_get_remote_message();

		$message = str_replace('{hidelink}', add_query_arg('hide', 'cf7_modules_promo_message'), $message);
		$message = str_replace('{showlink}', add_query_arg('show', 'cf7_modules_promo_message'), $message);
		$message = str_replace('{adminurl}', admin_url(), $message);
		$message = str_replace('{siteurl}', site_url(), $message);

		echo $message;
	}
}

function contact_form_7_modules_get_remote_message() {

    // The ad is stored locally for 30 days as a transient. See if it exists.
    $cache = function_exists('get_site_transient') ? get_site_transient('contact_form_7_modules_remote_ad') : get_transient('contact_form_7_modules_remote_ad');

    // If it exists, use that (so we save some request time), unless ?cache is set.
    if(!empty($cache) && !isset($_REQUEST['cache'])) { return $cache; }

    // Get the advertisement remotely. An encrypted site identifier, the language of the site, and the version of the cf7 plugin will be sent to katz.co
    $response = wp_remote_post('http://katz.co/ads/', array('timeout' => 45,'body' => array('siteid' => sha1(site_url()), 'language' => get_bloginfo('language'), 'plugin' => 'cf7_modules', 'version' => WPCF7_VERSION)));

    // If it was a successful request, process it.
    if(!is_wp_error($response) && !empty($response)) {

        // Basically, remove <script>, <iframe> and <object> tags for security reasons
        $body = strip_tags(trim(rtrim($response['body'])), '<b><strong><em><i><span><u><ul><li><ol><div><attr><cite><a><style><blockquote><q><p><form><br><meta><option><textarea><input><select><pre><code><s><del><small><table><tbody><tr><th><td><tfoot><thead><u><dl><dd><dt><col><colgroup><fieldset><address><button><aside><article><legend><label><source><kbd><tbody><hr><noscript><link><h1><h2><h3><h4><h5><h6><img>');

        // If the result is empty, cache it for 8 hours. Otherwise, cache it for 30 days.
        $cache_time = empty($response['body']) ? floatval(60*60*8) : floatval(60*60*30);

        if(function_exists('set_site_transient')) {
            set_site_transient('contact_form_7_modules_remote_ad', $body, $cache_time);
        } else {
            set_transient('contact_form_7_modules_remote_ad', $body, $cache_time);
        }

        // Print the results.
        return $body;
    }
}

add_filter( 'wpcf7_cf7com_links', 'contact_form_7_modules_links' );

function contact_form_7_modules_links($links) {
	return str_replace('</div>', ' - <a href="http://katz.si/gf?con=link" target="_blank" title="Gravity Forms is the best WordPress contact form plugin." style="font-size:1.25em;">Try Gravity Forms</a></div>', $links);
}
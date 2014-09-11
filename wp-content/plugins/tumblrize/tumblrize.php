<?php
/*
Plugin Name: Tumblrize
Plugin URI: http://tumblrize.ijulien.com/
Description: Automatically crossposts to your Tumblr blog when you publish a post on your WordPress blog.
Version: 1.4.2
Author: <a href="http://ijulien.com/">Julien Ott</a> and <a href="http://maymay.net/">Meitar Moscovitz</a>
Author URI: http://ijulien.com
*/

/*  Copyright 2009 Julien Ott and Meitar Moscovitz

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// To configure Tumblrize, go to the plugin options of your admin panel.

// Uncomment for assistance from WordPress in debugging.
define('WP_DEBUG', true);

// DON'T use Tumblrize for SPAM.

if (!defined('TUMBLRIZE_PLUGIN_VERISON')) {
    define('TUMBLRIZE_PLUGIN_VERSION', '1.4.2');
} else { die('A constant named TUMBLRIZE_PLUGIN_VERSION has already been defined.'); }

// Load helper functions.
require_once(dirname(__FILE__) . '/helperlib.php');

/**
 * The main TumblrizePlugin class.
 */
class TumblrizePlugin {
    var $tusername;               /**< This blog's Tumblr username (email address). */
    var $tpassword;               /**< This blog's Tumblr password. */
    var $generator = 'Tumblrize'; /**< Program name to be used in API calls. */

    /**
     * Constructor.
     */
    function TumblrizePlugin () {
        $this->tusername = get_option('tumblrize_tumblr_email');
        $this->tpassword = get_option('tumblrize_tumblr_password');

        // We need session support for cross-page error messages.
        if (!session_id()) { session_start(); }

        if (!$this->tusername || !$this->tpassword) {
            $this->add_admin_message('error', 'Tumblrize is not configured. Please provide your Tumblr email and password in the <a href="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=tumblrize/tumblrize.php">Tumblrize Options</a> screen.');
        }

    }

    /**
     * Checks to ensure user wishes to Tumblrize this post.
     *
     * @param int $post_ID Numeric ID of the WordPress post to check. Defaults to NULL.
     * @return bool True if this post should be crossposted, false otherwise.
     */
    // TODO: Enable WordPress delete post operations to be overriden on per-post basis.
    //       Right now, when you delete a WordPress, the individual "Crosspost to Tumblr?"
    //       question is ignored in favor of the global options in the plugin because the
    //       deletion operation is a link whereas the publish operation submits a form.
    function isTumblrizeablePost ($post_ID = NULL) {
        if (!isset($_POST['tumblrize_do-tumblrize']) && $_GET['action'] === 'delete') {
            // this is deletion operation and should be tumblrizeable
            // only if Tumblrize is turned on globally
            return (get_option('tumblrize_shutoff') === '') ? true : false;
        } else if ($post_ID !== NULL && in_category(get_option('tumblrize_exclude_cats'), $post_ID)) {
            // Don't crosspost items placed into these specific categories.
            return false;
        } else if ($post_ID !== NULL && get_post_meta($post_ID, 'tumblrize_post-future', true)) {
            // This is a scheduled post that wants to be crossposted.
            return true;
        }
        return ($_POST['tumblrize_do-tumblrize']) ? true : false;
    }

    /**
     * Uses WordPress's post scheduling features to mimic the Tumblr queue.
     *
     * @param string $to_status The new status of the post. This is passed by WP automatically.
     * @param string $from_status The old status of the post. This is passed by WP automatically.
     * @param object $post_obj The WordPress Post object to work on. This is passed by WP automatically.
     */
    function queuePost ($to_status, $from_status, $post_obj) {
        if ('publish' === $to_status && 'future' === $from_status) {
            $this->tumblrize($post_obj->ID);
            delete_post_meta($post_obj->ID, 'tumblrize_post-future');
        }
    }

    /**
     * Main workhorse function.
     *
     * @param int $post_ID The numeric ID of the WordPress post to crosspost.
     * @return int $post_ID
     */
    function tumblrize ($post_ID) {

        // DATA Preparation
        $post = get_post($post_ID);

        // Do not crosspost if not requested for this post.
        if (!$this->isTumblrizeablePost($post_ID)) { return $post_ID; }

        $post_group = get_post_meta($post_ID, 'tumblrize_post-group', true);
        $post_type  = get_post_meta($post_ID, 'tumblrize_post-type', true);
        $post_date  = $post->post_date_gmt . ' GMT'; // use UTC; avoid TZ issues
        $post_title = html_entity_decode($post->post_title);
        $post_body  = wpautop(str_replace('\"', "", html_entity_decode($post->post_content)));

        if ( get_option('tumblrize_add_permalink') ) {
            $postlink = get_permalink($post_ID);
            $post_body .= "<p class=\"tumblrize-permalink\"><a href=\"$postlink\" title=\"Go to original post at " .
                          get_bloginfo('name') . '" rel="bookmark">Original Article</a></p>';
        }

        if ( get_option('tumblrize_tags') ) {
            $tags  = get_option('tumblrize_tags');
            $tags .= ", tumblrize";
        } else { $tags = "tumblrize"; }

        if ( get_option('tumblrize_add_post_tags') ) {
            query_posts("p=$post_ID");
            if (have_posts()) : while (have_posts()) : the_post();
                $more_tags = get_the_tags();
            endwhile; endif;
            if ($more_tags) {
                foreach ($more_tags as $another_tag) {
                    $tags .= ",{$another_tag->name}";
                }
            }
        }

        // Gather valid data for Tumblr post types.
        if ('photo' === $post_type) {
            $pattern = '/<img.*?src="(.*?)".*?\/?>/';
            $matches = array();
            preg_match($pattern, $post_body, $matches);
            $photo_source = $matches[1];
            $post_body = strip_only($post_body, '<img>');
        } else if ('link' === $post_type) {
            $pattern = '/<a.*?href="(.*?)".*?>/';
            $matches = array();
            preg_match($pattern, $post_body, $matches);
            $link_url = $matches[1];
        } else if ('audio' === $post_type) {
            $pattern = '/<a.*?href="(.*?\.[Mm][Pp]3)".*?>/';
            $matches = array();
            preg_match($pattern, $post_body, $matches);
            $audio_url = $matches[1];
        } else if ('quote' === $post_type) {
            // TODO: Buggy. Doesn't always pick up the contents of cite="" attribute.
            $pattern = '/<blockquote.*?(?:cite="(.*?)")?.*?>(.*?)<\/blockquote>/';
            $matches = array();
            preg_match($pattern, $post_body, $matches);
            $post_quote = strip_tags($matches[2]);
            $quote_source = ($matches[1]) ? "<a href=\"{$matches[1]}\" title=\"Visit quotation source.\">{$matches[1]}</a>" : '';
        } else if ('video' === $post_type) {
            // Currently fetches YouTube's video ID from embedded code and sends the URL to Tumblr
            $pattern = '/youtube\.com\/v\/([\w\-]+)/';
            $matches = array();
            preg_match($pattern, $post_body, $matches);
            $post_video = "http://www.youtube.com/watch?v=".$matches[1];
        }

        // Override default plugin credentials if user supplies their own.
        $cur_user          = wp_get_current_user();
        $cur_user_twpemail = get_usermeta($cur_user->ID, 'tumblrize_wpuser_email');
        $this->tusername   = ($cur_user_twpemail) ? $cur_user_twpemail : $this->tusername;
        $cur_user_twppass  = get_usermeta($cur_user->ID, 'tumblrize_wpuser_password');
        $this->tpassword   = ($cur_user_twppass) ? $cur_user_twppass : $this->tpassword;

        // SEND Data
        if (!$this->tusername || !$this->tpassword || !$post_body) {
            $this->add_admin_message('error', 'Entry not crossposted! Missing Tumblr post body, or Tumblrize is misconfigured.');
        } else {
            // Prepare DATA
            $request_data = array();
            $request_data['email']     = $this->tusername;
            $request_data['password']  = $this->tpassword;
            $request_data['post-id']   = get_post_meta($post_ID, 'tumblrize_post-id', true);
            if (empty($request_data['post-id'])) { unset($request_data['post-id']); } // no need
            $request_data['group']     = $post_group;
            if (empty($request_data['group'])) { unset($request_data['group']); }
            $request_data['type']      = $post_type;
            $request_data['date']      = $post_date;
            $request_data['tags']      = $tags;
            $request_data['generator'] = $this->generator;

            switch ($post_type) {
                case 'photo':
                    if (!isset($photo_source) || empty($photo_source)) {
                        $this->add_admin_message('error', 'You indicated a Photo post type, but Tumblrize could not find any images in your post.');
                        return $post_ID; // will forego posting a photo
                    }
                    $request_data['source']  = $photo_source;
                    $request_data['caption'] = $post_body;
                    if ($photo_link = get_post_meta($post_ID, 'tumblrize_photo-click-through', true)) {
                        $request_data['click-through-url'] = $photo_link;
                    }
                    break;
                case 'link':
                    if (!isset($link_url) || empty($link_url)) {
                        $this->add_admin_message('error', 'You indicated a Link post type, but Tumblrize could not find any links in your post.');
                        return $post_ID; // will forego posting a link
                    }
                    $request_data['name'] = $post_title;
                    $request_data['url'] = $link_url;
                    $request_data['description'] = $post_body;
                    break;
                case 'audio':
                    if (!isset($audio_url) || empty($audio_url)) {
                        $this->add_admin_message('error', 'You indicated an Audio post type, but Tumblrize could not find any MP3 links in your post.');
                        return $post_ID; // will forego posting audio
                    }
                    $request_data['externally-hosted-url'] = $audio_url;
                    $request_data['caption'] = $post_body;
                    break;
                case 'video':
                    if (!isset($post_video) || empty($post_video)) {
                        $this->add_admin_message('error', 'You indicated a Video post type, but Tumblrize could not find any videos embedded in your post.');
                        return $post_ID; // will forego posting a video
                    }
                    $request_data['embed'] = $post_video;
                    $request_data['caption'] = $post_title;
                    break;
                case 'quote':
                    if (!isset($post_quote) || empty($post_quote)) {
                        $this->add_admin_message('error', 'You indicated a Quote post type, but Tumblrize could not find any blockquotes embedded in your post.');
                        return $post_ID; // will forego posting a quote; silently fails
                    }
                    $request_data['quote'] = $post_quote;
                    $request_data['source'] = $quote_source;
                    break;
                case 'text':
                case 'regular':
                default:
                    if (empty($post_title) || empty($post_body)) {
                        return $post_ID; // just bail out now
                    }
                    $request_data['title'] = $post_title;
                    $request_data['body']  = $post_body;
                    break;
            }

            // What's notified?
            $tb_services = '';
            if(get_option('tumblrize_shutoff' ) == ""){ $tb_services = "Tumblr"; }
            if(get_option('tumblrize_tumblr_posterous') == "posterous"){ $tb_services .= " and Posterous"; }
            if(get_option('tumblrize_shutoff' ) && get_option('tumblrize_tumblr_posterous') == "posterous"){ $tb_services = "Posterous"; }

            // Talk to Tumblr
            $r = $this->tumblrize_to_tumblr('write', $request_data);
            if ($r && $r['status'] === 201) { // Success
                add_post_meta($post_ID, 'tumblrize_post-id', $r['result'], true);
            } else if ($r && $r['status'] === 403) { // Authentication failed.
                $this->add_admin_message('error',
                    'Tumblrize could not crosspost this entry because Tumblr has rejected your username or password.');
                $this->add_admin_message('updated',
                    'Are you sure you entered your Tumblr email and password correctly? Tumblrize is having trouble accessing your account.');
            } else if ($r && $r['status'] === 400) { // Tumblr errors.
                $this->add_admin_message('error',
                    'Tumblr experienced errors trying to save the data we sent it.');
                // TODO: Show these errors here in subsequent error messages.
            } else if ($r && $r['status'] === 0) { // cURL error; no connection
                $this->add_admin_message('error',
                    'Tumblrize could access the Internet. Make sure you have a network connection, that Tumblr is online, or try again later.');
            } else if ($r && ($r['status'] === 500 || $r['status'] === 503) ) { // Tumblr barfed?
                $this->add_admin_message('error',
                    "Tumblr returned an HTTP Status Code of {$r['status']}. Tumblr might be having problems. Try crossposting again.");
            } else {
                // Uncomment to help in debugging.
                // var_dump($r);
                // exit();
                $this->add_admin_message('error',
                    'Tumblrize could not crosspost this entry. Unfortunately, we also do not know why.');
            }

            // Do additional notifications.
            $this->tumblrize_notify($this->tusername, $post_title, $post_body, $tb_services);
            $this->tumblrize_posterous($this->tusername, $post_title, $post_body, $tags);
        }

        return $post_ID;
    }

    /**
     * Deletes a post from Tumblr when it is deleted from WordPress.
     *
     * @param int $post_ID The numeric ID of the WordPress post which has been crossposted.
     * @return mixed Same as tumblrize_to_tumblr()
     *
     * @see tumblrize_to_tumblr(string $do, array $data)
     */
    function delete_post ($post_ID) {
        if ($this->isTumblrizeablePost()) {
            return $this->tumblrize_to_tumblr('delete', array(
                                     'email' => $this->tusername,
                                     'password' => $this->tpassword,
                                     'post-id' => get_post_meta($post_ID, 'tumblrize_post-id', true),
                                     'group' => get_post_meta($post_ID, 'tumblrize_post-group', true),
                                     'generator' => $this->generator
                                ));
        }
    }

    /**
     * Sends an email notification indicating that a new entry has been crossposted.
     * 
     * @param string $notify_destination The email address to send the notification TO.
     * @param string $notify_title
     * @param string $notify_body
     * @param string $notify_dest Which services did we just crosspost to?
     * @return bool True if mail was sent by PHP's mail() function, false otherwise.
     */
    function tumblrize_notify ($notify_destination, $notify_title, $notify_body, $notify_dest) {
        if ( get_option('tumblrize_notify_me') !== "notify_on" ) { return false; } // do nothing if not enabled
        $recipient = $notify_destination;
        $subject = 'Tumblrize: '.$notify_title;
        $body = "This article was posted to ".$notify_dest.".<br /><br />".$notify_body."<br /><br />from <img src=\"http://id.ijulien.com/img/tumblrize-small.png\" alt=\"Tumblrize\" /> with &hearts;<br />";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: Tumblrize <tumblrize@ijulien.com>"."\n"."Return-Path: Tumblrize <tumblrize@ijulien.com>"."\n"."Reply-To: Tumblrize <tumblrize@ijulien.com>"."\n";
        return mail($recipient, $subject, $body, $headers);
    }

    /**
     * Sends a crosspost to Posterous.
     *
     * @see tumblrize_notify()
     */
    function tumblrize_posterous($posterous_sender, $posterous_title, $posterous_body, $posterous_tags)  {
        if ( get_option('tumblrize_tumblr_posterous') !== "posterous" ) { return false; } // do nothing if not enabled
        $recipient = 'posterous@posterous.com';
        $subject = $posterous_title.'((tag: '.$posterous_tags.'))';
        $body = $posterous_body;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: " . $posterous_sender . "\n" . "Return-Path: " . $posterous_sender . "\n" . "Reply-To: " . $posterous_sender . "\n";
        return mail($recipient, $subject, $body, $headers);
    }

    /**
     * Performs a single API method call to Tumblr's API endpoint.
     *
     * @param string $do The API endpoint to call.
     * @param array $data A list of key-value pairs to send.
     * @return mixed Array containing cURL response status and result if successful, false otherwise.
     */
    function tumblrize_to_tumblr ($do, $data) {

        // Verify API URI endpoints
        switch ($do) {
            case 'authenticate':
            case 'delete':
            case 'read':
            case 'write':
                continue;
            default:
                return false;
        }

        if (!$data || !is_array($data)) { return false; }

        $data = http_build_query($data);

        $c = curl_init("http://www.tumblr.com/api/$do");
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $r = array();
        $r['result'] = curl_exec($c);
        $r['status'] = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        return $r;
    }

    /**
     * Generic function to alert user of messages from Tumblrize actions.
     *
     * @param mixed $class The type of message. Use a WordPress message class name for appropriate formatting. Also supports multiple class names in string or array format.
     * @param string $msg The text of the message. Can contain HTML, so please be careful.
     * @return void
     */
    function add_admin_message ($class, $msg) {
        if (!is_array($_SESSION['tumblrize_messages'])) {
            $_SESSION['tumblrize_messages'] = array();
        }
        if (is_array($class)) { $class = implode(' ', $class); }
        $class = htmlentities($class, ENT_QUOTES, 'UTF-8'); // escape output
        $_SESSION['tumblrize_messages'][] = "<div class=\"$class\"><p>$msg</p></div>";
    }

    /**
     * Displays all stored admin messages. Also clears the stored messages array.
     */
    function show_admin_messages () {
        if (empty($_SESSION['tumblrize_messages']) || !is_array($_SESSION['tumblrize_messages'])) {
            return;
        }
        foreach ($_SESSION['tumblrize_messages'] as $msg) {
            print $msg;
        }
        // TODO: For some reason, updating the options screen
        //       takes an extra page refresh to clear this...? Why?
        $_SESSION['tumblrize_messages'] = array();
        return;
    }

} // END TumblrizePlugin();

/**
 * User-specific Tumblrize functions, preferences, etc.
 */
class TumblrizeWPUser extends TumblrizePlugin {

    /**
     * Constructor.
     */
    function TumblrizeWPUser () {
        // Empty.
    }

    /**
     * Displays additional options in the user profile.
     */
    function show_user_options ($user) {
?>
<h3>Tumblrize User Options</h3>
<table class="form-table" summary="Options to configure Tumblrize for your posts.">
    <tr>
        <th><label for="tumblrize_wpuser_email">Tumblr Email</label></th>
        <td>
            <input type="text" name="tumblrize_wpuser_email" id="tumblrize_wpuser_email" value="<?php print esc_attr(get_the_author_meta('tumblrize_wpuser_email', $user->ID)); ?>" class="regular-text" />
            <span class="description">Enter the email address you use to login to Tumblr.</span>
        </td>
    </tr>
    <tr>
        <th><label for="tumblrize_wpuser_password">Tumblr Password</label></th>
        <td>
            <input type="password" name="tumblrize_wpuser_password" id="tumblrize_wpuser_password" value="<?php print esc_attr(get_the_author_meta('tumblrize_wpuser_password', $user->ID)); ?>" class="regular-text" />
            <span class="description">Enter the password you use to login to Tumblr.</span>
        </td>
    </tr>
</table>
<?
    }

    /**
     * Saves entered options.
     */
    function save_user_options ($user_id) {
        if (!current_user_can('edit_user', $user_id)) { return false; }

        update_usermeta($user_id, 'tumblrize_wpuser_email', $_POST['tumblrize_wpuser_email']);
        update_usermeta($user_id, 'tumblrize_wpuser_password', $_POST['tumblrize_wpuser_password']);
    }

} // END TumblrizeWPUser();

$tumblrize = new TumblrizePlugin();

/*************************
 * WordPress Admin Hooks *
 *************************/

add_action('admin_notices', array($tumblrize, 'show_admin_messages'));

// Display user options in profile.
add_action('show_user_profile', array('TumblrizeWPUser', 'show_user_options'));
add_action('edit_user_profile', array('TumblrizeWPUser', 'show_user_options'));
// Save options when updated from profile.
add_action('personal_options_update', array('TumblrizeWPUser', 'save_user_options'));
add_action('edit_user_profile_update', array('TumblrizeWPUser', 'save_user_options'));

function tumblrize_menu () {
  add_options_page('Tumblrize', 'Tumblrize', 8, __FILE__, 'tumblrize_options');
}

/**
 * Adds a customized box to the Write Post screen.
 */
function tumblrize_add_custom_box () {
    add_meta_box('tumblrize', 'Tumblrize', 'tumblrize_custom_box', 'post', 'advanced');
}

/**
 * Prints the actual custom box for the plugin.
 *
 * TODO: Make a UI for specifying a click-through-url, now that we support it.
 */
function tumblrize_custom_box () {
    global $post;

    // Use nonce for verification
    print '<input type="hidden" name="tumblrize_nonce" id="tumblrize_nonce" value="' . 
            wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    $tumblr_post_type = get_post_meta($post->ID, 'tumblrize_post-type', true);
    $tumblr_post_id = get_post_meta($post->ID, 'tumblrize_post-id', true);
    // Use global option unless post meta exists.
    $post_group = (get_post_meta($post->ID, 'tumblrize_post-group', true))
                    ? get_post_meta($post->ID, 'tumblrize_post-group', true)
                    : get_option('tumblrize_tumblr_group');
    $act = (get_post_meta($post->ID, 'tumblrize_post-id', true)) ? 'Update post on' : 'Crosspost to';
    $tumblrize_shutoff = (get_option('tumblrize_shutoff') === '') ? false : true;
    if (in_category(get_option('tumblrize_exclude_cats'), $post->ID)) {
        // TODO: Inform the user exactly which categories need to be removed
        //       for the custom box options to be honored.
        print '<p class="error fade">';
        print 'This post is in a category excluded from Tumblrize operations. ';
        print 'For the options below to take effect, be certain to also remove this post from all such categories.';
        print '</p>';
    }
?>
<fieldset>

    <legend><?php print $act;?> Tumblr?</legend>
    <div>
        <input type="radio" id="tumblrize_do-tumblrize" name="tumblrize_do-tumblrize" value="1"<?php if (!$tumblrize_shutoff) : print ' checked="checked"';endif;?> />
        <label for="tumblrize_do-tumblrize">Yes</label>
        <input type="radio" id="tumblrize_dont-tumblrize" name="tumblrize_do-tumblrize" value="0"<?php if ($tumblrize_shutoff) : print ' checked="checked"';endif;?> />
        <label for="tumblrize_dont-tumblrize">No</label>
    </div>

    <label for="tumblrize_post-type">Tumblr Post Type</label>
    <select id="tumblrize_post-type"
            name="tumblrize_post-type"
            <?php if ($tumblr_post_type && $tumblr_post_id) : print 'disabled="disabled"';endif;?>>
        <optgroup label="Select">
            <option value="regular"<?php if ('regular' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Text</option>
            <option value="photo"<?php if ('photo' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Photo</option>
            <option value="link"<?php if ('link' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Link</option>
            <option value="quote"<?php if ('quote' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Quote</option>
            <option value="audio"<?php if ('audio' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Audio</option>
            <option value="video"<?php if ('video' === $tumblr_post_type) : print ' selected="selected"';endif;?>>Video (YouTube)</option>
        </optgroup>
        <optgroup label="Not Implemented Yet">
            <option value="conversation">Conversation</option>
        </optgroup>
    </select>
</fieldset>
<fieldset><?php // TODO: Make this gracefully degrade when JavaScript is not turned on in the browser.?>
    <legend style="display: none;">Tumblr Groups</legend>
    <label for="tumblrize-use-alt-group">Post to Alternate Group</label>
<?php if (!get_post_meta($post->ID, 'tumblrize_post-id', true)) : ?>
    <input type="checkbox" id="tumblrize-use-alt-group" name="tumblrize-use-alt-group" value="1" onclick="var x = document.getElementById('tumblrize_post-group-options');(document.getElementById('tumblrize-use-alt-group').checked) ? x.style.display='block' : x.style.display='none';" />
    <div id="tumblrize_post-group-options" style="display:none;">
        <label for="tumblrize_post-group">Tumblr Group</label>
        <input type="text" id="tumblrize_post-group" name="tumblrize_post-group" value="<?php print $post_group;?>" />
    </div>
<?php else: ?>
        <input type="text" id="tumblrize_post-group" name="tumblrize_post-group" value="<?php print $post_group;?>" disabled="disabled" style="width:100%;" />
<?php endif; // END if (!get_post_meta($post->ID, 'tumblrize_post-id', true)) ?>
</fieldset>
<?php
} // END function tumblrize_custom_box

/**
 * Saves data from custom box on WordPress Write Post screen to database.
 */
function tumblrize_save_post ($post_id) {
    global $tumblrize;

    // Verify this came from our screen appropriately.
    if ( !wp_verify_nonce($_POST['tumblrize_nonce'], plugin_basename(__FILE__) )) {
        return $post_id;
    }

    // Verify we're not doing an auto save routine.
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { return $post_id; }

    // Check permissions
    if ( 'post' == $_POST['post_type'] ) {
        if ( !current_user_can('edit_post', $post_id) ) { return $post_id; }
    }

    // Check that Tumblrize should act on this post.
    if (!$tumblrize->isTumblrizeablePost()) { return $post_id; }

    // Okay, we've authenticated correctly. Find and save the data.
    if (!empty($_POST['tumblrize_post-type'])) {
        update_post_meta($post_id, 'tumblrize_post-type', $_POST['tumblrize_post-type']);
    }
    if (!empty($_POST['tumblrize_post-group'])) {
        update_post_meta($post_id, 'tumblrize_post-group', $_POST['tumblrize_post-group']);
    }
    if ($_POST['publish'] === 'Schedule') {
        add_post_meta($post_id, 'tumblrize_post-future', 1, true);
    }
}

/**
 * Performs programmatic activation/upgrade from prior version actions.
 */
function tumblrize_activate () {
    if (test_version(TUMBLRIZE_PLUGIN_VERSION, '>=', '1.3')) {
        // These were the pre-v1.3 option names, and should be moved.
        add_option('tumblrize_add_permalink', get_option('add_permalink'));
        delete_option('add_permalink');

        add_option('tumblrize_notify_me', get_option('notify_me'));
        delete_option('notify_me');

        add_option('tumblrize_shutoff', get_option('shut_tumblrize'));
        delete_option('shut_tumblrize');

        add_option('tumblrize_tumblr_email', get_option('tumblr_email'));
        delete_option('tumblr_email');

        add_option('tumblrize_tumblr_password', get_option('tumblr_password'));
        delete_option('tumblr_password');

        add_option('tumblrize_tumblr_posterous', get_option('tumblr_posterous'));
        delete_option('tumblr_posterous');
    }

    // For post version 1.3.1 plugin.
    add_option('tumblrize_tumblr_group', get_option('tumblrize_tumblr_group'));
}
register_activation_hook(__FILE__, 'tumblrize_activate');

add_action('admin_menu', 'tumblrize_add_custom_box');
add_action('save_post', 'tumblrize_save_post');

add_action('publish_post', array($tumblrize, 'tumblrize'));
add_action('transition_post_status', array($tumblrize, 'queuePost'), 10, 3);
add_action('delete_post', array($tumblrize, 'delete_post'));

add_action('admin_menu', 'tumblrize_menu');

/**
 * Provides plugin-wide options screen.
 *
 * TODO: Eventually, these options should be consolidated logically so as not
 *       to keep cluttering the database with Tumblrize options. Kind of messy.
 */
function tumblrize_options () {
?>
<div class="wrap">
    <h2><img src="http://id.ijulien.com/img/tumblrize.png" alt="Tumblrize" /></h2>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php if (get_option('tumblrize_tumblr_email') == "") : ?>
        <p>Enter your Tumblr email and password to enable Tumblrize to post updates.</p>
        <?php endif;?>

        <table class="form-table" summary="Tumblrize Plugin Options">
            <tr valign="top">
                <th scope="row"><label for="tumblrize_tumblr_email">Tumblr Email</label></th>
                <td>
                    <input type="text" id="tumblrize_tumblr_email" name="tumblrize_tumblr_email" autocomplete="off" value="<?php echo get_option('tumblrize_tumblr_email'); ?>" />
                    <span class="setting-description">Provide a default Tumblr account for authors to use to crosspost content.</span><br />
                    <span class="description">To force users to provide their own credentials, enter <code>NONE</code> here, and in the <em>Tumblr Password</em> field, below.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_tumblr_password">Tumblr Password</label></th>
                <td>
                    <input type="password" id="tumblrize_tumblr_password" name="tumblrize_tumblr_password" autocomplete="off" value="<?php echo get_option('tumblrize_tumblr_password'); ?>" />
                    <span class="setting-description">Provide the password for the default Tumblr account.</span><br />
                    <span class="description">To force users to provide their own credentials, enter <code>NONE</code> here, and in the <em>Tumblr Email</em> field, above.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_tumblr_group">Use Tumblr Group</label></th>
                <td>
                    <input type="text" id="tumblrize_tumblr_group" name="tumblrize_tumblr_group" autocomplete="off" value="<?php echo get_option('tumblrize_tumblr_group'); ?>" />
                    <span class="setting-description">Use a domain name; e.g., <code>my-other-blog.tumblr.com</code></span><br />
                    <span class="description">Send to this group on Tumblr by default. Leave blank to send to your default Tumblr blog instead. (Can be overriden on Write Post screen.)</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_shutoff">Turn Off Tumblrize</label></th>
                <td>
                    <input type="checkbox" id="tumblrize_shutoff" name="tumblrize_shutoff" value="tumblrize_shutoff_on" <?php if ( get_option('tumblrize_shutoff') == "tumblrize_shutoff_on" ) { echo 'checked="checked"'; }?> />
                    <span class="description">Disables default crossposting to Tumblr, but will still crosspost to Posterous if "Also send to Posterous?" option is enabled. (Can also be overriden on a per-post basis.)</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_exclude_cats">Do not crosspost entries in these categories:</label></th>
                <td>
                    <span class="description">Will cause posts in the specificied categories never to be crossposted to Tumblr. This is useful if, for instance, you are creating posts automatically using another plugin and wish to avoid a feedback loop of crossposting back and forth from one service to another.</span>
<?php
// TODO: Make this not such a terrible hack job. Really.
ob_start();
?>
                    <ul><?php print wp_category_checklist(0, 0, get_option('tumblrize_exclude_cats'));?></ul>
<?php
$out = ob_get_contents();
// Change form appropriately.
$out = preg_replace('/post_category\[]/', 'tumblrize_exclude_cats[]', $out);
ob_end_clean();
// But hey...this hack job totally works for now.
print $out;
?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="tumblrize_tumblr_posterous">Also send to <a href="http://www.posterous.com/" title="Posterous">Posterous</a>?</label>
                </th>
                <td>
                    <input type="checkbox" id="tumblrize_tumblr_posterous" name="tumblrize_tumblr_posterous" value="posterous" <?php if ( get_option('tumblrize_tumblr_posterous') == "posterous" ) { echo 'checked="checked"'; }?> />
                    <span class="description">Enables crossposting to Posterous independently from Tumblr.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="tumblrize_notify_me">Also send an email to <strong><?php if ( get_option('tumblrize_tumblr_email') ) { echo get_option('tumblrize_tumblr_email'); } else { echo 'me'; } ?></strong>?</label>
                </th>
                <td>
                    <input type="checkbox" id="tumblrize_notify_me" name="tumblrize_notify_me" value="notify_on" <?php if ( get_option('tumblrize_notify_me') == "notify_on" ) { echo 'checked="checked"'; }?> />
                    <span class="description">When enabled, Tumblrize will send you an email confirmation each time it successfully crossposts a new post.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_add_permalink">Also add link to the original post?</label></th>
                <td>
                    <input type="checkbox" id="tumblrize_add_permalink" name="tumblrize_add_permalink" value="tumblrize_add_permalink_on" <?php if ( get_option('tumblrize_add_permalink') == "tumblrize_add_permalink_on" ) { echo 'checked="checked"'; }?> />
                    <span class="description">When enabled, Tumblrize will append a paragraph with a link back to the original post on this blog in any crossposted entries.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_tags">Always add these tags?</label>
                <td>
                    <input type="text" id="tumblrize_tags" name="tumblrize_tags" autocomplete="off" value="<?php echo get_option('tumblrize_tags'); ?>" />
                    <br /><span class="description">Comma separated list of additional tags to always crosspost. E.g., <code>crossposted,tumblr</code>. Defaults to <code>tumblrize</code> if left blank.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tumblrize_add_post_tags">Add post tags, too?</label></th>
                <td>
                    <input type="checkbox" id="tumblrize_add_post_tags" name="tumblrize_add_post_tags" value="tumblrize_add_post_tags" <?php if ( get_option('tumblrize_add_post_tags') == "tumblrize_add_post_tags" ) { echo 'checked="checked"'; }?> />
                    <span class="description">Adds an individual post's tags to the crossposted post in addition to any tags always added, above.</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="tumblrize_purge_database">Purge Tumblrize options on uninstall?</label>
                </th>
                <td>
                    <input type="checkbox" id="tumblrize_purge_database" name="tumblrize_purge_database" value="tumblrize_purge_database" <?php if ( get_option('tumblrize_purge_database', 'tumblrize_purge_database') == "tumblrize_purge_database" ) { echo 'checked="checked"'; }?> />
                    <span class="description">Deletes saved options for this plugin from database when you uninstall Tumblrize.</span>
                </td>
            </tr>
        </table>

        <p style="text-align: center;">We &hearts; <a href="http://www.tumblr.com/">Tumblr</a>. Don't send spam. &mdash; <a href="http://ijulien.com/" title="ijulien" target="_blank">&infin;julien</a> &amp; <a href="http://maymay.net/">Meitar</a></p>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="tumblrize_tumblr_email,tumblrize_tumblr_password,tumblrize_tumblr_posterous,tumblrize_notify_me,tumblrize_add_permalink,tumblrize_shutoff,tumblrize_tags,tumblrize_add_post_tags,tumblrize_purge_database,tumblrize_tumblr_group,tumblrize_exclude_cats" />
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div><!-- END .wrap -->

<?php
} // END tumblrize_options()
?>

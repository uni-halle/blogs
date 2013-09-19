<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header('Allow: POST');
    header("HTTP/1.1 405 Method Not Allowed");
    header("Content-type: text/plain");
    exit;
}
$db_check = true;
function kill_data() {
    return '';
}
function check_db() {
    global $wpdb, $db_check;
    if($db_check) {
        // Check DB
        if(!$wpdb->dbh) {
            echo('Our database has issues. Try again later.');
        } else {
            echo('We\'re currently having site problems. Try again later.');
        }
        die();
    }
}
ob_start('kill_data');
register_shutdown_function('check_db');
require_once(dirname(__FILE__)."/../../../wp-config.php");
$db_check = false;
ob_end_clean();
nocache_headers();
function fail($s) {
    header('HTTP/1.0 500 Internal Server Error');
    echo $s;
    exit;
}
$comment_post_ID = (int) $_POST['comment_post_ID'];
$status = $wpdb->get_row("SELECT post_status, comment_status FROM $wpdb->posts WHERE ID = '$comment_post_ID'");
if ( empty($status->comment_status) ) {
    do_action('comment_id_not_found', $comment_post_ID);
    fail('The post you are trying to comment on does not currently exist in the database.');
} elseif ( 'closed' ==  $status->comment_status ) {
    do_action('comment_closed', $comment_post_ID);
    fail('Sorry, comments are closed for this item.');
} elseif ( in_array($status->post_status, array('draft', 'pending') ) ) {
    do_action('comment_on_draft', $comment_post_ID);
    fail('The post you are trying to comment on has not been published.');
}
$comment_author       = trim(strip_tags($_POST['author']));
$comment_author_email = trim($_POST['email']);
$comment_author_url   = trim($_POST['url']);
$comment_content      = trim($_POST['comment']);
// If the user is logged in
$user = wp_get_current_user();
if ( $user->ID ) {
    $comment_author       = $wpdb->escape($user->display_name);
    $comment_author_email = $wpdb->escape($user->user_email);
    $comment_author_url   = $wpdb->escape($user->user_url);
    if ( current_user_can('unfiltered_html') ) {
        if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
            kses_remove_filters(); // start with a clean slate
            kses_init_filters(); // set up the filters
        }
    }
} else {
    if ( get_option('comment_registration') )
        fail('Sorry, you must be logged in to post a comment.');
}
$comment_type = '';
if ( get_option('require_name_email') && !$user->ID ) {
    if ( 6> strlen($comment_author_email) || '' == $comment_author )
        fail('Sorry: please fill the required fields (name, email).');
    elseif ( !is_email($comment_author_email))
        fail('Sorry: please enter a valid email address.');
}
if ( '' == $comment_content )
    fail('Sorry: please type a comment.');
// Simple duplicate check
$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
$dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
if ( $wpdb->get_var($dupe) ) {
    fail('Duplicate comment detected; it looks as though you\'ve already said that!');
}
$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');
$comment_id = wp_new_comment( $commentdata );
$comment = get_comment($comment_id);
if ( !$user->ID ) {
    setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
    setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
    setcookie('comment_author_url_' . COOKIEHASH, clean_url($comment->comment_author_url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
}
@header('Content-type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>


<li class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admincomment';} else {echo 'regularcomment';} ?>" id="comment-<?php comment_ID() ?>">
<div class="comment-meta">
<? printf( __('%1$s at %2$s', 'philna'), get_comment_time(__('F jS, Y', 'philna')), get_comment_time(__('H:i', 'philna')) ); ?>| 
#<span class="nub"></span>
<?php edit_comment_link(__('Edit', 'philna'), ' | ', ''); ?></div>
<div class="comment-author">
<?php if (function_exists('get_avatar') && get_option('show_avatars')) { echo get_avatar($comment, 32); } ?>				

<?php if (get_comment_author_url()) : ?>
<span class="fn"><a id="commentauthor-<?php comment_ID() ?>" href="<?php comment_author_url() ?>"><?php comment_author(); ?></a></span>
<?php else : ?>
<span class="fn" id="commentauthor-<?php comment_ID() ?>"><?php comment_author(); ?></span>
<?php endif; ?>
</div>
<?php if ($comment->comment_approved == '0') : ?>
<p><small class="waiting">Your comment is awaiting moderation.</small></p>
<?php endif; ?>
<?php comment_text();?>
</li>
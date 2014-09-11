<?php
/**
 * Handles Comment Post to WordPress and prevents duplicate comment posting.
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

/** Sets up the WordPress Environment. I hate using the ../ 's but it seems they have to be used :( */
require("../../../../wp-load.php");

nocache_headers();

$comment_post_ID = (int) $_REQUEST['comment_post_id'];

$status = $wpdb->get_row( $wpdb->prepare("SELECT post_status, comment_status FROM $wpdb->posts WHERE ID = %d", $comment_post_ID) );
if ( empty($status->comment_status) ) :
?>
   <div class="dynamic-header"><div class="right"></div></div>
   <div class="dynamic-content">
        <div class="comment clearfix">
            <div class="comment-post">
            	<p>Comment I.D. not found.</p>
            </div>
        </div>
	</div>
    <div class="dynamic-footer"><div class="left"></div><div class="right"></div></div>
<?php
	exit;
elseif ( !comments_open($comment_post_ID) ) :
?>
   <div class="dynamic-header"><div class="right"></div></div>
   <div class="dynamic-content">
        <div class="comment clearfix">
            <div class="comment-post">
            	<p class="error">Sorry, comments are closed for this item.</p>
       		</div>
        </div>
	</div>
    <div class="dynamic-footer"><div class="left"></div><div class="right"></div></div>
<?php
	exit;

elseif ( in_array($status->post_status, array('draft', 'pending') ) ) :
?>
   <div class="dynamic-header"><div class="right"></div></div>
   <div class="dynamic-content">
        <div class="comment clearfix">
            <div class="comment-post">
            	<p class="error">Comment on Draft</p>
       		</div>
        </div>
	</div>
    <div class="dynamic-footer"><div class="left"></div><div class="right"></div></div>
<?php 
	exit;
endif;
global $comment_twitter , $comment_subscribe;
$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
$comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
$comment_twitter      = ( isset($_POST['twitter']) ) ? trim($_POST['twitter']) : null;
$comment_subscribe      = ( isset($_POST['email_subscribe']) ) ? trim($_POST['email_subscribe']) : null;

$comment_meta_table = $wpdb->prefix . "ocmx_comment_meta";	

$check_blocked = $wpdb->get_row( $wpdb->prepare("SELECT $wpdb->comments.*, $comment_meta_table.* FROM $wpdb->comments INNER JOIN $comment_meta_table ON $wpdb->comments.comment_ID = $comment_meta_table.commentId WHERE $wpdb->comments.comment_author_email = %s AND $comment_meta_table.block_user = 1", $comment_author_email) );

if(count($check_blocked) !== 0) :
?>
  	<div class="dynamic-header"><div class="right"></div></div>
	<div class="dynamic-content">
		<div class="comment clearfix">
            <div class="comment-post">
                <p class="error">Your email address has been blocked from commenting on this blog.</p>
            </div>
        </div>
    </div>
    <div class="dynamic-footer"><div class="left"></div><div class="right"></div></div>
<?php 
	exit;
endif;
// If the user is logged in
$user = wp_get_current_user();
if ( $user->ID ) {
	if ( empty( $user->display_name ) )
		$user->display_name=$user->user_login;
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
		wp_die( __('Sorry, you must be logged in to post a comment.') );
}

$comment_type = '';

if ( get_option('require_name_email') && !$user->ID ) {
	if ( 6 > strlen($comment_author_email) || '' == $comment_author )
		wp_die( __('Error: please fill the required fields (name, email).') );
	elseif ( !is_email($comment_author_email))
		wp_die( __('Error: please enter a valid email address.') );
}

if ( '' == $comment_content )
	wp_die( __('Error: please type a comment.') );

$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;

$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

$comment_id = wp_new_comment( $commentdata );
/* $ocmx_add_comment_meta($comment_id, $comment_post_ID) */

$comment = get_comment($comment_id);
if ( !$user->ID ) {
	setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_url_' . COOKIEHASH, clean_url($comment->comment_author_url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
}
// Adjust the classes according to whether or not we're replying to another comment
global $header_class, $main_class, $footer_class;
$comment_table = $wpdb->prefix . "ocmx_comment_meta";
$comment_meta_sql = "SELECT * FROM $comment_table WHERE commentId = ".$comment->comment_ID." LIMIT 1";
$comment_count = $wpdb->get_row( $wpdb->prepare("SELECT comment_count FROM $wpdb->posts WHERE ID = %d", $comment_post_ID) );
$comment_meta = $wpdb->get_row($comment_meta_sql);

?>
<?php if(isset($_POST['comment_parent']) && $_POST['comment_parent'] !== "0" && $_POST['comment_parent'] !== "") : ?>
	<div class="threaded-comments">
		<div class="thread-comment">
<?php else: ?>
	<?php if($comment_count == "1") : ?>
		<h1 class="header-comments"><?php  echo $post->comment_count; ?> Comments <a name="#comments"></a></h1>
	<?php endif; ?>
	<div class="comment clearfix">
<?php endif; ?>

	<div class="comment clearfix">
        <div class="user">
            <?php echo get_avatar($comment, 40 ); ?>                
        </div>
        <div class="comment-post">
            <div class="container-header-light-normal"><span></span></div>
            <div class="comment-content clearfix">
                <h3>
                    <?php if($comment->comment_author_url !== "http://" && $comment->comment_author_url !== "") : ?>
                       <a href="<?php echo $comment->comment_author_url; ?>" class="commentor_url" name="comment-<?php echo $comment->comment_ID; ?>" rel="nofollow"> <?php echo $comment->comment_author; ?></a>
                    <?php else : ?>
                         <?php echo $comment->comment_author; ?>
                    <?php endif; ?>
                    <?php if($comment_meta->twitter !== "") : ?><span class="twitter-link"><a href="http://twitter.com/<?php echo $comment_meta->twitter; ?>" class="commentor_url" rel="nofollow">@<?php echo $comment_meta->twitter; ?></a></span><?php endif; ?>
                    <?php comment_author(); ?></a><span class="comment-date"><?php echo date('F d Y', strtotime($comment->comment_date)); ?> <?php echo date("H\:i a", strtotime($comment->comment_date)); ?></span>
                </h3>
                <?php if ($comment->comment_approved == '0') : ?>
                    <p>Comment is awaiting moderation.</p>
                <?php else :
                    $use_comment = apply_filters('wp_texturize', $comment->comment_content);
                    $use_comment = str_replace("\n", "<br>", $use_comment);
                    echo "<p>".$use_comment."</p>";
                endif; ?>
            </div>
            <div class="container-footer-light-normal"><span></span></div>
        </div>
	</div>
<?php if(isset($_POST['comment_parent']) && $_POST['comment_parent'] !== "0" && $_POST['comment_parent'] !== "") : ?>
        </div>
    </div>
<?php else : ?>
	</div>
<?php endif; ?>




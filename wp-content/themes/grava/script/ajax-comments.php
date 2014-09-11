<?php
require_once(dirname(__FILE__)."/../../../../wp-config.php");
nocache_headers();

/* This posts the comments and checks for Math Comment Spam Protection
 * and is called by an asynchronous request (AJAX-Shit)
 */

// Returns HMTL-Code, this will be used as the XHR-response
function failMessage($s) {
  echo '<span class="commenterror">Fehler: </span> ' . $s;
  exit;
}
function successMessage($s) {
  echo $s;
  exit;
}

// first urldecode the post-values
foreach($_POST as $key => $post) {
  $_POST[$key] = urldecode($post);
}

// secondly trim the post-values
foreach($_POST as $key => $post) {
  $_POST[$key] = trim($post);
}

$author = $_POST['author'];
$email = $_POST['email'];
$url = $_POST['url'];
$comment = $_POST['comment'];
$commentType = '';
$postID = (int) $_POST['comment_post_ID'];
$postStatus = $wpdb->get_var("SELECT comment_status FROM $wpdb->posts WHERE ID = '$postID'");

if ( empty($postStatus) ) {
  failMessage('Der Beitrag, den du versucht zu kommentieren existiert nicht.');
} elseif ($postStatus == 'closed') {
  failMessage('Die Kommentarfunktion ist f&uuml;r diesen Beitrag deaktiviert.');
}

// If the user is logged in
get_currentuserinfo();
if ( $user_ID ) {
  $author = addslashes($user_identity);
  $email = addslashes($user_email);
  $url = addslashes($user_url);
} else {
  if ( get_option('comment_registration') ) {
    failMessage('Du musst eingeloggt sein um kommentieren zu d&uuml;rfen.');
  }
}

// Email and Name required?
if ( get_settings('require_name_email') && !($user_ID) ) {
  if ( strlen($email) < 6 || $author == '') {
    failMessage('Bitte f&uuml;ll die Felder &raquo;Name&laquo; und &raquo;E-Mail-Adresse&laquo; aus.');
  } else {
    if ( !is_email($email)) {
      failMessage('Bitte gib eine korrekte E-Mail-Adresse an.');
    }
  }
}

// No Comment?
if ($comment == '') {
  failMessage('Bitte gib einen Kommentar ein.');
}

// Simple duplicate check
$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$postID' AND ( comment_author = '$author' ";
if ( $email ) {
  $dupe .= "OR comment_author_email = '$email' ";
}
$dupe .= ") AND comment_content = '$comment' LIMIT 1";

if ( $wpdb->get_var($dupe) )
  failMessage('Der exakt gleiche Kommentar ist schon vorhanden.');

// Finally we perform the MathCommentSpamProtection-Check if
// the plugin is installed and a user is _not_ logged in

if ( ! $user_ID ) {
  if ( function_exists('math_comment_spam_protection')) {
    if (class_exists('MathCheck')) {
      $mcspStuff = get_option('plugin_mathcommentspamprotection');
      $gravaMcsp = new MathCheck;
      $spamCheck = $gravaMcsp->InputValidation($_POST['mcspinfo'], $_POST['mcspvalue']);
      
  		switch ($spamCheck) {
      	case 'No answer' : 
      		failMessage('Du musst noch die beiden Zahlen zusammenrechnen!');
      		break;
      	case 'Wrong answer': 
      		failMessage('Da hast Du dich wohl verrechnet!');
      		break;
      }
    }
  }
}

// Everythin is ok, enter prepare the comment an enter it in to the db
$commentData = array('comment_post_ID' => $postID, 
                     'comment_author' => $author, 
                     'comment_author_email' => $email, 
                     'comment_author_url' => $url, 
                     'comment_content' => $comment, 
                     'comment_type' => $commentType, 
                     'user_ID' => $userID);

$newCommentID = wp_new_comment($commentData);

// if current user not in db, set a cookie
if (!$user_ID) {
  setcookie('comment_author_' . COOKIEHASH, stripslashes($author), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
  setcookie('comment_author_email_' . COOKIEHASH, stripslashes($email), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
  setcookie('comment_author_url_' . COOKIEHASH, stripslashes($url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
}

$comment = $wpdb->get_row("SELECT * FROM {$wpdb->comments} WHERE comment_ID = " . $newCommentID);

// parse the new comments.php result and return the last <li>
ob_start();
$comments = array($comment);
include(TEMPLATEPATH . '/comments.php');
$commentout = ob_get_clean();
preg_match('#<li(.*?)>(.*)</li>#ims', $commentout, $matches);

successMessage("<li id=\"newcomment\">".$matches[2]."</li>");

?>
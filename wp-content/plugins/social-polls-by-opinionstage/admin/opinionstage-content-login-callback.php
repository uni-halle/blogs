<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

add_action( 'admin_menu', 'opinionstage_register_login_content_callback_page' );
add_action( 'admin_init', 'opinionstage_login_content_callback' );

// adds page for post-login redirect and setup in form of invisible menu page,
// and url: http://wp-host.com/wp-admin/admin.php?page=OPINIONSTAGE_LOGIN_CALLBACK_SLUG
function opinionstage_register_login_content_callback_page() {
  if (function_exists('add_menu_page')) {
    add_submenu_page(
      null,
      '',
      '',
      'edit_posts',
      'opinionstage-content-login-callback-page'
    );
  }
}

// performs redirect to content page with opened modal, after user logged in
function opinionstage_login_content_callback() {
  if ( 'opinionstage-content-login-callback-page' == filter_input( INPUT_GET, 'page' ) ) {
    $success = $_GET['success'];
    $uid = $_GET['uid'];
    $token = $_GET['token'];
    $email = $_GET['email'];
    $fly_id = $_GET['fly_id'];
    $article_placement_id = $_GET['article_placement_id'];
    $sidebar_placement_id = $_GET['sidebar_placement_id'];
    $redirect_url = urldecode($_GET['return_path']);

    delete_option(OPINIONSTAGE_OPTIONS_KEY);

    opinionstage_parse_client_data(
      compact(
        'success',
        'uid',
        'token',
        'email',
        'fly_id',
        'article_placement_id',
        'sidebar_placement_id'
      )
    );

    error_log('[opinionstage plugin] user logged in, redirect to '.$redirect_url);
    if ( wp_redirect( $redirect_url, 302 ) ) {
      exit;
    }
  }
}
?>

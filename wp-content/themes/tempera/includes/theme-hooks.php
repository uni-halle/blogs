<?php
/*
 * Theme hooks
 *
 * @package tempera
 * @subpackage Functions
 */

/**
 * HEADER.PHP HOOKS
*/

// Before wp_head hook
function cryout_header_hook() {
    do_action('cryout_header_hook');
}
// Meta hook
function cryout_meta_hook() {
    do_action('cryout_meta_hook');
}

// Before wrapper
function cryout_body_hook() {
    do_action('cryout_body_hook');
}

// Inside wrapper
function cryout_wrapper_hook() {
    do_action('cryout_wrapper_hook');
}

// Topbar
function cryout_topbar_hook() {
    do_action('cryout_topbar_hook');
}

// Before masthead
function cryout_masthead_hook() {
    do_action('cryout_masthead_hook');
}

// Inside branding
function cryout_branding_hook() {
    do_action('cryout_branding_hook');
}

// Inside header for widgets
function cryout_header_widgets_hook() {
    do_action('cryout_header_widgets_hook');
}

// Inside access
function cryout_access_hook() {
    do_action('cryout_access_hook');
}

// Inside main
function cryout_main_hook() {
    do_action('cryout_main_hook');
}

// Inside forbottom
function cryout_forbottom_hook() {
    do_action('cryout_forbottom_hook');
}

// Breadcrumbs
function cryout_breadcrumbs_hook() {
    do_action('cryout_breadcrumbs_hook');
}

/**
 * FOOTER.PHP HOOKS
*/

// Footer hook
function cryout_footer_hook() {
    do_action('cryout_footer_hook');
}


/**
 * COMMENTS.PHP HOOKS
*/

// Before comments hook
function cryout_before_comments_hook() {
    do_action('cryout_before_comments_hook');
}

// Actual comments hook
function cryout_comments_hook() {
    do_action('cryout_comments_hook');
}

// After comments hook
function cryout_after_comments_hook() {
    do_action('cryout_after_comments_hook');
}

// No comments hook
function cryout_nocomments_hook() {
    do_action('cryout_nocomments_hook');
}


/**
 * SIDEBAR.PHP HOOKS
*/

// No comments hook
function cryout_before_primary_widgets_hook() {
    do_action('cryout_before_primary_widgets_hook');
}

// No comments hook
function cryout_after_primary_widgets_hook() {
    do_action('cryout_after_primary_widgets_hook');
}

// No comments hook
function cryout_before_secondary_widgets_hook() {
    do_action('cryout_before_secondary_widgets_hook');
}

// No comments hook
function cryout_after_secondary_widgets_hook() {
    do_action('cryout_after_secondary_widgets_hook');
}

/**
 * LOOP.PHP HOOKS
*/

// Before each article hook
function cryout_before_article_hook() {
    do_action('cryout_before_article_hook');
}

// After each article hook
function cryout_after_article_hook() {
    do_action('cryout_after_article_hook');
}

// After each article title
function cryout_post_title_hook() {
    do_action('cryout_post_title_hook');
}

// After each post meta
function cryout_post_meta_hook() {
    do_action('cryout_post_meta_hook');
}

// Before the actual post content
function cryout_post_before_content_hook() {
    do_action('cryout_post_before_content_hook');
}

// After the actual post content
function cryout_post_after_content_hook() {
    do_action('cryout_post_after_content_hook');
}

// After the actual post content
function cryout_post_footer_hook() {
    do_action('cryout_post_footer_hook');
}

//Content hooks

function cryout_before_content_hook() {
    do_action('cryout_before_content_hook');
}

function cryout_after_content_hook() {
    do_action('cryout_after_content_hook');
}
?>
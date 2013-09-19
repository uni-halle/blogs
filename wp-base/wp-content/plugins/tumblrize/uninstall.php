<?php
/**
 *
 * @file uninstall.php
 * @license GPL3
 *
 *  Copyright 2008  Meitar Moscovitz  (email : meitarm@gmail.com)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) { exit(); }
define('WP_DEBUG', true);

if ( get_option('tumblrize_purge_database') ) {
    delete_option('tumblrize_add_permalink');
    delete_option('tumblrize_add_post_tags');
    delete_option('tumblrize_notify_me');
    delete_option('tumblrize_purge_database');
    delete_option('tumblrize_shutoff');
    delete_option('tumblrize_tags');
    delete_option('tumblrize_tumblr_email');
    delete_option('tumblrize_tumblr_group');
    delete_option('tumblrize_tumblr_password');
    delete_option('tumblrize_tumblr_posterous');
    delete_option('tumblrize_exclude_cats');

    // Delete user-specific Tumblr credentials.
    global $wpdb;
    $wpuser_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT $wpdb->users.ID FROM $wpdb->users ORDER BY %s ASC", 'ID'
        ));
    foreach ($wpuser_ids as $wpuser_id) {
        delete_usermeta($wpuser_id, 'tumblrize_wpuser_email');
        delete_usermeta($wpuser_id, 'tumblrize_wpuser_password');
    }
}
?>
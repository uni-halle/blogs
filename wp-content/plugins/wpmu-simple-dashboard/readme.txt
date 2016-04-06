=== WPMU Simple Dashboard ===
Contributors: DeannaS, kgraeme
Website: http://wordpress.org/extend/plugins/wpmu-simple-dashboard/
Tags: WPMU, dashboard
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

This plugin lets site admins control which features are shown by default on all user's dashboards.

== Description ==

This plugin allows site admins to turn on and off the following widgets on the WPMU dashboard (for all users):

1. Primary Feed
2. Secondary Feed
3. Incoming Links
4. Recent Comments
5. Recent Drafts
6. Plugins
7. Quick Press
8. Right Now

In addition, site admins can override the primary and secondary feeds.


== Installation ==

1. Upload the `cets_simple_dashboard.php` file to your  `wp-content/mu-plugins directory`
2. Go to Site Admin -> Options to configure dashboards.


== Frequently Asked Questions ==
1. Can I chose which blogs this affects?

No,these are sitewide settings.


== Screenshots ==

1. Administrative View of Simple Dashboard Options.
2. User view of simplified dashboard.

== Changelog ==

1.6.1 - Performance fix to reduce queries on non-dashboard pages
1.6 - Modifications to prepare for WP 3.3
1.5 - Fixed help options for 3.0.
1.3.4 - Added the ability to modify the "other help" information on the dashboard page. Title "Other Help" can not be removed through standard filters, but the text underneath can be changed.
1.3.3 - Added the ability to modify the help information on the dashboard page. Note that html is allowed. Only site admins have access to the options, so XSS and hacking shouldn't be an issue.

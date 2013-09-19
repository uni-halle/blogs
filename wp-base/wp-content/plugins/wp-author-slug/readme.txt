=== WP Author Slug ===
Contributors: obenland
Tags: security, slug, author, author archive, url, permalink
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XVPLJZ3VH4GCN
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.2.2

Add a layer of security and prevent your login name from being shown in the author archive's URL.

== Description ==

This plugin replaces the author slug with a sanitized version of the user's display name.

It will prevent hackers from finding out your login name through the author archive's URL and works 
towards your friendly URLs with using your display name.


== Installation ==

1. Download WP Author Slug.
2. Unzip the folder into the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 1.2.2 =
* Updated utility class
* Tested with WordPress 3.4.1

= 1.2.1 =
* Updated uninstall.php and activation hook to use WordPress User API instead of custom queries
* Updated utility class

= 1.2 =
* Tested for WordPress 3.3.1

= 1.1 =
* Tested for WordPress 3.1.1
* Added complete uninstall routine
* Added compatibility for pre-3.1 multisite installs

= 1.0 =
* Initial Release


== Upgrade Notice == 
Maintenance update
=== Plugin Name ===
Contributors: wordpressdotorg
Donate link: 
Tags: importer, livejournal
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Import posts and comments from LiveJournal.

== Description ==

Simple importer to bring your LiveJournal over to WordPress.

== Installation ==

1. Upload the `livejournal-importer` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Tools -> Import screen, Click on LiveJournal

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.5 =
* Fix order of translation functions loading so that translations can actually be rendered

= 0.4 =
* Fix AJAX handler to use the global and have the class defined and instantiated
* Slow the auto-refreshes down
* Handle the case where the last batch of comments are all deleted ones so we don't loop forever

= 0.3 =
* Moved the AJAX Handler out of core and into the plugin
* Added support for using WP_HTTP transports for the API request in WP 3.1
* Fixed the ajax messages to actually display the elipsis

= 0.2 =
* Updates

= 0.1 =
* Initial release

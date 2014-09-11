=== WP-Walla ===
Contributors: baronen
Donate link: http://www.baronen.org
Tags: gowalla, social network, plugin, widget, feeds, feed
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 0.5.3.5

WP-Walla is an plugin that allows you to get your checkins from Gowalla and present in sidebar as a widget.

== Description ==

Gowalla is a "Here am i" service that allows you to check in to various places so your friends can see
what you are up to.

WP-Walla allows you to show your gowalla checkin's on your wordpress blog. The plugin is design to
work as a sidebar widget or by manually put a code snipper in your template.

WP-Walla caches the data in a file.

You can easily change plugin settings under the WP-Walla option i the settings menu.

More info:
[Official plugin site](http://www.baronen.org/wpwalla/)

Twitter:
Follow me on Twitter to keep up with the latest updates [BaronOfSweden](http://twitter.com/baronofsweden/)

Contact: Do you have any questions or suggestions feel free to contact me at baronen@baronen.org

== Installation ==

1. Upload entire 'wpwalla' folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enter your Gowalla username and other options under WP-Walla settings, found under Settings section i the menu.
1. Activate the widget under "Widgets" in the Appearance section of Wordpress menu or place `< ?php $wpwalla = new WP_Walla(); $wpwalla->widget(); ?> in your template
1. Now you are good to go

* Included files: index.php, wpwalla_admin.php, Wpwalla.php, wpwallacache.txt

== Screenshots ==

* screenshot-1.png
* screenshot-3.png
* screenshot-2.png

== Changelog ==

= 0.5.3.5 =
* Minimum time to cache data is set to 20 minutes

= 0.5.3.1 =
* Fixed problem with PHP short-tags

= 0.5.3 =
* Fixed the problem with 'Couldn't fetch Gowalla'

= 0.5.2 =
* Get timezone settings from Wordpress to set correct date and time
* Added support to open Gowalla links in a new window
* Now using cURL if available instead of file_get_contents
* You can now choose to display a 'Powered by WP-Walla' link

= 0.5.1 =
* Fixed an issue with long gowalla spot names

= 0.5 =
* First published version of WP-Walla


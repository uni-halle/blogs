=== WPMU Theme Info ===
Contributors: DeannaS, kgraeme, MadtownLems
Tags: Wordpress Multiuser, Themes, MU, WPMU, multisite, network
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides info to network admins and users on the popularity of themes. 

== Description ==

WordPress Multisite has two ways to activate themes either sitewide, or on a blog-by-blog basis. But, there's no convenient way built-in to know which themes are actually being used, or by whom. This plugin addresses that issue by creating a "Theme Usage Info" sub-menu of the Network Admin theme menu. Included on the page are two tables of data - one of themes currently being used, and one of themes not currently being used. The currently used themes table provides information on how many blogs are using the theme, which blogs are using it, and whether or not the theme is currently activated site-wide. The table of unused themes provides information on whether the theme is currently activated sitewide.

In addition, network admins can choose to provide this information to their users via a toggle on the administration page.

If enabled, users will be able to view data on theme usage in Appearance -> themes for every theme except the currently activated theme. A single line of text is added just before the activate link indicating how many blogs are currently using the theme. When clicked, a scrolling list of themes is displayed in a thickbox:

Thanks go out to <a href="http://wpmututorials.com/plugins/wordpress-mu-theme-stats/">Ron and Andrea</a> for their prior work in this area.

= Development =

* Repository: [wp-repository](https://github.com/wp-repository) / [wpmu-theme-usage-info](https://github.com/wp-repository/wpmu-theme-usage-info)
* Issue-Tracker: [WPMU Theme Info Issues](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [Translate > WPMU Theme Info](https://translate.foe-services.de/projects/cets_theme_info)

== Installation ==

1. Place the cets\_theme\_info.php file and directory in the wp-content/mu-plugins folder.
1. Go to site admin -> Theme Usage Info to view information and configure user access.

== Screenshots ==

1. Network Admin view of list of used and unused themes.
2. Network Admin view of list toggle controls for user access.
3. User view of theme popularity information.
4. User view of blogs using theme.

== Frequently Asked Questions ==

Nothing yet.

== Changelog ==

= 1.9 =
* fix + update of tablesort js library
* tabbed settings
* fixes for WP 3.5
* move of the development repo to GitHub

= 1.8 =
* Added a check for making sure the Theme Files are present, and will display a message if there is a site using a theme that no longer exists

= 1.7 =
* Updated for 3.4. Because of the massive Theme info API change with 3.4, this plugin now REQURES 3.4+ to function

= 1.2 - 1.6 =
* Unknown exactly because I wasn't maintaining this plugin for public use for these versions, but the following occurred in here:
	* Properly enqueue scripts
	* Updated for 3.1.  Then later dropped support for anything below 3.4
	* fixed lots of notices and warnings
	* properly store data after the 3.3 add_site_option changes

= 1.1 =
* Adding Show/Hide blogs on the administrative page.

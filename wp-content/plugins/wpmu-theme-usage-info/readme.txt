=== WPMU Theme Usage Info ===
Contributors: cfoellmann, MadtownLems
Tags: Wordpress Multiuser, Themes, MU, WPMU, multisite, network, themes
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gives network admins an easy way to see what themes are actively used on the sites of a multisite installation

== Description ==

This plugin displays the count and the individual sites for each installed theme. It add a column to the Themes table on `wp-admin/network/themes.php`.

Optionally you can display the usage count in the theme details overlay on `wp-admin/plugins.php` activated via filter described in the [FAQ](https://wordpress.org/plugins/wpmu-theme-usage-info/faq/).

> __Requires a WordPress Multisite Installation__
> JavaScript is required to toggle the list of sites using a plugin

= Development =

* GitHub Repository: [wp-repository](https://github.com/wp-repository) / [wpmu-theme-usage-info](https://github.com/wp-repository/wpmu-theme-usage-info)
* Issue-Tracker: [WPMU Theme Info Issues](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [Translate > WPMU Theme Usage Info](http://wp-translate.org/projects/wpmu-theme-usage-info)

== Installation ==

1. Install by searching "WPMU Theme Info" on Plugins > Add New > Search
2. Activate by clicking "Network Activate"

== Frequently Asked Questions ==

= When is the stats data refreshed? =

 - Auto refresh on every theme switch
 - Auto refresh on `network/themes.php` if Transient is expired (2h/24h on large networks)
 - Manual refresh if you visit `network/themes.php?manual-stats-refresh=1`

= What happens on large installations =

 - Auto refresh is not running on plugin (de)activation
 - Stats data is being regenerated every 24h (see action `wpmu_plugin_stats_refresh`)

= Hooks =

- [Filter] `wpmu_theme_stats_refresh` - (int) seconds - Manually set the expiration time of the data (Transient)
- [Filter] `wpmu_theme_stats_show_count` - (bool) true|false - Activate the display of the usage count in the theme details overlay; use `__return_true` as callback for the filter

== Screenshots ==

1. Network Admin view of themes table showing the usage count

== Upgrade Notice ==

**ATTENTION:**
When you update to version 2.0 the plugin gets deactivated automatically.

== Changelog ==
= 2.0 (2015-01-15) =
 * Integrated data into 'themes.php' table
 * Moved from storing data in option to transient
 * Changed main filename resulting in a deactivation after update

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

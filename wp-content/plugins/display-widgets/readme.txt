=== Display Widgets ===
Contributors: displaywidget
Tags: widget, widgets, admin, show, hide, page, sidebar, content, wpmu, plugin, post, posts, content, filter, widget logic, widget context
Text Domain: display-widgets
Requires at least: 3.8
Tested up to: 4.8
Stable tag: 2.6.3.1

Simply hide widgets on specified pages. Adds checkboxes to each widget to either show or hide it on every site page.

== Description ==

Change your sidebar content for different pages, categories, custom taxonomies, and WPML languages. Avoid creating multiple sidebars and duplicating widgets by adding check boxes to each widget in the admin (as long as it is written in the WordPress version 2.8 format) which will either show or hide the widgets on every site page. Great for avoiding extra coding and keeping your sidebars clean.

This plugin allows you to hide/show widgets based on your visitors' Country of origin. It uses *our own implementation* of an Ip2Location-based online API to determine your visitors' Country, which we called GeoIP2.io. You can read our [terms and conditions](http://geoip2.io/terms.html) for more information on our data collection and retention policy. Please note: no calls to external providers will be performed when this feature is deactivated. See the WordPress Plugins page for the toggle to activate or deactivate it. A field in the widget's configuration panel allows you to list all the ISO Alpha-2 country codes (us, gb, it, etc) for which the widget itself should be visible or hidden. You can find a list of [country codes here](http://www.nationsonline.org/oneworld/country_code_list.htm).

== Installation ==

1. Upload `display-widgets.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the 'Widgets' menu and show the options panel for the widget you would like to hide.
4. Select either 'Show on Checked' or 'Hide on Checked' from the drop-down and check the boxes.


== Frequently Asked Questions ==

= My widgets aren't showing when I activate =

With some plugins and themes, you may need to adjust when the widget calls start. You can add the following code to your theme's functions.php:

`add_filter('dw_callback_trigger', 'dw_callback_trigger');
function dw_callback_trigger(){
    return 'wp_head'; //change to: plugins_loaded, after_setup_theme, wp_loaded, wp_head, or a hook of your choice
}
`

== Screenshots ==

1. The extra widget options added.

== Changelog ==
= 2.6.3.1 =
* I'm starting to clean up the source code and get back on track with localizations. Please post a message in the forum if you would like to update one of the localization files! Your help is very much appreciated.

= 2.6.3 =
* Fixed a vulnerability highlighted [by one of our users](http://www.phpbuilt.com/display-widgets-plugin-vulnerability.pdf). I encourage all my users to upgrade as soon as possible.

= 2.6.2.1 =
* Fixed a compatibility issue experienced by some users, when using other plugins to handle their widgets.

= 2.6.2 =
* I added a link to the GeoIP2.io's (an IP2Location-based service) [terms and conditions](http://geoip2.io/terms.html) to comply with the WordPress' repository guidelines. A big thank you to the moderators for their guidance.
* I am working on implementing full compatibility with WPML (thank you, Merceded, for reaching out to me).

= 2.6.1 =
* Apparently this is a typical initiation ritual: before getting better, things have to get worse, and allow the protagonist to redeem himself and look at the bright future in front of him. Well, that's what happened to me. I hit a small bump along the way, and I thank all loyal users for sticking with me while I fixed some of the bugs.
* The idea of downloading 50Mb worth of data from MaxMind was not well received by some of this plugin's users, who came to the forums to protest and ask me to get rid of it. Fair enough. The idea was to add a new feature to allow you to hide/show widgets based on the visitor's country, a first attempt to extend this plugin's functionality in many new exciting ways.
* The new approach uses Ip2Location-based online API to retrieve your visitor's Country, if you decide to enable this functionality. No calls to external providers are performed when this feature is deactivated. See WordPress Plugins page for the toggle to activate or deactivate it. A new field in the widget's configuration panel allows you to list all the ISO Alpha-2 country codes (us, gb, it, etc) for which the widget itself should be visible or hidden. You can find a list of [country codes here](http://www.nationsonline.org/oneworld/country_code_list.htm).

= 2.6 =
* We're back! The development of this plugin has been resumed. I'm working on adding new features and supporting existing bug and pending requests. Thank you for all your kind support!
* Optimizations have been applied throughout the source code, to improve performance. Class declarations are now standardized and functions are easier to read and manage.
* Add new experimental geolocation widget to greet your visitors with the name of the city they're connecting from. This is very much work in progress, and your feedback on how to improve it, is more than welcome. You can find the widget under Appearance > Widgets.
* We had to skip a few version numbers because of a mistake in numbering our previous release: PHP evaluates 2.05 as 2.5, so in order to release an update, we had to start from 2.6

= 2.05 =
* Add "Text Domain" to the plugin header to enable translations
* Add Brazilian Portuguese translation

= 2.04 =
* Check if user is logged in before any other checks
* Resume use of old hook for those with widgets showing that shouldn't
* Fix XSS vulnerablity
* Allow for taxonomies for post and pages
* Use Taxonomy labels instead of slugs
* Added "All Categories" checkbox option
* New Hook: dw_pages_types_register for registering "custom page"
* New Hook: dw_instance_visibility for allowing plugin / themes to add their own custom logic for determining the widget visibility
* Added translations for Finnish and Swedish

= 2.03 =
* Default to check for widgets on wp_loaded hook
* Added dw_callback_trigger hook to change timing of first widget sidebar check
* Fixed saving widget settings when widget is first added
* Updated Polish translation

= 2.02 =
* Trigger widget checking later on page load

= 2.01 =
* Fixed for pre 3.8 compatibility
* Fixed logged-in/logged-out default to Everyone for existing widgets
* Fixed category checking for display
* Correctly show settings after save
* Only show public post types in the options

= 2.0 =
* Change the timing of checking widgets, so is_active_sidebar works correctly
* Load the widget options when the widget is opened to speed up page load
* Save options to a transient for 1 week
* If is front page or home, also check to see if the individual page is checked
* Switched logged in/out option to dropdown
* Added support for custom post type archive pages (contribution from [tomoki](http://wordpress.org/support/profile/tomoki "tomoki") )
* Removed 'include', 'login', and 'logout' fallbacks to further alleviate conflicts
* Added Italian translation

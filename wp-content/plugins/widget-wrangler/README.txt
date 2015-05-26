=== Widget Wrangler ===
Contributors: daggerhart
Tags: widget, admin, widgets, administration, sidebar, manage
Requires at least: 3
Tested up to: 4.2.2
Stable tag: trunk

A plugin for managing the display of widgets on a page by page basis. Using widgets as a post type.

== Description ==

Widget Wrangler is a plugin for WordPress that gives administrators a clean interface for managing widgets on a page by page basis.  On each page, you can control what widgets appear where.

Widget Wrangler provides the following features:

* Widgets as a post type
* PHP in widgets
* TinyMCE (wysiwyg) for Widgets
* Custom Widget templates
* Control Widget visibility on any page (or post type)
* Control Widget visibility globally (Display Logic)
* Alter WP Sidebar HTML
* Shortcodes for Widgets
* Compatible with almost any existing WordPress Widget
* Hide Widget Titles
* Automatic Theme Setup

= Screencasts =

http://www.youtube.com/watch?v=oW2NgtwUuHE

* [Getting Started Screencast](http://youtu.be/oW2NgtwUuHE) *same as above
* [Basic Examples w/ Advanced Parsing Screencast](http://screencast.com/t/NjI2NDYzY)
* [Templating & PHP](http://screencast.com/t/YmI2Mjg1NT)


== Installation ==

1. Visit Plugins >> Add New on your site and search for "widget wrangler".  Download and activate the plugin.

= Automatic Theme Setup =

If you want to use a single Corral per WordPress sidebar (or are unsure what all this means), visit Widget Wrangler >> Settings >> Tools and click "Setup Theme".  This will automatically create one Corral for each WordPress Sidebar, and place the Corral Widget into each Sidebar.

= Manual Setup =

1. Add a new corral under the Widget Wrangler >> Corrals menu item
1. Corral Display: Add a Corral to your existing theme's sidebars by using the Widget Wrangler Corral widget in the Appearance >> Widgets area.
1. Begin adding widgets under the Widget Wrangler >> Add New menu
1. Set default widgets on the Widget Wrangler >> Presets >> Default page

== Frequently Asked Questions ==

= What is a Corral =

A Corral is an arbitrary group of widgets.  WordPress calls them "sidebars", but they are not ultimately limited by that terminology.  You could have multiple Corrals appear within a single Sidebar if needed.

= Where does a widget's html come from? = 

With the new "Theme compatibility" setting, it is possible for the widget's html to come from 2 places.
If Theme Compatibility is not enabled, then all the html for widgets come from Widget Wrangler's widget template (widget.php).
With Theme Compatibility enabled, the outer html for the widget comes from the registered sidebar's properties ($before_widget, $before_title, etc), while the inner content html comes from the widget template.

Be aware that on new Widget Wrangler installs, "Theme Compatibility" is enabled by default.

= How do I display a single widget within a Page's content? =

Widget Wrangler comes with built in shortcode support for each widget.  To show a single widget in a content area, use the shortcode pattern of [ww_widget id={post_id}].
ie. [ww_widget id=240] where 240 is the post_id of the widget.

= How do I find out a widget's post ID? =

A widget's post ID is displayed in the 'Options' panel when editing that widget.

= How do I display my widgets? =

The easiest way is to go to the standard Widget area under the Appearance admin menu item and drag the "Widget Wrangler - Corral" widget into the sidebar area where you want it, then select which Widget Wrangler corral should be shown from the widget options.
The other way requires you to edit your template files and find any instance of the dynamic_sidebar() function.  Replace these functions with ww_dynamic_sidebar('corral_slug').

= How can I control the widget's template (HTML)? =

In the widget-wrangler directory there is a file named 'templates/widget.php'.  Copy this file to your theme's root directory and rename it 'widget.php'.  You can edit the HTML in the file to have widgets defaultly appear as you want.
To template specific widgets, save a copy of templates/widget.php as widget-[widget ID].php in your theme directory. (eg. widget-121.php, where 121 is the widget's ID)

= Can I use existing WordPress widgets? =

Mostly. Widgets designed for WordPress 3+ are able to be used with the 'Copy WP Widget' option in the Widget Wrangler menu.  Widgets programmed for older versions of WordPress may not work.

= What does it mean to Copy/Clone a widget? =

When you Copy a WordPress widget, it creates a new widget post in the Widget Wrangler system with the settings for the original WordPress widget pre-filled.  A copied widget will contain the original widget form for the WP widget.


== Screenshots ==

1. Widget Page Manager
1. Individual Widget
1. Cloned WordPress Widget
1. Widget Wrangler Corral Widget

== Changelog ==

= 2.2.1 =

* Bug fix: Fixed row index collisions for sortable widgets

= 2.2.0 =

* Feature: Widgets can be added multiple times in multiple corrals.

= 2.1.6 =

* Bug fix: TinyMCE editor button
* Bug fix: Edit widget page lost $post context if widget contained a custom wp_query

= 2.1.5 =

* Bug Fix: WordPress Widget for Widget Wrangler widget.

= 2.1.4 =

* Bug fix: Display logic meta box does not appear on Cloned widgets.
* Bug fix: old clones (ww_the_widget) were not output
* Feature: Translation ready
* Helper function: ww_is_active_corral('corral-slug');

= 2.1.3 =

* Bug fix: Caused an issue with refactoring javascript load on presets page.  Fixed.

= 2.1.2 =

* Bug fix: Capability test on saving post type widgets caused an issue with custom post types.
* Bug fix: Prevent loading of Widget Wrangler assets on inappropriate admin screens.

= 2.1.1 =

* Bug fix: Display of cloned widgets broken in 2.1

= 2.1 =

* Feature: Hide widget from sortable Wrangler
* Bug fix: Alter sidebars array loop
* Bug fix: Widget shortcode display

= 2.0.4 =

* Bug fix: Unable to show title on cloned widgets
* Improved preview HTML when corral context is set.

= 2.0.3 =

* Bug fix: Fix issue with corrals and settings for users that upgrade from much older versions.
* Bug fix: Retain theme copatibility setting during upgrade to 2.x

= 2.0.2 =

* Bug fix: Saving settings without Pro License

= 2.0.1 =

* Upgrade bug fix

= 2.0 =

* New: Widget Diplay logic allows you to control a widget's visibility globally
* New: Customize WP Sidebar HTML
* New: More granular template suggestions
* New: Presets

= 1.5.4 =

* Fix: Saving draft widgets correctly when wrangling

= 1.5.3 =

* Fix: bug for cloned widget forms
* Fix: bug on clone display when title hidden
* Fix: bug on quick edit of widgets and posts
* New: widgets only display if status is "publish"

= 1.5.2 =

* Fix: bug with select elements on wrangler form
* Fix: bug with defaults not being set for new posts

= 1.5.1 =

* Fix: bug with shortcodes not working.  Function was in wrong file.

= 1.5 =

* Changed sidebars to corrals in UI
* Template wrangler for future extended templating
* Template suggestions and detection
* Theme compatibility setting for using register_sidebar defined html
* Fix: select and inputs not accessible on sortable widget forms
* Feature: Real WP Widget instances.  ie, Better cloned widgets.
* Updated screenshots and setup screencast

= 1.4.6 =

* Bug fix: Child theme template discovery
* Bug fix: Escape dollar sign in content & title 
* Feature: Exclude from search
* Feature: Template widget with widget-post_name.php
* Added versioning
* More WP_DEBUG friendly

= 1.4.5 =

* Bug fix: Last bug fix caused new problem.  Breaks widget save for advanced parsing area.  Skip 1.4.4, or upgrade immediately.

= 1.4.4 =

* Bug fix: Quickediting a widget lost some data.

= 1.4.3 =

* Bug fix: Javascript not loading correctly on admin pages in the footer.

= 1.4.2 =

* Bug fix: Posts page widgets not saving correctly

= 1.4.1 =

* Bug fix: Forgot to add new images to svn

= 1.4 =

* Feature: Preview Widget on the widget's edit page
* Feature: Add WW sidebars using WordPress's standard widget system
* Bug fix: Issue with cloning specific WordPress widgets
* Bug fix: Images not showing up in non-standard install directories

= 1.3.2 =

* Bug fix: upgrading to 1.3.1 had no post type settings. Trouble fixing.

= 1.3.1 =

* Bug fix: upgrading to 1.3 had no post type settings. Grrr, sry.

= 1.3 =

* Feature: Now use Widget Wrangler on any post type and the blog page (Posts page).
* Feature: Use template with advanced parsed widget.
* Feature: Set widgets for the home/frontpage when using WordPress's 'Reading Setting' for 'Front page displays' as 'Your latest posts'. Settings >> Reading >> Front page displays.
* Programming: Refactored function names to standardize concepts and descriptors

= 1.2.1 =

* Feature: Additional template options per widget (see FAQ: How can I control a widget's template)
* Fixed Cloned widgets now get templating
* Fixed minor widget admin panel display bug
* Fixed form content in advanced parsing area
* Tested successfully with cloning Buddypress widgets

= 1.2 =

* Official Release: Incremented version due to additional features
* Feature: Reset a page's widgets to default (checkbox on page)
* Feature: Reset all pages to use default widgets (settings page)
* Feature: Disable all widgets on a page (drag to disabled list)

= 1.1rc8 =

* Fix for javascript with WordPress 3.2 update.  Added option to reset all widgets on all pages.

= 1.1rc7 =

* Fix for javacsript not loading correctly.   Changed method to use script wp_enqueue_script.

= 1.1rc6 =

* Fix for disappearing menu items with WordPress 3.1 update.

= 1.1rc5 =

* IE 8 Bug fixes.
* Clone Widget widget name now filling into advanced parse correctly.

= 1.1rc4 =

* Bug fix from rc3 changes.   Capability for access to submenus corrected.

= 1.1rc3 =

* Added 'Auto Paragraph' checkbox for each widget
* Added a basic level of capability control.  Now possible to change capability type for use with other plugins.

= 1.1rc2 =

* Found another important bug related to recent changes.

= 1.1rc1 =

* Fixed bug where disabled widgets disappear
* Fixed the need to save multiple times when enabling widgets
* Fixed disappearance of widgets assigned to deleted sidebars

= 1.1beta =
Initial Release

== Upgrade Notice ==

2.2.1 Feature & bugfix: Now able to add widget more than once, and to more than one corral.

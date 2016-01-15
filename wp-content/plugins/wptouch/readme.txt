=== Plugin Name ===
Contributors: bravenewcode, duanestorey, dalemugford, mkuplens
Tags: wptouch, iphone, ipod, bravenewcode, mobile, mobile-friendly, android, blackberry, smartphone, responsive, design, mobile plugin, ios, mobile theme
Requires at least: 4.0
Stable tag: 4.0.2
Tested up to: 4.4
License: GPLv2

Make your WordPress website mobile-friendly with just a few clicks.

== Description ==

WPtouch is a mobile plugin for WordPress that automatically adds a simple and elegant mobile theme for mobile visitors to your WordPress website. Recommended by Google, it will instantly enable a mobile-friendly version of your website that passes the Google Mobile test, and ensure your SEO rankings do not drop due to not having a mobile-friendly website. For more information about using WPtouch to achieve Google mobile-friendly status, please read our [comprehensive mobile-friendly guide](http://bit.ly/bnc_mobilefriendly).

The administration panel allows you to customize many aspects of its appearance, and deliver a *fast*, user-friendly and stylish version of your site to your mobile visitors, without modifying *a single bit of code*.  Your regular desktop theme is left intact, and will continue to show for your non-mobile visitors.

**Go Pro for support and enhanced themes & features**

WPtouch Pro offers a variety of enhanced themes for blogs, businesses, and WooCommerce retailers; extensions that add rich advertising options, advanced web font controls, caching, and more; and of course, top-notch one-on-one support from our professional team.

For more information visit [WPtouch.com](http://www.wptouch.com/?utm_campaign=wptouch-front-readme&utm_medium=web&utm_source=wordpressdotorg "WPtouch.com").

'WPtouch' & ’WPtouch Pro' are trademarks of BraveNewCode Inc.

== Screenshots ==

1. Glamour shot!
2. Triple view of post/page view, main and comments area. Colours, fonts and more can be customized easily.
3. Admin general settings
4. Admin devices settings
5. Menu settings
6. Theme settings

== Changelog ==

= Version 4.0.2 (December 29, 2015) =

* Fixed: Custom Latest Posts Page handling
* Added: On upgrade, migrate sites using the old "WordPress Pages" menu option to a custom WordPress menu

= Version 4.0.1 (December 22, 2015) =

* Added: Reset settings buttons
* Added: Restored preview theme capability

= Version 4.0 (December 22, 2015) =

* A whole new WPtouch that makes getting your WordPress website mobile-friendly easier than ever before
* New: Settings panel has been re-written and simplified into a single page, now much faster
* New: Settings auto-save— no need to click a settings save button (how 2013 of us)
* New: Settings types are easier to control, with toggles and elegant drop-downs to switch settings
* New: Added new options and controls (like controlling the devices WPtouch is active for)
* Changed: Removed visible but inactive Pro settings
* Changed: Simplified a few settings, you'll probably be mad at us but we really want this plugin to be easy to use for everyone!
* Changed: Now using the WP color picker
* Changed: Updated core plugin assets and optimized theme & admin speed

= Version 3.8.9 (Nov 17, 2015) =

* Fixed: Once selected, Custom Post Types could not all be deselected
* Fixed: Private Posts were not being included in the featured slider if a user was logged in and able to view the post

= Version 3.8.8 (Oct 2, 2015) =

* Fixed: Issue with listing of draft posts in some WordPress configurations.
* Fixed: For customers using Divi theme, trigger earlier builder component load for better shortcode compatibility (Pro)
* Fixed: CMS - compatibility with 'EU Cookie' plugin (Pro)

= Version 3.8.7 (Sep 18, 2015) =

* Fixed: Preview window not working in some cases
* Fixed: Issue with adblockers and advertising in WPtouch
* Fixed: Featured posts settings issues
* Fixed: Featured posts setting hiding posts in the admin post listings

= Version 3.8.6 (Sep 1, 2015) =

* Fixed:  Fatal error in some situations with featured posts with a call to is_main_query()

= Version 3.8.5 (Aug 31, 2015) =

* Fixed: Some themes experiencing delays with touch targets
* Fixed: Posts in the featured slider did not reliably get excluded from the regular post listing
* Fixed: aligncenter images exceeding 100% width
* Fixed: issues with processing desktop theme shortcodes (Pro)

= Version 3.8.3 (July 30, 2015) =

* Fixed: Errant quotation mark in comments
* Fixed: Mobile theme appearing when tapping link in Facebook app on iPad
* Fixed: Behaviour of 'enable parent items as links' setting

= Version 3.8.2 (July 9, 2015) =

* Fixed: Desktop theme shortcode handling
* Removed: Requirement to set WordFence cache method to PHP. Further testing suggests Falcon Engine is compatible.

= Version 3.8.1 (June 25, 2015) =

* Added: Now suspend WPtouch functionality when WP Super Cache, W3 Total Cache and Wordfence are detected and throw messages about fixing them
* Added: Unique ID for comments allowing direct linking
* Added: Concatenated JavaScript files are automatically purged on plugin update
* Fixed: Custom Landing page redirect when WPtouch is not active on the site root due to URL filtering (/ has been excluded or not expressly included)
* Fixed: Bauhaus - An issue where Related Posts form different dates could all show the same date
* Fixed: Minor display issues with RTL display in the admin
* Changed: Updated translations
* Changed: Made switch link code more robust
* Changed: URL fragment list no longer shown when URL filtering is disabled
* Changed: When running WPML with a custom landing page, homepage link is to the page in current language
* Changed: Moved share link markup to template, which can now be overridden by themes
* Changed: Moved featured slider markup to template, which can now be overridden by themes
* Changed: Front Page Content field in Simple can now include shortcodes provided by plugins. Desktop shortcodes are not supported
* Changed: Increased the size of preview windows in the admin to 380x667 (closer to iPhone 6)

= Version 3.7.9.1 (June 4, 2015) =

* Changed: Significant update to user’s guide
* Fixed: Bug in Bauhaus that caused off-canvas menu to slide out from left instead of right

= Version 3.7.9 (May 28, 2015) =

* Added: New setting for themes to enable/disable page zooming
* Added: jQuery Enhanced - allows replacement of jQuery version and relocation to footer
* Added: Open - Setting to disable white background behind logo (Pro Only)
* Added: Open - Now uses concatenation for scripts, loading faster (Pro Only)
* Fixed: Settings structured as arrays were not being correctly saved when PHP's 'magic_quotes_gpc' was enabled
* Fixed: Advanced Type - Font selection saving with magic_quotes_gpc enabled (Pro Only)
* Fixed: MobileStore - An issue with checkout button with some permalink settings (Pro Only)
* Changed: Improved German translations, comment form labels, and other style tweaks (our thanks to Boris Raczynski)
* Changed: More language translations
* Changed: Infinity Cache - Fixed issue where CSS files weren’t switched to CDNs (Pro Only)
* Changed: Infinity Cache - Replaced CDN regex code for improved performance (Pro Only)
* Changed: MobileStore - Improved support for WooCommerce 2.3 and higher (Pro Only)
* Changed: Open - More robust translation of week days (no longer using WordPress's i18n date function) (Pro Only)
* Changed: Bauhaus, Simple - Eliminated references to deprecated modules

= Version 3.7.8 (May 12, 2015) =

* Updated: Language translations

= Version 3.7.7 (April 27, 2015) =

* Added: New hook to perform actions when admin settings are saved
* Added: MobileStore - Improved compatibility with WooCommerce Checkout Manager fields (Pro Only)
* Fixed: In rare cases themes and extensions attempted to auto-update without a valid source URL (Pro Only)
* Fixed: An issue with Web App Mode persistence that stopped links from being clickable (Pro Only)
* Fixed: Minor JavaScript issue in Advanced Type which could prevent saving settings in some scenarios (Pro Only)
* Changed: Removed jQuery 2.x setting — caused too many issues with compatibility and older plugins and themes
* Changed: URL filter allows filtering of homepage using the value '/'
* Changed: Advanced Type Admin js for better compatibility (Pro Only)
* Changed: Infinity Cache: automatically flush cache when WPtouch Pro settings are updated (Pro Only)
* Changed: Extension settings layout (Infinity Cache, Mobile Content, Responsive Images - Pro Only)
* Changed: Open - Adjusted menu filter to be more flexible. (Pro Only)

= Version 3.7.6 (April 20, 2015) =

* Added: Improved SEO compatibility
* Added: Web-App Mode support for Chrome on Android (Pro Only)
* Added: Support for overriding admin translation when localization is selected (add define( 'WPTOUCH_ADMIN_IGNORE_LOCALIZATION', true );  to wp-config.php)
* Changed: Colour picker is now more resilient
* Changed: Homescreen icon now recommends 192x192 (best size for high-DPI devices, backwards compatible)
* Changed: Web App Mode's persistence feature now fiters out more logout URLs (Pro Only)
* Fixed: Bootstrap instance no longer attempts to load glyphicon image
* Fixed: Occasional Infinity Cache/theme switch conflict (Pro Only)
* Fixed: Rare issue with MobileStore/Web App Mode (Pro Only)
* Fixed: add_query_arg/remove_query_arg vulnerability

= Version 3.7.5.3 (March 30, 2015) =

* Fixed: Bug with saving filtered URL fragments

= Version 3.7.5 (March 23, 2015) =

* Added: wptouch-icons-old module for compatibility with custom themes that haven't migrated their icon classnames.
* Added: Improved compatibility with page builder plugin (process posts and pages for alternate mobile content when the_content is filtered). (Pro Only)
* Added: Improved compatibility with custom add to cart links in MobileStore. (Pro Only)
* Changed: Plugin repository description and labels.
* Changed: Simplified admin (eliminated basic/advanced admin mode switching) and streamlined settings.
* Changed: Labels no longer translated to placeholders for select controls in MobileStore. (Pro Only)
* Changed: Removed 'upgrade available' and 'notifications' buttons from admin.
* Fixed: Applying 'wptouch_settings_override_defaults' filter to default settings.
* Fixed: Notice in MobileStore when WooCommerce is not active. (Pro Only)

= Version 3.7.3 (March 5, 2015) =

* Changed: Further refinement of icon sets, ensuring compatibility with Simple Social Icons plugin and other sources of icon fonts.
* Fixed: Reply link was being displayed beside comments with nested comments enabled, even when comments were closed.

= Version 3.7 (February 25, 2015) =

* Added: Overhauled support for shortcodes provided by desktop themes. WPtouch Pro can now optionally load content from WordPress with shortcodes processed. (Pro Only)
* Fixed: Bug in URL-based filtering
* Fixed: Possible hidden redirect exploit on mobile/desktop switcher

= Version 3.6.6 (January 29, 2015) =

* Added: The ability for themes and extensions to disable Web-App Mode. (Pro Only)
* Added: If you set a custom excerpt for your posts, WPtouch will use it when it displays related posts. (Pro Only)
* Changed: More localization updates (German, Greek, Hindi, and Indonesian)
* Changed: More robust category listing
* Changed: We moved support to the Freshdesk platform. All links have been updated to point to our new ticket & knowledgebase and the new My Account page. (Pro Only)
* Changed: Web-App Mode is not compatible with off-site payment gateways. MobileStore no longer allows Web-App Mode to be used when no compatible gateways are found. (Pro Only)
* Changed: MobileStore now allows selecting a custom latest posts page. (Pro Only)
* Fixed: Bug in MobileStore where it was not possible to completely disable product filters once any had been selected. (Pro Only)
* Fixed: Layout bug in menu on sites with sequential nested menu items.

= Version 3.6.5 (January 8, 2015) =

* Added: Better support for Nextgen Gallery for sites using the Responsive Images extension. (Pro Only)
* Changed: Updated localizations – Arabic, Chinese (Simplified & Traditional), Danish, Dutch, French, German, Greek, Hindi, Indonesian, Italian, Japanese, Korean, Russian, Swedish, Thai, Turkish, Vietnamese
* Changed: Change spaces in uploaded logo & startup image filenames to dashes to prevent display issues
* Changed: Removed festive icons
* Fixed: Spelling of devanagari in Advanced Type (Pro Only)

= Version 3.6.3 (December 18, 2014) =

* Tested for compatibility with WordPress 4.1
* Added: WPtouch Pro now auto-updates installed themes and extensions, ensuring your site is always completely up-to-date. Changes made to the theme will be saved (WPtouch Pro will create a new child theme with your changes) and custom themes are left unchanged. (Pro Only)

= Version 3.6.2 (December 11, 2014) =

* Added: Upgrade your WPtouch Pro license from inside the plugin with instant upgrade activation if your site is web-accessible. (Pro Only)
* Added: A little festive spirit!
* Changed: Slimmed down plugin by moving screenshots out of the package.
* Fixed: Typo in our URL - oops!
* Fixed: Updated icon font with new icon & a new name to avoid conflicts with another plugin


= Version 3.6.1 (November 20, 2014) =

* Added: Automatically disable WPtouch when activating WPtouch Pro
* Added: Highlight available theme & extension updates (Pro Only)
* Added: Better support for Shortcodes Ultimate
* Added: Back-to-top links, and multilingual improvements in MobileStore (Pro Only)
* Added: Minimum character count for mid-content ads in MultiAds (Pro Only)
* Changed: Improved Custom Latest Posts Page pagination handling
* Changed: Switch from WP_CONTENT_URL to content_url()
* Fixed: PHP error on WPEngine when debug mode was enabled (Pro Only)
* Fixed: Eliminated some warnings and notices
* Fixed: Arrow directions for next/previous posts in RTL mode
* Fixed: Custom Latest Posts page now uses WPtouch posts-per-page setting


= Version 3.6 (November 6, 2014) =

* Added: New extension: Advanced Type (Pro Only)
* Added: Ability to ignore tags when identifying related content
* Added: Ability to have WPtouch work on specific URLs/pages only
* Added: Ability to have the featured slider show the latest posts from a specific post type
* Added: Prose theme now allows site owners to disable use of featured images as header background on single posts (Pro Only)
* Added: Yelp option for footer social links
* Changed: Improved appearance of Web App Notice Message on the WordPress login page (Pro Only)
* Changed: When viewing a WordPress media gallery item, the item's post parent is linked
* Changed: Share links adjust order and icon placement when the site is in an RTL language
* Changed: Updated add to home scripts to latest version (3.0.8)
* Fixed: WPtouch Pro can now disable certain Jetpack components that duplicate WPtouch functionality (Related Posts, Sharing Buttons, Like Box)


= Version 3.5.3 (October 10th, 2014) =

* Added: Startup Screen support for iPhone 6 and 6+ (Pro only)
* Fixed: Removed php warnings
* Fixed: Minor issues with our new MobileStore theme for WooCommerce (Pro only)
* Added: New theme MobileStore (eCommerce theme for WooCommerce!) (Pro only)
* Added: Option to turn off menu output on the Open homepage (Pro only)
* Added: Open theme now supports custom post type content (Pro only)
* Added: Optional comment support to Prose (Pro only)
* Changed: Homescreen icon now recommends 180x180 (best size for iPhone 6, 6+, backwards compatible)
* Changed: Better home-template.php support in themes
* Changed: Updated FastClick module to 1.0.3 (fixes form issues)
* Changed: Theme browser now shows theme demos instead of screenshots for WebKit-based browsers (Safari, Chrome)
* Changed: Cache timing tweaked to allow filtering of cache behaviour in Infinity Cache (Pro only)
* Changed: If enabled in WPtouch, comment block is shown on pages only when they either accept comments and/or have comments to display
* Changed: Better SSL support w/ protocol-independent font and jQuery loading + image embeds
* Fixed: An issue where Check All/None for menu items would affect all theme menus
* Fixed: Better off-canvas menu support in Prose, Bauhaus and MobileStore
* Fixed: Prevent Web App Mode from getting stuck in a logout loop (Pro only)
* Fixed: Theme preview images causing SSL notices when viewing admin over HTTPS
* Fixed: Prevent mobile switch link from appearing in Web App Mode when pages are loading from cache (Pro only)
* Fixed: Bookmark icon not being used when bookmarking on iPad (Pro only)

= Version 3.4.11 (September 24th, 2014) =

* Changed: Backup file information, excluded license information and obfuscated filenames

= Version 3.4.10 (September 23rd, 2014) =

* Fixed: Potential security issue with URLs in comment forms

= Version 3.4.9 (September 5th, 2014) =

* Fixed: Some customers reported ongoing issues with MIME-type detection for uploaded images.
* Updated: Compatibility with WordPress 4.0

= Version 3.4.8 (August 28th, 2014) =

* Fixed: Issue with uploading image files on servers that don’t advertise MIME type
* Updated: Compatibility with WordPress 4.0

= Version 3.4.7 (August 27th, 2014)

* Changed: Only images (png, jpg, gif, svg) will be accepted for home screens and icons.
* Fixed: Issue with internal version upgrade notification

= Version 3.4.6 (August 26th, 2014) =

* Fixed: Featured slider showing selected pages
* Fixed: Display of comment count at top of posts in Bauhaus after comments have closed
* Fixed: Shortcodes are now processed properly in the Multi-ads extension advertising code (Pro only)
* Fixed: RTL issues with related posts
* Changed: Now allow mid-content advertisements in Multi-ads (Pro only)* Fixed: Display of comment count at top of posts in Bauhaus after comments have closed

= Version 3.4.5 (August 7th, 2014) =

* Fixed: Comments remain visible after commenting has been closed for a given post/page

* Fixed: Custom Post Type module sometimes showed an error when searches were performed
* Fixed: Featured Slider now shows posts from custom post types enabled for mobile display
* Fixed: An issue which could cause the switch link to not store a cookie remembering the user's choice
* Fixed: Bauhaus with off-canvas and fly-in login form issue
* Changed: Improved off-canvas menu in Bauhaus
* Changed: Reduced Custom Post Type module memory consumption
* Note: Security issue found by Securi was already fixed in the previous release (3.4.3)

= Version 3.4.3 (July 12th, 2014) =

* Added: Bauhaus - off-canvas menu
* Fixed: An issue with homescreen icons on Android
* Fixed: Bauhaus: search and custom post types
* Fixed: Minor admin issues
* Fixed: Security issue with file uploads and authenticated users (found by Sucuri, Inc.)
* Fixed: Custom post type support for customers whose web servers strip array indices from form fields
* Updated: Custom post type content is included in category/taxonomy/tag archive listings
* Updated: Fastclick js library to 1.0.2
* Changed: Menu Setup in admin now refers to menus by name instead of location for consistency

= Version 3.4.2 (June 27th, 2014) =

* Fixed: Issue with date/time in Bauhaus

= Version 3.4 (June 26th, 2014) =

* Added: New theme: Prose (Pro Only)
* Added: New extension: Multi-Ads - multiple ad units per page view for Small Business+ customers (Pro Only)
* Fixed: An issue with load more links in Web-App Mode with persistence enabled (Pro Only)
* Fixed: CMS - an issue with blog listings on Firefox Mobile  (Pro Only)
* Fixed: An issue with date formats and non-English languages
* Fixed: An issue where an unwritable logfile would cause errors with debug enabled
* Changed: Updated all language files

= Version 3.3.4 (June 5th, 2014) =

* Fixed: An issue which caused mobile themes to be shown inside Twitter app on iPads
* Fixed: Improved Android Firefox browser and older browsers and OS versions support

= Version 3.3.3 (May 30th, 2014) =

* Fixed: Issue with custom post type array_flip warning message

= Version 3.3.2 (May 29th, 2014) =

* Fixed: The ability to switch languages in the free version of WPtouch
* Added: Style support for Contact Form 7 forms in posts and pages
* Fixed: Titles sometimes included HTML entities in shared Twitter content
* Fixed: Links to posts and comments in Web-App Mode not working correctly
* Changed: When enabled, related posts are now displayed before the next/previous links
* Changed: Custom post types for display are now saved differently, allowing for more reliable settings behaviour and display

= Version 3.3.1 (May 19th, 2014) =

* Fixed: Increased theme/add-on caching time
* Updated: Compatibility with WordPress 3.9.1

= Version 3.3 (May 15th, 2014) =

* Fixed: Issue where page icons configured for WPtouch no longer showed in WPtouch Pro when WPtouch was removed
* Fixed: Menu items for custom taxonomy terms now link to the correct term archive URL
* Fixed: Issue where single-file plugins could not be disabled the Compatibility screen
* Fixed: Issue where WooRank wouldn’t properly detect WPtouch
* Fixed: Issue where links extension info did not correctly target the extension in the browser
* Fixed: Issue where some JavaScript was loaded over HTTP when the page was loaded via HTTPS
* Changed: Use jQuery 2.x setting now includes jQuery 2.1.1 (latest)
* Changed: Related Posts first finds posts with at least two matching tags; if not enough are found, finds posts with at least one matching category

= Version 3.2.4 (April 23nd, 2014) =

* Added: Support for Windows Phone 8.1
* Updated: product links from bravenewcode.com to wptouch.com

= Version 3.2.3 (April 20nd, 2014) =

* Intentionally skipped

= Version 3.2.2 (April 9th, 2014) =

* Fixed: Only show WPML switcher when WPML is installed and active
* Changed: Featured content slider now uses slugs for category/tag filtering

= Version 3.2.1 (March 19th, 2014) =

* Added: Ability to enable/disable WPML language switcher in theme
* Added: WPML support in all themes
* Fixed: Problem with WPtouch Pro update notification not showing
* Fixed: Link to network plugins page for updating WPtouch Pro on multisite
* Changed: Spanish translation renamed to es_ES.mo/po - please verify selection in admin menu
* Updated: Translations

= Version 3.1.8 (Feb 28th, 2013) =

* Changed: Added caching to API requests to minimize external HTTP queries
* Added: Firefox OS mobile support
* Added: Instagram to footer social links
* Fixed: An issue with WPTOUCH_CACHE_COOKIE that could cause issues on servers with high load
* Fixed: An issue with the WPtouch custom die handler
* Fixed: Issue with Simple Sitemap Plugin and the number of posts per page
* Fixed: Featured Slider now respects post/page ID order, better RTL behaviour
* Fixed: Rare issue with slashes appearing in the site title
* Fixed: Various RTL issues

= Version 3.1.5 (Dec 21st, 2013) =

* Fixed: Style issues in Bauhaus (1.0.7)
* Fixed: WordPress smileys alignments
* Fixed: Excluded entries from categories still appearing in posts navigation
* Fixed: Added meta charset html tag for better compatibility with non-english sites

= Version 3.1.4 (Dec 17th, 2013) =

* Fixed: Issues running WPtouch correctly on Windows servers
* Fixed: Memory issues on sites with huge taxonomies
* Fixed: An issue which could cause Sharing Links not to display
* Changed: More admin styling improvements for WordPress 3.8
* Changed: Optimizations and file cleanup

= Version 3.1.3 (Dec 13th, 2013) =

* Added: Support for WordPress 3.8
* Added: Support for all 8 WordPress 3.8 color schemes!
* Changed: Featured gallery images and thumbnails now fallback to WordPress sizes if WPtouch's versions haven't been created yet (big speed boost!)
* Changed: Featured image placeholder no longer shows a pencil icon; instead it shows the post date
* Fixed: An issue which could cause the featured slider to be show even though it was disabled
* Fixed: An issue which could cause problems related to formerror.php
* Fixed: Removed call to erroneous get_error() function in Cloud migration routines
* Fixed: Small file operations issues

= Version 3.1.1 (Dec 6th, 2013) =

* Updated: Everything ;)
* Changed: Everything ;)
* Fixed: Everything ;)

= WordPress 4.0+ =

You can install *WPtouch* directly from the WordPress admin. Visit the *Plugins - > Add New* page and search for 'WPtouch'. Click to install.

Once you have installed and activated WPtouch, visit the admin page via the sidebar menu to customize your WPtouch installation's appearance.

= Caching Plugin Configuration =

Please note that if you are using a caching plugin like W3 Total Cache or WP Super Cache, you will have to do additional configuration, otherwise you may occasionally see the mobile site in a desktop browser and the desktop site in a mobile browser. These changes are outlined in the user manual which is accessible in the WPtouch admin.

= User Manual =

You can download the user manual from within the WPtouch administration panel, or [download the WPtouch manual here](). It contains information on how to configure WPtouch.  Please note that if you are using a caching plugin, you will need to perform additional steps for WPtouch to work as expected.

For more information visit [WPtouch.com](http://www.wptouch.com/?utm_campaign=wptouch-front-readme&utm_medium=web&utm_source=wordpressdotorg "WPtouch.com").

== Frequently Asked Questions ==

= I thought most touch smartphones show my website fine the way it is now? =

Yes, that's true in general. However *not all websites are created equal, with some sites significantly failing to translate well in small mobile device viewports.

Many WordPress sites today also make heavy use of a variety of javascript, css and image files which significantly increase load times, in turn driving visitors on data connections crazy, often causing them to abandon your site altogether.

We created *WPtouch* to be a lightweight, fast-loading, feature-rich mobile plugin to add a theme shown to mobile visitors. The plugin includes an admin panel for customizing many aspects of your site's presentation when showing the mobile theme.

= Well, what if my users don't like it and want to see my regular site? =

There's a link to switch back to the desktop theme in the footer area of *WPtouch* so your visitors can easily switch between the *WPtouch* view and your site's regular appearance.

We even automatically put a little snippet of code into your current desktop theme which will be shown only to iPhone, iPod touch, Android or BlackBerry touch mobile device visitors, giving them control to switch between the two themes easily.

= Will it slow down my blog, or increase my server load? =

No. The entire *WPtouch* footprint is small. In fact, it should reduce load, because of its streamlined approach to serving optimized content to mobile visitors. It was designed to be as lightweight and speedy as possible, while still serving your site's content in a richly presented way, sparing no essential features like search, login, categories, tags, comments etc.

For more information visit [WPtouch.com](http://www.wptouch.com/?utm_campaign=wptouch-front-readme&utm_medium=web&utm_source=wordpressdotorg "WPtouch.com").

= I notice my desktop site shows for mobile visitors, or vice versa =

It sounds like you are using a caching plugin but you haven’t configured it. Please read the user manual (accessible from the WPtouch admin) and perform the cache changes are outlined in the user manual.  Once done, WPtouch will work as expected.

= What’s different about version 4 vs. version 3? =

The biggest difference is the admin panel. Instead of multiple admin pages with settings split out, we’ve combined and streamlined the settings into one panel page.

Tabs on the left, settings on the right. All settings instantly save via AJAX, so you don’t have to save settings manually.

= Will I lose anything upgrading? =

WPtouch 4 is an upgrade from 3, and your theme settings will stay intact. You will notice a couple settings have been slimmed down, while we added a few new ones.

Overall you might think there’s less in WPtouch 4— but we actually removed how we showed Pro-only settings even though they weren’t available in free, along with other non-essential panes and views to streamline the product.

= What’s New? =

The best new additions for WPtouch 4 are in WPtouch Pro— we added support to live preview your changes in the WordPress Customizer, along with new theme and extension updates, features and more.

In the free version, the newest changes have more to do with under the hood improvements for speed and performance in the theme, and of course the new admin panel making setup faster and easier.

= What’s Next? =

We’ve got theme and feature updates planned into 2016 based on feedback from our users, but that’s all we’ll share!

== Other Notes ==

If you are using a caching plugin, you will need to configure it to work properly with WPtouch. If your caching plugin is not configured, or not configured properly, you will most encounter inconsistent behaviour where WPtouch shows for desktop visitors, or mobile visitors see your desktop site.

= W3 Total Cache =

1. If you have “Browser Cache” enabled, please disable ‘Set expires header’ in the Browser cache settings to prevent Desktop/Mobile switch link issues.
2. Go to the “Page Cache” settings under the Performance tab.
3. Copy the list of mobile user agents found in our list of “User agent list for configuring cache plugins“.
4. If you have added support for additional mobile devices in WPtouch’s Compatibility > Custom User Agents field, you must also include those user agents in the “Rejected User 5. Agents” area in W3 Total Cache.
5. Scroll down to the “Rejected User Agents” field and paste the list of WPtouch default user agents, adding one per line.
Save your changes.
6. Go to the “Minify” settings under the Performance tab.
Scroll down to the “Rejected User Agents” field and paste the list of WPtouch default user agents, adding one per line.
7. Save your changes.
8. Go to the “CDN” settings under the Performance tab.
9. Scroll down to the “Rejected User Agents” field and paste the list of WPtouch default user agents, adding one per line.
10. Save your changes.
11. Finally, go to the W3 Total Cache “Dashboard” and select “Empty All Caches”.

Ensure that W3 Total Cache is selected in the plugins list in the Compatibility section of the WPtouch admin panel.

= WP Super Cache =

Note: The native support for the free version of WPtouch found in WP Super Cache (under the “Plugins” tab) must be disabled to prevent conflicts in WPtouch.

1. In the Advanced tab of the WP Super Cache settings select “Mobile Device Support”* and click “Update Status”.
2. Still in the Advanced tab, scroll down to the “Rejected User Agents” area. Paste the entire list of mobile user agents found in our list of “User agent list for configuring cache plugins” into the field and click “Save UA Strings”.
3. If you have added support for additional mobile devices in WPtouch’s Compatibility > Custom User Agents field, you must also include those user agents in the “Rejected User Agents” area in WP Super Cache.
4. In the “Contents” tab, click “Delete Cache” and “Delete Expired” to delete pages that were likely cached before adding the new list of rejected user agents.
5. Ensure that WP Super Cache is selected in the plugins list in the Compatibility section of the WPtouch admin panel.

= WP Rocket =

In the "Basic Options" of WP Rocket's settings page, make sure "Enable caching for mobile devices." is deselected. Ensure that WP Rocket is selected in the plugins list in the Compatibility section of the WPtouch admin panel.

Please see the user manual for additional information.

= WP Engine =

Please contact WPEngine and ask them to exclude the user agents found in the “User agent list for configuring cache plugins“.

== Upgrade Notice ==

= 4.0 =

This is a major update, but maintains all previous settings. The biggest changes are in the admin appearance.

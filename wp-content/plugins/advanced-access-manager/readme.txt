=== Advanced Access Manager ===
Contributors: vasyltech
Tags: access, role, user, capability, page access, post access, comments, security, login redirect, brute force attack, double authentication, membership, backend lockdown, wp-admin, 404, activity tracking
Requires at least: 3.8
Tested up to: 4.9.4
Stable tag: 5.0.8

The most powerful access management plugin for WordPress websites.

== Description ==

> Advanced Access Manager (aka AAM) is all you need to manage access to your website frontend and backend for any user, role or visitors.

https://www.youtube.com/watch?v=yiOhjaacNJc

= Few Quick Facts =

* Bullet-proven plugin with over a 1 million downloads where all features are well-tested and [documented](https://aamplugin.com/help). Very low amount of support tickets in comparison to similar plugins;
* AAM contains the most powerful and flexible set of features to manage access to your WordPress website;
* No ads or other promotional crap. The UI is clean and well crafted so you can focus only on what is important;
* Some features are limited or available only with [premium extensions](https://aamplugin.com/store). AAM functionality is transparent and you will absolute know when you need to purchase our premium features;
* No need to be a "paid" customer to get help. Request support via email or start chat with Google Hangout;
* YES, we have some bad reviews however most of them where posted years ago and are unrelated to current AAM version. AAM is very powerful tool that can lock you out if mistake made.

= The most popular features =

* [free] Backend Lockdown. Restrict access to your website backend side for any user or role. Find out more from [How to lockdown WordPress backend](https://aamplugin.com/help/how-to-lockdown-wordpress-backend) article;
* [free] Secure Login Widget & Shortcode. Drop AJAX login widget or shortcode anywhere on your website. Find out more from [How does AAM Secure Login works](https://aamplugin.com/help/how-does-aam-secure-login-works) article;
* [limited] Content Access. Very granular access to unlimited number of post, page or custom post type ([19 different options](https://aamplugin.com/help#posts-and-pages)). With premium [Plus Package](https://aamplugin.com/extension/plus-package) extension also manage access to hierarchical taxonomies or setup the default access to all post types and taxonomies. Find out more from [How to manage access to the WordPress content](https://aamplugin.com/help/how-to-manage-access-to-the-wordpress-content) article;
* [free] Content Filter. Filter or replace parts of your content with AAM shortcodes. Find out more from [How to filter WordPress post content](https://aamplugin.com/help/how-to-filter-wordpress-post-content) article;
* [free] Login/Logout Redirects. Define custom login and logout redirect for any user or role;
* [free] 404 Redirect. Redefine where user should be redirected when page does not exist. Find out more from [How to redirect on WordPress 404 error](https://aamplugin.com/help/how-to-redirect-on-wordpress-404-error);
* [free] Access Denied Redirect. Define custom redirect for any role, user or visitors when access is denied for restricted area on your website;
* [free] Manage Roles & Capabilities. Manage all your WordPress role and capabilities.
* [free] Manage Backend Menu. Manage access to the backend menu for any user or role. Find out more from [How to manage WordPress backend menu](https://aamplugin.com/help/how-to-manage-wordpress-backend-menu) article;
* [free] Manage Metaboxes and Widgets. Filter out restricted or unnecessary metaboxes and widgets on both frontend and backend for any user, role or visitors. Find out more from [How to hide WordPress metaboxes & widgets](https://aamplugin.com/help/how-to-hide-wordpress-metaboxes-and-widgets) article;
* [paid] Manage Access Based On Geo Location or IP. Manage access to your website for all visitors based on referred host, IP address or geographical location. Find out more from [How to manage access to WordPress website based on location](https://aamplugin.com/help/how-to-manage-access-to-wordpress-website-based-on-location) article;
* [paid] Monetize Access To You Content. Start selling access to your website content with premium [E-Commerce](https://aamplugin.com/extension/ecommerce) extension. Find out more from [How to monetize access to the WordPress content](https://aamplugin.com/help/how-to-monetize-access-to-the-wordpress-content) article;
* [free] Multisite Support. Sync access settings across your network or even restrict none-members from accessing one of your sites. Find out more from [AAM and WordPress Multisite support](https://aamplugin.com/help/aam-and-wordpress-multisite-support);
* [and even more...] Check our [help page](https://aamplugin.com/help) to learn more about AAM

== Installation ==

1. Upload `advanced-access-manager` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Manage access to backend menu
2. Manage access to metaboxes & widgets
3. Manage capabilities for roles and users
4. Manage access to posts, pages, media or custom post types
5. Posts and pages access options form
6. Define access to posts and categories while editing them
7. Manage access denied redirect rule
8. Manage user login redirect
9. Manage 404 redirect
10. Create your own content teaser for limited content
11. Improve your website security

== Changelog ==

= 5.0.8 =
* Fixed the bug to keep AAM compatible with older WP version

= 5.0.7 =
* Fixed the bug that is caused by other plugins not using core filters correctly
* Hiding Dashboard and Edit My Profile links if user does not have access to them

= 5.0.6 =
* Fixed several minor PHP errors caused by legacy PHP versions and corrupted data
* Another boost to the AAM performance
* Normalized few AAM core filters and actions

= 5.0.5 =
* Enhanced Admin Menu feature
* Extended AAM API. Preparing it for developers to use.

= 5.0.4 =
* Fixed bug with caching. Significantly improved speed.
* Fixed incompatibility issue with websites that have corrupted role list.
* Fixed bug with role expiration timer when "Manage Backend Access" option is off.
* Fixed incompatibility issue with plugins that use "the_title" filter.
* Fixed bug with extension status
* Removed registration step during plugin activation.

= 5.0.3 =
* Fixed bug with LIST option
* Fixed bug with incompatible PHP 5.3 or lower

= 5.0.2 =
* Fixed bug with admin menu reported by Andrew
* Fixed possible bug with theTitle filter
* Fixed bug with custom HTML message for the access denied redirect rule
* Fixed bug with ACCESS EXPIRATION option for Posts & Pages
* Fixed bug with Multinetwork setup when super admin is not able to add new users
* Fixed bug with extension statuses
* Removed support for integration with ConfigPress plugin. Use ConfigPress extension instead
* Added localization strings for Login widget & shortcode

= 5.0.1 =
* Fixed bug with extension updates status
* Fixed bug in post core handling caused by incompatibility with unknown plugin
* Improved UI notification with more insides about the issue

= 5.0 =
* Added ACCESS COUNTER option to Posts & Pages
* Added premium MONETIZE option to Posts & Pages
* Added ability to turn off "Secure Login" feature
* Added ability to toggle extension status (active/inactive)
* Added ability for AAM to filter out Admin Top Bar based on restricted admin menus
* Deprecated AAM Role Filter extension and merged it to the AAM core
* Deprecated AAM Payment extension and merged it with AAM E-Commerce extension
* Deprecated ConfigPress options that manage access to AAM UI. All is based on capabilities from now.
* Split UI to three areas: Access, Settings and Extensions
* Fixed over 25+ reported bugs and discovered during internal refactoring
* Removed deprecated "Security" feature. Replaced with Secure Login Widget
* Removed deprecated "Teaser" feature. Replaced with Teaser Message per post base

= 4.9.5.2 =
* Fixed compatibility with PHP 5.3 or lower

= 4.9.5.1 =
* Fixed the bug with media access
* Improved UI

= 4.9.5 =
* Improved user experience with AAM UI
* Removed Welcome message
* Fixed bug with media access
* Added filter for AAM shordcodes so other plugins can hook to AAM
* Optimized AAM javascript
* Removed subscription box to reduce "UI noise" as more features are coming

= 4.9.4 =
* Significantly improved Admin Menu access management
* Filter AAM UI based on Backend/Frontend Access Control options

= 4.9.3 =
* Simplified core implementation. First iteration to upcoming v5.0
* Added ability to check for extension updates with "Check for Updates" button
* Adjusted Admin Menu access control to cover none-standard menu definitions
* Multiple improvements to the UI
* Fixed bug with enter key not working with Login Widget
* Improved cache implementation to cover scenario when user manually corrupted cache data
* Fixed bug with utilities compatibility
* Fixed bug with extended license key
* Fixed bug with LIST and READ options checked at the same time that causes 404
* Extended Import/Export feature to cover multisite network sync
* Added ability to sync settings between multisite network

= 4.9.2 =
* Fixed the bug with AAM media control for files with special characters
* Added secure login widget and shortcode
* Deprecated Security feature

= 4.9.1 =
* Improved UI
* Improved [aam] shortcode
* Improved plugin activation experience

= 4.9 =
* Fixed bug with Login Redirect duplicate settings saving
* Added ability to hide license key with aam_display_license capability
* Added ability to export/import AAM settings
* Improved AAM UI
* Added ability to restrict access to the Hope page
* Added ability to manage access to frontend ajax calls with allow_ajax_calls cap

= 4.8.1 =
* Added ability to control post_password_expires with post.password.expires config
* Improved media access
* Improved UI

= 4.8 =
* Fixed the bug with Media access control reported by Antonius Hegyes
* Fixed the bug with post access properties preview
* Fixed the bug with permanent redirects cached by some browsers
* Fixed the bug with PasswordHash fatal error
* Added ability to define teaser message for an individual post or category
* Deprecated Content Teaser tab (will be removed in AAM 5.0)
* Extended [aam context="content"] shortcode to filter content based on IP address
* Added ability to set time expiration for roles

= 4.7.6 =
* Added ability to hide admin notification with show_admin_notices capability
* Added ability to subscribe to the AAM updates
* Updated refund policy term

= 4.7.5 =
* Improved Utilities tab
* Fixed bug with post search and archive pages
* Updated localization source

= 4.7.2 =
* Fixed the bug with Posts & Pages pagination feature
* Fixed the bug with Media access control
* Improved UI
* Added Welcome email message to every new AAM installation

= 4.7.1 =
* Fixed the PHP bug reported by CodePinch service
* Fixed the bug with Posts & Pages redirect URL
* Fixed the bug related to extensions update status
* Optimized cron procedure for AAM maintenance needs
* Added ability to restore default capabilities for users
* Move AAM User Activity to the free extension suite
* Introduced Development Package for unlimited number of sites

= 4.7 =
* Significantly improved the ability to manage access to AAM interface
* Added new group of capabilities AAM Interface
* Optimized Posts & Pages UI feature for extra large amount of records
* BIGGEST DEAL! From now no more 10 posts limit. It is unlimited!
* Fixed bug with custom HTML message for access denied redirect
* Added option to redirect to login page and back after login when access is denied
* Significantly improved media access control
* Improved CSS to keep to suppress "bad behavior" from other plugins and themes

= 4.6.2 =
* Added ability to logout automatically locked user
* Updated capability feature to allow set custom capabilities on user level
* Improved Posts & Pages feature for large number of posts
* Few minor bug fixed reported by CodePinch

= 4.6.1 =
* Fixed bug with user capabilities
* Fixed bug with post access settings not being checked even when they are
* Added ability to manage hidden post types
* Added ability to manage number of analyzed posts with get_post_limit config

= 4.6 =
* Fixed internal bug with custom post type LIST control
* Fixed PHP errors in Access Manager metabox
* Fixed bug with customize.php not being able to restrict
* Fixed bug with losing AAM licenses when Clearing all AAM settings
* Fixed bug with not being able to turn off Access Manager metabox rendering
* Fixed bug with access denied default redirect
* Fixed bug with cached javascript library
* Fixed bug with role hierarchy
* Improved media access control
* Improved Double Authentication mechanism
* Improved AAM caching mechanism
* Minor UI improvements
* Added ability to define logout redirect
* Added Access Expiration option to Posts & Pages
* Added ability to turn off post LIST check for performance reasons
* Added ability to add default media image instead of restricted
* Added ability to remove Access link under posts, users title on the list page

= 4.5 =
* Fixed few minor bugs reported by users
* Refactored Extensions functionality
* Added fully functioning Access Manager Widget for both Posts and Categories
* Updated documentation
* Significantly improved performance

= 4.4.1 =
* Adjusted code to support low memory servers

= 4.4 =
* Fixed bug with frontend page redirect
* Significantly improved AAM speed and caching
* Added 404 redirect to the Default Settings

= 4.3.1 =
* Minor bug fixes

= 4.3 =
* Fixed the bug with SSL when WordPress is not configured properly
* Added AAM User Activity extension
* Added ability to track access denied events
* Fixed the bug with internal AAM configurations
* Fixed the bug with login hook when only one argument is passed
* Fixed the bug with invalid argument is passed to password protected check

= 4.2 =
* Fixed the bug with post list caching
* Fixed the bug with Manage Access button
* Added REDIRECT option to post access list
* Added redirect to existing page for Backend tab on Access Denied Redirect
* Improved caching mechanism

= 4.1.1 =
* Fixed bug with Post & Pages UI
* Added ability to define default category for any role or user

= 4.1 =
* Added AAM IP Check extension
* Improved Content filter shortcode to allow other shortcodes inside
* Fixed bug for add/edit role with apostrophe
* Fixed bug with custom Access Denied message
* Fixed bug with data migration 

= 4.0.1 = 
* Fixed bug with login redirect
* Fixed minor bug with PHP Warnings on Utilities tab
* Fixed post filtering bug
* Updated login shortcode

= 4.0 =
* Added link Access to category list
* Added shortcode [aam] to manage access to the post's content
* Moved AAM Redirect extension to the basic AAM package
* Moved AAM Login Redirect extension to the basic AAM package
* Moved AAM Content Teaser extension to the basic AAM package
* Set single password for any post or posts in any category or post type
* Added two protection mechanism from login brute force attacks
* Added double authentication mechanism
* Few minor core bug fixings
* Improved multisite support
* Improved caching mechanism

= 3.9.5.1 = 
* Fixed bug with login redirect

= 3.9.5 =
* General bug fixing and improvements
* Added ability to setup access settings to all Users, Roles and Visitors
* Added Login Redirect feature

= 3.9.3 =
* Bug fixing
* Implemented license check mechanism
* Improved media access control
* Added ConfigPress extension

= 3.9.2.2 =
* Bug fixing
* Simplified affiliate implementation

= 3.9.2.1 =
* Minor bug fixes reported by CodePinch service

= 3.9.2 =
* Bug fixing
* Internal code improvements
* Extended list of post & pages access options

= 3.9.1.1 =
* Minor bug fix to cover uncommon scenario when user without role

= 3.9.1 =
* Replaced AAM Post Filter extension with core option "Large Post Number Support"
* Removed redundant HTML permalink support
* Visually highlighted editing role or user is administrator
* Hide restricted actions for roles and users on User/Role Panel
* Minor UI improvements
* Significant improvements to post & pages access inheritance mechanism
* Optimized caching mechanism
* Fixed bug with post frontend access

= 3.9 =
* Fixed UI bug with role list
* Fixed core bug with max user level 
* Fixed bug with CodePinch installation page
* Added native user switch functionality

= 3.8.3 =
* Fixed the bug with post access inheritance
* Update CodePinch affiliate program

= 3.8.2 =
* Optimized AAM UI to manage large amount of posts and categories
* Improved Multisite support
* Improved UI
* Fixed bug with Extensions tab
* Added ability to check for extension updates manually

= 3.8.1 =
* Minor refactoring
* UI improvements
* Bug fixing

= 3.8 =
* Added Clone Role feature
* Added auto cache clearing on term or post update
* Added init custom URL for metaboxes

= 3.7.6 =
* Fixed bug related to Media Access Control
* Fixed bug with cleaning user posts & pages cache after profile update

= 3.7.5 =
* Added AAM Content Teaser extension
* Added LIMIT option to Posts & Pages access forms to support Teaser feature
* Bug fixing
* Improved UI
* Added ability to show/hide admin bar with show_admin_bar capability

= 3.7.1 =
* Added AAM Role Hierarchy extension
* Fixed bug with 404 page for frontend
* Started CSS fixes for all known incompatible themes and plugins

= 3.7 =
* Introduced Redirect feature
* Added CodePinch widget
* Added AAM Redirect extension
* Added AAM Complete Package extension
* Removed AAM Development extension
* Removed setting Access Denied Handling from the Utilities tab

= 3.6.1 =
* Bug fixing related to URL redirect
* Added back deprecated ConfigPress class to keep compatability with old extensions
* Fixed bug reported through CodePinch service

= 3.6 =
* Added Media Access Control feature
* Added Access Denied Handling feature
* Improved core functionality

= 3.5 =
* Improved access control for Posts & Pages
* Introduced Access Manager metabox to Post edit screen
* Added Access action to list of Posts and Pages
* Improved UI
* Deprecated Skeleton extension in favor to upcoming totally new concept
* Fixed bug with metaboxes initialization when backend filtering is OFF

= 3.4.2 =
* Fixed bug with post & pages access control
* Added Extension version indicator

= 3.4.1 =
* Fixed bug with visitor access control

= 3.4 =
* Refactored backend UI implementation
* Integrated Utilities extension to the core
* Improved capability management functionality
* Improved UI
* Added caching mechanism to the core
* Improved caching mechanism
* Fixed few functional bugs

= 3.3 =
* Improved UI
* Completely protect Admin Menu if restricted
* Tiny core refactoring
* Rewrote UI descriptions

= 3.2.3 =
* Quick fix for extensions ajax calls

= 3.2.2 =
* Improved AAM security reported by James Golovich from Pritect
* Extended core to allow manage access to AAM features via ConfigPress

= 3.2.1 =
* Added show_screen_options capability support to control Screen Options Tab
* Added show_help_tabs capability support to control Help Tabs
* Added AAM Support

= 3.2 =
* Fixed minor bug reporetd by WP Error Fix
* Extended core functionality to support filter by author for Plus Package
* Added Contact Us tab

= 3.1.5 =
* Improved UI
* Fixed the bug reported by WP Error Fix

= 3.1.4 =
* Fixed bug with menu/metabox checkbox
* Added extra hook to clear the user cache after profile update
* Added drill-down button for Posts & Pages tab

= 3.1.3.1 =
* One more minor issue

= 3.1.3 =
* Fixed bug with default post settings
* Filtering roles and capabilities form malicious code 

= 3.1.2 =
* Quick fix

= 3.1.1 =
* Fixed potential bug with check user capability functionality
* Added social links to the AAM page

= 3.1 =
* Integrated User Switch with AAM
* Fixed bugs reported by WP Error Fix
* Removed intro message
* Improved AAM speed
* Updated AAM Utilities extension
* Updated AAM Plus Package extension
* Added new AAM Skeleton Extension for developers

= 3.0.10 =
* Fixed bug reported by WP Error Fix when user's first role does not exist
* Fixed bug reported by WP Error Fix when roles has invalid capability set

= 3.0.9 =
* Added ability to extend the AAM Utilities property list
* Updated AAM Plus Package with ability to toggle the page categories feature
* Added WP Error Fix promotion tab
* Finalized and resolved all known issues

= 3.0.8 =
* Extended AAM with few extra core filters and actions
* Added role list sorting by name
* Added WP Error Fix item to the extension list
* Fixed the issue with language file

= 3.0.7 =
* Fixed the warning issue with newly installed AAM instance

= 3.0.6 =
* Fixed issue when server has security policy regarding file_get_content as URL
* Added filters to support Edit/Delete caps with AAM Utilities extension
* Updated AAM Utilities extension
* Refactored extension list manager
* Added AAM Role Filter extension
* Added AAM Post Filter extension
* Standardize the extension folder name

= 3.0.5 =
* Wrapped all *.phtml files into condition to avoid crash on direct file access
* Fixed bug with Visitor subject API
* Added internal capability id to the list of capabilities
* Fixed bug with strict standard notice
* Fixed bug when extension after update still indicates that update is needed
* Fixed bug when extensions were not able to load js & css on windows server
* Updated AAM Utilities extension
* Updated AAM Multisite extension

= 3.0.4 =
* Improved the Metaboxes & Widget filtering on user level
* Improved visual feedback for already installed extensions
* Fixed the bug when posts and categories were filtered on the AAM page
* Significantly improved the posts & pages inheritance mechanism
* Updated and fixed bugs in AAM Plus Package and AAM Utilities
* Improved AAM navigation during page reload
* Removed Trash post access option. Now Delete option is the same
* Added UI feedback on current posts, menu and metaboxes inheritance status
* Updated AAM Multisite extension

= 3.0.3 =
* Fixed bug with backend menu saving
* Fixed bug with metaboxes & widgets saving
* Fixed bug with WP_Filesystem when non-standard filesystem is used
* Optimized Posts & Pages breadcrumb load

= 3.0.2 =
* Fixed a bug with posts access within categories
* Significantly improved the caching mechanism
* Added mandatory notification if caching is not turned on
* Added more help content

= 3.0.1 =
* Fixed the bug with capability saving
* Fixed the bug with capability drop-down menu
* Made backend menu help is more clear
* Added tooltips to some UI buttons

= 3.0 =
* Brand new and much more intuitive user interface
* Fully responsive design
* Better, more reliable and faster core functionality
* Completely new extension handler
* Added "Manage Access" action to the list of user
* Tested against WP 3.8 and PHP 5.2.17 versions

= 2.9.4 =
* Added missing files from the previous commit.

= 2.9.3 =
* Introduced AAM version 3 alpha

= 2.9.2 =
* Small fix in core
* Moved ConfigPress as stand-alone plugin. It is no longer a part of AAM
* Styled the AAM notification message

= 2.8.8 =
* AAM is changing the primary owner to VasylTech
* Removed contextual help menu
* Added notification about AAM v3

= 2.8.7 =
* Tested and verified functionality on the latest WordPress release
* Removed AAM Plus Package. Happy hours are over.

= 2.8.5 =
* Fixed bugs reported by (@TheThree)
* Improved CSS

= 2.8.4 =
* Updated the extension list pricing
* Updated AAM Plugin Manager

= 2.8.3 =
* Improved ConfigPress security (thanks to Tom Adams from security.dxw.com)
* Added ConfigPress new setting control_permalink

= 2.8.2 =
* Fixed issue with Default acces to posts/pages for AAM Plus Package
* Fixed issue with AAM Plugin Manager for lower PHP version

= 2.8.1 =
* Simplified the Repository internal handling
* Added Development License Support

= 2.8 =
* Fixed issue with AAM Control Manage HTML
* Fixed issue with __PHP_Incomplete_Class
* Added AAM Plugin Manager Extension
* Removed Deprecated ConfigPress Object from the core

= 2.7 =
* Fixed bug with subject managing check 
* Fixed bug with update hook
* Fixed issue with extension activation hook
* Added AAM Security Feature. First iteration
* Improved CSS

= 2.6 =
* Fixed bug with user inheritance
* Fixed bug with user restore default settings
* Fixed bug with installed extension detection
* Improved core extension handling
* Improved subject inheritance mechanism
* Removed deprecated ConfigPress Tutorial
* Optimized CSS
* Regenerated translation pot file

= 2.5 =
* Fixed issue with AAM Plus Package and Multisite
* Introduced Development License
* Minor internal adjustment for AAM Development Community

= 2.4 =
* Added Norwegian language Norwegian (by Christer Berg Johannesen)
* Localize the default Roles
* Regenerated .pod file
* Added AAM Media Manager Extension
* Added AAM Content Manager Extension
* Standardized Extension Modules
* Fixed issue with Media list

= 2.3 =
* Added Persian translation by Ghaem Omidi
* Added Inherit Capabilities From Role drop-down on Add New Role Dialog
* Small Cosmetic CSS changes

= 2.2 =
* Fixed issue with jQuery UI Tooltip Widget
* Added AAM Warning Panel
* Added Event Log Feature
* Moved ConfigPress to separate Page (refactored internal handling)
* Reverted back the SSL handling
* Added Post Delete feature
* Added Post's Restore Default Restrictions feature
* Added ConfigPress Extension turn on/off setting
* Russian translation by (Maxim Kernozhitskiy http://aeromultimedia.com)
* Removed Migration possibility
* Refactored AAM Core Console model
* Increased the number of saved restriction for basic version
* Simplified Undo feature

= 2.1 =
* Fixed issue with Admin Menu restrictions (thanks to MikeB2B)
* Added Polish Translation
* Fixed issue with Widgets restriction
* Improved internal User & Role handling
* Implemented caching mechanism
* Extended Update mechanism (remove the AAM cache after update)
* Added New ConfigPress setting aam.caching (by default is FALSE)
* Improved Metabox & Widgets filtering mechanism
* Added French Translation (by Moskito7)
* Added "My Feature" Tab
* Regenerated .pot file

= 2.0 =
* New UI
* Robust and completely new core functionality
* Over 3 dozen of bug fixed and improvement during 3 alpha & beta versions
* Improved Update mechanism

= 1.0 =
* Fixed issue with comment editing
* Implemented JavaScript error catching
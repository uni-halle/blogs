=== Advanced Access Manager ===
Contributors: vasyltech
Tags: access, role, user, capability, page access, post access, comments, security, login redirect, brute force attack, double authentication, membership, backend lockdown, wp-admin, 404, activity tracking
Requires at least: 3.8
Tested up to: 4.8.1
Stable tag: 4.9.5.2

Manage access to your website for any user, role or visitors for both frontend and backend.

== Description ==

> Advanced Access Manager (aka AAM) is all you need to manage access to your website frontend and backend for any user, role or visitors.
> Please Note! Some features are limited or not included in the basic plugin installation. Upon activation go to the Extensions tab to learn more about free and premium extensions that are available for download.

https://www.youtube.com/watch?v=yiOhjaacNJc

= Backend Lockdown =
Restrict access to your website backend side for any user or role. For more information about this feature refer to the [How to lockdown WordPress backend](https://aamplugin.com/help/how-to-lockdown-wordpress-backend)

= Secure Login Widget & Shortcode =
Use custom AJAX login widget or drop login form anywhere on your website with AAM shortcode. For more information check [How does AAM Secure Login works](https://aamplugin.com/help/how-does-aam-secure-login-works)

= Manage Posts & Categories =
Manage access to unlimited number of post, page or custom post type. With premium AAM Plus Package extension also manage access to categories, custom hierarchical taxonomies or setup the default access to all posts and categories. Refer to [How to manage WordPress post and category access](https://aamplugin.com/help/how-to-manage-wordpress-post-and-category-access) to learn more about this feature.

= Track Any User Activities =
Track any user or visitor activities on your website with AAM User Activity extension. For more information about this feature refer to the [How to track any WordPress user activity](https://aamplugin.com/help/how-to-track-any-wordpress-user-activity)

= 404 Redirect =
Redirect all users and visitors to specific page, URL or custom callback function when page does not exist.

= Login/Logout Redirect =
Define custom login and logout redirect for any user or group of users.

= Manage Backend Menu =
Manage access to the backend menu for any user or group or users (roles).

= Manage Capabilities =
Create, edit or delete capabilities for any role or even user.

= Manage Access Based On Geo Location And IP =
Manage access to your website for all visitors based on referred host, IP address or geographical location. For more information about this feature check [How to manage access to WordPress website based on location](https://aamplugin.com/help/how-to-manage-access-to-wordpress-website-based-on-location) article

= Manage Redirects =
Define custom access denied or login redirects for any user or group of users. Redirect user to any existing page, URL or specify your own PHP callback function to handle it.

= Manage Metaboxes and Widgets =
Filter list of metaboxes and widgets on both frontend and backend for any user, group of users or visitors.

= Content Teaser =
Create your own content teaser for any limited post, page or custom post type.

= Content Filter =
Filter or replace blocks of your content with [aam] shortcodes. For more information about this feature refer to the [How to filter WordPress post content](https://aamplugin.com/help/how-to-filter-wordpress-post-content) article

= Payments API =
Start selling access to your website content or even user levels with premium AAM Payment extension. For 
more information refer to the [AAM Payment extension](https://aamplugin.com/help/aam-payment-extension)

= Manage Roles =
Create, edit, clone, delete any role. Set expiration time to any role. With AAM Role Hierarchy extension define complex role hierarchy tree.

= Multisite Support =
AAM supports multisite network and allows you to sync access settings across your network or even restrict none-members from accessing one of your sites. For more information check [AAM and WordPress Multisite support](https://aamplugin.com/help/aam-and-wordpress-multisite-support).

= Single point API =
Easy to use programmatic interface that is used to develop your own custom 
functionality.

`//Get AAM_Core_Subject. This object allows you to work with access control
//for current logged-in user or visitor
$user = AAM::getUser();

//Example 1. Get Post with ID 10 and check if current user has access to read it
//on the frontend side of the website. If true then access denied to read this post.
$user->getObject('post', 10)->has('frontend.read');

//Example 2. Get Admin Menu object and check if user has access to Media menu.
//If true then access denied to this menu
$user->getObject('menu')->has('upload.php');`

Check our [help page](https://aamplugin.com/help) to find out more about AAM.

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
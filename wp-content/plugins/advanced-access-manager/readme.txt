=== Advanced Access Manager ===
Contributors: vasyltech
Tags: access, role, user, visitor, capability, page, post, permission, security, redirect, access
Requires at least: 3.8
Tested up to: 4.7
Stable tag: 3.9.5.1

Set of tools to manage access to your website resources like posts, pages or menus for
any user, role or visitors.

== Description ==

> Advanced Access Manager (aka AAM) is probably the only plugin that allows you to
> manage access to a website in the backend and frontend for any user, role or
> visitors. 

AAM is well documented so even inexperienced WordPress user can easily understand 
how to use it in the most efficient way.

Please note! Some of the features are limited with the basic version and if
necessary, consider to get recommended free or premium extension.

Below is the list of some of the most used features:

* Create, edit or delete Roles;
* Create, edit or delete Capabilities;
* Manage access to the Backend Menu;
* Manage access to Widgets & Metaboxes;
* Manage access to Posts, Pages, Custom Post Types or Categories;
* Limit access the a post's content with Teaser Message;
* Manage Access Denied Redirect for any restricted website resource;
* Manage Login Redirect after user authenticated successfully;
* And many more...

> AAM is very flexible and customizable plugin that is used by a lot of developers
> around the world to create secure and powerful WordPress solutions.

`//Get AAM_Core_Subject. This object allows you to work with access control
//for current logged-in user or visitor
$user = AAM::getUser();

//Example 1. Get Post with ID 10 and check if current user has access to read it
//on the frontend side of the website. If true then access denied to read this post.
$user->getObject('post', 10)->has('frontend.read');

//Example 2. Get Admin Menu object and check if user has access to Media menu.
//If true then access denied to this menu
$user->getObject('menu')->has('upload.php');`

Check our [website page](https://vasyltech.com/advanced-access-manager) to find 
out more about the Advanced Access Manager.

== Installation ==

1. Upload `advanced-access-manager` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Backend menu manager
2. Metaboxes & Widgets manager
3. User/Role Capabilities manager
4. Posts & Pages manager
5. Posts & Pages access control form
6. Utilities tab

== Changelog ==

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
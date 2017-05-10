=== FG Joomla to WordPress ===
Contributors: Frédéric GILLES
Plugin Uri: https://wordpress.org/plugins/fg-joomla-to-wordpress/
Tags: joomla, mambo, elxis, wordpress, importer, convert joomla to wordpress, migrate joomla to wordpress, joomla to wordpress migration, migrator, converter, import, k2, jcomments, joomlacomments, jomcomment, flexicontent, postviews, joomlatags, sh404sef, attachments, rokbox, kunena, phocagallery, phoca, joomsef, opensef, easyblog, zoo, zooitems, joomfish, joom!fish, wpml, joomgallery, jevents, contact directory, docman, virtuemart, woocommerce, jreviews, mosets tree, wpml, simple image gallery, rsgallery, community builder
Requires at least: 4.5
Tested up to: 4.7.4
Stable tag: 3.25.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=fred%2egilles%40free%2efr&lc=FR&item_name=fg-joomla-to-wordpress&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted

A plugin to migrate categories, posts, tags, images and other medias from Joomla to WordPress

== Description ==

This plugin migrates sections, categories, posts, images, medias and tags from Joomla to Wordpress.

It has been tested with **Joomla versions 1.5 through 3.7** and **Wordpress 4.7** on huge databases (72 000+ posts). It is compatible with multisite installations.

Major features include:

* migrates Joomla sections as categories
* migrates categories as sub-categories
* migrates Joomla posts (published, unpublished and archived)
* migrates Joomla web links
* uploads all the posts media in WP uploads directories (as an option)
* uploads external media (as an option)
* modifies the post content to keep the media links
* resizes images according to the sizes defined in WP
* defines the featured image to be the first post image
* keeps the alt image attribute
* keeps the image caption
* modifies the internal links
* migrates meta keywords as tags
* migrates page breaks
* can import Joomla articles as posts or pages

No need to subscribe to an external web site.

= Premium version =

The **Premium version** includes these extra features:

* migrates authors and other users with their passwords
* migrates the navigation menus
* SEO: migrates the meta description and the meta keywords
* SEO: keeps the Joomla articles IDs or redirects Joomla URLs to the new WordPress URLs
* compatible with **Joomla 1.0** and **Mambo 4.5 and 4.6** (process {mosimages} and {mospagebreak})
* migrates Joomla 1.0 static articles as pages
* migrates Joomla 2.5+ featured images
* migrates Joomla 3.1+ tags
* migrates Mambo data
* migrates Elxis data (Joomla 1.0 fork)

The Premium version can be purchased on: https://www.fredericgilles.net/fg-joomla-to-wordpress/

= Add-ons =

The Premium version allows the use of add-ons that enhance functionality:

* K2
* EasyBlog
* Flexicontent
* Zoo
* Kunena forum
* sh404sef
* JoomSEF
* OpenSEF
* WP-PostViews (keep Joomla hits)
* JComments
* JomComment
* Joomlatags
* Attachments
* Rokbox
* JoomGallery
* Phocagallery
* Joom!Fish translations to WPML
* JEvents events
* Contact Manager
* Docman
* Virtuemart
* JReviews
* Mosets Tree
* User Groups
* WPML
* Simple Image Gallery & Simple Image Gallery Pro
* RSGallery
* Community Builder

These modules can be purchased on: https://www.fredericgilles.net/fg-joomla-to-wordpress/add-ons/

== Installation ==

1.  Install the plugin in the Admin => Plugins menu => Add New => Upload => Select the zip file => Install Now
2.  Activate the plugin in the Admin => Plugins menu
3.  Run the importer in Tools > Import > Joomla (FG)
4.  Configure the plugin settings. You can find the Joomla database parameters in the Joomla file configuration.php<br />
    Hostname = $host<br />
    Port     = 3306 (standard MySQL port)<br />
    Database = $db<br />
    Username = $user<br />
    Password = $password<br />
    Joomla Table Prefix = $dbprefix

== Frequently Asked Questions ==

= I get the message: "[fg-joomla-to-wordpress] Couldn't connect to the Joomla database. Please check your parameters. And be sure the WordPress server can access the Joomla database. SQLSTATE[28000] [1045] Access denied for user 'xxx'@'localhost' (using password: YES)" =

* First verify your login and password to the Joomla database.
If Joomla and WordPress are not installed on the same host:
* If you use CPanel on the Joomla server, a solution is to allow a remote MySQL connection.
 - go into the Cpanel of the Joomla server
 - go down to Database section and click "Remote MySQL"
 - There you can add an access host (WordPress host). Enter the access host as the SOME-WEBSITE-DOMAIN-OR-IP-ADDRESS and click add host.
* Another solution is to copy the Joomla database on the WordPress database:
 - export the Joomla database to a SQL file (with phpMyAdmin for example)
 - import this SQL file on the same database as WordPress
 - run the migration by using WordPress database credentials (host, user, password, database) instead of the Joomla ones in the plugin settings.

= I get this error when testing the connection: "SQLSTATE[HY000] [2002] Connection refused" or "SQLSTATE[HY000] [2002] No such file or directory" =

* This error happens when the host is set like localhost:/tmp/mysql5d.sock
Instead, you must set the host to be localhost;unix_socket=/tmp/mysql5d.sock

= The migration stops and I get the message: "Fatal error: Allowed memory size of XXXXXX bytes exhausted" or I get the message: “Internal server error" =

* First, deactivate all the WordPress plugins except the ones used for the migration
* You can run the migration again. It will continue where it stopped.
* You can add: `define('WP_MEMORY_LIMIT', '512M');` in your wp-config.php file to increase the memory allowed by WordPress
* You can also increase the memory limit in php.ini if you have write access to this file (ie: memory_limit = 1G). See the <a href="https://premium.wpmudev.org/blog/increase-memory-limit/" target="_blank">increase memory limit procedure</a>.

= I get a blank screen and the import seems to be stopped =

* Same as above

= The media are not imported =

* Check the URL field that you filled in the plugin settings. It must be your Joomla home page URL and must start with http://

= The media are not imported and I get the error message: "Warning: copy() [function.copy]: URL file-access is disabled in the server configuration" =

* The PHP directive "Allow URL fopen" must be turned on in php.ini to copy the medias. If your remote host doesn't allow this directive, you will have to do the migration on localhost.

= Nothing is imported at all =

* Check your Joomla version. The Joomla 1.0 database has got a different structure from the other versions of Joomla. Importing Joomla 1.0 database is a Premium feature.

= All the posts are not migrated. Why ? =

* The posts put in trash are not migrated. But unpublished and archived posts are migrated as drafts.
* Some users reported that the Zend Framework causes an incomplete import. So, if all the data is not migrated, consider deactivating the Zend Framework during the migration.

= I get the message: "Fatal error: Class 'PDO' not found" =

* PDO and PDO_MySQL libraries are needed. You must enable them in php.ini on the WordPress host.<br />
Or on Ubuntu:<br />
sudo php5enmod pdo<br />
sudo service apache2 reload

= I get this error: PHP Fatal error: Undefined class constant 'MYSQL_ATTR_INIT_COMMAND' =

* You have to enable PDO_MySQL in php.ini on the WordPress host. That means uncomment the line extension=pdo_mysql.so in php.ini

= Does the migration process modify the Joomla site it migrates from? =

* No, it only reads the Joomla database.

= I get this error: Erreur !: SQLSTATE[HY000] [1193] Unknown system variable 'NAMES' =

* It comes from MySQL 4.0. It will work if you move your database to MySQL 5.0 before running the migration.

= I get this error "Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM" =

* You must use at least PHP 5.3 on your WordPress site.

= I get this error: SQLSTATE[HY000] [2054] The server requested authentication method unknown to the client =

* It is a compatibility issue with your version of MySQL.<br />
You can read this post to fix it: http://forumsarchive.laravel.io/viewtopic.php?id=8667

= None image get transferred into the WordPress uploads folder. I'm using Xampp on Windows. =

* Xampp puts the htdocs in the applications folder which is write protected. You need to move the htdocs to a writeable folder.

= How to import content from one section as posts and another section as pages? =

* You can use the Convert Post Types plugin after the migration.

= Do I need to keep the plugin activated after the migration? =

* No, you can deactivate or even uninstall the plugin after the migration (for the free version only).

= Is there a log file to show the information from the import? =
* Yes since version 1.45.0. First you must put these lines in wp-config.php:<br />
define('WP_DEBUG', true);<br />
define('WP_DEBUG_LOG', true);<br />
And the messages will be logged to wp-content/debug.log.

= How does the plugin handle Weblinks? =

* The plugin imports the Joomla web links to WordPress links managed by the Link Manager plugin: https://wordpress.org/plugins/link-manager/

= My screen hangs because of a lot of errors in the log window =
* You can stop the log auto-refresh by unselecting the log auto-refresh checkbox


Don't hesitate to let a comment on the forum or to report bugs if you found some.
https://wordpress.org/support/plugin/fg-joomla-to-wordpress

== Screenshots ==

1. Parameters screen

== Translations ==
* English (default)
* French (fr_FR)
* Spanish (es_ES)
* Italian (it_IT)
* German (de_DE)
* Russian (ru_RU)
* Polish (pl_PL)
* Bulgarian (bg_BG)
* Brazilian (pt_BR)
* other can be translated

== Changelog ==

= 3.25.0 =
* Compatible with Joomla 3.7
* Tested with WordPress 4.7.4

= 3.23.2 =
* Tested with WordPress 4.7.3

= 3.23.1 =
* Fixed: Images not imported on some servers
* Tested with WordPress 4.7.1

= 3.23.0 =
* Tweak: Code refactoring

= 3.22.0 =
* Tweak: Code refactoring

= 3.21.0 =
* New: Add an option to remove the accents from the medias (useful on Windows)
* Tested with WordPress 4.7

= 3.20.6 =
* Fixed: Existing images attached to imported posts were removed when deleting the imported data
* Fixed: Typo in Italian translation

= 3.20.5 =
* Fixed: Images not imported on HTTPS sites: Warning: fsockopen(): unable to connect to https::80 (php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known)

= 3.20.4 =
* Fixed: Wrong progress bar color

= 3.20.3 =
* Fixed: The progress bar didn't move during the first import
* Fixed: The log window was empty during the first import

= 3.20.2 =
* New: Check if the Community Builder module is required
* Fixed: The "IMPORT COMPLETE" message was still displayed when the import was run again

= 3.20.1 =
* Fixed: The images protected by a user agent protection were not imported

= 3.20.0 =
* Tweak: Code refactoring

= 3.19.1 =
* Fixed: Database passwords containing "<" were not accepted

= 3.19.0 =
* New: Modify the tags links in the post content

= 3.18.0 =
* New: Authorize the connections to Web sites that use invalid SSL certificates
* Tweak: If the import is blocked, stop sending AJAX requests

= 3.17.2 =
* Fixed: Review link broken
* Fixed: Imported tags were not removed when removing imported data only

= 3.17.1 =
* Fixed: Missing link between the post and its featured image
* Fixed: Wrong number of comments displayed
* Tested with WordPress 4.6.1

= 3.16.0 =
* New: Display the number of data found in the Joomla database before importing
* New: Display the needed modules as warnings before importing
* Tested with WordPress 4.6

= 3.15.3 =
* Tweak: Code optimization

= 3.15.2 =
* Fixed: the "Modify internal links" function could break some links

= 3.15.1 =
* Fixed: Internal links like catid=XXX&id=YYY were not modified
* Tweak: Speed up and reduce the memory consumed by the modification of the internal links

= 3.15.0 =
* New translation: Italian

= 3.14.0 =
* New: Compatible with Joomla 3.6

= 3.13.3 =
* Fixed: Display an error message when the process hangs
* Tweak: Increase the speed of counting the terms

= 3.13.2 =
* Tested with WordPress 4.5.3

= 3.13.1 =
* Fixed: Don't import the introtext in the post content if it is marked as hidden on Joomla

= 3.13.0 =
* New: Compatibility between the Joom!Fish and Docman add-ons
* Fixed: Wrong redirect when an attachment has the same name as a post

= 3.12.0 =
* Fixed: Rewrite the function to delete only the imported data
* Fixed: Categories import can hang if the import counter was resetted and the imported categories were not deleted

= 3.11.0 =
* New: Option to import the featured images only

= 3.10.2 =
* Fixed: The message "[ERROR] The import process is still running. Please wait before running it again." sometimes appears after the process has crashed, and it prevents the import process to resume
* FAQ updated

= 3.10.0 =
* New: Add some hooks
* Tweak: Code optimization
* FAQ updated

= 3.9.1 =
* Fixed: Images with line breaks inside the tag were not imported
* Tested with WordPress 4.5.2

= 3.9.0 =
* New: Allow image filenames starting with //

= 3.8.0 =
* Tweak: Add functions useful for add-ons
* Tested with WordPress 4.5.1

= 3.7.0 =
* New: Ability to stop the log window auto-refresh

= 3.6.1 =
* Tested with WordPress 4.5

= 3.6.0 =
* New: Compatible with Joomla 3.5
* Fixed: Images without slashes in their path were not imported

= 3.5.1 =
* Fixed: Import stopped when a post has no title or no content
* Fixed: The first image was not removed from the content when used both in the intro text and in the full text

= 3.5.0 =
* Fixed: Notice: Undefined variable: imported_tags
* Fixed: the progress bar was resetted when resuming the import

= 3.4.0 =
* New: Modify the first image options
* Tweak: Code refactoring

= 3.3.1 =
* Fixed: Error :SQLSTATE[42S22]: Column not found: 1054 Unknown column 'c.extension' in 'where clause'

= 3.3.0 =
* New: Use the WordPress FTP API instead of the phpseclib library
* New: Better handle the progress bar
* New: Don't log the [COUNT] data in the log window
* Fixed: Browser tab crashed when too much data was displayed in the log window

= 3.2.0 =
* New: Modify the Joomla SEF links

= 3.1.0 =
* New: Brazilian translation added
* Fixed: When choosing "Import first image as featured only", the first image was not removed from content if it was surrounded by a hyperlink

= 3.0.3 =
* Fixed: Articles got the unassigned category when the category is a duplicate

= 3.0.2 =
* Fixed: Infinite loop when some categories have duplicate names

= 3.0.1 =
* Fixed: After a resume, the posts were imported as uncategorized
* Tested with WordPress 4.4.2

= 3.0.0 =
* New: Run the import in AJAX
* New: Add a progress bar
* New: Add a logger frame to see the logs in real time
* New: Ability to stop the import
* New: Compatible with PHP 7

= 2.14.1 =
* Fixed: Medias with relative paths were not uploaded to the right folder when not using month- and year-based folders

= 2.14.0 =
* New: For the articles and categories whose alias is a date, the imported slug will be the title and not the alias

= 2.13.0 =
* New: Keep the Joomla media folder tree when the uploads are not organized into month- and year-based folders

= 2.12.0 =
* Fixed: Featured image issue

= 2.11.1 =
* Tested with WordPress 4.4.1

= 2.11.0 =
* Fixed: Categories with null description were not imported

= 2.10.0 =
* Tweak: Use the WordPress 4.4 term metas: performance improved, nomore need to add a category prefix
* Tweak: Optimize code
* Fixed: The notices and errors were sometimes displayed before the header is sent
* Fixed: Categories with duplicated names were not imported
* Fixed: The cache for the taxonomies different from category was not cleaned

= 2.9.2 =
* Tested with WordPress 4.4

= 2.9.0 =
* New: Add SFTP protocol
* New: Import the {audio} tag
* New: Add a link to the FAQ in the connection error message

= 2.8.0 =
* New: Add an Import link on the plugins list page

= 2.7.4 =
* Tweak: Code refactoring for unit tests

= 2.7.3 =
* New: Add the hook 'fgj2wp_get_wp_post_from_joomla_url'
* Tweak: Code refactoring

= 2.7.1 =
* Fixed: Don't display the warning about WPML if JoomFish is used
* Tweak: Change the range of the get_wp_post_id_from_joomla_id() function to be available for add-ons

= 2.7.0 =
* New: Add the FTP settings (used for Simple Image Gallery add-on)

= 2.6.0 =
* New: Make the platform more accessible to more languages
* Update all the translations

= 2.5.2 =
* New: Check if we need the WPML module

= 2.5.1 =
* Tested with WordPress 4.3.1

= 2.5.0 =
* Fixed some translations
* New add-on: WPML to move the multilingual content

= 2.4.0 =
* New: Add an anti-duplicate test if the user runs another import process again while one is still running
* Fixed: Solve conflicts between FG plugins by limiting the Javascript scope

= 2.3.3 =
* Fixed: Some medias with accents were not imported
* Tested with WordPress 4.3

= 2.3.2 =
* Tested with WordPress 4.2.4

= 2.3.1 =
* Tested with WordPress 4.2.3

= 2.3.0 =
* New: Change the video links {"video"} to WordPress video tags

= 2.2.2 =
* Fixed: Fatal error: Call to a member function fetch() on a non-object

= 2.2.0 =
* Tested with WordPress 4.2.2

= 2.1.2 =
* Tested with WordPress 4.2.1

= 2.1.1 =
* Tested with WordPress 4.2

= 2.1.0 =
* Tweak: Restructure and optimize the images import functions
* Tweak: Move the suspend cache functions into the dispatch method

= 2.0.0 =
* Restructure the whole code using the BoilerPlate foundation
* FAQ updated

= 1.46.1 =
* Fixed: Remove duplicate hook in weblinks.php
* New add-on: User Groups

= 1.46.0 =
* New: Compatible with Joomla 3.4 (ignore weblinks)

= 1.45.0 =
* New: Log the messages to wp-content/debug.log
* Tweak: Code optimization
* FAQ updated

= 1.44.3 =
* Fixed: Import images even when there are linefeeds in the img tags

= 1.44.2 =
* Fixed: Don't import the posts as duplicates if the categories are duplicated on Joomla

= 1.44.1 =
* Tested with WordPress 4.1.1

= 1.44.0 =
* Fixed: the joomla_query() function was returning only one row
* Update the German translation (thanks to Tobias C.)
* Update the Spanish translation (thanks to Jacob R.)

= 1.43.4 =
* Fixed: Multisite: Links that contain ":" were corrupted
* FAQ updated

= 1.43.2 =
* Tweak: Add hooks in the modify_links functions

= 1.43.0 =
* FAQ updated
* Tested with WordPress 4.1

= 1.42.0 =
* Tweak: Don't display the timeout field if the medias are skipped

= 1.41.0 =
* New: Keep the anchor link when modifying the internal links
* Tested with WordPress 4.0.1

= 1.40.0 =
* Update the German translation (thanks to Tobias C.)

= 1.39.5 =
* Update the Spanish translation (thanks to Bradis García L.)

= 1.39.4 =
* Fixed: Allow backslashes in the articles content

= 1.39.3 =
* Fixed: Remove extra slashes in the media filenames

= 1.39.1 =
* Tweak: Simplify the posts count function

= 1.39.0 =
* New: Add a timeout option

= 1.38.1 =
* Fixed: Some image captions were not imported

= 1.38.0 =
* Fixed: The media filename was empty on the attachment page
* Tested with WordPress 4.0

= 1.37.0 =
* New: Help screen

= 1.36.0 =
* New: Functions to get the Joomla imported posts, categories and users
* New add-on: JReviews

= 1.35.0 =
* New: Function to get the Joomla installation language
* New add-on: Virtuemart to WooCommerce
* Tested with WordPress 3.9.2

= 1.34.2 =
* Fixed: Define the width and the height of the images only if it isn't defined yet

= 1.34.1 =
* New: Modify the internal links for both posts, pages and custom post types

= 1.34.0 =
* New: Add option to automatically remove the WordPress content before each import

= 1.32.0 =
* New: Display the number of Joomla articles, categories, users and web links during the database connection test
* New: Compatibility with Joomla 3.3

= 1.31.4 =
* Fixed: Warning: Creating default object from empty value

= 1.31.3 =
* Fixed: "Fatal error: Call to a member function fetch() on a non-object" for versions of MySQL < 5.0.3

= 1.31.2 =
* New add-on: Docman
* Tested with WordPress 3.9.1

= 1.31.1 =
* New: Add a parameter to force the external media import

= 1.31.0 =
* New: Import Web links

= 1.30.0 =
* Tested with WordPress 3.9

= 1.29.4 =
* New: Change the visibility of some methods to use them in add-ons
* Fixed: Notice: Undefined index: width
* Fixed: Notice: Undefined index: height

= 1.29.3 =
* Fixed: Was displaying the warning "Your version of Joomla (probably 1.0) is not supported by this plugin." when both the Premium and the free versions were activated
* Tested with WordPress 3.8.2

= 1.29.2 =
* New: Change the visibility of some methods to use them in add-ons

= 1.29.1 =
* Fixed: Fatal error: Call to a member function fetch() on a non-object

= 1.29.0 =
* New: The required modules are listed when testing the connection to Joomla

= 1.28.0 =
* New: Nomore need to choose the Joomla version ; it is guessed by the plugin.
* New: Add a message when trying to import a Joomla 1.0 database

= 1.27.0 =
* New: Minor internal changes
* FAQ updated

= 1.24.4 =
* New: Add hooks
* Fixed: Notice Undefined offset

= 1.24.3 =
* Fixed: Don't add the &lt;!--more--&gt; tag if the introtext is empty
* Tested with WordPress 3.8.1

= 1.24.1 =
* Fixed: Syntax error with parse_ini_string
* Fixed: Images containing "%20" were not imported into the post content
* FAQ updated

= 1.24.0 =
* New: Compatibility with Joomla 3.2
* New translation: Bulgarian (thanks to Hristo P.)

= 1.22.6 =
* Fixed: The «Remove only new imported posts» option was not removing anything
* Tested with WordPress 3.8

= 1.22.5 =
* Fixed: Archived posts were always imported as drafts in Joomla 2.5

= 1.22.4 =
* New: Display error message if PDO is not enabled

= 1.22.3 =
* New: Display SQL errors in debug mode
* Description updated

= 1.22.2 =
* New: Check if the upload directory is writable
* Tested with WordPress 3.7.1

= 1.22.0 =
* Fixed: Import the categories even when the articles are imported as pages
* Tested with WordPress 3.7

= 1.21.3 =
* Fixed: "Warning: sprintf(): Too few arguments" message for image captions with %

= 1.21.0 =
* New translation: Spanish (thanks to Bradis García L.)

= 1.20.1 =
* Fixed: Use the modified post date if the creation date is empty
* Fixed: Warning: array_key_exists() [function.array-key-exists]: The second argument should be either an array or an object

= 1.19.3 =
* Fixed: Some spaces were removed (due to the extra newlines removal)
* Fixed: Better rule for the convert_post_attribs_to_array function
* Fixed: "WordPress database error Field 'post_content' doesn't have a default value"

= 1.19.0 =
* New: Import the page breaks
* New: Option to import the Joomla introtext in the post and in the excerpt
* New: Use the show_intro article parameter to import the introtext in the content or not
* Tested with WordPress 3.6.1

= 1.18.0 =
* New: Compatibility with Joomla 3.1
* Fixed: Remove extra newlines

= 1.17.0 =
* New: Add automatically http:// at the beginning of the URL if it is missing
* New: Option for the first image import
* FAQ updated

= 1.16.1 =
* Fixed: syntax error, "unexpected '&lt;'" in version 1.16.0

= 1.16.0 =
* New: Option to import images with duplicate names
* New translation: Polish (Thanks to Łukasz Z.)
* FAQ updated

= 1.15.2 =
* Optimize the Joomla connection

= 1.15.1 =
* New: Option to not import archived posts or to import them as drafts or as published posts

= 1.15.0 =
* New: Import archived posts as drafts
* Tested with WordPress 3.6

= 1.14.2 =
* Fixed: The HTML classes were lost in the a-href and img tags
* Unset by default the checkbox «Import the text above the "read more" to the excerpt»

= 1.14.1 =
* Fixed: The caption shortcode is imported twice if the image has a link a-href pointing to a different image

= 1.14.0 =
* New: Import images captions
* Improve speed of processing the image links
* Update the FAQ

= 1.13.0 =
* Tested with WordPress 3.5.2
* New: Add a button to save the settings
* New: Improve the speed of emptying the WordPress content

= 1.12.1 =
* Fixed: Replaces the publication date by the creation date as Joomla uses the creation date for sorting articles

= 1.12.0 =
* New: Add a button to remove the categories prefixes
* New: Option to not use the first post image as the featured image

= 1.11.0 =
* New: Import external media (as an option)
* New translation: Russian (Thanks to Julia N.)

= 1.10.6 =
* Fixed: Categories hierarchy lost when parent categories had an id greater than their children

= 1.10.4 =
* Fixed: Posts were not imported when the skip media option was off

= 1.10.3 =
* Fixed: Categories hierarchy lost when parent categories had an id greater than their children (Joomla 1.6+)
* New: Add hooks for extra images and after saving options

= 1.10.2 =
* Tested with WordPress 3.5.1
* New: Add hooks in the modify_links method

= 1.10.1 =
* New: Add a hook for extra options
* Fixed: Move the fgj2wp_post_empty_database hook
* FAQ updated

= 1.10.0 =
* New: Compatibility with Joomla 3.0
* New: Option to delete only new imported posts without deleting the whole database

= 1.9.1 =
* Fixed: the internal links where not modified on pages

= 1.9.0 =
* Tested with WordPress 3.5
* New: Button to test the database connection
* New: Improve the user experience by displaying explanations on the parameters and error messages
* New: get_categories hook modified

= 1.8.5 =
* New: Option to not import already imported medias

= 1.8.4 =
* FAQ updated

= 1.8.3 =
* Fixed: Cache flushed after the migration
* Fixed: Compatibility issue with WordPress < 3.3

= 1.8.2 =
* New: Better compatibility for copying media: uses the WordPress HTTP API

= 1.8.1 =
* New: Better compatibility for copying media: uses the copy function if cURL is not loaded

= 1.8.0 =
* New: Compatibility with PHP 5.1 (thanks to dmikam)
* New: Compatibility with WordPress 3.0 (thanks to dmikam)
* New: Better compatibility for copying media (uses cURL) (thanks to dmikam)

= 1.7.1 =
* FAQ updated

= 1.7.0 =
* New: Compatibility with Joomla 2.5

= 1.6.3 =
* New hooks added
* Description updated

= 1.6.2 =
* FAQ updated

= 1.6.1 =
* Fixed: clean the cache after emptying the database
* Fixed: the categories slugs were not imported if they had no alias

= 1.6.0 =
* New: Compatibility with Joomla 1.6 and 1.7

= 1.5.0 =
* New: Can import posts as pages (thanks to LWille)
* Translation: German (thanks to LWille)

= 1.4.2 =
* Tested with WordPress 3.4

= 1.4.1 =
* Add "c" in the category slug to not be in conflict with the Joomla URLs
* FAQ and description updated

= 1.4.0 =
* New: Option to import meta keywords as tags

= 1.3.1 =
* New: Deactivate the cache during the migration for improving speed

= 1.3.0 =
* New: Modify posts internal links using WordPress permalinks setup
* Fixed: Exhausted memory issue

= 1.2.2 =
* Fixed: Don't import HTML links as medias
* FAQ updated

= 1.2.1 =
* New: Get the post creation date when the publication date is empty
* Fixed: Accept categories with spaces in alias

= 1.2.0 =
* New: Import all media
* Fixed: Do not reimport already imported categories
* Fixed: Update categories cache
* Fixed: Issue with media containing spaces
* Fixed: Original images sizes are kept in post contents

= 1.1.1 =
* New: Manage sections and categories duplicates
* Fixed: Wrong categorization of posts

= 1.1.0 =
* Update the FAQ
* New: Can restart an import where it left after a crash (for big databases)
* New: Display the number of categories, posts and images already imported
* Fixed: Issue with categories with alias but no name
* Fixed: Now import only post categories, not all categories (ie modules categories, …)

= 1.0.2 =
* Fixed: The images with absolute links were not imported.
* New: Option to skip the images import
* New: Skip external images

= 1.0.1 =
* Fixed: The content was not imported in the post content for the posts without a "Read more" link.
* New: Option to choose to import the Joomla introtext in the excerpt or in the post content with a «Read more» tag.

= 1.0.0 =
* Initial version: Import Joomla 1.5 sections, categories, posts and images

== Upgrade Notice ==

= 3.25.0 =
Compatible with Joomla 3.7
Tested with WordPress 4.7.4

= 3.23.2 =
Tested with WordPress 4.7.3

= 3.23.1 =
Fixed: Images not imported on some servers
Tested with WordPress 4.7.1

= 3.23.0 =
Tweak: Code refactoring

= 3.22.0 =
Tweak: Code refactoring

= 3.21.0 =
New: Add an option to remove the accents from the medias (useful on Windows)
Tested with WordPress 4.7

= 3.20.6 =
Fixed: Existing images attached to imported posts were removed when deleting the imported data
Fixed: Typo in Italian translation

= 3.20.5 =
Fixed: Images not imported on HTTPS sites: Warning: fsockopen(): unable to connect to https::80 (php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known)

= 3.20.4 =
Fixed: Wrong progress bar color

= 3.20.3 =
Fixed: The progress bar didn't move during the first import
Fixed: The log window was empty during the first import

= 3.20.2 =
New: Check if the Community Builder module is required
Fixed: The "IMPORT COMPLETE" message was still displayed when the import was run again

= 3.20.1 =
Fixed: The images protected by a user agent protection were not imported

= 3.20.0 =
Tweak: Code refactoring

= 3.19.1 =
Fixed: Database passwords containing "<" were not accepted

= 3.19.0 =
New: Modify the tags links in the post content

= 3.18.0 =
New: Authorize the connections to Web sites that use invalid SSL certificates
Tweak: If the import is blocked, stop sending AJAX requests

= 3.17.2 =
Fixed: Review link broken
Fixed: Imported tags were not removed when removing imported data only

= 3.17.1 =
Fixed: Missing link between the post and its featured image
Fixed: Wrong number of comments displayed
Tested with WordPress 4.6.1

= 3.16.0 =
New: Display the number of data found in the Joomla database before importing
New: Display the needed modules as warnings before importing
Tested with WordPress 4.6

= 3.15.3 =
Tweak: Code optimization

= 3.15.2 =
Fixed: the "Modify internal links" function could break some links

= 3.15.1 =
Fixed: Internal links like catid=XXX&id=YYY were not modified
Tweak: Speed up and reduce the memory consumed by the modification of the internal links

= 3.15.0 =
New translation: Italian

= 3.14.0 =
New: Compatible with Joomla 3.6

= 3.13.3 =
Fixed: Display an error message when the process hangs
Tweak: Increase the speed of counting the terms

= 3.13.2 =
Tested with WordPress 4.5.3

= 3.13.1 =
Fixed: Don't import the introtext in the post content if it is marked as hidden on Joomla

= 3.12.0 =
Fixed: Rewrite the function to delete only the imported data
Fixed: Categories import can hang if the import counter was resetted and the imported categories were not deleted

= 3.11.0 =
New: Option to import the featured images only

= 3.10.2 =
Fixed: The message "[ERROR] The import process is still running. Please wait before running it again." sometimes appears after the process has crashed, and it prevents the import process to resume
FAQ updated

= 3.10.0 =
New: Add some hooks
Tweak: Code optimization
FAQ updated

= 3.9.1 =
Fixed: Images with line breaks inside the tag were not imported
Tested with WordPress 4.5.2

= 3.9.0 =
New: Allow image filenames starting with //

= 3.8.0 =
Tested with WordPress 4.5.1

= 3.7.0 =
New: Ability to stop the log window auto-refresh

= 3.6.1 =
Tested with WordPress 4.5

= 3.6.0 =
New: Compatible with Joomla 3.5
Fixed: Images without slashes in their path were not imported

= 3.5.1 =
Fixed: Import stopped when a post has no title or no content
Fixed: The first image was not removed from the content when used both in the intro text and in the full text

= 3.5.0 =
Fixed: Notice: Undefined variable: imported_tags
Fixed: the progress bar was resetted when resuming the import

= 3.4.0 =
New: Modify the first image options
Tweak: Code refactoring

= 3.3.1 =
Fixed: Error :SQLSTATE[42S22]: Column not found: 1054 Unknown column 'c.extension' in 'where clause'

= 3.3.0 =
New: Use the WordPress FTP API instead of the phpseclib library
New: Better handle the progress bar
New: Don't log the [COUNT] data in the log window
Fixed: Browser tab crashed when too much data was displayed in the log window

= 3.2.0 =
New: Modify the Joomla SEF links

= 3.1.0 =
New: Brazilian translation added
Fixed: When choosing "Import first image as featured only", the first image was not removed from content if it was surrounded by a hyperlink

= 3.0.3 =
Fixed: Articles got the unassigned category when the category is a duplicate

= 3.0.2 =
Fixed: Infinite loop when some categories have duplicate names

= 3.0.1 =
Fixed: After a resume, the posts were imported as uncategorized

= 3.0.0 =
New: Run the import in AJAX
New: Add a progress bar
New: Add a logger frame to see the logs in real time
New: Ability to stop the import
New: Compatible with PHP 7

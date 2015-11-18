=== Instagram for WordPress ===
Contributors: jbenders
Tags: widgets, photos, instagram
Requires at least: 3.0
Tested up to: 4.3.1
Stable tag: 2.1.6

A comprehensive sidebar widget that can show your latest photos, tagged
photos, photos from a location, your favourite photos, your feed, other users photos. Can be shown
in three ways with a Grid, Grid with paging and slideshow options.

== Description ==

The best Instagram widget for your Wordpress website or blog is now here! Built by one of the world's largest Instagram browsers INK361, this comprehensive widget that can showcase your Instagram account in the way you want with comprehensive customisation features.

Display your latest photos, tagged photos, photos from a place or location, your favourite photos, your feed, or other users photos. Our widget can be set up to fit any website or blog in three ways - with a Grid, Grid with paging and slideshow options.

To get started, download and activate our widget from this page or within the Wordpress plugin interface. Drag and drop the Instagram Widget into one of your widget areas and then click on the Connect To Instagram button.

You can find your widget management area under your Appearance menu.

Two shortcodes are available, [instagram-post] and [instagram-widget]. You can read more about their configuration on our shortcode help page: http://wordpress.ink361.com/help/shortcode

== Screenshots ==

1. Configuration form -> Content to display
2. Configuration form -> Content to display (another user with search drop
down)
3. Configuration form -> Content to display (multiple tag selection)
4. Configuration form -> Display settings
5. Configuration form -> Display settings (slideshow)
6. Configuration form -> Advanced settings
7. Drag and drop the widget to embed it

== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add 'Instagram' widget to Your sidebar via 'Appearance' > 'Widgets' menu in WordPress.
4. Click setup and follow the instructions
5. Enjoy!

For more installation options please refer to
http://wordpress.ink361.com/help/installing

For information regarding the use of shortcodes please refer to
http://wordpress.ink361.com/help/shortcode

== Changelog ==

= 2.1.6 = 
* Added options for more grid entries
* small textual improvement

= 2.1.5=
* Added celebrity accounts option

= 2.1.4=
* bug fix where slideshow wouldnt work 

= 2.1.3 =
* bug fix where configuration lightbox wouldnt load 
* tested compatibility with wordpress 4.3.1

= 2.1.2 =
* Better namespacing of javascript 

= 2.1.1 =
* Namespaced jQuery/plugins to prevent conflicts with other plugins/themes

= 2.1.0 =
* Added shortcode support for embedding photos and widgets into posts - see http://wordpress.ink361.com/help/shortcode for more information

= 2.0.10 =
* Small styling improvement for video.
* fixed description not showing.
* Added support for new image formats.

= 2.0.9 =
* Changed responsive image to standard resolution.
* Added support for video posts.

= 2.0.8 =
* Fix for slideshow options on form - they were not showing up.

= 2.0.7 =
* Fixed javascript conflicts in admin.
* Fixed alignment of close button on fancybox lightbox
* Added on-hover visible captions on fancybox
* Improved review bar visibility by removing it from all admin pages apart from plugins/widgets
* Added error message when database restrictions stop widget from functioning

= 2.0.6 =
* Added new location method to show images from a specific location.
* Added review bar.
* Improved drop down autocompletes by adding loading animation.
* Removed joe editor backup files from repo.

= 2.0.5 =
* Fix for warnings on line 32 of the plugin.
* Removed DateTime dependency.

= 2.0.4 =
* Added configuration guide notifications to make installation easier.

= 2.0.3 =
* Fixed image quality problem on slideshow.
* Added cumulative and restrictive option on tagged photos display.
* Added custom titles to widget configuration to allow easy identification of widgets.
* Fixed configuration lightbox in Customizer

= 2.0.2 =
* Fixed an issue with the database table checking method
* Fixes several issues with none defined array elements

= 2.0.1 =
* Replaced remote setting storage with local settings.
* Replaced widget configuration form.
* Added verbose warning mode.
* Added responsive mode.
* Added enable/disable social icons.
* Added cache timeout option.
* Added fixes for detecting access token issues.
* Simplified installation process.
* Fixed a number of installation issues.
* Fixes unique widget identifier issues.
* Created new website (http://wordpress.ink361.com)
* Created new help resources.
* Created customization guide.
* Fixed a number of display issues for fancybox.
* Fixed a number of typos.

= 1.0.6 = 
* Fix for poor error responses returned from widget service.

= 1.0.5 =
* Fix for bad plugin instantiation from widgets.php

= 1.0.4 =
* Addition of missing before and after widget variables

= 1.0.3 =
* Message alerting users to why the plugin uses the INK361 Instagram service
* Removal of paid version (refund given to every user who purchased)
* Free version now contains all of the features in the paid version
* Removal of incorrect license on lightbox.js

= 1.0.2 =
* Fixes for IE

= 1.0.1 =
* Bugfix for caching

= 1.0.0 =
* Major improvements in installation and setup
* New display modes
* New instagram results to choose from
* New pro version

= 0.4.5 =
* bugfix for syntax error

= 0.4.4 =
* bugfix for wordpress error types

= 0.4.3 =
* improved hit position of fancybox left/right arrows
* added css for cursor on fancybox image

= 0.4.2 = 
* improved error messages when wrong client details are entered
* increased z-index of fancybox to appear on top of headers

= 0.4.1 =
* fixed client_id hardcoded bug

= 0.4 =
* changed authentication details, own client_id required now
* added centering of widget

= 0.3.6 =
* added links to website

= 0.3.5 =
* bugfix

= 0.3.4 =
* added hashtag support
* bugfix

= 0.3.3 =
* fix for multiple widgets (thanks for bug report @kirbotica!)
* now possible to change cycle speed and number of latest instagrams
* cleanup and updates of 3rd party jQuery plugins

= 0.3.1 =
* cache fix
* moved to wp_remote_post & wp_remote_get

= 0.3 =
* Migrated to xAuth. After installation/update users will have to enter their Instagram login details (will be used only to get access token from Instagram and will not be saved or sent to someone else other than Instagram).

= 0.2.7 =
* Updated Instagram iPhone app version number. Apparently they are checking it.

= 0.2.6 =
* open_basedir check
* multiple widget cache fix
* custom cache duration

= 0.2.5 =
* paypal link

= 0.2.4 =
* bugfix

= 0.2.3 =
* jQuery code moved outside of wpinstagram.php
* container element changed from div to ul, li

= 0.2.1 =
* changed from anonymous to named functions

= 0.1.9 =
* yet another try to fix a jQuery conflict

= 0.1.8 =
* jQuery noConflict fix

= 0.1.7 =
* hopefully fixed possible conflict with fancybox-for-wordpress plugin

= 0.1.6 =
* added widget size options - Instagrams original sizes & custom size
* added "flattr this" link to WordPress plugins' area

= 0.1.5 =
* initial support for [instagram] shortcode.

= 0.1.4 =
* fancybox, cycle, easing, mousewhell jquery plugins are now included in plugins package
* changed way how jquery dependencies are loaded

= 0.1.3 =
* javascript now only loads if widget is activated

= 0.1.2 =
* some changes to plugin info

= 0.1.1 =
* Initial upload to WordPress plugin directory


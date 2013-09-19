=== Instagram for WordPress ===
Contributors: jbenders
Tags: widgets, photos, instagram
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 0.4.8

Simple sidebar widget that shows Your latest instagr.am pictures and picture embedder.

== Description ==

Simple sidebar widget that shows Your latest instagr.am pictures and picture embedder. Requires PHP curl extension.
To embed picture use [instagram] shortcode.
Example:
[instagram url='http://instagr.am/p/BSJRn/' size='large' addlink='no']

Sizes are: large (612x612px), middle (306x306px) and small (150x150px).
addlink='yes' encloses img element with a element.

== Screenshots ==

1. Setup view in WordPress administration panel ( Appearance > Widgets )
2. Setup view in WordPress administration panel ( Appearance > Widgets )

== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add 'Instagram' widget to Your sidebar via 'Appearance' > 'Widgets' menu in WordPress.
4. Follow the instructions for creating your own client for instagram
5. Enter the newly created details into the widget
6. Change title of widget to what ever You like.

== Changelog ==

= 0.4.8 = 
* fixed possible function collision by prefixing it

= 0.4.7 = 
* added local caching to prevent the instagram api from denying requests

= 0.4.6 = 
* removed some nasty code

= 0.4.5 = 
* fixed small bug

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


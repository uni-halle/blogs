== Notes ==

Featured images are only displayed on posts marked with the Audio post format.

== Changelog ==

= 1.3 May 22 2013 =
* Use a filter to modify the output of wp_title()
* Enqueue scripts and styles via callback
* Ensure captioned images do not overflow the content container
* Updates for 3.6 post formats compatibility

= 1.2 Nov 19 2012 =
* Added note about featured image limitations
* Improved structure of declaring theme support
* Minor bugfixes

= 1.1 Nov 5 2012 =
* Clean out unused functions
* Move functions for grabbing bits of content into a new file, for separation and organization
* Many updates for the "audio" post format, remove outdated code from js/audio-player.js, use core version of swfobject and list as a dependency of js/audio-player.js, remove unneeded jQuery dependency
* Make sure attribute escaping occurs after printing
* PNG and JPG image compression
* Add Jetpack compatibility file
* Remove loading of $locale.php
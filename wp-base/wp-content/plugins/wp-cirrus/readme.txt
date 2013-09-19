=== Plugin Name ===
Contributors: ga_ap, daill
Donate link: http://www.ga-ap.de/plugins/
Tags: tags, tag, cloud, meta, widget, javascript, css, html5, seo, tag cloud, sphere, 3D, cumulus
Requires at least: 3.0
Tested up to: 3.5.2
Stable tag: 0.6.11

A 3D JavaScript tagcloud inspired by WP-Cumulus.


== Description ==

WP-Cirrus is a plugin that displays a 3D tag cloud in your WordPress. It can be
used as a widget or anywhere on your blog using the [wp-cirrus] code.

WP-Cirrus was inspired by the famous WP-Cumulus plugin. WP-Cirrus uses no Flash,
but only JavaScript and CSS to display the cloud. It gets all meta tags directly
from WordPress.

Our plugin makes no use of external libraries that have to be loaded with it at runtime
(e.g. jQuery or even Flash). This makes it very small and fast to load.

As it's still under development more releases will be seen in near future.

Have fun and tell us what you think about it.


== Installation ==

1. Unzip the plugin archive.
2. Create a folder `wp-cirrus` in your `/wp-content/plugins/` directory.
3. Upload the files you just unzipped to this folder.
4. Activate the plugin through the `Plugins` menu in WordPress
5. That's it. Now activate it through the widget settings or via the code (see FAQ).


== Frequently Asked Questions ==

= How can I use the tag cloud on my pages? =

Add the `[wp-cirrus]` code somewhere on the page to display it.

= I found a bug. What can I do? =

Please tell us! http://www.ga-ap.de/plugins/wp-cirrus/


== Screenshots ==
1. A big sample cloud - of course not animated. You'll be able to move it around with your mouse and select the tags.
2. A cloud with some animation. This is an animated GIF from an early version. The real cloud is much smoother. We had to keep the image small here.


== Changelog ==
= 0.6.11 =
* bugfix release

= 0.6.10 =
* JavaScript has been recompiled with latest GWT 2.5.1
* reduced download size by up to 20%

= 0.6.9 =
* JavaScript has been recompiled with latest GWT 2.5

= 0.6.8 =
* JavaScript has been recompiled with latest GWT 2.4

= 0.6.7 =
* reduced download size by up to 60%

= 0.6.6 =
* JavaScript has been recompiled with latest GWT 2.3

= 0.6.5 =
* fixed a bug where cloud would not show up in IE6/7
* JavaScript has been recompiled with latest GWT 2.2


= 0.6.3 =
* JavaScript has been recompiled with latest GWT 2.1.1

= 0.6.2 =
* pages can now be displayed inside the cloud
* fixed a bug with maximum font size

= 0.6.1 = 
* fixed a bug where cloud would not display with certain themes

= 0.6.0.1 =
* bugfix

= 0.6 =
* this is the first release that makes use of the Google Web Toolkit (GWT)
* this one has all features from previous versions
* 0.6 has browser-specific optimized builds
* expect more features and bug fixes to come shortly

= 0.5.3 =
* compatibility update

= 0.5 =
* categories can now be displayed as well
* background and tag font colour can be changed
* removed args and added prettier form fields instead
* fixed error while using shortcode AND widget - shortcode has priority now
* added error handling

= 0.4.1 =
* update is highly recommended:
* removed WP version info from script/style

= 0.4 =
* fixed a problem with overlay on some plugins (e.g. Lightbox 2)
* if JavaScript is disabled on the client, tags are displayed more userfriendly now

= 0.3.6 =
* fixed critical offsetTop bug

= 0.3.5 =
* fixed bug where the cloud did not load properly in connection with other plugins
* compressed JS file for faster downloads (using javascriptcompressor.com)

= 0.3 =
* fixed fast rotating bug (scrolling issue)
* added slowdown on mouse out

= 0.2 =
* initial public release.


== Upgrade Notice ==
= 0.6.8 =
JavaScript has been recompiled with latest GWT 2.4

= 0.6.7 =
* reduced download size by up to 60%

= 0.6.6 =
JavaScript has been recompiled with latest GWT 2.3

= 0.6.5 =
Cloud did not show up in IE6/7

= 0.6.3 =
JavaScript has been recompiled with latest GWT 2.1.1

= 0.6.2 =
bugfixes and more features

= 0.6.1 = 
bugfix release

= 0.6.0.1 = 
bugfix

= 0.6 =
optimized build

= 0.5.3 =
compatibility update

= 0.5 =
lot's of new settings

= 0.4.1 =
removed WP version info from script/style

= 0.4 =
fixed bug with other plugins

= 0.3.6 =
fixed critical offsetTop bug

= 0.3.5 =
fixed loading bug

= 0.3 =
fixed rotation/scrolling

= 0.2 =
initial public release.


`<?php code(); // goes in backticks ?>`

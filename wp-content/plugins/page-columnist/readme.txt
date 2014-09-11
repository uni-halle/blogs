=== Page Columnist ===
Contributors: codestyling
Tags: pages, posts, columns, magazin, style, theming, plugin
Requires at least: 2.7
Tested up to: 3.1.3
Stable tag: 1.7.3

The behavior of single posts or static pages can be re-interpreted as columns and customized as usual.

== Description ==

This plugin allows you easily to get WordPress single posts/pages content to be shown automatically in a column based layout.
Various modes can be choosen, if you want to support additional header/footer appearances too.
This also includes possible different handling of paged posts/pages as the default WordPress implementation does. 
Also an extended user interface (Admin Center Extension) supports click 'n ready usage.
**New**: Starting with version 1.5.5 the plugin has been extended to be able to support also single posts. Prior versions did only support static pages.
**New**: Starting with version 1.7.0 the plugin supports now RTL column sorting, if current language is RTL based. Additionally the plugin now supports columnization also at overview pages. This can be configured for each post/page.

= Preview Assistance = 
1st step of Preview Assistance has been introduced as started feature, but some comming things are disabled.
Nevertheless you can adjust the column distribution (width of columns) by moving the spacer between by drag'n drop.
Each page remembers this setting after saving. To get back equalized default column sizes, just tick up/down the spacing spin.

= Virtual Paging vs. Overflow Hidden = 
Pages can now be defined only between 2 and 6 columns, but i think this may be enough even for fluid designs.
It's up to you what should happen to nextpage sections that are too much for one page. By default they will be hidden.
But you can force also virtual paging, so the overflowing sections build again one (or more) sub page at the same layout.
If the number of sections is to small for choosen layout, you will get a red warning for each "missing content".


= Requirements =
1. WordPress version 2.7 and later
1. PHP Interpreter version 4.4.2 or later

Please visit [the official website](http://www.code-styling.de/english/development/wordpress-plugin-page-columnist-en "Page Columnist") for further details, documentation and the latest information on this plugin.
You can also visit the new [demonstration page](http://www.code-styling.de/english/development/wordpress-plugin-page-columnist-en/demonstration-page-columnist "Page Columnist Demo") to get a first impression, what you can expect.

== Installation ==

1. Uncompress the download package
1. Upload folder including all files and sub directories to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to your pages overview and enjoy page configurations

== Changelog ==

= Version 1.7.3 = 
* Bugfix: adjusting percentage columns in preview failed by new jQuery versions.
* Bugfix: WordPress admin bar detection in preview mode to show assistant correctly

= Version 1.7.2 = 
* Bugfix: activation procedure changed in WP 3.0 so older versions may crash.

= Version 1.7.1 = 
* Feature: behavior at overview pages can now be set specific per page/post too (defaults to same as single page)
* Bugfix: detection of supported versions and warning attached
* Bugfix: filter priority increased 

= Version 1.7.0 = 
* Feature: supports now RTL language dependend column order
* Feature: for WordPress versions greated than 2.7 it's now possible to display columns at overview pages (categories/archives) too.
* Bugfix: debugging mode works without any side effects
* Bugfix: new post/page creation not longer falls back to 0% column width
* Bugfix: errors with loading translations

= Version 1.6.0 = 
* SVN error: WordPress Plugin Repository did only refresh the description but not the download!

= Version 1.5.5 = 
* Feature: only one javascript file necessary for different jQuery versions (includes hotfix handling inside)
* Feature: With this version not longer only pages can be columnized but also single posts !

= Version 1.3.0 = 
* Extension: WordPress Version 2.8 introduces a modified order to call loop hooks, so the content resampling did not affect the output.
* Bugfix: jQuery Version changed at WP 2.8 has damaged preview assistance scripts, 2 different java scripts necessary now

= Version 1.2.0 = 
* Browser Bugs especially at IE percentual calculations have been worked arround.
* Feature: dedicated layout definition
* Feature: drag 'n drop columns sizing
* Feature: virtual layout paging and hidden overflow support

= Version 1.1.6 / 1.1.7 / 1.1.8 =
* wordpress.org svn damaged some files!

= Version 1.1.5 =
* Security Bugfix: it was possible with enough investigation to change layout externally
* Bugfix: IE doesn't calculate correctly with a higher number of columns
* Bugfix: Preview doesn't show the selected layout but shown after Saving Page
* Feature: Preview Assistance introduced
(Because of security fix the features still in development are not all included. Only the column spacing modification per page and global are working.)

= Version 1.0 =
* initial version


== Frequently Asked Questions ==
= History? =
Please visit [the official website](http://www.code-styling.de/english/development/wordpress-plugin-page-columnist-en "Page Columnist") for the latest information on this plugin.

= Where can I get more information? =
Please visit [the official website](http://www.code-styling.de/english/development/wordpress-plugin-page-columnist-en "Page Columnist") for the latest information on this plugin.


== Screenshots ==
1. admin center page editor integration
1. onpage preview assistance and editor
1. virtual layout based paging
1. page overview column type information


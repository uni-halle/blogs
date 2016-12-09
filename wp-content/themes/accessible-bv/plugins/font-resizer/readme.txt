=== font-resizer ===
Contributors: cubetech, chrigu99
Donate link: https://www.cubetech.ch
Tags: font, size, increase, decrease, resizer, bigger, smaller, jquery, cookie, fonts, resize, change, wordpress, cubetech, webdesign, hosting, billing, widget, plugin, sidebar
Requires at least: 2.7
Tested up to: 4.5
Stable tag: 1.3.2
License: GPLv2

font-resizer allows the visitors of your website to change the content font size

== Description ==

This plugin allows you to give the visitors of your site the option to change the font size.
The plugin acts over jQuery and saves the settings in a cookie. So the visitor see the same font size if they revisit your site.
Which content is going resized, the resize steps and other options you can set on the admin page called "font-resizer".

== Installation ==

1. Upload the directory `font-resizer` to the `/wp-content/plugins/` directory or install the plugin directly with the 'Install' function in the 'Plugins' menu in WordPress
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the sidebar widget through the 'Design' menu in WordPress
1. If you would not use the widget, you can use the tag `<?php if(function_exists('fontResizer_place')) { fontResizer_place(); } ?>` somewhere
1. Define which content should be resized on the 'font-resizer' admin page (optional). If you are not familiar with html and css, select the body option (default). This would resize each content of your page.

== Screenshots ==

1. Adding the widget
2. A productive example of the plugin
3. Settings page

== Frequently Asked Questions ==

= How can i activate the function of the plugin? =
Go to the admin page of the plugin and select your option. If you are not familiar with html and css, select the body option (default). This would resize each content of your page.

= How can i use the plugin without the widget? =
Use this snippet of PHP code (in your theme or somewhere): <?php if(function_exists('fontResizer_place')) { fontResizer_place(); } ?>

= How can i change the color of the A's? =
With CSS in your theme.
Use something like:
li.fontResizer > a { color: blue; }

= How to contribute? =
If you have an idea or a pull request, contribute here:

https://github.com/cubetech/wordpress.font-resizer

= Are there more FAQ? =
Not yet, no. But feel free to contact us if you have a question! info@cubetech.ch

== Changelog ==

= 1.3.2 =
* HTML Bugfix

= 1.3.1 =
* Accessibility improvements (tab select and enter key press)

= 1.3.0 =
* New function: limit font size, add to option page
* Bug: replaced old path declaration
* Testing: WP 4.4 and actual Bootstrap theme

= 1.2.3 =
* Widget bug fix

= 1.2.2 =

* Added banner img

= 1.2.1 =

* Nothing relevant

= 1.2.0 =

* Fixed some deprecated functions

= 1.1.9 =

* Updated readme

= 1.1.7 =

* Little jquery bugfix for function ownid

= 1.1.6 =

* Fixed PHP problem

= 1.1.5 =

* Fixed problem with Internet Explorer

= 1.1.4 =

* Added option for cookie save time to admin pane
* Edited install instructions

= 1.1.3.1 =

* Added an answer to FAQ

= 1.1.3 =

* Fixed JavaScript issue with qTranslate
* Refactured jQuery scripts

= 1.1.2 =
* Added an option for changing the font resize steps
* Added comments to source code
* Cleaned up source code
* Changed css classes of the visible resizer element in the sidebar

= 1.1.1 =
* Bugfix for different directory structure (like language structure, yourdomain.tld/en/ for english)

= 1.1.0 =
* Added menu page
* Changed default resizable element from a div with id innerbody to body element
* Added uninstall hooks
* Added some answer to FAQ

= 1.0.0 =
* First stable version
* Publish the plugin

== About us ==

cubetech – we create your DIGITAL EXPERIENCE with WordPress
Our contact address: info@cubetech.ch


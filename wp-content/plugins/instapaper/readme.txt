=== Instapaper Read Later Links ===
Contributors: johnbillion
Tags: instapaper, bookmark, tumblr, read later, social bookmarking
Donate link: http://lud.icro.us/donations/
Requires at least: 2.7
Tested up to: 3.1.1
Stable tag: trunk

Allows you to automatically display Instapaper 'Read Later' links next to your blog posts.

== Description ==

This plugin allows you to display Instapaper 'Read later' links next to each post on your blog just like on [Give Me Something To Read](http://givemesomethingtoread.com/). You can either automatically insert the links adjacent to your blog entries, or you can just use the template tag to insert the links wherever you like.

== Installation ==

1. Unzip the ZIP file and drop the folder straight into your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Check out the front page of your blog. A 'Read Later' link will now show adjacent to each post.

= Usage =

By default, this plugin adds a 'Read Later' link adjacent to each blog entry on your blog. You can control where the 'Read Later' links show up by going to the Settings -> Read Later Links menu in WordPress.

If you choose not to automatically display them, you'll need to add the following code to your theme in order insert the 'Read Later' link for each post:

`<php do_action('read_later'); ?>`

The code should be inside the WordPress loop. If used outside the loop, it accepts an optional second parameter for the post ID.

== Screenshots ==

1. 'Read Later' links inserted automatically into the WordPress default theme

2. 'Read Later' links shown on [Ludicrous](http://lud.icro.us/) using the template tag to control placement

3. The options screen

== Frequently Asked Questions ==

= What the hell is Instapaper? =

From instapaper.com:

> Instapaper is a fast, easy, free tool to save web pages for reading later. When you find something you want to read, but you don't have time now, you click 'Read Later'. When you do have time to read, you visit Instapaper on your computer or phone and get whatever you wanted to read. 

Check out [instapaper.com](http://instapaper.com/) for all the details and to sign up.

= Why do the 'Read Later' buttons display incorrectly for some people? =

The buttons do not display correctly for visitors using Internet Explorer. Instapaper chooses not to support Internet Explorer, it has nothing to do with this plugin.

= Is this an official Instapaper plugin? =

No.

== Changelog ==

= 1.2 =
* Update to the new style button from Instapaper. The original black version no longer works.

= 1.1 =
* Option to choose which style 'Read Later' button to use.
* Misc plugin tweaks.

= 1.0.1 =
* Update settings screen to ensure compatibility with WPMU 2.9.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.1 =
There are now two different styles of 'Read Later' buttons to choose from.

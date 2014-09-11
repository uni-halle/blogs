=== Kindle This ===
Tags: Amazon, Kindle, sidebar, widget, plugin, shortcode
Stable tag: 2.3
Requires at least: 2.9
Tested up to: 3.3.1
Contributors: Keith P. Graham
Donate link: http://www.blogseye.com/buy-the-book/

Sends a blog post or page to a user's kindle - widget and shortcode.

== Description ==
Kindle-This is a sidebar widget that displays a button for sending a blog page to a user's Kindle using kindle.com automatic conversion. It also includes a shortcode to send individual posts or pages to a Kindle.

The contents of the current page are extracted and the title, post date, and content are formatted into a simple page that is sent to the kindle service for conversion into a kindle file. The results are not a web page, but a Kindle document so that links, images, embeds, JavaScript, etc may not appear. This conversion process at Amazon does this, and we have not control over it.

The Kindle-This form requires the user to enter his Kindle email id and the email address that is authorized to send documents to the Kindle service. The authorized email is not stored in order to prevent Kindle spam.

The plugin uses only the kindle.com service so the document will appear on the Kindle device as soon as the user makes a wifi connection. The free.kindle.com address no longer works for me. The web address is now an option. You will have to check "Use free.kindle.com" or "Use kindle.com" addresses.

== Installation ==

Widget Install:
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. In Appearance/Widgets, drag the widget to a sidebar.
5. Edit the sidebar title if needed.
6. Save

Shortcode Usage:
Place the shortcode [kindlethis] anywhere on any post or page. Anyone using the form will receive the post on their kindle.
You can style a div containing the form by using a style=parameter such as [kindelthis style="margin-left:auto;margin-right:auto;]"

== Changelog ==

= 1.0 =
* initial test release

= 1.1 =
* Fixed bug in unused code

= 1.2 =
* Added Customized header and footer

= 1.3 =
* Added shortcode for individual pages

= 2.0 =
* Use AJAX to send message rather than direct blog access.

= 2.1 =
* encoded title for utf-8. Placed the javascript in a separate js file. It was screwing up too many people by putting it in the footer.
* Fixed bugs in the template design. I found three different ones that prevented it from working. I apologise for that.

= 2.2 =
* Made the default email address for conversion selectable. Put the word Convert in the subject line.

= 2.3 =
* Wrong version of 2.2 was uploaded. Sorry for the problem.

== Support ==
This plugin is free and I expect nothing in return. If you wish to support my programming, buy my Kindle ebook (cheap): 
<a href="http://www.blogseye.com/buy-the-book/">Error Message Eyes: A Programmer's Guide to the Digital Soul</a>




=== My Page Order ===
Contributors: froman118
Donate link: http://geekyweekly.com/gifts-and-donations
Tags: page, order, sidebar, widget
Requires at least: 2.8
Tested up to: 3.3.2
Stable tag: 3.3.2

My Page Order allows you to set the order of pages through a drag and drop interface.

== Description ==

[My Page Order](http://geekyweekly.com/mypageorder) allows you to set the order of pages through a drag and drop interface. The default method of setting the order page by page is extremely clumsy, especially with a large number of pages. The plugin also installs a replacement widget that exposes all the options of the wp_list_pages template function.

= Big Update! =

My Page Order has been out since WP 1.5 (2006) and hasn't really changed since then. As of version 2.8.6 of the plugin I'm breaking backwards compatibility to add new features like a replacement widget. Keep using version [2.8.3](http://downloads.wordpress.org/plugin/my-page-order.2.8.3.zip) if you are not on WP 2.8 yet.

== Change Log ==
= 3.3.1 =
* Added Danish translation
= 3.0a =
* Filtering out Auto Drafts now
* Fix for widget to work with Twenty Ten, if "Show Home" is not used, falls back to using wp_list_pages instead of wp_page_menu
* Fixed error appearing in debug mode
= 3.0 =
* Update for compatibility with 3.0
* Switched way menu item was being added, any permission issues should be fixed
* Updated drag and drop to include a placeholder, makes it much easier to see where items will move
* Updated styles to fit in with Wordpress better
* Updated page code to use regular submit buttons, less reliance on Javascript and query strings
* Page titles now wrapped in localization code, thanks for the tip Florian
* Added Polish translation, thanks Cezary
* Added Turkish translation, thanks Demircan
* Added Finnish translation, thanks Jussi
= 2.9.1 =
* Trashed pages are now being filtered out.
= 2.8.6 =
* Reworked old My Page Order code, added replacement widget with more options. Starting fresh with support for WP 2.8 and above only.
* The PO file has changed and translations will need to be updated
= 2.8.3 =
* Trying to fix Javascript onload issues. Settled on using the addLoadEvent function built into Wordpress. If the sorting does not initialize then you have a plugin that is incorrectly overriding the window.onload event. There is nothing I can do to help. 
= 2.8b =
* Fixed drag and drop bug for sure this time
* Added Czech translation (Jan)
= 2.8a =
* Fixed bug breaking drag and drop in 2.7 (I think, can't test so let me know)
* Added translations: Belorussian (Fatcow), French (updated, Annelies)
= 2.8 =
* Updated for 2.8 compatibility
= 2.7.1 =
* Translations added and thanks: Spanish (Karin), German (Bernhard), Swedish (Joakim), Italian (Stefano and Danny)
= 2.7 =
* Updated for 2.7, now under the the new Page menu.
* Unpublished pages now show up in the Subpage dropdown (thanks Josef)
* Moved to jQuery for drag and drop
* Removed finicky AJAX submission
* Added missing translation phrase to POT, send me updated MO files and help fill in missing translations
* Translations added and thanks: Russian (Flector), French (Merimac), Persian (Mohammad and Mohammad), Dutch (Anja).
= 2.6.1 =
* Localized strings and added .po files for translation. If you are interested in translating send me an email.

== Installation ==

1. Install plugin and activate it on the Plugins page
2. Go to the "My Page Order" tab under Pages and specify your desired order for pages
3. If you are using widgets then just make sure the "Page" widget is set to order by "Page order". The plugin also installs it's own widget with more options.
4. If you aren't using widgets, modify your sidebar template to use correct sort order: `wp_list_pages('sort_column=menu_order&title_li=');`

== Frequently Asked Questions ==

= Why isn't the order changing on my site? =

The change isn't automatic. You need to modify your theme or widgets.

= Why isn't this already built into WP? =

I don't know. Hopefully it will be in a future release in one form or another because the current method sucks.

= Like the plugin? =

If you like the plugin, consider showing your appreciation by saying thank you or making a [small donation](http://geekyweekly.com/gifts-and-donations).
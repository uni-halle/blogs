=== Skype Legacy Buttons ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Skype%20Legacy%20Buttons&item_number=2%2e8&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us
Tags: skype, skype button, call, support, widget, widgets, post, posts, quicktag, tinymce, tinymce3, im, skype-button
Requires at least: 3.2
Tested up to: 4.3
Stable tag: 3.0.2

Unlimited, highly customizable and accessible (!) Skype buttons through widgets, post shortcode/quicktag and template tags.

== Description ==

NOTICE: as of Mai 2015, Microsoft has officially dropped Skype's Online Status service [read more](https://support.skype.com/en/faq/FA605/how-do-i-set-up-the-skype-button-to-show-my-status-on-the-web-in-skype-for-windows-desktop) and consequently, this plugin will no longer be able to provide buttons that indicate online status. Only legacy Skype buttons that do _not_ reflect the users online status remain available.

This WordPress plugin allows you to add one or multiple, customizable and accessible (!) Skype buttons to your site. You can pick any of the (still) available legacy Skype button themes and place them in your *pages and posts content* (with a neat little Skype button in the WYSIWYG editor), in your sidebar via *widgets* (unlimited number) or anywhere in your theme template files.

The Skype Buttons admin page and Skype Widget options are adequately annotated but there is also a **Quick Guide** section with lots of information on all the possible ways to get you blog Skyped. If you still cannot get it working the way you want to, find a Skype link to the developer on the Notes & Live Support section.

= Translations =

Since version 2.6.4.0, Skype Online Status is ready for internationalization. Anyone who wants to contribute can do so by making a transation. Read [translation instructions http://svn.wp-plugins.org/skype-online-status/trunk/languages/language-support.txt "Skype Online Status translation instructions" (also included in the latest package) for more info.

- **Basque** * Silvia Ribés http://silviaribes.net/ (version 2.9.3)
- **Belorussian** * M. Comfi http://www.comfi.com/ (version 2.7)
- **Bulgarian** * Nikolay Nikolov http://en.chat4o.com/ (version 2.8.2)
- **Croatian** * Saša http://lasersko-graviranje.bisa.hr (version 2.9.4)
- **Danish** * Georg S. Adamsen http://wordpress.blogos.dk/2009/03/18/skype-online-status/ (version 2.8.2)
- **Dutch** * R.A. van Hagen http://status301.net/ (version 3.0)
- **Estonian** * Iflexion Design http://iflexion.com/ (version 2.7)
- **Finnish** * Jani Alha http://www.wysiwyg.fi/ (version 2.8.5)
- **French** * Emmanuelle Traduction http://www.translatonline.com/ (version 2.8.2)
- **German** * Fabian Schulz http://www.fabianschulz.net/blog/2009/08/05/skype-online-status/ (version 2.7)
- **Hindi** * Outshine Solutions http://outshinesolutions.com (version 2.8.2)
- **Indonesian** * Nasrulhaq Muiz http://al-badar.net (version 2.8.4)
- **Italian** * Gianni Diurno http://gidibao.net/index.php/portfolio/ (version 2.6.9)
- **Japanese** * Ryuei Sasaki http://ryueisasaki.com/ (version 2.8.2)
- **Lithuanian** * Vincent G http://www.host1free.com (version 2.8.2)
- **Portuguese** * Miguel Angelo Patricio http://www.miguelpatricio.com/ (version 2.8.2)
- **Russian** * Leonid Jack http://studio-xl.com/ (version 2.7)
- **Serbian** * Ognjen Djuraskovic http://firstsiteguide.com (version 2.9.3)
- **Spanish** * Rodolfo Castro & Rocio Moreno http://www.ars-legalconsulting.es/blog/ (version 2.7)
- **Turkish** * Hakan Er http://hakanertr.wordpress.com/ (version 2.8.8)
- **Ukrainian** * Michael Svystun http://emarketingblog.com.ua/ (version 2.7)

New translations and their Authors will be mentioned here.

= Donations =

Top donators making my continued work possible:

- **Claudio Cosmaro** Social Hosting http://socialhosting.net/
- **G. Pelzer** Life is a journey. Life Coaching http://vivocoaching.com/
- **Yamisi Daniel, USA** What could you do with more time? inTouch Virtual Assistance http://www.intouchva.com/
- **Michelle Vandepas, USA** Live Your life's Purpose. Divine Purpose Unleashed http://divinepurposeunleashed.com/


== Installation ==

Quick installation: [Install now http://coveredwebservices.com/wp-plugin-install/?plugin=skype-online-status) !

 &hellip; OR &hellip;

Search for "Skype Legacy Buttons" and install with that slick **Plugins > Add New** back-end page.

 &hellip; OR &hellip;

Follow these steps:

Note: When upgrading from version 2.5.x or previous: please deactivate plugin and remove the old directories and files before uploading! 

 1. Download archive and unpack.
 2. Upload (and overwrite) the /skype-online-status/ folder and its content to the /plugins/ folder. 
 3. Activate plugin on the Plug-ins page
 4. Configure (or update) your SkypeID and settings on the Settings > Skype Buttons page
 5. Activate the widget or put `<?php if (function_exists(get_skype_status)) { get_skype_status(''); } ?>` in your sidebar.php or any other template file or use the Skype quicktag button to insert the **[**skype-status**]** shortcode in your posts/pages to display the Skype button anywhere on your website. 

Read more on usage in the Quick Guide section of the Settings > Skype Buttons page, available after installation.

== Frequently Asked Questions ==

= Why is my Skype Online Status button not working anymore? =

As of Mai 15th, 2015, Microsoft has officially dropped Skype's Online Status service. Consequently, all online status buttons stopped working but legacy Skype buttons that do not reflect the users online status are still available.

Go to your Settings > Skype Buttons (or Appearance > Widgets) and switch to any of the available preset themes to get a working Skype Button back.

= I have found a bug. Where do I report it? =

For bug reports, go to [WordPress Support &raquo; Skype Online Status http://wordpress.org/tags/skype-online-status "WordPress Support &raquo; Skype Online Status") and describe what is happening there. Please provide as much related info as you can but at least give me your plugin and Wordpress version number.

= I have a feature request. Where do I ask? =

For all 'normal' feature requests and suggestions go to [WordPress Support &raquo; Skype Online Status http://wordpress.org/tags/skype-online-status "WordPress Support &raquo; Skype Online Status") but if you want custom development, please contact me directly from the *Notes & Live Support* section on the Skype Online Status options page or browse to my website [Status301 http://status301.net/ "Status301.net"). I will gladly consider any proposal.

= I have trouble setting up a Skype button on my blog. Where do I get help? =

Have you read the *Quick Guide* section of your WordPress Options > Skype Status page, available after installation already? For all remaining questions, post them on [WordPress Support &raquo; Skype Online Status http://wordpress.org/tags/skype-online-status "WordPress Support &raquo; Skype Online Status") or go for Live Support via Skype Chat to the Support section on the Skype Online Status Options page after installation. I will be happy to respond :)

= I have installed the plugin, fiddled with the settings, added a widget/shortcode, there is no error message but I see no button! =

This usually happens when the plugin cannot find a Skype ID to work with. You can set a different Skype ID for each widget but be sure to set a Skype ID on your Skype options page so the plugin as at least one to fall back on. If you still see no Skype button, reset all options using the Reset button or the Remove button to remove-uninstall-reinstall and start completely fresh.

= I have installed the plugin and it is working fine, but now there is a ad link below my Skype button. What's with that? =

By default, the plugin displays a link below each Skype button that allows visitors without Skype to download it. This link goes via the plugin creators Skype Affiliate account so with each new Skype install there will be some small revenue for maintenance and support of this plugin. If you object to that, you can either disable the link, change is to a direct link or replace it with your own affiliate link on the Skype Online Status options page.

= I would like to contribute. Is that possible? =

Of cource! What did you have in mind? Monetary funding is always appreciated but if you can help me out in development, that's cool too :) Right now, translation (.mo) files are MORE then welcome! Read [translation instructions http://svn.wp-plugins.org/skype-online-status/trunk/languages/language-support.txt "Skype Online Status translation instructions" (also included in the latest package) for more info.

Any small donation via [PayPal https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Skype%20Online%20Status&item_number=2%2e6%2e2%2e9&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8 "PayPal Donate") to keep me developing is _**VERY**_ welcome. Thanks!

Read more on usage in the **Quick Guide** section of your WordPress Settings > Skype Buttons page, available after installation.

== Screenshots ==

1. Set your basic fall-back and advanced options on the Skype options page.
2. Activate a Skype widget and either set alternative options or leave blank and fall-back options will be used.
3. Example of Skype button widget in sidebar.

== Other Notes ==

This plugin has grown a lot since the start but it should be mentioned that it was built upon the neat little plugin Skype Button v2.01 by Anti Veeranna. Many thanks!

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

= Support =

Post your questions on [WordPress Support &raquo; Skype Legacy Buttons http://wordpress.org/tags/skype-online-status "WordPress Support &raquo; Skype Lecgacy Buttons") or go for Live Support via Skype Chat to the Support section on the Skype Buttons Options page after installation.

= Road Map =

For version 3.5 and beyond :)

- add the modern Skype buttons
- integration with WP user system (skype field on the Profile page, ++)
- More translations! Read [translation instructions http://svn.wp-plugins.org/skype-online-status/trunk/languages/language-support.txt "Skype Legacy Buttons translation instructions" (also included in the latest package) for more info.

= Bugs =

Please report anything you find on [WordPress Support &raquo; Skype Legacy Buttons http://wordpress.org/tags/skype-online-status "WordPress Support &raquo; Skype Legacy Buttons")

When reporting bugs, please describe as carefully as possible and provide information like the version number of the plugin, WordPress and - if relevant - server security settings, PHP and MySQL version. Provide as much related info as you can, so we can reproduce the error... And fix it :) 

= Known issues =

- The javascript provided by Skype that detects whether Skype is installed (client side), does not work properly on Linux. It defaults to displaying a download popup even if Skype is installed on the visitors computer after the Skype button is clicked. Hopefully - but I doubt it - Skype will fix this some day :$ 


== Upgrade Notice ==

= 3.0.2 =
Deprecating PHP4 style constructor

== Changelog ==

= 3.0.2 =
* Deprecating PHP4 style constructor

= 3.0.1 =
* Translation strings updated
* FIX: z-index for dropdown going under form input elements, props to brit77

= 3.0 =
* Removed status button templates and functionality because Skype dropped support
* Admin improvements
* Local resources as option for sites on HTTPS

= 2.9.7 =
* FIX: Prevent skype.com redirect/error from showing

= 2.9.6 =
* Modifications for SSL compat (as far as possible)

= 2.9.5 =
* Strip "live:" and "@live.com" from Skype ID to prevent parse error from Skype server. Still waiting for real fix by Microsoft.

= 2.9.4 =
* Some admin changes
* Basque and Croation translations
* Skype popup position fix

= 2.9.3 =
* BUGFIX: PHP Strict notices

= 2.9.2 =
* Multilingual status images
* BUGFIX: language tag appearing in Skype ID
* BUGFIX: .png appearing in Skype ID

= 2.9 =
* Reintroduction of the online status in ALT text
* BUGFIX: username with dot always displaying offline status image
* BUGFIX: Undefined and other PHP notices removed

= 2.8.8 =
* NEW: Turkish translation
* Undefinded value notice removed
* BUGFIX: Skype status server error response parced as status

= 2.8.6 =
* URL changes.

= 2.8.5 =
* BUGFIX: Custom link slashes

= 2.8.4 =
* Admin page improvements
* Switch to classes
* Switch to Widget class API
* New parameters for shortcode
* Interactive RTE shortcode button
* More translations! Thanks, everybody :)

= 2.8.3 =
* update: POT-file for translators
* update: Danish translation
* NEW: Japanese translation
* NEW: French translation

= 2.8.2 =
* Live Support for contributors
* hiding of 5x5 white square created by Skype detection SWF Object
* bugfix: RTE button missing

= 2.8 =
* new skypeCheck script for Windows7 / IE8 compatibility
* new shortcode in posts and pages can handle options like skype_id to override default settings
* load skypeCheck script only when needed

= 2.7.9.1 =
* bugfix: back-end style and script load

= 2.7.9 =
* skypeCheck javascript moved to footer to improve experienced/visual page load times
* bugfix: render no button/widget if skypeid is missing
* bugfix: widget blank page after setting Full Name value
* bugfix: slow load times on Widgets page
* changes to options page
* code cleanup
* new Ukrainian translation

= 2.7 =
* German, Spanish, Ukranian, Russian and Belorusian translations
* `wp_remote_fopen` replacing own cURL/`remote_fopen` routine
* admin page revision for WP 2.8
* code cleanup and multiple online status check streamlining
* switch to global WP constants (like `WP_CONTENT_DIR`)
* bugfix: multiple widget theme preview

= 2.6.9 = 
* Danish + Italian translations 
* bugfixes in install routine and detect blog language on reset
* bugfix: allow the use of cURL library (if available) while `allow_url_fopen` is off
* Removal of good old Buttonsnap Library to avoid showstopper error in WP 2.7 
* adaptation of settings page to fit the new WP 2.7 backend

= 2.6.4 =
* Internationalization: Read [translation instructions http://svn.wp-plugins.org/skype-online-status/trunk/languages/language-support.txt "Skype Online Status translation instructions") for more info.

= 2.6.3 =
* Implement the use of cURL if available
* Multiple widgets!! 
* Thank-you box for donators at backend
* Bugfixes: widget options page function, widgets for WP < 2.5, `get_skype_status` and valid characters in SkypeID

= 2.6.2 =
* bugfix in Custom template save to db (stripslashes)
* heaps more themes 
* new {function} tag to My Status templates 
* improved widget with preview

= 2.6.1 =
* automatic blog language detection for status text
* some small bugfixes and code improvements
* complete removal button
* simple widget
* removed built-in update checker (redundant since WP2.5 auto-update) 
* add your own download link
* improved `reg_exp` for quicktag replacement (defeating wpautop's wrapping p)
* minor changes in available settings (newline for download link optional)
* fixed &-sign in fields causing failed w3c validation

= 2.5 =
* made quicktag work for 2.1+ new TinyMCE button plugin routine

= 2.4 =
* onkeydown action on admin textarea
* backwards compatibility with PHP versions previous to 4.3 ( fallback to `file()` instead of `file_get_contents()` ) 
* check for allow_url_fopen before remote file reading (used in status check and upgrade check) with dynamic options change

= 2.3 =
* Download Skype now! link (with option to change text or disable), more template files and an upgrade function

= 2.2 =
* moved buttonsnap.php
* changes to Quick Guide, template files and Live Support 
* bugfixes: 
 1. quicktag button not showing; 
 2. multiple skype buttons in 1 post not showing
* minor changes to admin page
* global string for speed improvement

= 2.1 =
* Skype default status texts in different languages

= 2.0 =
* editable template and live support link
* minor bugfix (altered defaulting to fallback template procedure)

= 1.9 =
* RTE guicktag button for <!--skype status--> hook

= 1.8 =
* improved performance by loading template in database

= 1.7 =
* added post hook <!--skype status--> and appended instructions to quickguide


= 1.6 =
* templating guide 
* redesign Options > Skype Status page

= 1.5 =
* plain text fallback template in core code

= 1.4 =
* reset button 
* default settings

= 1.3 =
* new template tags {username} {sep1} {sep2}

= 1.2 =
* bugfixes:
 1. inconsistent options page form-labels; 
 2. `skype_status_check` not defaulting to `status_error_txt` when mystatus.skype.com is off-line 

= 1.1 =
* added new text template file

= 1.0 =
* instructions (quick guide)
* skype user name
* customizability for `get_skype_status('options')`
* redesign admin interface
* lot's of new settings and template tags
* added function `skype_parse_theme()` and `skype_status_check()`

= 0.1 =
* function and syntax conversion from plugin Skype Button (by Anti Veeranna, discontinued)
* starting date: 2006-03-03


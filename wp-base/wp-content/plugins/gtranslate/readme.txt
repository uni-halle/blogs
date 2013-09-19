=== GTranslate ===
Contributors: edo888
Author: Edvard Ananyan
Tags: widget, plugin, sidebar, google, translate, translation, automatic translator, google translate, ajax translator, jquery translator
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 1.0.37
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get translations with a single click between 58 languages (more than 98% of internet users) on your website!

== Description ==

This module uses Google Translate automatic translation service to translate your web page with Google power. With 58 available languages your site will be available to more than 98% of internet users.

Please `use` [GTranslate Forum](http://gtranslate.net/forum/) for your questions and support requests!

* Hides "Suggest better translation" pop-up
* Hides Google top frame after translation
* Mouse over effect
* Flags combined in one file to load faster
* Analytics
* Option to open translated page in new window
* Option to translate the page on fly
* Available styles Dropdown/Flags/flags with dropdown
* Valid XHTML

**Watch GTranslate Tour**
[vimeo http://vimeo.com/30132555]

== Installation ==

1. Upload `gtranslate` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can add a widget to your website or use [GTranslate] syntax inside pages where you want it to appear.

== Configuration ==

To configure the widget go to Settings -> GTranslate

== Frequently Asked Questions ==

= It doesn't work, what to do? =
Please check the [Troubleshooting](http://gtranslate.net/forum/troubleshooting-gtranslate-free-t370.html) and feel free to create a new topic if you cannot find your case.

= Where I can see analytics data? =
You need to login to your Google Analytics account -> Content -> Event Tracking. The event name will be GTranslate and you will see event categories for each language code. If you want to see French language usage you can click on fr and you will see which pages are translated to French by your visitors.

= What is the quality of translation? =
It's a Google translation, hence Google quality.

= Which languages are supported? =
Here is the list: Afrikaans, Albanian, Arabic, Armenian, Azerbaijani, Basque, Belarusian, Bulgarian, Catalan, Chinese, Croatian, Czech, Danish, Dutch, English, Estonian, Filipino, Finnish, French, Galician, Georgian, German, Greek, Haitian Creole, Hebrew, Hindi, Hungarian, Icelandic, Indonesian, Irish, Italian, Japanese, Korean, Latvian, Lithuanian, Macedonian, Malay, Maltese, Norwegian, Persian, Polish, Portuguese, Romanian, Russian, Serbian, Slovak, Slovenian, Spanish, Swahili, Swedish, Thai, Turkish, Ukrainian, Urdu, Vietnamese, Welsh, Yiddish

= Can I exclude some parts from being translated? =
Yes, you need to wrap the text you don't want to be translated with &lt;span class=&quot;notranslate&quot;&gt;&lt;/span&gt;.

== Screenshots ==

1. Main View

== Changelog ==

= 1.0.37 =
* Bug fixed with new versions of PHP
* Translation queue added

= 1.0.36 =
* On Fly IE9 bug fixed

= 1.0.35 =
* On Fly method is back

= 1.0.34 =
* Error fixed with Chinese language in Enterprise mode

= 1.0.33 =
* Support for Enterprise version added

= 1.0.30 =
* Link and call home updated

= 1.0.29 =
* Custom update checker added

= 1.0.28 =
* Links changed

= 1.0.27 =
* Changed text in noscript tag

= 1.0.26 =
* Fixed issue with SSL admin

= 1.0.25 =
* Fixed installation notification on update event
* Removed unnecessary commented code

= 1.0.24 =
* Tracking added for collecting statistics

= 1.0.23 =
* Bug fixed with javascript jquery loading option

= 1.0.22 =
* Bug fixed with language change in Pro mode
* Added noscript tag

= 1.0.21 =
* Statistics collector temporarily disabled

= 1.0.20 =
* Changed the statistics collector server from Google AppEngine to GoDaddy

= 1.0.19 =
* Added a dummy img to collect usage statistics

= 1.0.18 =
* Fixed issue with the parameters saving in the admin

= 1.0.17 =
* Minor changes in readme.txt

= 1.0.16 =
* Added [GTranslate] syntax to be used inside wordpress articles/pages

= 1.0.15 =
* Added aff link to track visits from wordpress on my site

= 1.0.14 =
* Added notes, so people will not copy the code into their posts

= 1.0.13 =
* jQuery conflicts fixed, using default wp jquery library
* jQuery Translate updated to v1.4.7
* CSRF Security Vulnerability fix

= 1.0.12 =
* No changes made

= 1.0.11 =
* Analytics feature implemented. If you have Google Analytics _gaq code on your site you can enable it and see the language usage.
* Fixed issue with the cookie path in On Fly method

= 1.0.10 =
* Bug fixed: configuration settings were lost after update
* FAQ updated
* Changelog reordered

= 1.0.9 =
* Updated the description

= 1.0.8 =
* Keywords added in the description page
* FAQ updated

= 1.0.7 =
* Widget title changed
* Link added

= 1.0.6 =
* Bug fixed: magic_quotes_gpc problem

= 1.0.5 =
* Bug fixed: settings save problem

= 1.0.4 =
* Minor changes

= 1.0.3 =
* Stable version released

= 1.0.2 =
* RC2 version

= 1.0.1 =
* RC1 version

= 1.0.0 =
* Initial version for WordPress

== Upgrade Notice ==

= 1.0.22 =
Users are highly recommended to upgrade to this version!
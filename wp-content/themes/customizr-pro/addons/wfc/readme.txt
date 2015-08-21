###################### Copyright ######################
WordPress Font Customizer is a WordPress plugin designed and developed by Nicolas Guillaume (nikeo) in Nice, France (www.presscustomizr.com), and distributed under the terms of the GNU GPL v2.0 or later.
Enjoy it!


####################### Licenses #######################
Unless otherwise specified, all the theme files, scripts and images
are licensed under GNU General Public License version 2, see file license.txt.


############## DOCUMENTATION AND SUPPORT ###############
DOCUMENTATION : http://presscustomizr.com/extension/wordpress-font-customizer/
SUPPORT : http://presscustomizr.com/support-forums/


#######################  Changelog ######################
= 2.0.12 July 29th 2015 =
* updated : (css) customizr.json : added a specific class for the new Customizr side menu
* fix : make the adding of custom fonts a little bit easier with the filter : 'tc_font_customizer_control_params'
* fix: add rules to style grid post list titles

= 2.0.11 April 10th 2015 =
* updated : added missing customizr skins
* updated : site name and copyright dates
* Fix : get the right theme name when previewing a non active theme
* Fix : properly loading of textdomain when used as Customizr Pro addon
* Fix : extend first-letter fix to customizr-pro
* Fix : front js register and enqueue jquery if necessary

= 2.0.10 January 25th, 2015 =
* Hide controls on load when they are not wrapper in zones, since wp 4.1+

= 2.0.9 Decembre 23rd, 2014 =
* 0af5a1c Safer fix for the hidden font customizer controls. Cross browser compatible and compatible with  WP version 4.1+

= 2.0.8 Decembre 21th, 2014 =
* 9176748 follow up of the font customizer controls not showing up. Additional delay added to init the sections

= 2.0.7 Decembre 20th, 2014 =
* ba45f18 fix the api.reflowPaneContents bug

= 2.0.6 Decembre 7th, 2014 =
* 6fd2a06 fix plugin / addon collisions

= 2.0.5 Decembre 6th, 2014 =
* b11d51a change green color to #27CDA5 / #1B8D71 fix minor CSS issues in the customizer
* 830f3e8 scrollTimer set to 600ms instead of 100ms

= 2.0.4 December 6th, 2014 =
* 8acb568 multiple Google font requests optimization during font selection request necessary characters only

= 2.0.3 December 6th, 2014 =
* e94afbf displays unminified script in plugins dev mode only AND if unmified file exists!

= 2.0.2 December 6th, 2014 =
* 2709680 update the digital store url
* 3194f29 include git folder and files when copying in customizr-pro => manage it
* e17a149 include git folder for addon
* 2c06e2c change the addon path from inc/addon to addon

= 2.0.1 December 3rd, 2014 =
* e15b3a8 fix stepper issue when not using customizr theme
* afd2d6e customizer control script displays unminified script in plugins dev mode only

= 2.0.0 December 2nd, 2014 =
* 2c78d45 filepath : dynamic value setting with the watch event
* 2322a40 jshint fixes
* 7bbf510 added underscore library dependency
* b0fcf26 new version first grunt build
* 4566281 setup wfc grunt task for dev and build
* 3ae32ce Updated conditions to load assets of jquery plugins icheck, selecter, stepper in customizer.
* 4a8530a fix selecter style issue
* d55086e //loads the jquery plugins css assets when theme is (OR) :     //1) customizr version < 3.2.5     //2) any theme different than customizr-pro
* 271112c build customizer control script with requirejs grunt task
* f05f2ab split front script and concat it with grunt
* 7659d93 grunt setup
* 56e3bf5 removed is_user_logged_in() function called too early. Replaced by a daily refreshing of the selector settings + a $_GET parameter to force de refresh if need* f71d400 replace tc_wfc by {$_opt_prefix}
* d96bcc5 loads the jquery plugins css assets in plugin mode only
* b587ae8 reorganize the jquery plugin css stylesheets and img resources
* 1ba3359 always update the json settings from file if user logged in added a customizr-pro.json = copy of customizr.json
* ce306b7 replace wp built-in function plugins_url() by a re-built url with sprintf and TC_WFC_BASE_URL
* 49b7310 check if plugins_loaded before registering plugin specific actions
* e647be1 plug_lang is 'customizr' in addon context
* 48ee754 dont load activation key/updates classes if pro addon context => check on did_action('plugins_loaded')
* 1927e32 plug option prefix is different if plugin used as a pro theme addon => check if did_action ('plugins_loaded')
* 5723e89 fix customizer ui style bugs
* 5b74c63 Change TC_PLUG_DIR_NAME to TC_WFC_DIR_NAME

= 1.20 november 5th, 2014 =
cf67669 (HEAD, dev) Require.js last build before production
0cadf3b Avoid conflict with Font Customizer WP.org plugin.
0bb741b (github/master, master) Fix the menu item issue in Customizr theme
e54fea1 (github/dev) Fix the reset single selector issue =>
1f7a4c9 Fix !important flag bug. Reset font-family property
41f13cb If WP_DEPUG is enabled, loads unminified scripts and stylesheets
c35337c if js app exists on server AND WP_DEBUG enabled => open the dev set js files
a9a0ce4 Verify if select, icheck, stepper are already loaded

= 1.19 november 3rd, 2014 =
* fixed (php) system info instanciation only once.
* improved(php, js) control script is now registerd before beeing enqueued
* (css, php:class_dyn_style.php) fix the first letter issue in the menu (reported here http://presscustomizr.com/support-forums/topic/help-header-menu-font/)
* (css) customizer reset icon fixed
* (js) the stepper js control in the customizer now allows negative value. => useful to set a negative letter-spacing for example.
* (js, css) Plugin version has been added a query parameter to all resources in wp_enqueue...()

= 1.18 July 1, 2014 =
* fixed (php) minor bug fixed on editor style fonts update after theme switch

= 1.17 June 29, 2014 =
* added : (php, css, js) new option to add/edit/remove custom selectors
* added : (php, css, js) compatibility with any WordPress themes

= 1.16 June 12, 2014 =
* fixed : (php, css) websafe fonts : removes single quote wrapper on front end stylesheet.

= 1.15 May 19, 2014 =
* fixed : (js) added important property to font-family in customizer live preview
* improved : (css, php) dynamic style is now loaded in two parts in wp_head : fonts are loaded first before everything (avoid thre FOUC) and the rest is loaded after every other stylesheets

= 1.14 May 19, 2014 =
* fixed : (css) some menu items css properties were not applied. Improved specificity in customizr.json

= 1.13 May 18, 2014 =
* fixed : (css) icons hiding set as !important
* improved : (js) Performance improvement : to avoid the Flash of Unstyled Content (FOUC) google fonts are not handled with webfontload.js anymore. They are added earlier in <head>.
* improved :(php, css) dyn-style is written in head on front end (=> better loading performance than the previous dyn_style.php).
* added : (css, php) dyn_style.php and gfonts have been added in the editor style
* added : (php) link to settings in plugin page
* added : (php) system info page for better support
* added : (php) upgraded from option
* improved : (css) fontselect.css has been minified
* todo : (css) adapt the dyn_style when loaded for editor style

= 1.12 =
* fixed : (css, js) webfontload : avoid the FOUC for IE
* improved : (css) minified version of font_customizer.css for front end style
* improved : (php) version of plugin added as parameter when calling font_customizer.min.css

= 1.11 =
* fixed : (css) customizr.json : added a specific class for site-description in responsive mode
* fixed : (js) font-customizer-front.js : skip the all-subsets case

= 1.10 =
* fixed : (css) customizr.json : Featured Pages classes are now more specific in dyn-style.php to override the default FPU front style in front-FPU.css

= 1.9 =
* fixed : (css) new css classes compliant with FPU 1.4+ update
* fixed : (css) more specific style for selecter (possible conflict with other plugins using the same style)
* fixed : (css) higher z-index for .selecter.open 

= 1.8 =
* fixed : (php) add the plugin file name as argument in updater

= 1.7 =
* fixed : (php, js) bug when selecting grey skin for Customizr theme

= 1.6 =
* fixed : (php) Selector list was generating header already sent warning

= 1.5 =
* fixed : (php) Google fonts were not retrieved properly

= 1.4 =
* improved : (php) performance : better options handling in database
* improved : (json) json settings are read with file_get_contents first, then wp_remote_fopen 

= 1.3 =
* fixed : (php) hover effect was not taken into account in dyn-style.php for multiple selectors
* fixed : (json) customizr settings for widget titles selectors => increased css specificity to override default style
* improved : (php) selectors settings are refreshed on activation/desactivation. Plugins options and transients are cleaned on uninstall.

= 1.2 =
* fixed : (php) Admin : Fixed a bug on update notification
* fixed : (js) better handling of CSS3 inset effect live preview

= 1.1 =
* fixed : (php) Admin : better handling of activation key timeout and nonce

= 1.0 =
* initial release

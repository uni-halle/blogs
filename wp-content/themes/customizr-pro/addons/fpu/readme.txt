######################  Featured Pages Unlimited ######################
Contributors : Press Customizr
Donate link: http://www.presscustomizr.com#footer
Tags: pages, customizer, home, wordpress
Requires at least: 3.4
Tested up to: 4.2
Stable tag: 2.0.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Engage your visitors with featured pages on home, design live from the WordPress customizer.

== Description ==
The Featured Pages Unlimited is a simple and powerful solution to engage your visitors on your most visited page : home. The plugin allows you to select the most important content of your website, and display it on home beautifully, with a cool hover effect and call to action buttons.

All settings are done from the WordPress customizer in live preview, which makes it easy as breeze :
- select the number of page/post to feature
- select a layout
- select a location and add custom location
- style the color of the text 
- style the background color
- show or hide the page's featured images
- change the position of the featured pages
- edit the button's text 
- style the button's color 
- edit the page's excerpt

The Featured Pages Unlimited is compatible with all WordPress themes, fully responsive, translation ready.

###################### Copyright ######################
Featured Pages Unlimited is a WordPress plugin designed and developed by Nicolas Guillaume in Nice, France (www.presscustomizr.com), and distributed under the terms of the GNU GPL v2.0 or later.
Enjoy it!

####################### Licenses #######################
Unless otherwise specified, all the theme files, scripts and images
are licensed under GNU General Public License version 2, see file license.txt.

############## DOCUMENTATION AND SUPPORT ###############
DOCUMENTATION : http://presscustomizr.com/extension/featured-pages-unlimited/
SUPPORT : http://presscustomizr.com/support-forums/


#######################  Changelog ######################
= 2.0.12 July 17th 2015 =
* changed : adapt code to the latest Customizr changes

= 2.0.11 May 15th 2015 =
* Fix: polylang cast fpu options to array, needed when no options are set

= 2.0.10 April 29th 2015 =
* added : php code improvements, new way to filter a single option with "tc_fpc_get_opt_{$option_name}" + some handy filters on featured pages number
* Fix: use stronger selectors for fp spans

= 2.0.9 April 10th 2015 =
* fixed : handles the case when a post has been deleted
* add support for preview context in tc_get_theme_name()
* updated : site name and copyright dates
* Fix use correct callback for fpc_text filter
* Fix: final lang plugins compatibility
* Add: initial lang plugins compatibility
* Fix: properly loading of textdomain when as addon
* Fix: recenter on fp block class changed
* Fix: better definition of js deps in front js enqueing 2
* Fix: italian translation update
* Fix: better definition of js deps in front js enqueing

= 2.0.8 March 15th 2015 =
* Fix: various centering css rules

= 2.0.7 March 9th 2015 =
* replaced get_the_excerpt filter by the_excerpt

= 2.0.6 March 2nd 2015 =
* fix default_fp_text_length not defined

= 2.0.5 March 2nd 2015 =
improved : better limit chars number handling
updated : italian translation
improved : use of get_the_excerpt hook
added js fp img dynamic centering option
improved fp titles line height fo~r a better accessibility
added edit link after the fp titles when user is logged in and has the capabilities
updated : uses a utils_thumbnails class, inspired from TC_post_thumbnail class
updated filter name for text to get_the_excerpt
Fix: use a more proper filter to which pass the ->post_excerpt

= 2.0.4 February 13th 2015 =
Fix: use text-domain prefix for translations files
Fix: fp button display nothing if empty
Fix: perform tc_setup after_setup_theme when as addon in customizr-pro
Fix: temporary fix, don't include woocommerce products in fp
Fix: use a more proper filter to which pass the ->post_excerpt
Fix: don't retrieve already retrieved post/page object when getting the excerpt

= 2.0.3 December 12th 2014 =
* 51b73c3 fix customizr pro name to customizr-pro in config.json

= 2.0.2 Decembre 7th, 2014 =
* c357a6c Fix the comment bubble bug. get_the_title not used anymore.

= 2.0.1 : December 6th, 2014 =
* 6fd2a06 fix plugin / addon collisions
* 0d6a5bc disable default featured pages rendering and option if theme = Customizr or Customizr Pro
* 778228d create unique names for the jquery plugin css file enqueue slugs avoid collision with other TC plugins
* 55c7e8a add support for panels plugin section is located in content panel on addon mode
* a47cb17 replace plugins_url() by sprintf()
* 3295210 update the digital store url for updates and activation
* 989cf88 include git folder and files when copying in customizr-pro => manage it as a submodule

= 2.0.0 : December 1st, 2014 =
* ff2ace5 customizer controls : fix jquery plugins assets issues
* c009a1d fix jshint issues
* 9519081 include git folder for addon
* 8a68f25 change path from inc/addon to addon
* c570558 added customizr-pro in config.json
* c817d2f jquery plugins assets reorganization add TC_FPU_BASE_URL constant plug option prefix is different in addon mode : tc_pro_fpu

= 1.17 : November 3rd, 2014 =
* added : (php, class_front_fpu.php ) new hook for fp location : 'tc_fp_location'
* fixed : (php, front/classes/class_front_fpu.php ) added compatibility with JetPack photon
* updated (js, holder.js) version 2.4 of the script.
* improved : (php, class-fire-ressources.php) performance : holder.min.js is now loaded when FP are set to show images
* improved : (php, js, css) All js and CSS resources, in admin and front end, are queried with the version number parameter in wp_enqueue_...()
* improved : (js) In customizing mode, jQuery plugins icheck, stepper, selecter are loaded only when necessary. For example : 'function' != typeof(jQuery.fn.stepper) => avoir double loading if a plugin/theme already uses this $ module
* improved : (php, class-front_fpu.php) Edit Featured Pages button is displayed if is_user_logged_in() AND current_user_can( 'edit_theme_options' )
* improved : (php, class_front_fpu) tc_set_fp_hook is now a callback of template_redirect instead of wp_head
* added : (php, class_front_fpu) fpc_random_color_list to filter the random color list
* added : (php, class_front_fpu) new option to choose the shape and effect of the featured page thumbnail

= 1.16 : May 31st, 2014 =
* fixed : (php) if Customizr theme is used, added a check if method exists and has action before removing fp action

= 1.15 : May 29th, 2014 =
* fixed : (css) added z-index:0 to the .fpc-container selector

= 1.14 : May 29th, 2014 =
* added : (json) compatibility with the genesis framework

= 1.13 =
* added : (php) version and version upgraded from option on plugin activation

= 1.12 =
* fixed : (php, class_controls_fpc.php) in case : dropdown-posts-pages, $tc_all_posts array() has to be declared before filling it
* fixed : (css) removed media query for devices < 320 px
* changed : (php, class_controls_fpc.php) in case : dropdown-posts-pages, changed 'numberposts' to -1
* improved : (css, js) add !important to background-color and border-color
* added : when layout is 4 by lines, new option to force responsive behaviour for devices < 979px
* added : (php, admin) system infos page to paste for support request. Uses a browser's class librairy by Chris Schuld (http://chrisschuld.com/)
* improved : (php , class_utils_fpu.php) tc_fpc_get_option will return false if option not set

= 1.11 =
* improved : (css) better centering for mobile devices max-width 480px
* added : (css, php)  dynamic class name of the theme added to body tag

= 1.10 =
* improved : (js : fpu-front.min.js) better handling of dynamic span classes
* improved : (js : fpu-customizer-preview.min.js, php : class_front-fpu) button text color property set as !important

= 1.9 =
* fixed : (js) prevent conflict with WPF : dynamic style for title and excerpts in fpu-cutomizr-preview.js is set with attr('style' + !important instead of css('color')

= 1.8 =
* fixed : (css) title and excerpt color set to !important when random mode enabled
* fixed : (php) option fp_number_per_line called with __get_fpc_option to get the default in class utils

= 1.7 =
* fixed : (php) better handling of previous featured pages settings id on update

= 1.6 =
* fixed : (php) bug in class_front_fpu.php for older version of PHP
* fixed : (css) better centering of thumbnail

= 1.5 =
* fixed : (css) button text color overrides random colors
* fixed : (css, php) custom colors set flagged !important
* improved : (js) better handling of the front end javascript with var localization
* improved : (js) check if jQuery is registered and enqueued for old themes

= 1.4 =
* added : compatibility with any WordPress theme
* added : predefined location for most popular WP.org themes : twenty fourteen, twenty thirteen, twenty twelve, twenty eleven, twenty ten, customizr, alexandria, responsive, spacious, swift basic, vantage, sixteen, virtue, attitude, radiate, ridizain, portfolio press, pinboard, ifeature, catch kathmandu, evolve, weaver ii, sugar and spice, raindrops, coller, dms, coraline, catch box, catch evolution, mantra, tempera, iconic one, destro
* added : new option section in the WordPress customizer
* added : new option : select a predefined or custom location (with a hook)
* added : new option : customize the background color
* added : new option : thumbnails override the random colors
* added : new option : customize title/excerpt colors
* added : new option : disable the 200 char. limit for excerpt
* added : new option : select button style
* added : new option : customize the button text color
* improved : better loading performance with minified stylesheet
* improved : better handling of default options
* improved : better loading performance with minified stylesheet

= 1.3 =
* fixed : (php) plugin update issue : the plugin was still displaying an update notification after update

= 1.2 =
* improved : (php) Admin : better handling of activation key timeout and nonce

= 1.1 =
* changed : (php) Admin : title of activation key admin page

= 1.0 =
* initial release

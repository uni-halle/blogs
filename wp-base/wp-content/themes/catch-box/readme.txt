= Catch Box=
* by the Catch Themes team, http://catchthemes.com/

== ABOUT Catch Box==
Catch Box is simple, lightweight, box shaped, and adaptable WordPress Theme for bloggers and professionals. It is based on HTML5, CSS3 and Responsive Web Design to view in various devices. 10 Best Reasons to use Catch Box Theme. 1. Responsive Web Design, 2. Custom Menus (Primary, Secondary and Footer menus), 3. Theme Options for light, dark, blue, green, red, brown and orange color scheme, custom link colors, three layout choices, two content choices between excerpt and content option in homepage, feed redirection, custom css styles, 4. Featured Sliders where you can define number of slides and post IDs, 5. Social Links (Facebook, Twitter, RSS, Linkedin, Pinterest, etc), 6. Webmaster Tools (Google, Yahoo and Bing site verification ID, Header and Footer codes), 7. Custom Backgrounds, 8. Custom Header, 9. Catchbox Adspace widget to add any type of Advertisements, and 10. Support popular plugins. Multilingual Ready (WPML) and also currently translated in Brazilian Portuguese, Spanish, Danish, Germany, French, Polish, Czech, Croatian, Italian, Swedish, Russian, Arabic, Serbian, Dutch, Persian, Hungarian, Slovak and Japanese. Theme Support at http://catchthemes.com/support/

== License ==
Unless otherwise specified, all the theme files, scripts and images are licensed under GNU General Public License Version 2, see file license.txt

License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Catch Box WordPress Theme, Copyright 2012-2014 Catchthemes.com
Catch Box is distributed under the terms of the GNU General Public License v2 

Catch Box is a derivative of the Twenty Eleven theme by the WordPress team:
http://wordpress.org/themes/twentyeleven
Copyright: Automattic, automattic.com
Licensed under GPLv2 or later

== Translation ==
Catch Box theme is translation ready. 
Added Translation for Brazilian Portuguese by Valdir Trombini ( Email: valdir.trombini@gmail.com )
Added Translation for Spanish by César Gómez ( Email: cesar@temperatio.com )
Added Translation for Danish by Kris Thomsen ( Email: mail@kristhomsen.dk )
Added Translation for Germany by Heinz Peter Brinkmann
Added Translation for French by  D'CLIC (cEmail: ontact@dclic.info), Athéna G (Email: webmaster@athena.georgakelos.net) and Capronnier luc (Emil: lcapronnier@yahoo.com)
Added Translation for Polish by Piotr (Email: fansitepoprostugry@gmail.com)
Added Translation for Czech by Marek Přidal (Email: m.pridal29@gmail.com)
Added Translation for Croatian by Sanjin Barac (Email: rapid24income@gmail.com)
Added Translation for Italian by Lorenzo Bossi (Email: lorenzobossi83@gmail.com)
Added Translation for Swedish by Johnny Eriksson (Email: info@johnnysblogg.se)
Added Translation for Russian by XakRu (Email: calvinxakru@gmail.com)
Added Translation for Arabic by ed3s (Email: master@ed3s.com)
Added Translation for Serbian by Tomo Popovic (Email: tp0x45@gmail.com)
Added Translation for Dutch by Alain Baudrez (Email: a.baudrez@gmail.com)
Added translation for Persian by Ali Akbar Kaviani (Email: persian@wiki10.net)
Added translation for Hungarian by Szentpétery István (Email: szempy@gmail.com)
Added translation for Slovak by Michal Kralik (Email: ja@michalkralik.sk)
Added translation for Japanese by IIOKA Shinji (Email: iiokashinji@gmail.com)

== Menus ===
There are 3 Menus registered in this theme. Primary, Secondary and Footer menu. 
Primary and Secondary menu is dull drop down menu while the Footer menu displays only parent menu and no drop down.
Only Primary Menu is visible in responsive (mobile devices with max-width: 650px)

== Tags ==
dark, light, blue, white, black, gray, one-column, two-columns, left-sidebar, right-sidebar, fixed-width, flexible-width, custom-background, custom-colors, custom-header, custom-menu, editor-style, featured-image-header, featured-images, full-width-template, microformats, post-formats, rtl-language-support, sticky-post, theme-options, translation-ready

== Installation ==

1. Primary: Login to your wp-admin area and go to Appearance -> Themes. Select Install tab and click on Upload link. Select theme .zip and ckick on Install now button. If you have any errors, use alternate method.
2. Alternate: Unzip the template file (catchbox.zip) that you have downloaded. Via FTP, upload the whole folder (catchresponsive) to your server and place it in the /wp-content/themes/ folder. Do not change directory name. The template files should thus be here now: /wp-content/themes/catchbox/index.php (for example).
3. Log into your WP admin panel and click on the Design tab. Now click on the Catch Box theme to activate it.
4. Complete all of the required inputs on the Catch Box Options page (in the WP admin panel) and click "Save Changes".

== Changelog ==
Version 1.0.1
* Added text domain $beginning[ 'postid' ] = __( 'ID' ); in function catchbox_post_id_column()
* Fixed undefined index for feed_url and moved the function catchbox_rss_redirect() from function.php to theme-options.php
* Fixed undefined index for custom_css in theme-options.php
* Added margin left for sub level list in widget lists. 
* Added function catchbox_filter_wp_title() to filter the wp_title() 

Version 1.0.2
* Removed extra register_nav_menu( 'primary', __( 'Primary Menu', 'catchbox' ) );
* Removed Extra add_custom_background();

Version 1.0.3
* Fixed DEBUG ERROR 
  ** theme-options.php Undefined index: feed_url on line 410
* Fixed UNIT TEST
  ** Footer menu: Now only display parent menu in footer menu. I have describe it in readme.txt
  ** Fixed css for menu widget
  ** Fixed the css for Layout Test h1, h2, h3, h4, h5, h6
  ** Fixed css for Gallery Post: Remove extra space and center it.
  ** Fixed css for Image Test: Wide Image, resize in editor
  ** Fixed 404 Error Page and search box in it
* Added CSS to support plugin WP-PageNavi and WP Page Numbers Plugins

Version 1.0.4
* Fixed footer Navigation widget
* Added Style for Single Page Navigation

Version 1.0.5
* Fixed 'wp-head-callback', 'admin-head-callback', 'admin-preview-callback'

Version 1.0.6
* Fixed CSS for Navigation
* Fixed CSS for widget heading link
* Changed Screenshot to showcase the Responsive design and Sample Ads Widget

Version 1.0.7
* Fixed cycle_setup.js file.
* Cleaned header.php file.
* Fixed functions.php file.
* Fixed theme_options.php file

Version 1.0.8
* Added option to exclude featured slider post from Home page posts.
* Fixed issue with inline CSS option
* Cleaned theme-options.php

Version 1.0.9
* Added catchbox.pot file to make theme translation ready

Version 1.1.0
* Fixed the Dual title issue in feed
* Backward compatibility for wp_get_theme, using get_current_theme for WordPress Version below 3.4

Version 1.1.1
* Updated catchbox.pot file
* Added language translation file pt_BR.po and pt_BR.mo files

Version 1.1.2
* Added slider effect options in slider options.
* Added toggle effects on Options

Version 1.1.3
* Added option to change excerpt length in Theme Options
* Minified js files jquery.cycle.all.min.js and pngfix.min.js

Version 1.1.3.1
* Fixed Slider loading issue and display overlap 
* Added un-minified version of JS as per GPL

Version 1.1.3.2
* Fixed site title and heading font issue
* Fixed dark.css for dark color scheme

Version 1.2
* Added language translation file es_ES.po and es_ES.mo files
* Replaced get_stylesheet_directory_uri to get_template_directory_uri
* Added flexibility to height and width of Header Image (Logo)
* Replaced the default menu to responsive input menu for mobile devices.

Version 1.2.1
* Fixed the Featured Image size to match with iPad display
* Fixed css for responsive design
* Fixed tag.php closing primary div

Version 1.2.2
* Fixed Responsive Design CSS and Image Size
* Note if you are upgrading the theme then please use the regenerate thumbnail plugin (http://wordpress.org/extend/plugins/regenerate-thumbnails/) to regenerate the new image size for featured image. It's size is 644px width and 320px height.

Version 1.2.3
* Removed extra file style.css.backsov.css
* Added Full Width Page (no sidebar) Template
* Added clear class for clearing floats in css

Version 1.3.0
* Added Danish translation da_DK.po and da_DK.mo

Version 1.3.1
* Fixed responsive css for iPhone
* Fixed Full Width Disable Sidebar and Content on Right Layout CSS
* Fixed widget title link text
* Fixed Page nav wp_link_pages design

Version 1.4
* Fixed Slider Initialization 
* Added Option to Disable Header Search in Theme Option Panel
* Added Info bar in Theme Option Panel
* Change the license to GPL 2

Version 1.4.1
* Fixed css for Threaded Comment Image Issue

Version 1.5
* Added Germany translation de_DE.po and de_DE.mo
* Fixed site title though changes in wp_title and catchbox_filter_wp_title function

Version 1.5.1
* Fixed css for Content on Right Layout

Version 1.5.2
* Fixed the linked widget title color in dark color scheme
* Fixed css for menu hover for older browser and IE

Version 1.5.3
* Added Blue Color Scheme

Version 1.5.4
* Fixed Default Layout and Page Template Issue with body_class filter

Version 1.5.5
* Fixed body_class filter loop for layout issue found in version 1.5.4
* Fixed CSS for Content on Right Layout 

Version 1.5.5.1
* Fixed the sidebar option

Version 1.5.6
* Added One-column, no sidebar page template and sidebar option

Version 1.5.6.1
* Added Word Wrap for commentlist in style.css

Version 1.6
* Added favicon url option in Theme Options

Version 1.6.1
* Updated screenshot to match with WordPress 3.5 version update
* Added comment form fields alteration function to match with WordPress 3.5 version update
* Fixed Spanish translation issue with the closing of span tag

Version 1.6.2
* Fixed Spanish translation issue in es_ES.mo

Version 1.6.3
* Fixed Google's Structured Data Testing Tool reports the error 'Warning: Missing required field "updated"'

Version 1.7
* Added French translation fr_FR.po and fr_FR.mo

Version 1.7.1
* Added sidebar in image.php to match layout for image
* Fixed editor css
* Fixed language file catchbox.pot

Version 1.7.2
* Fixed Polish translation pl_PL.po and pl_PL.mo
* Updated Spanish and German translation
* Fixed the title for Catch Box Adspace Widget

Version 1.7.2.1
* Fixed Undefined variable: commenter in catchbox_comment_form_fields() function

Version 1.7.2.2
* Fixed Missing text domain in comment catchbox_comment_form_fields() function
* Fixed Debugger notices for feed_url, fav_icon, and custom_css

Version 1.8
* Added Czech translation cs_CZ.po and cs_CZ.mo
* Updated French Language 

Version 1.9
* Center image in the slider if the image size is smaller then wrapper
* Added Croatian translation hr_HR.po and hr_HR.mo

Version 2.0
* Added Italian translation it_IT.po and it_IT.mo

Version 2.0.1
* Added content layout excerpt to show full content if found excerpt empty
* Added Separator  for Header Image and Site Details   

Version 2.0.2
* Deleted the extra css in dark.css and fixed chrome issue

Version 2.0.3
* Added webclip icon url option

Version 2.1
* Added Swedish translation sv_SE.po and sv_SE.mo 

Version 2.1.1
* Fixed author info color in Dark color scheme
* Fixed Social Icon title for Vimeo and Flickr
* Added Responsive menu support for default wp_page_menu 
* Fixed Arrow on Responsive Menu
* Removed simplecatch_sort_query_by_post_in function as post orderby is already implement in WordPress Version 3.5 by default

Version 2.2
* Moved comment-reply script to wp_enqueue_scripts
* Added Russian translation ru_RU.po and ru_RU.mo
* Added missing !function_exists() condition to support child theme easy editing
* Added redirect to Theme Options upon theme activation

Version 2.2.1
* Fixed the web clip icon not showing up
* Added missing !function_exists() condition to support child theme easy editing

Version 2.2.2
* Added option to enable Secondary and Footer Menu on Mobile Devices
* Update menu scripts and css

Version 2.3
* Added Arabic translation ar.po and ar.mo 
* Update Spanish translation es_ES.po and es_ES.mo

Version 2.3.1
* Added catchbox_content hook
* Added page template Blog for show blog on page.
* Fixed the slider homepage condition while setting static Posts Page
* Moved Featured Slider Code from header.php to function.php and hooked with catchbox_content
* Update Italian translation it_IT.po and it_IT.mo

Version 2.3.2
* Fixed the Slider

Version 2.4
* Added Serbian translation sr_RS.po and sr_RS.mo
* Fixed RTL css for mobile devices

Version 2.5
* Added Jetpack Infinite Scroll support
* Added option to move the Site Title and Tagline above Header Image
* Added Action Hooks
++ catchbox_before
++ catchbox_before_header
++ catchbox_before_headercontent
++ catchbox_headercontent
++ catchbox_after_headercontent
++ catchbox_after_header
++ catchbox_before_main
++ catchbox_before_primary
++ catchbox_before_content
++ catchbox_above_secondary
++ catchbox_below_secondary
++ catchbox_after_content
++ catchbox_after_primary
++ catchbox_after_main
++ catchbox_before_footer_sidebar
++ catchbox_after_footer_sidebar
++ catchbox_before_footer_menu
++ catchbox_after_footer_menu
++ catchbox_site_generator
++ catchbox_after
* Changed hgroup wrap with div id header-content wrap
* Depreciated Action Hooks
-- catchbox_startgenerator_open
-- catchbox_startgenerator_close action hook 

Version 2.5.1
* Added default navigation for custom template page-blog.php as Jetpack Infinite Scroll doesn't support custom query
* Fixed long select menu
* Merge Theme Options in single page
* Removed site verification code as required by WordPress.org Theme Review Guideline
* Updated Polish translation pl_PL.po and pl_PL.mo

Version 2.6
* Fixed z-index css for header
* Fixed rtl css for footer widget
* Added Dutch translation nl_NL.po and nl_NL.mo

Version 2.6.1
* Added Persian translation fa_IR.po and fa_IR.mo
* Fixed rtl css for Single nav
* Fixed theme-options.js to support WordPress 3.6 jQuery UI version update

Version 2.6.2
* Added Instagram, Slideshare and Skype social icons

Version 2.7
* Added Green Color Scheme
* Added class slider-title, sep and slider-excerpt for Featured Post Slider content to give more control though css

Version 2.7.1
* Added Soundcloud social icon
* Fixed Mobile menu issue not displaying last menu item
* Fixed Skype Link Data Validation as per https://dev.skype.com/skype-uri
* Fixed RTL css for secondary menu

Version 2.8
* Added Hungarian translation hu_HU.po and hu_HU.mo
* Added sep-comment class in comment separator

Version 2.8.1
* Added option to change search text in search box
* Changed Theme Options page design

Version 2.8.2
* Added Red Color Scheme

Version 2.8.3
* Added Brown Color Scheme
* Changed the Theme Screenshot

Version 2.9
* Added missing !function_exists() for continue reading link
* Added initial-scale=1 in viewport content
* Added responsive design css for iPad and iPhone landscape view
* Fixed Secondary menu option in responsive design
* Fixed CSS Issues
** Fixed RTL
** Fixed overflow long title
** Removed duplicate colour code for title
** Fixed heading font sizes overwriting page/post title 
* Replaced responsive menu icon

Version 2.9.1
* Added Slovak translation sk_SK.po and sk_SK.mo
* Fixed translation by replacing simplecatch with catchbox in Theme Options panel
* Fixed mobile menu item text css in dark color scheme
* Removed Redirect to Theme Options Page on Activation as per new theme review guideline
* Removed depreciated functions add_custom_image_header() and add_custom_background()
* Replaced theme screenshot file to match with WordPress 3.8
* Updated theme Tag Filter in style.css

Version 2.9.2
* Fixed menu and slider nav css issue by changing z-index value
* Fixed typo in Menu script for Secondary text. Special thanks to @flamenco for reporting bug
* Fixed Secondary menu display issue in responsive design
* Fixed Germany translation

Version 2.9.3
* Added Japanese translation ja.po and ja.mo

Version 2.9.4
* Updated html5 Script added minified version
* Updated responsive menu script
* Updated jQuery Cookie Script added minified version
* Updated slider script
* Updated IE script
* Moved style.css load from header.php to functions.php file and hooked to wp_enqueue_style

Version 2.9.5
* Added Orange Color Scheme
* Minified admin script and style
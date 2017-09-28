=== Plugin Name ===
Contributors: Cryout Creations
Donate link: https://www.cryoutcreations.eu/donate/
Tags: theme, admin
Requires at least: 4.2
Tested up to: 4.8.1
Stable tag: 0.5.8.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

This plugin is designed to inter-operate with our Mantra, Parabola, Tempera, Nirvana themes to restore their settings pages.

== Description ==

This plugin is designed to inter-operate with our [Nirvana](https://wordpress.org/themes/nirvana/), [Tempera](https://wordpress.org/themes/tempera/), [Parabola](https://wordpress.org/themes/parabola/), [Mantra](https://wordpress.org/themes/mantra/) themes and restore their advanced settings pages which we had to remove due to the Customize-based settings transition.

Additionally, it fixes an incompatibility between the older version of listed themes and Wordpress 4.4 and newer.

= Compatibility = 
The plugin is meant to be used with the following theme releases regardless of WordPress version:

* Nirvana version 1.2 and newer
* Tempera version 1.4 and newer
* Parabola version 1.6 and newer
* Mantra version 2.5 and newer

Additionally, it is needed to correct an incompatibility between WordPress 4.4 and newer and the following theme versions:

* Tempera versions 0.9 - 1.3.3
* Parabola versions 0.9 - 1.5.1
* Mantra versions 2.0 - 2.4.1.1

You do not need this plugin if you use do not use any of the listed themes.

== Installation ==

= Automatic installation =

0. Have one of our supported themes activated with a non-working or disabled settings page.
1. Navigate to Plugins in your dashboard and click the Add New button.
2. Type in "Cryout Theme Settings" in the search box on the right and press Enter, then click the Install button next to the plugin title. 
3. After installation Activate the plugin, then navigate to Appearance > [Theme] Settings to access the restored theme settings page.

= Manual installation =

0. Have one of our supported themes activated with a non-working or disabled settings page.
1. Upload `cryout-theme-settings` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Appearance > [Theme] Settings to access the restored theme settings page. 

== Changelog ==

= 0.5.8.1 = 
* Fixed compatibility mode malfunctioning
* Fixed misssing theme images in compatibility mode info

= 0.5.8 =
* Added meta links
* Updated plugin's about page
* Added 'featured themes' and 'priority support' panels

= 0.5.7 =
* Fixed incorrect status for Nirvana versions before 1.2

= 0.5.6 =
* Added support for Mantra 2.5

= 0.5.5 = 
* Added support for Parabola 1.6
* Added detection of supported themes when the theme folder is renamed

= 0.5.4 = 
* Fixed compatibility support for Mantra

= 0.5.3 = 
* Added support for Tempera 1.4
* Fixed typo causing error in compatibility code

= 0.5.2 = 
* Added themes compatibility fix for WordPress 4.4-RC1 and newer

= 0.5.1 = 
* Fixed detection of parent theme name and version when using a child theme
* Clarified plugin information

= 0.5 =
* Initial release. Currently Nirvana 1.2 implements support for this plugin.

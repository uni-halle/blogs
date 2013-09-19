=== TimeZoneCalculator ===
Contributors: neoxx
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=J6ZGWTZT4M29U
Tags: time, date, timezone, calendar, world clock, clock, travel, widget, sidebar, dashboard, shortcode, multisite, multi-site, ajax, javascript, jquery, prototype, bar, admin bar
Requires at least: 2.8
Tested up to: 3.6
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Calculates, displays and automatically updates times and dates in different timezones with respect to daylight saving.

== Description ==

Calculates, displays and automatically updates times and dates in different timezones with respect to daylight saving.

**starting from version 2.00 with a new Calculator and enhanced Ajax functionality**

* based on PHP timezones database (please read the [FAQ](http://wordpress.org/extend/plugins/timezonecalculator/faq/) for further information)
* fully optionpage-configurable
* easy to integrate (ships with multi/sidebar- and dashboard-widget functionality as well as integrated timezones search function)
* display clock in WordPress 3.1+ Admin Bar
* display clock in Admin Menu header
* possible to integrate in "Right Now" box or to display as widget on the dashboard and on the user's profile page
* Calculator section in Tools-Menu with individual timezone-selection for every user
* optional Ajax refresh (jQuery or Prototype)
* fully compatible with [https/SSL/TLS-sites](http://codex.wordpress.org/Administration_Over_SSL)
* drag and drop admin menu page
* fully WP 3.0 multi-site network compatible
* clean uninstall

**Fancy on timezones-calculation? - Try the free [JourneyCalculator](http://www.journeycalculator.com/) with its integrated TimeZoneCalculator...**

Requirements for current version:

* PHP 5 or higher (find the version for PHP 4 [here](http://downloads.wordpress.org/plugin/timezonecalculator.php4.zip))
* You can check your PHP version with the [Health Check](http://wordpress.org/extend/plugins/health-check/) plugin.

Please find the version for WordPress

* 2.8 and higher [here](http://downloads.wordpress.org/plugin/timezonecalculator.zip)
* 1.5 to 2.7 [here](http://downloads.wordpress.org/plugin/timezonecalculator.wordpress1.5-2.7.zip)

**Plugin's website:** [http://www.bernhard-riedl.com/projects/](http://www.bernhard-riedl.com/projects/)

**Author's website:** [http://www.bernhard-riedl.com/](http://www.bernhard-riedl.com/)

== Installation ==

1. Copy the `timezonecalculator` directory into your WordPress Plugins directory (usually wp-content/plugins). Hint: You can also conduct this step within your Admin Menu.

2. In the WordPress Admin Menu go to the Plugins tab and activate the TimeZoneCalculator plugin.

3. Navigate to the Settings/TimeZoneCalculator tab and customize the timezones according to your desires.

4. If you have widget functionality just drag and drop TimeZoneCalculator on your widget area in the Appearance Menu. Add additional [function and shortcode calls](http://wordpress.org/extend/plugins/timezonecalculator/other_notes/) according to your desires.

5. Be happy and celebrate! (and maybe you want to add a link to [http://www.bernhard-riedl.com/projects/](http://www.bernhard-riedl.com/projects/))

== Frequently Asked Questions ==

= So, a lot of stuff has changed with TimeZoneCalculator 0.90? =
= I get the error-message `Fatal error: Class 'DateTimeZone' not found in` [..] =

Heck, yeah. - I changed the internal structure of the plugin to use the [PHP timezones library](http://php.net/manual/en/timezones.php) instead of calculating the timezones myself. Thus, please make sure that you have a recent version of PHP including the required library installed and enabled. In case you don't have a suitable environment, you can still use [version 0.81](http://downloads.wordpress.org/plugin/timezonecalculator.last_version_with_built-in_calculations.zip) which is the last TimeZoneCalculator version that can be used with older PHP versions. Nevertheless, please note that due to various security reasons there should always be a recent version of PHP installed. In the case of a hosted environment, please contact your provider for further information.

As your environment is now set up properly, enjoy the benefits of TimeZoneCalculator 0.90 (and higher) :) This new version gives the advantage that the timezones-entries can be more easily selected and managed: information like abbreviations, offset to UTC, and daylight saving can be automatically retrieved and updated. I'm afraid it is not possible to use your old timezone-entries with the new version, but it won't take you more than five minutes to convert them to the new format by manually editing. I'm sorry for any inconvenience caused.

If any timezone information like offset, abbreviations, etc. appears to be wrong, please leave a message for the PHP guys on [their board](http://php.net/manual/en/timezones.php).

Though timezone abbreviations can be automatically filled out, the corresponding full names (for example Central European Time for CET) are currently not supported within the PHP library. Nevertheless, you can look up this information in the TimeZoneCalculator Tab in your Admin Menu.

= Which Javascript library should I choose for the Ajax refresh in my theme? =

That's [a well-covered topic in the web](https://encrypted.google.com/search?q=prototype+vs.+jquery). TimeZoneCalculator provides you with the flexibility to use either [Prototype](http://www.prototypejs.org/) or [jQuery](http://jquery.com/). Thus, your decision merely depends on what your other installed plugins use.

= Why is 'Drag and Drop' not working? Why can't I see the 'Drag and Drop' section? =

This section is based on Javascript. Thus, you have to enable Javascript in your browser (this is a default setting in a modern browser like [Firefox](http://www.mozilla.org/firefox?WT.mc_id=aff_en01&WT.mc_ev=click)). TimeZoneCalculator is still fully functional without these constraints, but you need to customize your stats manually as in older versions of TimeZoneCalculator.

= How can I adopt the color scheme in the TimeZoneCalculator Settings Tab? =

If you select one of the two default color schemes (`classic = Blue` or `fresh = Gray`) in your Profile Page, TimeZoneCalculator automatically adopts its colors to this scheme.

In case you use a custom color scheme, this cannot be done automatically because WordPress still doesn't provide any proper functions to find out which colors of your scheme are used for background, font, etc. - Nevertheless, you can set up your preferred colors manually: Just add the [filter](http://codex.wordpress.org/Function_Reference/add_filter) `timezonecalculator_available_admin_colors` in for example timezonecalculator.php or in your custom-colors-plugin.

= Is there anything I need to know before updating to TimeZoneCalculator v2? =

As the majority of the source-code changed with version 2.00, there are two things I would like to mention:

- Your 1.x options will be automatically converted. - Nevertheless, you should make a backup prior to this upgrade!
- `GetTimeZoneTime()` has been deprecated in favor of `$timezonecalculator->output()`

== Other Notes ==

**Attention! - Geeks' stuff ahead! ;)**

= API =

With TimeZoneCalculator 1.00 and higher you can also realize a html select which displays for example the airtime of your internet radio station or your broadcasts in your users' local time. Another possibility for the usage of the upcoming function is the integration of date, time and timezone information into your travelling blog.

Parameters can either be passed [as an array or a URL query type string (e.g. "display=0&format=0")](http://codex.wordpress.org/Function_Reference/wp_parse_args). Please note that WordPress parses all arguments as strings, thus booleans have to be 0 or 1 if used in query type strings whereas for arrays [real booleans](http://php.net/manual/en/language.types.boolean.php) should be used. - Furthermore you have to break your timezones with \n : `America/New_York;EST;EWT;New York, NY, US;New York, NY, US;0;0\nEurope/Vienna;;;sleep longer in winter;get up earlier to enjoy the sun;1;0` if you want to use different timezones in a query_string. In case you use an array, an array should also be used for the timezones.

**`function $timezonecalculator->output($params=array())`**

$params:

- `query_time`: any unix timestamp (where `-1262304000 <= query_time <= 2145916800`) or any English textual datetime description in the range of `1930-01-01` and `2038-01-01` which can be parsed with [PHP's strtotime function](http://php.net/manual/en/function.strtotime.php); default is set to current UTC

- `query_timezone`: origin-timezone of `query_time`; you can choose a [PHP timezone_string](http://php.net/manual/en/timezones.php); otherwise `UTC` will be used

- `before_list`: default `<ul>`

- `after_list`: default `</ul>`

- `format_timezone`: default `<li><abbr title="%name">%abbreviation</abbr>: <span title="%name">%datetime</span></li>`

- `format_datetime`: default `Y-m-d H:i`

- `timezones`: alternative timezones-array - each array entry has to be a string as described in the Expert Section of the Admin Menu; default is the timezones-entries array which can be modified in the Admin Menu

- `prefer_user_timezones`: prefer user set timezones - if they exist - to global or function call timezones; default is `false`

- `user_id`: determines which user's timezones should be used; not set as default -> use the timezones of the user who is currently logged in

- `use_container`: if set to `true` (default value), the current UTC is used as `query_time` and the same selected stats and format is used as set in the admin menu, TimeZoneCalculator wraps the output in a html div with the class `timezonecalculator-refreshable-output` - the class `timezonecalculator-output` will be used for all other output; if you set `use_container` to `false`, no container div will be generated

- `display`: if you want to return the timezone-information (e.g. for storing in a variable) instead of echoing it with this function-call, set this to `false`; default setting is `true`

- `format_container`: This option can be used to format the `div` container with css. Please note, that it should only be used to provide individual formats in case the class-style itself cannot be changed.

- `no_refresh`: If set to true, TimeZoneCalculator will not produce any Ajax-Refresh-code, even if you have enabled the Ajax refresh in the admin menu.

Example for including a world-clock in your [post-template](http://codex.wordpress.org/Theme_Development) (usually single.php or post.php in wp-content/themes) using [WordPress the_date() function](http://codex.wordpress.org/Template_Tags/the_date):

Find something similar to

`the_date();`

or

`the_time();`

and replace it with

`<?php $timezonecalculator->output(array('query_time' => the_date('U', '', '', false))); ?>`

This outputs your selected timezones, whereas calculations are based on the timestamp of your post instead of using the current UTC.

= Shortcodes =

[How-to for shortcodes](http://codex.wordpress.org/Shortcode_API)

**General Example:**

Enter the following text anywhere in a post or page to inform the clan when to meet up again:

`let's meet tomorrow for a new challenge at:

[timezonecalculator_output query_time="tomorrow 8pm" query_timezone="Europe/Vienna" timezones="Europe/Vienna\nAsia/Bangkok"]`

**Available Shortcode:**

`timezonecalculator_output`

Invokes `$timezonecalculator->output($params)`. Please note that you have to use a query_string to select timezones which can be parsed into an associative array. - For example: `America/New_York;EST;EWT;New York, NY, US;New York, NY, US;0;0\nEurope/Vienna;;;sleep longer in winter;get up earlier to enjoy the sun;1;0`

= Filters =

[How-To for filters](http://codex.wordpress.org/Function_Reference/add_filter)

**General Example:**

`function my_timezonecalculator_available_admin_colors($colors=array()) {
	$colors['custom_scheme'] = array('#14568A', '#14568A', '', '#C3DEF1');
	return $colors;
}

add_filter('timezonecalculator_available_admin_colors', 'my_timezonecalculator_available_admin_colors');`

**Available Filters:**

`timezonecalculator_defaults`

In case you want to set the default parameters globally rather than handing them over on every function call, you can add the [filter](http://codex.wordpress.org/Function_Reference/add_filter) `timezonecalculator_defaults` in for example timezonecalculator.php or your [own customization plugin](http://codex.wordpress.org/Writing_a_Plugin) (recommended).

Please note that parameters which you hand over to a function call (`$timezonecalculator->output`) will always override the defaults parameters, even if they have been set by a filter or in the admin menu.

`timezonecalculator_dashboard_widget`

Receives an array which is used for the dashboard-widget-function call to `$timezonecalculator->output($params)`. `display` and `use_container` will automatically be set to true.

`timezonecalculator_dashboard_right_now`

Receives an array which is used for the right-now-box-function call to `$timezonecalculator->output($params)`. `display` and `use_container` will automatically be set to true.

`timezonecalculator_world_clock_tools_page`

Receives an array which is used for the world-clock-function call on the tools-page to `$timezonecalculator->output($params)`. `display`, `use_container` and `prefer_user_timezones` will automatically be set to true.

`timezonecalculator_user_profile`

Receives an array which is used for the user-profile-function call to `$timezonecalculator->output($params)`. `display`, `use_container` and `prefer_user_timezones` will automatically be set to true.

`timezonecalculator_admin_head_clock`

Receives an array which is used for the admin-head-clock-function call to `$timezonecalculator->output($params)`. `display` and `use_container` will automatically be set to true. Moreover, the `timezones` will be set to Local_WordPress_Time. The filter `timezonecalculator_admin_head_clock_format_container` will be used as default format for the clock.

`timezonecalculator_admin_head_clock_format_container`

Receives a string which represents the container style.

`timezonecalculator_admin_bar_clock`

Receives an array which is used for the admin-bar-clock-function call to `$timezonecalculator->output($params)`. `display` will automatically be set to false and `use_container` to true. Moreover, the `timezones` will be set to Local_WordPress_Time. The filter `timezonecalculator_admin_bar_clock_format_container` will be used as default format for the clock.

`timezonecalculator_admin_bar_clock_format_container`

Receives a string which represents the container style.

`timezonecalculator_admin_bar_clock_position`

Position of the clock in the Admin Bar (see `wp-includes/class-wp-admin-bar.php` function `add_menus`). Default is `1000` which is the last position.

`timezonecalculator_calculator`

Receives an array which is used for the calculator-page function call to `$timezonecalculator->output($params)`. `display`, `use_container` will automatically be set to true.

`timezonecalculator_calculator_format_date`

Receives an array whereas each entry represents a date-format. The first date-format will be used as display-format in the textfield.

`timezonecalculator_calculator_format_time`

Receives a string which is used to determine whether to use 12 or 24-hour clock. In case this string contains `a` or `A`, the 12-hour clock will be preferred.

`timezonecalculator_available_admin_colors`

Receives an array which is appended to the default-color schemes of TimeZoneCalculator.

Array-Structure:

- 1 -> border-color
- 2 -> background-color
- 4 -> text-color

== Screenshots ==

1. This screenshot shows the Settings/TimeZoneCalculator Tab with the Drag and Drop Section in the Admin Menu.

2. This picture presents the Preview Section of the TimeZoneCalculator Tab in the Admin Menu.

3. This screenshot shows an example of the clock in the WordPress 3.1+ Admin Bar. - The date/time and the display format can be customized.

4. This image presents an example of the clock of the Admin Menu. - The date/time and the display format can be customized.

5. The last screenshot shows the Calculator Section in the Tools Menu.

== Upgrade Notice ==

= 2.00 =

This is not only a feature but also a security update. - Thus, I'd strongly recommend all users of TimeZoneCalculator which have at least an environment of WordPress 2.8 or higher and PHP 5 to install this version!

== Changelog ==

= 2.45 =
* made add-link to [link manager for WordPress 3.5 and higher optional](https://core.trac.wordpress.org/ticket/21307)
* fixed some HTML5 deprecated warnings

= 2.44 =
* extended length of format_timezone-string to 150 chars
* adopted 'Defaults'-string to use WordPress internal i18n
* updated support section
* updated project-information

= 2.43 =

* changed handling of contextual help for WordPress 3.3
* implemented WordPress 3.3 Admin Bar add_node() function
* adopted handling of default settings
* external files are now registered in init-hook

= 2.42 =

* changed spin-url to also be delivered according to the site's protocol

= 2.41 =

* fixed a bug with Ajax-update functionality in a SSL-environment. Thanks to huyz who has mentioned this in the forum http://wordpress.org/support/topic/plugin-generalstats-makes-https-call-to-admin-ajax-even-if-site-is-http
* adopted Admin Header Clock to WordPress 3.2 Admin Menu

= 2.40 =

* revised the security model (replaced option `Allow anonymous Ajax Refresh Requests` with `All users can view timezones` and added the option `Capability to view timezones` to define the capability of a certain user to access the timezones)
* de-coupling of Ajax-refresh-functions and output of `wp_localize_script` (TimeZoneCalculator is now compatible with [WP Minify](http://wordpress.org/extend/plugins/wp-minify/))
* small enhancements

= 2.31 =

* adopted Admin Bar implementation to re-worked WordPress code

= 2.30 =

* Changed default Ajax library to jQuery (Prototype is by default now only used for the settings and calculator pages)
* added support of Admin Menu header clock for WordPress 3.1 Admin Bar
* Code clean-up in the Ajax-refresh-files
* Small bug-fixes and enhancements

= 2.20 =

* added jQuery as alternative to Prototype for the Ajax refresh in the front-end

= 2.10 =

* added `$params['user_id']` as argument of `$timezonecalculator->output()` to make it possible for users to access the timezones of other users
* the access to the user's timezones-selection can be restricted
* reworked and extended a few internal functions
* corrected a few typos and fixed potential bugs

= 2.00 =

* start Changelog
* completely reworked API methods and internal structure
* Security improvements (wp_nonce, capabilities)
* reworked Admin Menu
* extracted JavaScript-code
* offer new function `$timezonecalculator->output()`
* all timezones, that are not set to specific datetime are now Ajax refreshable
* the timezone-name can now also be displayed if you opt-out from displaying the timezone-abbreviation
* localized datetimes and timezones
* added Admin Menu header clock
* possible to add in "Right Now" box on dashboard
* the calculator and a world-clock can be displayed in the tools-Menu.
* users are now able to choose their own timezones
* added log functionality
* reworked handling of settings in the Admin Menu
* deprecated old function `GetTimeZoneTime()`
* added contextual help to settings menu
* updated license to GPLv3
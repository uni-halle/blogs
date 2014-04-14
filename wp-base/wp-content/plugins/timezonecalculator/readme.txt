=== TimeZoneCalculator ===
Contributors: neoxx
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=J6ZGWTZT4M29U
Tags: time, date, timezone, calendar, world clock, clock, travel, widget, sidebar, dashboard, shortcode, multisite, multi-site, ajax, javascript, jquery, bar, admin bar
Requires at least: 3.3
Tested up to: 3.9
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Calculates, displays and automatically updates times and dates in different timezones with respect to daylight saving.

== Description ==

* based on PHP timezones database (please read the [FAQ](http://wordpress.org/plugins/timezonecalculator/faq/) for further information)
* fully optionpage-configurable
* easy to integrate (ships with multi/sidebar- and dashboard-widget functionality)
* display clock in WordPress Admin Bar
* possible to integrate in "Right Now" box or to display as widget on the dashboard and on the user's profile page
* Calculator section in Tools-Menu with individual timezone-selection for every user
* optional Ajax refresh with jQuery
* fully compatible with [https/SSL/TLS-sites](http://codex.wordpress.org/Administration_Over_SSL)
* drag and drop admin menu page
* [API for developers](http://wordpress.org/plugins/timezonecalculator/other_notes/)
* fully multisite network compatible
* clean uninstall

**Plan your travels with the free [JourneyCalculator](http://www.journeycalculator.com/) which is based on TimeZoneCalculator.**

Please find the version for WordPress

* 3.3 and higher [here](http://downloads.wordpress.org/plugin/timezonecalculator.zip)
* 2.8 to 3.2 [here](http://downloads.wordpress.org/plugin/timezonecalculator.wordpress2.8-3.2.zip)
* 1.5 to 2.7 [here](http://downloads.wordpress.org/plugin/timezonecalculator.wordpress1.5-2.7.zip)

**Plugin's website:** [http://www.bernhard-riedl.com/projects/](http://www.bernhard-riedl.com/projects/)

**Author's website:** [http://www.bernhard-riedl.com/](http://www.bernhard-riedl.com/)

== Installation ==

1. Copy the `timezonecalculator` directory into your WordPress Plugins directory (usually wp-content/plugins). Hint: You can also conduct this step within your Admin Menu.

2. In the WordPress Admin Menu go to the Plugins tab and activate the TimeZoneCalculator plugin.

3. Navigate to the Settings/TimeZoneCalculator tab and customize the timezones according to your desires.

4. If you have widget functionality just drag and drop TimeZoneCalculator on your widget area in the Appearance Menu. Add additional [function and shortcode calls](http://wordpress.org/plugins/timezonecalculator/other_notes/) according to your desires.

5. Be happy and celebrate! (and maybe you want to add a link to [http://www.bernhard-riedl.com/projects/](http://www.bernhard-riedl.com/projects/))

== Frequently Asked Questions ==

= I get the error-message `Fatal error: Class 'DateTimeZone' not found in` [..] =

TimeZoneCalculator uses the [PHP timezones library](http://php.net/manual/en/timezones.php). Thus, please make sure that you have a recent version of PHP including this library installed and enabled.

If any timezone information like offset, abbreviations, etc. appears to be wrong, please leave a message for the PHP guys on [their board](http://php.net/manual/en/timezones.php).

= Why can't I see the 'Drag and Drop' section? =

This section is based on JavaScript. Thus, you have to enable JavaScript in your browser (this is a default setting in modern browsers like [Mozilla Firefox](http://en.wikipedia.org/wiki/Firefox) or [Google Chrome](http://en.wikipedia.org/wiki/Google_Chrome)). TimeZoneCalculator is still fully functional without JavaScript, but you need to customize your timezones manually. If you use a device with a smaller display (e.g. mobile phone), this section will also be hidden.

== Other Notes ==

**Attention! - Geeks' stuff ahead! ;)**

= API =

With TimeZoneCalculator 2.00 and higher you can also realize a html select which displays for example the airtime of your internet radio station or your broadcasts in your users' local time. Another possibility for the usage of the upcoming function is the integration of date, time and timezone information into your travelling blog.

Parameters can either be passed [as an array or a URL query type string (e.g. "display=0&format=0")](http://codex.wordpress.org/Function_Reference/wp_parse_args). Please note that WordPress parses all arguments as strings, thus booleans have to be 0 or 1 if used in query type strings whereas for arrays [real booleans](http://php.net/manual/en/language.types.boolean.php) should be used. - Furthermore you have to break your timezones with \n : `America/New_York;EST;EWT;New York, NY, US;New York, NY, US;0;0\nEurope/Vienna;;;sleep longer in winter;get up earlier to enjoy the sun;1;0` if you want to use different timezones in a query_string. In case you use an array, an array should also be used for the timezones.

**`function $timezonecalculator->output($params=array())`**

$params:

- `query_time`: any unix timestamp (where `-1262304000 <= query_time <= 2145916800`) or any English textual datetime description in the range of `1930-01-01` and `2038-01-01` which can be parsed with [PHP's strtotime function](http://php.net/manual/en/function.strtotime.php); default is set to current UTC

- `query_timezone`: origin-timezone of `query_time`; you can choose a [PHP timezone_string](http://php.net/manual/en/timezones.php); otherwise `UTC` will be used

- `before_list`: default `<ul>`

- `after_list`: default `</ul>`

- `format_timezone`: default `<li><abbr title="%name">%abbreviation</abbr>: <span title="%name">%datetime</span></li>`

- `format_datetime`: default `Y-m-d H:i`

- `timezones`: alternative timezones-array - each array entry has to be a string as described in the Expert Settings of the Admin Menu; default is the timezones-entries array which can be modified in the Admin Menu

- `prefer_user_timezones`: prefer user set timezones - if they exist - to global or function call timezones; default is `false`

- `user_id`: determines which user's timezones should be used; not set as default -> use the timezones of the user who is currently logged in

- `use_container`: if set to `true` (default value), the current UTC is used as `query_time` and the same selected timezones and format is used as set in the admin menu, TimeZoneCalculator wraps the output in a html div with the class `timezonecalculator-refreshable-output` - the class `timezonecalculator-output` will be used for all other output; if you set `use_container` to `false`, no container div will be generated

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

== Screenshots ==

1. This screenshot shows the Settings/TimeZoneCalculator Tab with the Drag and Drop Section in the Admin Menu.

2. This picture presents the Preview Section of the TimeZoneCalculator Tab in the Admin Menu.

3. This screenshot illustrates the clock in the WordPress Admin Bar. - The date/time and display format can be customized.

4. The last picture shows the Calculator Section in the Tools Menu on a mobile phone.

== Upgrade Notice ==

= 3.00 =

This is a general code clean-up. - Please note that for TimeZoneCalculator v3.00 you need at minimum WordPress 3.3.

= 2.00 =

This is not only a feature but also a security update. - Thus, I'd strongly recommend all users of TimeZoneCalculator which have at least an environment of WordPress 2.8 or higher and PHP 5 to install this version!

== Changelog ==

= 3.10 =

* implemented responsive web design on settings-page and calculator (thanks for feedback to Alfred Dahlmann, Veronika Grascher and Christian Heiling)
* removed calls to screen_icon()
* improved performance
* extended length of format-parameters to provide space for example for mobile css-classes
* fixed some bugs (thanks for patches to Robert Koch and Yasen Tenev)
* removed filter timezonecalculator_available_admin_colors
* cleaned-up code

= 3.00 =

* changed settings-page to jQuery
* improved usability
* discontinued support for Prototype
* updated jshashtable to 3.0
* removed legacy-code -> minimum-version of WordPress necessary is now 3.3
* renamed option include_wordpress_clock_admin_head to include_wordpress_clock_admin_bar
* added caching for continent/timezone-select fields
* removed option ajax_refresh_lib
* removed deprecated function getTimeZonesTime()
* applied PHP 5 constructor in widget
* tested with PHP 5.4
* removed PHP closing tag before EOF
* removed reference sign on function calls
* adopted plugin-links to the new structure of wordpress.org
* cleaned-up code

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
* de-coupling of Ajax-refresh-functions and output of `wp_localize_script` (TimeZoneCalculator is now compatible with [WP Minify](http://wordpress.org/plugins/wp-minify/))
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
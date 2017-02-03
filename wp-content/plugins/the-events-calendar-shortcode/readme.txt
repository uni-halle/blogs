=== The Events Calendar Shortcode ===
Contributors: brianhogg, dandelionweb, ankitpokhrel, sujin2f
Tags: event, events, calendar, shortcode, modern tribe
Requires at least: 4.0
Tested up to: 4.7.2
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds shortcode functionality to The Events Calendar Plugin (Free Version) by Modern Tribe.

== Description ==

This plugin adds a shortcode for use with The Events Calendar Plugin (by Modern Tribe).

With this plugin, just add the shortcode on a page to display a list of your events. For example to show next 8 events in the category festival:

[ecs-list-events cat="festival" limit="8"]

= Shortcode Options: =
* Basic shortcode: [ecs-list-events]
* cat - Represents single event category. [ecs-list-events cat='festival'].  Use commas when you want multiple categories [ecs-list-events cat='festival, workshops']
* limit - Total number of events to show. Default is 5. [ecs-list-events limit='3']
* order - Order of the events to be shown. Value can be 'ASC' or 'DESC'. Default is 'ASC'. Order is based on event date. [ecs-list-events order='DESC']
* date - To show or hide date. Value can be 'true' or 'false'. Default is true. [ecs-list-events eventdetails='false']
* venue - To show or hide the venue. Value can be 'true' or 'false'. Default is false. [ecs-list-events venue='true']
* excerpt - To show or hide the excerpt and set excerpt length. Default is false. [ecs-list-events excerpt='true'] //displays excerpt with length 100
  * excerpt='300' //displays excerpt with length 300
* thumb - To show or hide thumbnail image. Default is false. [ecs-list-events thumb='true'] //displays post thumbnail in default thumbnail dimension from media settings.
* You can use 2 other attributes: thumbwidth and thumbheight to customize the thumbnail size [ecs-list-events thumb='true' thumbwidth='150' thumbheight='150']
* message - Message to show when there are no events. Defaults to 'There are no upcoming events at this time.'
* viewall - Determines whether to show 'View all events' or not. Values can be 'true' or 'false'. Default to 'true' [ecs-list-events cat='festival' limit='3' order='DESC' viewall='false']
* contentorder - Manage the order of content with commas. Default to `title, thumbnail, excerpt, date, venue`. [ecs-list-events cat='festival' limit='3' order='DESC' viewall='false' contentorder='title, thumbnail, excerpt, date, venue']
* month - Show only specific Month. Type 'current' for displaying current month only [ecs-list-events cat='festival' month='2015-06']
* past - Show Outdated Events. [ecs-list-events cat='festival' past='yes']
* key - Order with Start Date [ecs-list-events cat='festival' key='start date']

= Pro Version Options: =
* design - Shows improved design by default, or set to 'standard' for the regular one and 'compact' for a more compact listing
* days - Specify how many days in the future, for example [ecs-list-events days="1"] for one day or [ecs-list-events days="7"] for one week
* tag - Filter by one or more tags.  Use commas when you want to filter by multiple tags.
* hiderecurring - To only show the first instance of a recurring event, set to 'true'

[View Details and Get the Pro Version](https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=wordpress.org&utm_medium=link&utm_campaign=tecs-readme&utm_content=description)

This plugin is not developed by or affiliated with The Events Calendar or Modern Tribe in any way.

== Installation ==

1. Install The Events Calendar Shortcode Plugin from the WordPress.org repository or by uploading the-events-calendar-shortcode folder to the /wp-content/plugins directory. You must also install The Event Calendar Plugin by Modern Tribe and add your events to the calendar.

2. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==

= What are the shortcode Options: =
= Shortcode Options: =
* Basic shortcode: [ecs-list-events]
* cat - Show events from an event category [ecs-list-events cat='festival'] or specify multiple categories [ecs-list-events cat='festival, workshops']
* limit - Total number of events to show. Default is 5. [ecs-list-events limit='3']
* order - Order of the events to be shown. Value can be 'ASC' or 'DESC'. Default is 'ASC'. Order is based on event date. [ecs-list-events order='DESC']
* date - To show or hide date. Value can be 'true' or 'false'. Default is true. [ecs-list-events eventdetails='false']
* venue - To show or hide the venue. Value can be 'true' or 'false'. Default is false. [ecs-list-events venue='true']
* excerpt - To show or hide the excerpt and set excerpt length. Default is false. [ecs-list-events excerpt='true'] //displays excerpt with length 100
 excerpt='300' //displays excerpt with length 300
* thumb - To show or hide thumbnail image. Default is false. [ecs-list-events thumb='true'] //displays post thumbnail in default thumbnail dimension from media settings.
* You can use 2 other attributes: thumbwidth and thumbheight to customize the thumbnail size [ecs-list-events thumb='true' thumbwidth='150' thumbheight='150']
* message - Message to show when there are no events. Defaults to 'There are no upcoming events at this time.'
* viewall - Determines whether to show 'View all events' or not. Values can be 'true' or 'false'. Default to 'true' [ecs-list-events cat='festival' limit='3' order='DESC' viewall='false']
* contentorder - Manage the order of content with commas. Default to `title, thumbnail, excerpt, date, venue`. [ecs-list-events cat='festival' limit='3' order='DESC' viewall='false' contentorder='title, thumbnail, excerpt, date, venue']
* month - Show only specific Month. Type 'current' for displaying current month only. [ecs-list-events cat='festival' month='2015-06']
* past - Show Outdated Events. [ecs-list-events cat='festival' past='yes']
* key - Order with Start Date [ecs-list-events cat='festival' key='start date']

With [The Events Calendar Shortcode PRO](https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=wordpress.org&utm_medium=link&utm_campaign=tecs-readme-faq-options&utm_content=description) you also get the following options:

* design - Shows improved design by default, or set to 'standard' for the regular one and 'compact' for a more compact listing
* days - Number of days to show events for ie. [ecs-list-events days='7'] (for a week)
* hiderecurring - Show only the first instance of a recurring event
* tag - Show events with a tag [ecs-list-events tag='music'] or specify multiple tags [ecs-list-events tag='music,art']

= How do I use this shortcode in a widget? =

* You can put the shortcode in a text widget.
* Not all themes support use of a shortcode in a widget. If a regular text widget doesn't work, put the shortcode in a <a href="https://wordpress.org/plugins/black-studio-tinymce-widget/">Visual Editor Widget</a>.

= What are the classes for styling the list of events? =
By default the plugin does not include styling. Events are listed in ul li tags with appropriate classes for styling with a bit of CSS.

* ul class="ecs-event-list"
* li class="ecs-event"
* event title link is H4 class="entry-title summary"
* date class is time
* venue class is venue
* span .ecs-all-events
* p .ecs-excerpt

Want a better looking design without knowing any CSS?  Check out [The Events Calendar Shortcode PRO](https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=wordpress.org&utm_medium=link&utm_campaign=tecs-readme-faq-design&utm_content=description)

= How do I include a list of events in a page template? =
include echo do_shortcode("[ecs-list-events]"); in the template where you want the events list to display.

== Screenshots ==

1. After adding the plugin, add the shortcode where you want the list of events to appear in the page
2. Events will appear in a list
3. Many settings you can use in the shortcode to change what details appear in the events listing

== Upgrade Notice ==

= 1.4.1 =
* Additional filters for formatting a single event

= 1.4 =
* Checks for whether The Events Calendar is installed
* Additional filters
* Improved design of shortcode help page

= 1.3 =
* Fixes issue with "viewall" showing the events twice
* Fixes time zone issue by using current_time() instead of date()
* Hides events that are marked 'hide from listing'
* Switches to tribe_get_events() to get the events
* Removes the ... from the end of the excerpt if less than the excerpt length
* Adds date_thumb option
* Adds additional filters

= 1.2 =
* Updates author/description (Event Calendar Newsletter / Brian Hogg Consulting)

= 1.0.11 =
Add Link to Thumbnail
merge pull request from d4mation -Replaced extracted variables with $atts as using extract was deprecated
=1.0.10 =
Minor Error Change - fix  name and slug 
= 1.0.9 =
Minor Error Change - Multiple Categories
= 1.0.8 =
Add options : multi-categories - Thanks to sujin2f
= 1.0.7 =
Add options : contentorder, month, past, key  - Thanks to sujin2f
= 1.0.6 =
Fix missing ul
= 1.0.5 =
* Add excerpt and thumbnail - Thanks to ankitpokhrel
= 1.0.2 =
* Add venue to shortcode - Thanks to ankitpokhrel
= 1.0.1 =
* Fix Firefox browser compatibility issue
= 1 =
* Initial Release

== Changelog ==

= 1.4.1 =
* Additional filters for formatting a single event

= 1.4 =
* Checks for whether The Events Calendar is installed
* Additional filters
* Improved design of shortcode help page

= 1.3 =
* Fixes issue with "viewall" showing the events twice
* Fixes time zone issue by using current_time() instead of date()
* Hides events that are marked 'hide from listing'
* Switches to tribe_get_events() to get the events
* Removes the ... from the end of the excerpt if less than the excerpt length
* Adds date_thumb option
* Adds additional filters

= 1.2 =
* Updates author/description (Event Calendar Newsletter / Brian Hogg Consulting)

= 1.0.11 =
Add Link to Thumbnail
merge pull request from d4mation -Replaced extracted variables with $atts as using extract was deprecated
=1.0.10 =
Minor Error Change - fix  name and slug
= 1.0.9 =
Minor Error Change - Multiple Categories
= 1.0.8 =
Add options : multi-categories - Thanks to sujin2f
= 1.0.7 =
Add options : contentorder, month, past, key  - Thanks to sujin2f
= 1.0.6 =
Fix missing ul
= 1.0.5 =
* Add excerpt and thumbnail - Thanks to ankitpokhrel
= 1.0.2 =
* Add venue to shortcode - Thanks to ankitpokhrel
= 1.0.1 =
* Fix Firefox browser compatibility issue
= 1 =
* Initial Release

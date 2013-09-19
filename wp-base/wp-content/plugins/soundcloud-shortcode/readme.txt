=== SoundCloud Shortcode ===
Contributors: jowagener, theophani, por_
Tags: soundcloud, html5, flash, player, shortcode,
Requires at least: 3.1.0
Tested up to: 3.4.0
Stable tag: trunk

The SoundCloud Shortcode plugin allows you to integrate a player widget from SoundCloud into your Wordpress Blog by using a Wordpress shortcodes.

== Description ==

The SoundCloud Shortcode plugin allows you to easily integrate a player widget for a track, set or group from SoundCloud into your Wordpress Blog by using a Wordpress shortcode.
Use it like that in your blog post: `[soundcloud]http://soundcloud.com/LINK_TO_TRACK_SET_OR_GROUP[/soundcloud]`
It also supports these optional parameters: width, height and params.
The "params" parameter will pass the given options on to the player widget.
Our player accepts the following parameter options:

* auto_play = (true or false)
* show_comments = (true or false)
* color = (color hex code) will paint the play button, waveform and selections in this color
* theme_color = (color hex code) will set the background color

Examples:

`[soundcloud params="auto_play=true&show_comments=false"]http://soundcloud.com/forss/flickermood[/soundcloud]`
Embed a track player which starts playing automatically and won't show any comments.

`[soundcloud params="color=33e040&theme_color=80e4a0"]https://soundcloud.com/forss/sets/soulhack[/soundcloud]`
Embeds a set player with a green theme.

`[soundcloud width="250"]http://soundcloud.com/groups/experimental[/soundcloud]`
Embeds a group player with 250px width.


When posting the standard SoundCloud embed code, the plugin tries to use the new HTML5 player, but falls back to the Flash Player for legacy URL formats.

== Installation ==
== Frequently Asked Questions ==
== Screenshots ==

1. This is how the Flash player looks, which is still available as an option. It is also the fallback for legacy URL formats.

2. This is how the default player looks, which uses HTML5.

== Changelog ==
= 2.3 =
* Don’t use oEmbed anymore because of various bugs.
* Standard http://soundcloud.com/<user> permalinks will always return the flash widget. Use the widget generator on the website to get an API url.

= 2.2 =
* Improved default options support

= 2.1 =
* Integrate oEmbed

= 2.0 =
* HTML5 Player added as the default player, with Flash as an option and fallback for legacy URL formats.

= 1.2.1 =
* Removed flash fallback HTML

= 1.2 =
* Added options page to allow blog-wide global default settings.

= 1.1.9 =
* Fix to support resources from api.soundcloud.com
* Security enhancement. Only support players from player.soundcloud.com, player.sandbox-soundcloud.com and player.staging-soundcloud.com

= 1.1.8 =
Bugfix to use correct SoundCloud player host

= 1.0 =
* First version

=== SoundCloud Shortcode ===
Contributors: jowagener, theophani, por_
Tags: soundcloud, shortcode
Requires at least: 3.1.0
Tested up to: 3.8.1
Stable tag: trunk

SoundCloud Shortcode plugin for WordPress

== Description ==

This plugin converts all SoundCloud Shortcodes into embeddable SoundCloud players. It works for any SoundCloud track, playlist, user, or group. Once you install this plugin, it works for any of your blog posts.

A simple example:

`[soundcloud]http://soundcloud.com/forss/flickermood[/soundcloud]`

**More Options**

SoundCloud Shortcodes support these optional parameters:

* `width`
* `height`
* `params`

The `params` parameter passes additional options to the SoundCloud embeddable player. You can find a full list on the SoundCloud Developers pages: http://developers.soundcloud.com/docs/widget

An example of a track that starts playing automatically and won’t show any comments:

`[soundcloud params="auto_play=true&show_comments=false"]http://soundcloud.com/forss/flickermood[/soundcloud]`

== Installation ==

1. Upload `soundcloud-shortcode` to your plug-in directory or install it from the Wordpress Plug-in Repository
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 3.0.2 =
* Always load embeds over https

= 3.0.1 =
* Minor copy updates in readme.txt

= 3.0.0 =
* Make visual player the default player (option to disable in settings)

= 2.3.2 =
* Add developer documentation
* Update README

= 2.3.1 =
* Add support for permalinks in HTML5 shortcode

= 2.3.0 =
* Don’t use oEmbed anymore because of various bugs.
* Standard http://soundcloud.com/<user> permalinks will always return the flash widget. Use the widget generator on the website to get an API url.

= 2.2.0 =
* Improved default options support

= 2.1.0 =
* Integrate oEmbed

= 2.0.0 =
* HTML5 Player added as the default player, with Flash as an option and fallback for legacy URL formats.

= 1.2.1 =
* Removed flash fallback HTML

= 1.2.0 =
* Added options page to allow blog-wide global default settings.

= 1.1.9 =
* Fix to support resources from api.soundcloud.com
* Security enhancement. Only support players from player.soundcloud.com, player.sandbox-soundcloud.com and player.staging-soundcloud.com

= 1.1.8 =
Bugfix to use correct SoundCloud player host

= 1.0 =
* First version

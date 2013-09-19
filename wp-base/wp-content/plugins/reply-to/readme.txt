=== Plugin Name ===
Contributors: iyus
Donate link: http://yus.me/
Tags: reply, at-reply, comments, twitter
Requires at least: 2.8
Tested up to: 3.1.3
Stable tag: trunk

This plugin allows you to add Twitter-like @reply links to comments.

== Description ==

This plugin allows you to add Twitter-like @reply links to comments.
When clicked, those links insert the author name and a link to the comment you are replying to in the textarea.

Most of the code is taken from the Custom Smilies plugin by Quang Anh Do which is released under GNU GPL :
http://wordpress.org/extend/plugins/custom-smilies/

Thanks to Guillaume Ringuenet for the arrow graphics.

I didn't create anything new, and I do not claim so in any way.
This plugin is just a feature I wanted and judging by the number of downloads I guess I wasn't the only one. ;)

== Changelog ==

= 3.1.3 - Minor update =
* CSS fix for themes that aren't displaying comments inside <li> tags.

= 3.1 - Major update =
* Added compatibility with WordPress' threaded comments. If they're enabled (and supported by your theme), @ Reply will replace the native "reply" links, otherwise it will be just like the previous versions.

= 3.0.5 - Bugfix =
* WordPress 3.0.5 broke @ Reply, this should fix it.
* P.S. I told you this should be a theme feature and not a plugin. ;)

= 2.9 - Minor update =
* Added support for paged comments.

= 2.8 - Major update =
* Now compatible with WordPress 2.8 and higher **only**!
* You don't need to edit your theme anymore, just activate the plugin and it works.
* The plugin now uses your WordPress language file.

= 1.0.1 - Minor (graphic) update =
* replaced the arrow graphics with better **and** original ones made by Guillaume Ringuenet.

= 1.0 - Initial Release =

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. (optional) The `dreply.png` file is for dark background themes use it to replace the default `reply.png` file if you want/need.

== Frequently Asked Questions ==

= It doesn't work! I activated the plugin but I can't see the reply links. =

They *should* be visible only when you move your mouse cursor over a comment text. If it still doesn't work, open `at-reply.php` search for `visibility:hidden;` (line 26) and remove that part, then you can also remove the whole line 29 `.comment:hover .yarr { visibility:visible }` because it becomes useless. This will make the reply links always visible for **all** comments (and not just the one your mouse cursor is over).

= It doesn't work! I can see the reply links but clicking them does nothing. =

Either you have disabled JavaScript in your browser or your WordPress theme is not using the default id for the comments textarea (which is `comment`). In the later case, check your `comments.php` file to find your textarea id, then open `at-reply.php` and replace all the `document.getElementById('comment')` with `document.getElementById('YOUR_ID')`.

= This version sucks! I prefer the old one because I could choose where I want to put the reply link. =
It's hard to please everyone. For me, **@ Reply** should be a built-in theme feature and not a plugin. But plugins are easier to install (when it works).
You can still download the [previous version](http://downloads.wordpress.org/plugin/reply-to.1.0.1.zip), just remember to replace `Version: 1.0.1` with `Version: 2.8` (or any higher number) on line 6 so that WordPress doesn't suggest you to update the plugin.

= Possible conflicts with other plugins. =

A plugin that filters the default WordPress comments functions, may prevent @ Reply from working. For example, WP_Identicon does this, but it also has an alternate setup for "advanced users" that doesn't conflict with @ Reply.

== Screenshots ==

1. The reply links as they appear in comments.
2. This is automaticaly inserted when you click on a reply link.
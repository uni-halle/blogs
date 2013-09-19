=== German Slugs ===

Contributors: texttheater
Tags: slug, umlaut, german, url, permalink
Requires at least: 3.1
Tested up to: 3.1
Stable tag: trunk

German Slugs properly transliterates umlauts and the letter ß appearing in
titles for slugs (i.e. for pretty permalinks).

== Description ==

While creating a post or page, WordPress automatically suggests a permalink. If
your permalink structure contains the %postname% tag, then the title of your
post or page will be used, simplified by conversion to lowercase, replacing
spaces with hyphens, removing certain punctuation characters and removing
diacritics from latin letters.

Thus, by default, ä is replaced with a, ö with o, ü with u and ß with s (similar
for capital letters). For German, the conventional transliteration is ä to ae, ö
to oe, ü to ue and ß to ss (sometimes sz in Austria, this is not yet supported).
This plugin makes your WordPress apply the conventional transliteration.

I wrote this plugin because manually correcting WordPress's suggestions is a
considerable hassle, especially if you decide to change the title afterwards.

== Installation ==

Either:

1. Search for and install German Slugs directly through the 'Plugins' menu in WordPress

Or:

1. Download and unzip German Slugs
1. Upload the `german-slugs` directory to the `/wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Support ==

If you have questions or suggestions, contact me at poststelle ät texttheater döt
net.

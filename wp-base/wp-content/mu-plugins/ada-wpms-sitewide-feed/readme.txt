=== Ada WPMS Sitewide Feed ===
Contributors: skcsknathan001
Donate link: http://1uthavi.adadaa.com/
Plugin URI: http://1uthavi.adadaa.com/ada-wpmu-sitewide-feed-plugin/
Author: CAPitalZ
Author URI: http://1uthavi.adadaa.com/
Tags: wpmu, wordpressmu, wpms, multi-site, sitewide, feed, ada, adadaa
Requires at least: 2.8.6
Tested up to: 3.3.1
Stable tag: 0.5.2
License: GPL

Creates four rss 2.0 feeds showing recent posts, comments, pages, and one combined [posts and pages] from all blogs.  Works both on WPMS, and WPMU

== Description ==

This plugin creates four (4) seperate RSS 2.0 feeds from posts, comments, pages, and one combined [posts & pages] across all blogs on your site.

Works both on WPMS 3.0 & up, and WPMU 2.9.2.

This will skip the first comment and page of a blog; also will not include private, spam, mature and deleted blogs.

Both Subdirectory and VHOST installations are now supported. Thanks to DaryL.

Features:

* Generates sitewide combined [posts & pages] feed
* Generates sitewide posts feed
* Generates sitewide comments feed
* Generates sitewide pages feed
* Client-side caching via ETag headers
* Admin screen to configure options
* Compliance with xhtml and xml standards & many more

For more explanation and to leave any suggestions to improve or bugs, please visit on my blog : [Ada WPMS Sitewide Feed](http://1uthavi.adadaa.com/ada-wpmu-sitewide-feed-plugin/)

== Installation ==

WordPress automatic installation is fully supported and recommended.

If you want to manually install

* WPMU:

Unzip and upload the file ada-wpms-sitewide-feed.php to mu-plugins directory.  It is automatically activated.

You should see Ada Sitewide Feed under the Super Admin menu.

* WP 3.0 & up:

You can either create a directory called "mu-plugins" inside the /wp-content/ folder and put ada-wpms-sitewide-feed.php inside, which in turn, will automatically activate the plugin.
Or you can put it inside the plugin directory and activate it yourself.

You should see Ada Sitewide Feed under the Settings in Network Admin section.

== Upgrade Notice ==

Just replace the old file with this.


= 0.5.2 =
Made it to work both in WPMU and WP 3.0

= 0.5.1 =
Cyril AKNINE Fixed many bug issues with WP 3.0

= 0.5.0 =
Optimized, bug fixed WP Object Cache, added display of site logo, inclution of author avatars

= 0.4.0 =
Optimized, bug fixed WP Object Cache, added an additional feed

= 0.3.2 =
I.T. Damager's original version

== Frequently Asked Questions ==

= Why http://yourdomain.com/full-feed/ is not working? =

* Only if you install WPMU/ WP 3.0 as subdomain installation when you installed WordPress, you will be able to have feeds like "yourdomain.com/full-feed/". When you install WPMU/ WP 3.0 into a subdirectory installation, your feed url will be something like "yourdomain.com/subdirectory?wpmu-feed=full-feed".

* wp-config.php should have either of the below constants defined and set, in order for the plugin to detect its a subdomain installation.

` define('SUBDOMAIN_INSTALL', true); `
` define('VHOST', 'yes'); `

= My feed is working.  But when I include in RSS Widget, it does not show the entries? =

You must disable your cache plugin to cache the feed as a static page.
If you are using W3-Total-Cache plugin, add your Feed URL (relative path): to the Never cache the following pages: section under Page Cache Settings. Ex. "/full-feed/" and make sure to clear empty all caches.

The reason is your cache plugin makes the cache of the feed [which has a Content-Type: application/xml].  But after it is cached, when the browser requests, your cache plugin deliver the already cached static file, which now is delivered with Content-Type: text/html].  Some cases, this causes the RSS Widget to not to recognize the feed.

= Is the WP Object Cache still in use? =

I know its out-of-date.  I kept the original codes and cleaned long time ago.  I should probably remove WP Object Cache in the next versions.  Please suggest a better way on my blog [Ada WPMS Sitewide Feed](http://1uthavi.adadaa.com/ada-wpmu-sitewide-feed-plugin/)

== Screenshots ==

1. Super Admin Settings

== Usage ==

Compatible with WP Multi-Network plugin . You can have different blog ID than 1 to trigger the full feed. 

== To Do ==

* Comply fully for language translation
* Exclude blogs by ID
* Enable/disable individual sitewide feeds
* Option to include the blog title in the post title shown in the feed
* Consider changing the function ada_get_avatar_url() to use the core hook get_avatar(). So it can work with WPMUDEV's Avatars for Multisite.
* Option to exclude by post types
=== SoundCloud Is Gold ===
Contributors: Thomas Michalak
Donate link: http://www.mightymess.com/soundcloud-is-gold-wordpress-plugin
Tags: soundcloud, integrated, media, shortcode, browse, design, easy, simple, music, sound, js, live preview, flash, html5
Requires at least: 3.2
Tested up to: 3.9
Stable tag: 2.2.2

Browse through your soundcloud tracks, sets and favourites. Select and add tracks, sets or favourites to your posts. Live preview, easy.

== Description ==

** Compatible with new WP 3.9 **

**Now with soundcloud's official html5 player!**

**New Widget to display latest and random track, favorites or sets for one user, multiple users or random users.**

**Soundcloud is Gold** integrates perfectly into wordpress. Browse through your soundcloud tracks, sets and favorites from the 'Soundcloud is gold' tab in the post's 'upload media' popup window. Select, set and add track, sets, favorites to your post using the soundcloud player. Live Preview, easy, smart and straightforward.
You can set default settings in the option page, choose your defaut soundcloud player (Mini, Standard, Artwork, Html5), it's width, add extra classes for you CSS lovers, show comments, autoplay and your favorite color.
You'll also be able to set players to different settings before adding to your post if you fancy a one off change.

**Save multiple users, very useful for labels, collectives or artists with many projects.**

**Soundcloud is Gold** use a shortcode but the "Soundcloud is Gold" tab will write it for you dynamicly as you select parameters, and on top of this it will provide a nice live preview of your player so you know what does what. When done just press the 'insert soundcloud player' and it will added to your post just like when you're adding a photo or gallery.

If you love it please rate it! If you use it and want to help, [donations are always welcomed](http://www.mightymess.com/soundcloud-is-gold-wordpress-plugin) or you could like, tweet or spread the love on your blog ;)

Latest developments updates on twitter: [#soundcloudisgold](https://twitter.com/#!/search/realtime/%23soundcloudisgold) or follow me on [twitter](http://twitter.com/#!/mighty_mess)

Check out my [TM soundcloud profile](http://www.soundcloud.com/t-m), more [mighty mess](http://www.mightymess.com).

= Features =

* Browse through your soundcloud tracks, sets and favorites from a tab in the media upload window (see screenshot), no need to go back and forth between soundcloud and your website.
* Save multiple users, very useful for labels, collectives or artists with many projects
* Live Preview in the Tab, see what does what instantly (see screenshot).
* Integrates perfectly with wordpress media upload by using the same listing style that you get with the images (see screenshot).
* See track's info directly in the tab (description, url, cover, etc...).
* Set default settings from the option page (see screenshot):
    * Default player type (Mini, Standard, Artwork, Html5)
    * Width
    * Extra Classes for the div that wraps around the player
    * Auto Play
    * Show/Hide Comments
    * Player's Colors
* Use shortcode
* Plugin construct shortode for you, no need to remember any syntax.
* Style sortcode for neat layout in your editor.
* Implement Soundcloud Html5 player (beta).
* Widget for showing latest and random track, favorites or sets for one user, multiple users or random users.
* Follow WP developpers guidelines (enqueue scripts and styles just for the plugin, clean code, commented, secure and leave no trace when uninstall ).

= Advantages against pasting embed code from soundcloud =

* By changing the main settings in the options, all players on your site using the default settings will change. If green isn't trendy anymore and black is the new white, it won't be a problem and you keep your street credibility safe.
* If Soundcloud update their player or release a even cooler new player that let you scratch your track while streaming to google+, I will most defenetly update the plugin to use those new features.

That's just my opinion of course...


= To Do List =

* v3: So much to do I can't list it.
* V3: Advance Settings (change background color and comments color, playcounts, buy link, font, wmode, etc, show/hide styled shortcode, number of tracks per page)
* v3: url attribute for shortcode: easier for people using the shortcode manually.
* v3: other soundcloud shortcode conflict fix (jetpack)
* Add Soundcloud default Width to the options
* Trigger live preview when changing Soundcloud user name
* Live search while typing a name in the user name field. So if you're looking for someone it's kind of easier.
* Add 'activities' to a widget
* Fall Back for smartphone to html5 player when using flash player.


== Installation ==

= Updating =
When updating to 2.0, if you're experiencing issues, deactivate and reactivate the plugin from the plugin page. This is due to switching to the Settings API. Sorry for the inconvenient. 

Just follow the usual procedure. Log on to your wordpress, go to plugin -> add new -> search 'Soundcloud is Gold' -> click install



== Frequently Asked Questions ==

= I can't see my tracks? =

* Have you entered your real username? Your username is what you see in your soundcloud url when you click your name in soundcloud or view public profile (e.g http://soundcloud.com/anna-chocola ).
* Bare in mind is that all tracks that are set as private on soundcloud won't appear.
* Have you got other soundcloud plugin installed? That generally happen as you've been 'shopping around', disable them or even delete them and this if it works.

= It's behaving strangely or working partially or I've check everything but it still doesn't work =

Here's a simple method to track down incompatibilities with plugins and themes:

* Disable all plugins
* Enable 'soundcloud is gold' and check if it works (add a track to a post to be sure)
* If it worked: enable the other plugins one by one and check if it breaks
* If it didn't worked: enable the default Worpress theme and check if it works (add a track to a post to be sure).

Remenber that even if a plugin is popular, most of the plugins are badly coded or the developer didn't follow Wordpress guidelines on plugin development. Therefor conflict happens. The method is useful not just for this plugin. 

= Can't play my tracks on my iphone, ipad or ipod? =

Soundcloud has just released a html5 player. It's currently in it's beta version, which means that there might be bugs. Soundcloud is gold give you the option to use the html5 player but it's either flash all flash or all html5 for now.

= How can I use the shortcode manually? =

If for some reason you wish to use the shortcode manually, like for embeding someone else tracks, you can use:

**[soundcloud id='10450254']**
or
**[soundcloud user='t-m']** to always display the latest track
 
This will use your default setting of with, classes, colors, autoplay, comments. (Replace *10450254* with the track id you want to show)

If you wish to have more control here is an example:

**[soundcloud id='10450254' comments='true' autoplay='false' playertype='Standard' width='100%' color='#005bff']**


= Can I request features? =

Yes, you can. If asked nicely and the requests are sensibles, I almost always integrate them to new releases.

= Can you help me? =

Sometimes, I generally keep a eye on my plugin's forums and website's comments. Bear in mind that I've got a full time job and a life, so I can't always help straight away. I will not reply to people who obviously don't read the faqs or the forum or just say 'it doesn't work'.

== Upgrade Notice ==

= 2.2.1 =
* Security Update. Thanks to Samuel Wood for his help and time.

= 2.2 =
Widget Update! Display latest and random track, favorites or sets for one user, multiple users or random users.
Perfect for labels, collectives, festivals and schizophrenic artists.

= 2.1 =
New widget to display a user's latest track. New "user" argument for the shortcode to display user's latest track.

= 2.0 =
When updating to 2.0, if you're experiencing issues, deactivate and reactivate the plugin from the plugin page. This is due to switching to the Settings API. Sorry for the inconvenient. 



== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg
4. screenshot-4.jpg
5. screenshot-5.jpg


== Changelog ==

= 2.2.1 =
* Fix bug with tinymce plugin that prevented user from using editot
* Fixing bug mean that the delete and edit button on the soundcloud-is-gold image in the editor have been removed
* Fixed the insert-shortcode button, now close the modal window once shortcode inserted

= 2.2.1 =
* Security Update. Thanks to Samuel Wood for his help and time.

= 2.2 =
* Widget Update! Display latest and random track, favorites or sets for one user, multiple users or random users.

= 2.1 =
* Widget to display a user's latest track in the sidebar
* New "user" argument for the shortcode to show latest track of an user

= 2.0 =
* Save multiple users, very useful for labels, collectives or artists with many projects.
* favourites browsing fix.
* Settings API (Should fix the multi wp install issues)

= 1.0.7 =
* Moved to Settings API, which should enable multi-site compatibility (good tutorial at http://www.presscoders.com/2010/05/wordpress-settings-api-explained/ and plugin for reference at http://wordpress.org/extend/plugins/plugin-options-starter-kit/)
* Fixed bug where pagination would always go back to tracks when browsing sets or favourites. Thanks to givafizz for spotting it.

= 1.0.6 =
* Now you can browse and add Sets and your favorites ;)

= 1.0.5 =
* New Soundcloud official Html5 player! Woop Woop!

= 1.0.4 =
* Faster loading of the tab (only load player's preview when click on 'show')
* Pagination as people with more than 50 tracks couldn't access the rest of their tracks (25 tracks per page)
* Styled shortcode in tinymce editor with delete and edit buttons

= 1.0.3.2 =
* Emergency fix linked to soundcloud server been attacked (DDoS): Added user-agent header to request.

= 1.0.3 and 1.0.3.1 =
* Fixed warning message related to xml not loading when allow_url_fopen is disable: Now using cURL as a first choice for getting xml, and then simplexml_load_file as a last desperate option. Thanks a million to Karl Rixon (http://www.karlrixon.co.uk/).

= 1.0.2 =
* Fixed minor warnings
* Made shortcode stronger/safer with shortcode_atts()
* Set object mode to transparent until V1.1 is ready with new advance settings.
* Made all shortcode attributes lowercase (autoPlay is now autoplay, playerType is now playertype). sorry about that but it's needed.

= 1.0.1 =
* Fixed shortode using echo instead of return (silly I know). This caused the shortcode to be outputted at the top of the post instead of it's position in the post. Thanks raceyblood for spotting it.

= 1.0 =
* Hello world! Listen to me.
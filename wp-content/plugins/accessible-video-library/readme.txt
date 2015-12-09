=== Accessible Video Library ===
Contributors: joedolson
Donate link: http://www.joedolson.com/donate.php
Tags: a11y, video, library, manager, captions, subtitles, transcription, i18n, l10n
Requires at least: 4.0
Tested up to: 4.2.2
License: GPLv2 or later
Stable tag: 1.1.0

Generates a library for your video information where you can upload caption files, include transcripts, and upload subtitles for other languages.

== Description ==

Accessible Video Library solves a problem in WordPress: the ability to manage videos and manage critical associated media including captions, transcripts, and subtitles for each video.

In WordPress 3.6, WordPress incorporated the MediaElements.js library for showing videos. It's a good library, and includes native support for captions and internationalization, is keyboard accessible, and supports YouTube.

However, the WordPress implementation doesn't provide any method to add captions, subtitles, or reference YouTube videos. You can always embed YouTube videos using oEmbed, but these videos don't have much at all in the way of keyboard support. 

Accessible Video Library gives you a custom post type that you can use to manage your video media. You can upload captions in .SRT or .DFXP format, upload subtitles also in .SRT or .DFXP format, use the content of the post to include a video transcript, and can reference YouTube videos. 

Additionally, you can upload multiple formats of your video files to improve browser compatibility for your videos.

= Translations =

Available languages (in order of completeness):
German, Portuguese (Brazil), Polish, French, Dutch

Visit the [Accessible Video Library translations site](http://translate.joedolson.com/projects/accessible-video-library) to check the progress of a translation.

Translating my plug-ins is always appreciated. Visit <a href="http://translate.joedolson.com">my translations site</a> to start getting your language into shape!

<a href="http://www.joedolson.com/translator-credits/">Translator Credits</a>

== Installation ==

1. Upload the `/accessible-video-library/` directory into your WordPress plugins directory.
2. Activate the plugin on your WordPress plugins page 
3. Start uploading your videos and captions!

== Changelog ==

= 1.1.1 =

* Filter on MEJS settings 'avl_mediaelement_args'
* Option to enable subtitle/caption by default.
* Bug fix: URL replacement supports broader variety of YouTube URLs
* Bug fix: No height on video player in some contexts.
* Bug fix: Subtitles would only show up if Captions were also added.
* Updated mediaelementjs init file.
* Added translations: German, French, Portuguese (Brazil), Polish, Dutch

= 1.1.0 =

* Feature: Introduces option for responsive videos.
* Bug fix: Use reply-to header instead of from header in support messages.
* Bug fix: Support form submitted to wrong page.
* Bug fix: change mime-type for DFXP subtitle format
* Bug fix: Provide default height/width if not available.

= 1.0.7 =

* Bug fixes: avl_media shortcode. 
* Bug fix: JS was stripping base URL from inserted URLs, but that breaks if site is not at root.
* New attributes in avl_media shortcode: orderby and order

= 1.0.6 =

* Bug fix: Various textdomain issues.
* Bug fix: Menu position potential collision.

= 1.0.5 =

* [CSS] Fix text issue if placed immediately next to shortcode.
* [CSS] Added styling to caption selector controls to increase accessibility of hover state.
* Bug fix: extra space in video parameters broke non-attached videos.
* Bug fix: video disappeared for transcripted YouTube-only videos.
* Thanks to <a href="http://www.coolfields.co.uk/">Graham Armfield</a> for patient testing!

= 1.0.4 =

* Added support for DFXP format caption files.
* Added filters to filter videos to only those without captions or transcripts.

= 1.0.3 =

* Bug fix: broken uploader.js 

= 1.0.2 =

* Figured out how to force alwaysShowControls to true, so keyboard & touch accessibility is more reliable.
* Adjusted video control bar to appear below video.
* Increased default font size on captions from 12px to 14px.

= 1.0.1 =

* Moved Help into Video submenu
* Added shortcode note into post meta box.
* Added ID column to Video posts screen.

= 1.0.0 =

* Initial release.

== Frequently Asked Questions ==

= My transcript links go to a 404 error. How can I fix that? =

Go to Settings > Permalinks and update your permalinks format -- the issue is that the permalink format for AVL isn't being understood by WordPress, and needs to be registered.

== Screenshots ==

1. Accessible Video Library posts page
2. Accessible Video Library meta box

== Upgrade Notice ==

= 1.1.0 =
Responsive video, Miscellaneous bug fixes.
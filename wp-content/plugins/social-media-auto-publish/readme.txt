=== Social Media Auto Publish ===
Contributors: f1logic
Donate link: http://xyzscripts.com/donate/
Tags:  social media auto publish, social media publishing, post to facebook, post to twitter, post to linkedin, social network auto publish, social media, social network, add link to facebook, add link to twitter, add link to linkedin, publish to facebook, publish to twitter, publish to linkedin
Requires at least: 3.0
Tested up to: 4.6.1
Stable tag: 1.7
License: GPLv2 or later

Publish posts automatically to social media networks like Facebook, Twitter and LinkedIn.

== Description ==

A quick look into Social Media Auto Publish :

	★ Publish message to Facebook with image
	★ Attach post or share link  to Facebook
	★ Publish to Twitter with image
	★ Publish to LinkedIn with image
	★ Filter items  to be published based on categories
	★ Filter items to be published based on custom post types
	★ Enable or disable wordpress page publishing
	★ Customizable  message formats for Twitter, LinkedIn and Facebook


= Social Media Auto Publish Features in Detail =

The Social Media Auto Publish lets you publish posts automatically from your blog to social media networks like Facebook, Twitter and LinkedIn. The plugin supports filtering posts based on  custom post-types as well as categories.

The prominent features of  the social media auto publish plugin are highlighted below.

= Supported Social Media =

The various social media supported are listed below. 

    Facebook
    Twitter
    LinkedIn

= Filter Settings =

The plugin offers multiple kinds of filters for contents to be published automatically.

    Enable or disable publishing of wordpress pages
    Filter posts to be published based on categories
    Filtering based on custom post types

= Message Format Settings =

    Separate message format for Facebook, Twitter and LinkedIn
    Supports post title, description, excerpt, permalink, blog title and user nicename

= Posting options =

    Publish message to Facebook with image
    Attach post to Facebook
    Share link on Facebook
    Post to specific pages on Facebook
    Post to Twitter with image    
    Post to LinkedIn with image    


= About =

Social Media Auto Publish is developed and maintained by [XYZScripts](http://xyzscripts.com/ "xyzscripts.com"). For any support, you may [contact us](http://xyzscripts.com/support/ "XYZScripts Support").

★ [Social Media Auto Publish User Guide](http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish User Guide")
★ [Social Media Auto Publish FAQ](http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish FAQ")

== Installation ==

★ [Social Media Auto Publish User Guide](http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish User Guide")
★ [Social Media Auto Publish FAQ](http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish FAQ")

1. Extract `social-media-auto-publish.zip` to your `/wp-content/plugins/` directory.
2. In the admin panel under plugins activate Social Media Auto Publish.
3. You can configure the settings from Social Media Auto Publish menu. (Make sure to Authorize Facebook application after saving the settings.)
4. Once these are done, posts should get automatically published based on your filter settings.

If you need any further help, you may contact our [support desk](http://xyzscripts.com/support/ "XYZScripts Support").

== Frequently Asked Questions ==

★ [Social Media Auto Publish User Guide](http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish User Guide")
★ [Social Media Auto Publish FAQ](http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish FAQ")

= 1. The Social Media Auto Publish is not working properly. =

Please check the wordpress version you are using. Make sure it meets the minimum version recommended by us. Make sure all files of the `social media auto publish` plugin are uploaded to the folder `wp-content/plugins/`


= 2. Can I post to Facebook pages instead of profile ? =

Yes, you can select the pages to which you need to publish after authorizing Facebook application.


= 3. How do I restrict auto publish to certain categories ? =

Yes, you can specify the categories which need to be auto published from settings page.


= 4. Why do I have to create applications in Facebook, Twitter and LinkedIn ? =

When you create your own applications, it ensures that the posts to Facebook, Twitter and LinkedIn are not shared with any message like "shared via xxx"


= 5. Which  all data fields can I send to social networks ? =

You may use post title, content, excerpt, permalink, site title and user nicename for auto publishing.


= 6. Why do I see SSL related errors in logs ? =

SSL peer verification may not be functioning in your server. Please turn off SSL peer verification in settings of plugin and try again.


More questions ? [Drop a mail](http://xyzscripts.com/members/support/ "XYZScripts Support") and we shall get back to you with the answers.


== Screenshots ==

1. This is the Facebook configuration section.
2. This is the Twitter configuration section.
3. This is the LinkedIn configuration section.
4. Publishing options while creating a post.

== Changelog ==

= Social Media Auto Publish 1.7 =
* Facebook api updated
* Twitter 140 character exceeding issue fixed

= Social Media Auto Publish 1.6.1 =
* Fixed custom post types autopublish issue	
* Fixed duplicate autopublish issue

= Social Media Auto Publish 1.6 =
* Added option to enable/disable utf-8 decoding before publishing	
* Removed unwanted configuration related to 'future_to_publish' hook
* Removed unwanted setting "Facebook user id"
* Postid added in autopublish logs
* Updated auto publish mechanism using transition_post_status hook
* Open graph meta tags will be prefered for facebook and linkedin attachments

= Social Media Auto Publish 1.5.2 =
* Latest five auto publish logs for each social media account are maintained
* Inline edit of posts will work according to the value set for "Default selection of auto publish while editing posts/pages" 
* Resolved issue in fetching facebook pages in settings page (in case of more than 100 pages)

= Social Media Auto Publish 1.5.1 =
* Updated Linkedin authorization

= Social Media Auto Publish 1.5 =
* Updated Linkedin API
* Auto publish added during quick edit 
* Added option to enable/disable "future_to_publish" hook for handling auto publish of scheduled posts	
* Added options to enable/disable "the_content", "the_excerpt", "the_title" filters on content to be auto-published

= Social Media Auto Publish 1.4.3 =
* Fixed category display issue
* Removed outdated facebook scopes from authorization

= Social Media Auto Publish 1.4.2 =
* Bug fix for duplicate publishing of scheduled posts

= Social Media Auto Publish 1.4.1 =
* Fixed auto publish related bug in post edit
* Fixed message format bug in auto publish
* Updated Facebook authorization

= Social Media Auto Publish 1.4 =
* Option to configure auto publish settings while editing posts/pages
* General setting to enable/disable post publishing
* Added auto publish for scheduled post
* Fixed issue related to \" in auto publish

= Social Media Auto Publish 1.3.2 =
* Fixed auto-publish of scheduled post

= Social Media Auto Publish 1.3.1 =
* Added compatibility with wordpress 3.9.1
* Facebook API V 2.0 compatibility added
* Compatibility with bitly plugin

= Social Media Auto Publish 1.3 =
* View logs for last published post
* Option to enable/disable SSL peer verification
* Option to reauthorize the Facebook/LinkedIn application

= Social Media Auto Publish 1.2.2 =
* Bug fixed for &amp;nbsp; in post
* Twitter api updated to https

= Social Media Auto Publish 1.2.1 =
* Default image fetch logic for auto publish updated
* Thumbnail image logic for facebook updated
* LinkedIn bug fixed

= Social Media Auto Publish 1.2 =
* Support for user nicename in auto publish
* A few bug fixes 

= Social Media Auto Publish 1.1.1 =
* Fix for multiple posting to social media
* Fixed PHP version compatability issue for versions less than 5.3
* A few bug fixes 

= Social Media Auto Publish 1.1 =
* Support for publishing to LinkedIn
* A few bug fixes 

= Social Media Auto Publish 1.0 =
* First official launch.

== Upgrade Notice ==

= Social Media Auto Publish 1.2.1 =
If you had issues  with default image used for auto publishing as well as linkedin publishing, you may apply this upgrade.

== More Information ==

★ [Social Media Auto Publish User Guide](http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish User Guide")
★ [Social Media Auto Publish FAQ](http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/ "Social Media Auto Publish FAQ")

= Troubleshooting =

Please read the FAQ first if you are having problems.

= Requirements =

    WordPress 3.0+
    PHP 5+ 

= Feedback =

We would like to receive your feedback and suggestions about Social Media Auto Publish plugin. You may submit them at our [support desk](http://xyzscripts.com/support/ "XYZScripts Support").

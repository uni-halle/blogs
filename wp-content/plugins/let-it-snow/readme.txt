=== Plugin Name ===
Contributors: Aen
Donate link: http://twitter.com/Aen
Tags: snow, christmas, xmas, flakes, aen
Requires at least: 1.5
Tested up to: 3.1
Stable tag: 3.0

All you need to do is upload the plugin folder to your plugin directory and activate to see falling snow on your blog.

== Description ==

**Update Jan 30, 2011**

Updated the plugin to the latest stable version of the snow script. Added additional options to the plugin admin page.

**Update Nov 07, 2009**

I have not been supporting this plugin for a long long time, almost 2 years in fact. Since Christmas is round the corner, I thought I should give this plugin a nice refresh. I have updated the snow script to the latest version. Best of all, I have added a plugin settings page so it's easier for those who are not comfortable with tweaking code.

**History**

In 2007, WordPress.com introduced a [falling snow options](http://wordpress.com/blog/2007/12/25/let-it-snow/) for its users after [Matt Mullenweg asked for a falling snow script](http://photomatt.net/2007/12/23/falling-snow-script/). Because the feature was only available to WordPress.com users and not self-hosted WordPress blogs, I took the falling snow code and made a plugin that exactly the same thing. All users needed to do was to download and activate the plugin to have beautiful falling snow flakes on the blogs.

== Installation ==

1. upload the folder `let-it-snow` with its contents to `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress Admin Panel
1. Configure snowfall if you like, in the Let It Snow! settings page. That's it!

== Frequently Asked Questions ==

= The snow looks weird =

Check that your theme is not applying styles to all `IMG` elements. If you are not sure what this is, contact your theme's author.

= It's not working =

If it does not work for you. Check that your theme has the `wp_head()` function call in header.php. If it does not, add `<? wp_head(); ?>` just before the closing `</head>` tag.
If you have questions or need help, leave a comment here.

= I have a suggestion! =

Sure! Write to me at [hello@aentan.com](mailto:hello@aentan.com) with your suggestions.

== Notes & Credits ==

The falling snow script used in this plugin is originally by [Scott Schiller](http://www.schillmania.com/projects/snowstorm/).
This plugin is written by [Aen Tan](http://aentan.com/).

You should follow me on Twitter *[here](http://twitter.com/Aen)*.

= Changelog =
**3.0** [January 30, 2011] Updated snow fall script and generally cleaned things up.
**2.0** [November 07, 2009] Updated snow fall script and generally cleaned things up.
**1.3** [December 27, 2007] Changed z-index of snow so it's on top of everything.
**1.2** [December 26, 2007] snowCollect now working, thanks to Scott Schiller for his help.
**1.1** Added additional style declarations for themes that style the img tag.
**1.0** [December 25, 2007] First version
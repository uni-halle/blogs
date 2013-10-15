=== Very Simple Sitemap ===
Contributors: roidayan
Donate link: http://roidayan.com
Tags: sitemap, page, xml, sitemapxml
Requires at least: 3.2.1
Tested up to: 3.3.1
Stable tag: 1.1

Very simple plugin to help create a basic sitemap page and sitemap xml file.

== Description ==

Very simple plugin to help create a basic sitemap page and sitemap xml file.

The site map page is based the info from
http://www.intenseblog.com/wordpress/wordpress-sitemap-page.html

== Installation ==

1. Extract the folder into your wordpress plugins directory.
2. Activate the plugin.

sitemap.xml and sitemap.xml.gz are auto created for you
and updated once a day.

3. To create a sitemap page:
  *  Add a new page with title 'sitemap'
  *  Write '[sitemap'] in the content
  *  Disable the page comments

== Frequently Asked Questions ==

= How to exclude pages from sitemap ? =

Since there is no options page you will need to add the php file.
This is easy and does not require something special.
Go to the plugins editor in the admin area and choose the plugin.
Go to the line where u see the comment to put excluded pages here.
Add the pages ids to be excluded and save.

== Screenshots ==

== Changelog ==

= 1.1 =
* added option to exclude pages.

= 1.0 =
* first
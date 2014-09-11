=== Delicious XML Importer ===
Contributors: sillybean
Tags: delicious, links, bookmarks, import
Donate Link: http://sillybean.net/code/wordpress/delicious/
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.4

Lets you import your Delicious bookmarks into WordPress as links, posts, or a custom post type.

== Description ==

= Notes =

On the import screen, there's a link to <a href="https://api.del.icio.us/v1/posts/all?meta=yes">the Delicious API page</a> that exports all your bookmarks (including the private ones) to an XML file. Do not right click and press "save;" you will need to log in using your Delicious username and password first. Once the XML appears, you can save the file to your desktop. (Some browsers, like Safari, will show you a blank page. View source to make sure the XML is there.)

You can get all the links saved since your last import by using the <a href="http://www.delicious.com/help/api#posts_all"><kbd>&fromdt</kbd> argument</a>. There are several other arguments you can use.

You will have the option to import your bookmarks as links, with your Delicious tags becoming link categories, or as posts. Note that the WordPress link manager does not break up long lists into pages, the way the post and page managers do. If you have a lot of Delicious bookmarks, your link manager could become very, very slow after importing. You might be better off importing your bookmarks as posts, at least until WP's link manager screen gets updated.

See also: <a href="http://links.sillybean.net/">Twenty Links</a>, a child theme for Twenty Ten based on the old Delicious design.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit Tools > Import > Delicious to begin importing.

== Frequently Asked Questions ==

= Troubleshooting =

Check the number of Delicious links you have. (It's shown at the top of your Delicious page, and it's near the top of the XML file as well.) If the importer stops before it reaches that number, you can edit your XML file to remove all the bookmarks up to that point and try again.

The importer cannot handle bookmarklets (links that begin with <em>javascript:</em>). If you come across one in your export file, you can remove the bookmarklet's entire <post> line from your XML file and re-import. 

= Translations =

If you would like to send me a translation, please write to me through <a href="http://sillybean.net/about/contact/">my contact page</a>. Let me know which plugin you've translated and how you would like to be credited. I will write you back so you can attach the files in your reply.

== Upgrade Notice ==

= 0.3 =
Updated to accommodate changes in the Delicious export API.
= 0.4 =
Supports importing links into a custom post type. New option to save the link into a custom field rather than the post content.

== Changelog ==

= 0.4 =
* Supports importing links into a custom post type.
* New option to save the link into a custom field rather than the post content.
* More notes on the importer screen about changes to the Delicious export API.
= 0.3 =
* Delicious now exports links with the word "empty" in the extended description if you didn't enter any notes. Links without tags are also tagged with "empty." The importer will ignore this, so that your notes and tags are actually empty.
* When importing links as posts, the link will now be the first thing saved in the content area, followed by your notes. (Previously, the notes came first.)
* Removed link to the Delicious XML Exporter service, since all users should be able to access the API now that AVOS has taken over Delicious.
= 0.2.1 =
* Added link to the <a href="http://deliciousxml.com/">Delicious XML Exporter service</a> for users who can't access the API directly. (Thanks to Aaron Parecki for the tip.)
= 0.2 =
* Added the option to import bookmarks as links.
* Added privacy settings. Delicious links that were marked as private will be imported as private posts or invisible links.
= 0.1 =
* <a href="http://memografia.com/wordpress/delicious-to-wordpress-importer/">Guillermo Moreno</a>'s original importer.
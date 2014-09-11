=== Wordpress Zootool ===
Contributors: Johannes Lauter
Donate link: http://www.lautr.com/wordpress-zootool
Tags: widget, badge, zootool
Requires: 2.5
Tested: 3
Stable tag: trunk

This Plugin provides a Zootool Badge in form of a Wordpress Widget

== Description ==

This Plugin provides a Zootool Badge in form of a Wordpress Widget, "Zootool is about collecting, organizing and sharing your favorite images, videos, documents and links from all over the internet. " Learn more on <a href="http://zootool.com/">zootool.com</a>. To use this Plugin you'll need a zootool-Account. Just Configure the Widget the way you want and you're good to go. If your Theme does <strong>not</strong> support widgets just place the code `<?= wp_zootool::widgetContent() ?>` in your sidebar template. This Plugin is written and maintained by <a href="http://www.lautr.com">Johannes Lauter</a>

== Installation ==

Upload the Directory 'wp-zootool' directory in your Plugin directory. Fill Out the necessary Options in the back-end and thats all.

= For those with Sidebar Widget compatible themes =

Simply at the Widget in your Design > Widget Menu.

= For those without Sidebar Widget =

Open your themes' `sidebar.php` file if you have one and add `<?= wp_zootool::widgetContent() ?>`


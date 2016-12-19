=== No Category Base (WPML) ===
Contributors: Marios Alexandrou
Donate Link: http://infolific.com/technology/software-worth-using/no-category-base-for-wordpress/
Tags: category base, category parents, category slug, categories, category, url structure, permalinks, wpml
Requires at least: 4.0
Tested up to: 4.7
License: GPLv2 or later

This plugin removes the mandatory 'Category Base' from your category permalinks. It's compatible with WPML.

== Description ==

As the name suggests this plugin will completely remove the mandatory 'Category Base' from your category permalinks ( e.g. 'mysite.com/category/my-category/' to 'mysite.com/my-category/' ).

The plugin requires no setup or modifying core wordpress files and will not break any links. It will also take care of redirecting your old category links to the new ones.

= Features =

1. Better and logical permalinks like 'mysite.com/my-category/' and 'mysite.com/my-category/my-post/'.
2. Simple plugin - barely adds any overhead.
3. Works out of the box - no setup needed.
4. No need to modify WordPress files.
5. Doesn't require other plugins to work.
6. Compatible with sitemap plugins.
7. Compatible with WPML.
8. Works with multiple sub-categories.
9. Works with WordPress Multisite.
10. Redirects old category permalinks to the new ones (301 redirect, good for SEO).

== Installation ==

1. Upload the no-category-base-wpml folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access your categories via mysite.com/my-category/

== Frequently Asked Questions ==

= Why should I use this plugin? =

Use this plugin if you want to get rid of WordPress' "Category base" completely. The normal behavior of WordPress is to add '/category' to your category permalinks if you leave "Category base" blank in the Permalink settings. So your category links look like 'mysite.com/category/my-category/'. With this plugin your category links will look like 'mysite.com/my-category/' (or 'mysite.com/my-category/sub-category/' in case of sub categories).

= Will it break any other plugins? =

As far as I can tell, no. I have been using this on several site for a while and it doesn't break anything.

= Won't this conflict with pages? =

Simply don't have a page and category with the same slug. Even if they do have the same slug it won't break anything, just the category will get priority (Say if a category and page are both 'xyz' then 'mysite.com/xyz/' will give you the category). This can have an useful side-effect. Suppose you have a category 'news', you can add a page 'news' which will show up in the page navigation but will show the 'news' category.

= Do I need WPML to use it? =

No, you can use this plugin without having WPML installed.

= Can you add a feature X? =

Just ask on the support forum. If its useful enough and I have time for it, sure.

= I get 404 errors when I deactivate the plugin. What can I do? =

When you deactivate the plugin, you need to tell WordPress to refresh its permalink rules. This is easy to do.

Go to Settings -> Permalinks and then click on Save Changes.

== Screenshots ==

1. Look Ma, No Category Base!

== Changelog ==

= 1.3 =
* Bug fix provided by Albin.

= 1.2 =
* Plugin transferred to Marios Alexandrou. Support and development resumed.
* Confirmed compatibility with WordPress 4.4.2.

= 1.1.5 =
* Added support for custom pagination_base.

= 1.1.0 =
* Fixed compatibility for WordPress 3.4.

= 1.0.0 =
* Initial release.

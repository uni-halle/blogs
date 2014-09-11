=== Toggle The Title ===
Contributors: avner.komarow
Donate link: 
Tags: title, hide, remove, simple, wp, titles, toggle
Requires at least: 3.0
Tested up to: 3.6.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin that will allow to actually remove (not just hide) page titles per page.

== Description ==

A plugin that will allow removing page titles per page. Only intended for pages (IE: no posts / other custom post types).

Note: this plugin dose not simply hiding the title via css, it actually removes it which is much better seo wise.

== Installation ==

1. Upload and extract ` toggle-the-title.zip` to the `/wp-content/plugins/ toggle-the-title` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. go and edit any page you like - and you will find a check box (default is showing the title)
4. You can enable auto-save in the setting menu (IE: toggling the title will automatically save the page)

== Frequently asked questions ==

= menu items disappeared when toggling =
update to 1.2 or higher.

= it does not remove the title =

you need to use the_title() or  wp_title() or $post_id->post_title and so on just as long that you are getting the title via wp internal functions 

= i would like to totally remove the <h1> from the dom =

You could do something like this:

1) Add this function to your functions.php file on you current theme

'function return_page_title_html_block($post) {
	$is_page_title_active = get_post_meta($post->ID, $key = 'toggle_page_title', $single = true);
	if($is_page_title_active == '' || $is_page_title_active) return '<h1 class="entry-title">' . $post->post_title . '</h1>';
	return '<span class="entry-title">&nbsp;</span>';
}'

(without the tick marks of course)

2) In your page.php file replace the title with the h1 tags to a call to echoing out the above function - print return_page_title_html_block($post);

== Screenshots ==

1. The page edit screen.
2. The options screen.

== Changelog ==
= 1.4 =
* fixed Warning: \"Missing argument 2 for TitleToggler_hide_title()\" http://wordpress.org/support/topic/hello-great-plugin-but-im-getting-an-error-msg-below

= 1.3 =
* added a new feature to turn off all page titles

= 1.2 =
* fixed menu items disappeared when toggling the title

= 1.1 =
* fixed link to the \"developer web site\" (thanks Charles Anders for pointing it out)
* fixed plugin conflict with Q and A FAQ (thanks murokoma - http://wordpress.org/support/topic/not-seeing-questions-plugin-conflict-with-toggle-the-title)

= 1.0 =
-- Init --

== Upgrade Notice ==
= 1.4 =
* fixed Warning: \"Missing argument 2 for TitleToggler_hide_title()\" http://wordpress.org/support/topic/hello-great-plugin-but-im-getting-an-error-msg-below
this issue was reported by many users...

== Arbitrary section ==
you can contact me at avner.komarow@gmail.com	
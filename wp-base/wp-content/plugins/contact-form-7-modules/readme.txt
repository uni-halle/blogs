=== Contact Form 7 Modules ===
Tags: Contact Form 7, form, forms, contactform7, contact form, hidden fields, hidden, cf7, cforms ii, cforms, Contact Forms 7, Contact Forms, contacted, contacts
Requires at least: 2.8
Tested up to: 3.8.1
Stable tag: trunk
Contributors: katzwebdesign, katzwebservices
Donate link: https://katz.co/contact-form-7-hidden-fields/

Contact Form 7 - Add useful modules such as hidden fields and "send all fields" to the Contact Form 7 plugin

== Description ==

### Add Hidden Fields to Contact Form 7

The Contact Form 7 plugin has over <em>8.4 million</em> downloads, yet the great plugin still lacks a simple feature: <strong>hidden fields</strong>. This plugin adds hidden fields to Contact Form 7 once and for all.

#### Inserting dynamic values

You can also choose to have the value of the hidden field dynamically populated in your form when you are contacted. To do so, choose the "Default value" to be:

* `post_title` - Inserts the title of the post/page
* `post_category` - The categories of the post or page
* `post_url` - The URL of the post or page
* `post_author` - The author of the post or page
* `custom_field-[Name]` - The value of a post or page's custom field. If you had a custom field "Foo", you would use the following as the hidden field value: `custom_field-Foo`

The following values will be replaced if an user is logged in:

* `user_name`: User Login
* `user_id`: User ID
* `user_email`: User Email Address
* `user_display_name`: Display Name (Generally the first and last name of the user)
* `user_url`: User Website

And you can also use it for user custom meta data using the format of `user-{field}`:

* `user-aim`: AIM
* `user-jabber`: Jabber / Google Talk
* `user-description`: User Bio

<strong>You can also use a filter:</strong> hook into the `wpcf7_hidden_field_value` filter to modify the value of the hidden field  using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>. If you know the ID of the input, you can also use the `wpcf7_hidden_field_value_[#ID]` filter.

Now, when someone contacts you using your Contact Form 7 contact form, you can have lots more information about their visit - and you'll see it when you receive the email that tells you you've been contacted.

### Easily Send All Submitted Fields At Once

####Save time setting up your form emails...and never miss a field!

One of the limitations of Contact Form 7 is that you need to manually add each field to generated emails. This means that if you update the form with a new field and forget to add it to your email message, you won't receive it in your email. <strong>No longer.</strong>.

Using the <strong>Send All Fields</strong> module, you simply need to add `[all-fields]` to your message, and you will receive every field submitted. If you use HTML formatting, the formatting even looks nice.

<h4>Visit the official <a href="https://katz.co/contact-form-7-hidden-fields/">Contact Form 7 Modules plugin page</a> for more support & additional information</h4>

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
1. Activate the plugin
1. Edit a form in Contact Form 7
1. Choose "Hidden field" from the Generate Tag dropdown
1. Follow the instructions on the page

== Screenshots ==

1. The new Hidden fields tag generator

== Frequently Asked Questions ==

= How do I turn off formatting the key in the `[all-fields]` output? =
Add the following to your theme's `functions.php` file:

`
add_filter('wpcf7_send_all_fields_format_key', '__return_false');
`

= How do I set non-standard user data as hidden field values? =

Starting with Version 1.4, you can access user data, including meta data.

You need to set the default value as: `user-{meta_key}` where `{meta_key}` is the key of the meta field you want the value of.

To get the values of WordPress default profile fields, for example, you would use:

* `user-aim` - AOL
* `user-jabber` - Jabber / Google Talk
* `user-description` - Biographical description

= What is the plugin license? =

* This plugin is released under a GPL license.

= Is the plugin available in other languages? =

Not yet, so [help translate the plugin!](https://www.transifex.com/projects/p/contact-form-7-modules/)

= How do I send empty values with the `[all-fields]` shortcode? =

Add this to your `functions.php` file: `add_filter('wpcf7_send_all_fields_send_empty_fields', '__return_true');`

= How do I modify the output of the `[all-fields]` shortcode? =

* `wpcf7_send_all_fields_format_before` - Before the loop of fields (`<dl>` for HTML output)
	* `$value` _string_ Previous output
	* `$format` _string_ Either "html" or "text"
* `wpcf7_send_all_fields_format_item` - Change each item output. Passes four arguments:
	* `$value` _string_ Previous output
	* `$k` _string_ Field label
	* `$v` _string_ Value of the field
	* `$format` _string_ Either "html" or "text"
* `wpcf7_send_all_fields_format_after` - After the loop of fields (`</dl>` for HTML output). Passes two arguments:
	* `$value` _string_ Previous output
	* `$format` _string_ Either "html" or "text"

== Changelog ==

= 1.4.2 on March 25, 2014 =
* Added: `[all-fields]` shortcode now skips sending data for empty fields
	* Added `wpcf7_send_all_fields_send_empty_fields` filter to override the setting. See the FAQ.
* Added: `[all-fields]` shortcode output filters (see the FAQ item "How do I modify the output...")
	* `wpcf7_send_all_fields_format_before`
	* `wpcf7_send_all_fields_format_item`
	* `wpcf7_send_all_fields_format_after`

= 1.4 & 1.4.1 on March 15, 2014 =
* Added: Internationalization support. [Help translate the plugin!](https://www.transifex.com/projects/p/contact-form-7-modules/)

__The below updates apply only to the Hidden Fields module.__

* Added: Support for retrieving other user data by using the field name `user_{data you want}`. See the FAQ "How do I set non-standard user data as hidden field values?"
* Added: `wpcf7_hidden_field_implode_glue` filter. If you want to modify how arrays of data get combined into a string (default is CSV), use this filter.
* Fixed: `$post` global no longer needs to be defined for user data to be successfully passed.
* Fixed: Now supports multiple post `custom_field` data values, instead of only fetching one
* Modified: Added callback function to format the hidden field instead of relying on depricated PHP
* Modified: Improved include path for `functions.php` file
* Modified: Added text to support additional localization

= 1.3.3 =
* Hidden Fields: Fixed issue that broke the plugin with WordPress 3.8.

= 1.3.2 =
* Hidden Fields: Fixed PHP notice caused by improper adding of script in administration
* Hidden Fields: Fixed double inputs that were the exact same (<a href="http://wordpress.org/support/topic/render-the-fields-twice">as reported here</a>)

= 1.3.1 =
* Fixed: issue in Hidden Fields where the `[hidden-###]` shortcode no longer worked and only `[post_title]` format worked.
    * Added: Hidden fields now support both formats: `[hidden-123]` and `[post_title]` as long as they're in the form itself.
* Fixed: issue in Send All Fields where the <a href="http://wordpress.org/support/topic/post_title-hidden-field-no-longer-working#post-3708463">HTML was showing as text</a>.
* Added `wpcf7_send_all_fields_format_key` filter to Send All Fields plugin to turn on or off formatting of the key (replacing `example-key` with `Example Key` in output). See "How do I turn off formatting the key in the `[all-fields]` output?" in the FAQ.

= 1.3 =
* Fixed: Hidden field now supports new Contact Form 7 format; post fields will work again.
* Fixed: Send All Fields no longer causes spinning form submission in WordPress 3.5
* Added: access any of the <a href="http://www.rlmseo.com/blog/wordpress-post-variable-quick-reference/" rel='nofollow'>data in `$post` object</a> by using the variable name. Example: You want `post_modified`? Use `[hidden hidden-123 "post_modified"]`
* Added: If an user is logged in, you can now use `user_name`, `user_id`, `user_email`, `user_display_name` replacement values
* Added/Improved: `post_author` will now return the author's Display Name. Use `post_author_id` for the post author's ID.
* Added: Inline instructions on the Hidden field module
* Improved: In Send All Fields, the name of the field now has dashes replaced with spaces. This will show "your name", rather than "your-name". Thanks, <a href="http://wordpress.org/support/topic/sending-all-fields-with-content-code-provided">@hitolonen</a>

= 1.2.2 =
* Removed `_wpnonce` field from `[all-fields]` output
* Fixed a conflict when using "Send All Fields" module alongside "Hidden Fields" module (<a href="http://wordpress.org/support/topic/plugin-contact-form-7-modules-all-fields-doesn´t-work-wit-wordpress-33">as reported here</a>)

= 1.2.1 =
* Added support for checkboxes with Send All Fields (`[all-fields]`)

= 1.2 =
* Hidden fields are now displayed inside a hidden `<div>` instead of Contact Form 7's default `<p>`. This makes hidden fields more hidden :-)
* Added brand-new module: Send All Fields. Allows you to add a `[all-fields]` tag to your email message that includes every submitted field in one tag.

= 1.1.1 =
* Fixed `Parameter 1 to wpcf7_add_tag_generator_hidden() expected to be a reference, value given` error, <a href="http://www.seodenver.com/contact-form-7-hidden-fields/#comment-116384456"> as reported by BDN Online</a>

= 1.1 =
* Added support for using post titles as hidden fields
* Added support for using custom field values as hidden fields
* Added `wpcf7_hidden_field_value` filter to hook into using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>

= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 1.4.2 on March 25, 2014 =
* Added: `[all-fields]` shortcode now skips sending data for empty fields
	* Added `wpcf7_send_all_fields_send_empty_fields` filter to override the setting. See the FAQ.
* Added: `[all-fields]` shortcode output filters (see the FAQ item "How do I modify the output...")
	* `wpcf7_send_all_fields_format_before`
	* `wpcf7_send_all_fields_format_item`
	* `wpcf7_send_all_fields_format_after`

= 1.4 & 1.4.1 on March 15, 2014 =
* Added: Internationalization support. [Help translate the plugin!](https://www.transifex.com/projects/p/contact-form-7-modules/)

__The below updates apply only to the Hidden Fields module.__

* Added: Support for retrieving other user data by using the field name `user_{data you want}`. See the FAQ "How do I set non-standard user data as hidden field values?"
* Added: `wpcf7_hidden_field_implode_glue` filter. If you want to modify how arrays of data get combined into a string (default is CSV), use this filter.
* Fixed: `$post` global no longer needs to be defined for user data to be successfully passed.
* Fixed: Now supports multiple post `custom_field` data values, instead of only fetching one
* Modified: Added callback function to format the hidden field instead of relying on depricated PHP
* Modified: Improved include path for `functions.php` file
* Modified: Added text to support additional localization

= 1.3.3 =
* Hidden Fields: Fixed issue that broke the plugin with the WordPress 3.8

= 1.3.2 =
* Hidden Fields: Fixed PHP notice caused by improper adding of script in administration
* Hidden Fields: Fixed double inputs that were the exact same (<a href="http://wordpress.org/support/topic/render-the-fields-twice">as reported here</a>)

= 1.3.1 =
* Fixed: issue in Hidden Fields where the `[hidden-###]` shortcode no longer worked and only `[post_title]` format worked.
* Fixed: issue in Send All Fields where the <a href="http://wordpress.org/support/topic/post_title-hidden-field-no-longer-working#post-3708463">HTML was showing as text</a>.

= 1.3 =
* Fixed: Hidden field now supports new Contact Form 7 format; post fields will work again.
* Fixed: Send All Fields no longer causes spinning form submission in WordPress 3.5
* Added: access any of the <a href="http://www.rlmseo.com/blog/wordpress-post-variable-quick-reference/" rel='nofollow'>data in `$post` object</a> by using the variable name. Example: You want `post_modified`? Use `[hidden hidden-123 "post_modified"]`
* Added: If an user is logged in, you can now use `user_name`, `user_id`, `user_email`, `user_display_name` replacement values
* Added/Improved: `post_author` will now return the author's Display Name. Use `post_author_id` for the post author's ID.
* Added: Inline instructions on the Hidden field module
* Improved: In Send All Fields, the name of the field now has dashes replaced with spaces. This will show "your name", rather than "your-name". Thanks, <a href="http://wordpress.org/support/topic/sending-all-fields-with-content-code-provided">@hitolonen</a>

= 1.2.2 =
* Removed `_wpnonce` field from `[all-fields]` output
* Fixed a conflict when using "Send All Fields" module alongside "Hidden Fields" module (<a href="http://wordpress.org/support/topic/plugin-contact-form-7-modules-all-fields-doesn´t-work-wit-wordpress-33">as reported here</a>)

= 1.2.1 =
* Added support for checkboxes with Send All Fields (`[all-fields]`)

= 1.2 =
* Hidden fields are now displayed inside a hidden `<div>` instead of Contact Form 7's default `<p>`. This makes hidden fields more hidden :-)
* Added brand-new module: Send All Fields. Allows you to add a `[all-fields]` tag to your email message that includes every submitted field in one tag.

= 1.1.1 =
* Fixed `Parameter 1 to wpcf7_add_tag_generator_hidden() expected to be a reference, value given` error, <a href="http://www.seodenver.com/contact-form-7-hidden-fields/#comment-116384456"> as reported by BDN Online</a>

= 1.1 =
* Added support for using post titles as hidden fields
* Added support for using custom field values as hidden fields
* Added `wpcf7_hidden_field_value` filter to hook into using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>

= 1.0 =
* Woot!
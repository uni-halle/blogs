=== WP Evernote Site Memory ===
Plugin Name: WP Evernote Site Memory
Plugin URI: http://slocumstudio.com/2010/09/evernote-site-memory-plugin-for-wordpress/
Contributors: desrosj,slocumstudio,jeffgolenski
Tags: evernote,site memory,clipping
Requires at least: 2.7
Tested up to: 3.5
Stable tag: 2.0.2

WP Evernote Site Memory fully integrates Evernote's Site Memory feature into your Wordpress blog.

== Description ==

<a href="http://www.evernote.com/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=plugin%2Bpage&utm_campaign=WP%2BEvernote%2BSite%2BMemory" target="_blank">Evernote</a> makes it easy to remember things big and small from your notable life using your computer, phone, and the web.

One of the best ways to build a loyal following for your site is to give visitors the ability to remember the pages and posts that they love. With the <a href="http://www.evernote.com/about/developer/sitememory/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=readme%2Bfile&utm_campaign=WP%2BEvernote%2BSite%2BMemory" target="_blank">Evernote Site Memory</a> button adding this functionality to your site could not be simpler.

WP Evernote Site Memory integrates Evernote's site memory feature into your Wordpress blog, placing your fully customizable site memory button at the end of every page and post.

= Links =
 * <a href="http://www.slocumstudio.com/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=plugin&utm_campaign=Important%2BLinks" target="_blank">Slocum Design Studio</a>
 * <a href="http://www.slocumstudio.com/2010/09/evernote-site-memory-plugin-for-wordpress/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=plugin&utm_campaign=Important%2BLinks" target="_blank">Plugin Release Blog Post</a>

= Features =
WP Evernote Site Memory gives you the ability to customize the following options:

 * Provider's Name
 * Title Format
 * Suggested Notebook
 * Content ID to be clipped
 * Evernote Affiliate Code
 * Button Style
 * Clip Style
 * Note Signature
 * Note Header
 * Note Footer
 * Normal or Minified JavaScript

== Credits ==

Copyright 2010 by <a href="http://www.slocumstudio.com/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=plugin%2Bpage&utm_campaign=WP%2BEvernote%2BSite%2BMemory" target="_blank">Slocum Design Studio</a> & <a href="http://www.jonathandesrosiers.com/?utm_source=Wordpress%2BPlugin%2BDirectory&utm_medium=plugin%2Bpage&utm_campaign=WP%2BEvernote%2BSite%2BMemory" target="_blank">Jonathan Desrosiers</a>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

= Disclaimer =

We are note affiliated with Evernote in any way, other than being users who enjoy the service that they provide.

Evernote Corporation owns the following trademarks and service marks, which may be registered trademarks in the United States and other countries.

 * EVERNOTE
 * The Evernote Elephant logo

Please note that Evernote Corporation may also own other trademarks, service marks or logos and their absence from this list does not constitute a waiver of any intellectual property or other rights that Evernote Corporation may have in those marks.


== Installation ==

1. Download and unzip the WP Evernote Site Plugin
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. If desired, customize the settings in the Settings->Site Memory section

You can also further customize the button with css styles.
The button is 3 components, a &lt;div&gt;, an &lt;a&gt;, and an &lt;img&gt;.
They are assigned the CSS classes evernoteSiteMemory,  evernoteSiteMemoryLink, and  evernoteSiteMemoryButton respectively.

== Frequently Asked Questions ==

= What is Evernote? =

Evernote makes it easy to remember things big and small from your notable life using your computer, phone, and the web.

One of the best ways to build a loyal following for your site is to give visitors the ability to remember the pages and posts that they love. With the Evernote Site Memory button adding this functionality to your site could not be simpler.

To learn more about Evernote, <a href="http://www.evernote.com/" target="_blank">visit their website</a>.

= When I click the button, nothing happens. =

In order for the necessary files to be included for the button to work, you need to call wp_footer. Just insert <code><?php wp_footer(); ?></code> just before the closing &lt;body&gt; tag

= The entire page is being clipped. What gives? =

By default, WP Evernote Site Memory uses the post-## ID to specify the content to be clipped. However, if your theme does not utilize this scheme, you will have to specify the ID you wish to use instead in the admin panel. Remember, ID's are case sensitive.

= Can I style the button? =

The button itself is an image. Therefore certain aspects of it can not be changed. However the Site Memory button is composed of three HTML elements that will allow you to further customize the button with css styles.
The 3 components are a &lt;div&gt;, an &lt;a&gt;, and an &lt;img&gt;.
They are assigned the CSS classes evernoteSiteMemory,  evernoteSiteMemoryLink, and  evernoteSiteMemoryButton respectively.

= Where are the suggested tags coming from? =

The tags that are suggested to the user clipping your post are the tags assigned to it in Wordpress. So to suggest tags to the user, just add tags to that post.

= There is no admin menu to edit the settings =

If you do not see a "Site Memory" admin menu in the settings section of the Wordpress backend, you are most likely using an outdated version of Wordpress. The administration features are only available on versions < 2.7.

== Screenshots ==

1. Site Memory button at the base of a post.
2. Site Memory button at the base of a page.
3. WP Evernote Site Memory administration page.
4. WP Evernote Site Memory administration page.
5. WP Evernote Site Memory administration page.

== Changelog ==
= 1.1.1 =
 * Fixed custom ID issue
 * Fixed a line break tag issue that was occurring in some situations

= 1.1 =
 * New Button Styles
 * Included the JavaScript files locally to avoid IE XSS Filter
 * Added option to have the button before or after the post

= 1.0.5 =
 * Fixed CSS errors

= 1.0.3 =
 * Fixed more special character issues

= 1.0.2 =
 * Fixed issue with escaping special characters such as &#039;, &quot;, and &amp;.
 * Removed a default for Suggested Notebook as Evernote does this automatically.
 * Minor change to IMG tag to remove HTML warning.
 * Updated FAQs.

= 1.0.1 =
 * Added additional button styles.

= 1.0 =
* Initial Release
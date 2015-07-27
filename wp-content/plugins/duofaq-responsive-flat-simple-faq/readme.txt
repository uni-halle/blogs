=== Best WordPress FAQ ===
Contributors: duogeek
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VZB6MW3L4F526
Tags: faq, faqs, frequently asked questions, wordpress faq, faq wordpress, faq for wordpress, faq plugin
Requires at least: 3.5
Tested up to: 4.3
Stable tag: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best and most simple plugin for creating a "Frequently Asked Questions - FAQ section" on your WordPress website.

== Description ==

You sell products or services on your website? Then your customers have lots of common question that you have to answer everyday. So for showing the answers of those repeatedly asked questions in a very smart and beautiful way, you can use duoFAQ.

duoFAQ is a very simple and beautiful but powerful plugin for creating FAQ on your WordPress website. duoFAQ adds FAQ support to your WordPress site in a way that you will feel like it's one of the native features of WordPress. It integrates with all themes and styles.

<blockquote><p><a href="https://duogeek.com/products/add-ons/">Get more themes</a></p></blockquote>

When you install duoFAQ, you will get a "FAQ" menu at the left menubar of your WordPress Dashboard. From there, you can add questions and their respective answers very easily; like you create a regular post in WordPress.

In duoFAQ, you can use all native styles of jQuery Accordion. And moreover, we have included 18 flat themes so that they can match your website design.

So, if you still don't have FAQ section on your site yet, install duoFAQ now and serve 50% of your customers without even talking to them.

For detailed instruction and video tutorial, please visit the <a href="https://duogeek.com/products/plugins/duofaq-responsive-flat-simple-faq/">plugin page</a>.

For any issues, problem or query, please feel free to <a href="https://duogeek.com/support/forum/duogeek-products/">contact us for support</a>.



**Features**

----------------------------------------------------------------------

* Native jQuery Accordion
* Full support from jQuery official documentation
* Fully Responsive
* Custom 18 Flat Themes of 18 Flat Colors Pre-Installed
* Developed following WordPress coding standard
* Native post editor for adding questions and answers
* Native-alike Category for questions


**Bonus Features**

----------------------------------------------------------------------

* We provide insane 24 X 7 support worldwide. Try emailing us on Christmas eve or on Saint Patrick's Day
* Subscribe to Plugin Website and you will get discount on all premium plugins
* Rich Blog with lots of solutions of different WordPress related problems on Plugin Website



**Coming Soon**

----------------------------------------------------------------------

* More awesome themes
* More awesome designs




**About Developer - duogeek**

----------------------------------------------------------------------

"Duogeek" is the wordpress development powerhouse. If you have any kind of query, feedback, suggestion or customization request, email us here : support@duogeek.com


== Installation ==

**Way 1:**<br>
1. Download the plugin file<br>
2. Unzip the file into a folder on your hard drive<br>
3. Upload via FTP into your plugins (/wp-content/plugins/) folder<br>
4. Activate the plugin from your dashboard<br>

**Way 2:**<br>
1. From Dashboard > Plugins > Add New > Upload the plugin<br>
2. Activate the plugin<br>

**How to use**<br>
1. Use shortcode: [duo_faq]<br>
2. Use shortcode: [duo_faq category="CATEGORY_ID"]<br>
3. Use in template or php file: `<?php echo do_shortcode('[duo_faq]'); ?>`<br>
4. Use as widget from Appearances > Widgets<br>


== Screenshots ==
1. How you should create a question
2. The FAQ settings page


== Changelog ==
= 1.4.3 =
* Enhancement

= 1.4.1 =
* Fixed: Warning in Category Page
* Fixed: SSL Warning
* Added: Option to change the FAQ menu name
* Example:
add_filter( 'duo_change_faq_menu_name', 'duo_change_faq_menu_name_cb' );
function duo_change_faq_menu_name_cb( $menu ){
	return "New Menu Name instead of FAQ";
}

= 1.4.0 =
* Added: Sorting feature for questions
* Added: Sorting feature for categories
* Improved: Performance

= 1.3.7 =
* Constants added to provide more flexibility
* Multiple categories can be grouped like [duo_faq category="2,5"]

= 1.3.6 =
* Minor Fixes

= 1.3.5 =
* Notices fixes

= 1.3.4 =
* Fixed: Style issue
* Fixed: Title was not hiding in category shortcode view
* Modification: Not dependent on bootstrap anymore
* Added: Control font size of question
* Added: Control font size of FAQ Category label
* Added: Faq theme uploader
* Added: External Theme support

= 1.3.3 =
* Minor fixes

= 1.3.2 =
* js error fixed in admin

= 1.3.1 =
* php 5.3 compatible (solvd fatal error)

= 1.3 =
* "on" removed when category title is hidden in shortcode
* Option added

= 1.2 =
* Style issue fixed

= 1.1 =
* php 5.2 compatible

= 1.0 =
* Initial release
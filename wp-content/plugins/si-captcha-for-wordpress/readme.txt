=== SI CAPTCHA Anti-Spam ===
Contributors: fastsecure
Author URI: http://www.642weather.com/weather/scripts.php
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KXJWLPPWZG83S
Tags: captcha, recaptcha, buddypress, bbpress, woocommerce, wpforo, multisite, jetpack, comment, comments, login, register, anti-spam, spam, security
Requires at least: 3.6.0
Tested up to: 4.8
Stable tag: 3.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds Secure Image CAPTCHA on the forms for comments, login, registration, lost password, BuddyPress, bbPress, wpForo, and WooCommerce checkout.

== Description ==

Adds Secure Image CAPTCHA anti-spam to WordPress pages for comments, login, registration, lost password, BuddyPress register, bbPress register, wpForo register, bbPress New Topic and Reply to Topic Forms, Jetpack Contact Form, and WooCommerce checkout.
In order to post comments, login, or register, users will have to pass the CAPTCHA test. This prevents spam from automated bots, adds security, and is even compatible Akismet. Compatible with Multisite Network Activate. 
If you don't like image captcha and code entry, you can uninstall this plugin and try my new plugin [Fast Secure reCAPTCHA](https://wordpress.org/plugins/fast-secure-recaptcha/) 

= Help Keep This Plugin Free =

If you find this plugin useful to you, please consider [__making a small donation__](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KXJWLPPWZG83S) to help contribute to my time invested and to further development. Thanks for your kind support! - [__Mike Challis__](http://profiles.wordpress.org/users/MikeChallis/)


Features:
--------
 * Secure Image CAPTCHA.
 * Optional setting to hide the Comments CAPTCHA from logged in users.
 * Enable or disable the CAPTCHA on any of the pages for comments, login, registration, lost password, BuddyPress register, bbPress register, wpForo Register, Jetpack Contact Form, and WooCommerce checkout.
 * Login form - WordPress, BuddyPress, bbPress, wpForo Forum, WooCommerce, WP Multisite
 * Lost Password form - WordPress, BuddyPress, bbPress, wpForo Forum, WooCommerce, WP Multisite. 
 * Register form - WordPress, BuddyPress, bbPress, wpForo Forum, WooCommerce, WP Multisite.
 * Comment form - WordPress, WP Multisite.  
 * Signup new site - WP Multisite.
 * Checkout form - WooCommerce.
 * Jetpack Contact Form.
 * bbPress New Topic, Reply to Topic Forms.
 * You can disable any of the forms you don't want CAPTCHA on.
 * Style however you need with CSS.
 * I18n language translation support.
 * Compatible with Akismet.
 * Compatible with Multisite Network Activate.
 * I18n language translation support. [See FAQ](http://wordpress.org/extend/plugins/si-captcha-for-wordpress/faq/).

Captcha Image Support:
---------------------
 * Open-source free PHP CAPTCHA library by www.phpcaptcha.org is included (customized version)
 * Abstract background with multi colored, angled, and transparent text
 * Arched lines through text
 * Refresh button to reload captcha if you cannot read it.


== Installation ==

= How to Install on WordPress =

1. Install automatically through the `Plugins`, `Add New` menu in WordPress, find in the Plugins directory, click Install, or upload the `si-captcha-for-wordpress.zip` file.

2. Activate the plugin through the `Plugins` menu in WordPress.

3. Configure on the settings page, be sure to select all the forms you want to protect.

4. Updates are automatic. Click on "Upgrade Automatically" if prompted from the admin menu. If you ever have to manually upgrade, simply deactivate, uninstall, and repeat the installation steps with the new version. 

= How to install on WordPress Multisite with Network Activate and Main site control of the settings =

1. Install the plugin from Network Admin menu then click Network Activate.

2. Go to the Main site dashboard and click on settings for this new plugin. 

3. Configure on the settings page, be sure to select all the forms you want to protect. All the settings configured here will be applied to all the sites. Other site admins cannot see or change the settings.


= How to install on WordPress Multisite with Network Activate and individual site control of the settings =

1. Install the plugin from Network Admin menu then click Network Activate.

2. Go to the Main site dashboard and click on settings for this new plugin. Configure on the settings page, be sure to select all the forms you want to protect. 

3. Check the setting: "Allow Multisite network activated sites to have individual SI CAPTCHA settings." Now each site admin can configure the settings on their dashboard SI CAPTCHA settings page, and be sure to select all the forms to protect.


== Screenshots ==

1. screenshot-1.png is the captcha on the comment form.

2. screenshot-2.png is the captcha on the registration form.

3. screenshot-3.png is the `Captcha options` tab on the `Admin Plugins` page.


== Configuration ==

After the plugin is activated, you can configure it by selecting the `SI Captcha options` tab on the `Admin Plugins` page.


== Usage ==

Once activated, a captcha image and captcha code entry is added to the comment and register forms. The Login form captcha is not enabled by default because it might be annoying to users. Only enable it if you are having spam problems related to bots automatically logging in.


== Frequently Asked Questions ==

= How does it work? =

Users users will have to pass the Secure Image CAPTCHA test. They are shown an image with a code, then they have to enter the code in the form field before the click submit. If the code does not match, the form will return "Incorrect CAPTCHA".

= What are spammers doing anyway? =

= Human spammers = 
They actually visit your form and fill it out including the CAPTCHA.

= Human or Spambot probes =
Sometimes contain content that does not make any sense (jibberish). Humans or Spam bots will try to target any forms that they discover. They first attempt an email header injection attack to use your web form to relay spam emails. This form is to prevent relaying email to other addresses. After failing that, they simply submit the form with a spammy URL or black hat SEO text with embedded HTML, hoping someone will be phished or click the link.

= Blackhat SEO spammers = 
Spamming blog comment forms, contact forms, Wikis, etc. By using randomly generated unique "words", they can then do a Google search to find websites where their content has been posted un-moderated. Then they can go back to these websites, identify if the links have been posted without the rel="nofollow" attribute (which would prevent them contributing to Google's algorithm), and if not they can post whatever spam links they like on those websites, in an effort to boost Google rankings for certain sites. Or worse, use it to post whatever content they want onto those websites, even embedded malware.

= Human-powered CAPTCHA solvers =
It is easy and cheap for someone to hire a person to enter this spam. Usually it can be done for about $0.75 for 1,000 or so form submissions. The spammer gives their employee a list of sites and what to paste in and they go at it. Not all of your spam (and other trash) will be computer generated - using CAPTCHA proxy or farm the bad guys can have real people spamming you. A CAPTCHA farm has many cheap laborers (India, far east, etc) solving them. CAPTCHA proxy is when they use a bot to fetch and serve your image to users of other sites, e.g. porn, games, etc. After the CAPTCHA is solved, they post spam to your form.

= Spammers have been able to bypass my CAPTCHA, what can I do? =

Make sure you have configured the settings page and enabled the CAPTCHA on all your forms.

The CAPTCHA will not show to logged in users posting comments if you have enabled this setting: 'No comment form CAPTCHA for logged in users'. Enable this setting if a logged in user is the spammer.

Check for a plugin conflict.
A plugin conflict can break the validation test so that the CAPTCHA is never checked.
Be sure to always test all the comments, login, registration, and lost password CAPTCHA forms after installing or updating themes or plugins. 

Troubleshoot plugin conflicts, see troubleshooting below.

Sometimes your site becomes targeted by a human spammer or a spam bot and human captcha solver. If the issue persists, try the following suggestions:

Try allowing only Registered users to post, and or moderating comments.
Read more about [Combating Comment Spam](http://codex.wordpress.org/Combating_Comment_Spam)

Filter Spam with Akismet - The [Akismet plugin](https://docs.akismet.com/getting-started/activate/) filters spam comments. Akismet should able to block most of or all spam that comes in. 

I made another plugin with Google No CAPTCHA reCAPTCHA that has realtime bot detection. You can uninstall this plugin and try my new plugin [Fast Secure reCAPTCHA](https://wordpress.org/plugins/fast-secure-recaptcha/) 


= How can I change the color of the CAPTCHA input field on the comment form? =
If you need to adjust the captcha input form colors, [See this FAQ](http://www.fastsecurecontactform.com/si-captcha-comment-form-css)


= Troubleshooting the CAPTCHA image or form field does not display, or it does not block the form properly =
Another plugin could be causing a conflict. 
Temporarily deactivate other plugins to see if the CAPTCHA starts working. 

Your theme could be missing the wp_head or wp_footer PHP tag. Your theme should be considered broken if the wp_head or wp_footer PHP tag is missing.

Do this as a test:
In Admin, click on Appearance, Themes. Temporarily activate your theme to one of the default default themes. 
It does not cause any harm to temporarily change the theme, test and then change back. Does it work properly with the default theme?
If it does then the theme you are using is the cause. 

Missing CAPTCHA image and input field on comment form?
You may have a theme that has an improperly coded comments.php

When diagnosing missing CAPTCHA field on comment form....

Make sure your theme has `<?php comment_form(); ?>`
inside `/wp-content/themes/[your_theme]/comments.php`. (look inside the Twenty Ten theme's comments.php for proper example.

Make sure that the theme comments.php file contains at least one or more of the standard hooks: 
`do_action ( 'comment_form_logged_in_after' );`
`do_action ( 'comment_form_after_fields' );` 
`do_action ( 'comment_form' );` 
If you didn't find one of these hooks, then put this string in the comment form: 
`<?php do_action( 'comment_form', $post->ID ); ?>` 

= The CAPTCHA and input field does not display on JetPack comments form =
If you have JetPack comments module enabled then captcha/recaptca/anti-spam plugins will not work on your comments form because the comments are then loaded in an iFrame from WordPress.com The solution is to disable the comments module in JetPack, then the CAPTCHA plugin will work correctly on your comments form.

= Troubleshooting if the CAPTCHA image itself is not being shown on the comment form: =

By default, a logged in user not see the CAPTCHA on the comment form. If you click "log out", go look and it should be there. Make sure you have configured the settings page and enabled the CAPTCHA on the comment form.

If the image is broken and you have the CAPTCHA entry box:

This can happen if a server has folder permission problem, or the WordPress address (URL)
or Blog address (URL) are set incorrectly in WP settings: Admin,  Settings,  General

[See FAQ page on fixing this problem](http://www.fastsecurecontactform.com/captcha-image-not-showing-si-captcha-anti-spam)


= The CAPTCHA refresh button does not work =

Your theme could be missing the wp_footer PHP tag. Your theme should be considered broken if the wp_footer PHP tag is missing.

All WordPress themes should always have `<?php wp_footer(); ?>` PHP tag just before the closing `</body>` tag of your theme's footer.php, or you will break many plugins which generally use this hook to reference JavaScript files. The solution: edit your theme's footer.php and make sure this tag is there. If it is missing, add it. Next, be sure to test that the CAPTCHA refresh button works, if it does not work and you have performed this step correctly, you could have some other cause.


= The CAPTCHA is not working and I cannot login at my login page =
This failure could have been caused by another plugin conflict with this one.
If you use CAPTCHA on the login form and ever get locked out due to CAPTCHA is broken, here is how to get back in:
FTP to your WordPress directory `/wp-content/plugins/`
Delete this folder: 
`si-captcha-for-wordpress`
This manually removes the plugin so you should be able to login again. 


= Is this plugin available in other languages? =

Yes. To use a translated version, you need to obtain or make the language file for it. 
At this point it would be useful to read [Installing WordPress in Your Language](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") from the Codex.
You will need an .mo file for this plugin that corresponds with the "WPLANG" setting in your wp-config.php file.
Translations are listed below -- if a translation for your language is available, all you need to do is place it in the `/wp-content/plugins/si-captcha-for-wordpress/languages` directory of your WordPress installation.
If one is not available, and you also speak good English, please consider doing a translation yourself (see the next question).


The following translations are included:

= Translators =
* Albanian (sq_AL) - Romeo Shuka
* Arabic (ar) - Amine Roukh
* Belorussian (by_BY) - Marcis Gasuns
* Chinese (zh_CN) - Awu
* Czech (cs_CZ) - Radovan
* Danish (da_DK) - Parry
* Dutch (nl_NL) - Robert Jan Lamers
* French (fr_FR) - BONALDI
* German (de_DE) - Sebastian Kreideweiss
* Greek (el) - Ioannis
* Hungarian (hu_HU) - Vil
* Indonesian (id_ID) - Masino Sinaga
* Italian (it_IT) - Gianni Diurno
* Japanese (ja) - Chestnut
* Lithuanian (lt_LT) - Vincent G
* Norwegian (nb_NO) - Roger Sylte
* Polish (pl_PL) - Tomasz
* Portuguese Brazil (pt_BR) - Newton Dan Faoro
* Portuguese Portugal (pt_PT) - PL Monteiro
* Romanian (ro_RO) - Laszlo SZOKE
* Russian (ru_RU) - Urvanov
* Serbian (sr_SR) - Milan Dinic
* Slovakian (sk_SK) - Marek Chochol
* Spanish (en_ES) - zinedine
* Swedish (sv_SE) - Benct
* Traditional Chinese, Taiwan Language (zh_TW) - Cjh
* Turkish (tr_TR) - Burak Yavuz
* More are needed... Please help translate.


= Can I provide a new translation? =

Yes please. 
Please read [How to translate SI Captcha Anti-Spam for WordPress](http://www.fastsecurecontactform.com/translate-si-captcha-anti-spam) 

= Can I update a translation? =

Yes please. 
Please read [How to update a translation of SI Captcha Anti-Spam for WordPress](http://www.fastsecurecontactform.com/update-translation-si-captcha-anti-spam) 


== Changelog ==

= 3.0.2 - 28 July 2017 =
* I'm starting to update all the language files to reflect the new strings. Please submite your localization to let this plugin speak even more languages!
* Fixed a regression bug introduced as a typo during the first round of code clean-up.

= 3.0.1 - 27 July 2017 =
* Can you believe it? This plugin is about to celebrate its 9th birthday! Thank you for helping me make it the successful piece of code it has become over time.
* Initial code clean-up for improved readability. This will help me add new features more quickly.
* I've started experimenting with the new [SecurImage PHP Captcha library](http://www.phpcaptcha.org/) version 3.x, which hopefully will be added to this plugin soon.

= 3.0.0.20 - 20 Jun 2017 =
* Fix readme

= 3.0.0.19 - 05 Jun 2017 =
* Fix duplicate si_captcha_code ID.

= 3.0.0.18 - 05 Jun 2017 =
* Fix possible empty needle error.

= 3.0.0.17 - 13 May 2017 =
* Fix possible Catchable fatal error on WooCommerce password reset.

= 3.0.0.16 - 09 May 2017 =
* Fix typo in code causing validation error on WooCommerce checkout. Sorry for any inconvenience.

= 3.0.0.15 - 04 May 2017 =
* Revert changes to last update to fix missing CAPTCHA on JetPack Contact form.

= 3.0.0.14 - 04 May 2017 =
* Fix rare but possible double CAPTCHA on JetPack Contact form.

= 3.0.0.13 - 02 May 2017 =
* Fix "You have selected an incorrect CAPTCHA value" error on WooCommerce checkout page if "Create an account" is checked and Enable CAPTCHA on WooCommerce checkout is disabled.

= 3.0.0.12 - 21 Apr 2017 =
* Fix "You have selected an incorrect CAPTCHA value" error on WooCommerce checkout page if "Create an account" is checked.

= 3.0.0.11 - 20 Apr 2017 =
* Fix WooCommerce /my-account/lost-password/ page validation error causes cannot click "Reset password".

= 3.0.0.10 - 10 Apr 2017 =
* Fix double CAPTCHA WooCommerce register My Account forms WooCommerce 2.x

= 3.0.0.9 - 10 Apr 2017 =
* Fix CAPTCHA did not work on WooCommerce register My Account forms since WooCommerce 3.

= 3.0.0.8 - 21 Mar 2017 =
* Fixed error caused by uninitialized value si_captcha_login on line 764.

= 3.0.0.7 - 03 Mar 2017 =
* Fixed CAPTCHA not loading on register form on BuddyPress when Extended Profiles is disabled.
* Fixed CAPTCHA not loading on JetPack Contact Form in a widget.

= 3.0.0.6 - 27 Feb 2017 =
* Fixed WooCommerce checkout CAPTCHA was still on the form when not enabled.

= 3.0.0.5 - 25 Feb 2017 =
* Fixed bbPress Register form did not have the CAPTCHA.
* Added support for bbPress New Topic and Reply to Topic Forms.

= 3.0.0.4 - 18 Feb 2017 =
* Added CAPTCHA for Jetpack Contact Form.
* Fix CAPTCHA not showing on Woocommerce /my-account/ page when "My account page" is enabled in Woocommerce settings.
* Fix CAPTCHA missing on comment form on some old themes.
* Improved text on enable forms settings.
* Fix some strings that could not be internationalized.
* Update French (fr_FR) - BONALDI (thank you).
* Update Russian (ru_RU) - Urvanov (thank yuu).

= 3.0.0.3 - 12 Feb 2017 =
* Fixed reCAPTCHA on wpForo Registration page was not working unless comment form was also checked.

= 3.0.0.2 - 12 Feb 2017 =
* Added CAPTCHA for wpForo Forum Registration page. (you can enable/disable it on the settings page).

= 3.0.0.1 - 12 Feb 2017 =
* Removed aria setting.
* Fixed broken links at top of settings page.

= 3.0.0.0 - 12 Feb 2017 =
* All new codebase, this is a major update.
* Make compatible with WooCommerce, BuddyPress, and Multisite Network Activate.
* Test and fix all forms on the most current WordPress.
* Remove some of the difficult style features.
* Remove some obsolete settings.
* If you don't like image captcha and code entry, you can uninstall this plugin and try my new plugin [Fast Secure reCAPTCHA](https://wordpress.org/plugins/fast-secure-recaptcha/) 
* Language files need updating, if you want to help, please read [How to update a translation of SI Captcha Anti-Spam](http://www.fastsecurecontactform.com/update-translation-si-captcha-anti-spam).
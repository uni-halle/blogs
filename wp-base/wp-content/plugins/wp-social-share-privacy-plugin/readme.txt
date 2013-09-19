=== WP-Social-Share-Privacy ===
Contributors: Fabian Künzel
Donate link: http://fkblog.de/wp/plugins/wp-social-share-privacy/
Requires at least: 3.0
Tags: bookmark, bookmarking, bookmarks, button, Facebook, google, links, sharing, social, social media, social bookmarking, social bookmarks, twitter, google plus, google +1, +1, privacy
Tested up to: 3.2.1
Stable tag: 1.1.6

Wordpress-Plugin Umsetzung des jQuery Plug-In socialshareprivacy von heise.de

== Description ==
[German-Translation]
Wordpress-Plugin Umsetzung des socialshareprivacy jQuery Plug-In von heise.de
Das Plugin bietet die Möglichkeit den Facebook-Like, twitter- und Google Plus Button erst durch ein Klick des Besuchers auf die Seite einzubinden um den neuen Datenschutzbestimmungen in Deutschland gerecht zu werden

Weitere Informationen: http://heise.de/-1333879


[English-Translation]
Implementation of the Wordpress plugin socialshareprivacy jQuery plug-in from heise.de
The plugin offers the possibility of the Facebook-Like, Twitter and Google plus button just by a click of the visitor to the site to meet to incorporate the new data protection legislation in Germany

For more information: http://heise.de/-1333879

== Installation ==

1. Upload `socialshareprivacy` folder to your WP plugin folder `/wp-content/plugins/` directory
2. The path must look like this: `/wp-content/plugins/wp-social-share-privacy/`
3. Activate the plugin through the 'Plugins' menu in WordPress admin
4. Select socical networks and insert Facebook App-Id
5. to use the plugin, paste the code in your theme: &lt;?php if ( function_exists('socialshareprivacy') ) { socialshareprivacy(); } ?&gt;

Thanks for installing!

== Upgrade Notice ==
[German-Translation] - Rechtschreibfehler korrigiert, readme.txt Aktualisiert und Sprachdatei Englisch hinzugefügt.
[English-Translation] - Spelling error corrected, updated readme.txt and English language file added.
That's it

== Screenshots ==

1. Option Page for general Settings of the Plugin
2. Settings for Twitter Share Button
3. Output of the WP Social Share Privay Plugin

== Changelog ==
= 1.1.6 =
* This Update fixes a very important bug. If you change the text settings and open the dialog again, it does not show your 
text, but the default text again. Thanks to Bodo Tasche for this fix.
= 1.1.5 =
* Facebook App ID, just as they no longer needed to use the Like / Recommend button.
* Add the current version 1.2 of the script of jquery.socialshareprivacy from heise.de 
= 1.1.4 =
* small bug fix in language file
* Add the current version of the script of jquery.socialshareprivacy from heise.de 
= 1.1.3 =
* small bug fix in the CSS and JS inclusion in the options page 
* Adaptation of the JS code of "jquery.socialshareprivacy.js". $ Replaced by jQuery because of complications with other JavaScripts
= 1.1.2 =
* Corrects path in the plugin
= 1.1.1 =
* Typos corrected
* English language file added
* CSS error by integrating the jQuery plugin fixed   
= 1.1.0 =
* Page revised option, settings for general, Facebook, Twitter and Google in Tabs + packed.
* All text prepared for internationalization.
* All of the options socialshareprivacy scripts inserted into the options page.
= 1.0.0 =
* Release of the wp-social-share-privacy plugin

== Frequently Asked Questions ==
= Wo bekomm ich eine Facebook App-ID her? =
Für den „Empfehlen“-Button von Facebook benötigt man eine Facebook App-ID. Diese kann man sich mit seinem verifizierten Facebook-Konto auf den Developer-Seiten erzeugen.
    1. Einloggen bei Facebook
    2. Konto verifizieren mittels Handy-Nummer (oder Kreditkartendaten)
    https://www.facebook.com/settings?tab=mobile Option Handy-Nr.:
    Handy-Nr. eintragen und anschließend per SMS empfangenen Bestätigungscode in das Feld auf der rechten Seite eintragen.
    3. Entwickler-Seite aufrufen
    http://developers.facebook.com/docs/reference/plugins/like/
    Dort in der Box unter "Step 1" auf "Get Code" klicken und die App-ID aus dem angezeigten Code-Teil entnehmen.
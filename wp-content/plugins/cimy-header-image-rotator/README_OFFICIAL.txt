Cimy Header Image Rotator

Displays an image that automatically rotate depending on setting. You can setup from one second up to one day and more. Intermediate settings are also possible.

Supports:
 * static transition
 * fade transition
 * slide effect
 * Ken Burns effect
 * differentiated links from every picture
 * differentiated captions for every picture

BEFORE writing to me read carefully ALL the documentation AND the FAQ. Missing this step means you are wasting my time!
Bugs or suggestions can be mailed at: cimmino.marco@gmail.com


REQUIREMENTS:
PHP >= 5.0.0
WORDPRESS >= 3.2.x

INSTALLATION:
- copy whole cimy-header-image-rotator subdir into your plug-in directory and activate it
- create /wp-content/Cimy_Header_Images directory and set write permission for the web server

UPDATE FROM A PREVIOUS VERSION:
- always deactivate the plug-in and reactivate after the update


HOW TO USE IT:
1. first of all you need a directory where all images will be stored, the directory MUST HAVE this name: 'Cimy_Header_Images' and MUST BE placed under: "wp-content" dir
example: /wp-content/Cimy_Header_Images directory and give it 777 permissions if you are under Linux (or 770 and group to "www-data" if you are under Ubuntu Linux).
2. go to Settings -> Cimy Header Image Rotator
3. upload some images
4. change the swap rate as you wish
5. copy/paste the code you find in the same page to your favourite theme, where you want: inside header.php or sidebar.php etc.

HOW-TO VIDEO:
http://youtu.be/vpq4U3h1BPY

KNOWN ISSUES:
- none


FAQ:
Q: How can I use this plug-in with Twenty Ten/Eleven theme?

A: The best idea is to create a child theme indeed, someone already tried it: http://blog.duncanmoran.net/archives/1436


Q: How can I set the order of the pictures?

A: First rename the pictures like this: 01-pic, 02-pic, 03-pic and finally upload them.


Q: Why when I refresh the page then the rotation does not start from first picture even if shuffle images is turned off?

A: Because the plug-in calculates a time frame where every picture should be positioned based on the swap rate and the total amount of uploaded pictures and then this time frame is assigned to this picture in this moment.
Since v5.0.5 you can change this behaviour from the plug-in's options.


Q: Images never load, "Loading images..." stays always there, what happened?
A1: Did you install recently a plug-in? If yes try to isolate it, most probably it is its fault due to wrong inclusion of jQuery script. Its author should fix this referring to WordPress documentation: http://codex.wordpress.org/Function_Reference/wp_enqueue_script
A2: If did not help then probably you have selected Slide effect and you did not read that your images should be bigger than the div so: reduce the div dimensions and/or reduce the speed of this effect.
A3: If still did not help then probably you loaded too big images (the file size) and takes too long to load all of them.


Q: When feature XYZ will be added?

A: I don't know, remember that this is a 100% free project so answer is "When I have time and/or when someone help me with a donation".


Q: Can I help with a donation?

A: Sure, visit the donation page or contact me via e-mail.


Q: Can I hack this plug-in and hope to see my code in the next release?

A: For sure, this is just happened and can happen again if you write useful new features and good code. Try to see how I maintain the code and try to do the same (or even better of course), I have rules on how I write it, don't want "spaghetti code", I'm Italian and I want spaghetti only on my plate.
There is no guarantee that your patch will hit an official upcoming release of the plug-in, but feel free to do a fork of this project and distribute it, this is GPL!


Q1: I have found a bug what can I do?
Q2: Something does not work as expected, why?

A: The first thing is to download the latest version of the plug-in and see if you still have the same issue.
If yes please write me an email or write a comment but give as more details as you can, like:
- Plug-in version
- WordPress version
- MYSQL version
- PHP version
- exact error that is returned (if any)

after describe what you did, what you expected and what instead the plug-in did :)
Then the MOST important thing is: DO NOT DISAPPEAR!
A lot of times I cannot reproduce the problem and I need more details, so if you don't check my answer then 80% of the times bug (if any) will NOT BE FIXED!


CHANGELOG:
v6.1.1 - 29/07/2013
- Fixed Menu's dropdowns were hidden in some themes like Twenty Twelve (z-index is now set to 'auto', this may break IE7, if so I do not care, really)
- Fixed rotating headers may not work on certain themes, like: 'Modular' theme and 'Emporium' theme for WooCommerce
- Fixed default directory's permissions on Unix servers was set to 0777, now is handled by WordPress if FS_CHMOD_DIR is not set
- Added Slovak translation (Branco Radenovich - WebHostingGeeks.com)

v6.1.0 - 28/01/2013
- Added an option to let the image's size adapt on smaller form factors
- Fixed image deletion does not work for configurations different than the main one (regressed in v6.0.2) (thanks to carnini)
- Fixed non minified version of the JavaScript was not included when SCRIPT_DEBUG is set to true

v6.0.2 - 29/10/2012
- Added Double Fade option (works better with transparent backgrounds)

v6.0.1 - 17/09/2012
- Fixed captions/links per image were not working (regressed in v6.0.0) (thanks to m_disseny)

v6.0.0 - 10/09/2012
- Added possibility to use theme's default or uploaded pictures instead of the plug-in's uploaded pictures

v5.0.5 - 27/08/2012
- Added option to start the image sequence always from the first picture
- Fixed malicious/wrong image extensions are now renamed

v5.0.4 - 30/07/2012
- Fixed image extensions are now restricted to what WordPress allows
- Added Romanian translation (Web Geeks)

v5.0.3 - 30/01/2012
- Force proper options if user makes a wrong combo
- Added Swedish translation (Jon Wallsten)

v5.0.2 - 05/08/2011
- Fixed file deletion bug introduced in v5.0.0 (thanks to Annette Krznarich)
- Fixed file deletion vulnerability
- Updated Italian translation

v5.0.1 - 01/08/2011
- Fixed div id change in v5.0.0 for upgrading users

v5.0.0 - 01/08/2011
- Added multiple configurations support running at the same times
- Added possibility to choose to open links on new tab/window or not
- Fixed picture's uris to have proper https when in use (thanks to Jordan Applewhite)
- Code cleanup
- Updated Italian translation

v4.0.3 - 20/05/2011
- Fixed captions not displayed correctly when Fade effect is set to 0

v4.0.2 - 12/03/2011
- Fixed JavaScript inclusions to prevent conflicts with others plugins (thanks to Mike Sweitzer-Beckman and Dan)

v4.0.1 - 08/03/2011
- Added caption possibility for all images
- Do not allow upload of files with illegal characters in their name (thanks to Stuart Johnstone)

v4.0.0 - 23/02/2011
- Added slide effect
- Added Ken Burns effect
- Added link possibility for all images (thanks to Mark Buckshon for sponsoring it)
- Use minified JavaScript to save bandwidth
- Updated jquery.cross-slide.js to v0.6.2
- Updated Italian translation

v3.0.3 - 31/01/2011
- Fixed regression in v3.0.2 where plug-in was crashing the website (thanks to Yoni)
- Fixed PHP error on IE when no images are uploaded (thanks to Barbara)
- Fixed possible Html injection in the file names
- Fixed possible JS injection in the file names

v3.0.2 - 30/01/2011
- Added a different role to manage the plug-in (thanks to Matthew Cegielka for the patch)
- Added float attribute in the suggested code to make it more compatible with silly browsers (thanks to Helge)
- Fixed possible Html injection in the div id text field
- Fixed possible JS injection in the div id
- Fixed extra slash in the images path (thanks to Lloyd for the patch)

v3.0.1 - 28/09/2010
- Fixed JavaScript error when plugin is active, but is not meant to display anything (thanks to Jefferis Peterson)
- Fixed images loading order is now based on the settings as well (regression since 1.1.x)

v3.0.0 - 14/08/2010
- Added WordPress 3.0 MultiSite support
- Fixed directory creation and permission (thanks to Daniel Imhoff)
- Fixed sometimes images were not loaded
- Fixed images z-index to 1 (thanks to Michael G for the patch)

v2.0.2 - 22/05/2010
- Fixed plug-in initialization for silly IE when shuffle option is turned off (thanks to Favio Manríquez León for the patch)

v2.0.1 - 17/05/2010
- Fixed plug-in initialization for silly Internet Explorer

v2.0.0 - 09/05/2010
- Completely rewritten the JavaScript engine, now using jquery.cross-slide.js v0.3.8 by Tobia Conforto <tobia.conforto@gmail.com> (GPL v2 license)
  - Added possibility to fade between images (thanks to Brooke DeRam for sponsoring it and pointing the jquery script)
  - Fixed images rotation does not relocate things anymore

v1.1.1 - 11/03/2010
- Added example code to handle browsers that have JavaScript disabled.
- Fixed HTML code produced is now W3C compliant.

v1.1.0 - 04/03/2010
- Fixed Internet Explorer was flickering (thanks to Tony Devlin for the patch)
- Fixed not all pictures were shown in some cases (thanks to Paul C and to Wendi Carlock)
- Fixed switching off real-time rotation was not working (thanks to Robert)
- Renamed plug-in directory due to WordPress Plugin Directory rules

v1.0.0 - 09/02/2009
- Added image upload functionality
- Added possibility to translate the plug-in
- Added Italian translation
- Removed tons of get_files calls, they were only wasting cpu!
- Rewritten get_files function
- Code cleanup

v1.0.0 beta2 - 06/02/2009
- Added JavaScript to make rotation real-time for all browsers that has it enabled
- Added code example in the admin page to simplify the use of the plug-in
- Code cleanup

v1.0.0 beta1 (branched from v2.1 basic by Matthew Hough) - 28/01/2009
- Added image rotation every: second, minute, day
- Fixed options are written in one row only (no need to waste more rows)
- Fixed plug-in not working when 'wp-content' is not in the default place
- Removed 5 images limitation
- Code cleanup

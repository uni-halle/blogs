========
Contents
========
* Installing WordPress Themes
* Creating localized WordPress Themes with Artisteer
* Installing additional language support
* Editing translations

For more information please visit http://www.artisteer.com/?p=help_wordpress


*** Installing WordPress Themes
-------------------------------
1. Access your Web server using an FTP client or Web server administration tools.

2. Create a folder for your specific theme under "wp-content/themes" folder within WordPress installation. For example:
<WordPressFolder>\wp-content\themes\<YourThemeName>

3. Copy or upload theme files exported from Artisteer into the newly created <YourThemeName> folder.

4. Login to your WordPress administration panel and select Design -> Themes.

5. In the 'Available Themes' section click on your theme title or screenshot, then click the 'Activate Theme' link to activate it.

For more information please refer to the official WordPress documentation:
http://codex.wordpress.org/Using_Themes#Adding_New_Themes


*** Creating localized WordPress Themes with Artisteer
-------------------------------
When exporting themes Artisteer automatically includes several language files
obtained from Wordpress Website at http://codex.wordpress.org/WordPress_Localization
Specifically French, German, Italian and Spanish translations are exported by Artisteer in the Wordpress compliant file naming format:

<country>_<lang>.mo

For example German language file is: de_DE.mo

*** Installing additional language support
-------------------------------
To include a different or additional language support in your Worpdress theme
please find and copy the desired language files into your specific Wordpress theme folder.
You can find the additional language files in your default theme folder "<WordPressFolder>\wp-content\themes\default",
or on the Wordpress Website at http://codex.wordpress.org/WordPress_Localization

To select a different language than your current Wordpress language,
change the "define" function in the wp-config.php file, for example:
define('WPLANG', 'de_DE')


*** Editing translations
-------------------------------
To edit translations for your language please download and install
Codestyling Localization plug-in from http://wordpress.org/extend/plugins/codestyling-localization/
Then in WordPress administration select Manage -> Localization.